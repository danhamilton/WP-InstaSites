<?php

class BAPI
{
	const BAPI_USER_AGENT = 'InstaSites Agent';
	const WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

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

	// [] | true (not found) | false (error)
	public function get($entity, $ids, $options=null)
	{
		$url = $this->getBaseURL() . "/ws/?method=get&apikey=" . $this->apikey . "&entity=" . $entity . '&ids=' . implode(",", $ids);
		if (!empty($options)) { $url = $url . "&" . http_build_query($options); }

		// adding <meta> on the page, for debugging purposes
		global $entityUpdateURL;
		$entityUpdateURL = $url;
		add_action('wp_head', 'bapi_add_entity_meta', 1);

		$response = wp_remote_get(
			$url,
			array(
				'sslverify'	=>	!(defined('KIGO_DEBUG') && KIGO_DEBUG),
				'timeout'	=>	50,
				'headers'	=>	array( 'User-Agent' => 'InstaSites Agent' )
			)
		);

		if(is_wp_error($response)) {
			error_log( $response->get_error_message() );
			return false;
		}

		if(
			is_array($response) &&

			isset($response['response']) &&
			is_array($response['response']) &&

		    isset($response['response']['code']) &&
			$response['response']['code'] == 200 &&

			isset($response['body']) &&
		    is_string($response['body'])
		) {
			// BAPI also returns 200 when there are problems. So if the entity doesn't seem to be correctly retrieved, consider it a resource not found (404)
			if(
				!self::json_decode($decoded, $response['body']) ||

				isset($decoded['error']) ||

				!isset($decoded['status']) ||
					$decoded['status'] != '1' ||

				!isset($decoded['result']) ||
					!is_array($decoded['result'])
			) {
				return true; // "not found"
			}

			return $decoded;
		}

		return false;
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
			'cookies' => array(),
			'sslverify' => !( defined( 'KIGO_DEBUG' ) && KIGO_DEBUG ) // in dev mode, allow self-signed certs
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

	static private function json_decode(&$decoded, $json, $assoc=true)
	{
		if(!is_string($json) || !strlen($json))
			return false;

		if(($decoded = @json_decode($json, $assoc)) === null)
			return json_last_error() == JSON_ERROR_NONE;

		return true;
	}
}
