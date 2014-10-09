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
	
	public function get($entity,$ids,$options=null,$jsondecode=true,$debugmode=0) {
		if (!$this->isvalid()) { return null; }
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
	public function save($jsonObj) {
		if (!$this->isvalid()) {
			wp_die( "An error occured while saving your setup. Please <a href='#' onclick='window.history.back(); return false;'>try again</a>." );
		}

		$url = $this->getBaseURL() . "/ws/?method=save&entity=seo&apikey=" . $this->apikey;
		$response = wp_remote_post(
			$url,
			array(
				'method'		=> 'POST',
				'timeout' 		=> 45,
				'redirection'	=> 5,
				'httpversion'	=> '1.0',
				'blocking'		=> true,
				'user-agent'	=> '',
				'headers'		=> array(
									'content-type'	=> self::WWW_FORM_URLENCODED,
									'user-agent'	=> self::BAPI_USER_AGENT
								),
				'body'			=> $jsonObj,
				'cookies'		=> array()
			)
		);
		if( is_wp_error( $response ) ) {
   			$error_message = $response->get_error_message();
			wp_die( "An error occured while saving your setup. Please <a href='#' onclick='window.history.back(); return false;'>try again</a>.\n".$error_message );
		}

		if(
			200 !== wp_remote_retrieve_response_code( $response ) ||
			!is_array( $body =  json_decode( wp_remote_retrieve_body( $response ), true ) ) ||
			isset( $body['error'] )
		) {
			wp_die( "An error occured while saving your setup. Please <a href='#' onclick='window.history.back(); return false;'>try again</a>." );
		}
		
	}
}
?>
