<?php

function bapi_create_menu() {
	//create new top-level menu
	add_menu_page('Bookt API Plugin Settings', 'Bookt API', 'administrator', __FILE__, 'bapi_settings_page',plugins_url('/img/icon.png', __FILE__));

	//call register settings function
	add_action('admin_init','bapi_options_init');
}

function bapi_options_init(){
	register_setting('bapi_options','api_key');
	register_setting('bapi_options','solution_id');
	register_setting('bapi_options','property_template');
	register_setting('bapi_options','search_css');
	register_setting('bapi_options','search_template');
	register_setting('bapi_options','update_action'); 
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
	//register_setting('bapi_options','property_category_name');
}

function bapi_option_category($old,$new){
	if($old!=$new){
		wp_create_category($new);
	}
}

function bapi_settings_page() {
	$cdn_url = get_option('home');
	if(get_option('bapi_site_cdn_domain')){
		$cdn_url = get_option('bapi_site_cdn_domain');
	}
	/* Removed after migrating to mustache */
	/*$t = get_option('property_template');
	if(empty($t)){
		$t = file_get_contents(plugins_url('/default-content/property-template.php', __FILE__));
	}
	$p = get_option('property_category_name');
	if(empty($p)){
		$p = "Properties";
	}
	$sc = get_option('search_css');
	if(empty($sc)){
		$sc = file_get_contents(plugins_url('/default-content/search-template.css', __FILE__));
	}
	$st = get_option('search_template');
	if(empty($st)){
		$st = file_get_contents(plugins_url('/default-content/search-template.php', __FILE__));
	}*/
?>
<style type="text/css">
	.available-tags ul { margin:0; padding:0; }
	.available-tags ul li { margin:0; padding:0; }
</style>
<div class="wrap">
<h1><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></h1>
<h2>Bookt API Plugin Settings</h2>
<!-- Use h4 below for debug messages -->
<h4><?php 
//$tmpl = bapi_get_templates();
//echo $tmpl['tmpl-leadrequestform-propertyinquiry'];
 ?></h4>
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
            <td><input type="text" name="api_key" size="60" value="<?php echo get_option('api_key'); ?>" /> 
            </td>
            </tr>
            <tr valign="top" style="<?php if(!is_super_admin()){echo 'display:none;'; } ?>">
            <th scope="row">CDN Site URL</th>
            <td><input type="text" name="bapi_site_cdn_domain" size="60" value="<?php echo $cdn_url; ?>" /> 
            </td>
            </tr>
            <tr>
            <td colspan="2"><em>If you do not already have an API key for Bookt, please contact <a href="mailto:support@bookt.com?subject=API%20Key%20-%20Wordpress%20Plugin">support@bookt.com</a> to obtain an API key.</em></td>
            </tr>
        </table>
        <h3>Slideshow Options</h3>
        <table class="form-table">
            <!--<tr valign="top">
            <th scope="row">Property Post Category</th>
            <td><input type="text" name="property_category_name" size="60" value="<?php //echo $p ?>" /></td>
            </tr>-->
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
     <!--   <h3>Property Options</h3>
        <table class="form-table">
            <!--<tr valign="top">
            <th scope="row">Property Post Category</th>
            <td><input type="text" name="property_category_name" size="60" value="<?php //echo $p ?>" /></td>
            </tr>
            <tr>
            <th scope="row">Property Page Template</th>
            <td>
            	<div id="bapi-options-shown_prop_template">
                	<a href="javascript:toggle_template('prop_template');">Display Template Editor</a>
                </div>
            	<div id="bapi-options-hidden_prop_template" style="display:none;">
                    <a href="javascript:toggle_template_off('prop_template');">Hide</a>
                    <br/>
                    <textarea name="property_template" cols="150" rows="20" style="float:left;"><?php echo $t; ?></textarea>
                </div>
            </td>
            </tr>
        </table>
        <h3>Search Options</h3>
        <table class="form-table">
            <tr>
            <th scope="row">Search Page CSS</th>
            <td>
            	<div id="bapi-options-shown_search_css">
                	<a href="javascript:toggle_template('search_css');">Display CSS Editor</a>
                </div>
            	<div id="bapi-options-hidden_search_css" style="display:none;">
                    <a href="javascript:toggle_template_off('search_css');">Hide</a>
                    <br/>
                    <textarea name="search_css" cols="150" rows="16" style="float:left;"><?php echo $sc; ?></textarea>
                </div>
            </td>
            </tr>
            <tr>
            <th scope="row">Search Page Template</th>
            <td>
            	<div id="bapi-options-shown_search_template">
                	<a href="javascript:toggle_template('search_template');">Display Template Editor</a>
                </div>
            	<div id="bapi-options-hidden_search_template" style="display:none;">
                    <a href="javascript:toggle_template_off('search_template');">Hide</a>
                    <br/>
                    <textarea name="search_template" cols="150" rows="16" style="float:left;"><?php echo $st; ?></textarea>
                </div>
            </td>
            </tr>
        </table>-->
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
        	<input class="button-primary" value="Import All Properties" onClick="javascript:import_all_properties()"> <span id="loading-import"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
    <div class="clear"></div>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Update Developments" onClick="javascript:update_all_developments()"> <span id="loading-update-dev"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    	<p class="submit" style="float:left;margin-left:20px;">
        	<input class="button-primary" value="Import Developments" onClick="javascript:import_all_developments()"> <span id="loading-import-dev"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
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
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Create Default Pages" onClick="javascript:initial_import()"> <span id="loading-initial"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
        </p>
    </div>
    <?php
	}
	?>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
	function toggle_template(id){
		$('#bapi-options-hidden_'+id).css('display','');
		$('#bapi-options-shown_'+id).css('display','none');
	}
	function toggle_template_off(id){
		$('#bapi-options-hidden_'+id).css('display','none');
		$('#bapi-options-shown_'+id).css('display','');
	}
	function update_all_properties(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will affect the content in all posts linked to Bookt API.\n\nExisting content will be replaced using the Property Page Template and current property data from the Bookt platform.\n\nThis action will not remove or add properties");
		if(c){
			$('#bapi-update-action').val('update-props');
			$('#loading-update img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function import_all_properties(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will import all active properties from the Bookt account linked to the curent API key and add them as Pages on this site.\n\nExisting content will not be affected.");
		if(c){
			$('#bapi-update-action').val('import-props');
			$('#loading-import img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function update_all_developments(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will affect the content in all posts linked to Bookt API.\n\nExisting content will be replaced using the Development Page Template and current development data from the Bookt platform.\n\nThis action will not remove or add properties");
		if(c){
			$('#bapi-update-action').val('update-devs');
			$('#loading-update-dev img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function import_all_developments(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will import all active developments from the Bookt account linked to the curent API key and add them as Pages on this site.\n\nExisting content will not be affected.");
		if(c){
			$('#bapi-update-action').val('import-devs');
			$('#loading-import-dev img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function update_all_specials(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will affect the content in all posts linked to Bookt API.\n\nExisting content will be replaced using the Special Page Template and current specials data from the Bookt platform.\n\nThis action will not remove or add properties");
		if(c){
			$('#bapi-update-action').val('update-specials');
			$('#loading-update-specials img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function import_all_specials(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will import all active specials from the Bookt account linked to the curent API key and add them as Pages on this site.\n\nExisting content will not be affected.");
		if(c){
			$('#bapi-update-action').val('import-specials');
			$('#loading-import-specials img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function update_all_searches(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will affect the content in all posts linked to Bookt API.\n\nExisting content will be replaced using the Search Page Template and current specials data from the Bookt platform.\n\nThis action will not remove or add properties");
		if(c){
			$('#bapi-update-action').val('update-searches');
			$('#loading-update-searches img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function import_all_searches(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will import all active searches from the Bookt account linked to the curent API key and add them as Pages on this site.\n\nExisting content will not be affected.");
		if(c){
			$('#bapi-update-action').val('import-searches');
			$('#loading-import-searches img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function update_all_attractions(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will affect the content in all posts linked to Bookt API.\n\nExisting content will be replaced using the Search Page Template and current attractions data from the Bookt platform.\n\nThis action will not remove or add properties");
		if(c){
			$('#bapi-update-action').val('update-attractions');
			$('#loading-update-attractions img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function import_all_attractions(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will import all active attractions from the Bookt account linked to the curent API key and add them as Pages on this site.\n\nExisting content will not be affected.");
		if(c){
			$('#bapi-update-action').val('import-attractions');
			$('#loading-import-attractions img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	function initial_import(){
		var apiKey = '<?= get_option('api_key') ?>';
		//alert(apiKey);
		var c = confirm("This action will add a default set of content pages and link them to your main navigation.\n\nTHIS ACTION SHOULD ONLY BE PERFORMED ONCE DURING INITIAL SETUP!");
		if(c){
			$('#bapi-update-action').val('initial_import');
			$('#loading-initial img').css('display','');
			$('#bapi-options-form #submit').click();
		}
	}
	jQuery(document).ready(function($){
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