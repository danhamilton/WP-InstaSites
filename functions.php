<?php

function gettemplatelocation() {
	return "http://bapi.s3.amazonaws.com/dev/bapi.ui.mustache.tmpl";
}

function getbapiurl() {
	$bapi_baseurl = 'connect.bookt.com';
	if(get_option('bapi_baseurl')){
		$bapi_baseurl = get_option('bapi_baseurl');
	}
	if(empty($bapi_baseurl)){
		$bapi_baseurl = 'connect.bookt.com';
	}
	if (stripos($bapi_baseurl, "localhost", 0) === 0) {
		return "http://" . $bapi_baseurl;
	}
	return "https://" . $bapi_baseurl;
}

function getbapilangauge() {
	$language = get_option('bapi_language');	
	if(empty($language)) {
		$language = "en-US";
	}
	return $language;	
}

function getbapijsurl($apiKey) {
	return getbapiurl() . "/js/bapi.js?apikey=" . $apiKey . "&uitmploc=" . urlencode(gettemplatelocation()) . "&language=" . getbapilangauge();
}

function getbapiapikey() {
	return get_option('api_key');
}

function getconfig(){
	if(get_option('api_key')){
		$apiKey = get_option('api_key');
		$language = getbapilangauge();
		?>
		<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/slideshow.css', __FILE__) ?>" />
		<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__) ?>" />
		<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ad-gallery.min.css', __FILE__) ?>" />		
		
		<script type="text/javascript" src="<?= plugins_url('/js/jquery.1.9.1.min.js', __FILE__) ?>" ></script>
		<script type="text/javascript" src="<?= plugins_url('/js/jquery-migrate-1.0.0.min.js', __FILE__) ?>" ></script>		
		<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-1.10.2.min.js', __FILE__) ?>" ></script>
		<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-i18n.min.js', __FILE__) ?>" ></script>			
		
		<script type="text/javascript" src="<?= plugins_url('/js/jquery.cycle2.min.js', __FILE__) ?>" ></script>
		<script type="text/javascript" src="<?= plugins_url('/js/slides.min.jquery.js', __FILE__) ?>" ></script>
		<script type="text/javascript" src="<?= plugins_url('/js/jquery.ad-gallery.min.js', __FILE__) ?>" ></script>
		<script type="text/javascript" src="<?= plugins_url('/js/validity.js', __FILE__) ?>" ></script>
		<script type="text/javascript" src="<?= plugins_url('/js/mustache.min.js', __FILE__) ?>" ></script>
		
		<script type="text/javascript" src="<?= getbapijsurl($apiKey) ?>"></script>
		<script type="text/javascript" src="<?= plugins_url('/bapi/bapi.ui.js', __FILE__) ?>" ></script>		
		<script src="<?= getbapiurl() ?>/js/bapi.textdata.js?apikey=<?= $apiKey ?>&language=<?= $language ?>" type="text/javascript"></script>
		<script type="text/javascript">		
			BAPI.defaultOptions.baseURL = '<?= getbapiurl() ?>';
			BAPI.init('<?= $apiKey ?>');			
		</script>
		<?php
		
		bapi_search_page_head($content);
	}
}

function bapi_get_slideshow($mode='raw'){
	$slide1 = get_option('bapi_slideshow_image1');
	$slide2 = get_option('bapi_slideshow_image2');
	$slide3 = get_option('bapi_slideshow_image3');
	$slide4 = get_option('bapi_slideshow_image4');
	$slide5 = get_option('bapi_slideshow_image5');
	$slide6 = get_option('bapi_slideshow_image6');
	$slide1cap = get_option('bapi_slideshow_caption1');
	$slide2cap = get_option('bapi_slideshow_caption2');
	$slide3cap = get_option('bapi_slideshow_caption3');
	$slide4cap = get_option('bapi_slideshow_caption4');
	$slide5cap = get_option('bapi_slideshow_caption5');
	$slide6cap = get_option('bapi_slideshow_caption6');
	$slideshow = array();
	$i = 0;
	if(strlen($slide1)>0){
		$slideshow[$i] = array("url"=>$slide1,"caption"=>$slide1cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide1).'&h=80', __FILE__));
		$i++;
	}
	if(strlen($slide2)>0){
		$slideshow[$i] = array("url"=>$slide2,"caption"=>$slide2cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide2).'&h=80', __FILE__));
		$i++;
	}
	if(strlen($slide3)>0){
		$slideshow[$i] = array("url"=>$slide3,"caption"=>$slide3cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide3).'&h=80', __FILE__));
		$i++;
	}
	if(strlen($slide4)>0){
		$slideshow[$i] = array("url"=>$slide4,"caption"=>$slide4cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide4).'&h=80', __FILE__));
		$i++;
	}
	if(strlen($slide5)>0){
		$slideshow[$i] = array("url"=>$slide5,"caption"=>$slide5cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide5).'&h=80', __FILE__));
		$i++;
	}
	if(strlen($slide6)>0){
		$slideshow[$i] = array("url"=>$slide6,"caption"=>$slide6cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide6).'&h=80', __FILE__));
		$i++;
	}
	if($mode=='raw'){
		return $slideshow;
	}
	if($mode=='json'){
		$json = json_encode($slideshow);
		?>
		<script>
			var slides_json = '<?= $json ?>';
		</script>	
		<?php
		return true;
	}
	if($mode=='divs'){
		foreach($slideshow as $sl){
			?>
            <div>
            	<a href=""><img src="<?= $sl['url'] ?>" title="<?= $sl['caption'] ?>" /></a>
            </div>
            <?php
		}
		return true;
	}
}

function bapi_get_template($tmpl,$url='http://bapi.s3.amazonaws.com/dev/bapi.ui.mustache.tmpl'){
	$templates= file_get_contents($url);
	$tmpl = preg_quote($tmpl);
	preg_match_all('/<script id=\"'.$tmpl.'\" type=\"text\/html\">(.*?)<\/script>/s', $templates, $matches);
	
	return $matches[1][0]; 
} 

function bapi_get_templates($url='http://bapi.s3.amazonaws.com/dev/bapi.ui.mustache.tmpl'){
	$templates= file_get_contents($url);
	$domd = new DOMDocument();
	libxml_use_internal_errors(true);
	$domd->loadHTML($templates);
	libxml_use_internal_errors(false);
	
	$items = $domd->getElementsByTagName('script');
	$data = array();
	
	foreach($items as $item) {
		$id = $item->getAttribute('id');
		$content = $domd->saveXML($item->firstChild);
		$content = str_replace("<![CDATA[","",$content);
		$content = str_replace("]]>","",$content);
		$data[$id] = $content;
	}
	return $data;
}



function home_url_cdn( $path = '', $scheme = null ) {
	return get_home_url_cdn( null, $path, $scheme );
}

function get_home_url_cdn( $blog_id = null, $path = '', $scheme = null ) {	
	$cdn_url = get_option('home');
	if(get_option('bapi_site_cdn_domain')){
		$cdn_url = get_option('bapi_site_cdn_domain');
	}
	$home_url = str_replace(get_option('home'),$cdn_url,$path);
	//echo $home_url; 
	
	return $home_url;
}
?>