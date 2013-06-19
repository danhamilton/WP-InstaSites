<?php

function bapi_create_menu() {
	//create new top-level menu
	$parent = get_adminurl('admin.php');
	add_menu_page('InstaSite Plugin Settings', 'InstaSite', 'administrator', __FILE__, 'bapi_settings_page', plugins_url('/img/icon.png', __FILE__));	
	
	add_submenu_page($parent, 'General','General', 'administrator', get_adminurl('admin.php'));	
	add_submenu_page($parent, 'Slideshow','Slideshow', 'administrator', get_adminurl('setup-slideshow.php'));
	add_submenu_page($parent, 'Take me Live','Take me Live', 'administrator', get_adminurl('setup-golive.php'));
	add_submenu_page($parent, 'Data Sync','Data Sync', 'administrator', get_adminurl('setup-sync.php'));	
	add_submenu_page($parent, 'Initial Setup','Initial Setup', 'administrator', get_adminurl('setup-initial.php'));	
	
	//call register settings function
	add_action('admin_init','bapi_options_init');
}

function bapi_options_init(){
	// register the core settings
	register_setting('bapi_options','api_key');
	register_setting('bapi_options','bapi_language');
	register_setting('bapi_options','bapi_sitelive');
	register_setting('bapi_options','bapi_basueurl');
	register_setting('bapi_options','bapi_secureurl');	
	register_setting('bapi_options','bapi_solutiondata');
	register_setting('bapi_options','bapi_solutiondata_lastmod');	
	register_setting('bapi_options','bapi_textdata');
	register_setting('bapi_options','bapi_textdata_lastmod');
	register_setting('bapi_options','bapi_site_cdn_domain'); 
	register_setting('bapi_options','bapi_cloudfronturl'); 
	
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
	
	$slitelive = get_option('bapi_sitelive');
	$sitelive = empty($slitelive) ? 'No' : 'Yes';		
?>

<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__) ?>" />
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#viewraw-soldata').click(function() { $("#dlg-soldata").dialog({ width: 540});});	
		$('#viewraw-textdata').click(function() { $("#dlg-textdata").dialog({ width: 540 });});	
		$('#viewraw-seodata').click(function() { $("#dlg-seodata").dialog({ width: 540 });});	
	});
</script>

<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin</h2>
<table class="form-table">
<tr valign="top">
	<td scope="row">Site Status:</td>
	<td><?php echo $sitelive; ?></td>
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
	<td scope="row">Solution Data Last Sync:</td>
	<td><?php echo $lastmod_soldata; ?>
		<a href="javascript:void(0)" id="viewraw-soldata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">View Raw</a>
	</td>
</tr>
<tr valign="top">
	<td scope="row">SEO Last Sync:</td>
	<td><?php echo $lastmod_seodata; ?>
		<a href="javascript:void(0)" id="viewraw-seodata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">View Raw</a>
	</td>
</tr>
<tr valign="top">
	<th scope="row">Text Data Last Sync:</th>
	<td><?php echo $lastmod_textdata; ?>
		<a href="javascript:void(0)" id="viewraw-textdata" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">View Raw</a>
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

<?php 
} 
?>