<?php
//[foobar]
/*function foobar_func( $atts ){
	return "foo and bar";
}
add_shortcode( 'foobar', 'foobar_func' );*/

//Doc Templates
function doc_template_sc($atts){
	extract( shortcode_atts( array(
		'docname' => 'none'
	), $atts ) );  //Deserialize parameters in to their own variables w/ default values.
	if($docname=='none'){
		return 'Document Template Shortcode Not Configured.  Parameter "docname" is not specified.';
	}
	$settings = array(
		"Rental Policy" => 'bapi_rental_policy',
		"Privacy Policy" => 'bapi_privacy_policy',
		"Terms of Use" => 'bapi_terms_of_use',
		"Safe Harbor Policy" => 'bapi_safe_harbor'
	);
	if(!array_key_exists($docname,$settings)){
		return 'The specified document template is not configured for synchronization.';
	}
	$setting = $settings[$docname];
	$d = get_doc_template($docname,$setting); //This function is defined in sync.php
	return $d;
}
add_shortcode( 'doctemplate', 'doc_template_sc' );


//Property Search
function search_page_sc(){
	$d = '<div id="results" class="bapi-summary" data-log="0" data-templatename="tmpl-propertysearch-listview" data-entity="property" data-applyfixers="1" data-rowfixcount="2"></div>';
	return $d;
}
add_shortcode( 'searchpage', 'search_page_sc' );


//All Properties
function all_properties_sc(){
	$td = getbapisolutiondata();
	$d = '<header class="pagination-centered"><h1>'.$td['textdata']['All Rentals'].'</h1></header><div id="results" class="bapi-summary" data-log="0" data-templatename="tmpl-allproperties" data-entity="property" data-ignoresession="1"></div>';
	return $d;
}
add_shortcode( 'allproperties', 'all_properties_sc' );


//Predefined Searches
function predefined_searches_sc(){
	$d = '<div id="results" class="propertyfinders-results bapi-summary" data-log="0" data-templatename="tmpl-searches-summary" data-entity="searches" data-rowfixselector=".pfrowfix" data-rowfixcount="3"></div>';
	return $d;
}
add_shortcode( 'predefined-searches', 'predefined_searches_sc' );


//Developments
function developments_sc(){
	$d = '<div id="results" class="bapi-summary" data-applyfixers="1" data-log="0" data-templatename="tmpl-developments-summary-list" data-entity="development"></div>';
	return $d;
}
add_shortcode( 'developments', 'developments_sc' );


//My List
function mylist_sc(){
	$td = getbapisolutiondata();
	$d = '<header class="pagination-centered"><h1>'.$td['textdata']['My WishList'].'</h1></header><div id="results" class="bapi-summary" data-log="0" data-templatename="tmpl-propertysearch-listview" data-usemylist="1" data-entity="property" data-applyfixers="1" data-rowfixselector="" data-rowfixcount="3"></div>';
	return $d;
}
add_shortcode( 'mylist', 'mylist_sc' );


//Specials
function specials_sc(){
	$td = getbapisolutiondata();
	$d = '<header class="pagination-centered"><h1>'.$td['textdata']['Specials'].'</h1></header><div id="results" class="specials-results bapi-summary" data-log="0" data-templatename="tmpl-specials-summary" data-entity="specials" data-rowfixselector=".specials-results%20%3E%20.span4" data-rowfixcount="3"></div>';
	return $d;
}
add_shortcode( 'specials', 'specials_sc' );


//Attractions
function attractions_sc(){
	$td = getbapisolutiondata();
	$d = '<header class="pagination-centered"><h1>'.$td['textdata']['Attractions'].'</h1></header><div id="results" class="poi-results bapi-summary" data-log="0" data-applyfixers="1" data-templatename="tmpl-attractions-summary-list" data-entity="poi" data-rowfixselector=".rowfix" data-rowfixcount="3"></div>';
	return $d;
}
add_shortcode( 'attractions', 'attractions_sc' );


//Make Booking
function makebooking_sc(){
	/* the SSL was added here so it renders when the page is loaded otherwise the seal says that the page needs to be reloaded so it can be verified */
	$d = '<div class="bapi-bookingform" id="bookingform"></div>';
	return $d;
}
add_shortcode( 'makebooking', 'makebooking_sc' );


//Booking Confirmation
function booking_confirmation_sc(){
	$td = getbapisolutiondata();
	$d = '<h1>Reservation Request Received</h1>
<p>Thank You! We have received your request and will process it shortly. Please note that your reservation is not confirmed until you receive written confirmation from us.</p>
<p>If you have any questions regarding this reservation, please <a href="mailto:'.$td['site']['PrimaryEmail'].'">contact us.</a></p>';
	return $d;
}
add_shortcode( 'bookingconfirmation', 'booking_confirmation_sc' );
?>
