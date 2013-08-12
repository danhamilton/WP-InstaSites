<?php				
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {		
		update_option('bapi_global_header', $_POST['bapi_global_header']);
		bapi_wp_site_options();
		echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
	}
global $bapi_all_options;
?> 
<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin - Advanced Options</h2>
<form method="post">
<table class="form-table">
<tr valign="top">
	<td scope="row">Global Header Scripts<br/><em><small>JavaScript must be contained within &lt;script&gt; tags.</small></em></td>
	<td><textarea name="bapi_global_header" id="bapi_global_header" cols="80" rows="8"><?=  $bapi_all_options['bapi_global_header'] ?></textarea></td>
</tr>
</table>
<div class="clear"></div>
<?php submit_button(); ?>
</form>
</div>
