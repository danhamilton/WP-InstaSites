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

//TODO test single site installation
//TODO test to add/remove a property on app side
//TODO test the cron on the newly created website (with an already existing API key)

class Kigo_Network_Cron
{

	const CUSTOM_WP_IS_LARGE_NETWORK = 100000; //default (wp_is_large_network) value 10 000
	const ACTION_CRON = 'kigo_network_cron';
	const IS_PROCESSING_OPTION = 'kigo_cron_processing';
	
	static public $_wp_die_logs = array();
	static public $_sync_error_logs = array();
	
	
	public static function do_sync() {
		$debug_mode = defined( 'KIGO_DEBUG' ) && KIGO_DEBUG;
		
		// Do not allow to process the cron if the previous call is not finished
		if(
			!update_site_option( self::IS_PROCESSING_OPTION, self::IS_PROCESSING_OPTION ) // update_site_option() return true, only if the option has changed 
		) {
			self::$_sync_error_logs[] = 'Previous cron execution is not finished';
			self::handle_logs( $debug_mode );
			exit;
		}
		
		// Add our custom handler for wp_die() because some functions dies on error, and we don't want the script to die !
		add_filter( 'wp_die_ajax_handler', array( 'Kigo_Network_Cron', 'kigo_cron_wp_die_handler_filter' ) );
		
		$prevTimeTotal = microtime( true );
		if( is_multisite() ) {
			// Change the default value of wp_is_large_network necessary if # of sites reach the 10000
			add_filter( 'wp_is_large_network', array( 'Kigo_Network_Cron', 'custom_wp_is_large_network' ), 1, 3 );
			
			// Initialize the list of sites
			$sites = wp_get_sites( array( 'limit' => self::CUSTOM_WP_IS_LARGE_NETWORK, 'public' => 1 ) );
			shuffle( $sites );
			
			foreach( $sites as $site )
			{
				// Do not process on network blog
				if( 1 == $site[ 'blog_id' ] )  {
					continue;
				}
				
				 $prevTimeSite = microtime( true );
				// Even if the sync fail we don't stop execution, we just log it
				if(
					true !== ( $sync_respons = self::do_site_sync( $site[ 'blog_id' ] ) ) ||
					$debug_mode
				) {
					self::$_sync_error_logs[ $site[ 'blog_id' ] ][] = $sync_respons;
					self::$_sync_error_logs[ $site[ 'blog_id' ] ][ 'sync_exeuction_time' ] = ( microtime( true ) - $prevTimeSite );
				}
			}
		}
		else
		{
			if(
				true !== ( $sync_respons = self::do_site_sync( get_current_blog_id() ) ) ||
				$debug_mode
			) {
				self::$_sync_error_logs[] = $sync_respons;
			}
		}
		
		if( $debug_mode ) {
			self::$_sync_error_logs[ 'total_execution_time' ] = ( microtime( true ) - $prevTimeTotal );
		}
		
		// Set the cron state as finished
		delete_site_option( self::IS_PROCESSING_OPTION );
		
		// Echo the logs in debug mode or send them by mail
		self::handle_logs( $debug_mode );
		exit;
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
	 * Do the sync process for a site/blog.
	 * 
	 * @param $blog_id
	 *
	 * @return array|bool
	 */
	private static function do_site_sync( $blog_id ) {
		switch_to_blog( $blog_id );
		$site_cron = new Kigo_Site_Cron( $blog_id );
		return $site_cron->sync_entities() ? true : $site_cron->_errors;
	}

	/**
	 * Handle the errors log if any
	 * 
	 * @param $debug_mode
	 */
	private static function handle_logs( $debug_mode ) {
		if( count( $logs = array_merge( self::$_sync_error_logs, self::$_wp_die_logs ) ) ) {
			if( $debug_mode )
				echo json_encode( $logs );
			//else
				// TODO: shouldn't we save the logs in a WP table or anywhere to be able to debug if needed ?
		}
	}
}


/**
 * Class Kigo_Site_Cron
 */
class Kigo_Site_Cron
{
	const KIGO_CRON_DIFF_OPTION = 'kigo_cron_diff';
	
	public $_errors = array();
	
	private $_blog_id;
	private $_api_key;
	private $_bapi;
	private $_entity_diff_meth_ids;
	
	// To add an entity to the cron sync please add it bellow
	private $_default_entity_diff_meth_ids = array(
		//'entity'	=> array( 'diff_method'		=> <default_diff_id>
		'property'	=> array( 'pricingdiffid'	=> -1 )
	);
	
	
	public function __construct( $blog_id )
	{
		// Reload all option to correspond to the right site/blog
		bapi_wp_site_options();
		$this->_blog_id = $blog_id;
		$this->_api_key = getbapiapikey();
		$this->_bapi = getBAPIObj();
		
		// Get the stored diff ids/methods for each entity and merge it with the default (allow to add entity in future)
		if( is_array( $entity_diff_meth_ids = json_decode( get_option( self::KIGO_CRON_DIFF_OPTION ), true ) ) ) {
			$this->_entity_diff_meth_ids = array_merge( $this->_default_entity_diff_meth_ids, $entity_diff_meth_ids );
		}
		else {
			$this->_entity_diff_meth_ids = $this->_default_entity_diff_meth_ids;
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
			$this->log_error( 0, 'Invalide API key' );
			return false;
		}
		
		foreach( $this->_entity_diff_meth_ids as $entity => $options ) {
			// In case of error propagate, the error, don't update the diff_id and continue with the next entity
			
			// Call the diff method to get the changed entity's ids
			if( !is_array( $ids_to_update = $this->get_entity_diff( $entity, $options, $new_diff_id ) ) ) {
				$this->log_error( 1, 'Unable to process diff method', array( 'entity' => $entity ) );
				continue;
			}
			
			// Initialize the "cache" for get call (this reduce the number of calls by doing bulk calls of ids and caching the result
			$options = array();// Taken from getMustache() function
			if( $entity == "property" ){
				$options = array( "seo" => 1, "descrip" => 1, "avail" => 1, "rates" => 1, "reviews" => 1, "poi" => 1 );
			}
			else if($entity == "poi") {// Taken from getMustache() function
				$options = array( "nearbyprops" => 1, "seo" => 1 );
			}
			
			if( !$this->_bapi->init_get_cache( $entity, $ids_to_update, $options ) ) {
				$this->log_error( 2, 'Unable to initialize the cache data', array( 'entity' => $entity, 'ids' => $ids_to_update, 'options' => $options ) );
				continue;
			}
			
			foreach( $ids_to_update as $id ) {
				
				if( !is_array( $seo = $this->get_seo_from_bapi_cache( $entity, $id ) ) ) {
					$this->log_error( 3, 'Unable to retrieve the SEO', array( 'entity' => $entity, 'entity_id' => $id ) );
					continue 2;
				}
				
				// The value of post is not tested because kigo_sync_entity will handle Wp_post and null values
				$post = get_page_by_path( BAPISync::cleanurl( $seo[ "DetailURL" ] ) );
				
				if( !kigo_sync_entity( $post, $seo, true ) ) {
					$this->log_error( 4, 'Unable to process the sync', array( 'entity' => $entity, 'entity_id' => $id, 'SEO' => $seo ) );
					continue 2;
				}
			}
			
			// If this point is reached that means the the sync has been done without error, we can update the diff_id
			$this->_entity_diff_meth_ids[ $entity ][ key( $options ) ] = $new_diff_id;
		}
		
		if(
			!is_string( $json_entity_diff_meth_ids = json_encode( $this->_entity_diff_meth_ids ) ) ||
			(// update_option() returns false in case of failure AND when nothing has changed !
				!update_option( self::KIGO_CRON_DIFF_OPTION, $json_entity_diff_meth_ids ) &&
				$json_entity_diff_meth_ids !== get_option( self::KIGO_CRON_DIFF_OPTION )
			)
		) {
			$this->log_error( 5, 'Unable to update the option', array( 'entity_diff_meth_ids' => $this->_entity_diff_meth_ids ) );
		}
		
		return ( 0 === count( $this->_errors ) );
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