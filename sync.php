<?php

	require_once('bapi-php/bapi.php');
	require_once('functions.php');
	
	$bapisync = null;
	class BAPISync {
		public $soldata = null;
		public $textdata = null;
		public $seodata = null;
		public $templates = null;
		public function init() {
			$this->soldata = BAPISync::getSolutionData();
			$this->textdata = BAPISync::getTextData();
			$this->seodata = BAPISync::getSEOData();			
		}
		public function loadtemplates() { if (empty($this->templates)) { $this->templates = BAPISync::getTemplates(); } }
		
		public static function getSolutionDataRaw() { global $bapi_all_options; return $bapi_all_options['bapi_solutiondata']; }
		public static function getSolutionDataLastModRaw() { global $bapi_all_options; return $bapi_all_options['bapi_solutiondata_lastmod']; }
		public static function getSolutionData() { return json_decode(BAPISync::getSolutionDataRaw(), TRUE); }
		
		public static function getTextDataRaw() { global $bapi_all_options; return $bapi_all_options['bapi_textdata']; }
		public static function getTextDataLastModRaw() { global $bapi_all_options; return $bapi_all_options['bapi_textdata_lastmod']; }
		public static function getTextData() { return json_decode(BAPISync::getTextDataRaw(), TRUE); }
		
		public static function getSEODataRaw() { global $bapi_all_options; return $bapi_all_options['bapi_keywords_array']; }
		public static function getSEODataLastModRaw() { global $bapi_all_options; return $bapi_all_options['bapi_keywords_lastmod']; }
		public static function getSEOData() { return json_decode(BAPISync::getSEODataRaw(), TRUE); }
		
		public static function isMustacheOverriden() { 
			$basefilename = "bapi/bapi.ui.mustache.tmpl";
			// see if there is a custom theme in the theme's folder
			$test = get_stylesheet_directory() . '/' . $basefilename;
			if (file_exists($test)) {
				return true;
			}
			return false;			
		}
		
		public static function getMustacheLocation() { 
			$basefilename = "bapi/bapi.ui.mustache.tmpl";
			// see if there is a custom theme in the theme's folder
			$test = get_stylesheet_directory() . '/' . $basefilename;
			if (file_exists($test)) {
				return $test;
			}
			
			// otherwise, just return the baseline version stored in the plugin folder
			$test = plugins_url($basefilename, __FILE__);
			$test = get_relative($test);
			$test = realpath(substr($test,1));
			return $test;			
		}
		public static function getTemplates() { 			
			return file_get_contents(BAPISync::getMustacheLocation()); 
		}
		
		public static function cleanurl($url) {
			if (empty($url)) { return ""; }
			$url = strtolower(trim($url));
			if (strpos($url, "/") != 0) { $url = "/" . $url; }
			if (substr($url, -1) != "/") { $url = $url . "/"; }
			return $url; exit();
		}
		
		public static function clean_post_name($url) {
			$url = basename($url);			
			if (substr($url, -1) == "/") { $url = substr_replace($url ,"",-1); }
			return $url;
		}
		
		public function getSEOFromUrl($url) {
			if (empty($url)) { return null; }			
			$url = BAPISync::cleanurl($url);
			if(!empty($this->seodata)){
				foreach ($this->seodata as $seo) {												
					$turl = BAPISync::cleanurl($seo["DetailURL"]);				
					if ($turl == $url) { return $seo; }								
				}
			}
			return null;
		}
		
		public static function getPageKey($entity, $pkid) { return $entity . ':' . $pkid; }
		public static function getPageTemplate($entity) {
			if($entity=='property') { return 'page-templates/property-detail.php'; }
			if($entity=='development') { return 'page-templates/other-detail-page.php'; }
			if($entity=='specials') { return 'page-templates/other-detail-page.php'; }
			if($entity=='poi') { return 'page-templates/other-detail-page.php'; }
			if($entity=='searches') { return 'page-templates/other-detail-page.php'; }
			if($entity=='marketarea') { return 'page-templates/other-detail-page.php'; }
			return 'page-templates/full-width.php';
		}
		
		public static function getRootPath($entity) {
			if($entity=='property') { $t=BAPISync::getSolutionData(); return $t["Site"]["BasePropertyURL"]; }
			if($entity=='development') { $t=BAPISync::getSolutionData(); return $t["Site"]["BaseDevelopmentURL"]; }
			if($entity=='specials') { $t=BAPISync::getSolutionData(); return $t["Site"]["BaseSpecialURL"]; }
			if($entity=='poi') { $t=BAPISync::getSolutionData(); return $t["Site"]["BasePOIURL"]; }
			if($entity=='searches') { $t=BAPISync::getSolutionData(); return $t["Site"]["BasePropertyFinderURL"]; }
			if($entity=='marketarea') { $t=BAPISync::getSolutionData(); $s=BAPISync::getSEOData(); return ''; }
			return '/rentals/';
		}
		
		public function getMustacheTemplate($entity) {
			$template_name = "";
			if ($entity == "property") { $template_name = "tmpl-properties-detail"; }
			else if ($entity == "development") { $template_name = "tmpl-developments-detail"; }
			else if ($entity == "specials") { $template_name = "tmpl-specials-detail"; }
			else if ($entity == "poi") { $template_name = "tmpl-attractions-detail"; }
			else if ($entity == "searches") { $template_name = "tmpl-searches-detail"; }
			else if ($entity == "marketarea") { $template_name = "tmpl-marketarea-detail"; }
			if (empty($template_name)) { return ""; } // not a valid entity to get a template
			
			$this->loadtemplates();
			$si = strpos($this->templates, $template_name);
			if (!$si) { return ""; }			
			$si = strpos($this->templates, ">", $si+1);
			if (!si) { return ""; }
			$ei = strpos($this->templates, "</script>", $si);
			if (!ei) { return ""; }
			
			return substr($this->templates, $si+1, $ei-$si-1);			
		}
		
		public static function getMustache($entity, $pkid, $template,$debugmode=0) {
			if(!(strpos($_SERVER['REQUEST_URI'],'wp-admin')===false)||!(strpos($_SERVER['REQUEST_URI'],'wp-login')===false)){
				return false;
			}
			$bapi = getBAPIObj();
			if (!$bapi->isvalid()) { return; }
			$pkid = array(intval($pkid));

			/* Its the entity a property?, if yes, lets set the options */
			if($entity == "property"){
				$options = array("seo" => 1, "descrip" => 1, "avail" => 1, "rates" => 1, "reviews" => 1,"poi"=>1);	
			}else{
				/* Its the entity a poi?, if yes, lets set the options */
				if($entity == "poi"){
					$options = array("nearbyprops" => 1,"seo" => 1);	
				}else{
					$options = null;
				}
			}

			$c = $bapi->get($entity,$pkid,$options,true,$debugmode);
			$c["config"] = BAPISync::getSolutionData();
			$c["config"] = $c["config"]["ConfigObj"];
			/* we get the sitesettings */
			global $bapi_all_options;
			$sitesettings = json_decode($bapi_all_options['bapi_sitesettings'],TRUE);
			if (!empty($sitesettings)) {
				/* we get the review value from the sitesettings*/
				$hasreviews = $sitesettings["propdetail-reviewtab"];
				if (!empty($hasreviews)){
					/* we make an array using = and ; as delimiters */
					$hasreviews = split('[=;]', $hasreviews);
					/* we assign the value to var in the config array - reviews*/
					$hasreviews = $hasreviews[1];
					$c["config"]["hasreviews"] = ($hasreviews === 'true');
				}
				/* the same as review but for the availability calendar */
				$displayavailcalendar = $sitesettings["propdetail-availcal"];
				if (!empty($displayavailcalendar)){
					$displayavailcalendar = split('[=;]', $displayavailcalendar);
					$availcalendarmonths = (int) $displayavailcalendar[3];
					$displayavailcalendar = $displayavailcalendar[1];
					$c["config"]["displayavailcalendar"] = ($displayavailcalendar === 'true');
					$c["config"]["availcalendarmonths"] =  $availcalendarmonths;
				}
			}
			
			$c["textdata"] = BAPISync::getTextData();
			$m = new Mustache_Engine();
			$string = $m->render($template, $c);				
			$string = str_replace("\t", '', $string); // remove tabs
			$string = str_replace("\n", '', $string); // remove new lines
			$string = str_replace("\r", '', $string); // remove carriage returns
			return $string;
		}
	}
	
	function bapi_sync_entity($wp) {
		$debugmode = 0; //added by jacob for mantis #4115
		if(!(strpos($_SERVER['REQUEST_URI'],'wp-admin')===false)||!(strpos($_SERVER['REQUEST_URI'],'wp-login')===false)){
			return false;
		}
		//global $post;
		global $bapisync;		
		if (empty($bapisync)) { 
			// ERROR: What should we do?
		}

		$t=BAPISync::getSolutionData();
		$maEnabled = $t['BizRules']['Has Market Area Landing Pages'];
		
		$post = get_page_by_path($_SERVER['REDIRECT_URL']);
		if(empty($_SERVER['REDIRECT_URL'])){
			$home_id = get_option('page_on_front');
			$post = get_page($home_id);
		}	
		// parse out the meta attributes for the current post
		$page_exists_in_wp = !empty($post);				
		$meta = $page_exists_in_wp ? get_post_custom($post->ID) : null;
		$last_update = !empty($meta) ? $meta['bapi_last_update'][0] : null;
		$staticpagekey = !empty($meta) ? $meta['bapi_page_id'][0] : null;
		$pagekey = !empty($meta) ? $meta['bapikey'][0] : null;
		$meta_keywords = !empty($meta) ? $meta['bapi_meta_keywords'][0] : null;
		$meta_description = !empty($meta) ? $meta['bapi_meta_description'][0] : null;	
		
		// locate the SEO data stored in Bookt from the requested URL
		$seo = $bapisync->getSEOFromUrl(str_replace("?".$_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']));
		//print_r($seo);//exit();
		if (!empty($seo) && (empty($seo["entity"]) || empty($seo["pkid"])) && empty($staticpagekey)) {
			$seo = null; // ignore seo info if it doesn't point to a valid entity
		}			
			
		$do_page_update = false;
		$do_meta_update = false;
		$do_market_update = false;
		$changes = "";
		
		if(!empty($seo) && ($seo["entity"]=='property' || $seo["entity"]=='marketarea') && $maEnabled){
			$do_market_update = true;
		}
		
		if($page_exists_in_wp && !empty($staticpagekey)){
			// update the meta tags		
			if(empty($meta['bapi_last_update'])||((time()-$meta['bapi_last_update'][0])>3600)){			
				does_meta_exist("bapi_last_update", $meta) ? update_post_meta($post->ID, 'bapi_last_update', time()) : add_post_meta($post->ID, 'bapi_last_update', time(), true);
				if(!empty($seo)){
					if ($meta['bapi_meta_description'][0] != $seo["MetaDescrip"]) { update_post_meta($post->ID, 'bapi_meta_description', $seo["MetaDescrip"]); }
					if ($meta['bapi_meta_keywords'][0] != $seo["MetaKeywords"]) { update_post_meta($post->ID, 'bapi_meta_keywords', $seo["MetaKeywords"]); }
					//does_meta_exist("bapi_meta_description", $meta) ? update_post_meta($post->ID, 'bapi_meta_description', $seo["MetaDescrip"]) : add_post_meta($post->ID, 'bapi_meta_description', $seo["MetaDescrip"], true);
					//does_meta_exist("bapi_meta_keywords", $meta) ? update_post_meta($post->ID, 'bapi_meta_keywords', $seo["MetaKeywords"]) : add_post_meta($post->ID, 'bapi_meta_keywords', $seo["MetaKeywords"], true);
				}
			}
			return true;
		}
		
		//catch bad bapikey
		if ($page_exists_in_wp && !empty($pagekey)){
			$pktest = explode(":",$pagekey);
			//print_r($pktest); exit();
			if((strlen($pktest[0])==0)||(strlen($pktest[1])==0)){
				//To Delete Meta or Page, that is the question.
				wp_delete_post($post->ID,true);  //Going w/ deleting post for now - I think this will work because if page should exist it will ge recreated.
				//delete_post_meta($post->ID,'bapikey');
			}
			//Check for non-initialized market area page (-1) and set correct bapikey
			if(($pktest[1]==-1)&&$pktest[0]=='marketarea'){
				$seo = $bapisync->getSEOFromUrl(str_replace("?".$_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']));
				//print_r($post); exit();
				update_post_meta($post->ID, "bapikey", 'marketarea:'.$seo['pkid']);	
			}
		}
		
		// case 1: page exists in wp and is marked for syncing on wp but, it no longer exists in Bookt		
		if ($page_exists_in_wp && empty($seo) && !empty($pagekey)) {
			//echo $post->ID; exit();
			//print_r("case 1");
			// Action: Set current page to "unpublished"
			// $post->post_status = "unpublish";
			wp_delete_post($post->ID,true); //optional 2nd parameter can be added -> if true then page will be deleted immediately instead of going to trash.
		}
		// case 2: pages exists in wp and in Bookt
		else if ($page_exists_in_wp && !empty($seo)) {
			//Move from trashcan to publish if exists and no published
			if($post->post_status=='trash'){ $post->post_status='publish'; $do_page_update = true; } 
			//print_r("case 2");
			if(empty($meta['bapi_last_update'])||((time()-$meta['bapi_last_update'][0])>3600)){	$changes = $changes."|bapi_last_update"; $do_page_update = true; }
			// check for difference in meta description
			if ($meta['bapi_meta_description'][0] != $seo["MetaDescrip"]) { $changes = $changes."|meta_description"; $do_meta_update = true; }	
			// check for difference in meta keywords
			if ($meta['bapi_meta_keywords'][0] != $seo["MetaKeywords"]) { $changes = $changes."|meta_keywords"; $do_meta_update = true; }	
			// check for different in title
			if ($post->post_title != $seo["PageTitle"]) { $changes = $changes."|post_title"; $do_page_update = true; }
			// check for difference in post name
			if ($post->post_name != BAPISync::clean_post_name($seo["DetailURL"])) { $changes = $changes."|post_name"; $do_page_update = true; }
		}
		// case 3: page exists does not exist in wp and does not exist in Bookt
		else if (!$page_exists_in_wp && empty($seo)) {
			// Action: Do nothing and let wp generate a 404
			//print_r("case 3");
		}
		// case 4: page does not exist in wp but exists in Bookt
		else if (!$page_exists_in_wp && !empty($seo)) {
			//print_r("case 4".$do_market_update);exit();
			// Result-> Need to create the page
			$changes = "create new page";
			$tempPost = new stdClass();
			$post = new WP_Post($tempPost);
			$do_page_update = true;
			$do_meta_update = true;
		}
		//Check if developer is using debugmode and force entity sync
		if (isset($_GET['debugmode'])&&$_GET['debugmode']){
			$do_page_update = true;
			$debugmode = 1;
		}
		
		if ($do_page_update) {
			// do page update
			$post->comment_status = "close";		
			$template = $bapisync->getMustacheTemplate($seo["entity"]);		
			$post->post_content = $bapisync->getMustache($seo["entity"],$seo["pkid"],$template,$debugmode);
			//print_r($post); exit();
			$post->post_title = $seo["PageTitle"];
			$post->post_name = BAPISync::clean_post_name($seo["DetailURL"]);
			$post->post_parent = get_page_by_path(BAPISync::getRootPath($seo["entity"]))->ID;
			if($do_market_update){
				$post->post_parent = ensure_ma_landing_pages($seo["DetailURL"]);
			}
			$post->post_type = "page";
			remove_filter('content_save_pre', 'wp_filter_post_kses');
			//print_r($post);exit();
			if (empty($post->ID)) {
				$post->ID = wp_insert_post($post, $wp_error);
			} else {
				wp_update_post($post);
			}						
			add_filter('content_save_pre', 'wp_filter_post_kses');
		}
		if ($do_meta_update || $do_page_update) {
			// update the meta tags					
			does_meta_exist("bapi_last_update", $meta) ? update_post_meta($post->ID, 'bapi_last_update', time()) : add_post_meta($post->ID, 'bapi_last_update', time(), true);
			does_meta_exist("bapi_meta_description", $meta) ? update_post_meta($post->ID, 'bapi_meta_description', $seo["MetaDescrip"]) : add_post_meta($post->ID, 'bapi_meta_description', $seo["MetaDescrip"], true);
			does_meta_exist("bapi_meta_keywords", $meta) ? update_post_meta($post->ID, 'bapi_meta_keywords', $seo["MetaKeywords"]) : add_post_meta($post->ID, 'bapi_meta_keywords', $seo["MetaKeywords"], true);
			does_meta_exist("_wp_page_template", $meta) ? update_post_meta($post->ID, "_wp_page_template", BAPISync::getPageTemplate($seo["entity"])) : add_post_meta($post->ID, "_wp_page_template", BAPISync::getPageTemplate($seo["entity"]), true);
			does_meta_exist("bapikey", $meta) ? update_post_meta($post->ID, "bapikey", BAPISync::getPageKey($seo["entity"],$seo["pkid"])) : add_post_meta($post->ID, "bapikey", BAPISync::getPageKey($seo["entity"],$seo["pkid"]), true);			
		}		
	}
	
	function does_meta_exist($name, $meta) {
		if (empty($meta)) { return false; }
		if (empty($meta[$name])) { return false; }
		return true;
	}
	
	function bapi_sync_coredata() {
		$syncdebugmode = 0;
		$do_core_update = false;
		//Check if developer is using debugmode and force entity sync
		if (isset($_GET['syncdebugmode'])&&$_GET['syncdebugmode']){
			$do_core_update = true;
			$syncdebugmode = 1;
			echo '<!--synctest-->';
		}
		if(!(strpos($_SERVER['REQUEST_URI'],'wp-admin')===false)||!(strpos($_SERVER['REQUEST_URI'],'wp-login')===false)){
			return false;
		}
		
		// initialize the bapisync object		
		global $bapisync;
		$bapisync = new BAPISync();
		$bapisync->init();
		
		$bapi = getBAPIObj();
		if (!$bapi->isvalid()) { return; }
		
		// check if we need to refresh textdata
		$data = BAPISync::getTextDataRaw();
		$lastmod = BAPISync::getTextDataLastModRaw();
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600) || $do_core_update) {					
			$data = $bapi->gettextdata(true,$syncdebugmode);			
			if (!empty($data)) {
				$data = $data['result']; // just get the result part
				$data = json_encode($data); // convert back to text
				update_option('bapi_textdata',$data);
				update_option('bapi_textdata_lastmod',time());
			}					
		}	
		
		// check if we need to refresh solution data
		$data = BAPISync::getSolutionDataRaw();
		$lastmod = BAPISync::getSolutionDataLastModRaw();
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600) || $do_core_update) {					
			$data = $bapi->getcontext(true,$syncdebugmode);
			if (!empty($data)) {
				$tagline = $data['SolutionTagline'];
				$solName = $data['SolutionNameInformal'];
				$data = json_encode($data); // convert back to text
				update_option('bapi_solutiondata',$data);
				update_option('bapi_solutiondata_lastmod',time());
				update_option('blogdescription',$tagline);
				update_option('blogname',$solName);
			}			
		}	
		
		// check if we need to refresh seo data
		$data = BAPISync::getSEODataRaw();
		$lastmod = BAPISync::getSEODataLastModRaw();
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600) || $do_core_update) {					
			$data = $bapi->getseodata(true,$syncdebugmode);
			if (!empty($data)) {
				$data = $data['result']; // just get the result part
				$data = json_encode($data); // convert back to text
				update_option('bapi_keywords_array',$data);
				update_option('bapi_keywords_lastmod',time());
			}					
		}
	}

function get_doc_template($docname,$setting){
	global $bapi_all_options;
	$docmod = $bapi_all_options[$setting.'_lastmod']; //settings must be registered w/ this consistent format.
	$doctext = $bapi_all_options[$setting];
	if((time()-$docmod)>0){
		$url = getbapiurl().'/ws/?method=get&ids=0&entity=doctemplate&docname='.urlencode($docname).'&apikey='.getbapiapikey().'&language='.getbapilanguage();
		$d = file_get_contents($url);
		$darr = json_decode($d);
		$doctext = $darr->result[0]->DocText;
		
		/* Temporary Hack For Tag Substitution */
		$siteurl = parse_url($bapi_all_options['bapi_site_cdn_domain'],PHP_URL_HOST);
		$solution = $bapi_all_options['blogname'];
		$doctext = str_replace("#Solution.Solution#", $solution, $doctext);
		$doctext = str_replace("#Site.PrimaryURL#", $siteurl, $doctext);
		/* End Temporary Hack */
		
		update_option($setting,$doctext);
		update_option($setting.'_lastmod',time());
		bapi_wp_site_options();
	}
	return $bapi_all_options[$setting];
}

function ensure_ma_landing_pages($detailurl){
	$perm = explode('/',rtrim(ltrim($detailurl,'/'),'/'));
	$i = 0;
	$req_path = '';
	while($i < count($perm)-1){
		$orig_req = $req_path;
		$req_path .= $perm[$i].'/';
		//echo $req_path;
		$pid = get_page_by_path($req_path)->ID;
		//echo ' '.$pid;
		if(empty($pid)){
			//echo "no-page";
			$tempPost = new stdClass();
			$post = new WP_Post($tempPost);
			$post->comment_status = "close";
			$post->post_content = "";
			$post->post_title = $perm[$i];
			$post->post_name = $perm[$i];
			$post->post_parent = get_page_by_path($orig_req)->ID;
			$post->post_type = "page";
			//print_r($post);
			$pid = wp_insert_post($post, $wp_error);
			//echo $postid;
			// update the meta tags					
			add_post_meta($pid, 'bapi_last_update', 0, true);
			add_post_meta($pid, 'bapi_meta_description', '', true);
			add_post_meta($pid, 'bapi_meta_keywords', '', true);
			add_post_meta($pid, "bapikey", 'marketarea:-1', true);			
		}
		$i++;
		//echo "<br>";
	}
	return $pid;
}
?>
