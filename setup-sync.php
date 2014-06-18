<?php	
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//update_option('bapi_slideshow_image1', $_POST['bapi_slideshow_image1']);
		//echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
	}	
	getconfig(); // need this to update detail pages
	
	$soldata = json_decode(get_option('bapi_solutiondata'),TRUE);
?> 

<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo-im.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin - Data Sync Setup</h2>

<h3>Initial Configuration</h3>
<div style="margin-top:-5px;">			
	<input class="button-primary setuppages" value="Create Default Pages">
</div>	
<div class="clear"></div>				
<small>Note: Permalink Settings should be set to Post name for the menu structure to function correctly.</small>
<div class="clear"></div>

<br />
<h3>Base URLs</h3>
<small>These base urls define where detail pages will get synced.</small>
<table class="form-table">
<tr valign="top">
	<td scope="row">Property:</td>
	<td><?php echo $soldata["Site"]["BasePropertyURL"]; ?></td>
</tr>
<tr valign="top">
	<td scope="row">Developments:</td>
	<td><?php echo $soldata["Site"]["BaseDevelopmentURL"]; ?></td>
</tr>
<tr valign="top">
	<td scope="row">Attractions:</td>
	<td><?php echo $soldata["Site"]["BasePOIURL"]; ?></td>
</tr>
<tr valign="top">
	<td scope="row">Specials:</td>
	<td><?php echo $soldata["Site"]["BaseSpecialURL"]; ?></td>
</tr>
<tr valign="top">
	<td scope="row">Search Buckets:</td>
	<td><?php echo $soldata["Site"]["BasePropertyFinderURL"]; ?></td>
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Market Areas:</td>
	<td><?php echo $soldata["Site"]["BaseMarketAreaURL"]; ?></td>
</tr>
</table>
<small>Note: Base urls need to be modified in the control panel.</small>

</div>

<div id="dlg-result" style="display:none; width:600px">
	<div id="dlg-txtresult" style="padding:10px; height:300px; overflow: auto"></div>
</div>

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
	jQuery(document).ready(function($){
	
		$("#tabs").tabs();  			
		
		var pagedefs = [
			{ "title": "Home", "url": "", "intid": "bapi_home", "parent": "", "order": 1, "template": "page-templates/front-page.php", "content": '/default-content/home.php', "addtomenu": false },
			{ "title": "Rentals", "url": "rentals", "intid": "bapi_rentals", "parent": "", "order": 2, "template": "page-templates/search-page.php", "content": '', "addtomenu": true },
				{ "title": "Search", "url": "rentalsearch", "intid": "bapi_search", "parent": "rentals", "order": 1, "template": "page-templates/search-page.php", "content": '/default-content/rentalsearch.php', "addtomenu": true },
				{ "title": "All Rentals", "url": "allrentals", "intid": "bapi_property_grid", "parent": "rentals", "order": 2, "template": "page-templates/full-width.php", "content": '/default-content/allrentals.php', "addtomenu": true },
				{ "title": "Search Buckets", "url": "searchbuckets", "intid": "bapi_search_buckets", "parent": "rentals", "order": 3, "template": "page-templates/full-width.php", "content": '/default-content/propertyfinders.php', "addtomenu": true },				
				{ "title": "Developments", "url": "developments", "intid": "bapi_developments", "parent": "rentals", "order": 4, "template": "page-templates/search-page.php", "content": '/default-content/developments.php', "addtomenu": true },
				{ "title": "My List", "url": "mylist", "intid": "bapi_mylist", "parent": "rentals", "order": 5, "template": "page-templates/search-page.php", "content": '/default-content/mylist.php', "addtomenu": false },
			{ "title": "Specials", "url": "specials", "intid": "bapi_specials", "parent": "", "order": 3, "template": "page-templates/full-width.php", "content": '/default-content/specials.php', "addtomenu": true },			
			{ "title": "Attractions", "url": "attractions", "intid": "bapi_attractions", "parent": "", "order": 4, "template": "page-templates/full-width.php", "content": '/default-content/attractions.php', "addtomenu": true },
			{ "title": "Company", "url": "company", "intid": "bapi_company", "parent": "", "order": 5, "template": "page-templates/full-width.php", "content": '', "addtomenu": true },
				{ "title": "Services", "url": "services", "intid": "bapi_services", "parent": "company", "order": 1, "template": "page-templates/full-width.php", "content": '/default-content/services.php', "addtomenu": true },
				{ "title": "About Us", "url": "aboutus", "intid": "bapi_about_us", "parent": "company", "order": 2, "template": "page-templates/full-width.php", "content": '/default-content/aboutus.php', "addtomenu": true },
				{ "title": "Owner Information", "url": "companyowner", "intid": "bapi_company_owner", "parent": "company", "order": 3, "template": "page-templates/full-width.php", "content": '/default-content/infoforowners.php', "addtomenu": true },
				{ "title": "Guest Information", "url": "companyguest", "intid": "bapi_company_guest", "parent": "company", "order": 4, "template": "page-templates/full-width.php", "content": '/default-content/infoforguests.php', "addtomenu": true },
				{ "title": "Travel Insurance", "url": "travelinsurance", "intid": "bapi_travel_insurance", "parent": "company", "order": 5, "template": "page-templates/full-width.php", "content": '/default-content/travelinsurance.php', "addtomenu": true },	
				{ "title": "Contact Us", "url": "contact", "intid": "bapi_contact", "parent": "company", "order": 6, "template": "page-templates/full-width.php", "content": '/default-content/contactus.php', "addtomenu": true },
				{ "title": "Blog", "url": "blog", "intid": "bapi_blog", "parent": "company", "order": 7, "template": "", "content": '', "addtomenu": true },
			{ "title": "Make Booking", "url": "makebooking", "intid": "bapi_makebooking", "parent": "", "order": 9, "template": "page-templates/full-width.php", "content": '/default-content/makebooking.php', "addtomenu": false },
			{ "title": "Make a Payment", "url": "makepayment", "intid": "bapi_makepayment", "parent": "", "order": 10, "template": "page-templates/full-width.php", "content": '/default-content/makepayment.php', "addtomenu": false },
			{ "title": "Booking Confirmation", "url": "bookingconfirmation", "intid": "bapi_booking_confirm", "parent": "", "order": 11, "template": "page-templates/full-width.php", "content": '/default-content/bookingconfirmation.php', "addtomenu": false },
			{ "title": "Rental Policy", "url": "rentalpolicy", "intid": "bapi_rental_policy", "parent": "", "order": 12, "template": "page-templates/full-width.php", "content": '/default-content/rentalpolicy.php', "addtomenu": false },
			{ "title": "Privacy Policy", "url": "privacypolicy", "intid": "bapi_privacy_policy", "parent": "", "order": 13, "template": "page-templates/full-width.php", "content": '/default-content/privacypolicy.php', "addtomenu": false },
			{ "title": "Terms of Use", "url": "termsofuse", "intid": "bapi_tos", "parent": "", "order": 14, "template": "page-templates/full-width.php", "content": '/default-content/termsofuse.php', "addtomenu": false }
		];
		//$defpages[] = array("Title"=>"Owner Login", "URL"=>"/Owners", "IntID"=>"bapi_owners", "Parent"=>''); //TO be added to footer menu only

		$(".setuppages").on("click", function () {			
			if (confirm("Are you sure you want to setup the menu system")) {
				$('#dlg-result').dialog({width:700});
				var txtresult = $('#dlg-txtresult');
				txtresult.html('<h5>Setting up menu system</h5>');
				var url = '/bapi.init?p=1';
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
	});
</script>
