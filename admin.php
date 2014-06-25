<?php

if(isset($_POST['reset-data'])){
	$ent = $_POST['reset-data'];
	if($ent=='soldata'){
		update_option( 'bapi_solutiondata_lastmod', 0 );
	}
	if($ent=='seodata'){
		update_option( 'bapi_keywords_lastmod', 0 );
	}
	if($ent=='textdata'){
		update_option( 'bapi_textdata_lastmod', 0 );
	}
	
	
}

function bapi_create_menu() {
	//create new top-level menu
	$parent = get_adminurl('admin.php');
	add_menu_page('InstaSite Plugin Settings', 'InstaSite', 'administrator', __FILE__, 'bapi_settings_page', plugins_url('/img/icon.png', __FILE__));	
	
	add_submenu_page($parent, 'General','General', 'administrator', get_adminurl('admin.php'));	
	add_submenu_page($parent, 'Property & Search Settings','Property & Search Settings', 'administrator', get_adminurl('setup-sitesettings.php'));
	add_submenu_page($parent, 'Slideshow','Slideshow', 'administrator', get_adminurl('setup-slideshow.php'));	
	add_submenu_page($parent, 'Take me Live','Take me Live', 'administrator', get_adminurl('setup-golive.php'));
	add_submenu_page($parent, 'Data Sync','Data Sync', 'administrator', get_adminurl('setup-sync.php'));	
	add_submenu_page($parent, 'Initial Setup','Initial Setup', 'administrator', get_adminurl('setup-initial.php'));	
	add_submenu_page($parent, 'Advanced Options','Advanced', 'administrator', get_adminurl('setup-advanced.php'));	
	
	//call register settings function
	add_action('admin_init','bapi_options_init');
}

function bapi_options_init(){
	// register the core settings
	register_setting('bapi_options','api_key');
	register_setting('bapi_options','bapi_language');
	register_setting('bapi_options','bapi_basueurl');
	register_setting('bapi_options','bapi_secureurl');	
	register_setting('bapi_options','bapi_solutiondata');
	register_setting('bapi_options','bapi_solutiondata_lastmod');	
	register_setting('bapi_options','bapi_textdata');
	register_setting('bapi_options','bapi_textdata_lastmod');
	register_setting('bapi_options','bapi_site_cdn_domain'); 
	register_setting('bapi_options','bapi_cloudfronturl');
	register_setting('bapi_options','bapi_cloudfrontid'); 
	register_setting('bapi_options','bapi_global_header'); 
	register_setting('bapi_options','bapi_sitesettings');
	register_setting('bapi_options','bapi_google_webmaster_htmltag');
	
	// register the slideshow settings
	// register the settings
	register_setting('bapi_options','bapi_slideshow_image1');
	register_setting('bapi_options','bapi_slideshow_image2');
	register_setting('bapi_options','bapi_slideshow_image3');
	register_setting('bapi_options','bapi_slideshow_image4');
	register_setting('bapi_options','bapi_slideshow_image5');
	register_setting('bapi_options','bapi_slideshow_image6');
	register_setting('bapi_options','bapi_slideshow_caption1');
	register_setting('bapi_options','bapi_slideshow_caption2');
	register_setting('bapi_options','bapi_slideshow_caption3');
	register_setting('bapi_options','bapi_slideshow_caption4');
	register_setting('bapi_options','bapi_slideshow_caption5');
	register_setting('bapi_options','bapi_slideshow_caption6');	
	
	// doc template specific settings
	register_setting('bapi_options','bapi_rental_policy');
	register_setting('bapi_options','bapi_rental_policy_lastmod');
	register_setting('bapi_options','bapi_privacy_policy');
	register_setting('bapi_options','bapi_privacy_policy_lastmod');
	register_setting('bapi_options','bapi_terms_of_use');
	register_setting('bapi_options','bapi_terms_of_use_lastmod');
	register_setting('bapi_options','bapi_safe_harbor');
	register_setting('bapi_options','bapi_safe_harbor_lastmod');	
}

function bapi_settings_page() {
	$bapi = getBAPIObj();
	if(!$bapi->isvalid()) {
		$url = '/wp-admin/admin.php?page=' . get_adminurl('initialsetup.php');
		//echo $url;
		//echo '<script type="text/javascript">window.location.href="' . $url . '"</script>';
		//exit();
	}
	$lastmod_soldata = get_option('bapi_solutiondata_lastmod');
	$lastmod_textdata = get_option('bapi_textdata_lastmod');
	$lastmod_seodata = get_option('bapi_keywords_lastmod');
	$lastmod_soldata = (!empty($lastmod_soldata) ? date('r',$lastmod_soldata) : "N/A");
	$lastmod_textdata = (!empty($lastmod_textdata) ? date('r',$lastmod_textdata) : "N/A");
	$lastmod_seodata = (!empty($lastmod_seodata) ? date('r',$lastmod_seodata) : "N/A");
	
	$soldata = is_super_admin() ? get_option('bapi_solutiondata') : 'N/A';
	$textdata = is_super_admin() ? get_option('bapi_textdata') : 'N/A';
	$seodata = is_super_admin() ? get_option('bapi_keywords_array') : 'N/A';		
	
?>

<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__) ?>" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#viewraw-soldata').click(function() { $("#dlg-soldata").dialog({ width: 540});});	
		$('#viewraw-textdata').click(function() { $("#dlg-textdata").dialog({ width: 540 });});	
		$('#viewraw-seodata').click(function() { $("#dlg-seodata").dialog({ width: 540 });});
		$('#reset-soldata').click(function() { $("#reset-soldata-form").submit(); });	
		$('#reset-textdata').click(function() { $("#reset-textdata-form").submit(); });	
		$('#reset-seodata').click(function() { $("#reset-seodata-form").submit(); });	
	});
</script>
<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo-im.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin</h2>
<table class="form-table">
<tr valign="top">
	<td scope="row">Site Live:</td>
	<td><?php $st=array(); echo $sitelive; ?></td>
</tr>
<tr valign="top">
	<td scope="row">API Key:</td>
	<td><?php echo get_option('api_key'); ?></td>
</tr>
<tr valign="top">
	<td scope="row">Language:</td>
	<td><?php echo get_option('bapi_language'); ?></td>	
</tr>
<tr valign="top">
	<th scope="row">Solution Data Last Sync:</th>
	<td><?php echo $lastmod_soldata; ?>
		<a href="javascript:void(0)" id="viewraw-soldata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">View Raw</a>
		<a href="javascript:void(0)" id="reset-soldata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">Reset</a>
	</td>
</tr>
<tr valign="top">
	<th scope="row">SEO Last Sync:</th>
	<td><?php echo $lastmod_seodata; ?>
		<a href="javascript:void(0)" id="viewraw-seodata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">View Raw</a>
		<a href="javascript:void(0)" id="reset-seodata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">Reset</a>
	</td>
</tr>
<tr valign="top">
	<th scope="row">Text Data Last Sync:</th>
	<td><?php echo $lastmod_textdata; ?>
		<a href="javascript:void(0)" id="viewraw-textdata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">View Raw</a>
		<a href="javascript:void(0)" id="reset-textdata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">Reset</a>
	</td>
</tr>
<tr>
	<td colspan="2"><em>If you do not already have an API key for Bookt, please contact <a href="mailto:support@bookt.com?subject=API%20Key%20-%20Wordpress%20Plugin">support@bookt.com</a> to obtain an API key.</em></td>
</tr>
</table>
</div>

<div id="dlg-textdata" title="Text Data" style="display:none">
<textarea style="width:500px;height:300px"><?php echo htmlentities($textdata); ?></textarea>
</div>

<div id="dlg-soldata" title="Solution Data" style="display:none">
<textarea style="width:500px;height:300px"><?php echo htmlentities($soldata); ?></textarea>
</div>

<div id="dlg-seodata" title="SEO Data" style="display:none">
<textarea style="width:500px;height:300px"><?php echo htmlentities($seodata); ?></textarea>
</div>

<div id="hidden-reset-forms" style="display:none">
	<form id="reset-soldata-form" method="post">
		<input type="hidden" name="reset-data" value="soldata" />
		<input type="hidden" name="bapi_solutiondata" value="" />
		<input type="hidden" name="bapi_solutiondata_lastmod" value="0" />
	</form>
	<form id="reset-seodata-form" method="post">
		<input type="hidden" name="reset-data" value="seodata" />
		<input type="hidden" name="bapi_keywords_array" value="" />
		<input type="hidden" name="bapi_keywords_lastmod" value="0" />
	</form>
	<form id="reset-textdata-form" method="post">
		<input type="hidden" name="reset-data" value="textdata" />
		<input type="hidden" name="bapi_textdata" value="" />
		<input type="hidden" name="bapi_textdata_lastmod" value="0" />
	</form>
</div>

<?php 
} 


function bapi_notify_blog_public(){
	global $bapi_all_options;
	if($bapi_all_options['blog_public']==0){
		echo '<div class="error"><p>Your site is currently hidden to search engines. <a href="/wp-admin/options-reading.php">CLICK HERE</a> to enable <em>Search Engine Visibility</em> and fix this problem.</p></div>';
	}
}
add_action('admin_notices','bapi_notify_blog_public');

//Mantis Ticket 5408 compatible permalinks
function bapi_notify_incompatible_permalinks(){
	$currentPermalinkStructure = get_option('permalink_structure');
	if($currentPermalinkStructure != "/%year%/%monthnum%/%day%/%postname%/" && $currentPermalinkStructure != "/%year%/%monthnum%/%postname%/" && $currentPermalinkStructure != "/%postname%/" ){
		echo '<div id="incompatiblepermalink" class="error"><p>The Permalink settings for your site are not compatible with the InstaSites Plugin. Please <a href="/wp-admin/options-permalink.php">CLICK HERE</a> and select \'Day and name\', \'Month and name\', or \'Post name\'.</p></div>';
	}
}
add_action('admin_notices','bapi_notify_incompatible_permalinks');
// Mantis  Ticket: 5859 Display error notice if site config in InstaSite is mis-matched with InstaApp
// function site_config_error(){
	// $bapi_solutiondata = json_decode(get_option('bapi_solutiondata'),true);
	// $bapi_cdn_domain = get_option('bapi_site_cdn_domain');
	// $bapi_secure_url = get_option('bapi_secureurl');
	// $primaryUrl = 'http://'.$bapi_solutiondata['PrimaryURL'];
	// $secureUrl = 'www.'.$bapi_solutiondata['SecureURL'];
	// //bapi_solutiondata.PrimaryURL must equal get_option('bapi_site_cdn_domain')
	// if($primaryUrl !== $bapi_cdn_domain){
			// echo '<div id="mis-match-config" class="error"><p> config in InstaSite is mis-matched with InstaApp Plugin. Please <a href="/wp-admin/admin.php?page=WP-InstaSites/setup-initial.php ">CLICK HERE</a> please enter the correct Site URL.</p></div>';			
	// }
	// if($secureUrl == '' || $secureUrl !== $bapi_secure_url){
		// echo '<div id="mis-match-config" class="error"><p> config in InstaSite is mis-matched with InstaApp Plugin. Please <a href="/wp-admin/admin.php?page=WP-InstaSites/setup-initial.php ">CLICK HERE</a> please enter the correct Secure Site URL.</p></div>';
	// }	
// }
// this function Display error notice if site config in InstaSite is mis-matched 
////add_action('admin_notices','site_config_error');

function bapi_update_incompatible_permalinks_error_notice($oldvalue, $_newvalue){
	if($_newvalue == "/%year%/%monthnum%/%day%/%postname%/" || $_newvalue == "/%year%/%monthnum%/%postname%/" || $_newvalue == "/%postname%/" ){
		?>
		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function() {
			if(jQuery('#incompatiblepermalink').length > 0){
				jQuery('#incompatiblepermalink').remove();
			}
		});
		//]]>
		</script>
		<?php
	}
}
add_action( 'update_option_permalink_structure' , 'bapi_update_incompatible_permalinks_error_notice', 10, 2 );

?>
