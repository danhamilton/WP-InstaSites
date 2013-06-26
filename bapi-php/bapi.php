<?php

class BAPI
{
	public $apikey;
	public $language = 'en-US';
    public $currency = 'USD';
    public $baseURL = 'connect.bookt.com';
	
	public function __construct($apikey, $language="en-US", $baseURL='connect.bookt.com') {
		$this->apikey = $apikey;
		$this->language = $language;
		$this->baseURL = $baseURL;
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
		
	public function getcontext($jsondecode) {
		if (!$this->isvalid()) { return null; }
		$c = file_get_contents($this->getBaseURL() . '/js/bapi.context?apikey=' . $this->apikey . '&language=' . $this->language);
		$res = json_decode($c,TRUE);
		return $res;
	}
	
	public function gettextdata($jsondecode) {
		if (!$this->isvalid()) { return null; }
		$c = file_get_contents($this->getBaseURL() . '/ws/?method=get&entity=textdata&apikey=' . $this->apikey . '&language=' . $this->language);
		if ($jsondecode) {return json_decode($c,TRUE); }
		return $c;
	}
	
	public function getseodata($jsondecode=true) {
		if (!$this->isvalid()) { return null; }
		$c = file_get_contents($this->getBaseURL() . '/ws/?method=get&entity=seo&apikey=' . $this->apikey . '&language=' . $this->language);
		if ($jsondecode) {return json_decode($c,TRUE); }
		return $c;
	}
	
	public function getSolutionConfig($apikey){
		$url = $this->getBaseURL() . "/ws/?method=getconfig&apikey=".$apikey;
		$json = file_get_contents($url);
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
		$c = file_get_contents($url);		
		if (empty($jsondecode) || $jsondecode) { return json_decode($c,TRUE); }
		return $c;
	}
	
	public function get($entity,$ids,$options=null,$jsondecode=true) {
		if (!$this->isvalid()) { return null; }
		$url = $this->getBaseURL() . "/ws/?method=get&apikey=" . $this->apikey . "&entity=" . $entity . '&ids=' . implode(",", $ids) . '&language=' . $this->language;
		if (!empty($options)) { $url = $url . "&" . http_build_query($options); }	
			echo $url; exit();
		$c = file_get_contents($url);
		if (empty($jsondecode) || $jsondecode) { return json_decode($c,TRUE); }
		return $c;
	}	
	
	public function del($entity,$ids,$options,$jsondecode=true) {
		if (!$this->isvalid()) { return null; }
		$optionsURL = implode("&", $options);
		//$url = $this->getBaseURL . "/ws/?method=get&apikey=" . $this->apikey . "&entity=" . $entity . '&ids' . implode(",", $ids);
		//echo $url; exit();
		//$json = file_get_contents($url);
		//$data = json_decode($json, TRUE);
		//return $data;		
		return null;
	}
	
	public function save($entity,$postdata,$jsondecode=true) {
		if (!$this->isvalid()) { return null; }
		$optionsURL = implode("&", $options);
		//$url = $this->baseURL . "/ws/?method=get&apikey=" . $this->apikey . "&entity=" . $entity . '&ids' . implode(",", $ids);
		//echo $url; exit();
		//$json = file_get_contents($url);
		//$data = json_decode($json, TRUE);
		//return $data;		
		return null;
	}
}
?>