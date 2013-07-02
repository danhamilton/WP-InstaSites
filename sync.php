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
		
		public static function getSolutionDataRaw() { return get_option('bapi_solutiondata'); }
		public static function getSolutionDataLastModRaw() { get_option('bapi_textdata_lastmod'); }
		public static function getSolutionData() { return json_decode(BAPISync::getSolutionDataRaw(), TRUE); }
		
		public static function getTextDataRaw() { return get_option('bapi_textdata'); }
		public static function getTextDataLastModRaw() { return get_option('bapi_textdata_lastmod'); }
		public static function getTextData() { return json_decode(BAPISync::getTextDataRaw(), TRUE); }
		
		public static function getSEODataRaw() { return get_option('bapi_keywords_array'); }
		public static function getSEODataLastModRaw() { return get_option('bapi_keywords_lastmod'); }
		public static function getSEOData() { return json_decode(BAPISync::getSEODataRaw(), TRUE); }
		
		public static function getTemplates() { 
			$url = "bapi/bapi.ui.mustache.tmpl";
			$url = get_relative( plugins_url($url, __FILE__) );
			$url = realpath('') . $url;
			return file_get_contents($url); 
		}
		
		public static function cleanurl($url) {
			if (empty($url)) { return ""; }
			$url = strtolower(trim($url));
			if (strpos($url, "/") != 0) { $url = "/" . $url; }
			if (substr($url, -1) != "/") { $url = $url . "/"; }
			return $url;
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
			return 'page-templates/full-width.php';
		}
		
		public static function getRootPath($entity) {
			if($entity=='property') { $t=BAPISync::getSolutionData(); return $t["Site"]["BasePropertyURL"]; }
			if($entity=='development') { $t=BAPISync::getSolutionData(); return $t["Site"]["BaseDevelopmentURL"]; }
			if($entity=='specials') { $t=BAPISync::getSolutionData(); return $t["Site"]["BaseSpecialURL"]; }
			if($entity=='poi') { $t=BAPISync::getSolutionData(); return $t["Site"]["BasePOIUrl"]; }
			if($entity=='searches') { $t=BAPISync::getSolutionData(); return $t["Site"]["BasePropertyFinderURL"]; }
			return '/rentals/';
		}
		
		public function getMustacheTemplate($entity) {
			$template_name = "";
			if ($entity == "property") { $template_name = "tmpl-properties-detail"; }
			else if ($entity == "development") { $template_name = "tmpl-developments-detail"; }
			else if ($entity == "specials") { $template_name = "tmpl-specials-detail"; }
			else if ($entity == "poi") { $template_name = "tmpl-attractions-detail"; }
			else if ($entity = "searches") { $template_name = "tmpl-searches-detail"; }
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
		
		public static function getMustache($entity, $pkid, $template) {
			if(!(strpos($_SERVER['PATH_INFO'],'wp-admin')===false)&&!(strpos($_SERVER['PATH_INFO'],'wp-login')===false)){
				return false;
			}
			$bapi = getBAPIObj();
			if (!$bapi->isvalid()) { return; }
			$pkid = array(intval($pkid));			
			$options = $entity == "property" ? array("seo" => 1, "descrip" => 1, "avail" => 1, "rates" => 1, "reviews" => 1) : null;
			$c = $bapi->get($entity,$pkid,$options);						
			$c["config"] = BAPISync::getSolutionData();
			$c["config"] = $c["config"]["ConfigObj"];
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
		if(!(strpos($_SERVER['PATH_INFO'],'wp-admin')===false)&&!(strpos($_SERVER['PATH_INFO'],'wp-login')===false)){
			return false;
		}
		//global $post;
		global $bapisync;		
		if (empty($bapisync)) { 
			// ERROR: What should we do?
		}
		$post = get_page_by_path($_SERVER['REQUEST_URI']);
		
		// locate the SEO data stored in Bookt from the requested URL
		$seo = $bapisync->getSEOFromUrl($_SERVER['REQUEST_URI']);
		if (!empty($seo) && (empty($seo["entity"]) || empty($seo["pkid"]))) {
			$seo = null; // ignore seo info if it doesn't point to a valid entity
		}		
		// parse out the meta attributes for the current post
		$page_exists_in_wp = !empty($post);				
		$meta = $page_exists_in_wp ? get_post_custom($post->ID) : null;
		$last_update = !empty($meta) ? $meta['bapi_last_update'][0] : null;
		$pagekey = !empty($meta) ? $meta['bapikey'][0] : null;
		$meta_keywords = !empty($meta) ? $meta['bapi_meta_keywords'][0] : null;
		$meta_description = !empty($meta) ? $meta['bapi_meta_description'][0] : null;			
			
		$do_page_update = false;
		$do_meta_update = false;
		$changes = "";
		// case 1: page exists in wp and is marked for syncing on wp but, it no longer exists in Bookt		
		if ($page_exists_in_wp && empty($seo) && !empty($pagekey)) {
			//print_r("case 1");
			// Action: Set current page to "unpublished"
			// $post->post_status = "unpublish";
		}
		// case 2: pages exists in wp and in Bookt
		else if ($page_exists_in_wp && !empty($seo)) {
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
			//print_r("case 4");
			// Result-> Need to create the page
			$changes = "create new page";
			$post = new WP_Post();
			$do_page_update = true;
			$do_meta_update = true;
		}

		if ($do_page_update) {
			// do page update
			$post->comment_status = "close";		
			$template = $bapisync->getMustacheTemplate($seo["entity"]);		
			$post->post_content = $bapisync->getMustache($seo["entity"],$seo["pkid"],$template);
			$post->post_title = $seo["PageTitle"];
			$post->post_name = BAPISync::clean_post_name($seo["DetailURL"]);
			$post->post_parent = get_page_by_path(BAPISync::getRootPath($seo["entity"]))->ID;						
			$post->post_type = "page";
			remove_filter('content_save_pre', 'wp_filter_post_kses');
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
		echo $_SERVER['PATH_INFO']; exit();
		if(!(strpos($_SERVER['PATH_INFO'],'wp-admin')===false)&&!(strpos($_SERVER['PATH_INFO'],'wp-login')===false)){
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
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600)) {					
			$data = $bapi->gettextdata(true);			
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
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600)) {					
			$data = $bapi->getcontext(true);
			if (!empty($data)) {
				$data = json_encode($data); // convert back to text
				update_option('bapi_solutiondata',$data);
				update_option('bapi_solutiondata_lastmod',time());
			}					
		}	
		
		// check if we need to refresh seo data
		$data = BAPISync::getSEODataRaw();
		$lastmod = BAPISync::getSEODataLastModRaw();
		if(empty($data) || empty($lastmod) || ((time()-$lastmod)>3600)) {					
			$data = $bapi->getseodata(true);
			if (!empty($data)) {
				$data = $data['result']; // just get the result part
				$data = json_encode($data); // convert back to text
				update_option('bapi_keywords_array',$data);
				update_option('bapi_keywords_lastmod',time());
			}					
		}
	}	
?>