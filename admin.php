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

<div id="tabs">
<ul>
		<li><a href="#tabs-1">BAPI Configuration</a></li>
		<li><a href="#tabs-2">Slideshow</a></li>
		<li><a href="#tabs-3">Data Synchronization</a></li>
</ul>
	
    <form method="post" action="options.php" id="bapi-options-form" enctype="multipart/form-data">
		
		<div id="tabs-1">
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
		<div class="clear"></div>
		<?php submit_button(); ?>
		
		<div class="clear"></div>
		<h3>Initial Configuration</h3>
		<small>Note: Permalink Settings should be set to Post name for the menu structure to function correctly.</small>
		<div id="bapi-import-update" style="margin-top:-20px;">
			<p class="submit" style="float:left;">
				<input class="button-primary setuppages" value="Create Default Pages"> 
				<span id="loading-initial"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>
			</p>
		</div>			
		</div>
				
		<div id="tabs-2">
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
        
        <?php submit_button(); ?>
		</div>
    </form>
	
	<div id="tabs-3">
    <h3>Bulk Update Actions</h3>
    <div id="bapi-import-update" style="margin-top:-20px;">
    	<p class="submit" style="float:left;">
        	<input class="button-primary" value="Update All"> <span id="loading-update"><img src="<?= plugins_url('/img/ajax-loader.gif', __FILE__) ?>" style="display:none;" height="20" valign="middle"></span>     
			<input class="button-primary import" value="Import All" data-entity="property" data-template="tmpl-properties-detail" data-parentmenu="bapi_search"> 			
        	<select id="importtype">
				<option value="property">Property</option>
				<option value="development">Development</option>
				<option value="specials">Specials</option>
				<option value="poi">Attractions</option>
				<option value="searches">Searches</option>
			</select>
		</p>
    </div>
    
	<div id="dlg-result" style="display:none; width:600px">
		<div id="dlg-txtresult" style="padding:10px; height:300px; overflow: auto"></div>
	</div>
	
    <?php
		$apiKey = get_option('api_key');
		$language = getbapilangauge();
	?>	
</div>
<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__) ?>" />
<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ad-gallery.min.css', __FILE__) ?>" />

<script type="text/javascript" src="<?= plugins_url('/js/jquery.1.9.1.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-migrate-1.0.0.min.js', __FILE__) ?>" ></script>		
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-1.10.2.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-i18n.min.js', __FILE__) ?>" ></script>			
<script type="text/javascript" src="<?= plugins_url('/js/mustache.min.js', __FILE__) ?>" ></script>			
<script type="text/javascript" src="<?= getbapijsurl($apiKey) ?>"></script>
<script type="text/javascript" src="<?= plugins_url('/bapi/bapi.ui.js', __FILE__) ?>" ></script>		
<script src="<?= getbapiurl() ?>/js/bapi.textdata.js?apikey=<?= $apiKey ?>&language=<?= $language ?>" type="text/javascript"></script>
<script type="text/javascript">		
	BAPI.defaultOptions.baseURL = '<?= getbapiurl() ?>';
	BAPI.init('<?= $apiKey ?>');			
</script>
<script type="text/javascript">

	function getImportParams(entity) {
		if (entity == "property") {
			return { "entity": entity, "template": "tmpl-properties-detail", "parent": "bapi_search" }
		}
	}
	$(document).ready(function($){
	
		$("#tabs").tabs();  
		
		$(".import").on("click", function () {			
			var entity = $('#importtype').val();
			var params = getImportParams(entity);
			var template = BAPI.templates.get(params.template);			
			if (confirm("Are you sure you want to import this data?")) {				
				$('#dlg-result').dialog({width:700});
				var txtresult = $('#dlg-txtresult');
				txtresult.html('<h5>Importing Data</h5>');
				txtresult.append('<div>Requesting ids from BAPI...</div>');
				BAPI.search(entity, null, function (data) { 
					txtresult.append('<div>BAPI returned ' + data.result.length + ' results.</div>');
					$.each(data.result, function (i, pkid) {
//if (i==0) {
						BAPI.get(pkid, entity, { "avail": 1, "reviews": 1, "seo": 1, "descrip": 1, "rates": 1 }, function(pdata) {
							pdata.config = BAPI.config();
							pdata.textdata = BAPI.textdata;
							var url = '<?= plugins_url('/import.php', __FILE__) ?>';
							params.pkid = pkid;
							params.PrimaryImage = pdata.result[0].PrimaryImage.MediumURL;
							params.BookingURL = pdata.result[0].ContextData.SEO.BookingURL;
							params.DetailURL = pdata.result[0].ContextData.SEO.DetailURL;
							params.Keyword = pdata.result[0].ContextData.SEO.Keyword;
							params.MetaDescrip = pdata.result[0].ContextData.SEO.MetaDescrip;
							params.PageTitle = pdata.result[0].ContextData.SEO.PageTitle;
							params.content = Mustache.to_html(template, pdata);
							BAPI.utils.dopost(url, params, function(res) {
								txtresult.append(res);
							});
						});														
//}
					});					
				});						
			}
		});
	

		var pagedefs = [
			{ "title": "Home", "url": "", "intid": "bapi_home", "parent": "", "order": 1, "template": "page-templates/front-page.php", "content": '/default-content/homepage-content.php', "addtomenu": true }
		];
		//$defpages[] = array("Title"=>"Home", "URL"=>"", "IntID"=>"bapi_home", "Parent"=>'', "Order" => 1, "Template" => 'page-templates/front-page.php', "Content" => '/default-content/homepage-content.php', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Search", "URL"=>"RentalSearch", "IntID"=>"bapi_search", "Parent"=>'', "Order" => 2, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$pagedef = array("Title"=>"All Rentals", "URL"=>"RentalGrid", "IntID"=>"bapi_property_grid", "Parent"=>'Search', "Order" => 1, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/allproperties-php', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Property Finders", "URL"=>"PropertyFinders", "IntID"=>"bapi_property_finders", "Parent"=>'Search', "Order" => 2, "Template" => 'page-templates/content-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Specials", "URL"=>"Specials", "IntID"=>"bapi_specials", "Parent"=>'Search', "Order" => 3, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Developments", "URL"=>"Developments", "IntID"=>"bapi_developments", "Parent"=>'Search', "Order" => 4, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Attractions", "URL"=>"Attractions", "IntID"=>"bapi_attractions", "Parent"=>'', "Order" => 3, "Template" => 'page-templates/search-page.php', "Content" => '', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"Services", "URL"=>"Services", "IntID"=>"bapi_services", "Parent"=>'', "Order" => 4, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/ourservices-content.php', "AddToMenu"=>true);
		//$defpages[] = array("Title"=>"About Us", "URL"=>"AboutUs", "IntID"=>"bapi_about_us", "Parent"=>'', "Order" => 5, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/aboutus-content.php', "AddToMenu"=>true);
		/*$defpages[] = array("Title"=>"Blog", "URL"=>"Blog", "IntID"=>"bapi_blog", "Parent"=>'About Us', "Order" => 1, "Template" => 'page-templates/content-page.php', "Content" => '', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Contact Us", "URL"=>"Contact", "IntID"=>"bapi_contact", "Parent"=>'', "Order" => 6, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/contactus-content.php', "AddToMenu"=>true);
		$defpages[] = array("Title"=>"Booking Details", "URL"=>"BookingDetails", "IntID"=>"bapi_booking_detail", "Parent"=>'', "Order" => 7, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/booking-detail-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Make a Payment", "URL"=>"BookingPayment", "IntID"=>"bapi_booking_payment", "Parent"=>'', "Order" => 8, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/booking-payment-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Booking Confirmation", "URL"=>"BookingConfirmation", "IntID"=>"bapi_booking_confirm", "Parent"=>'', "Order" => 9, "Template" => 'page-templates/full-width.php', "Content" => '/default-content/booking-confirmation-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Rental Policy", "URL"=>"RentalPolicy", "IntID"=>"bapi_booking_terms", "Parent"=>'', "Order" => 10, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/rental-policy-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Privacy Policy", "URL"=>"PrivacyPolicy", "IntID"=>"bapi_site_privacy", "Parent"=>'', "Order" => 11, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/privacy-policy-content.php', "AddToMenu"=>false);
		$defpages[] = array("Title"=>"Terms of Use", "URL"=>"TermsOfUse", "IntID"=>"bapi_site_terms", "Parent"=>'', "Order" => 12, "Template" => 'page-templates/content-page.php', "Content" => '/default-content/tos-content.php', "AddToMenu"=>false);
		//$defpages[] = array("Title"=>"Owner Login", "URL"=>"/Owners", "IntID"=>"bapi_owners", "Parent"=>''); //TO be added to footer menu only
			*/
		$(".setuppages").on("click", function () {			
			if (confirm("Are you sure you want to setup the menu system")) {
				$('#dlg-result').dialog({width:700});
				var txtresult = $('#dlg-txtresult');
				txtresult.html('<h5>Setting up menu system</h5>');
				$.each(pagedefs, function (i, pagedef) {
					var url = '<?= plugins_url('/init.php', __FILE__) ?>?' + $.param(pagedef);
					$.get(url, function(data) {
						txtresult.append(data);						
					});					
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