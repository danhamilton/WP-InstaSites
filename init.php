
<h4>Params</h4>
<?php
	require_once ('../../../wp-load.php');
	
	$menu_id = initmenu();
	
	// temp
	$parent = $_REQUEST['parent'];	
	$parentid = 0;
	$test = get_page_by_path($parent);
	if (!empty($test)) {
		print_r("<div>found parent</div>");
		$parentid = $test->ID;
	}
	
	// try to find if this page already exists
	$pid = getPageID($parent, $_REQUEST['url'], $_REQUEST['title']);
		
	// create the post
	$post = array();
	$post['ID'] = $pid;
	$post['menu_order'] = $_REQUEST['order'];
	$post['post_name'] = $_REQUEST['url'];
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
		print_r('<div>Added post_id=' . $pid . ', title=' . $post['post_title'] . '</div>');
	}
	else {
		wp_update_post($post);
		add_post_meta($pid, 'bapi_page_id', $_REQUEST['intid'], true);
		update_post_meta($pid, "_wp_page_template", $_REQUEST['template']);
		print_r('<div>Edited post_id=' . $pid . ', title=' . $post['post_title'] . '</div>');
	}
	add_filter('save_post','update_post_bapi');
	
	$addtomenu = settype($_REQUEST['addtomenu'],'boolean');
	if($addtomenu) {
		print_r("ADDING TO MENU");
		return;
		$miid = wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page['Title'],
								   'menu-item-object' => 'page',
								   'menu-item-object-id' => $pid,
								   'menu-item-type' => 'post_type',
								   'menu-item-status' => 'publish',
								   'menu-item-parent-id' => $menu_id,
								   'menu-item-position' => $post['Order']));
		$navmap[$pid] = $miid;
	}
	if($post['Title']=='Home'){
		update_option( 'page_on_front', $pid);
		update_option( 'show_on_front', 'page');
	}
	if($post['Title']=='Blog'){
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
?>