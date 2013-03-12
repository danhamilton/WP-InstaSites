<?php

function entity_replacer($string,$data){
	//print_r($data); exit();
	$m = new Mustache_Engine;
	$string = $m->render($string, $data); // "Hello, world!"
	//echo $string; exit();
	return $string;
}

function property_list(){
	$apiKey = get_option('api_key');
	$url = "http://connect.bookt.com/ws/?method=search&apikey=".$apiKey."&entity=property";
	//echo $url; exit();
	$json = file_get_contents($url);
	$data = json_decode($json, TRUE);
	
	return($data);
}

function bapi_get_list($entity){
	$apiKey = get_option('api_key');
	$url = "http://connect.bookt.com/ws/?method=search&apikey=".$apiKey."&entity=".$entity;
	//echo $url; exit();
	$json = file_get_contents($url);
	$data = json_decode($json, TRUE);
	
	return($data);
}

function update_post_bapi(){
	if( !get_option('solution_id') ) {
		$solId = -1;
		$apiKey = -1;
	} else {
		$solId = get_option('solution_id');
		$apiKey = get_option('api_key');
	}
	global $flag;
	if($flag==1){
		if (get_post_meta(get_the_ID(),'property_id',true)!='' && (get_post_meta(get_the_ID(),'bapi_last_update',true) < time()-(3600*60*24))){
			$propid = get_post_meta(get_the_ID(),'property_id',true);
			remove_filter('save_post','update_post_bapi');
			wp_update_post(mod_post_builder($propid,get_the_ID()));
			$p = new Property($solId,$apiKey,$propid,true);
			$avgrating = $p->propAvgReview;
			$avgrating = (round($avgrating*2)/2);
			update_post_meta(get_the_ID(), 'bapi_avg_rating', $avgrating);
			update_post_meta(get_the_ID(), 'bapi_lat', $p->propLatitude);
			update_post_meta(get_the_ID(), 'bapi_long', $p->propLongitude);
			update_post_meta(get_the_ID(), 'bapi_beds', $p->propBeds);
			update_post_meta(get_the_ID(), 'bapi_baths', $p->propBaths);
			update_post_meta(get_the_ID(), 'bapi_last_update', time());
			wp_set_post_terms(get_the_ID(), wp_create_category(get_option('property_category_name')), 'category');
			add_filter('save_post','update_post_bapi');
		}
	}
	$flag++;
}

function bapi_option_update($old,$new){
	$solId = -1;
	$apiKey = -1;
	$args = array(
		'meta_key' => 'bapi_page_id',
		'meta_value' => 'bapi_search');
	$posts_array = get_pages($args);
	$search_parent = $posts_array[0]->ID;
	$args = array(
		'meta_key' => 'bapi_page_id',
		'meta_value' => 'bapi_developments',
		'child_of' => $search_parent);
	$posts_array = get_pages($args);
	$development_parent = $posts_array[0]->ID;
	$args = array(
		'meta_key' => 'bapi_page_id',
		'meta_value' => 'bapi_specials',
		'child_of' => $search_parent);
	$posts_array = get_pages($args);
	$specials_parent = $posts_array[0]->ID;
	$args = array(
		'meta_key' => 'bapi_page_id',
		'meta_value' => 'bapi_property_finders',
		'child_of' => $search_parent);
	$posts_array = get_pages($args);
	$searches_parent = $posts_array[0]->ID;
	$args = array(
		'meta_key' => 'bapi_page_id',
		'meta_value' => 'bapi_attractions');
	$posts_array = get_pages($args);
	$poi_parent = $posts_array[0]->ID;
	if( get_option('solution_id') ) {
		$solId = get_option('solution_id');
		$apiKey = get_option('api_key');
	}
	if($new=='update-props'){
		$args = array(
			'meta_key' => 'property_id',
			'child_of' => $search_parent);
		$posts_array = get_pages($args);
		//echo $search_parent;
		//print_r($posts_array);exit();
		if(count($posts_array)>0){
			foreach($posts_array as $p){
				$propid = get_post_custom_values('property_id',$p->ID);
				if(!empty($propid)){
					remove_filter('save_post','update_post_bapi');
					wp_update_post(mod_post_builder($propid[0],$p->ID,null,'tmpl-properties-detail','property',$search_parent));
					$prop = new Property($solId,$apiKey,$propid[0],true);
					$avgrating = $prop->propAvgReview;
					update_post_meta($p->ID, 'bapi_avg_rating', (round($avgrating*2)/2), true);
					update_post_meta($p->ID, 'bapi_lat', $prop->propLatitude);
					update_post_meta($p->ID, 'bapi_long', $prop->propLongitude);
					update_post_meta($p->ID, 'bapi_beds', $prop->propBeds);
					update_post_meta($p->ID, 'bapi_baths', $prop->propBaths);
					update_post_meta($p->ID, 'bapi_last_update', time());
					update_post_meta($p->ID, 'bapi_page_id', 'bapi_property_detail', true);
					update_post_meta($p->ID, '_wp_page_template', 'page-templates/property-detail.php', true);
					wp_set_post_terms($p->ID, wp_create_category(get_option('property_category_name')), 'category');
					add_filter('save_post','update_post_bapi');
				}
			}
		}
	} 
	if($new=='import-props'){
		$plist = property_list();
		$plist = $plist['result'];
		$pc = count($plist);
		$i = 0;
		while($i<$pc){
			$args = array(
				'meta_key' => 'property_id',
				'meta_value' => $plist[$i],
				'child_of' => $search_parent);
			//print_r($args);exit();
			$posts_array = get_pages($args);
			if(count($posts_array)==0){
				//print_r($mod_post);exit();
				remove_filter('save_post','update_post_bapi');
				$pid = wp_insert_post(mod_post_builder($plist[$i],-1,null,'tmpl-properties-detail','property',$search_parent),$wp_error);
				$p = new Property($solId,$apiKey,$plist[$i],true);
				$avgrating = $p->propAvgReview;
				add_post_meta($pid, 'bapi_avg_rating', (round($avgrating*2)/2), true);
				add_post_meta($pid, 'bapi_lat', $p->propLatitude);
				add_post_meta($pid, 'bapi_long', $p->propLongitude);
				add_post_meta($pid, 'bapi_beds', $p->propBeds);
				add_post_meta($pid, 'bapi_baths', $p->propBaths);
				add_post_meta($pid, 'property_id', $plist[$i], true); 
				add_post_meta($pid, 'bapi_last_update', time(), true);
				add_post_meta($pid, 'bapi_page_id', 'bapi_property_detail', true);
				add_post_meta($pid, '_wp_page_template', 'page-templates/property-detail.php', true);
				wp_set_post_terms($pid, wp_create_category(get_option('property_category_name')), 'category');
				add_filter('save_post','update_post_bapi');
			}
			$i++;
		}
	}
	if($new=='update-devs'){
		$args = array(
			'meta_key' => 'development_id',
			'child_of' => $development_parent);
		$posts_array = get_pages($args);
		if(count($posts_array)>0){
			//print_r($posts_array);exit();
			foreach($posts_array as $p){
				$propid = get_post_custom_values('development_id',$p->ID);
				if(!empty($propid)){
					remove_filter('save_post','update_post_bapi');
					wp_update_post(mod_post_builder($propid[0],$p->ID,null,'tmpl-developments-detail','development',$development_parent),$wp_error);
					update_post_meta($p->ID, 'bapi_last_update', time());
					update_post_meta($p->ID, 'bapi_page_id', 'bapi_development_detail', true);
					add_filter('save_post','update_post_bapi');
				}
			}
		}
	}
	if($new=='import-devs'){
		$plist = bapi_get_list('development');
		$plist = $plist['result'];
		$pc = count($plist);
		$i = 0;
		while($i<$pc){
			$args = array(
				'meta_key' => 'property_id',
				'meta_value' => $plist[$i],
				'child_of' => $development_parent);
			//print_r($args);exit();
			$posts_array = get_pages($args);
			if(count($posts_array)==0){
				//print_r($mod_post);exit();
				remove_filter('save_post','update_post_bapi');
				$pid = wp_insert_post(mod_post_builder($plist[$i],-1,null,'tmpl-developments-detail','development',$development_parent),$wp_error);
				add_post_meta($pid, 'development_id', $plist[$i], true); 
				add_post_meta($pid, 'bapi_last_update', time(), true);
				add_post_meta($pid, 'bapi_page_id', 'bapi_development_detail', true);
				add_filter('save_post','update_post_bapi');
			}
			$i++;
		}
	}
	if($new=='update-devs'){
		$args = array(
			'meta_key' => 'special_id',
			'child_of' => $specials_parent);
		$posts_array = get_pages($args);
		if(count($posts_array)>0){
			//print_r($posts_array);exit();
			foreach($posts_array as $p){
				$propid = get_post_custom_values('special_id',$p->ID);
				if(!empty($propid)){
					remove_filter('save_post','update_post_bapi');
					wp_update_post(mod_post_builder($propid[0],$p->ID,null,'tmpl-specials-detail','specials',$specials_parent),$wp_error);
					update_post_meta($p->ID, 'bapi_last_update', time());
					update_post_meta($p->ID, 'bapi_page_id', 'bapi_special_detail', true);
					add_filter('save_post','update_post_bapi');
				}
			}
		}
	}
	if($new=='import-specials'){
		$plist = bapi_get_list('specials');
		$plist = $plist['result'];
		$pc = count($plist);
		$i = 0;
		while($i<$pc){
			$args = array(
				'meta_key' => 'special_id',
				'meta_value' => $plist[$i],
				'child_of' => $specials_parent);
			//print_r($args);exit();
			$posts_array = get_pages($args);
			if(count($posts_array)==0){
				//print_r($mod_post);exit();
				remove_filter('save_post','update_post_bapi');
				$pid = wp_insert_post(mod_post_builder($plist[$i],-1,null,'tmpl-specials-detail','specials',$specials_parent),$wp_error);
				add_post_meta($pid, 'special_id', $plist[$i], true); 
				add_post_meta($pid, 'bapi_last_update', time(), true);
				add_post_meta($pid, 'bapi_page_id', 'bapi_special_detail', true);
				add_filter('save_post','update_post_bapi');
			}
			$i++;
		}
	}
	if($new=='update-searches'){
		$args = array(
			'meta_key' => 'search_id',
			'child_of' => $searches_parent);
		$posts_array = get_pages($args);
		if(count($posts_array)>0){
			//print_r($posts_array);exit();
			foreach($posts_array as $p){
				$propid = get_post_custom_values('special_id',$p->ID);
				if(!empty($propid)){
					remove_filter('save_post','update_post_bapi');
					wp_update_post(mod_post_builder($propid[0],$p->ID,null,'tmpl-searches-detail','searches',$searches_parent),$wp_error);
					update_post_meta($p->ID, 'bapi_last_update', time());
					update_post_meta($p->ID, 'bapi_page_id', 'bapi_search_detail', true);
					add_filter('save_post','update_post_bapi');
				}
			}
		}
	}
	if($new=='import-searches'){
		$plist = bapi_get_list('searches');
		$plist = $plist['result'];
		$pc = count($plist);
		$i = 0;
		while($i<$pc){
			$args = array(
				'meta_key' => 'search_id',
				'meta_value' => $plist[$i],
				'child_of' => $searches_parent);
			//print_r($args);exit();
			$posts_array = get_pages($args);
			if(count($posts_array)==0){
				//print_r($mod_post);exit();
				remove_filter('save_post','update_post_bapi');
				$pid = wp_insert_post(mod_post_builder($plist[$i],-1,null,'tmpl-searches-detail','searches',$searches_parent),$wp_error);
				add_post_meta($pid, 'search_id', $plist[$i], true); 
				add_post_meta($pid, 'bapi_last_update', time(), true);
				add_post_meta($pid, 'bapi_page_id', 'bapi_search_detail', true);
				add_filter('save_post','update_post_bapi');
			}
			$i++;
		}
	}
	if($new=='update-attractions'){
		$args = array(
			'meta_key' => 'poi_id',
			'child_of' => $searches_parent);
		$posts_array = get_pages($args);
		if(count($posts_array)>0){
			//print_r($posts_array);exit();
			foreach($posts_array as $p){
				$propid = get_post_custom_values('poi_id',$p->ID);
				if(!empty($propid)){
					remove_filter('save_post','update_post_bapi');
					wp_update_post(mod_post_builder($propid[0],$p->ID,null,'tmpl-attractions-detail','poi',$poi_parent),$wp_error);
					update_post_meta($p->ID, 'bapi_last_update', time());
					update_post_meta($p->ID, 'bapi_page_id', 'bapi_poi_detail', true);
					add_filter('save_post','update_post_bapi');
				}
			}
		}
	}
	if($new=='import-attractions'){
		$plist = bapi_get_list('poi');
		$plist = $plist['result'];
		$pc = count($plist);
		$i = 0;
		while($i<$pc){
			$args = array(
				'meta_key' => 'poi_id',
				'meta_value' => $plist[$i],
				'child_of' => $searches_parent);
			//print_r($args);exit();
			$posts_array = get_pages($args);
			if(count($posts_array)==0){
				//print_r($mod_post);exit();
				remove_filter('save_post','update_post_bapi');
				$pid = wp_insert_post(mod_post_builder($plist[$i],-1,null,'tmpl-attractions-detail','poi',$poi_parent),$wp_error);
				add_post_meta($pid, 'poi_id', $plist[$i], true); 
				add_post_meta($pid, 'bapi_last_update', time(), true);
				add_post_meta($pid, 'bapi_page_id', 'bapi_poi_detail', true);
				add_filter('save_post','update_post_bapi');
			}
			$i++;
		}
	}
	if($new=='initial_import'){ 
		/*register_nav_menus(
			array(
				'primary' => __( "Main Navigation Menu", get_current_theme() )
			)
		);*/
		$menuname = "Main Navigation Menu";
		$bpmenulocation = 'primary'; //Needs to be customized to InstaThemes when ready
		// Does the menu exist already?
		$menu_exists = wp_get_nav_menu_object( $menuname );
		
		// If it doesn't exist, let's create it.
		if( !$menu_exists){
			$menu_id = wp_create_nav_menu($menuname);
		}
		if( !has_nav_menu( $bpmenulocation ) ){
        	$locations = get_theme_mod('nav_menu_locations');
			$locations[$bpmenulocation] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		//print_r($mymenu);exit();
		//print($menuID);exit();
		$defpages = array();
		$defpages[] = array("Title"=>"Home", "URL"=>"", "IntID"=>"bapi_home", "Parent"=>'', "Order" => 1, "Template" => 'page-templates/front-page.php', "Content" => '/default-content/homepage-content.php', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Search", "URL"=>"RentalSearch", "IntID"=>"bapi_search", "Parent"=>'', "Order" => 2, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"All Rentals", "URL"=>"RentalGrid", "IntID"=>"bapi_property_grid", "Parent"=>'Search', "Order" => 1, "Template" => 'page-templates/full-width.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Property Finders", "URL"=>"PropertyFinders", "IntID"=>"bapi_property_finders", "Parent"=>'Search', "Order" => 2, "Template" => 'page-templates/content-page.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Specials", "URL"=>"Specials", "IntID"=>"bapi_specials", "Parent"=>'Search', "Order" => 3, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Developments", "URL"=>"Developments", "IntID"=>"bapi_developments", "Parent"=>'Search', "Order" => 4, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Attractions", "URL"=>"Attractions", "IntID"=>"bapi_attractions", "Parent"=>'', "Order" => 3, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Services", "URL"=>"Services", "IntID"=>"bapi_services", "Parent"=>'', "Order" => 4, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/ourservices-content.php', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"About Us", "URL"=>"AboutUs", "IntID"=>"bapi_about_us", "Parent"=>'', "Order" => 5, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/aboutus-content.php', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Blog", "URL"=>"Blog", "IntID"=>"bapi_blog", "Parent"=>'About Us', "Order" => 1, "Template" => 'page-templates/content-page.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Contact Us", "URL"=>"Contact", "IntID"=>"bapi_contact", "Parent"=>'', "Order" => 6, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/contactus-content.php', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Booking Details", "URL"=>"BookingDetails", "IntID"=>"bapi_booking_detail", "Parent"=>'', "Order" => 7, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/booking-detail-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Make a Payment", "URL"=>"BookingPayment", "IntID"=>"bapi_booking_payment", "Parent"=>'', "Order" => 8, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/booking-payment-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Booking Confirmation", "URL"=>"BookingConfirmation", "IntID"=>"bapi_booking_confirm", "Parent"=>'', "Order" => 9, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/booking-confirmation-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Rental Policy", "URL"=>"RentalPolicy", "IntID"=>"bapi_booking_terms", "Parent"=>'', "Order" => 10, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/rental-policy-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Privacy Policy", "URL"=>"PrivacyPolicy", "IntID"=>"bapi_site_privacy", "Parent"=>'', "Order" => 11, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/privacy-policy-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Terms of Use", "URL"=>"TermsOfUse", "IntID"=>"bapi_site_terms", "Parent"=>'', "Order" => 12, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/tos-content.php', "AddToMenu"=>false);
		//$defpages[] = array("Title"=>"Owner Login", "URL"=>"/Owners", "IntID"=>"bapi_owners", "Parent"=>''); //TO be added to footer menu only
		//print_r($defpages);
		//exit();
		
		remove_filter('save_post','update_post_bapi');
		$navmap = array();
		foreach($defpages as $page){
			$args = array(
				'meta_key' => 'bapi_page_id',
				'meta_value' => $page['IntID']);
			$posts_array = get_pages($args);
			if(empty($posts_array)){
				$parent = get_page_by_title($page['Parent']);
				$post = array();
				$post['menu_order'] = $page['Order'];
				$post['post_name'] = $page['URL'];
				$post['post_title'] = $page['Title'];
				$post['post_status'] = 'publish';
				$post['post_parent'] = 0;
				$post['comment_status'] = 'closed';
				if($page['Content']!=''){
					$c = file_get_contents('https://'.BAPI_API_LOCATION.'/js/bapi.context?apikey='.$apiKey);
					$data = json_decode($c,TRUE);
					$t = file_get_contents(plugins_url($page['Content'], __FILE__));
					$m = new Mustache_Engine();
					$string = $m->render($t, $data);
					$post['post_content'] = $string;
				}
				if(!empty($parent)){
					$post['post_parent'] = $parent->ID;
				}
				$post['post_type'] = 'page';
				//print_r($post);exit();
				$pid = wp_insert_post($post);
				add_post_meta($pid, 'bapi_page_id', $page['IntID'], true);
				add_post_meta($pid, '_wp_page_template', $page['Template'], true);
				if($page['AddToMenu']){
					$miid = wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page['Title'],
											   'menu-item-object' => 'page',
											   'menu-item-object-id' => $pid,
											   'menu-item-type' => 'post_type',
											   'menu-item-status' => 'publish',
											   'menu-item-parent-id' => $navmap[$post['post_parent']],
											   'menu-item-position' => $page['Order']));
					$navmap[$pid] = $miid;
				}
				if($page['Title']=='Home'){
					update_option( 'page_on_front', $pid);
					update_option( 'show_on_front', 'page');
				}
				if($page['Title']=='Blog'){
					update_option( 'page_for_posts', $pid);
				}
			}
		}
		add_filter('save_post','update_post_bapi');
	}
	remove_action('update_option_update_action','update_post_bapi');
	update_option('update_action','');
	add_action('update_option_update_action','update_post_bapi', 10, 2);
}

function mod_post_builder($propid,$postid,$p,$tmpl='tmpl-properties-detail',$entity='property',$parentid=-1){
	if(!defined('BAPI_API_LOCATION')){
		define('BAPI_API_LOCATION','connect.bookt.com');
	}
	$solId = get_option('solution_id');
	$apiKey = get_option('api_key');
	$data = file_get_contents('http://'.BAPI_API_LOCATION.'/ws/?method=get&apikey='.$apiKey.'&entity='.$entity.'&seo=1&rates=1&poi=1&reviews=1&descrip=1&avail=1&ids='.$propid);
	$data = json_decode($data,TRUE);
	//print_r($data['result'][0]); exit();

	$c = file_get_contents('https://'.BAPI_API_LOCATION.'/js/bapi.context?apikey='.$apiKey);
	$config = json_decode($c,TRUE);
	$td = file_get_contents('https://'.BAPI_API_LOCATION.'/ws/?method=get&entity=textdata&apikey='.$apiKey);
	$textdata = json_decode($td,TRUE);
	
	$data['config'] = $config['Config'];
	$data['textdata'] = $textdata['result'];
	
	$args = array(
		'meta_key' => 'bapi_page_id',
		'meta_value' => 'bapi_search');
	$posts_array = get_pages($args);
	if($parentid<0){
		$parentid = $posts_array[0];
	}
	
	$content = bapi_get_template($tmpl);
	//header("Content-Type: text/plain");
	//echo $content.'\n\n';
	//print_r($data);
	//exit();
	$content = entity_replacer($content,$data);
	$mod_post = array();
	if($postid>0){
		$mod_post['ID'] = $postid;
	}
	$mod_post['post_content'] = $content;
	$mod_post['post_excerpt'] = '<img src="'.$data['result'][0]['Images']['OriginalURL'].'" style="display:block;max-height:420px;" ><br/>'.$data['result'][0]['Summary'];
	if(empty($data['result'][0]['ContextData']['SEO']['PageTitle'])){
		$pt = $data['result'][0]['Headline'];
	}
	if(!empty($data['result'][0]['ContextData']['SEO']['PageTitle'])){
		$pt = $data['result'][0]['ContextData']['SEO']['PageTitle'];
	}
	$pn='/rentalsearch/'.$propid;
	if(!empty($data['result'][0]['ContextData']['SEO']['DetailURL'])){
		$pn = parse_url($data['result'][0]['ContextData']['SEO']['DetailURL'],PHP_URL_PATH);
		$pn = str_replace('/rentalsearch','',$pn);
		$pn = str_replace('/developments','',$pn);
		$pn = str_replace('/specials','',$pn);
	}
	$mod_post['post_title'] = wp_strip_all_tags($pt);
	$mod_post['post_name'] = wp_strip_all_tags($pn);
	$mod_post['post_type'] = 'page';
	$mod_post['post_status'] = 'publish';
	$mod_post['comment_status'] = 'closed';
	$mod_post['post_parent'] = $parentid;
	
	return $mod_post;
}

?>