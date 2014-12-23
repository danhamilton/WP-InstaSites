<?php

class BAPI
{
	const BAPI_USER_AGENT = 'InstaSites Agent';
	const WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';
	const MAX_NB_BULK_GET_IDS = 20; // This value can not be higher than 20 by restriction of the app.

	public $apikey;
	public $language = 'en-US';
    public $currency = 'USD';
    public $baseURL = 'connect.bookt.com'; 
	public $getopts = array(
		'http'=>array(
			'method'=>"GET",
			'header'=>''
		)
	);
	
	public  $cache_get_call = array();
	private $use_cache_in_get_calls = false;
	
	public function __construct(
			$apikey, 
			$language="en-US", 
			$baseURL='connect.bookt.com'
	) {
		$getopts=array('http'=>array('method'=>"GET",'header'=>"User-Agent: InstaSites Agent\r\nReferer: http://" . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI] . "\r\n"));
		$this->apikey = $apikey;
		$this->language = $language;
		$this->baseURL = $baseURL;
		$this->getOptions = stream_context_create($getopts);
	}
	
	public function isvalid() {
		if (empty($this->apikey)) return false;
		if (empty($this->baseURL)) return false;
		return true;
	}
	
	public function getBaseURL() {
		if (!strncmp("localhost", $this->baseURL, strlen("localhost"))) {
			return "http://" . $this->baseURL;
		} else {
			return "https://" . $this->baseURL;
		}
	}
		
	public function getcontext($jsondecode,$debugmode=0) {
		if (!$this->isvalid()) { return null; }
		$url = $this->getBaseURL() . '/js/bapi.context?apikey=' . $this->apikey;
		$c = file_get_contents($url,FALSE,$this->getOptions) or wp_die('Error Retrieving Context','Oops!');
		
		global $getContextURL;
		$getContextURL = $url;
		add_action('wp_head','bapi_add_context_meta',1);	
		
		$res = json_decode($c,TRUE);
		return $res;
	}
	
	public function gettextdata($jsondecode,$debugmode=0) {
		if (!$this->isvalid()) { return null; }
		$url = $this->getBaseURL() . '/ws/?method=get&entity=textdata&apikey=' . $this->apikey;
		$c = file_get_contents($url,FALSE,$this->getOptions) or wp_die('Error Retrieving TextData','Oops!');
		
		global $textDataURL;
		$textDataURL = $url;
		add_action('wp_head','bapi_add_textdata_meta',1);	
		
		if ($jsondecode) {return json_decode($c,TRUE); }
		return $c;
	}
	
	public function getseodata($jsondecode=true,$debugmode=0) {
		if (!$this->isvalid()) { return null; }
		$url = $this->getBaseURL() . '/ws/?method=get&entity=seo&apikey=' . $this->apikey;
		$c = file_get_contents($url,FALSE,$this->getOptions) or wp_die('Error Retrieving Keywords','Oops!');
		
		global $seoDataURL;
		$seoDataURL = $url;
		add_action('wp_head','bapi_add_seo_meta',1);	
		
		if ($jsondecode) {return json_decode($c,TRUE); }
		return $c;
	}
	
	public function getSolutionConfig($apikey,$debugmode=0){
		$url = $this->getBaseURL() . "/ws/?method=getconfig&apikey=".$apikey;
		$json = file_get_contents($url,FALSE,$this->getOptions) or wp_die('Error Retrieving Solution Config','Oops!');
		$data = json_decode($json, TRUE);
		if($data['status']==1){
			return($data['result']);
		}
		else{
			return(false);
		}
	}
	
	public function search($entity,$options=null,$jsondecode=true) {
		if (!$this->isvalid()) { return null; }
		$url = $this->getBaseURL() . "/ws/?method=search&apikey=" . $this->apikey . "&entity=" . $entity;
		if (!empty($options)) { $url = $url . "&" . http_build_query($options); }		
		$c = file_get_contents($url,FALSE,$this->getOptions) or wp_die('Error Retrieving Search Results','Oops!');		
		if (empty($jsondecode) || $jsondecode) { return json_decode($c,TRUE); }
		return $c;
	}
	public function quicksearch($entity,$options=null,$jsondecode=true) {
		if (!$this->isvalid()) { return null; }
		$url = $this->getBaseURL() . "/ws/?method=quicksearch&apikey=" . $this->apikey . "&entity=" . $entity;
		if (!empty($options)) { $url = $url . "&" . http_build_query($options); }		
		$c = file_get_contents($url,FALSE,$this->getOptions) or wp_die('Error Retrieving Search Results','Oops!');		
		if (empty($jsondecode) || $jsondecode) { return json_decode($c,TRUE); }
		return $c;
	}
	
	/**
	 * Generate a bulk call to the function get with an array of ids.
	 * This allow to reduce the number of call to Kigo app
	 * 
	 * @param $entity string
	 * @param $ids array
	 * @param null $options array default value null
	 *
	 * @return bool
	 */
	public function init_get_cache( $entity, $ids, $options = null ) {
		// Split the ids into small chunks to avoid errors
		$ids_chunks = array_chunk( $ids, self::MAX_NB_BULK_GET_IDS );
		
		// Set the page size option to receive the correct amount of results
		$options = array_merge( $options, array( 'pagesize' => self::MAX_NB_BULK_GET_IDS ) );
		
		// Process one call by chunks
		foreach( $ids_chunks as $ids_chunk ) {
			if(
				!is_array( $response = $this->get( $entity, $ids_chunk, $options, true ) ) ||
				
				!isset( $response[ 'status' ] ) ||
				1 !== $response[ 'status' ] ||
				
				!isset( $response[ 'result' ] ) ||
				!is_array( $response[ 'result' ] ) ||
				
				count( $response[ 'result' ] ) !== count( $ids_chunk )
			) {
				return false;
			}
			
			foreach( $response[ 'result' ] as $result ) {
				$this->cache_get_call[ $entity ][ $result[ 'ID' ] ] = $result;
			}
		}
		
		// Set to use the cache only if all the calls have been successful 
		$this->use_cache_in_get_calls = true;
		
		return true;
	}
	
	public function get($entity,$ids,$options=null,$jsondecode=true,$debugmode=0) {
		if (!$this->isvalid()) { return null; }
		
		// In case init_get_cache() has been called before, try to retrieve the values from the local cache
		if( $this->use_cache_in_get_calls ) {
			$fake_response = array(
				'status'	=> 1,
				'result'	=> array()
			);
			
			$error = false;
			foreach( $ids as $id ) {
				if( !isset( $this->cache_get_call[ $entity ][ $id ] ) ) {
					$error = true;
				}
				$fake_response[ 'result' ][] = $this->cache_get_call[ $entity ][ $id ];
			}
			
			if( !$error ) { // In case of error, retrieving one or more id from the cache, default to the get call.
				return $jsondecode ? $fake_response : json_encode( $fake_response );
			}
		}
		
		$url = $this->getBaseURL() . "/ws/?method=get&apikey=" . $this->apikey . "&entity=" . $entity . '&ids=' . implode(",", $ids);
		if (!empty($options)) { $url = $url . "&" . http_build_query($options); }
		
		global $entityUpdateURL;
		$entityUpdateURL = $url;
		add_action('wp_head','bapi_add_entity_meta',1);	
		
		$c = file_get_contents($url,FALSE,$this->getOptions) or wp_die('Error Retrieving Entity Data','Oops!');
		if (empty($jsondecode) || $jsondecode) { return json_decode($c,TRUE); }
		return $c;
	}	
	
	public function del($entity,$ids,$options,$jsondecode=true) {
		if (!$this->isvalid()) { return null; }
		$optionsURL = implode("&", $options);
		$url = $this->getBaseURL . "/ws/?method=get&apikey=" . $this->apikey . "&entity=" . $entity . '&ids' . implode(",", $ids);
		echo $url; exit();
		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);
		return $data;		
		return null;
	}
	//erro testing for send objects to the api
	public function error($response){
		if( is_wp_error( $response ) ) {
   		$error_message = $response->get_error_message();
   		echo "Something went wrong: $error_message";
		echo $response;
		} else {
	   		echo "Something went right";
			echo $response;
 		}
	}
	//saves to our api
	public function save($jsonObj, $apiKey) {
		if (!$this->isvalid()) { return null; }
		$url =$this->getBaseURL()."/ws/?method=save&apikey=".$apiKey."&entity=seo";
		//print_r($url); exit();
		$response = wp_remote_post( $url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array('content-type'=>'application/x-www-form-urlencoded','User-Agent'=>'InstaSites Agent'),
			'body' => $jsonObj,
			'cookies' => array()
    	)
		);
		if( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo "Something went wrong: $error_message";
			echo $response;
		} else {
			 // print_r($jsonObj);
	   		 // print_r($response);
			 // exit();
 		}
		
	}
}
?>
