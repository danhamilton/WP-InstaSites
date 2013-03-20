<?php

function bapi_create_menu() {
	//create new top-level menu
	add_menu_page('Bookt API Plugin Settings', 'Bookt API', 'administrator', __FILE__, 'bapi_settings_page',plugins_url('/img/icon.png', __FILE__));

	//call register settings function
	add_action('admin_init','bapi_options_init');
}

function bapi_options_init(){
	register_setting('bapi_options','api_key');
	register_setting('bapi_options','bapi_language');
	register_setting('bapi_options','solution_id');
	register_setting('bapi_options','bapi_baseurl');	
	register_setting('bapi_options','bapi_custom_tmpl_loc');
	register_setting('bapi_options','bapi_site_cdn_domain'); 
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
	$cdn_url = get_option('home');
	if(get_option('bapi_site_cdn_domain')){
		$cdn_url = get_option('bapi_site_cdn_domain');
	}
	
	$bapi_baseurl = 'connect.bookt.com';
	if(get_option('bapi_baseurl')){
		$bapi_baseurl = get_option('bapi_baseurl');
	}
	if(empty($bapi_baseurl)){
		$bapi_baseurl = 'connect.bookt.com';
	}
?>
<style type="text/css">
	.available-tags ul { margin:0; padding:0; }
	.available-tags ul li { margin:0; padding:0; }
</style>
<div class="wrap">
<h1><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></h1>
<h2>Bookt API Plugin Settings</h2>
	<!-- Use h4 below for debug messages -->
	<h4></h4>
    <form method="post" action="options.php" id="bapi-options-form" enctype="multipart/form-data">
    	<input type="hidden" name="update_action" id="bapi-update-action" value="" />
        <?php settings_fields( 'bapi_options' ); ?>
        <table class="form-table">
		<tr valign="top">
			<th scope="row">Solution ID</th>
			<td><input type="text" name="solution_id" size="6" value="<?php echo get_option('solution_id'); ?>" /></td>
		</tr> 
		<tr valign="top">
			<th scope="row">API Key</th>
			<td><input type="text" name="api_key" size="60" value="<?php echo get_option('api_key'); ?>" /></td>
		</tr>
		<tr valign="top">
			<th scope="row">Language</th>
			<td><input type="text" name="bapi_language" size="60" value="<?php echo get_option('bapi_language'); ?>" /></td>
		</tr>
		<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
			<th scope="row">BAPI Base URL</th>
			<td><input type="text" name="bapi_baseurl" size="60" value="<?php echo $bapi_baseurl; ?>" /></td>
		</tr>
		<tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
			<th scope="row">CDN Site URL</th>
			<td><input type="text" name="bapi_site_cdn_domain" size="60" value="<?php echo $cdn_url; ?>" /></td>
		</tr>
		<tr>
			<td colspan="2"><em>If you do not already have an API key for Bookt, please contact <a href="mailto:support@bookt.com?subject=API%20Key%20-%20Wordpress%20Plugin">support@bookt.com</a> to obtain an API key.</em></td>
		</tr>
        </table>
        <h3>Slideshow Options</h3>
        <table class="form-table">
            <tr>
            <th scope="row">Slide 1</th>
            <td>
            	Image: <input type="text" id='bapi_slideshow_image1' name="bapi_slideshow_image1" size="60" value="<?php echo get_option('bapi_slideshow_image1'); ?>" /><input type="button" id="image-pick1" name="image-pick1" value="Select Image" /><br/>
                Caption: <input type="text" id='bapi_slideshow_caption1' name="bapi_slideshow_caption1" size="58" value="<?php echo get_option('bapi_slideshow_caption1'); ?>" />
            </td>
            </tr>
            <tr>
            <th scope="row">Slide 2</th>
            <td>
            	Image: <input type="text" id='bapi_slideshow_image2' name="bapi_slideshow_image2" size="60" value="<?php echo get_option('bapi_slideshow_image2'); ?>" /><input type="button" id="image-pick2" name="image-pick2" value="Select Image" /><br/>
                Caption: <input type="text" id='bapi_slideshow_caption2' name="bapi_slideshow_caption2" size="58" value="<?php echo get_option('bapi_slideshow_caption2'); ?>" />
            </td>
            </tr>
            <tr>
            <th scope="row">Slide 3</th>
            <td>
            	Image: <input type="text" id='bapi_slideshow_image3' name="bapi_slideshow_image3" size="60" value="<?php echo get_option('bapi_slideshow_image3'); ?>" /><input type="button" id="image-pick3" name="image-pick3" value="Select Image" /><br/>
                Caption: <input type="text" id='bapi_slideshow_caption3' name="bapi_slideshow_caption3" size="58" value="<?php echo get_option('bapi_slideshow_caption3'); ?>" />
            </td>
            </tr>
            <tr>
            <th scope="row">Slide 4</th>
            <td>
            	Image: <input type="text" id='bapi_slideshow_image4' name="bapi_slideshow_image4" size="60" value="<?php echo get_option('bapi_slideshow_image4'); ?>" /><input type="button" id="image-pick4" name="image-pick4" value="Select Image" /><br/>
                Caption: <input type="text" id='bapi_slideshow_caption4' name="bapi_slideshow_caption4" size="58" value="<?php echo get_option('bapi_slideshow_caption4'); ?>" />
            </td>
            </tr>
            <tr>
            <th scope="row">Slide 5</th>
            <td>
            	Image: <input type="text" id='bapi_slideshow_image5' name="bapi_slideshow_image5" size="60" value="<?php echo get_option('bapi_slideshow_image5'); ?>" /><input type="button" id="image-pick5" name="image-pick5" value="Select Image" /><br/>
                Caption: <input type="text" id='bapi_slideshow_caption5' name="bapi_slideshow_caption5" size="58" value="<?php echo get_option('bapi_slideshow_caption5'); ?>" />
            </td>
            </tr>
            <tr>
            <th scope="row">Slide 6</th>
            <td>
            	Image: <input type="text" id='bapi_slideshow_image6' name="bapi_slideshow_image6" size="60" value="<?php echo get_option('bapi_slideshow_image6'); ?>" /><input type="button" id="image-pick6" name="image-pick6" value="Select Image" /><br/>
                Caption: <input type="text" id='bapi_slideshow_caption6' name="bapi_slideshow_caption6" size="58" value="<?php echo get_option('bapi_slideshow_caption6'); ?>" />
            </td>
            </tr>
        </table>
        <?php // property_list(); ?>
        <?php submit_button(); ?>
    </form>
    <?php
	if(is_super_admin()){
		?>
    <hr />
    <h4>The following section is only available to Super Admins</h4>
    <hr />
	
    <h3>Bulk Update Actions</h3>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Update All Properties" onClick="javascript:update_all_properties()"> <span id="loading-update"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    	<p class="submit" style="float:left;margin-left:20px;">
        	<input class="button-primary import" value="Import All Properties" data-action="import" data-entity="property" data-template="tmpl-properties-detail" data-parentmenu="bapi_search"> 
			<span id="loading-import"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
    <div class="clear"></div>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Update Developments" onClick="javascript:update_all_developments()"> <span id="loading-update-dev"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    	<p class="submit" style="float:left;margin-left:20px;">
        	<input class="button-primary import" value="Import All Developments" data-action="import" data-entity="development" data-template="tmpl-development-detail" data-parentmenu=""> 
			<span id="loading-import-dev"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
    <div class="clear"></div>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Update Specials" onClick="javascript:update_all_specials()"> <span id="loading-update-specials"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    	<p class="submit" style="float:left;margin-left:20px;">
        	<input class="button-primary" value="Import Specials" onClick="javascript:import_all_specials()"> <span id="loading-import-specials"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
    <div class="clear"></div>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Update Searches" onClick="javascript:update_all_searches()"> <span id="loading-update-searches"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    	<p class="submit" style="float:left;margin-left:20px;">
        	<input class="button-primary" value="Import Searches" onClick="javascript:import_all_searches()"> <span id="loading-import-searches"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
    <div class="clear"></div>
    <div class="clear"></div>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Update Attractions" onClick="javascript:update_all_attractions()"> <span id="loading-update-attractions"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    	<p class="submit" style="float:left;margin-left:20px;">
        	<input class="button-primary" value="Import Attractions" onClick="javascript:import_all_attractions()"> <span id="loading-import-attractions"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
    <div class="clear"></div>
    <h3>Initial Configuration</h3>
	<small>Note: Permalink Settings should be set to Post name for the menu structure to function correctly.</small>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary import" value="Create Default Pages" data-action="initial_import"> 
			<span id="loading-initial"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
	
	<div id="dlg-result" style="display:none; width:600px">
		<div id="dlg-txtresult" style="padding:10px"></div>
	</div>
	
    <?php
	}
	?>
</div>
<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__) ?>" />
<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ad-gallery.min.css', __FILE__) ?>" />

<script type="text/javascript" src="<?= plugins_url('/js/jquery.1.9.1.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-migrate-1.0.0.min.js', __FILE__) ?>" ></script>		
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-1.10.2.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-i18n.min.js', __FILE__) ?>" ></script>			
<script type="text/javascript">
	function toggle_template(id){
		$('#bapi-options-hidden_'+id).css('display','');
		$('#bapi-options-shown_'+id).css('display','none');
	}
	function toggle_template_off(id){
		$('#bapi-options-hidden_'+id).css('display','none');
		$('#bapi-options-shown_'+id).css('display','');
	}	
	
	$(document).ready(function($){
	
		$(".import").on("click", function () {			
			if (confirm("Are you sure you want to import this data?")) {
				var url = '<?= plugins_url('/import.php', __FILE__) ?>' + 
					'?apikey=<?= getbapiapikey() ?>' +
					'&bapiurl=<?= urlencode(getbapiurl()) ?>' +
					'&action=' + $(this).attr("data-action") + 
					'&entity=' + $(this).attr("data-entity") + 
					'&template=' + $(this).attr("data-template") + 
					'&parent=' + $(this).attr("data-parentmenu");
				console.log(url);
				$('#loading-initial img').show();
				$.get(url, function(res) {					
					$('#dlg-txtresult').html(res);
					$('#dlg-result').dialog({width:700});
					$('#loading-initial img').hide();
				});
			}
		});
	
		$('input#bapi_slideshow_image1,input#image-pick1').click(function(){
			//alert('test');
			wp.media.editor.send.attachment = function(props, attachment){
				$('input#bapi_slideshow_image1').val(attachment.url);
			}
			wp.media.editor.open(this);
			return false;
		});
		$('input#bapi_slideshow_image2,input#image-pick2').click(function(){
			//alert('test');
			wp.media.editor.send.attachment = function(props, attachment){
				$('input#bapi_slideshow_image2').val(attachment.url);
			}
			wp.media.editor.open(this);
			return false;
		});
		$('input#bapi_slideshow_image3,input#image-pick3').click(function(){
			//alert('test');
			wp.media.editor.send.attachment = function(props, attachment){
				$('input#bapi_slideshow_image3').val(attachment.url);
			}
			wp.media.editor.open(this);
			return false;
		});
		$('input#bapi_slideshow_image4,input#image-pick4').click(function(){
			//alert('test');
			wp.media.editor.send.attachment = function(props, attachment){
				$('input#bapi_slideshow_image4').val(attachment.url);
			}
			wp.media.editor.open(this);
			return false;
		});
		$('input#bapi_slideshow_image5,input#image-pick5').click(function(){
			//alert('test');
			wp.media.editor.send.attachment = function(props, attachment){
				$('input#bapi_slideshow_image5').val(attachment.url);
			}
			wp.media.editor.open(this);
			return false;
		});
		$('input#bapi_slideshow_image6,input#image-pick6').click(function(){
			//alert('test');
			wp.media.editor.send.attachment = function(props, attachment){
				$('input#bapi_slideshow_image6').val(attachment.url);
			}
			wp.media.editor.open(this);
			return false;
		});
	});
</script>
<?php 
} 
?>