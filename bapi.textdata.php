
<?php
	require_once ('../../../wp-load.php');	
	
	header('Content-Type: application/javascript');	
	header('Cache-Control: public');
	//header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	$lastupdatetime = get_option('bapi_textdata_lastmod');
	//echo round((time() - $lastupdatetime));
	$dofetch = false;
	if (empty($lastupdatetime)) { 
		$dofetch = true;	
	} else if ( round((time() - $lastupdatetime), 2) > 60*60) { // 1 hour
		$dofetch = true;
	}
	
	if ($dofetch) {	
		$lastupdatetime = time();
		// need to refresh the textdata
		$c = file_get_contents(getbapiurl() . '/ws/?method=get&entity=textdata&apikey=' . getbapiapikey() . '&language=' . getbapilanguage());
		$c = json_decode($c,TRUE); // convert to json object
		$c = $c['result']; // just get the result part
		
		// updat the last mode time
		$lastupdatetime = time(); 
		update_option('bapi_textdata_lastmod',$lastupdatetime);
		
		// update the text data in our settings
		$c = json_encode($c); // convert back to text
		update_option('bapi_textdata', $c);
	}
	
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