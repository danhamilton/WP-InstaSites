
<?php
	require_once ('../../../wp-load.php');
	
	$menuname = "Main Navigation Menu";
	$menu_id = initmenu($menuname);
	
	// temp
	$parent = $_REQUEST['parent'];	
	$parentid = 0;
	$test = get_page_by_path($parent);
	if (!empty($test)) {
		$parentid = $test->ID;
	}
	
	// try to find if this page already exists
	$pid = getPageID($parent, $_REQUEST['url'], $_REQUEST['title']);
	
	// create the post
	$post = array();
	$post['ID'] = $pid;
	$post['menu_order'] = $_REQUEST['order'];
	$post['post_name'] = $_REQUEST['url'];	
	if (empty($post['post_name'])) {
		$post['post_name'] = null;
	}
	$post['post_title'] = $_REQUEST['title'];
	$post['post_status'] = 'publish';
	$post['post_parent'] = $parentid;
	$post['comment_status'] = 'closed';		
	$content = $_REQUEST['content'];	
	if($content!=''){				
		$t = file_get_contents(plugins_url($content, __FILE__));
		$m = new Mustache_Engine();
		//$c = file_get_contents(getbapiurl() . '/js/bapi.context?apikey=' . $apiKey);
		//$data = json_decode($c,TRUE);
		$string = $m->render($t, $data);
		$post['post_content'] = $string;		
	}
	$post['post_type'] = 'page';			
			
	remove_filter('save_post','update_post_bapi');		
	if ($pid == 0) {
		$pid = wp_insert_post($post);
		add_post_meta($pid, 'bapi_page_id', $_REQUEST['intid'], true);
		update_post_meta($pid, "_wp_page_template", $_REQUEST['template']);
		print_r('<div>Added menu item <b>' . $post['post_title'] . '</b> post_id=' . $pid . '</div>');
	}
	else {
		wp_update_post($post);
		add_post_meta($pid, 'bapi_page_id', $_REQUEST['intid'], true);
		update_post_meta($pid, "_wp_page_template", $_REQUEST['template']);
		print_r('<div>Modfied menu item <b>' . $post['post_title'] . '</b> post_id=' . $pid . '</div>');
	}
	add_filter('save_post','update_post_bapi');
		
	$addtomenu = ($_REQUEST['addtomenu'] == 'true');
	if($addtomenu && !doesNavMenuExist($pid)) {				
		if (!empty($parent)) {
			$navParentID = getNavMenuID($parent);			
		}
		else {
			$navParentID = $menu_id;
		}
		if ($navParentID == 0) {
			$navParentID = $menu_id;
		}
		print_r("nav parentid=" . $navParentID . "<br />");		
		$miid = wp_update_nav_menu_item($menu_id, 0, array(
								'menu-item-title' => $post['post_title'],
								'menu-item-object' => 'page',
								'menu-item-object-id' => $pid,
								'menu-item-type' => 'post_type',
								'menu-item-status' => 'publish',
								'menu-item-parent-id' => $parentid, //$navParentID,
								'menu-item-position' => $_REQUEST['order']));				
	}
	
	if($post['post_title']=='Home'){
		update_option( 'page_on_front', $pid);
		update_option( 'show_on_front', 'page');
	}
	if($post['post_title']=='Blog'){
		update_option( 'page_for_posts', $pid);
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