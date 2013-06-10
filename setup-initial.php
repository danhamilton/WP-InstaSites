<?php				
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		update_option('bapi_site_cdn_domain', $_POST['bapi_site_cdn_domain']);
		update_option('api_key', $_POST['api_key']);
		update_option('bapi_sitelive', $_POST['bapi_sitelive']=='on');
		update_option('bapi_language', $_POST['bapi_language']);
		update_option('bapi_baseurl', $_POST['bapi_baseurl']);
		update_option('bapi_secureurl', $_POST['bapi_secureurl']);
		update_option('bapi_cloudfronturl', $_POST['bapi_cloudfronturl']);
		
		echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
	}
	
	$bapi_baseurl = 'connect.bookt.com';
	if(get_option('bapi_baseurl')){ $bapi_baseurl = get_option('bapi_baseurl'); }
	if(empty($bapi_baseurl)){ $bapi_baseurl = 'connect.bookt.com'; }
	
	$cdn_url = get_option('home');
	if(get_option('bapi_site_cdn_domain')){ $cdn_url = get_option('bapi_site_cdn_domain'); }
	
	$surl = '';
	if(get_option('bapi_secureurl')){ $surl = get_option('bapi_secureurl'); }
	
	$sitelive = get_option('bapi_sitelive');		
	$sitelive = empty($sitelive) ? '' : 'checked';
	
	$cloudfronturl = get_option('bapi_cloudfronturl');		
?> 
<script type="text/javascript" src="<?= plugins_url('/js/jquery.1.9.1.min.js', __FILE__) ?>" ></script>
<script type="text/javascript">
	var baseURL = 'https://connect.bookt.com'; // TODO: (JACOB) set this to bapi_baseurl
	$(document).ready(function(){
		$('#validate-apikey').click(function() {
			var apikey = $('#apikey').val();
			if (apikey===null || apikey=='') {
				alert("This is not a valid api key");
				return;
			}
			var url = baseURL + "/js/bapi.js?apikey=" + apikey;
			$.ajax(url)
				.done(function() { alert("This is a valid api key"); })
				.fail(function() { alert("This is not a valid api key"); });
		});
	});
</script>
<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin - Initial Setup</h2>
<form method="post">
<table class="form-table">
<tr valign="top">
	<td scope="row">Site URL</td>
	<td><input type="text" name="bapi_site_cdn_domain" size="60" value="<?php echo $cdn_url; ?>" /></td>
</tr>
<tr valign="top">
	<td scope="row">Site Live?</td>
	<td><input type="checkbox" name="bapi_sitelive" size="60" <?php echo $sitelive; ?> /></td>
</tr>
<tr valign="top">
	<td scope="row">API Key:</td>
	<td><input type="text" name="api_key" id="apikey" size="60" value="<?php echo get_option('api_key'); ?>" />
		<a href="javascript:void(0)" id="validate-apikey">Validate</a>
	</td>
</tr>
<tr valign="top">
	<td scope="row">Language:</td>
	<td><input type="text" name="bapi_language" size="60" value="<?php echo get_option('bapi_language'); ?>" /></td>
</tr>
<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
	<td scope="row">BAPI Base URL:</td>
	<td><input type="text" name="bapi_baseurl" size="60" value="<?php echo $bapi_baseurl; ?>" /></td>
</tr>
<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
	<td scope="row">Secure Site URL:</td>
	<td><input type="text" name="bapi_secureurl" size="60" value="<?php echo $surl; ?>" /></td>
</tr>
<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
	<td scope="row">Cloudfront URL:</td>
	<td><input type="text" name="bapi_cloudfronturl" size="60" value="<?php echo $cloudfronturl; ?>" /></td>
</tr>
<tr>
	<td colspan="2"><em>If you do not already have an API key for Bookt, please contact <a href="mailto:support@bookt.com?subject=API%20Key%20-%20Wordpress%20Plugin">support@bookt.com</a> to obtain an API key.</em></td>
</tr>
</table>
<div class="clear"></div>
<?php submit_button(); ?>
</form>
</div>
