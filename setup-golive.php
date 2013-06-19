<?php	
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//update_option('bapi_site_cdn_domain', $_POST['bapi_site_cdn_domain']);
		echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
	}	
?> 
<script type="text/javascript">
	jQuery(document).ready(function($){
	});	
</script>
<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin - Go Live!</h2>
<form method="post">

<br />
<h3>Instructions</h3>
<p>
To go live, you must make changes with your DNS provider.  If you are unable to make these changes, please contact Bookt Support.
<table class="form-table" border="1">
<tr>
	<th><strong>Record Type</strong></th>
	<th><strong>Setting</strong></th>	
	<th><strong>Value</strong></th>
</tr>
<tr>
	<td>A Record</td>
	<td>@ (Wildcard)</td>
	<td>137.117.72.13</td>
</tr>
<tr>
	<td>CNAME Record</td>
	<td>www</td>
	<td><?php echo get_option('bapi_cloudfronturl') ?></td>
</tr>
</table>
</p>
<div class="clear"></div>
<?php submit_button(); ?>
</form>
</div>
