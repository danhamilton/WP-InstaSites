<?php

	/* PLUGIN VERSION-RELATED FUNCTIONS */

	function kigo_plugin_activation() {

		// This plugin requires a new table
		if( !Kigo_Single_Sign_On::create_table() ) {
			wp_die('Error activating Kigo Sites plugin');
		}

		add_site_option( 'wp_plugin_kigo_sites_current_version', KIGO_PLUGIN_VERSION );
	}

	function kigo_plugin_deactivation() {

		if( !Kigo_Single_Sign_On::drop_table() ) {
			wp_die('Error deactivating Kigo Sites plugin');
		}

		delete_site_option( 'wp_plugin_kigo_sites_current_version' );
	}

	// Version checker call the function update( <version_number> ). This allow doing action on version update.
	// If the version format is changed please be ensure that the new format is compatible with the previous one and is higher when compared with strcmp()
	function kigo_plugin_detect_update() {

		$option_name = 'wp_plugin_kigo_sites_current_version';

		if( !is_string( $current_version = get_site_option( $option_name ) ) ) {

			// What if it's a WP single becoming WP MU? Options will be stored in different tables and we'll forget the plugin version?!
			// Well, doc says: "Deactivate all active plugins [before creating the network!]".
			// So problem solved.
			// source: http://codex.wordpress.org/Create_A_Network

			// For pre-existing and activated plugins that didn't have this version control, init version with 0
			add_site_option( $option_name, $current_version = '0' );
		}

		if( strcmp( $current_version, KIGO_PLUGIN_VERSION ) < 0 ) {
			if( !kigo_on_plugin_update( $current_version ) ) {
				wp_die('An error occured while the Kigo Sites plugin was being updated. Please try again.');
			}

			update_site_option( $option_name, KIGO_PLUGIN_VERSION );
		}
	}

	function kigo_on_plugin_update( $current_version ) {

		if( strcmp( $current_version, '1.0.20141002' ) < 0 ) { // The auto sign on table was introduced in version 1.0.20141002 2014/10/02, every previous version should create it now!
			if( !Kigo_Single_Sign_On::create_table() ) {
				return false;
			}
		}

		return true;
	}

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
		if(defined('BAPI_BASEURL')){
			$bapi_all_options['bapi_baseurl'] = BAPI_BASEURL;
		}
		if(!isset($bapi_all_options['bapi_first_look'])){
			$bapi_all_options['bapi_first_look'] = 0;
		}
		//print_r($bapi_all_options); exit();
	}

	/* Rebranding functions */
	function is_newapp_website() {

		$data = getbapicontext();

		return (
			is_array($data) &&
			isset( $data[ 'App' ] ) &&
			isset( $data[ 'App' ][ 'Data' ] ) &&
		    false !== strpos( $data['App']['Data'], 'newapp.kigo.net' )
		);
	}

	function newapp_login_headertitle( $title ) {
		if( !is_newapp_website() ) {
			return $title;
		}

		return 'Kigo Websites - Powered by Kigo';
	}


	/* BAPI url handlers */
	function urlHandler_emailtrackingimage() {
		$url = get_relative($_SERVER['REQUEST_URI']);		
		$url = strtolower($url);
		$url = substr($url, 0, 8);
		
		if ($url == "/t/misc/") {
			//header('Content-Type: application/javascript');	
			header('Cache-Control: public');
			//$expires = round((60*10 + $lastupdatetime), 2); // expires every 10 mins
			//$expires = gmdate('D, d M Y H:i:s \G\M\T', $expires);
			//header( 'Expires: ' . $expires );		
			echo "Image Handler";
			exit();
		}
	}
	
	function urlHandler_bapitextdata() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.textdata.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');

		$expires = round((60*10 + $lastupdatetime), 2); // expires every 10 mins
		$expires = gmdate('D, d M Y H:i:s \G\M\T', $expires);
		header( 'Expires: ' . $expires );
		
		echo urlHandler_bapitextdata_helper();
		exit();
	}
	
	function urlHandler_bapitextdata_helper() {
		global $bapi_all_options; 
		$js = $bapi_all_options['bapi_textdata']; // core data should have been synced prior to this
		$jsn = "/*\r\n";
		$jsn .= "	BAPI TextData\r\n";
		$jsn .= "	Last updated: " . date('r',$lastupdatetime) . "\r\n";	
		$jsn .= "	Language: " . getbapilanguage() . "\r\n";
		$jsn .= "*/\r\n\r\n";
		$jsn .= "BAPI.textdata = " . $js . ";\r\n";	
		return $jsn;
	}
	
	function urlHandler_bapiconfig() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.config.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');
		//header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		echo urlHandler_bapiconfig_helper();
		exit();
	}
	
	function urlHandler_bapiconfig_helper() {
		$js = '';
		$js .= 'BAPI.config().searchmodes={}||BAPI.config().searchmodes'; 
		$js .= "\r\n";
		global $bapi_all_options;
		$sitesettings = $bapi_all_options['bapi_sitesettings'];
		/*do nothing if $sitesettings is null or empty*/
		if($sitesettings != null && $sitesettings != ''){
			$array = json_decode($sitesettings, TRUE);
			foreach($array as $v) {
				if (strpos($v, 'BAPI.config()') === 0) {
					$js .= stripslashes($v)."\r\n";
				}
				//print_r($v);
			}
			/* we check if the headline field its enabled. if not dont do a thing*/
			if (strpos($sitesettings,'BAPI.config().headline.enabled=true;') !== false){
				$bapi = getBAPIObj();
				$theProperty = $bapi->quicksearch("property",null,false);
				$headlinesArray = $theProperty["result"];
				if(count($headlinesArray) > 0){
					$js .= "BAPI.config().headline.values=["; 
					  foreach ( $headlinesArray as $page ){
						$js .= '{"Label":"'.str_replace('"',"&quot;",$page["obj"]).'"}';
						if(end($headlinesArray) != $page){
							$js .= ","; // not the last element
						}
					  }
					$js .= "]";
				}
			}
		}
		return $js;
	}
	
	
	function urlHandler_bapitemplates() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.templates.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');
		//header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		echo urlHandler_bapitemplates_helper();
		exit();
	}
	
	function urlHandler_bapitemplates_helper() {		 
		$c = file_get_contents(BAPISync::getMustacheLocation());
		$j2 = rawurlencode($c); //addslashes($c);		
		$js = "";
		
		if (BAPISync::isMustacheOverriden()) {
			$js .= "// custom bapi template file\r\n";
		} else {
			$js .= "// baseline bapi template file\r\n";
		}
		
		$js .= "var t = '" . $j2 . "';\r\n";	
		$js .= "t = decodeURIComponent(t);\r\n";
		$js .= "BAPI.templates.set(t);\r\n";	
		return $js;
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
	
	function urlHandler_timthumb() {
		$url = $_SERVER['REQUEST_URI'];		
		$url = strtolower($url);
		$url = substr($url, 0, 8);
		
		if ($url == "/img.php") {
			include('thumbs/timthumb.php');
			exit();
		}
	}
	
	function urlHandler_bapi_ui_min() {
		$url = $_SERVER['REQUEST_URI'];		
		$url = strtolower($url);
		if ($url == "/bapi.ui.min.js") {
			header('Content-Type: application/javascript');	
			header('Cache-Control: public');
			$js = file_get_contents('bapi/bapi.ui.js', true);
			$minifiedCode = \JShrink\Minifier::minify($js);
			echo $minifiedCode;
			exit();
		}
	}
	
	function urlHandler_bapi_js_combined() {
		global $bapi_all_options;
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
			$sitesettings = $bapi_all_options['bapi_sitesettings'];
			$url = $_SERVER['REQUEST_URI'];		
			$url = strtolower($url);
			$url = substr($url, 0, 21);
			if ($url == "/bapi.combined.min.js") {	
				header('Content-Type: application/javascript');	
				header('Cache-Control: public');
				$js = urlHandler_bapi_js_combined_helper();
				if( // In debug mode, do not minify or use cache for the combined JS file
					defined('KIGO_DEBUG') &&
					true === KIGO_DEBUG
				) {
					echo $js;
					exit();
				}
				$jsh = md5($js);
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					$cacheFile = sys_get_temp_dir().'\\'.$jsh.'.js';
				} else {
					$cacheFile = sys_get_temp_dir().'/'.$jsh.'.js';
				}
				if(file_exists($cacheFile)){
					include($cacheFile);
				}
				else{
					$fp = fopen($cacheFile, 'w');
					$minifiedCode = \JShrink\Minifier::minify($js);
					fwrite($fp, $minifiedCode);
					fclose($fp);
					echo $minifiedCode;
				}
				exit();
			}
		}
	}
	
	function urlHandler_bapi_js_combined_helper() {
		global $bapi_all_options;
		$sitesettings = $bapi_all_options['bapi_sitesettings'];
		$js = '';
		$js .= file_get_contents('bapi/bapi.ui.js', true);
		$js .= urlHandler_bapitextdata_helper();
		$js .= urlHandler_bapitemplates_helper();
		if (!empty($sitesettings) && $sitesettings!='') {
			$js .= urlHandler_bapiconfig_helper();
		}
		return $js;
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
		$siteurl = parse_url(site_url());
		$str = $siteurl['path']."/wp-content/plugins";
		return str_replace($str,"",$url);	
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
			$bapi_baseurl = $bapi_all_options['bapi_baseurl'];
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
		return getbapiurl() . "/js/bapi.js?apikey=" . $apiKey;
	}

	function getbapiuijsurl() {
		return getbapiurl() . "/ws/js/bapi.ui.js";
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

	static $BAPI_ALL_OPTIONS__BAPI_SOLUTIONDATA_DECODED = null;
	function getbapicontext() {	
		global $bapi_all_options, $BAPI_ALL_OPTIONS__BAPI_SOLUTIONDATA_DECODED;

		// cache the JSON decoding, as this is called several times in 1 execution
		if(!is_array($BAPI_ALL_OPTIONS__BAPI_SOLUTIONDATA_DECODED)) {
			$BAPI_ALL_OPTIONS__BAPI_SOLUTIONDATA_DECODED = json_decode( $bapi_all_options['bapi_solutiondata'], true );
		}

		return $BAPI_ALL_OPTIONS__BAPI_SOLUTIONDATA_DECODED;
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
	
	function enqueue_and_register_my_scripts_in_head(){
		wp_register_script( 'jquery-min', '//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js',false,'1.9.1' );
		wp_enqueue_script( 'jquery-min' );
		
		wp_register_script( 'jquery-migrate-min', '//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js',array( 'jquery-min'),'1.2.1' );
		wp_enqueue_script( 'jquery-migrate-min' );
		
		wp_register_script( 'jquery-ui-min', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js',array( 'jquery-min'),'1.10.3' );
		wp_enqueue_script( 'jquery-ui-min' );
		
		wp_register_script( 'jquery-ui-i18n-min', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/i18n/jquery-ui-i18n.min.js',array( 'jquery-min'),'1.10.3' );
		wp_enqueue_script( 'jquery-ui-i18n-min' );

		wp_register_style( 'kigo-plugin-main', get_relative(plugins_url('/css/style.css', __FILE__)) );
		wp_enqueue_style( 'kigo-plugin-main' );
	}
	
	/* Load conditional script */
	function loadscriptjquery(){	
	?>
		<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/jquery.ui.all.css" rel="stylesheet" />    
		<!--[if lt IE 8]>
		<script type="text/javascript" src="<?= get_relative(plugins_url('/js/pickadate/source/legacy.js', __FILE__)) ?>" ></script>
		<![endif]-->
		<!--[if gte IE 8]>
		<script type="text/javascript" src="<?= get_relative(plugins_url('/js/pickadate/source/pickadate.min.js', __FILE__)) ?>" ></script>
		<![endif]-->
		<!--[if !IE]> -->
		<script type="text/javascript" src="<?= get_relative(plugins_url('/js/pickadate/source/pickadate.min.js', __FILE__)) ?>" ></script>
		<!-- <![endif]-->     
	<?php	
	}
	
	/* Common include files needed for BAPI */
	function getconfig() {
		global $bapi_all_options;
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
			$sitesettings = $bapi_all_options['bapi_sitesettings'];
			?>
			<script type="text/javascript" src="<?= getbapijsurl($apiKey) ?>" ></script>
			<script type="text/javascript" src="/bapi.combined.min.js?ver=<?= md5(urlHandler_bapi_js_combined_helper()) ?>" ></script>
			<script type="text/javascript">
				preload_image = new Image(66,66); 
				preload_image.src="<?= get_relative(plugins_url("/img/loading.gif", __FILE__)) ?>"; 
				BAPI.UI.loading.setLoadingImgUrl('<?= get_relative(plugins_url("/img/loading.gif", __FILE__)) ?>');
				BAPI.site.url =  '<?= $siteurl ?>';
				<?php if ($secureurl!='') { ?>
				BAPI.site.secureurl = '<?= $secureurl ?>';
				<?php } ?>
				BAPI.init();
				BAPI.UI.WPIS_PATH = '<?php echo get_relative( plugins_url( '/', __FILE__ ) ); ?>';
				BAPI.UI.jsroot = '<?= plugins_url("/", __FILE__) ?>';
				BAPI.defaultOptions.logpageviews = true;
				$(document).ready(function () { BAPI.UI.init(); });
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
			$slideshow[$i] = array("url"=>wp_make_link_relative($slide1),"caption"=>$slide1cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide1).'&h=80', __FILE__));
			$i++;
		}
		if(strlen($slide2)>0){
			$slideshow[$i] = array("url"=>wp_make_link_relative($slide2),"caption"=>$slide2cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide2).'&h=80', __FILE__));
			$i++;
		}
		if(strlen($slide3)>0){
			$slideshow[$i] = array("url"=>wp_make_link_relative($slide3),"caption"=>$slide3cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide3).'&h=80', __FILE__));
			$i++;
		}
		if(strlen($slide4)>0){
			$slideshow[$i] = array("url"=>wp_make_link_relative($slide4),"caption"=>$slide4cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide4).'&h=80', __FILE__));
			$i++;
		}
		if(strlen($slide5)>0){
			$slideshow[$i] = array("url"=>wp_make_link_relative($slide5),"caption"=>$slide5cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide5).'&h=80', __FILE__));
			$i++;
		}
		if(strlen($slide6)>0){
			$slideshow[$i] = array("url"=>wp_make_link_relative($slide6),"caption"=>$slide6cap,"thumb"=>plugins_url('thumbs/timthumb.php?src='.urlencode($slide6).'&h=80', __FILE__));
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
		if(get_option('bapi_site_cdn_domain')&&!(current_user_can('manage_options')||is_super_admin())){
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
		
		$metak = esc_attr( get_post_meta( $pid,'bapi_meta_keywords', true ) );
		$metad = esc_attr( get_post_meta( $pid,'bapi_meta_description', true ) );
		
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
	
	function display_global_header(){
		global $bapi_all_options;
		echo $bapi_all_options['bapi_global_header'];
	}
	
	function perm($return, $id, $new_title, $new_slug){
		/* if the user is not super admin */
		if (!is_super_admin()) {
			/* if the post var is set this var show when editing post and pages like this /wp-admin/post.php?post=2468&action=edit */
			if(isset($_GET['post']) && $_GET['post'] != ''){
			/* its set we get the post ID */
			$thePostID = $_GET['post'];
			/* we get the meta data array for this post */
			$metaArray = get_post_meta($thePostID);
				/* we check if our custom fields exists */
				if(!empty($metaArray) && array_key_exists('bapi_page_id', $metaArray) || array_key_exists('bapikey', $metaArray) || array_key_exists('bapi_last_update', $metaArray)){
					/* this is not a super admin and the page is a BAPI page we remove the permalink edit button*/
					$ret2 = preg_replace('/<span id="edit-slug-buttons">.*<\/span>/i', '', $return);
					return $ret2;
				}else{
					/* this is a page created by the user , we do nothing */
					return $return;
				}
			}
		}
			/* this is a super admin we do nothing */
			return $return;
	}
	function getSSL(){
		global $wp_query;
		$postid = $wp_query->post->ID;
		$thePostMeta = get_post_meta($postid, 'bapi_page_id', true);
		$SSLscriptBlock = '<div id="SSLcontent"><script pin type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=135640565046fb4bc11011f1400b8da37ea394266002690762020641"></script></div>';
		if($thePostMeta == 'bapi_makebooking'){echo $SSLscriptBlock;}
	}
	
	function bapi_setup_default_pages() {
		global $bapi_all_options;
		$url = get_relative($_SERVER['REQUEST_URI']);
		//echo $url; exit();
		if (strtolower($url) == "/bapi.init")
			return;
		if(!(strpos($_SERVER['REQUEST_URI'],'wp-admin')===false)||!(strpos($_SERVER['REQUEST_URI'],'wp-login')===false)){
			return;
		}
		$menuname = "Main Navigation Menu";
		$menu_id = initmenu($menuname);
		$menu = wp_get_nav_menu_items($menu_id);
		//print_r($menu);
		if(count($menu) == 0){
			//Initialize menu and pages
			if($bapi_all_options['bapi_first_look']==1){
				wp_die('<h3>Site Configuration Incomplete</h3>Please <a href="/wp-login.php?redirect_to='.urlencode(get_site_url()).'">sign-in to the dashboard</a> to complete setup','Site Configuration Incomplete');
			}
			$path = '/bapi.init?mode=initial-setup';
			$url = get_site_url().$path;
			//$server_output = file_get_contents($url);
			header("Cache-Control: no-cache, must-revalidate");
			header("HTTP/1.1 307 Temporary Redirect");
			header("Location: $url");
			exit();
		}
	}
	
	function bapi_reset_first_look(){
		update_option( 'bapi_first_look', 0 );
	}
	
	function bapi_login_handler(){
		header('Access-Control-Allow-Origin: *');
		$url = get_relative($_SERVER['REQUEST_URI']);
		//if (strtolower($url) != "/bapi.login"){
		if (strpos($_SERVER['REQUEST_URI'],'bapi.login')===false){
			return;
		}
		header("Cache-Control: no-cache, must-revalidate");
		
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$redir = $_REQUEST['redir'];
		
		$creds = array();
		$creds['user_login'] = $username;
		$creds['user_password'] = $password;
		$creds['remember'] = true;
		$user = wp_signon( $creds, false );
		if ( is_wp_error($user) )
			wp_die($user->get_error_message());
			
		header("HTTP/1.1 307 Temporary Redirect");
		header("Location: $redir");
		exit();
	}
	
	function bapi_no_follow(){
		//Amazon CloudFront
		if($_SERVER['HTTP_USER_AGENT']!="Amazon CloudFront"){
			?>
            <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
            <?php
		}
	}
	
	
  function relative_url() {
    // Don't do anything if:
    // - In feed
    // - In sitemap by WordPress SEO plugin
    if ( is_feed() || get_query_var( 'sitemap' ) )
      return;
    $filters = array(
      'post_link',       // Normal post link
      'post_type_link',  // Custom post type link
      'page_link',       // Page link
      '_page_link',       // Page link?
      'attachment_link', // Attachment link
      'get_shortlink',   // Shortlink
      'post_type_archive_link',    // Post type archive link
      'get_pagenum_link',          // Paginated link
      'get_comments_pagenum_link', // Paginated comment link
      'term_link',   // Term link, including category, tag
      'search_link', // Search link
      'day_link',   // Date archive link
      'month_link',
      'year_link',

      // site location
      'option_siteurl',
      'blog_option_siteurl',
      'option_home',
      'admin_url',
      'home_url',
      'includes_url',
      'site_url',
      'site_option_siteurl',
      'network_home_url',
      'network_site_url',

      // debug only filters
      'get_the_author_url',
      'get_comment_link',
      'wp_get_attachment_image_src',
      'wp_get_attachment_thumb_url',
      'wp_get_attachment_url',
      'wp_login_url',
      'wp_logout_url',
      'wp_lostpassword_url',
      'get_stylesheet_uri',
      // 'get_stylesheet_directory_uri',
      // 'plugins_url',
      // 'plugin_dir_url',
      // 'stylesheet_directory_uri',
      // 'get_template_directory_uri',
      // 'template_directory_uri',
      'get_locale_stylesheet_uri',
      'script_loader_src', // plugin scripts url
      'style_loader_src', // plugin styles url
      'get_theme_root_uri'
      // 'home_url'
    );

    foreach ( $filters as $filter ) {
      add_filter( $filter, 'bapi_make_link' );
    }
    home_url($path = '', $scheme = null);
  }
  
  
function bapi_make_link( $link ) {
	global $bapi_all_options; 
	$filters = array(
      'post_link',       // Normal post link
      'post_type_link',  // Custom post type link
      'page_link',       // Page link
      '_page_link',       // Page link?
      'attachment_link', // Attachment link
      'get_shortlink',   // Shortlink
      'post_type_archive_link',    // Post type archive link
      'get_pagenum_link',          // Paginated link
      'get_comments_pagenum_link', // Paginated comment link
      'term_link',   // Term link, including category, tag
      'search_link', // Search link
      'day_link',   // Date archive link
      'month_link',
      'year_link',

      // site location
      'option_siteurl',
      'blog_option_siteurl',
      'option_home',
      'admin_url',
      'home_url',
      'includes_url',
      'site_url',
      'site_option_siteurl',
      'network_home_url',
      'network_site_url',

      // debug only filters
      'get_the_author_url',
      'get_comment_link',
      'wp_get_attachment_image_src',
      'wp_get_attachment_thumb_url',
      'wp_get_attachment_url',
      'wp_login_url',
      'wp_logout_url',
      'wp_lostpassword_url',
      'get_stylesheet_uri',
      // 'get_stylesheet_directory_uri',
      // 'plugins_url',
      // 'plugin_dir_url',
      // 'stylesheet_directory_uri',
      // 'get_template_directory_uri',
      // 'template_directory_uri',
      'get_locale_stylesheet_uri',
      'script_loader_src', // plugin scripts url
      'style_loader_src', // plugin styles url
      'get_theme_root_uri'
      // 'home_url'
    );
	
	$cdn_url = 'test.com';
	//$home_url = str_replace($bapi_all_options['home'],$cdn_url,$path);
	if($bapi_all_options['bapi_site_cdn_domain']&&!(current_user_can('manage_options')||is_super_admin())){
		$cdn_url = $bapi_all_options['bapi_site_cdn_domain'];
	}
		
	foreach ( $filters as $filter ) {
	  remove_filter( $filter, 'bapi_make_link' );
	}
	$cdn = rtrim($cdn_url,'/');
	return preg_replace( '|https?://[^/]+(/?.*)|i', $cdn, $link );
	foreach ( $filters as $filter ) {
	  add_filter( $filter, 'bapi_make_link' );
	}
}

function display_gw_verification(){
	global $bapi_all_options;
	if(strlen($bapi_all_options['bapi_google_webmaster_htmltag'])>1){
		?><meta name="google-site-verification" content="<?= esc_attr($bapi_all_options['bapi_google_webmaster_htmltag']) ?>" />
<?php
	}
}

function getTextDataArray(){
	return BAPISync::getTextData();
}
	/**
	* Remove quick edit link in the list of all pages for non super users.
	*
	* @param	array		$actions		The page row actions
	* @param	object		$page_object			The page being listed
	* @return	array
	*/
	function remove_quickedit_for_nonsuperusers( $actions, $page_object ) {
		/* if the user is not super admin */
		if (!is_super_admin()) {
			/* we get the page ID */
			$thePageID = $page_object->ID;
			/* we get the meta data array for this post */
			$metaArray = get_post_meta($thePageID);
				/* we check if our custom fields exists */
				if(!empty($metaArray) && array_key_exists('bapi_page_id', $metaArray) || array_key_exists('bapikey', $metaArray) || array_key_exists('bapi_last_update', $metaArray)){
					/* this is not a super admin and the page is a BAPI page we remove quick edit*/
					unset ( $actions ['inline hide-if-no-js'] );
				}
		}
		return $actions;
	}
	
	/**
	* Remove page attributes meta box.
	*
	* @uses		remove_meta_box()
	*/
	function remove_pageattributes_meta_box() {
		/* if the user is not super admin */
		if (!is_super_admin()) {
			/* if the post var is set this var show when editing post and pages like this /wp-admin/post.php?post=2468&action=edit */
			if(isset($_GET['post']) && $_GET['post'] != ''){
			/* its set we get the post ID */
			$thePostID = $_GET['post'];
			/* we get the meta data array for this post */
			$metaArray = get_post_meta($thePostID);
				/* we check if our custom fields exists */
				if(!empty($metaArray) && array_key_exists('bapi_page_id', $metaArray) || array_key_exists('bapikey', $metaArray) || array_key_exists('bapi_last_update', $metaArray)){
					/* this is not a super admin and the page is a BAPI page we remove the metabox*/
					remove_meta_box( 'pageparentdiv', 'page', 'normal' );
					/* lets add a metabox with a message as to why there is no page Attributes metabox */
					if(!array_key_exists('bapi_page_id', $metaArray)){
						add_meta_box( 'pageattributesmessage_meta_box_id', 'Type: Data-Driven', 'create_DataDriventDetailPagesmessage_meta_box', 'page', 'side', 'high' );
						remove_post_type_support('page', 'title');
						remove_post_type_support('page', 'editor');
					}else{
						add_meta_box( 'pageattributesmessage_meta_box_id', 'Type: BAPI-Initialized', 'create_BAPIInitializedPagesmessage_meta_box', 'page', 'side', 'high' );
					}
				}else{
					add_meta_box( 'pageattributesmessage_meta_box_id', 'Type: Static', 'create_StaticPagesmessage_meta_box', 'page', 'side', 'high' );
				}
			}
		}
	}
	
	function create_DataDriventDetailPagesmessage_meta_box()
	{
		echo '<div class="updated inline"><p>This page is synchronized with ' . ( is_newapp_website() ? 'Kigo' : 'InstaManager' ) . '. All editing has been disabled.</p> <a href="' . ( is_newapp_website() ? '//supportdocs.imbookingsecure.com/missing_attributes_on_shared_pages' : '//support.bookt.com/customer/portal/articles/1455747-missing-attributes-on-shared-pages' ) . '" target="_blank">Learn More</a></div>';
	}
	function create_BAPIInitializedPagesmessage_meta_box()
	{
		echo '<div class="updated inline"><p>This page is synchronized with ' . ( is_newapp_website() ? 'Kigo' : 'InstaManager' ) . '. You may only edit the page content. All other editing functions have been disabled.</p> <a href="' . ( is_newapp_website() ? '//supportdocs.imbookingsecure.com/missing_attributes_on_shared_pages' : '//support.bookt.com/customer/portal/articles/1455747-missing-attributes-on-shared-pages' ) . '" target="_blank">Learn More</a></div>';
	}
	function create_StaticPagesmessage_meta_box()
	{
		echo '<div class="updated inline"><p>This page is a WordPress Page. All editing its enabled.</p> <a href="' . ( is_newapp_website() ? '//supportdocs.imbookingsecure.com/missing_attributes_on_shared_pages' : '//support.bookt.com/customer/portal/articles/1455747-missing-attributes-on-shared-pages' ) . '" target="_blank">Learn More</a></div>';
	}
	
/* Custom Instasite Dashboard */

function bapi_welcome_panel() {
/*
 Hide the defaul welcome message and put the custom Instansite block.
*/	
?>	
<script type="text/javascript">
/* Hide default welcome message */
jQuery(document).ready( function($) 
{
	<?php
	 if(!is_newapp_website()) {
		?> $('#wpbody .wrap h2').html('<img src="<?php echo plugins_url( 'img/dashboard-logo.png' , __FILE__ ) ?>" />'); <?php
	 }
	?>
	$('#welcome-panel .welcome-panel-content').hide();
	$('#welcome-panel .welcome-panel-close').hide();
	$('#welcome-panel.custom .welcome-panel-close').show();
	
});
</script>
<div id="welcome-panel" class="welcome-panel custom">
		<?php wp_nonce_field( 'welcome-panel-nonce', 'welcomepanelnonce', false ); ?>
        <div class="btn-close">
		<a class="welcome-panel-close" href="<?php echo esc_url( admin_url( '?welcome=0' ) ); ?>"><?php _e( 'Close' ); ?></a>
		</div>
        <div class="welcome-panel-content-custom">
        <h1><?php _e( ( is_newapp_website() ? 'Welcome to your Kigo site!' : 'Welcome to your InstaSite!' ) ); ?></h1>
        <p class="about-description"><?php _e( 'If this is your first time here, take the tour, choose theme, etc.' ); ?><br />			<?php _e( 'If you are tired of seeing this message simply close at the top right.' ); ?></p>
        </div>
</div>
<?php
}
add_action( 'welcome_panel', 'bapi_welcome_panel' );

function bapi_dashboard_custom_footer() {
	if( is_newapp_website() ){
		return; // Do not display the footer fo the newapp users
	}
/* Put the logo in the right botton footer */
 echo '<span id="footer-thankyou"><img src="'.plugins_url( 'img/dashboard-logo.png' , __FILE__ ).'" /></span>';
}
add_filter( 'admin_footer_text', 'bapi_dashboard_custom_footer' );

function hide_dashboard_metabox() {
/* Put off the wordpress dashboard default metabox*/	
   $hide = array(
      0 => 'dashboard_recent_comments',
      1 => 'dashboard_incoming_links',
      2 => 'dashboard_activity',
      3 => 'dashboard_quick_press',
      4 => 'dashboard_primary',
      5 => 'dashboard_secondary',
	  6 => 'dashboard_recent_drafts',
	  7 => 'dashboard_right_now',
   );
   return $hide;
}
add_filter('get_user_option_metaboxhidden_dashboard', 'hide_dashboard_metabox', 1);

function bapi_register_dashboard_metabox() {
/* Add the custom Instansite Metaboxes */	
	global $wp_meta_boxes;	
	  add_meta_box('bapi-gs', 'Getting Started', 'register_started_box', 'dashboard', 'normal', 'high');
	  add_meta_box('bapi-instaapp', ( is_newapp_website() ? 'Kigo App Actions' : 'InstaApp Actions' ), 'register_instaapp_box', 'dashboard', 'side', 'high');
	  add_meta_box('bapi-action', 'Advanced Actions', 'register_action_box', 'dashboard', 'normal', 'high');
	  add_meta_box('bapi-tips', 'Tips', 'register_tips_box', 'dashboard', 'side', 'high');
	  wp_enqueue_style( 'custom-dashboard', plugins_url('css/custom-dashboard.css', __FILE__) );
	}
add_action('wp_dashboard_setup', 'bapi_register_dashboard_metabox',2);

function register_started_box() {	
/* Getting Started Metabox */
	$items = array(
				array( 'url' => admin_url( "themes.php" ),
					  'icon' => "welcome-icon dashicons-images-alt2",
					  'name' => "Choose your theme"
					),
				array( 'url' => admin_url( "themes.php?page=theme_options#tabs-1" ),
					  'icon' => 'welcome-icon dashicons-admin-appearance',
					  'name' => 'Change your theme style',
					),
				array( 'url' => menu_page_url( "site_settings_slideshow", false ),
					  'icon' => "welcome-icon dashicons-format-gallery",
					  'name' => "Add a slideshow"
					),
				array( 'url' => admin_url( "nav-menus.php" ),
					  'icon' => "welcome-icon dashicons-menu",
					  'name' => "Manage your menu"
					),
				array( 'url' => admin_url( "post-new.php?post_type=page" ),
					  'icon' => "welcome-icon dashicons-welcome-add-page",
					  'name' => "Add a page"
					)
			 );
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
   echo '<ul>';
   for($i = 0; $i < count($items) ; $i++ ){		
				echo '<li>';
				echo '<a href="' . $items[$i]['url'] . '" class="' . $items[$i]['icon'] . '">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	echo '<li><a class="button button-primary button-hero" href="'.home_url( '/' ).'" target="_blank">View your site</a></li>';
	echo '</ul></div>';
}
function register_instaapp_box() {
/* Instaapp Options Metabox */
	$items = array(
			   array( 'url' => "https://" . ( is_newapp_website() ? 'newapp.kigo.net' : 'app.instamanager.com' ) . "/marketing/properties/",
                      'icon' => "welcome-icon dashicons-screenoptions",
                      'name' => "Manage Properties"
                    ),
               array( 'url' => "https://" . ( is_newapp_website() ? 'newapp.kigo.net' : 'app.instamanager.com' ) . "/marketing/propertyfinders/",
                      'icon' => 'welcome-icon dashicons-search',
                      'name' => 'Set up Property Finders',
                    ),
               array( 'url' => "https://" . ( is_newapp_website() ? 'newapp.kigo.net' : 'app.instamanager.com' ) . "/marketing/attractions/",
                      'icon' => "welcome-icon dashicons-location-alt",
                      'name' => "Set up Attractions"
                    ),
				array( 'url' => "https://" . ( is_newapp_website() ? 'newapp.kigo.net' : 'app.instamanager.com' ) . "/booking/mgr/setup/specials/",
                      'icon' => "welcome-icon dashicons-awards",
                      'name' => "Add Specials for your visitors"
                    ),
				array( 'url' => "https://" . ( is_newapp_website() ? 'newapp.kigo.net' : 'app.instamanager.com' ) . "/marketing/optionalservices/",
                      'icon' => "welcome-icon dashicons-plus",
                      'name' => "See Optional Services"
                    )
             );
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
echo '<ul>';
   for($i = 0; $i < count($items) ; $i++ ){
				echo '<li>';
				echo '<a href="'.$items[$i]['url'].'" class="'.$items[$i]['icon'].'" target="_blank">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	if( is_newapp_website() ){
		echo '<li><a class="button button-primary button-hero" href="https://newapp.kigo.net/" target="_blank">Go To Kigo App</a></li>';
	}
	else{
		echo '<li><a class="button button-primary button-hero" href="https://app.instamanager.com/" target="_blank">Go To InstaApp</a></li>';
	}
	echo '</ul></div>';
}
function register_action_box() {
/* Advanced Options Metabox */
	$items = array(
				array( 'url' => admin_url( "options-general.php?page=mr_social_sharing" ),
                      'icon' => "welcome-icon dashicons-facebook-alt",
                      'name' => "Set up Social Media"
                    ),
               array( 'url' => admin_url( "options-general.php?page=googlelanguagetranslator-menu-options" ),
                      'icon' => 'welcome-icon dashicons-translation',
                      'name' => 'Add Google Translate',
                    ),
                array( 'url' => menu_page_url( "site_settings_propsearch", false ),
                      'icon' => "welcome-icon dashicons-admin-generic",
                      'name' => "Property Search Settings"
                    ),
				array( 'url' => admin_url( "themes.php?page=theme_options#tabs-3" ),
                      'icon' => "welcome-icon dashicons-art",
                      'name' => "Add Custom CSS"
                    ),
				array( 'url' => menu_page_url( "site_settings_advanced", false ),
                      'icon' => "welcome-icon dashicons-welcome-write-blog",
                      'name' => "Add Custom Scripts"
                    ),
				array( 'url' => admin_url( "themes.php?page=theme_options#tabs-2" ),
                      'icon' => "welcome-icon dashicons-format-image",
                      'name' => "Change Logo Size or Add a Favicon"
                    ),
               array( 'url' => menu_page_url( "site_settings_golive", false ),
	                  'icon' => "welcome-icon dashicons-admin-site",
	                  'name' => "Take Me Live"
	                )
             );
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
echo '<ul>';
   for($i = 0; $i < count($items) ; $i++ ){
				echo '<li>';
				echo '<a href="' . $items[$i]['url'] . '" class="' . $items[$i]['icon'] . '">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	echo '</ul></div>';
?>
<?php
}
function register_tips_box() {
/* Tips Metabox */
	$items = array( array( url => "https://codex.wordpress.org/WordPress_Widgets",
                      icon => "welcome-icon dashicons-editor-help",
                      name => "What are widgets"
                    ),
               array( url => ( is_newapp_website() ? '//supportdocs.imbookingsecure.com/managing_seo_keywords' : "http://support.bookt.com/customer/portal/articles/1200398-managing-seo-and-keywords-for-your-instasite" ),
                      icon => 'welcome-icon dashicons-analytics',
                      name => 'How to Manage SEO',
                    ),
			   array( url => ( is_newapp_website() ? '//supportdocs.imbookingsecure.com/featured_properties_widget' : "http://support.bookt.com/customer/portal/articles/1394482-featured-properties-widget"),
                      icon => "welcome-icon dashicons-admin-post",
                      name => "Change your Featured Properties settings"
                    ),
			   array( url => ( is_newapp_website() ? '//supportdocs.imbookingsecure.com/websites' : "http://support.bookt.com/customer/portal/topics/566455-instasites/articles"),
                      icon => "welcome-icon dashicons-sos",
                      name => "View All " . ( is_newapp_website() ? 'Kigo sites' : 'InstaSite' ) . " Help Topics"
                    ),
               array( url => ( is_newapp_website() ? '//supportdocs.imbookingsecure.com' : "http://support.bookt.com/"),
                      icon => "welcome-icon welcome-view-site",
                      name => "Visit Support"
                    )
             );
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
echo '<ul>';
   echo '<li><div class="welcome-icon dashicons-welcome-learn-more">How to create a <a href="http://codex.wordpress.org/Writing_Posts" target="_blank">Blog</a> or <a href="http://codex.wordpress.org/Pages" target="_blank">Page</a></div></li>';
   for($i = 0; $i < count($items) ; $i++ ){
				echo '<li>';
				echo '<a href="'.$items[$i]['url'].'" class="'.$items[$i]['icon'].'" target="_blank">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	echo '</ul></div>';
}
?>
<?php
//add meta box to  wp backend
function myplugin_add_meta_box() {
	foreach ( array( 'post', 'page' ) as $screen ) {
		add_meta_box(
			'myplugin_sectionid',
			__( 'SEO Attributes &nbsp;&nbsp;&nbsp;<a href="'.menu_page_url( 'site_settings_advanced', false ).'">Google Adwords Code</a>', 'myplugin_textdomain' ),
			'myplugin_meta_box_callback',
			$screen
		);
	}
}
//adds the information inside the mata seo meta box
add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );
function myplugin_meta_box_callback( $metaId ) {
	wp_nonce_field( 'insta_seo_metabox ', 'myplugin_meta_box_nonce' );
	 $pageId = get_the_ID();
	 $url = get_permalink($pageId);
	 $newUrlray = split('http://localhost', $url);
	 $relpermalink = $newUrlray[1];
	 $meta_words = get_post_custom($pageId, '', true);
	 $post = get_post($pageId);
	 if(empty($meta_words['bapi_meta_title'][0])){
		$meta_words['bapi_meta_title'][0] = $post->post_title;
	 }
	 $keyword_meta = get_post_meta($pid,'bapi_meta_keywords',true);
	?>
	<!--  creats the live snippet preview box -->
	<script>
		jQuery(document).ready(function($) {
   			$("#Descript_prev").text("<?php echo addslashes($meta_words['bapi_meta_description'][0]);?>");
   			$("#seoTitle").text("<?php echo addslashes($meta_words['bapi_meta_title'][0]); ?>");
		$("#bapi_meta_description").keyup(function(){
			var prevDesc = $("#bapi_meta_description").val();
			var desc_length = prevDesc.length;
			var totLeft = 156 - desc_length;
			$("#Descript_prev").text(prevDesc).css({"width":"100%"});

			if(prevDesc == ""){
				$("#Descript_prev").text("New Description");
			}
			var charColor = $("#descrip_lenght").text(totLeft).css({"color":"green"});
			if(totLeft <= 0){
				$("#descrip_lenght").text(totLeft).css("color", "red");
			}
		});
		$("#bapi_meta_title").keyup(function(){
			var prevTitle = $("#bapi_meta_title").val();
			var title_length = prevTitle.length;
			var charleft = 70 - title_length;
			$("#seoTitle").text(prevTitle);
			var color = $("#Title_lenght").text(charleft).css("color", "green");
			if(charleft <= 0){
				$("#Title_lenght").text(charleft).css("color", "red");
			}
			if(prevTitle == ""){
				$("#seoTitle").text("New SEO Title");
			}
		});
	});
	</script>
	<!-- meta box fields -->
	<table style="max-width: 95%;">
	<tr>
	<td class="left" style="width:30%;">Snippet Preview: </td>
	<td><u><span style="color:#0000CF;" id="seoTitle"></span></u></td>
	</tr>
	<tr>
		<td></td>
		<td style="color: #006621;"><?php  echo $cdn_url = get_option('bapi_site_cdn_domain').$relpermalink;?></td>
	</tr>
	<tr>
		<td></td>
		<td style="padding-bottom: 40px;max-width: 300px;color: #808080;" ><div style="word-wrap: break-word;width:100%;" id="Descript_prev"></div></td>
	</tr>
	<tr >
		<td><label for="bapi_meta_keywords">Keywords:</label></td>
		<td><input  style="width:100%;" id="bapi_meta_keywords" class="input" type="text" name="bapi_meta_keywords" value="<?php echo esc_attr($meta_words['bapi_meta_keywords'][0]);?>"></td>
	</tr>
	<tr >
		<td><label for="bapi_meta_title">SEO Title:</label></td>
		<td><input style="width:100%;"id="bapi_meta_title" class="input" type="text" name="bapi_meta_title" value="<?php echo esc_attr($meta_words['bapi_meta_title'][0]); ?>" >
			<br />Title display in search engines is limited to 70 chars, <span id="Title_lenght"></span> chars left.
		</td>
	</tr>
	<tr>
		<td><label for="bapi_meta_description">Meta Description: </label></td>
		<td><textarea style="width:100%;" name="bapi_meta_description" id="bapi_meta_description" rows="5" cols="30" value="testing"><?php echo $meta_words['bapi_meta_description'][0];?></textarea>
			<br > The meta description will be limited  to 156 chars. <span id="descrip_lenght"></span> chars left.
		</td>
	</tr>
	</table>
<?php
}
//this function is triggered when save or update
 function save_seo_meta( $postid ) {
	$bapisync = new BAPISync();
	$bapisync->init();
	$perma = get_permalink();
	$permaPath = parse_url($perma);
	$relativePerma = get_relative($perma);
	$pageID = get_post_meta(get_the_ID(),'bapi_page_id');
	if($relativePerma=='/' && $pageID[0]!='bapi_home'){
		return;
	}
	$seo = $bapisync->getSEOFromUrl($relativePerma);
	$meta_words = get_post_custom($post->ID, '', true);
	$myPageId = $seo['ID'];
	$myType = $seo['entity'];
	$myPkId = $seo['pkid'];
	if($myType === null){$myType = 0;}
	if($myPageId === null){$myPageId = 0;}
	if($myPkId === null){$myPkId = 0;}
	$apiKey = getbapiapikey();
 	$bapi = getBAPIObj();
	if (!$bapi->isvalid()) { return; }
	$keywor = sanitize_text_field( $_POST[ 'bapi_meta_keywords' ]);
	$metle = sanitize_text_field( $_POST[ 'bapi_meta_title' ]);
	$meta_desc = sanitize_text_field( $_POST[ 'bapi_meta_description' ]);
	// save old value if keyword empty or null
	If($metle === null || empty($metle)){
		$metle = $meta_words['bapi_meta_title'][0];
	}
	If($meta_desc === null || empty($meta_desc)){
		$meta_desc = $meta_words['bapi_meta_description'][0];
	}
	if($keywor === null || empty($keywor)){
		$keywor = $meta_words['bapi_meta_keywords'][0];
	}
	//saves to wordpress database
	if(isset($_POST['bapi_meta_keywords'])){
		if($_POST['bapi_meta_keywords'] !== $meta_words['bapi_meta_keywords'][0]){
		}
		update_post_meta( $postid, 'bapi_meta_keywords', sanitize_text_field( $_POST[ 'bapi_meta_keywords' ]) );
	}
	if(isset($_POST['bapi_meta_title']) && $_POST['bapi_meta_title'] !== $meta_words['bapi_meta_title'][0]){
		update_post_meta( $postid, 'bapi_meta_title', sanitize_text_field( $_POST[ 'bapi_meta_title' ]) );
	}
	if(isset($_POST['bapi_meta_description']) && $_POST['bapi_meta_description'] !== $meta_words['bapi_meta_description'][0]){
		update_post_meta( $postid, 'bapi_meta_description', sanitize_text_field( $_POST[ 'bapi_meta_description' ]) );
	}
	$metaArr = array('MetaKeywords'=>$keywor,'PageTitle'=>$metle,'MetaDescrip'=>$meta_desc, 'ID'=> $myPageId,'pkid'=>$myPkId, 'Keyword'=>$relativePerma, 'entity'=>$myType);
	$jsify = json_encode($metaArr);
	$jsonObj = 'data='.(string)$jsify;
	// entety: tyoe and language  needs to be 
	//print_r($jsonObj);exit();
	$bapi->save($jsonObj,$apiKey);
	update_option( 'bapi_keywords_lastmod', 0 );
	bapi_sync_coredata();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	add_action( 'wp_insert_post',  'save_seo_meta');
}
