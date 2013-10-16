<?php	
	global $bapi_all_options;	
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {				
		//bapi_wp_site_options();
		unset($_POST['submit']);		
		//print_r($_POST);
		$sitesettings = json_encode($_POST);
		update_option('bapi_sitesettings',  $sitesettings);		
		echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';		
	}
	else {
		$sitesettings = $bapi_all_options['bapi_sitesettings'];
	}
?> 
<script type="text/javascript">
<?php			
	if (!empty($sitesettings)) {
		echo 'var settings=' . $sitesettings . ';';
	} else {
		echo 'var settings={};';
	}
	
?> 
jQuery(document).ready(function () {	
	// if not a post
	jQuery.each(settings, function( key, value ) {
		console.log(key + '=' + value);
		if (key.indexOf('$')<0) {
			jQuery('#'+key).val(value);
		}
	});
})
</script>
<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin - Site Settings</h2>
<form method="post">

<a href="javascript:void(0)" class="dosave">Save</a>
<h3>Search Settings</h3>
<table class="form-table">
<tr valign="top">
	<td scope="row">Check-In Check-Out Mode:</td>
	<td><select class="bapi-config" name="checkinoutmode" id="checkinoutmode">
		<option value="BAPI.config().checkin.enabled=false; BAPI.config().checkout.enabled=false; BAPI.config().los.enabled=false;">Disable</option>
		<option value="BAPI.config().checkin.enabled=true; BAPI.config().checkout.enabled=true; BAPI.config().los.enabled=false;">Check In Date Picker/Check Out DatePicker</option>
		<option value="BAPI.config().checkin.enabled=true; BAPI.config().checkout.enabled=false; BAPI.config().los.enabled=true;">Check In Date Picker/Length of Stay DropDown</option>
	    </select>
	</td>
</tr>
<tr valign="top">
	<td scope="row">Default Nights of Stay:</td>
	<td><input class="bapi-config" id="deflos" type="numeric" name="deflos" data-bapisetting="BAPI.config().los.defaultval={0}; BAPI.config().minlos={0};"></td>
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Default Check-In Date X # of days Out (0 means no default):</td>
	<td><input id="" type="numeric" name=""></td>
</tr>
<tr valign="top">
	<td scope="row">Category Search:</td>
	<td><select class="bapi-config" name="categorysearch" id="categorysearch">
		<option value="BAPI.config().category.enabled=false;">Disable</option>
		<option value="BAPI.config().category.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Bedroom Search:</td>
	<td><select class="bapi-config" name="bedsearch" id="bedsearch">
		<option value="BAPI.config().beds.enabled=false;">Disable</option>
		<option value="BAPI.config().beds.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Max Bedrooms In List:</td>
	<td><select class="bapi-config" name="maxbedsearch" id="maxbedsearch">
		<option value="BAPI.config().beds.values.splice(0,1);">1</option>
		<option value="BAPI.config().beds.values.splice(0,2);">2</option>
		<option value="BAPI.config().beds.values.splice(0,3);">3</option>
		<option value="BAPI.config().beds.values.splice(0,4);">4</option>
		<option value="BAPI.config().beds.values.splice(0,5);">5</option>
		<option value="BAPI.config().beds.values.splice(0,6);">6</option>
		<option value="BAPI.config().beds.values.splice(0,7);">7</option>
		<option value="BAPI.config().beds.values.splice(0,8);">8</option>
		<option value="BAPI.config().beds.values.splice(0,9);">9</option>
		<option value="BAPI.config().beds.values.splice(0,10);">10</option>
		<option value="BAPI.config().beds.values.splice(0,11);">11</option>
		<option value="BAPI.config().beds.values.splice(0,12);">12</option>
		<option value="BAPI.config().beds.values.splice(0,13);">13</option>
		<option value="BAPI.config().beds.values.splice(0,14);">14</option>
		<option value="BAPI.config().beds.values.splice(0,15);">15</option>
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Search Greater Or Equal Bedrooms(uncheck for exact match):</td>
	<td><input id="" type="checkbox" name=""></td>
</tr>
<tr valign="top">
	<td scope="row">Amenity Search:</td>
	<td>
		<select class="bapi-config" name="amenitysearch" id="amenitysearch">
		<option value="BAPI.config().amenity.enabled=false">Disabled</option>
		<option value="BAPI.config().amenity.enabled=true">Amenities Checkbox Group</option>
		<option value="BAPI.config().amenity.enabled=true">Amenities Entity List</option>
		</select>
	</td>
</tr>
<tr valign="top">
	<td scope="row">Development Search:</td>
	<td><select class="bapi-config" name="devsearch" id="devsearch">
		<option value="BAPI.config().dev.enabled=false;">Disable</option>
		<option value="BAPI.config().dev.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Number of Adults Search:</td>
	<td><select class="bapi-config" name="adultsearch" id="adultsearch">
		<option value="BAPI.config().adults.enabled=false;">Disable</option>
		<option value="BAPI.config().adults.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Number of Children Search:</td>
	<td><select class="bapi-config" name="childsearch" id="childsearch">
		<option value="BAPI.config().children.enabled=false;">Disable</option>
		<option value="BAPI.config().children.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Property Headline Search:</td>
	<td><select class="bapi-config" name="headlinesearch" id="headlinesearch">
		<option value="BAPI.config().headline.enabled=false;">Disable</option>
		<option value="BAPI.config().headline.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Include # of Rooms/Units Search:</td>
	<td><input id="" type="checkbox" name=""></td>
</tr>
<tr valign="top">
	<td scope="row">Location Search:</td>
	<td>
		<select name="locsearch" id="locsearch">
		<option value="BAPI.config().city.enabled=false; BAPI.config().location.enabled=false;">Disabled</option>
		<option value="BAPI.config().city.enabled=true; BAPI.config().location.enabled=false;">City Drop Down List</option>
		<option value="BAPI.config().city.enabled=false; BAPI.config().location.enabled=true;">Market Area Drop Down List</option>
		</select>
	</td>
</tr>	
</table>
<div class="clear"></div>
<a href="javascript:void(0)" class="dosave">Save</a>

<h3>Availability Search Result Settings</h3>
<table class="form-table">
<tr valign="top">
	<td scope="row">Show unavailable units:</td>
	<td><select class="bapi-config-na" name="showunavailunits" id="showunavailunits">
		<option value="BAPI.config().headline.enabled=false;">Disable</option>
		<option value="BAPI.config().headline.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Show Avg. Review Rating in Search Result:</td>
	<td><input id="" type="checkbox" name=""></td>
</tr>
<tr valign="top">
	<td scope="row">Default Search Sort Order Option:</td>
	<td>
		<select name="" id="">
		<option value="1">By Bedrooms</option>
		<option value="0">By Category</option>
		<option value="6">By Headline</option>
		<option value="4">By Location</option>
		<option value="3">By Price HiLo</option>
		<option value="2">By Price LoHi</option>
		<option value="5">By Random</option>
		</select>
	</td>
</tr>
</table>

<div class="clear"></div>

<h3>Property Detail Screen Settings</h3>
<table class="form-table">
<tr valign="top">
	<td scope="row">Availability Calendar:</td>
	<td><select class="bapi-config" name="propdetail-availcal" id="propdetail-availcal">
		<option value="BAPI.config().displayavailcalendar=false; BAPI.config().availcalendarmonths=0;">Disable</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 1 Month</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 2 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 3 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 4 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 5 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 6 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 7 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 8 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 9 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 10 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 11 Months</option>
		<option value="BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;">Show 12 Months</option>
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Show Split Days in Availability Calendars:</td>
	<td><input id="" type="checkbox" name=""></td>
</tr>
<tr valign="top">
	<td scope="row">Display Property Review Tab:</td>
	<td><select class="bapi-config" name="propdetail-reviewtab" id="propdetail-reviewtab">
		<option value="BAPI.config().hasreviews=false;">Disable</option>
		<option value="BAPI.config().hasreviews=true;">Enable</option>		
	    </select>
	</td>	
</tr>
</table>

<?php submit_button(); ?>
</form>
</div>
