<?php	
	/* Pre-Load Site Options - Utilizes Built-in Cache Functions */
	global $bapi_all_options; 
	function bapi_wp_site_options(){
		global $bapi_all_options;
		$bapi_all_options = wp_load_alloptions();
		if(!isset($bapi_all_options['bapi_solutiondata'])){
			$bapi_all_options['bapi_solutiondata'] = '';
		}
		if(!isset($bapi_all_options['bapi_solutiondata_lastmod'])){
			$bapi_all_options['bapi_solutiondata_lastmod'] = 0;
		}
		if(!isset($bapi_all_options['bapi_textdata'])){
			$bapi_all_options['bapi_textdata'] = '';
		}
		if(!isset($bapi_all_options['bapi_textdata_lastmod'])){
			$bapi_all_options['bapi_textdata_lastmod'] = 0;
		}
		if(!isset($bapi_all_options['bapi_keywords_array'])){
			$bapi_all_options['bapi_keywords_array'] = '';
		}
		if(!isset($bapi_all_options['bapi_keywords_lastmod'])){
			$bapi_all_options['bapi_keywords_lastmod'] = 0;
		}
		if(!isset($bapi_all_options['bapi_language'])){
			$bapi_all_options['bapi_language'] = 'en-US';
		}
		if(!isset($bapi_all_options['bapi_baseurl'])){
			$bapi_all_options['bapi_baseurl'] = 'connect.bookt.com';
		}
		//print_r($bapi_all_options); exit();
	}
	
	/* BAPI url handlers */
	function urlHandler_bapitextdata() {
		global $bapi_all_options; 
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.textdata.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');

		$expires = round((60*10 + $lastupdatetime), 2); // expires every 10 mins
		$expires = gmdate('D, d M Y H:i:s \G\M\T', $expires);
		header( 'Expires: ' . $expires );
		
		$js = $bapi_all_options['bapi_textdata']; // core data should have been synced prior to this
		echo "/*\r\n";
		echo "	BAPI TextData\r\n";
		echo "	Last updated: " . date('r',$lastupdatetime) . "\r\n";	
		echo "	Language: " . getbapilanguage() . "\r\n";
		echo "*/\r\n\r\n";
		echo "BAPI.textdata = " . $js . ";\r\n";
		exit();
	}
	
	function urlHandler_bapitemplates() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.templates.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');
		//header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
				 
		$path = plugins_url('bapi/bapi.ui.mustache.tmpl', __FILE__);
		$path = get_relative($path);
		$path = realpath(substr($path,1));
		$c = file_get_contents($path);
		$j2 = rawurlencode($c); //addslashes($c);	
		echo "var t = '" . $j2 . "';\r\n";	
		echo "t = decodeURIComponent(t);\r\n";
		echo "BAPI.templates.set(t);\r\n";	
		exit();
	}
	
	function urlHandler_sitelist() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.sitelist.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');
		$blog_list = get_blog_list( 0, 'all' );
		$i=0;
		echo '{';
		foreach ($blog_list AS $blog) {
			if ($i>0) echo ', ';
			echo $blog['domain'].$blog['path'];
			$i++;
		}
		echo '}';
		exit();
	}
	
	
	/* Converted a url to a physical file path */
	function get_local($url) {
		$urlParts = parse_url($url);
		return realpath($_SERVER['DOCUMENT_ROOT']) . $urlParts['path'];				
	}
	
	function get_relative($url) {
		$urlParts = parse_url($url);
		return $urlParts['path'];		
	}
	
	function get_adminurl($url) {
		$url = get_relative( plugins_url($url, __FILE__) );
		return str_replace("/wp-content/plugins","",$url);	
	}	
	
	/* BAPI Helpers */	
	function getbapiurl() {
		global $bapi_all_options;
		$bapi_baseurl = 'connect.bookt.com';
		//Check if there is a globally defined baseurl constant.  This should be set in wp-config.php like so: define('BAPI_BASEURL', 'connect.bookt.com');
		if(defined(BAPI_BASEURL)){ 
			$bapi_baseurl = BAPI_BASEURL;
		}
		if($bapi_all_options['bapi_baseurl']){
			$bapi_baseurl = get_option('bapi_baseurl');
		}
		if(empty($bapi_baseurl) || $bapi_baseurl=='connect.bookt.com'){
			$bapi_baseurl = 'd2kqqk9digjl80.cloudfront.net';  
			//$bapi_baseurl = 'connect.bookt.com';
		}
		if (stripos($bapi_baseurl, "localhost", 0) === 0) {			
			return "http://" . $bapi_baseurl;
		}
		return "https://" . $bapi_baseurl;
	}

	function getbapilanguage() {
		global $bapi_all_options;
		$language = $bapi_all_options['bapi_language'];	
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
		global $bapi_all_options;
		return $bapi_all_options['api_key'];	
	}
	
	function getbapisolutiondata() {
		$wrapper = array();	
		$wrapper['site'] = getbapicontext();
		$wrapper['textdata'] = getbapitextdata();			
		return $wrapper;
	}	
	
	function getbapicontext() {	
		global $bapi_all_options;
		return json_decode($bapi_all_options['bapi_solutiondata'],TRUE); 		
	}
	
	function getbapitextdata() {
		global $bapi_all_options;
		return json_decode($bapi_all_options['bapi_textdata'],TRUE); 		
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
	
	/* Common include files needed for BAPI */
	function getconfig() {	
		global $bapi_all_options;	
		//echo 'getconfig';
		//echo get_option('api_key');
		if($bapi_all_options['api_key']){
			$apiKey = $bapi_all_options['api_key'];
			$language = getbapilanguage();			
			
			$secureurl = '';
			if($bapi_all_options['bapi_secureurl']){
				$secureurl = $bapi_all_options['bapi_secureurl'];
			}
			$siteurl = $bapi_all_options['home'];
			if($bapi_all_options['bapi_site_cdn_domain']){
				$siteurl = $bapi_all_options['bapi_site_cdn_domain'];
			}
			$siteurl = str_replace("http://", "", $siteurl);
?>
<link rel="stylesheet" type="text/css" href="<?= get_relative(plugins_url('/css/jquery.ui/jquery-ui-1.10.2.min.css', __FILE__)) ?>" />

<script type="text/javascript" src="<?= get_relative(plugins_url('/js/jquery.1.9.1.min.js', __FILE__)) ?>" ></script>
<script type="text/javascript" src="<?= get_relative(plugins_url('/js/jquery-migrate-1.0.0.min.js', __FILE__)) ?>" ></script>		
<script type="text/javascript" src="<?= get_relative(plugins_url('/js/jquery-ui-1.10.2.min.js', __FILE__)) ?>" ></script>
<script type="text/javascript" src="<?= get_relative(plugins_url('/js/jquery-ui-i18n.min.js', __FILE__)) ?>" ></script>
<!--[if gt IE 7]>
<script type="text/javascript" src="<?= get_relative(plugins_url('/js/pickadate/source/pickadate.min.js', __FILE__)) ?>" ></script>			
<![endif]-->
<![if !IE]>
<script type="text/javascript" src="<?= get_relative(plugins_url('/js/pickadate/source/pickadate.min.js', __FILE__)) ?>" ></script>			
<![endif]>
<!--[if lte IE 8]>
<script type="text/javascript" src="<?= get_relative(plugins_url('/js/pickadate/source/pickadate.legacy.min.js', __FILE__)) ?>" ></script>			
<![endif]-->
<script type="text/javascript" src="<?= getbapijsurl($apiKey) ?>"></script>
<script type="text/javascript" src="<?= get_relative(plugins_url('/bapi/bapi.ui.js', __FILE__)) ?>" ></script>		
<script type="text/javascript" src="/bapi.textdata.js" ></script>		
<script type="text/javascript" src="/bapi.templates.js" ></script>		
<script type="text/javascript">		
	BAPI.UI.loading.setLoadingImgUrl('<?= get_relative(plugins_url("/img/loading.gif", __FILE__)) ?>');
	BAPI.site.url =  '<?= $siteurl ?>';
	<?php if ($secureurl!='') { ?>
	BAPI.site.secureurl = '<?= $secureurl ?>';
	<?php } ?>
	BAPI.init();
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
		if(get_option('bapi_site_cdn_domain')&&!(is_admin()||is_super_admin())){
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
		
		$lastu = (int) get_post_meta($pid,'bapi_last_update',true);
		$lastu = date('r',$lastu);
		
		?><meta name="LASTMOD" content="<?= $lastu ?>" /><?= "\n" ?><meta name="KEYWORDS" content="<?= $metak ?>" /><?= "\n" ?><meta name="DESCRIPTION" content="<?= $metad ?>" /><?= "\n" ?><?php
	}
	
	function bapi_add_entity_meta(){
		global $entityUpdateURL;
		?><meta name="ENTITYURL" content="<?= $entityUpdateURL ?>" /><?= "\n" ?><?php
	}
	function bapi_add_context_meta(){
		global $getContextURL;
		?><meta name="CONTEXTURL" content="<?= $getContextURL ?>" /><?= "\n" ?><?php
	}
	function bapi_add_textdata_meta(){
		global $textDataURL;
		?><meta name="TEXTDATAURL" content="<?= $textDataURL ?>" /><?= "\n" ?><?php
	}
	function bapi_add_seo_meta(){
		global $seoDataURL;
		?><meta name="SEOURL" content="<?= $seoDataURL ?>" /><?= "\n" ?><?php
	}
	
	function getBAPIObj() {
		global $bapi_all_options;
		return new BAPI($bapi_all_options['api_key'], $bapi_all_options['bapi_language'], $bapi_all_options['bapi_baseurl']);
	}		
	
	function disable_kses_content() {
		if(is_admin()||is_super_admin()){
			remove_filter('content_save_pre', 'wp_filter_post_kses');
		}
	}
	
	function custom_upload_mimes ( $existing_mimes=array() ) {
		// add the file extension to the array
		$existing_mimes['ico'] = 'image/x-icon';
		// call the modified list of extensions
		return $existing_mimes;
	}
?>