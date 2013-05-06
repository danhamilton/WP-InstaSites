<?php
	require_once ('../../../wp-load.php');
	
	$menuname = "Main Navigation Menu";
	$menu_id = initmenu($menuname);
	
	$pagedefs = $_POST['pagedefs'];
	$navmap = array();
	foreach ($pagedefs as $pagedef) {
		addpage($pagedef, $menu_id);
		//print_r($pagedef);
		//print_r("<br />");
	}
	return;
	
	
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
			$cpath = get_local(plugins_url($content,__FILE__));
			$t = file_get_contents($cpath);
			$m = new Mustache_Engine();
		
			$wrapper = getbapisolutiondata();			
			$string = $m->render($t, $wrapper);	
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
		print_r('<div>' . $action . ' menu item <b>' . $post['post_title'] . '</b> post_id=' . $pid . ', miid=' . $miid . '</div>');		
	}	

	function addtonav($pid, $menu_id, $post, $parent) {
		global $navmap;
		$navParentID = 0;
		if (!empty($navmap[$parent])&&!empty($parent)) {
			$navParentID = $navmap[$parent]; //getNavMenuID($parent); //$menu_id;
		}
		print_r("Parent=".$parent.", navParentID=" . $navParentID . "<br/>");
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