<?php

	require_once('bapi-php/bapi.php');
	require_once('functions.php');
	
	function bapi_sync_coredata() {
		$bapi = getBAPIObj();
		
		// check if we need to refresh seo data
		$data = get_option('bapi_keywords_array');
		$lastmod = get_option('bapi_keywords_lastmod');
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600)) {					
			$data = $bapi->getseodata(true);
			if (!empty($data)) {
				$data = $data['result']; // just get the result part
				$data = json_encode($data); // convert back to text
				update_option('bapi_keywords_array',$data);
				update_option('bapi_keywords_lastmod',time());
			}					
		}
		
		// check if we need to refresh textdata
		$data = get_option('bapi_textdata');
		$lastmod = get_option('bapi_textdata_lastmod');
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600)) {					
			$data = $bapi->gettextdata(true);			
			if (!empty($data)) {
				$data = $data['result']; // just get the result part
				$data = json_encode($data); // convert back to text
				update_option('bapi_textdata',$data);
				update_option('bapi_textdata_lastmod',time());
			}					
		}	
		
		// check if we need to refresh solution data
		$data = get_option('bapi_solutiondata');
		$lastmod = get_option('bapi_solutiondata_lastmod');
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600)) {					
			$data = $bapi->getcontext(true);
			if (!empty($data)) {
				$data = json_encode($data); // convert back to text
				update_option('bapi_solutiondata',$data);
				update_option('bapi_solutiondata_lastmod',time());
			}					
		}	
	}	
?>