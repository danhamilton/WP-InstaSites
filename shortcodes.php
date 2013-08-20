<?php
//[foobar]
/*function foobar_func( $atts ){
	return "foo and bar";
}
add_shortcode( 'foobar', 'foobar_func' );*/

function doc_template_sc($atts){
	extract( shortcode_atts( array(
		'docname' => 'none'
	), $atts ) );
	if($docname=='none'){
		return 'Document Template Shortcode Not Configured.  Parameter "docname" is not specified.';
	}
	$settings = array(
		"Rental Policy" => 'bapi_rental_policy',
		"Privacy Policy" => 'bapi_privacy_policy',
		"Terms of Use" => 'bapi_terms_of_use'
	);
	if(!array_key_exists($docname,$settings)){
		return 'The specified document template is not configured for synchronization.';
	}
	$setting = $settings[$docname];
	$d = get_doc_template($docname,$setting);
	return $d;
}
add_shortcode( 'doctemplate', 'doc_template_sc' );
?>