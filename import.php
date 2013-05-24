
<?php
	require_once ('../../../wp-load.php');	
	require_once ('sync.php');	
	// get all the post parameters
	$entity = $_POST['entity'];
	$pkid = $_POST['pkid'];
	$primaryimage = $_POST['PrimaryImage'];
	$content = $_POST['content'];
	$content = str_replace("\t", '', $content); // remove tabs
	$content = str_replace("\n", '', $content); // remove new lines
	$content = str_replace("\r", '', $content); // remove carriage returns
	//$content = preg_replace(array('/\r/', '/\n/'), '', $content);
	$bookingurl = $_POST['BookingURL'];
	$detailurl =  $_POST['DetailURL'];
	$keyword = $_POST['Keyword'];
	if(empty($keyword)) {
		$keyword = $pkid;
	}
	$metadescrip = $_POST['MetaDescrip'];
	$title = $_POST['PageTitle'];
	$summary = $metadescrip;	
	
	// This could fail, should probably do some error checking here
	$rootpath = getrootpath($entity);
	$parentid = get_page_by_path($rootpath)->ID;
	
	// try to see if there is an existing post
	$posts_array = getPageForEntity($entity, $pkid, $parentid);
	
	$thepost = array();
	$isnew = true;
	foreach ($posts_array as $page) {
		$thepost['ID'] = $page->ID;
		$isnew = false;
	}
	//print_r("<div>count=" . count($posts_array) . ",parentid=" . $parentid . ", pid=". $thepost['ID'] . "</div>");
	$thepost['post_content'] = $content;
	$thepost['post_excerpt'] = '<img src="' . $primaryimg . '" style="display:block;max-height:420px;" ><br/>' . wp_strip_all_tags($summary);	
	$thepost['post_title'] = wp_strip_all_tags($title);
	$thepost['post_name'] = wp_strip_all_tags($keyword);
	$thepost['post_type'] = 'page';
	$thepost['post_status'] = 'publish';
	$thepost['comment_status'] = 'closed';
	$thepost['post_parent'] = $parentid;
	
	$metak = 'Testing Keyword Meta';
	$metad = $metadescrip;
	if(strlen($metadescrip)<2){
		$metad = 'Testing Keyword Description';
	}
		
	$pagetemplate = getpagetemplate($entity);
	if ($isnew) {
		$pid = wp_insert_post($thepost,$wp_error);
		add_post_meta($pid, 'bapi_last_update', time(), true);
		add_post_meta($pid, 'bapikey', getPageKeyForEntity($entity, $pkid), true);
		add_post_meta($pid, 'bapi_meta_keywords', $metak, true);
		add_post_meta($pid, 'bapi_meta_description', $metad, true);	
		update_post_meta($pid, "_wp_page_template", $pagetemplate);
		print_r('<div>Added post_id=' . $pid . ', title=' . $thepost['post_title'] . ', t=' . $pagetemplate . '</div>');
	}
	else  {
		$pid = $thepost['ID'];
		wp_update_post($thepost,$wp_error);
		add_post_meta($pid, 'bapi_last_update', time(), true);
		add_post_meta($pid, 'bapikey', getPageKeyForEntity($entity, $pkid), true);
		add_post_meta($pid, 'bapi_meta_keywords', $metak, true);
		add_post_meta($pid, 'bapi_meta_description', $metad, true);	
		update_post_meta($pid, "_wp_page_template", $pagetemplate);		
		print_r('<div>Edited post_id=' . $thepost['ID'] . ', title=' . $thepost['post_title'] . ', t=' . $pagetemplate . '</div>');
	}	

	// Helper function
	function getrootpath($entity) {		
		if($entity=='property') {
			return '/rentals/allrentals/';
		}
		elseif($entity=='development') {
			return '/rentals/developments/';
		}
		elseif($entity=='specials') {
			return '/specials/';
		}
		elseif($entity=='poi') {
			return '/attractions/';
		}
		elseif($entity=='searches') {
			return '/rentals/searchbuckets/';
		}
		return '/rentals/';
	}
	
	function getpagetemplate($entity) {
		if($entity=='property') {
			return 'page-templates/property-detail.php';
		}
		elseif($entity=='development') {
			return 'page-templates/other-detail-page.php';
		}
		elseif($entity=='specials') {
			return 'page-templates/other-detail-page.php';
		}
		elseif($entity=='poi') {
			return 'page-templates/other-detail-page.php';
		}
		elseif($entity=='searches') {
			return 'page-templates/other-detail-page.php';
		}
		return 'page-templates/full-width.php';
	}
	
?>