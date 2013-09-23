<?php

function bapi_create_site(){
	if ( ! preg_match( '/bapi-signup\.php$/', $_SERVER['REQUEST_URI'] ) ) {
		return;
	}
	
	$prefix = $_POST['siteprefix'];
	$sname = $_POST['sitename'];
	$apikey = $_POST['apikey'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$u = username_exists($username);
	if(empty($u)){
		$u = wpmu_create_user($username,$password,$username);
	}
	
	//$u = wpmu_create_user($username,$password,$username);
	if(is_numeric($u)){
		$meta = array('api_key' => $apikey, 'bapi_secureurl' => $prefix.'.imbookingsecure.com');
		$domain = $_SERVER['SERVER_NAME'];
		$siteurl = $prefix.'.'.$domain;  //How to check which domain is used for current service
		//$siteurl = $prefix.'.imbookingsecure.com';
		$s = wpmu_create_blog($siteurl,'/',$sname,$u,$meta);
		//$t = wpmu_create_blog('wpmutest.localhost','/','Test1',1);  //use this one to force a 'blog_taken' failure.
		if(is_numeric($s)){
			//success
			switch_to_blog($s);
			//echo get_site_url();exit();
			
			switch_theme('instatheme01');
			bapi_wp_site_options();
			
			//Initialize menu and pages
			$path = '/bapi.init?p=1';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,get_site_url().$path);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($ch);
			curl_close ($ch);
			
			print_r($server_output);
			
			$menuname = "Main Navigation Menu";
			$menu_id = initmenu($menuname);
			echo $menu_id;
			//echo $s; exit();
			//header('Location: http://'.$siteurl.'/wp-admin/admin.php?page=bookt-api/setup-sync.php');
		}
		else{
			//fail
			//print_r($s->errors['blog_taken'][0]); exit();  //Not sure if this is the only error returned.  Need a more generic message handler.
			print_r($s); exit();
		}
	}
	else{
		echo "debug1";
		print_r($u);
	}
	exit();
}
?>