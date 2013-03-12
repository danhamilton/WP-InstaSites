<?php

function getconfig(){
	if(!defined('BAPI_API_LOCATION')){
		define('BAPI_API_LOCATION','connect.bookt.com');
	}
	if(get_option('api_key')){
		$apiKey = get_option('api_key');
		$useCustomTemplatesLoc = get_option('bapi_custom_tmpl_loc');
		$tmplLoc = '';
		if(strlen($useCustomTemplatesLoc)>0){
			$tmplLoc = '&uitmploc='.urlencode($useCustomTemplatesLoc);
		}
		?>
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="<?= plugins_url('/slideshow.css', __FILE__) ?>" />
		
		<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.0.0.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/i18n/jquery-ui-i18n.min.js"></script>
		
		<script src="//bapi.s3.amazonaws.com/lib/jquery.cycle2.min.js" type="text/javascript"></script>
		<script src="//bapi.s3.amazonaws.com/lib/validity.js" type="text/javascript"></script>
		
		<script src="//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.7.0/mustache.min.js" type="text/javascript"></script>
		
		<script src="//<?= BAPI_API_LOCATION ?>/js/bapi.js?apikey=<?= $apiKey ?><?= $tmplLoc ?>" type="text/javascript"></script>
		<script src="//bapi.s3.amazonaws.com/dev/bapi.ui.js" type="text/javascript"></script>
		<script src="//<?= BAPI_API_LOCATION ?>/js/bapi.textdata.js?apikey=<?= $apiKey ?>" type="text/javascript"></script>
		
		<script type="text/javascript" src="<?= plugins_url('/slides.min.jquery.js', __FILE__) ?>" ></script>
		<link rel="stylesheet" type="text/css" href="http://coffeescripter.com/code/ad-gallery/jquery.ad-gallery.css" />
		<script src="//booktplatform.s3.amazonaws.com/App_SharedStyles/JavaScript/jquery.ad-gallery.min.js" type="text/javascript"></script>
		<script type="text/javascript">		
			BAPI.defaultOptions.baseURL = 'https://<?= BAPI_API_LOCATION ?>';
			$(document).ready(function () {
				BAPI.init('<?= $apiKey ?>');
			});
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