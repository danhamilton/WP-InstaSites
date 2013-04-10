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
	register_setting('bapi_options','bapi_solutiondata');
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
		<li><a href="#tabs-4">Solution Info (Debug)</a></li>
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
			<p class="submit" style="float:left;"><input class="button-primary setuppages" value="Create Default Pages"></p>
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
		<div class="clear"></div>
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
	<div class="clear"></div>
	</div>
    
	<div id="dlg-result" style="display:none; width:600px">
		<div id="dlg-txtresult" style="padding:10px; height:300px; overflow: auto"></div>
	</div>
	
	
	<div id="tabs-4">
    <h3>Solution Data</h3>
		<div style="padding:10px; height:300px; overflow: auto">
		<?php			
			echo "<pre>";
            print_r(getbapisolutiondata()); 
            echo "</pre>";
		?>
		</div>
	</div>
	
    <?php
		$apiKey = get_option('api_key');
		$language = getbapilanguage();
		$gmapkey = getGoogleMapKey();
	?>	
</div>
<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__) ?>" />
<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ad-gallery.min.css', __FILE__) ?>" />

<script type="text/javascript" src="<?= plugins_url('/js/jquery.1.9.1.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-migrate-1.0.0.min.js', __FILE__) ?>" ></script>		
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-1.10.2.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-i18n.min.js', __FILE__) ?>" ></script>			
<script type="text/javascript" src="<?= plugins_url('/js/mustache.min.js', __FILE__) ?>" ></script>			
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?= $gmapkey ?>&sensor=false"></script>
<script type="text/javascript" src="<?= getbapijsurl($apiKey) ?>"></script>
<script type="text/javascript" src="<?= plugins_url('/bapi/bapi.ui.js', __FILE__) ?>" ></script>		
<script src="<?= getbapiurl() ?>/js/bapi.textdata.js?apikey=<?= $apiKey ?>&language=<?= $language ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= plugins_url('/bapi.templates.php', __FILE__) ?>" ></script>	
<script type="text/javascript">		
	BAPI.defaultOptions.baseURL = '<?= getbapiurl() ?>';
	BAPI.init('<?= $apiKey ?>');			
</script>
<script type="text/javascript">

	function getImportParams(entity) {
		if (entity == "property") {
			return { "entity": entity, "template": "tmpl-properties-detail", "parent": "bapi_search" }
		}
		if (entity == 'development') {
			return { "entity": entity, "template": "tmpl-developments-detail", "parent": "bapi_search" }
		}
		if (entity == 'specials') {
			return { "entity": entity, "template": "tmpl-specials-detail", "parent": "bapi_search" }
		}
		if (entity == 'poi') {
			return { "entity": entity, "template": "tmpl-attractions-detail", "parent": "bapi_search" }
		}
		if (entity == 'searches') {
			return { "entity": entity, "template": "tmpl-searches-detail", "parent": "bapi_search" }
		}
	}
	$(document).ready(function($){
	
		$("#tabs").tabs();  
		
		$(".import").on("click", function () {			
			var entity = $('#importtype').val();
			var params = getImportParams(entity);
			var template = BAPI.templates.get(params.template);			
			if (typeof(template)==="undefined") {
				return alert('Unable to find the template: ' + params.template);
			}
			
			if (confirm("Are you sure you want to import this data?")) {				
				$('#dlg-result').dialog({width:700});
				var txtresult = $('#dlg-txtresult');
				txtresult.html('<h5>Importing Data</h5>');
				txtresult.append('<div>Requesting ids from BAPI...</div>');
				BAPI.search(entity, null, function (data) { 
					txtresult.append('<div>BAPI returned ' + data.result.length + ' results.</div>');
					$.each(data.result, function (i, pkid) {
//if (i==0) {
						BAPI.get(pkid, entity, { "nearbyprops": 1, "avail": 1, "reviews": 1, "seo": 1, "descrip": 1, "rates": 1, "poi": 1 }, function(pdata) {
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
			{ "title": "Home", "url": "", "intid": "bapi_home", "parent": "", "order": 1, "template": "page-templates/front-page.php", "content": '/default-content/home.php', "addtomenu": true },
			{ "title": "Search", "url": "rentalsearch", "intid": "bapi_search", "parent": "", "order": 2, "template": "page-templates/search-page.php", "content": '/default-content/rentalsearch.php', "addtomenu": true },
				{ "title": "All Rentals", "url": "allrentals", "intid": "bapi_property_grid", "parent": "rentalsearch", "order": 1, "template": "page-templates/full-width.php", "content": '/default-content/allrentals.php', "addtomenu": true },
				{ "title": "Search Buckets", "url": "searchbuckets", "intid": "bapi_search_buckets", "parent": "rentalsearch", "order": 2, "template": "page-templates/full-width.php", "content": '/default-content/propertyfinders.php', "addtomenu": true },
				{ "title": "Specials", "url": "specials", "intid": "bapi_specials", "parent": "rentalsearch", "order": 3, "template": "page-templates/full-width.php", "content": '/default-content/specials.php', "addtomenu": true },			
				{ "title": "Developments", "url": "developments", "intid": "bapi_developments", "parent": "rentalsearch", "order": 4, "template": "page-templates/search-page.php", "content": '/default-content/developments.php', "addtomenu": true },
				{ "title": "My List", "url": "mylist", "intid": "bapi_mylist", "parent": "rentalsearch", "order": 5, "template": "page-templates/search-page.php", "content": '/default-content/mylist.php', "addtomenu": false },
			{ "title": "Attractions", "url": "attractions", "intid": "bapi_attractions", "parent": "", "order": 3, "template": "page-templates/full-width.php", "content": '/default-content/attractions.php', "addtomenu": true },
			{ "title": "Services", "url": "services", "intid": "bapi_services", "parent": "", "order": 4, "template": "page-templates/full-width.php", "content": '/default-content/services.php', "addtomenu": true },
			{ "title": "About Us", "url": "aboutus", "intid": "bapi_about_us", "parent": "", "order": 5, "template": "page-templates/full-width.php", "content": '/default-content/aboutus.php', "addtomenu": true },
				{ "title": "Blog", "url": "blog", "intid": "bapi_blog", "parent": "aboutus", "order": 1, "template": "page-templates/full-width.php", "content": '', "addtomenu": true },
			{ "title": "Contact Us", "url": "contact", "intid": "bapi_contact", "parent": "", "order": 6, "template": "page-templates/full-width.php", "content": '/default-content/contactus.php', "addtomenu": true },
			{ "title": "Make Booking", "url": "makebooking", "intid": "bapi_makebooking", "parent": "", "order": 7, "template": "page-templates/full-width.php", "content": '/default-content/makebooking.php', "addtomenu": false },
			{ "title": "Make a Payment", "url": "makepayment", "intid": "bapi_makepayment", "parent": "", "order": 8, "template": "page-templates/full-width.php", "content": '/default-content/makepayment.php', "addtomenu": false },
			{ "title": "Booking Confirmation", "url": "bookingconfirmation", "intid": "bapi_booking_confirm", "parent": "", "order": 9, "template": "page-templates/full-width.php", "content": '/default-content/bookingconfirmation.php', "addtomenu": false },
			{ "title": "Rental Policy", "url": "rentalpolicy", "intid": "bapi_rental_policy", "parent": "", "order": 10, "template": "page-templates/full-width.php", "content": '/default-content/rentalpolicy.php', "addtomenu": false },
			{ "title": "Privacy Policy", "url": "privacypolicy", "intid": "bapi_privacy_policy", "parent": "", "order": 11, "template": "page-templates/full-width.php", "content": '/default-content/privacypolicy.php', "addtomenu": false },
			{ "title": "Terms of Use", "url": "termsofuse", "intid": "bapi_tos", "parent": "", "order": 12, "template": "page-templates/full-width.php", "content": '/default-content/termsofuse.php', "addtomenu": false }
		];
		//$defpages[] = array("Title"=>"Owner Login", "URL"=>"/Owners", "IntID"=>"bapi_owners", "Parent"=>''); //TO be added to footer menu only

		$(".setuppages").on("click", function () {			
			if (confirm("Are you sure you want to setup the menu system")) {
				$('#dlg-result').dialog({width:700});
				var txtresult = $('#dlg-txtresult');
				txtresult.html('<h5>Setting up menu system</h5>');
				var url = '<?= plugins_url('/init.php', __FILE__) ?>';
				BAPI.utils.dopost(url, { "pagedefs": pagedefs }, function(res) {
					txtresult.append(res);
				});
				/*
				$.each(pagedefs, function (i, pagedef) {
					var url = '<?= plugins_url('/init.php', __FILE__) ?>?' + $.param(pagedef);
					$.get(url, function(data) {
						txtresult.append(data);
					});					
				});*/							
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