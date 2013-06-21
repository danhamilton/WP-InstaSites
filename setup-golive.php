<?php	
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//update_option('bapi_site_cdn_domain', $_POST['bapi_site_cdn_domain']);
		echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
	}	
	
	$cdn_url = get_option('home');
	if(get_option('bapi_site_cdn_domain')){ $cdn_url = get_option('bapi_site_cdn_domain'); }
	$cdn_url = parse_url($cdn_url);
	//print_r($cdn_url);
?> 
<style type="text/css">
.bapi_expand{
	-webkit-border-radius: 3px;
	border-radius: 3px;
	border-width: 1px;
	border-style: solid;
	margin: 5px 15px 15px 0;
	padding: 0 .6em;
	background-color: #ddd;
	border-color: #ccc;
	cursor: pointer;
	width: 
}
.bapi_expand_hidden{
	display: none;
}
</style>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#gdsetup').click(function(){
			$('#gdsetup .bapi_expand_hidden').css('display','block');
		});
		var nip = $('#bapi_wildcard_ip').html();
		$('#bapi_wildcard_ip_inst').html(nip);
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
	<td id="bapi_wildcard_ip">137.117.72.13</td>
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
<br/>
<div id="gdsetup" class="bapi_expand"><h4>GoDaddy DNS Setup Instructions</h4>
	<div class="bapi_expand_hidden">
	<em>Please note that these instructions are generated based on the current version of GoDaddy's website and DNS management tools. Please contact support@bookt.com to report any discrepancies.</em>
	<ol>
		<li>Go to <a href="http://dcc.godaddy.com">GoDaddy.com</a> and sign in to your account.</li>
		<li>From the <em>Domains</em> management screen, click on the domain name you wish to update.<br/>
			<img src="<?= plugins_url('/img/dns/godaddy-domains-summary.png', __FILE__) ?>"/></li>
		<li>On the <em>Domain Details</em> screen, click on the <em>DNS Zone File</em> tab and then click <em>Edit</em>.  This will open the <em>Zone File Editor</em> in a new browser tab.<br/>
			<img src="<?= plugins_url('/img/dns/godaddy-domain-detail.png', __FILE__) ?>"/></li>
		<li>Locate the <em>A (Host)</em> record for <em>"@"</em> and set it to <strong id="bapi_wildcard_ip_inst"></strong>. Use the <em>Quick Add</em> button if the record does not already exist.<br/>
			<img src="<?= plugins_url('/img/dns/godaddy-zone-editor1.png', __FILE__) ?>"/></li>
		<li>Locate the <em>CNAME (Alias)</em> record for <em>"www"</em> and set it to <strong><?php if(get_option('bapi_cloudfronturl')==''){echo 'the CloudFront URL you will be given before going live';}else{echo get_option('bapi_cloudfronturl');} ?></strong>. Use the <em>Quick Add</em> button if the record does not already exist. <br/>
			<em>If there is already an A (Host) record for "www", you must delete that first before adding a www CNAME record.</em><br/>
			<img src="<?= plugins_url('/img/dns/godaddy-zone-editor2.png', __FILE__) ?>"/></li>
		<li>Save your zone file changes.<br/>
			<img src="<?= plugins_url('/img/dns/godaddy-zone-editor3.png', __FILE__) ?>"/></li>
	</ol>
	<p>Please note that after saving your changes it will take as long as 48 hours for complete global DNS propagation.  In most cases, your live site URL will begin working within just a few minutes.</p>
	<p>Click here to check DNS propagation for your domain: <a class="button" href="http://www.whatsmydns.net/#CNAME/<?= $cdn_url['host'] ?>" target="_blank">DNS Test</a></p>
	</div>
</div>