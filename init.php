<?php	
	function curPageURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	function urlHandler_securepages() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		//echo $url; exit();
		if (((strpos($url,'makepayment') !== false)||(strpos($url,'makebooking') !== false))&&(strpos($_SERVER['HTTP_HOST'],'lodgingcloud.com') == false)&&(strpos($_SERVER['HTTP_HOST'],'localhost') == false)) { //Do not force the redirect on lodgingcloud - helps bobby debug connect.
			$purl = parse_url(curPageURL());
			if($purl['scheme'] == 'http'){
				$nurl = "https://".$purl['host'].$purl['path'];
				if(!empty($purl['query'])){
					$nurl .= "?".$purl['query'];
				}
				//echo $nurl;
				header("Location: $nurl");
				exit();
			}
		}
		else{
			return;
		}
	}

	function urlHandler_bapidefaultpages() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		//echo $_SERVER['REQUEST_URI']; exit();
		if (strtolower($url) != "/bapi.init")
			return;
		
		$menuname = "Main Navigation Menu";
		$menu_id = initmenu($menuname);
		//echo $menu_id; //exit();
		
		/*if(!empty($_POST['pagedefs'])){
			$pagedefs = $_POST['pagedefs'];
		}
		if(!empty($_GET['pagedefs'])){
			$pagedefs = json_decode(stripslashes(urldecode($_GET['pagedefs'])),true);
			//print_r($pagedefs);exit();
		}*/
		
		$json = '[ { "addtomenu" : false,
			"content" : "/default-content/home.php",
			"intid" : "bapi_home",
			"order" : 1,
			"parent" : "",
			"template" : "page-templates/front-page.php",
			"title" : "Home",
			"url" : ""
		  },
		  { "addtomenu" : true,
			"content" : "",
			"intid" : "bapi_rentals",
			"order" : 2,
			"parent" : "",
			"template" : "page-templates/search-page.php",
			"title" : "Rentals",
			"url" : "rentals"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/rentalsearch.php",
			"intid" : "bapi_search",
			"order" : 1,
			"parent" : "rentals",
			"template" : "page-templates/search-page.php",
			"title" : "Search",
			"url" : "rentalsearch"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/allrentals.php",
			"intid" : "bapi_property_grid",
			"order" : 2,
			"parent" : "rentals",
			"template" : "page-templates/full-width.php",
			"title" : "All Rentals",
			"url" : "allrentals"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/propertyfinders.php",
			"intid" : "bapi_search_buckets",
			"order" : 3,
			"parent" : "rentals",
			"template" : "page-templates/full-width.php",
			"title" : "Search Buckets",
			"url" : "searchbuckets"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/developments.php",
			"intid" : "bapi_developments",
			"order" : 4,
			"parent" : "rentals",
			"template" : "page-templates/search-page.php",
			"title" : "Developments",
			"url" : "developments"
		  },
		  { "addtomenu" : false,
			"content" : "/default-content/mylist.php",
			"intid" : "bapi_mylist",
			"order" : 5,
			"parent" : "rentals",
			"template" : "page-templates/search-page.php",
			"title" : "My List",
			"url" : "mylist"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/specials.php",
			"intid" : "bapi_specials",
			"order" : 3,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Specials",
			"url" : "specials"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/attractions.php",
			"intid" : "bapi_attractions",
			"order" : 4,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Attractions",
			"url" : "attractions"
		  },
		  { "addtomenu" : true,
			"content" : "",
			"intid" : "bapi_company",
			"order" : 5,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Company",
			"url" : "company"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/aboutus.php",
			"intid" : "bapi_about_us",
			"order" : 2,
			"parent" : "company",
			"template" : "page-templates/full-width.php",
			"title" : "About Us",
			"url" : "aboutus"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/infoforowners.php",
			"intid" : "bapi_company_owner",
			"order" : 3,
			"parent" : "company",
			"template" : "page-templates/full-width.php",
			"title" : "Owner Information",
			"url" : "companyowner"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/infoforguests.php",
			"intid" : "bapi_company_guest",
			"order" : 4,
			"parent" : "company",
			"template" : "page-templates/full-width.php",
			"title" : "Guest Information",
			"url" : "companyguest"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/travelinsurance.php",
			"intid" : "bapi_travel_insurance",
			"order" : 5,
			"parent" : "company",
			"template" : "page-templates/full-width.php",
			"title" : "Travel Insurance",
			"url" : "travelinsurance"
		  },
		  { "addtomenu" : true,
			"content" : "/default-content/contactus.php",
			"intid" : "bapi_contact",
			"order" : 6,
			"parent" : "company",
			"template" : "page-templates/full-width.php",
			"title" : "Contact Us",
			"url" : "contact"
		  },
		  { "addtomenu" : true,
			"content" : "",
			"intid" : "bapi_blog",
			"order" : 7,
			"parent" : "company",
			"template" : "",
			"title" : "Blog",
			"url" : "blog"
		  },
		  { "addtomenu" : false,
			"content" : "/default-content/makebooking.php",
			"intid" : "bapi_makebooking",
			"order" : 9,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Make Booking",
			"url" : "makebooking"
		  },
		  { "addtomenu" : false,
			"content" : "/default-content/makepayment.php",
			"intid" : "bapi_makepayment",
			"order" : 10,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Make a Payment",
			"url" : "makepayment"
		  },
		  { "addtomenu" : false,
			"content" : "/default-content/bookingconfirmation.php",
			"intid" : "bapi_booking_confirm",
			"order" : 11,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Booking Confirmation",
			"url" : "bookingconfirmation"
		  },
		  { "addtomenu" : false,
			"content" : "/default-content/rentalpolicy.php",
			"intid" : "bapi_rental_policy",
			"order" : 12,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Rental Policy",
			"url" : "rentalpolicy"
		  },
		  { "addtomenu" : false,
			"content" : "/default-content/privacypolicy.php",
			"intid" : "bapi_privacy_policy",
			"order" : 13,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Privacy Policy",
			"url" : "privacypolicy"
		  },
		  { "addtomenu" : false,
			"content" : "/default-content/termsofuse.php",
			"intid" : "bapi_tos",
			"order" : 14,
			"parent" : "",
			"template" : "page-templates/full-width.php",
			"title" : "Terms of Use",
			"url" : "termsofuse"
		  }
		]';
		$pagedefs = json_decode($json,true);
			
		$navmap = array();
		foreach ($pagedefs as $pagedef) {
			addpage($pagedef, $menu_id);
			//print_r($pagedef);
			//print_r("<br />");
		}
		
		$qs = $_SERVER['QUERY_STRING'];
		if(strtolower($qs) == 'mode=initial-setup'){
			switch_theme('instatheme01');
			$toptions = get_option('instaparent_theme_options');
			$toptions['presetStyle'] = 'style01';
			update_option('instaparent_theme_options',$toptions);
			setSlideshowImages();
			bapi_wp_site_options();
			$blog_url = get_site_url();
			update_option( 'bapi_first_look', 0 );
			header("Location: $blog_url");
		}
		//return;
		exit();
	}
	
	
	function addpage($pagedef, $menu_id) {
		global $navmap;
		$parent = $pagedef['parent'];	
		$parentid = 0;
		$test = get_page_by_path($parent);
		if (!empty($test)) {
			$parentid = $test->ID;
		}
		
		// try to find if this page already exists
		$pid = getPageID($parent, $pagedef['url'], $pagedef['title']);
		
		// create the post
		$post = array();
		$post['ID'] = $pid;
		$post['menu_order'] = $pagedef['order'];
		$post['post_name'] = $pagedef['url'];	
		if (empty($post['post_name'])) {
			$post['post_name'] = null;
		}
		$post['post_title'] = $pagedef['title'];
		$post['post_status'] = 'publish';
		$post['post_parent'] = $parentid;
		$post['comment_status'] = 'closed';		
		
		// set the default content
		$content = $pagedef['content'];	
		if($content!=''){
			/* we check if the content is pointing to a local file */
			if(strpos($content, '/') === 0)
			{			
			$cpath = get_local(plugins_url($content,__FILE__));
			$t = file_get_contents($cpath);
			$m = new Mustache_Engine();
		
			$wrapper = getbapisolutiondata();			
			$string = $m->render($t, $wrapper);
			}else{
				/* if not is pointing to a json object */				
				$jsonContent = file_get_contents($content);
				if($jsonContent != FALSE)
				{
				$jsonObjContent = json_decode($jsonContent);
				$string = $jsonObjContent->result[0]->DocText;
				}else{$string = '';}
			}
			$string = str_replace("\t", '', $string); // remove tabs
			$string = str_replace("\n", '', $string); // remove new lines
			$string = str_replace("\r", '', $string); // remove carriage returns			
			$post['post_content'] = $string; //utf8_encode($string);				
		}
		$post['post_type'] = 'page';			
						
		$action = "Added";
		if ($pid == 0) {			
			$pid = wp_insert_post($post, $error);			
		}
		else {
			$action = "Edited";
			wp_update_post($post);
		}
		add_post_meta($pid, 'bapi_page_id', $pagedef['intid'], true);
		update_post_meta($pid, "_wp_page_template", $pagedef['template']);					
			
		$miid = 0;
		$addtomenu = ($pagedef['addtomenu'] == 'true');
		if($addtomenu && !doesNavMenuExist($pid)) {				
			$miid = addtonav($pid, $menu_id, $post, $parent);
		}
		
		if($post['post_title']=='Home'){
			update_option( 'page_on_front', $pid);
			update_option( 'show_on_front', 'page');
		}
		if($post['post_title']=='Blog'){
			update_option( 'page_for_posts', $pid);
		}
		print_r('<div>' . $action . ' menu item <b>' . $post['post_title'] . '</b> post_id=' . $pid . ', miid=' . $miid . ', menu_id=' . $menu_id . '</div>');		
	}	

	function addtonav($pid, $menu_id, $post, $parent) {
		global $navmap;
		$navParentID = 0;
		if (!empty($navmap[$parent])&&!empty($parent)) {
			$navParentID = $navmap[$parent]; //getNavMenuID($parent); //$menu_id;
		}
		print_r("PageID=".$pid.", Parent=".$parent.", navParentID=" . $navParentID . "<br/>");
		$miid = wp_update_nav_menu_item($menu_id, 0, array(
								'menu-item-title' => $post['post_title'],
								'menu-item-object' => 'page',
								'menu-item-object-id' => $pid,
								'menu-item-type' => 'post_type',
								'menu-item-status' => 'publish',
								'menu-item-parent-id' => $navParentID,
								'menu-item-position' => $post['menu_order']));
		$url = $post['post_name'];
		$navmap[$url] = $miid;		
		return $miid;
	}
	
	function initmenu($menuname) {		
		$bpmenulocation = 'primary'; //Needs to be customized to InstaThemes when ready
		// Does the menu exist already?
		$menu_exists = wp_get_nav_menu_object( $menuname );
		
		// If it doesn't exist, let's create it.
		if( !$menu_exists){			
			$menu_id = wp_create_nav_menu($menuname);
			//print_r("<div>Menu does not exist.  Created menu with menuid=" . $menu_id . ".</div>");
		}
		else {
			$menu_id = getMenuID($bpmenulocation);
			//print_r("<div>Menu already exists with menuid=" . $menu_id . ".</div>");
		}
		
		if( !has_nav_menu( $bpmenulocation ) ){
			$locations = get_theme_mod('nav_menu_locations');
			$locations[$bpmenulocation] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		return $menu_id;
	}	
	
	/* Helper Functions */
	function getPageID($parent, $url, $title) {
		$testurl = $parent . '/' . $url;
		$existing_page = get_page_by_path($testurl);
		if (!empty($existing_page)) {
			return $existing_page->ID;
		}
		$existing_page = get_page_by_title($title);
		if (!empty($existing_page)) {
			return $existing_page->ID;
		}
		return 0;
	}
	
	function getMenuID($menuname) {
		$locations = get_nav_menu_locations();
		if (isset($locations[$menuname])) {
			return $locations[$menuname];
		}
	}
	
	function doesNavMenuExist($pid) {		
		$locations = get_nav_menu_locations();		
		$menu = wp_get_nav_menu_object( $locations['primary'] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);		
		foreach ( (array) $menu_items as $key => $menu_item ) {
			if ($menu_item->object_id == $pid) {				
				return true;
			}			
		}
		return false;		
	}
	
	function getNavMenuID($url) {
		$locations = get_nav_menu_locations();		
		$menu = wp_get_nav_menu_object( $locations['primary'] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);		
				
		foreach ( (array) $menu_items as $key => $menu_item ) {
			$turl = parse_url($menu_item->url);
			$purl = $turl['path'];
			if ($purl == $url || $purl == '/' . $url || $purl == $url . '/' || $purl == '/' . $url . '/') {
				return $menu_item->ID;
			}			
		}
		return 0;
	}
?>