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
		if(!isset($bapi_all_options['bapi_first_look'])){
			$bapi_all_options['bapi_first_look'] = 0;
		}
		//print_r($bapi_all_options); exit();
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
	
	function urlHandler_bapiconfig() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.config.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');
		//header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		
		echo 'BAPI.config().searchmodes={}||BAPI.config().searchmodes'; echo "\r\n";
		global $bapi_all_options;
		$sitesettings = $bapi_all_options['bapi_sitesettings'];
		$array = json_decode($sitesettings, TRUE);
		foreach($array as $v) {
			if (strpos($v, 'BAPI.config()') === 0) {
				echo stripslashes($v); echo "\r\n";
			}
			//print_r($v);
		}
		exit();
	}
	
	function urlHandler_bapitemplates() {
		$url = get_relative($_SERVER['REQUEST_URI']);
		if (strtolower($url) != "/bapi.templates.js")
			return; // not our handler
		
		header('Content-Type: application/javascript');	
		header('Cache-Control: public');
		//header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
				 
		$c = file_get_contents(BAPISync::getMustacheLocation());
		$j2 = rawurlencode($c); //addslashes($c);		
		
		if (BAPISync::isMustacheOverriden()) {
			echo "// custom bapi template file\r\n";
		} else {
			echo "// baseline bapi template file\r\n";
		}
		
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
			$sitesettings = $bapi_all_options['bapi_sitesettings'];
?>
<script type="text/javascript">
window.Muscula = { settings: { logId: "2d835166-5e05-4073-817c-c7d0bf477ff4", suppressErrors: false, branding: "none" } }; (function () { var m = document.createElement("script"); m.type = "text/javascript"; m.async = true; m.src = (window.location.protocol == "https:" ? "https:" : "http:") + "//musculahq.appspot.com/Muscula2.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(m, s); window.Muscula.run = function (c) { eval(c); window.Muscula.run = function () { } }; window.Muscula.errors = []; window.onerror = function () { window.Muscula.errors.push(arguments); return window.Muscula.settings.suppressErrors === undefined } })();
</script>
<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/jquery.ui.all.css" rel="stylesheet" />

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js" type="text/javascript"></script>    
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/i18n/jquery-ui-i18n.min.js" type="text/javascript"></script>
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
<script type="text/javascript" src="<?= getbapiuijsurl() ?>" ></script>		
<script type="text/javascript" src="/bapi.textdata.js" ></script>
<script type="text/javascript" src="/bapi.templates.js" ></script>
<?php if (!empty($sitesettings) && $sitesettings!='') { ?>
<script type="text/javascript" src="/bapi.config.js" ></script>
<?php } ?>
<script type="text/javascript">
    preload_image = new Image(66,66); 
    preload_image.src="<?= get_relative(plugins_url("/img/loading.gif", __FILE__)) ?>"; 
	BAPI.UI.loading.setLoadingImgUrl('<?= get_relative(plugins_url("/img/loading.gif", __FILE__)) ?>');
	BAPI.site.url =  '<?= $siteurl ?>';
	<?php if ($secureurl!='') { ?>
	BAPI.site.secureurl = '<?= $secureurl ?>';
	<?php } ?>
	BAPI.init();
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
		?><meta name="google-site-verification" content="<?= $bapi_all_options['bapi_google_webmaster_htmltag'] ?>" />
<?php
	}
}

function getTextDataArray(){
	bapi_wp_site_options();
	global $bapi_all_options;
	$apikey = $bapi_all_options["api_key"];
	$connecturl = $bapi_all_options["bapi_baseurl"];
	//var_dump($connecturl);
	$dataurl = 'https://'.$connecturl.'/ws/?method=get&entity=textdata&apikey='.$apikey;
	$data = file_get_contents($dataurl);
	$textDataArray = json_decode($data,TRUE);
	$textDataArray = $textDataArray["result"];
	if($textDataArray === null){
		$textDataArray = '';
	}
	return $textDataArray;
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
					add_meta_box( 'pageattributesmessage_meta_box_id', 'Page Attributes', 'create_pageattributesmessage_meta_box', 'page', 'side', 'high' );
				}
			}
		}
	}
	
	function create_pageattributesmessage_meta_box()
	{
		echo '<div style="background-color:#FCF8E3;border:1px solid #FBEED5;border-radius:4px;padding:8px 35px 8px 14px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);color: #C09853;">This page is synced with InstaManager. Editing content, URL and page attributes is disabled.<br/> <a href="//support.bookt.com/customer/portal/articles/1455747-missing-attributes-on-shared-pages" target="_blank">Learn More</a></div>';  
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
	$('#wpbody .wrap h2').html('<img src="<?php echo plugins_url( 'img/dashboard-logo.png' , __FILE__ ) ?>" />');
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
        <h1><?php _e( 'Welcome to your InstaSite!' ); ?></h1>
        <p class="about-description"><?php _e( 'If this is your first time here, take the tour, choose theme, etc.' ); ?><br />			<?php _e( 'If you are tired of seeing this message simply close at the top right.' ); ?></p>
        </div>
</div>
<?php
}
add_action( 'welcome_panel', 'bapi_welcome_panel' );

function bapi_dashboard_custom_footer() {
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
	  add_meta_box('bapi-instaapp', 'InstaApp Actions', 'register_instaapp_box', 'dashboard', 'normal', 'high');
	  add_meta_box('bapi-action', 'Advanced Actions', 'register_action_box', 'dashboard', 'normal', 'high');
	  add_meta_box('bapi-tips', 'Tips', 'register_tips_box', 'dashboard', 'normal', 'high');
	  wp_enqueue_style( 'custom-dashboard', plugins_url('css/custom-dashboard.css', __FILE__) );
	}
add_action('wp_dashboard_setup', 'bapi_register_dashboard_metabox',2);

function register_started_box() {	
/* Getting Started Metabox */
	$items = array( array( url => "themes.php", 
                      icon => "welcome-icon dashicons-images-alt2",
                      name => "Choose your Theme" 
                    ),
               array( url => "themes.php?page=theme_options", 
                      icon => 'welcome-icon dashicons-admin-appearance',
                      name => 'Change your theme',
                    ),
			   array( url => "admin.php?page=bookt-api/setup-slideshow.php", 
                      icon => "welcome-icon dashicons-format-gallery",
                      name => "Add a slideshow" 
                    ),	 	
               array( url => "nav-menus.php", 
                      icon => "welcome-icon dashicons-menu",
                      name => "Manage your menu" 
                    ),
				array( url => "post-new.php?post_type=page", 
                      icon => "welcome-icon dashicons-welcome-add-page",
                      name => "Add a page" 
                    )	
             );	
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
   echo '<ul>';
   for($i = 0; $i < count($items) ; $i++ ){		
				echo '<li>';
				echo '<a href="'.admin_url( $items[$i]['url'] ).'" class="'.$items[$i]['icon'].'">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	echo '<li><a class="button button-primary button-hero load-customize hide-if-no-customize" href="'.home_url( '/' ).'">View your site</a></li>';
	echo '</ul></div>';
}
function register_instaapp_box() {	
/* Instaapp Options Metabox */
	$items = array( array( url => "https://app.instamanager.com/SummaryV2.aspx?sid=1011", 
                      icon => "welcome-icon dashicons-screenoptions",
                      name => "Manage Properties" 
                    ),
               array( url => "https://app.instamanager.com/SummaryV2.aspx?sid=2014", 
                      icon => 'welcome-icon dashicons-search',
                      name => 'Set up Property Finders',
                    ),
               array( url => "https://app.instamanager.com/SummaryV2.aspx?sid=1010", 
                      icon => "welcome-icon dashicons-location-alt",
                      name => "Set up Attractions" 
                    ),
				array( url => "https://app.instamanager.com/SummaryV2.aspx?sid=9023", 
                      icon => "welcome-icon dashicons-awards",
                      name => "Add Specials for your visitors" 
                    ),
				array( url => "https://app.instamanager.com/marketing/optionalservices/", 
                      icon => "welcome-icon dashicons-plus",
                      name => "See Optional Services" 
                    ),
				array( url => "https://app.instamanager.com/", 
                      icon => "button button-primary button-hero load-customize hide-if-no-customize",
                      name => "Go To InstaApp" 
                    )		
             );	
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
echo '<ul>';
   for($i = 0; $i < count($items) ; $i++ ){		
				echo '<li>';
				echo '<a href="'.$items[$i]['url'].'" class="'.$items[$i]['icon'].'">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	echo '</ul></div>';
}
function register_action_box() {	
/* Advanced Options Metabox */
	$items = array( array( url => "options-general.php?page=mr_social_sharing", 
                      icon => "welcome-icon dashicons-facebook-alt",
                      name => "Setup social media" 
                    ),
               array( url => "options-general.php?page=googlelanguagetranslator-menu-options", 
                      icon => 'welcome-icon dashicons-translation',
                      name => 'Add Google Translate',
                    ),
               array( url => "admin.php?page=bookt-api/setup-sitesettings.php", 
                      icon => "welcome-icon dashicons-admin-generic",
                      name => "Change your site settings" 
                    ),
				array( url => "themes.php?page=theme_options", 
                      icon => "welcome-icon dashicons-art",
                      name => "Add Custom CSS or Scripts" 
                    ),
				array( url => "themes.php?page=theme_options", 
                      icon => "welcome-icon dashicons-format-image",
                      name => "Change Logo Size or Add a Favicon" 
                    )		
             );	
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
echo '<ul>';
   for($i = 0; $i < count($items) ; $i++ ){		
				echo '<li>';
				echo '<a href="'.admin_url($items[$i]['url']).'" class="'.$items[$i]['icon'].'">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	echo '</ul></div>';
}
function register_tips_box() {	
/* Tips Metabox */
	$items = array( array( url => "https://codex.wordpress.org/WordPress_Widgets", 
                      icon => "welcome-icon dashicons-editor-help",
                      name => "What are widgets" 
                    ),
               array( url => "http://support.bookt.com/customer/portal/articles/1200398-managing-seo-and-keywords-for-your-instasite", 
                      icon => 'welcome-icon dashicons-analytics',
                      name => 'Manager SEO',
                    ),
               array( url => "http://support.bookt.com/", 
                      icon => "welcome-icon welcome-view-site",
                      name => "Visit Support" 
                    ),
				array( url => "http://support.bookt.com/customer/portal/emails/new", 
                      icon => "welcome-icon dashicons-sos",
                      name => "Stuck? Contact Support" 
                    )	
             );	
	// Display the container
	echo '<div class="welcome-panel rss-widget custom">';
echo '<ul>';
   echo '<li><div class="welcome-icon dashicons-welcome-learn-more">How to create a <a href="http://codex.wordpress.org/Writing_Posts">blog</a> or <a href="http://codex.wordpress.org/Pages">page</a></div></li>';
   for($i = 0; $i < count($items) ; $i++ ){		
				echo '<li>';
				echo '<a href="'.$items[$i]['url'].'" class="'.$items[$i]['icon'].'">';
				echo $items[$i]['name'];
				echo '</a>';
				echo '</li>';
	}
	echo '</ul></div>';
}
?>
