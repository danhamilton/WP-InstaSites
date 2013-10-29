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
		echo 'var settings=' . stripslashes($sitesettings) . ';';
	} else {
		echo 'var settings={
			"searchmode-listview": "BAPI.config().searchmodes.listview=true;",			
			"searchmode-photoview": "BAPI.config().searchmodes.photoview=true;",
			"searchmode-widephotoview": "BAPI.config().searchmodes.widephotoview=false;",
			"searchmode-hotelview": "BAPI.config().searchmodes.hotelview=false;",
			"amenitysearch": "BAPI.config().amenity.enabled=false;",
			"devsearch": "BAPI.config().dev.enabled=false;",
			"adultsearch: "BAPI.config().adults.enabled=false;",
			"childsearch": "BAPI.config().children.enabled=false;",
			"headlinesearch": "BAPI.config().headline.enabled=false;",
			"locsearch": "BAPI.config().city.enabled=false; BAPI.config().location.enabled=false;",
			"showunavailunits": "BAPI.config().restrictavail=true;",
			"searchsort": "BAPI.config().sort=\'random\';",
			"searchsortorder": "BAPI.config().sortdesc=false;",
			"propdetail-availcal": "BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=1;",
			"propdetail-reviewtab": "BAPI.config().hasreviews=false;"		
		};';
	}	
?> 
jQuery(document).ready(function () {	
	// update the settings
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

<h3>Search Result Display Modes</h3>
<table class="form-table">
<tr valign="top">
	<td scope="row">List View:</td>
	<td><select name="searchmode-listview" id="searchmode-listview">
		<option value="BAPI.config().searchmodes.listview=false;">Disable</option>
		<option value="BAPI.config().searchmodes.listview=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Photo View:</td>
	<td><select name="searchmode-photoview" id="searchmode-photoview">
		<option value="BAPI.config().searchmodes.photoview=false;">Disable</option>
		<option value="BAPI.config().searchmodes.photoview=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Wide Photo View:</td>
	<td><select name="searchmode-widephotoview" id="searchmode-widephotoview">
		<option value="BAPI.config().searchmodes.widephotoview=false;">Disable</option>
		<option value="BAPI.config().searchmodes.widephotoview=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Hotel View:</td>
	<td><select name="searchmode-hotelview" id="searchmode-hotelview">
		<option value="BAPI.config().searchmodes.hotelview=false;">Disable</option>
		<option value="BAPI.config().searchmodes.hotelview=true;">Enable</option>		
	    </select>
	</td>	
</tr>
</table>
<div class="clear"></div>

<h3>Search Result Settings</h3>
<table class="form-table">
<tr valign="top">
	<td scope="row">Show unavailable units:</td>
	<td><select name="showunavailunits" id="showunavailunits">
		<option value="BAPI.config().restrictavail=false;">Disable</option>
		<option value="BAPI.config().restrictavail=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Show Avg. Review Rating in Search Result:</td>
	<td><input id="" type="checkbox" name=""></td>
</tr>
<tr valign="top">
	<td scope="row">Default Search Sort Order Option:</td>
	<td>
		<select name="searchsort" id="searchsort">
		<option value="BAPI.config().sort='beds';">By Bedrooms</option>
		<option value="BAPI.config().sort='sleeps';">By Sleeps</option>
		<option value="BAPI.config().sort='category';">By Category</option>
		<option value="BAPI.config().sort='headline';">By Headline</option>
		<option value="BAPI.config().sort='location';">By City</option>
		<option value="BAPI.config().sort='minrate';">By Minimum Price</option>
		<option value="BAPI.config().sort='maxrate';">By Maximum Price</option>
		<option value="BAPI.config().sort='random';">Random</option>
		</select>
		<select name="searchsortorder" id="searchsortorder">
			<option value="BAPI.config().sortdesc=false;">Ascending</option>
			<option value="BAPI.config().sortdesc=true;">Descending</option>		
		</select>
	</td>
</tr>
</table>
<div class="clear"></div>

<h3>Property Search Form Settings</h3>
<table class="form-table">
<tr valign="top">
	<td scope="row">Check-In Check-Out Mode:</td>
	<td><select name="checkinoutmode" id="checkinoutmode">
		<option value="BAPI.config().checkin.enabled=false; BAPI.config().checkout.enabled=false; BAPI.config().los.enabled=false;">Disable</option>
		<option value="BAPI.config().checkin.enabled=true; BAPI.config().checkout.enabled=true; BAPI.config().los.enabled=false;">Check In Date Picker/Check Out DatePicker</option>
		<option value="BAPI.config().checkin.enabled=true; BAPI.config().checkout.enabled=false; BAPI.config().los.enabled=true;">Check In Date Picker/Length of Stay DropDown</option>
	    </select>
	</td>
</tr>
<tr valign="top">
	<td scope="row">Default Nights of Stay:</td>
	<td><select name="deflos" id="deflos">
		<option value="BAPI.config().los.defaultval=0; BAPI.config().minlos=0;">Disable</option>
		<option value="BAPI.config().los.defaultval=1; BAPI.config().minlos=1;">1</option>
		<option value="BAPI.config().los.defaultval=2; BAPI.config().minlos=2;">2</option>
		<option value="BAPI.config().los.defaultval=3; BAPI.config().minlos=3;">3</option>
		<option value="BAPI.config().los.defaultval=4; BAPI.config().minlos=4;">4</option>
		<option value="BAPI.config().los.defaultval=5; BAPI.config().minlos=5;">5</option>
		<option value="BAPI.config().los.defaultval=6; BAPI.config().minlos=6;">6</option>
		<option value="BAPI.config().los.defaultval=7; BAPI.config().minlos=7;">7</option>
		<option value="BAPI.config().los.defaultval=8; BAPI.config().minlos=8;">8</option>
		<option value="BAPI.config().los.defaultval=9; BAPI.config().minlos=9;">9</option>
		<option value="BAPI.config().los.defaultval=10; BAPI.config().minlos=10;">10</option>
		<option value="BAPI.config().los.defaultval=11; BAPI.config().minlos=11;">11</option>
		<option value="BAPI.config().los.defaultval=12; BAPI.config().minlos=12;">12</option>
		<option value="BAPI.config().los.defaultval=13; BAPI.config().minlos=13;">13</option>
		<option value="BAPI.config().los.defaultval=14; BAPI.config().minlos=14;">14</option>
		<option value="BAPI.config().los.defaultval=15; BAPI.config().minlos=15;">15</option>
		<option value="BAPI.config().los.defaultval=16; BAPI.config().minlos=16;">16</option>
		<option value="BAPI.config().los.defaultval=17; BAPI.config().minlos=17;">17</option>
		<option value="BAPI.config().los.defaultval=18; BAPI.config().minlos=18;">18</option>
		<option value="BAPI.config().los.defaultval=19; BAPI.config().minlos=19;">19</option>
		<option value="BAPI.config().los.defaultval=20; BAPI.config().minlos=20;">20</option>
		<option value="BAPI.config().los.defaultval=21; BAPI.config().minlos=21;">21</option>
		<option value="BAPI.config().los.defaultval=22; BAPI.config().minlos=22;">22</option>
		<option value="BAPI.config().los.defaultval=23; BAPI.config().minlos=23;">23</option>
		<option value="BAPI.config().los.defaultval=24; BAPI.config().minlos=24;">24</option>
		<option value="BAPI.config().los.defaultval=25; BAPI.config().minlos=25;">25</option>
		<option value="BAPI.config().los.defaultval=26; BAPI.config().minlos=26;">26</option>
		<option value="BAPI.config().los.defaultval=27; BAPI.config().minlos=27;">27</option>
		<option value="BAPI.config().los.defaultval=28; BAPI.config().minlos=28;">28</option>
		<option value="BAPI.config().los.defaultval=29; BAPI.config().minlos=29;">29</option>
		<option value="BAPI.config().los.defaultval=30; BAPI.config().minlos=30;">30</option>
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Default Check-In Date X # of days Out (0 means no default):</td>
	<td><input id="" type="numeric" name=""></td>
</tr>
<tr valign="top">
	<td scope="row">Category Search:</td>
	<td><select name="categorysearch" id="categorysearch">
		<option value="BAPI.config().category.enabled=false;">Disable</option>
		<option value="BAPI.config().category.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Sleeps Search (Exact):</td>
	<td><select name="sleepsearch" id="sleepsearch">
		<option value="BAPI.config().sleeps.enabled=false;" selected>Disable</option>
		<option value="BAPI.config().sleeps.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Sleeps Search (Min):</td>
	<td><select name="minsleepsearch" id="minsleepsearch">
		<option value="BAPI.config().minsleeps={}; BAPI.config().minsleeps.enabled=false;">Disable</option>
		<option value="BAPI.config().minsleeps={}; BAPI.config().minsleeps.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Bedroom Search (Exact):</td>
	<td><select name="bedsearch" id="bedsearch">
		<option value="BAPI.config().beds.enabled=false;" selected>Disable</option>
		<option value="BAPI.config().beds.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Bedroom Search (Min):</td>
	<td><select name="minbedsearch" id="minbedsearch">
		<option value="BAPI.config().minbeds={}; BAPI.config().minbeds.enabled=false;">Disable</option>
		<option value="BAPI.config().minbeds={}; BAPI.config().minbeds.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Max Bedrooms In List:</td>
	<td><select name="maxbedsearch" id="maxbedsearch">
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,1);">1</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,2);">2</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,3);">3</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,4);">4</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,5);">5</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,6);">6</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,7);">7</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,8);">8</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,9);">9</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,10);">10</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,11);">11</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,12);">12</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,13);">13</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,14);">14</option>
		<option value="BAPI.config().beds.values=BAPI.config().beds.values.splice(0,15);">15</option>
	    </select>
	</td>	
</tr>
<tr valign="top" style="display:none">
	<td scope="row">Amenity Search:</td>
	<td>
		<select name="amenitysearch" id="amenitysearch">
		<option value="BAPI.config().amenity.enabled=false;">Disabled</option>
		<option value="BAPI.config().amenity.enabled=true;">Amenities Checkbox Group</option>		
		</select>
	</td>
</tr>
<tr valign="top">
	<td scope="row">Development Search:</td>
	<td><select name="devsearch" id="devsearch">
		<option value="BAPI.config().dev.enabled=false;">Disable</option>
		<option value="BAPI.config().dev.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Number of Adults Search:</td>
	<td><select name="adultsearch" id="adultsearch">
		<option value="BAPI.config().adults.enabled=false;">Disable</option>
		<option value="BAPI.config().adults.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Number of Children Search:</td>
	<td><select name="childsearch" id="childsearch">
		<option value="BAPI.config().children.enabled=false;">Disable</option>
		<option value="BAPI.config().children.enabled=true;">Enable</option>		
	    </select>
	</td>	
</tr>
<tr valign="top">
	<td scope="row">Property Headline Search:</td>
	<td><select name="headlinesearch" id="headlinesearch">
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
		<option value="BAPI.config().city.enabled=true; BAPI.config().location.enabled=false; BAPI.config().city.autocomplete=false;">City Drop Down List</option>
		<option value="BAPI.config().city.enabled=true; BAPI.config().location.enabled=false; BAPI.config().city.autocomplete=true;">City Autocomplete</option>
		<option value="BAPI.config().city.enabled=false; BAPI.config().location.enabled=true; BAPI.config().location.autocomplete=false;">Market Area Drop Down List</option>
		<option value="BAPI.config().city.enabled=false; BAPI.config().location.enabled=true; BAPI.config().location.autocomplete=true;">Market Area Autocomplete</option>
		</select>
	</td>
</tr>	
</table>
<div class="clear"></div>

<h3>Property Detail Screen Settings</h3>
<table class="form-table">
<tr valign="top">
	<td scope="row">Availability Calendar:</td>
	<td><select name="propdetail-availcal" id="propdetail-availcal">
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
	<td><select name="propdetail-reviewtab" id="propdetail-reviewtab">
		<option value="BAPI.config().hasreviews=false;">Disable</option>
		<option value="BAPI.config().hasreviews=true;">Enable</option>		
	    </select>
	</td>	
</tr>
</table>

<?php submit_button(); ?>
</form>
</div>
