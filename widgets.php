<?php

/**
 * Adds BAPI_Footer widget.
 */
class BAPI_Header extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_header', // Base ID
			'Insta Header', // Name
			array( 'description' => __( 'Displays the Header', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		$apikey = getbapiapikey();
		if (!empty($apikey)) {
			$fname = get_stylesheet_directory() . '/insta-default-content/insta-header.php';
			if (!file_exists($fname)) {
				$fname = plugin_dir_path( __FILE__ ) . 'insta-default-content/insta-header.php';				
			}
			if (file_exists($fname)) {
				$t = file_get_contents($fname);					
				$m = new Mustache_Engine();				
				$wrapper = getbapisolutiondata();				
				$string = $m->render($t, $wrapper);
				echo $string;			
			}
			else {
				echo '<div id="poweredby"><a rel="nofollow" target="_blank" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
			}
		}
		else {
			echo '<div id="poweredby"><a rel="nofollow" target="_blank" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
		}
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}	

} // class BAPI_Header

/**
 * Adds BAPI_Footer widget.
 */
class BAPI_Footer extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_footer', // Base ID
			'Insta Footer', // Name
			array( 'description' => __( 'Displays the Footer', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		$apikey = getbapiapikey();
		if (!empty($apikey)) {
			$fname = get_stylesheet_directory() . '/insta-default-content/insta-footer.php';
			if (!file_exists($fname)) {
				$fname = plugin_dir_path( __FILE__ ) . 'insta-default-content/insta-footer.php';				
			}
			if (file_exists($fname)) {
				$t = file_get_contents($fname);					
				$m = new Mustache_Engine();
				$wrapper = getbapisolutiondata();
				//print_r($wrapper);
				$string = $m->render($t, $wrapper);
				echo $string;			
			}
			else {
				echo '<div id="poweredby"><a rel="nofollow" target="_blank" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
			}
		}
		else {
			echo '<div id="poweredby"><a rel="nofollow" target="_blank" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
		}
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}	

} // class BAPI_Footer


/**
 * Adds BAPI_HP_Slideshow widget.
 */
class BAPI_HP_Slideshow extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_hp_slideshow', // Base ID
			'Insta Homepage Slideshow', // Name
			array( 'description' => __( 'Homepage Slideshow', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		?>
        <div id="bapi-hp-slideshow"></div>		        
        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Homepage Slideshow', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_HP_Slideshow




/**
 * Adds BAPI_HP_LogoWithTagline widget.
 */
class BAPI_HP_LogoWithTagline extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_hp_logowithtagline', // Base ID
			'Insta Homepage Logo With Tagline', // Name
			array( 'description' => __( 'Homepage Logo With Tagline', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		$wrapper = getbapisolutiondata();
		$logo = str_replace("http:", "https:", $wrapper["site"]["SolutionLogo"]);
		$tagline = $wrapper["site"]["SolutionTagline"];
		$url = ($_SERVER['SERVER_PORT']==443 ? get_option('bapi_site_cdn_domain') : "/");
		if (empty($url)) { $url = "/"; }		
		?>
		<a href="<?= $url ?>"><img src="<?= $logo ?>" alt="" /></a>
		<h2><?= $logo ?></h2>
        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Homepage Logo With Tagline', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_HP_LogoWithTagline




/**
 * Adds BAPI_HP_Logo widget.
 */
class BAPI_HP_Logo extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_hp_logo', // Base ID
			'Insta Homepage Logo', // Name
			array( 'description' => __( 'Homepage Logo', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		$wrapper = getbapisolutiondata();
		$logo = $wrapper["site"]["SolutionLogo"];
		$currdomain = $_SERVER['SERVER_NAME']; //echo $currdomain;
		$cdndomain = parse_url(get_option('bapi_site_cdn_domain')); //echo $cdndomain['host']; exit();
		//$url = ($_SERVER['SERVER_PORT']==443 ? get_option('bapi_site_cdn_domain') : "/");
		//if (empty($url)) { $url = "/"; }
		if(($currdomain==$cdndomain['host'])||is_admin()||is_super_admin()){ //Always link to subdomain if logged in as admin [Jacob]
			$url = '/';
			if($_SERVER['SERVER_PORT']==443){
				$url = 'http://'.$currdomain.'/';
			}
		}
		else{
			$url = get_option('bapi_site_cdn_domain');
		}
		?>
        <div class="bapi-logo"><a href="<?= $url ?>" ><img src="<?= $logo ?>" alt="" /></a></div>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Homepage Logo', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_HP_Logo



/**
 * Adds BAPI_HP_Search widget.
 */
class BAPI_HP_Search extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_hp_search', // Base ID
			'Insta Search - Home Page', // Name
			array( 'description' => __( 'Availability Search Widget for Home Page', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . "<span class='glyphicons search'><i></i>" . $title . "</span>" . $after_title;
		?>
        <div id="bapi-search" class="bapi-search" data-searchurl="/rentals/rentalsearch/" data-templatename="tmpl-search-homepage" data-log="0"></div>
        <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Homepage Search', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_HP_Search

/**
 * Adds BAPI_Search widget.
 */
class BAPI_Search extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi__search', // Base ID
			'Insta Search', // Name
			array( 'description' => __( 'Availability Search Widget', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . "<span class='glyphicons search'><i></i>" . $title . "</span>" . $after_title;
		?>
        <div id="bapi-search" class="bapi-search" data-searchurl="/rentals/rentalsearch" data-templatename="tmpl-search-homepage" data-log="0"></div>
        <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Revise Search', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Search

/**
 * Adds BAPI_Inquiry_Form widget.
 */
class BAPI_Inquiry_Form extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_inquiry_form', // Base ID
			'Insta Inquiry Form', // Name
			array( 'description' => __( 'Inquiry Form', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		/* Do we show the phone field ? */
		if(isset( $instance[ 'showPhoneField' ])){$bShowPhoneField =  $instance['showPhoneField'];}
		else{ $bShowPhoneField = true;}
		/* Its the Phone Field Required ? */
		if(isset( $instance[ 'phoneFieldRequired' ])){$bPhoneFieldRequired =  $instance['phoneFieldRequired'];}
		else{ $bPhoneFieldRequired = true;}
		
		/* Do we show the date fields ? */
		if(isset( $instance[ 'showDateFields' ])){$bShowDateFields =  $instance['showDateFields'];}
		else{ $bShowDateFields = true;}
		
		/* Do we show the number of guests fields ? */
		if(isset( $instance[ 'showNumberGuestsFields' ])){$bShowNumberGuestsFields =  $instance['showNumberGuestsFields'];}
		else{ $bShowNumberGuestsFields = true;}
		
		/* Do we show the how did you hear about us dropdown ? */
		if(isset( $instance[ 'showLeadSourceDropdown' ])){$bShowLeadSourceDropdown =  $instance['showLeadSourceDropdown'];}
		else{ $bShowLeadSourceDropdown = true;}
		/* Its the Lead Source Dropdown Required ? */
		if(isset( $instance[ 'leadSourceDropdownRequired' ])){$bLeadSourceDropdownRequired =  $instance['leadSourceDropdownRequired'];}
		else{ $bLeadSourceDropdownRequired = false;}
		
		/* Do we show the comments field ? */
		if(isset( $instance[ 'showCommentsField' ])){$bShowCommentsField =  $instance['showCommentsField'];}
		else{ $bShowCommentsField = true;}

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		?>
		<div id="bapi-inquiryform" class="bapi-inquiryform" data-templatename="tmpl-leadrequestform-propertyinquiry" data-log="0" data-showphonefield="<?= $bShowPhoneField ? 1 : 0; ?>" data-phonefieldrequired="<?= $bPhoneFieldRequired ? 1 : 0; ?>" data-showdatefields="<?= $bShowDateFields ? 1 : 0; ?>" data-shownumberguestsfields="<?= $bShowNumberGuestsFields ? 1 : 0; ?>" data-showleadsourcedropdown="<?= $bShowLeadSourceDropdown ? 1 : 0; ?>" data-leadsourcedropdownrequired="<?= $bLeadSourceDropdownRequired ? 1 : 0; ?>" data-showcommentsfield="<?= $bShowCommentsField ? 1 : 0; ?>" ></div>
        <?php
        
        $googleConversionkey = get_option( 'bapi_google_conversion_key');
	$googleConversionlabel = get_option( 'bapi_google_conversion_label');
	$googleConversionCode = '';
	if($googleConversionkey != '' && $googleConversionlabel != ''){
		$googleConversionCode = '<!-- Google Code Conversion -->
<script type="text/javascript">
function googleConversionTrack(){
	var image = new Image(1,1); 
	image.src = "//www.googleadservices.com/pagead/conversion/'.$googleConversionkey.'/?value=0&amp;label='.$googleConversionlabel.'&amp;guid=ON&amp;script=0";
}
</script>';
	}
	
        echo $googleConversionCode;
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		/* we sanitize the values, either 1 or nothing */
		$instance['showPhoneField'] =  strip_tags($new_instance['showPhoneField']);
		$instance['phoneFieldRequired'] =  strip_tags($new_instance['phoneFieldRequired']);
		$instance['showDateFields'] =  strip_tags($new_instance['showDateFields']);
		$instance['showNumberGuestsFields'] =  strip_tags($new_instance['showNumberGuestsFields']);
		$instance['showLeadSourceDropdown'] =  strip_tags($new_instance['showLeadSourceDropdown']);
		$instance['leadSourceDropdownRequired'] =  strip_tags($new_instance['leadSourceDropdownRequired']);
		$instance['showCommentsField'] =  strip_tags($new_instance['showCommentsField']);

		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Contact Us', 'text_domain' );
		}
		
		// Show phone field checkbox
		if ( isset( $instance[ 'showPhoneField' ] ) ) { $bShowPhoneField = esc_attr($instance[ 'showPhoneField' ]); }
		else{ $bShowPhoneField = true;}
		
		// phone field required checkbox
		if ( isset( $instance[ 'phoneFieldRequired' ] ) ) { $bPhoneFieldRequired = esc_attr($instance[ 'phoneFieldRequired' ]); }
		else{ $bPhoneFieldRequired = true;}
		
		// Show dates fields checkbox
		if ( isset( $instance[ 'showDateFields' ] ) ) { $bShowDateFields = esc_attr($instance[ 'showDateFields' ]); }
		else{ $bShowDateFields = true;}
		
		// Show number of guests fields checkbox
		if ( isset( $instance[ 'showNumberGuestsFields' ] ) ) { $bShowNumberGuestsFields = esc_attr($instance[ 'showNumberGuestsFields' ]); }
		else{ $bShowNumberGuestsFields = true;}
		
		// Show lead source dropdown checkbox
		if ( isset( $instance[ 'showLeadSourceDropdown' ] ) ) { $bShowLeadSourceDropdown = esc_attr($instance[ 'showLeadSourceDropdown' ]); }
		else{ $bShowLeadSourceDropdown = true;}
		
		// lead source dropdown required checkbox
		if ( isset( $instance[ 'leadSourceDropdownRequired' ] ) ) { $bLeadSourceDropdownRequired = esc_attr($instance[ 'leadSourceDropdownRequired' ]); }
		else{ $bLeadSourceDropdownRequired = false;}
		
		// Show comments field checkbox
		if ( isset( $instance[ 'showCommentsField' ] ) ) { $bShowCommentsField = esc_attr($instance[ 'showCommentsField' ]); }
		else{ $bShowCommentsField = true;}
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

<p>
<input id="<?php echo $this->get_field_id('showPhoneField'); ?>" name="<?php echo $this->get_field_name('showPhoneField'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bShowPhoneField ); ?>/>
<label for="<?php echo $this->get_field_id( 'showPhoneField' ); ?>">
  <?php _e( 'Display Phone Field?' ); ?>
</label>
</p>

<p <?php if(!$bShowPhoneField ){echo 'style="display:none;"';} ?>>&nbsp;&nbsp;&nbsp;&nbsp;<input id="<?php echo $this->get_field_id('phoneFieldRequired'); ?>" name="<?php echo $this->get_field_name('phoneFieldRequired'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bPhoneFieldRequired ); ?>/>
<label for="<?php echo $this->get_field_id( 'phoneFieldRequired' ); ?>">
  <?php _e( 'Phone Field Required?' ); ?>
</label>
</p>

<p>
<input id="<?php echo $this->get_field_id('showDateFields'); ?>" name="<?php echo $this->get_field_name('showDateFields'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bShowDateFields ); ?>/>
<label for="<?php echo $this->get_field_id( 'showDateFields' ); ?>">
  <?php _e( 'Display Dates Fields?' ); ?>
</label>
</p>

<p>
<input id="<?php echo $this->get_field_id('showNumberGuestsFields'); ?>" name="<?php echo $this->get_field_name('showNumberGuestsFields'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bShowNumberGuestsFields ); ?>/>
<label for="<?php echo $this->get_field_id( 'showNumberGuestsFields' ); ?>">
  <?php _e( 'Display Guests Fields?' ); ?>
</label>
</p>

<p>
<input id="<?php echo $this->get_field_id('showLeadSourceDropdown'); ?>" name="<?php echo $this->get_field_name('showLeadSourceDropdown'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bShowLeadSourceDropdown ); ?>/>
<label for="<?php echo $this->get_field_id( 'showLeadSourceDropdown' ); ?>">
  <?php _e( 'Display Lead Source Dropdown?' ); ?>
</label>
</p>

<p <?php if(!$bShowLeadSourceDropdown ){echo 'style="display:none;"';} ?>>&nbsp;&nbsp;&nbsp;&nbsp;
<input id="<?php echo $this->get_field_id('leadSourceDropdownRequired'); ?>" name="<?php echo $this->get_field_name('leadSourceDropdownRequired'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bLeadSourceDropdownRequired ); ?>/>
<label for="<?php echo $this->get_field_id( 'leadSourceDropdownRequired' ); ?>">
  <?php _e( 'Lead Source Required?' ); ?>
</label>
</p>

<p>
<input id="<?php echo $this->get_field_id('showCommentsField'); ?>" name="<?php echo $this->get_field_name('showCommentsField'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bShowCommentsField ); ?>/>
<label for="<?php echo $this->get_field_id( 'showCommentsField' ); ?>">
  <?php _e( 'Display Comments Field?' ); ?>
</label>
</p>

		<?php 
	}

} // class BAPI_Inquiry_Form


/**
 * Adds BAPI_Featured_Properties widget.
 */
class BAPI_Featured_Properties extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_featured_properties', // Base ID
			'Insta Featured Properties', // Name
			array( 'description' => __( 'Insta Featured Properties', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$pagesize = esc_textarea($instance['text']);
		$rowsize = intval($instance['rowsize']);
		if($rowsize<=0) { $rowsize=1; }
		
		echo $before_widget;
		if(!empty($title))
			echo $before_title.$title.$after_title;
		?>
		<div id="featuredproperties" class="bapi-summary featuredproperties row-fluid" data-log="0" data-templatename="tmpl-featuredproperties-quickview" data-ignoresession="1" data-entity="property" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector=".fp-featured" data-rowfixcount="<?= $rowsize ?>"></div>
        <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['rowsize'] = $new_instance['rowsize'];
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; }
		else { $title = __( 'Featured Properties', 'text_domain' ); }
		if ( isset( $instance[ 'text' ] ) ) { $pagesize =  esc_textarea($instance['text']); }
		else { $pagesize = __( '4', 'text_domain' ); }
		if ( isset( $instance[ 'rowsize' ] ) ) { $rowsize =  $instance['rowsize']; }
		else { $rowsize = '1'; }
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( '# of Properties:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $pagesize ); ?>" />
        <label for="<?php echo $this->get_field_id( 'rowsize' ); ?>"><?php _e( 'Row Size:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'rowsize' ); ?>" name="<?php echo $this->get_field_name( 'rowsize' ); ?>" type="text" value="<?php echo esc_attr( $rowsize ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Featured_Properties


/**
* Adds BAPI_Developments_Widget widget.
*/
class BAPI_Developments_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'bapi_developments_widget', // Base ID
			'Insta Developments', // Name
			array( 'description' => __( 'Insta Developments', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$pagesize = esc_textarea($instance['text']);
		$rowsize = intval($instance['rowsize']);
		if($rowsize<=0) { $rowsize=1; }
		echo $before_widget;
		if(!empty($title))
			echo $before_title . $title . $after_title;
		?>
        <div id="developments-widget" class="bapi-summary development-widget row-fluid" data-applyfixers="1" data-log="0" data-templatename="tmpl-developments-quickview" data-entity="development" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector=".development-holder" data-rowfixcount="<?= $rowsize ?>"></div>
        <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['rowsize'] = $new_instance['rowsize'];
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; }
		else { $title = __( 'Developments', 'text_domain' ); }
		if ( isset( $instance[ 'text' ] ) ) { $pagesize =  esc_textarea($instance['text']); }
		else { $pagesize = __( '4', 'text_domain' ); }
		if ( isset( $instance[ 'rowsize' ] ) ) { $rowsize =  $instance['rowsize']; }
		else { $rowsize = '4'; }		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( '# of Developments:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $pagesize ); ?>" />
        <label for="<?php echo $this->get_field_id( 'rowsize' ); ?>"><?php _e( 'Row Size:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'rowsize' ); ?>" name="<?php echo $this->get_field_name( 'rowsize' ); ?>" type="text" value="<?php echo esc_attr( $rowsize ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Developments_Widget


/**
 * Adds BAPI_Property_Finders widget.
 */
class BAPI_Property_Finders extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_property_finders', // Base ID
			'Insta Search Buckets', // Name
			array( 'description' => __( 'Insta Search Buckets', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$pagesize = esc_textarea($instance['text']);
		$rowsize = intval($instance['rowsize']);
		if($rowsize<=0) { $rowsize=1; }
		echo $before_widget;
		if(!empty($title))
			echo $before_title.$title.$after_title;		
		?>		
		<div id="propertyfinders" class="bapi-summary propertyfinders row-fluid" data-applyfixers="1" data-log="0" data-templatename="tmpl-searches-quickview"  data-entity="searches" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector=".pf-featured" data-rowfixcount="<?= $rowsize ?>"></div>
        <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['rowsize'] = $new_instance['rowsize'];
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; }
		else { $title = __( 'Property Finders', 'text_domain' ); }
		if ( isset( $instance[ 'text' ] ) ) { $pagesize =  esc_textarea($instance['text']); }
		else { $pagesize = __( '4', 'text_domain' ); }
		if ( isset( $instance[ 'rowsize' ] ) ) { $rowsize =  $instance['rowsize']; }
		else { $rowsize = '1'; }		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( '# of Properties:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $pagesize ); ?>" />
        <label for="<?php echo $this->get_field_id( 'rowsize' ); ?>"><?php _e( 'Row Size:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'rowsize' ); ?>" name="<?php echo $this->get_field_name( 'rowsize' ); ?>" type="text" value="<?php echo esc_attr( $rowsize ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Property_Finders




/**
 * Adds BAPI_Specials_Widget widget.
 */
class BAPI_Specials_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_specials_widget', // Base ID
			'Insta Specials', // Name
			array( 'description' => __( 'Insta Specials', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$pagesize = esc_textarea($instance['text']);
		$rowsize = intval($instance['rowsize']);
		if($rowsize<=0) { $rowsize=1; }
		echo $before_widget;
		if(!empty($title))
			echo $before_title . "<span class='glyphicons tags'><i></i>" . $title . "</span>" . $after_title;
		?>
        <div id="specials-widget" class="bapi-summary specials-widget row-fluid" data-log="0" data-templatename="tmpl-specials-quickview" data-entity="specials" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector=".special-holder" data-rowfixcount="<?= $rowsize ?>"></div>		
        <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['rowsize'] = $new_instance['rowsize'];
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; }
		else { $title = __( 'Special Offers', 'text_domain' ); }
		if ( isset( $instance[ 'text' ] ) ) { $pagesize =  esc_textarea($instance['text']); }
		else { $pagesize = __( '4', 'text_domain' ); }
		if ( isset( $instance[ 'rowsize' ] ) ) { $rowsize =  $instance['rowsize']; }
		else { $rowsize = '1'; }		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( '# of Properties:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $pagesize ); ?>" />
        <label for="<?php echo $this->get_field_id( 'rowsize' ); ?>"><?php _e( 'Row Size:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'rowsize' ); ?>" name="<?php echo $this->get_field_name( 'rowsize' ); ?>" type="text" value="<?php echo esc_attr( $rowsize ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Specials_Widget



/**
 * Adds BAPI_Similar_Properties widget.
 */
class BAPI_Similar_Properties extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_similar_properties', // Base ID
			'Insta Similar Properties', // Name
			array( 'description' => __( 'Insta Similar Properties', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$pagesize = esc_textarea($instance['text']);
		if(empty($pagesize)) { $pagesize = 3; }
		$rowsize = intval($instance['rowsize']);
		if($rowsize<=0) { $rowsize=1; }
		
		echo $before_widget;
		if(!empty($title))
			echo $before_title.$title.$after_title;
		?>
        <div id="featuredproperties" class="bapi-summary" data-log="0" data-templatename="tmpl-featuredproperties-quickview" data-entity="property" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random", "similarto": true }' data-rowfixselector=".fp-featured" data-rowfixcount="<?= $rowsize ?>"></div>
		<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['rowsize'] = $new_instance['rowsize'];
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; }
		else { $title = __( 'Similar Properties', 'text_domain' ); }
		if ( isset( $instance[ 'rowsize' ] ) ) { $rowsize =  $instance['rowsize']; }
		else { $rowsize = '1'; }		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'rowsize' ); ?>"><?php _e( 'Row Size:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'rowsize' ); ?>" name="<?php echo $this->get_field_name( 'rowsize' ); ?>" type="text" value="<?php echo esc_attr( $rowsize ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Similar_Properties

/**
 * Adds BAPI_Weather widget.
 */
class BAPI_Weather_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_weather_widget', // Base ID
			'Insta Weather', // Name
			array( 'description' => __( 'Insta Weather', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$woid = esc_textarea($instance['text']);
		$unit = $instance['unit'];
		if(empty($woid)) return;
		if(empty($unit)){
			$unit = 'f';
		}
		echo $before_widget;
		if(!empty($title))
			echo $before_title . "<span class='glyphicons brightness_increase'><i></i>" . $title . "</span>" . $after_title;
		?>
        <div id="weather-widget"></div>
		<script>
			$(document).ready(function () {
				// weather widget uses code found here: http://www.zazar.net/developers/jquery/zweatherfeed/
				// lookup woid here: http://woeid.rosselliot.co.nz/
				var woid = '<?= $woid ?>';
				var sTemperatureUnit = '<?= $unit ?>';
				if (woid!='') {
					if (sTemperatureUnit == null || sTemperatureUnit == '' && BAPI.defaultOptions.language=="en-US") { sTemperatureUnit = 'f'; }
					BAPI.UI.createWeatherWidget('#weather-widget', ['<?= $woid ?>'], { "link": false, "woeid": true, "unit": sTemperatureUnit });
				}
			});
        </script>
        <?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['unit'] = $new_instance['unit'];
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Weather', 'text_domain' );
		}
		if ( isset( $instance[ 'text' ] ) ) {
			$woid =  esc_textarea($instance['text']);
		}
		else {
			$woid = __( '2450022', 'text_domain' );
		}
		if ( isset( $instance[ 'unit' ] ) ) {
			$unit =  $instance['unit'];
		}
		else {
			$unit = 'f';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'WOID:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $woid ); ?>" />
        <br/>
        <small><a href="//woeid.rosselliot.co.nz/lookup/" target="_blank">Lookup WOID</a></small>
		<div class="clear"></div>
		<label for="<?php echo $this->get_field_id( 'unit' ); ?>">Unit</label>
		<select id="<?php echo $this->get_field_id( 'unit' ); ?>" name="<?php echo $this->get_field_name( 'unit' ); ?>">
			<option value="f" <?php if($unit=='f') echo 'selected'; ?>>Farenheit</option>
			<option value="c" <?php if($unit=='c') echo 'selected'; ?>>Celcius</option>
		</select>
		</p>
		<?php 
	}

} // class BAPI_Weather_Widget

class BAPI_DetailOverview_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_detailoverview', // Base ID
			'Insta Detail Overview', // Name
			array( 'description' => __( 'Displays the overview section of a detail screen', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$woid = esc_textarea($instance['text']);
		echo $before_widget;
		if(!empty($title))
			echo $before_title.$title.$after_title;
		?>
        <div class="detail-overview-target"></div>
		<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}	

} // class BAPI_DetailOverview_Widget


?>
