
<?php
	require_once ('../../../wp-load.php');	
	
	header('Content-Type: application/javascript');	
	header('Cache-Control: public');

	$expires = round((60*10 + $lastupdatetime), 2); // expires every 10 mins
	$expires = gmdate('D, d M Y H:i:s \G\M\T', $expires);
	header( 'Expires: ' . $expires );
	
	$js = get_option('bapi_textdata');
	echo "/*\r\n";
	echo "	BAPI TextData\r\n";
	echo "	Last updated: " . date('r',$lastupdatetime) . "\r\n";	
	echo "	Language: " . getbapilanguage() . "\r\n";
	echo "*/\r\n\r\n";
	echo "BAPI.textdata = " . $js . ";\r\n";				
?>