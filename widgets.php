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
				//print_r($wrapper);
				$string = $m->render($t, $wrapper);
				echo $string;			
			}
			else {
				echo '<div id="poweredby"><a rel="nofollow" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
			}
		}
		else {
			echo '<div id="poweredby"><a rel="nofollow" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
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
				echo '<div id="poweredby"><a rel="nofollow" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
			}
		}
		else {
			echo '<div id="poweredby"><a rel="nofollow" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></div>';
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
        <script type="text/javascript">
			$(document).ready(function () {
				var imgurl = ''; //BAPI.site().slideshowimages[0].imgurl;
				var logourl = BAPI.site.logo;
				var tagline = BAPI.site.tagline;
				$('#bapi-hp-slideshow').parent().css('min-height','350px');
				$('#bapi-hp-slideshow').parent().css('background-image','url(\''+imgurl+'\') ');
				$('#bapi-hp-slideshow').parent().css('background-repeat','no-repeat');
				$('#bapi-hp-slideshow').parent().css('background-position','center');
				$('#bapi-hp-slideshow').parent().css('background-size','auto 100%');
				
				(function slidesLoop (i,t) {   
					setTimeout(function () {
						var imgurl = BAPI.site().slideshowimages[t].imgurl;
						$('#bapi-hp-slideshow').parent().css('background-image','url(\''+imgurl+'\') ');
						t++;
						if(t>=i){t=0};
				    	slidesLoop(i,t);
				   	}, 8000)
				})(BAPI.site.slideshowimages.length,1); 
			});
        </script>
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
		?>
        <div id="logo-replace"></div>
        <script type="text/javascript">
			$(document).ready(function () {
				var logourl = BAPI.site.logo;
				var tagline = BAPI.site.tagline;
				$('#logo-replace').parent().prepend('<img src="'+logourl+'" alt=""><h2>'+tagline+'</h2>');
			});
        </script>
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
		?>
        <style type="text/css">
			#bapi-logo{ margin-bottom:16px; }
		</style>
        <div id="bapi-logo"></div>
        <script type="text/javascript">
			$(document).ready(function () {
				var logourl = BAPI.site.logo;
				$('#bapi-logo').html('<img src="'+logourl+'" alt="">');
			});
        </script>
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
        <div id="bapi-search" class="bapi-search" data-searchurl="/rentalsearch" data-templatename="tmpl-search-homepage" data-log="0"></div>
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
        <div id="bapi-search" class="bapi-search" data-searchurl="/rentalsearch" data-templatename="tmpl-search-homepage" data-log="0"></div>
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

		echo $before_widget;
		if ( ! empty( $title ) )
			//echo $before_title . $title . $after_title;
		?>
		<div id="bapi-inquiryform" class="bapi-inquiryform" data-templatename="tmpl-leadrequestform-propertyinquiry" data-log="0"></div>        
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
			$title = __( 'Contact Us', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
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
		echo $before_widget;
		if(!empty($title))
			echo $before_title.$title.$after_title;
		?>
		<div id="featuredproperties" class="bapi-summary featuredproperties row-fluid" data-log="0" data-templatename="tmpl-featuredproperties-horiz"  data-entity="property" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector=".featuredproperties%3E.span6" data-rowfixcount="2"></div>
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
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Featured Properties', 'text_domain' );
		}
		if ( isset( $instance[ 'text' ] ) ) {
			$pagesize =  esc_textarea($instance['text']);
		}
		else {
			$pagesize = __( '4', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( '# of Properties:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $pagesize ); ?>" />
        
		</p>
		<?php 
	}

} // class BAPI_Featured_Properties





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
		echo $before_widget;
		if(!empty($title))
			echo $before_title.$title.$after_title;		
		?>		
		<div id="propertyfinders" class="bapi-summary propertyfinders row-fluid" data-log="0" data-templatename="tmpl-searches-horiz"  data-entity="searches" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector=".propertyfinders-results%20%3E%20.span4" data-rowfixcount="3"></div>
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
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Property Finders', 'text_domain' );
		}
		if ( isset( $instance[ 'text' ] ) ) {
			$pagesize =  esc_textarea($instance['text']);
		}
		else {
			$pagesize = __( '4', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( '# of Properties:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $pagesize ); ?>" />
        
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
		echo $before_widget;
		if(!empty($title))
			echo $before_title . "<span class='glyphicons tags'><i></i>" . $title . "</span>" . $after_title;
		?>
        <div id="specials-widget" class="bapi-summary specials-widget row-fluid" data-log="0" data-templatename="tmpl-specials-vert" data-entity="specials" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector=".specials-results%20%3E%20.span4" data-rowfixcount="3"></div>		
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
		return $instance;
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Special Offers', 'text_domain' );
		}
		if ( isset( $instance[ 'text' ] ) ) {
			$pagesize =  esc_textarea($instance['text']);
		}
		else {
			$pagesize = __( '4', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( '# of Properties:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $pagesize ); ?>" />
        
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
		echo $before_widget;
		if(!empty($title))
			echo $before_title.$title.$after_title;
		?>
        <div id="featuredproperties" class="bapi-summary" data-log="0" data-templatename="tmpl-featuredproperties-vert"  data-entity="property" data-searchoptions='{ "pagesize": <?= $pagesize ?>, "sort": "random" }' data-rowfixselector="" data-rowfixcount=""></div>
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
			$title = __( 'Similar Properties', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        
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
				var unit = '<?= $unit ?>';
				if (woid!='') {
					var unit = 'c';
					if (BAPI.defaultOptions.language=="en-US") { unit = 'f'; }
					BAPI.UI.createWeatherWidget('#weather-widget', ['<?= $woid ?>'], { "link": false, "woeid": true, "unit": unit });
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