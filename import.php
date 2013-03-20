
<h4>Params</h4>
<?php
require_once ('../../../wp-load.php');
$bapiurl = $_REQUEST['bapiurl'];
$entity = $_REQUEST['entity'];
$apikey = $_REQUEST['apikey'];
$action = $_REQUEST['action'];
$template = $_REQUEST['template'];
$parent = $_REQUEST['parent'];

print_r('<div>BAPI URL: ' . $bapiurl . '</div>'); 
print_r('<div>Entity: ' . $entity . '</div>'); 
print_r('<div>APIKey: ' . $apikey . '</div>'); 
print_r('<div>Action: ' . $action . '</div>'); 
print_r('<div>Template: ' . $template . '</div>'); 
print_r('<div>Parent: ' . $parent . '</div>'); 

if ($action=='initial_import') {
	create_initial_menu($apikey);
}
elseif ($action=='import') {
	print_r('<h4>Results</h4>');
	import_entities($bapiurl, $apikey, $entity, $template, $parent);		
}

function entity_replacer($string,$data){
	//print_r($data); exit();
	$m = new Mustache_Engine;
	$string = $m->render($string, $data); // "Hello, world!"
	//echo $string; exit();
	return $string;
}

function bapi_get_list($bapiurl, $apikey, $entity){
	print_r("<div>Getting list of ids.</div>");
	$url = $bapiurl . '/ws/?apikey=' . $apikey . "&entity=" . $entity . '&method=search';
	print_r($url);
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

function import_entities($bapiurl, $apikey, $entity, $template, $parent){
	$plist = bapi_get_list($bapiurl, $apikey, $entity);	
	$plist = $plist['result'];
	$pc = count($plist);
	print_r("<div>Count=" . count($pc) . "</div>");
	$i = 0;
	$pkid_name = $entity . '_id';
	$meta_name = 'bapi_' . $entity . '_detail';
	
	$config = getbapiconfig($bapiurl, $apikey);
	$textdata = getbapitextdata($bapiurl, $apikey);	
	
	while($i<$pc){
		$args = array(
			'meta_key' => 'property_id',
			'meta_value' => $plist[$i],
			'child_of' => $parent);
		//print_r($args);exit();
		$posts_array = get_pages($args);		
		if(count($posts_array)==0){
			//print_r($mod_post);exit();
			remove_filter('save_post','update_post_bapi');
			$obj = $plist[$i];			
			$thepost = mod_post_builder($bapiurl, $apikey, $config, $textdata, $obj, -1, null, $template, $entity);
			$pid = wp_insert_post($thepost,$wp_error);
			//print_r('<div>Imported ' . $entity . ' with PKID=' . $obj.ID . ', postid=' . $pid . '</div>'); 
			add_post_meta($pid, $pkid_name, $plist[$i], true); 
			add_post_meta($pid, 'bapi_last_update', time(), true);
			add_post_meta($pid, 'bapi_page_id', $meta_name, true);
			add_post_meta($pid, 'bapi_page_id', $meta_name, true);
			//update_post_meta($pid, '_wp_page_template', (plugins_url() . '/themefiles/myAccount.php'), true);

			add_filter('save_post','update_post_bapi');			
		}
		$i++;
	}
}

function create_initial_menu($apiKey){
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
	
	$c = file_get_contents(getbapiurl() . '/js/bapi.context?apikey=' . $apiKey);
	$data = json_decode($c,TRUE);
	
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
			print_r("<div>Added page with pageid=" . $pid . ", title=" . $post['post_title'] . ", URL=" . $post['post_name'] . "</div>");
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

function getbapiconfig($bapiurl, $apikey) {
	$c = file_get_contents($bapiurl . '/js/bapi.context?apikey= ' . $apikey);
	$res = json_decode($c,TRUE);
	return $res;
}

function getbapitextdata($bapiurl, $apikey) {
	$c = file_get_contents($bapiurl . '/ws/?method=get&entity=textdata&apikey=' . $apikey);
	$res = json_decode($c,TRUE);
	return $res;
}

function mod_post_builder($bapiurl, $apikey, $config, $textdata, $propid, $postid, $p, $tmpl='tmpl-properties-detail', $entity='property'){
	$url = $bapiurl . '/ws/?method=get&apikey=' . $apikey. '&entity=' . $entity.'&seo=1&rates=1&poi=1&reviews=1&descrip=1&avail=1&ids=' . $propid;
	//print_r('Fetching from ' . $url);
	$data = file_get_contents($url);
	$data = json_decode($data,TRUE);	
	$data['config'] = $config['Config'];
	$data['textdata'] = $textdata['result'];
	$obj = $data['result'][0];
	
	$content = bapi_get_template($tmpl);
	$content = entity_replacer($content,$data);
	$mod_post = array();
	if($postid>0){
		$mod_post['ID'] = $postid;
	}	
	$mod_post['post_content'] = $content;
	$mod_post['post_excerpt'] = '<img src="'.$obj['PrimaryImage']['MediumURL'] . '" style="display:block;max-height:420px;" ><br/>' . $obj['Summary'];
	$pt = $obj['ContextData']['SEO']['PageTitle'];
	if(empty($pt)){
		$pt = $obj['Headline'];
	}
	$rootpath = '/rentalsearch';
	if($entity=='property') {
		$rootpath = '/rentalsearch';
	}
	elseif($entity=='development') {
		$rootpath = '/developments';
	}
	elseif($entity=='specials') {
		$rootpath = '/specials';
	}
	elseif($entity=='poi') {
		$rootpath = '/attractions';
	}
	
	// This could fail, should probably do some error checking here
	$parentid = get_page_by_path($rootpath)->ID;
	
	$kw = $obj['ContextData']['SEO']['Keyword'];
	$pn = $kw;
	print_r("<div>rootpath=" . $rootpath . ", kw=" . $kw . "</div>");
	if(empty($kw)) {
		$pn = $obj['ID']; //$rootpath . '/' . $obj['ID'];
	}
	
	$mod_post['post_title'] = wp_strip_all_tags($pt);
	$mod_post['post_name'] = wp_strip_all_tags($pn);
	$mod_post['post_type'] = 'page';
	$mod_post['post_status'] = 'publish';
	$mod_post['comment_status'] = 'closed';
	$mod_post['post_parent'] = $parentid;
	
	print_r('<div>title=' . $mod_post['post_title'] . ', name=' . $mod_post['post_name'] . '</div>');
	return $mod_post;
}

?>