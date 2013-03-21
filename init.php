
<h4>Params</h4>
<?php
	require_once ('../../../wp-load.php');
	$template = $_REQUEST['template'];
	$parent = $_REQUEST['parent'];

	print_r('<div>Template: ' . $template . '</div>'); 
	print_r('<div>Parent: ' . $parent . '</div>'); 

	initmenu();
	
	$pagedef = array("Title"=>"All Rentals", "URL"=>"RentalGrid", "IntID"=>"bapi_property_grid", "Parent"=>'Search', "Order" => 1, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/allproperties-php', "AddToMenu"=>true);
	// temp
	$parent = $pagedef['Parent'];
	$parentid = 0;
	$test = get_page_by_path($parent);
	if (!empty($test)) {
		$parentid = $test->ID;
	}
	print_r("<div>ParentID=" . $parentid . "</div>");
	return;
	$existing_page = get_page_by_path($page['URL']);
	if (!empty($existing_page)) {
		$pid = $existing_page->ID;
	}
	
	$navmap = array();
	$post = array();
	$post['menu_order'] = $pagedef['Order'];
	$post['post_name'] = $pagedef['URL'];
	$post['post_title'] = $pagedef['Title'];
	$post['post_status'] = 'publish';
	$post['post_parent'] = 0;
	$post['comment_status'] = 'closed';			
	if($pagedef['Content']!=''){				
		$t = file_get_contents(plugins_url($pagedef['Content'], __FILE__));
		$m = new Mustache_Engine();
		$string = $m->render($t, $data);
		$post['post_content'] = $string;
	}
	$post['post_parent'] = $parentid;	
	$post['post_type'] = 'page';			
	
	print_r("<div>Added page with pageid=" . $pid . ", title=" . $post['post_title'] . ", URL=" . $post['post_name'] . "</div>");
	return;
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

	function initmenu() {
		$menuname = "Main Navigation Menu";
		$bpmenulocation = 'primary'; //Needs to be customized to InstaThemes when ready
		// Does the menu exist already?
		$menu_exists = wp_get_nav_menu_object( $menuname );
		
		// If it doesn't exist, let's create it.
		if( !$menu_exists){			
			$menu_id = wp_create_nav_menu($menuname);
			print_r("<div>Menu does not exist.  Created menu with menuid=" . $menu_id . ".</div>");
		}
		else {
			print_r("<div>Menu already existed with menuid=" . $menu_exists . ".</div>");
		}
		
		if( !has_nav_menu( $bpmenulocation ) ){
			$locations = get_theme_mod('nav_menu_locations');
			$locations[$bpmenulocation] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
	
	function create_initial_menu($apiKey){
		
		//print_r($mymenu);exit();
		//print($menuID);exit();
		//$defpages = array();
		//$defpages[] = array("Title"=>"Home", "URL"=>"", "IntID"=>"bapi_home", "Parent"=>'', "Order" => 1, "Template" => 'page-templates/front-page.php', "Content" => '/default-content/homepage-content.php', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Search", "URL"=>"RentalSearch", "IntID"=>"bapi_search", "Parent"=>'', "Order" => 2, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$pagedef = array("Title"=>"All Rentals", "URL"=>"RentalGrid", "IntID"=>"bapi_property_grid", "Parent"=>'Search', "Order" => 1, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/allproperties-php', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Property Finders", "URL"=>"PropertyFinders", "IntID"=>"bapi_property_finders", "Parent"=>'Search', "Order" => 2, "Template" => 'page-templates/content-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Specials", "URL"=>"Specials", "IntID"=>"bapi_specials", "Parent"=>'Search', "Order" => 3, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Developments", "URL"=>"Developments", "IntID"=>"bapi_developments", "Parent"=>'Search', "Order" => 4, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Attractions", "URL"=>"Attractions", "IntID"=>"bapi_attractions", "Parent"=>'', "Order" => 3, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Services", "URL"=>"Services", "IntID"=>"bapi_services", "Parent"=>'', "Order" => 4, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/ourservices-content.php', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"About Us", "URL"=>"AboutUs", "IntID"=>"bapi_about_us", "Parent"=>'', "Order" => 5, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/aboutus-content.php', "AddToMenu"=>true);
		/*$defpages[] = array("Title"=>"Blog", "URL"=>"Blog", "IntID"=>"bapi_blog", "Parent"=>'About Us', "Order" => 1, "Template" => 'page-templates/content-page.php', "Content" => '', "AddToMenu"=>true);
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
		*/
		$c = file_get_contents(getbapiurl() . '/js/bapi.context?apikey=' . $apiKey);
		$data = json_decode($c,TRUE);
		
		remove_filter('save_post','update_post_bapi');		
	
		//$args = array('meta_key' => 'bapi_page_id', 'meta_value' => $pagedef['IntID']);
		$posts_array = get_pages($args);
		if(empty($posts_array)){
			$parent = get_page_by_title($page['Parent']);
			addpage($page, $parent);
		}
		add_filter('save_post','update_post_bapi');
	}
?>