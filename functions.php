<?php
	/* Converted a url to a physical file path */
	function get_local($url) {
		$urlParts = parse_url($url);
		return realpath($_SERVER['DOCUMENT_ROOT']) . $urlParts['path'];				
	}
	
	/* BAPI Helpers */	
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

	function getbapilanguage() {
		$language = get_option('bapi_language');	
		if(empty($language)) {
			$language = "en-US";
		}
		return $language;	
	}
	
	function bapi_language_attributes($doctype) {
		return 'lang="'.getbapilanguage().'"';
	}

	function getbapijsurl($apiKey) {
		return getbapiurl() . "/js/bapi.js?apikey=" . $apiKey . "&language=" . getbapilanguage();
	}

	function getbapiapikey() {
		return get_option('api_key');
	}
	
	$solutiondata = null;
	function getbapisolutiondata() {
		// TODO: This was loading via get_option but the data can be stale.  Need to think of an efficient way of handling this.
		$tst = null; //get_option('bapi_solutiondata'); 
		if (empty($tst)) {
			if (!empty($solutiondata)) {
				return $solutiondata;
			}
			$ctx = getbapicontext();	
			$raw = getbapitextdata(); 
			$td = $raw['result'];	
			$wrapper = array();	
			$wrapper['site'] = $ctx;
			$wrapper['textdata'] = $td;
			add_option('bapi_solutiondata', $wrapper);
			$tst = $wrapper;
		}
		$solutiondata = $tst;
		return $tst;	
	}	

	function getbapicontext() {		
		$c = file_get_contents(getbapiurl() . '/js/bapi.context?apikey=' . getbapiapikey() . '&language=' . getbapilanguage());
		$res = json_decode($c,TRUE);
		return $res;
	}
	
	function getbapitextdata() {
		$c = get_option('bapi_textdata');
		$c = json_decode($c,TRUE); // convert to json object
		return $c;
	}	
	
	/* Page Helpers */
	function getPageKeyForEntity($entity, $pkid) {
		return $entity . ':' . $pkid;
	}	
	
	function getPageForEntity($entity, $pkid, $parentid) {
		$pagekey = getPageKeyForEntity($entity, $pkid);
		$args = array('meta_key' => 'bapikey', 'meta_value' => $pagekey, 'child_of' => $parentid);
		return get_pages($args);		
	}
	
	function getGoogleMapKey() {
		return "AIzaSyAY7wxlnkMG6czYy9K-wM4OWXs0YFpFzEE";
	}

	/* Common include files needed for BAPI */
	function getconfig() {		
		if(get_option('api_key')){
			$apiKey = get_option('api_key');
			$language = getbapilanguage();
			$gmapkey = getGoogleMapKey();
			
			$secureurl = '';
			if(get_option('bapi_secureurl')){
				$secureurl = get_option('bapi_secureurl');
			}
			$siteurl = get_option('home');
			if(get_option('bapi_site_cdn_domain')){
				$siteurl = get_option('bapi_site_cdn_domain');
			}
			$siteurl = str_replace("http://", "", $siteurl);
?>
<link rel="stylesheet" type="text/css" href="<?= plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__) ?>" />

<script type="text/javascript" src="<?= plugins_url('/js/jquery.1.9.1.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-migrate-1.0.0.min.js', __FILE__) ?>" ></script>		
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-1.10.2.min.js', __FILE__) ?>" ></script>
<script type="text/javascript" src="<?= plugins_url('/js/jquery-ui-i18n.min.js', __FILE__) ?>" ></script>			

<script type="text/javascript" src="<?= getbapijsurl($apiKey) ?>"></script>
<script type="text/javascript" src="<?= plugins_url('/bapi/bapi.ui.js', __FILE__) ?>" ></script>		
<script type="text/javascript" src="<?= str_replace("http","https",get_option('bapi_site_cdn_domain')) ?>/wp-content/plugins/bookt-api/bapi.textdata.php" ></script>		
<script type="text/javascript" src="<?= str_replace("http","https",get_option('bapi_site_cdn_domain')) ?>/wp-content/plugins/bookt-api/bapi.templates.php" ></script>		
<script type="text/javascript">		
	BAPI.defaultOptions.baseURL = '<?= getbapiurl() ?>';
	BAPI.UI.loading.setLoadingImgUrl('<?= plugins_url("/img/loading.gif", __FILE__) ?>');
	BAPI.site.url =  '<?= $siteurl ?>';
	<?php if ($secureurl!='') { ?>
	BAPI.site.secureurl = '<?= $secureurl ?>';
	<?php } ?>
	BAPI.init('<?= $apiKey ?>');
	BAPI.UI.jsroot = '<?= plugins_url("/", __FILE__) ?>'
	$(document).ready(function () {
		BAPI.UI.init();
	});    
</script>

<?php			
		}
	}

	/* Slideshow */
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
	
	/* CDN Support */
	function home_url_cdn( $path = '', $scheme = null ) {
		return get_home_url_cdn( null, $path, $scheme );
	}

	function get_home_url_cdn( $blog_id = null, $path = '', $scheme = null ) {	
		$cdn_url = get_option('home');
		if(get_option('bapi_site_cdn_domain')&&!is_admin()){
			$cdn_url = get_option('bapi_site_cdn_domain');
		}
		$home_url = str_replace(get_option('home'),$cdn_url,$path);
		//echo $home_url; 
		
		return $home_url;
	}
	
	function add_server_name_meta(){
		$sn = gethostname();
		echo '<meta name="SERVERNAME" content="'.$sn.'" />'."\n";
	}
	
	function bapi_redirect_fix($redirect_url, $requested_url) {
		$cdn_domain = parse_url(get_option('bapi_site_cdn_domain'));
		$redirect = parse_url($redirect_url);
		if($redirect['scheme']!='https') {
			$redirect_url = $redirect['scheme'].'://'.$cdn_domain['host'];
			$redirect_url .= $redirect['path'];
			if ( !empty($redirect['query']) ) {
				$redirect_url .= '?' . $redirect['query'];
			}
			return $redirect_url; 
		}
		return $redirect_url;
	}
	
	function bapi_getmeta(){
		$pid = get_the_ID();
		
		$metak = get_post_meta($pid,'bapi_meta_keywords',true);
		$metak = str_replace('"', "", $metak);
		$metak = str_replace("'", "", $metak);
		
		$metad = get_post_meta($pid,'bapi_meta_description',true);
		$metad = str_replace('"', "", $metad);
		$metad = str_replace("'", "", $metad);
		
		?><meta name="KEYWORDS" content="<?= $metak ?>" /><?= "\n" ?><meta name="DESCRIPTION" content="<?= $metad ?>" /><?= "\n" ?><?php
	}
	
	function bapi_refresh_keywords(){
		$apikey = get_option('api_key');
		$baseurl = get_option('bapi_baseurl');
		$lang = get_option('bapi_language');
		$seo = get_option('bapi_keywords_array');
		$lastmod = get_option('bapi_keywords_lastmod');
		if(empty($seo)||empty($lastmod)||((time()-$lastmod)>3600)){
			$seourl = 'https://'.$baseurl.'/ws/?method=get&entity=seo&apikey='.$apikey.'&language='.$lang;
			$seoarr = file_get_contents($seourl);
			update_option('bapi_keywords_array',$seoarr);
			update_option('bapi_keywords_lastmod',time());
			//$seoarr = json_decode($seoarr);
			//print_r($seoarr);
			//print_r(get_option('bapi_keywords_array'));
			//echo 'keyword list updated';
		}
		else{
			//echo 'no update required';
		}
	}
?>