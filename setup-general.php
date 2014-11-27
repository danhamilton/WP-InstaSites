<?php

if ( isset( $_POST['reset-data'] ) ) {
	$ent = $_POST['reset-data'];
	if ( $ent == 'soldata' ) {
		update_option( 'bapi_solutiondata_lastmod', 0 );
	}
	if ( $ent == 'seodata' ) {
		update_option( 'bapi_keywords_lastmod', 0 );
	}
	if ( $ent == 'textdata' ) {
		update_option( 'bapi_textdata_lastmod', 0 );
	}
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