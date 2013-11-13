<?php

function bapi_create_site(){
	if ( ! preg_match( '/bapi-signup\.php$/', $_SERVER['REQUEST_URI'] ) ) {
		return;
	}
	
	$prefix = $_POST['siteprefix'];
	$sname = $_POST['sitename'];
	$tagline = '';
	if(isset($_POST['tagline'])&&!empty($_POST['tagline'])){
		$tagline = $_POST['tagline'];
	}
	$apikey = $_POST['apikey'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$domain = $_SERVER['SERVER_NAME'];
	$siteurl = $prefix.'.'.$domain;  //How to check which domain is used for current service
	$liveurl = 'http://'.$siteurl;
	if(isset($_POST['domain'])&&!empty($_POST['domain'])){
		$liveurl = $_POST['domain']; //bapi_site_cdn_domain
	}
	$cf_url = str_replace('http://','',$liveurl);
	$cf_origin = str_replace('http://','',$siteurl);
	$cf = create_cf_distro($cf_origin,$cf_url);
	if($cf==false){
		$cf = 'Error Creating CloudFront Distribution';
		
		header('Content-Type: application/javascript');	
		$new_site = array(
			"status" => "error",
			"data" => array(
				"errors" => array("cloudfront_distrib" => $cf),
				"error_data" => ""
			)
		);
		echo json_encode($new_site);
		exit();
	}
	$meta = array('api_key' => $apikey, 'bapi_secureurl' => $prefix.'.imbookingsecure.com', 'bapi_site_cdn_domain' => $liveurl, 'bapi_cloudfronturl' => $cf, 'blogdescription' => $tagline, 'bapi_first_look' => 1);
	if(defined(BAPI_BASEURL) && (BAPI_BASEURL == 'connect.bookt.biz')){
		$meta['bapi_secureurl'] = $prefix.'.lodgingcloud.com';
		$meta['bapi_baseurl'] = BAPI_BASEURL;
	}
	//$siteurl = $prefix.'.imbookingsecure.com';
	
	$u = username_exists($username);
	if(empty($u)){
		$u = wpmu_create_user($username,$password,$username);
	}
	
	//$u = wpmu_create_user($username,$password,$username);
	if(is_numeric($u)){
		$s = wpmu_create_blog($siteurl,'/',$sname,$u,$meta);
		//$t = wpmu_create_blog('wpmutest.localhost','/','Test1',1);  //use this one to force a 'blog_taken' failure.
		if(is_numeric($s)){
			//success
			switch_to_blog($s);
			//echo get_site_url();exit();
			
			switch_theme('instatheme01');
			bapi_wp_site_options();
			
			//Initialize menu and pages
			//$path = '/bapi.init?p=1';
			//$url = get_site_url().$path;
			//$server_output = file_get_contents($url);
			
			//Provide response
			header('Content-Type: application/javascript');	
			$new_site = array(
				"status" => "success",
				"data" => array(
					"blog_id" => $s,
					"blog_url" => get_site_url()
				)
			);
			echo json_encode($new_site);
		}
		else{
			//fail
			//print_r($s->errors['blog_taken'][0]); exit();  //Not sure if this is the only error returned.  Need a more generic message handler.
			header('Content-Type: application/javascript');	
			$new_site = array(
				"status" => "error",
				"data" => $s
			);
			echo json_encode($new_site);
			exit();
		}
	}
	else{
		header('Content-Type: application/javascript');	
		$new_site = array(
			"status" => "error",
			"data" => array(
				"errors" => array("user_unknown" => "Sorry, the username specified is invalid."),
				"error_data" => ""
			)
		);
		echo json_encode($new_site);
		exit();
	}
	exit();
}
?>