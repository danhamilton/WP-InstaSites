<?php

/**
 * Class Kigo_Network_Cron
 * 
 * Class containing the static methods needed to launch a sync by cron.
 * 
 * The sync can be triggered by calling <domain>/wp-admin/admin-ajax.php?action=kigo_network_cron
 * WARMING: The sync endpoint can only be called when not logged in !
 * 
 * In case of a multi-sites installation, the sync can be triggered on any domain (or sub domain) 
 * and will do the sync for any website of the installation.
 * 
 * The Sync "lock" the execution of an other sync until the previous one is finished.
 * 
 */
class Kigo_Network_Cron
{

	const CUSTOM_WP_IS_LARGE_NETWORK	= 100000; //default (wp_is_large_network) value 10 000

	const CURL_TIMEOUT					= 300;
	const CURL_PARALLEL_CALLS			= 10;

	const ACTION_CRON					= 'kigo_network_cron';
	const ACTION_SITE_CRON				= 'kigo_site_cron';
	const GET_PARAM_FORCED_SYNC			= 'force_full_sync';

	const ADV_LOCK_PROCESSING			= 'KIGO_CRON_LOCK';
	const LOGGLY_TAG					= 'wp_cron_sync';
	
	static public $_wp_die_logs = array();
	static public $_sync_error_logs = array();
	
	
	public static function do_sync() {
		global $wpdb;

		$debug_mode = defined( 'KIGO_DEBUG' ) && KIGO_DEBUG;

		// Ensure that no other cron will run concurrently by acquiring an advisory lock (at MySQL database)
		if( ! $wpdb->get_var( $wpdb->prepare( 'SELECT GET_LOCK(%s, 0)', self::ADV_LOCK_PROCESSING ) ) ) {
			self::$_sync_error_logs[] = 'Previous cron execution is not finished, could not acquire cron lock';
			self::handle_logs( $debug_mode );
			exit;
		}

		$prevTimeTotal = microtime( true );
		if( is_multisite() ) {
			require_once( dirname( __FILE__ ) . '/ext/class-zebra-curl.php' );
			
			// Change the default value of wp_is_large_network necessary if # of sites reach the 10000
			add_filter( 'wp_is_large_network', array( 'Kigo_Network_Cron', 'custom_wp_is_large_network' ), 1, 3 );
			
			// Initialize the list of sites
			$sites = wp_get_sites( array( 'limit' => self::CUSTOM_WP_IS_LARGE_NETWORK, 'public' => 1, 'deleted' => 0, 'archived' => 0 ) );
			shuffle( $sites );
			
			if( $debug_mode )
				self::$_sync_error_logs[ 'nb_sites' ] = count( $sites );
			
			//Do the Zebra cURL call (asynchronous calls)
			$curl = new Zebra_cURL();
			$curl->option( CURLOPT_TIMEOUT, self::CURL_TIMEOUT );
			$curl->threads = self::CURL_PARALLEL_CALLS;
				
			//Prepare URLs to be called
			$urls = array_map( array( 'Kigo_Network_Cron', 'generate_curl_urls' ), $sites );
			$urls = array_filter( $urls, function( $url ) { return is_string( $url ); } );
			
			$curl->get( $urls, array( 'Kigo_Network_Cron', 'zebra_curl_callback' ) );
		}
		else
		{
			set_error_handler( array( 'Kigo_Network_Cron', 'php_error_handler' ) );
			// Add our custom handler for wp_die() because some functions die on error, and we don't want the script to die !
			add_filter( 'wp_die_ajax_handler', array( 'Kigo_Network_Cron', 'kigo_cron_wp_die_handler_filter' ) );
			
			$site_cron = new Kigo_Site_Cron();
			self::$_sync_error_logs[] = $site_cron->sync_entities() ? true : $site_cron->_errors;
			
			restore_error_handler();
		}
		
		if( $debug_mode ) {
			self::$_sync_error_logs[ 'total_execution_time' ] = ( microtime( true ) - $prevTimeTotal );
		}

		if( ! $wpdb->query( $wpdb->prepare( 'SELECT RELEASE_LOCK(%s)', self::ADV_LOCK_PROCESSING ) ) ) {
			self::$_sync_error_logs[] = 'Could not release cron lock';
		}

		// Echo the logs in debug mode or send them by mail
		self::handle_logs( $debug_mode );
		exit;
	}
	
	/**
	 * Network only: Callback called each time a website cron is finished
	 * 
	 * @param $result
	 */
	public static function zebra_curl_callback( $result ) {
		// cURL error
		if( 
			CURLE_OK !== $result->response[1] ||
			200 !== $result->info['http_code']
		) {
			Kigo_Network_Cron::$_sync_error_logs[] = $result;
			return;
		}
		
		if( true === ( $body = json_decode( $result->body ) ) ) {
			return;
		}
		
		if( !is_array( $body ) ) {
			Kigo_Network_Cron::$_sync_error_logs[] = array( $result->info, $body );
			return;
		}
		
		Kigo_Network_Cron::$_sync_error_logs[] = $body;
	}
	
	/**
	 * Filter to increase the number of sites/blog wp_get_sites will accept to return
	 * 
	 * @param $prevRet	not used
	 * @param $using	not used
	 * @param $count	number of blogs/sites in the network
	 *
	 * @return bool
	 */
	public static function custom_wp_is_large_network( $prevRet, $using, $count ) {
		return $count > self::CUSTOM_WP_IS_LARGE_NETWORK;
	}

	/**
	 * Add a filter returning the function to execute on wp_die()
	 * This is needed because BAPI functions dies on error, and the cron shouldn't stop on error.
	 * 
	 * @return array function to execute on wp_die
	 */
	public static function kigo_cron_wp_die_handler_filter() {
		return array( 'Kigo_Network_Cron', 'kigo_cron_wp_die_handler' );
	}

	/**
	 * Action to execute on wp_die(), overwrite the default behaviour.
	 * This is needed because BAPI functions dies on error, and the cron shouldn't stop on error.
	 * 
	 * @param $message
	 * @param string $title
	 * @param array $args
	 */
	public static function kigo_cron_wp_die_handler( $message, $title = '', $args = array() ) {
		self::$_wp_die_logs[] = array(
			'message'	=> $message,
			'title'		=> $title,
			'args'		=> $args
		);
	}

	/**
	 * Do the sync process for a site/blog call remotely only.
	 * 
	 * @return array|bool
	 */
	public static function do_site_sync() {
		// Do not let the sync happen on the network blog
		if( BLOG_ID_CURRENT_SITE == get_current_blog_id() ) {
			header('Content-type: application/json');
			echo json_encode( false );
			exit;
		}

		set_error_handler( array( 'Kigo_Network_Cron', 'php_error_handler' ) );
		// Add our custom handler for wp_die() because some functions die on error, and we don't want the script to die !
		add_filter( 'wp_die_ajax_handler', array( 'Kigo_Network_Cron', 'kigo_cron_wp_die_handler_filter' ) );
		
		$site_cron = new Kigo_Site_Cron( isset( $_GET[ self::GET_PARAM_FORCED_SYNC ] ) && $_GET[ self::GET_PARAM_FORCED_SYNC ] == 1 );
		$ret = $site_cron->sync_entities() ? true : $site_cron->_errors;
		
		restore_error_handler();
		
		header('Content-type: application/json');
		echo json_encode( $ret );
		exit;
	}
	
	public static function php_error_handler( $code, $msg, $file, $line ) {
		Kigo_Network_Cron::$_sync_error_logs[] = array(
			'code'	=> $code,
			'msg'	=> $msg,
			'file'	=> $file,
			'line'	=> $line
		);
	}

	/**
	 * Helper to generate the URL to be called to sync a site. Called only if in a multi-site context.
	 * 
	 * @param $blog
	 *
	 * @return null|string
	 */
	private static function generate_curl_urls( $blog ) {
		// Do not process on network blog
		if(
			BLOG_ID_CURRENT_SITE != $blog[ 'site_id' ] ||
			BLOG_ID_CURRENT_SITE == $blog[ 'blog_id' ] ||
			!is_string( $blog[ 'domain' ] )
		)  {
			return null; // This will be removed by the filter
		}
		
		return $blog[ 'domain' ] . '/wp-admin/admin-ajax.php?' . http_build_query( array( 'action' => self::ACTION_SITE_CRON ) );
	}
	
	/**
	 * Handle the errors log if any
	 * 
	 * @param $debug_mode
	 */
	private static function handle_logs( $echo_logs = false ) {
		if( count( $logs = array_merge( self::$_sync_error_logs, self::$_wp_die_logs ) ) ) {
			if( $echo_logs ){
				header('Content-type: application/json');
				echo json_encode( $logs );
			}
			else
				Loggly_logs::log( $logs, array( self::LOGGLY_TAG ) );
		}
	}
}


/**
 * Class Kigo_Site_Cron
 */
class Kigo_Site_Cron
{
	const KIGO_CRON_DIFF_OPTION			= 'kigo_cron_diff';
	const ACTION_GET_LAST_CRON_EXEC		= 'kigo_get_last_cron_execution';
	
	const MAX_SOLUTION_DATA_AGE			= 8035200; // If the solution's data from a websites hasn't been updated since 3 months we don't call the diff method. (This is done to prevent executing the cron on legacy sites)
	
	public $_errors = array();
	
	private $_blog_id;
	private $_api_key;
	private $_bapi;
	private $_entity_diff_meth_ids;
	
	// To add an entity to the cron sync please add it bellow
	private $_default_entity_diff_meth_ids = array(
		//'entity'	=> array( 'diff_method_name' => <diff_method_name>, 'diff_id' => <diff_id>, 'last_update_timestamp' = 0 )
		'property'	=> array(
						'diff_method_name'		=> 'pricingdiffid',
						'diff_id'				=> -1,
						'last_update_timestamp'	=> 0,
						'first_cron_execution'	=> null
					)
	);
	
	
	public function __construct( $force_full_sync = false )
	{
		// Reload all option to correspond to the right site/blog
		bapi_wp_site_options();
		$this->_blog_id = get_current_blog_id();
		$this->_api_key = getbapiapikey();
		$this->_bapi = getBAPIObj();

		// Get the stored diff ids/methods for each entity and merge it with the default (allow to add entity in future)
		if(
			$force_full_sync ||
			!is_array( $entity_diff_meth_ids = json_decode( get_option( self::KIGO_CRON_DIFF_OPTION ), true ) )
		) {
			$this->_entity_diff_meth_ids = $this->_default_entity_diff_meth_ids;
			
			// Special case: if cron has never been executed and full sync is asked we need to set first_cron_execution = 1 otherwise it wont actually sync
			if( $force_full_sync ) {
				foreach( $this->_default_entity_diff_meth_ids as $entity => $info ) {
					$this->_entity_diff_meth_ids[ $entity ][ 'first_cron_execution' ] = 1;
				}
			}
		}
		else {
			$this->_entity_diff_meth_ids = array_merge( $this->_default_entity_diff_meth_ids, $entity_diff_meth_ids );
		}
	}
	
	/**
	 * Loop on each syncable entities present in $_default_entity_diff_meth_ids, call the diff method associated and do the sync on each entity 
	 * In case of error (return false), error information can be found in $this->_errors array.
	 * 
	 * @return bool
	 */
	public function sync_entities() {
		if(
			!is_string( $this->_api_key ) ||
			!strlen( $this->_api_key )
		) {
			$this->log_error( 0, 'Invalid API key' );
			return false;
		}
		
		if(
			is_string( $bapi_solutiondata_lastmod = get_option( 'bapi_solutiondata_lastmod', null ) ) &&
			$bapi_solutiondata_lastmod < ( time() - self::MAX_SOLUTION_DATA_AGE )
		) {
			$this->log_error( 0, 'Solution data not updated since: ' . date( 'c', intval( $bapi_solutiondata_lastmod ) ) );
			return false;
		}
		
		foreach( $this->_entity_diff_meth_ids as $entity => $options ) {
			// In case of error propagate, the error, don't update the diff_id and continue with the next entity
			
			// Call the diff method to get the changed entity's ids
			if( !is_array( $ids_to_update = $this->get_entity_diff( $entity, array( $options[ 'diff_method_name' ] => $options[ 'diff_id' ] ), $new_diff_id ) ) ) {
				$this->log_error( 1, 'Unable to process diff method', array( 'entity' => $entity, $options[ 'diff_method_name' ] => $options[ 'diff_id' ] ) );
				continue;
			}
			
			// First time the cron is executed, we just save the returned diff id and the first execution timestamp, without syncing anything 
			if( null === $this->_entity_diff_meth_ids[ $entity ][ 'first_cron_execution' ] ) {
				$this->_entity_diff_meth_ids[ $entity ][ 'diff_id' ] = $new_diff_id;
				$this->_entity_diff_meth_ids[ $entity ][ 'first_cron_execution' ] = time();
				continue;
			}
			
			if( count( $ids_to_update ) > 0 ) {
				// Initialize the "cache" for get call (this reduce the number of calls by doing bulk calls of ids and caching the result
				$cache_options = array();// Taken from getMustache() function
				if( $entity == "property" ){
					$cache_options = array( "seo" => 1, "descrip" => 1, "avail" => 1, "rates" => 1, "reviews" => 1, "poi" => 1 );
				}
				else if($entity == "poi") {// Taken from getMustache() function
					$cache_options = array( "nearbyprops" => 1, "seo" => 1 );
				}
				
				// Initialize the "cache" of get calls, the return value is not checked because if it didn't worked, then get calls won't use the cache.
				$this->_bapi->init_get_cache( $entity, $ids_to_update, $cache_options );
				
				foreach( $ids_to_update as $id ) {
					
					if( !is_array( $seo = $this->get_seo_from_bapi_cache( $entity, $id ) ) ) {
						$this->log_error( 3, 'Unable to retrieve the SEO', array( 'entity' => $entity, 'entity_id' => $id ) );
						continue 2;
					}
					
					if( !is_a( ( $post = get_page_by_path( BAPISync::cleanurl( $seo[ "DetailURL" ] ) ) ), 'WP_Post' ) ) {
						continue 1;
					}
					
					if( !kigo_sync_entity( $post, $seo, true ) ) {
						$this->log_error( 4, 'Unable to process the sync', array( 'entity' => $entity, 'entity_id' => $id, 'SEO' => $seo ) );
						continue 2;
					}
				}
			}
			
			// If this point is reached that means the sync has been done without error, we can update the diff_id and save the timestamp
			$this->_entity_diff_meth_ids[ $entity ][ 'diff_id' ] = $new_diff_id;
			$this->_entity_diff_meth_ids[ $entity ][ 'last_update_timestamp' ] = time();
		}
		
		if( //If there were an error before, we don't want to update the option
			!count( $this->_errors ) &&
			(
				!is_string( $json_entity_diff_meth_ids = json_encode( $this->_entity_diff_meth_ids ) ) ||
				!update_option( self::KIGO_CRON_DIFF_OPTION, $json_entity_diff_meth_ids )
			)
		) {
			$this->log_error( 5, 'Unable to update the option', array( 'entity_diff_meth_ids' => $this->_entity_diff_meth_ids ) );
		}
		
		return ( 0 === count( $this->_errors ) );
	}
	
	public static function get_cron_info_option( $entity ) {
		if(
			!is_array( $entity_diff_meth_ids = json_decode( get_option( self::KIGO_CRON_DIFF_OPTION ), true ) ) ||
			
			!isset( $entity_diff_meth_ids[ $entity ] ) ||
			!is_array( $entity_diff_meth_ids[ $entity ] ) ||
			
			!isset( $entity_diff_meth_ids[ $entity ][ 'diff_method_name' ] ) ||
			!isset( $entity_diff_meth_ids[ $entity ][ 'diff_id' ] ) ||
			!isset( $entity_diff_meth_ids[ $entity ][ 'last_update_timestamp' ] ) ||
			!isset( $entity_diff_meth_ids[ $entity ][ 'first_cron_execution' ] )
		) {
			return null;
		}
		
		return $entity_diff_meth_ids[ $entity ];
	}
	
	public static function get_interval_last_update_prop() {

		if( !is_array( $cron_info = Kigo_Site_Cron::get_cron_info_option( 'property' ) ) ) {
			echo json_encode( array( 'success' => false ) );
			exit();
		}
		
		if(
			0 === ( $last_update_timestamp = $cron_info[ 'last_update_timestamp' ] ) ||
			( time() - $last_update_timestamp ) > 3600  //No sync since one hour
		) {
				echo json_encode( array(
					'success'	=> true,
					'too_much'	=> true
				) );
				exit();
		}
		
		$last_update = new DateTime();
		$last_update->setTimestamp( $last_update_timestamp );
		$now = new DateTime();
		$interval = $now->diff($last_update);
		
		echo json_encode( array(
			'success'	=> true,
			'too_much'	=> false,
			'formated'	=> $interval->format( '%i minute(s) %s second(s)' )
		) );
		exit();
	}

	/**
	 * Call the diff method for a given entity.
	 * Return an array containing the ids that need to be synced.
	 * 
	 * @param $entity
	 * @param $options			Array( <dif_method> => <previous_diff_id> )
	 * @param &$new_diff_id		Variable passed by reference: Will contain the new diff ID once the diff method is successfully called
	 *
	 * @return array|null
	 */
	private function get_entity_diff( $entity, $options, &$new_diff_id ) {
		if(
			!$this->_bapi->isvalid() ||
			
			!is_array( $deff_result = $this->_bapi->search( $entity, $options, true ) ) ||
			
			!isset( $deff_result[ 'status' ] ) ||
			1 !== $deff_result[ 'status' ] ||
			
			!isset( $deff_result[ 'result' ] ) ||
			!is_array( $ids_to_update = $deff_result[ 'result' ] ) ||
			
			!isset( $deff_result[ 'retparams' ] ) ||
			!is_numeric( $new_diff_id = $deff_result[ 'retparams' ][ 'diffid' ] )
		) {
			return null;
		}
		return $ids_to_update;
	}

	/**
	 * Return the SEO from the bapi cached data.
	 * Warning: the param seo=1 has to be passed to the call that init the cache.
	 * 
	 * @param $entity
	 * @param $id
	 *
	 * @return array|null
	 */
	private function get_seo_from_bapi_cache( $entity, $id ) {
		if(
			!isset( $this->_bapi->cache_get_call[ $entity ][ $id ] ) ||
			!is_array( $this->_bapi->cache_get_call[ $entity ][ $id ] ) ||
			
			!isset( $this->_bapi->cache_get_call[ $entity ][ $id ][ 'ContextData' ] ) ||
			!is_array( $this->_bapi->cache_get_call[ $entity ][ $id ][ 'ContextData' ] ) ||
			
			!isset( $this->_bapi->cache_get_call[ $entity ][ $id ][ 'ContextData' ][ 'SEO' ] ) ||
			!is_array( $seo = $this->_bapi->cache_get_call[ $entity ][ $id ][ 'ContextData' ][ 'SEO' ] )
		) {
			return null;
		}
		
		// For some weird reason pkid and entity are not set to property when received with the property info
		if( empty( $seo[ 'pkid' ] ) ) {
			$seo[ 'pkid' ] = $id;
		}
		if( empty( $seo[ 'entity' ] ) ) {
			$seo[ 'entity' ] = $entity;
		}
		
		return $seo;
	}

	/**
	 * @param $code
	 * @param string $msg
	 * @param null $blog_id
	 * @param array $options
	 */
	private function log_error( $code, $msg = '', $options = array() ) {
		$this->_errors[] = array(
			'code'		=> $code,
			'message'	=> $msg,
			'blog_id'	=> $this->_blog_id,
			'api_key'	=> $this->_api_key,
			'options'	=> $options
		);
	}
}
