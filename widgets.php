<?php

/**
 * Adds BAPI_Footer widget.
 */
class BAPI_Footer extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_footer', // Base ID
			'Bookt Footer', // Name
			array( 'description' => __( 'Displays the Footer', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		$apikey = getbapiapikey();
		if (!empty($apikey)) {
			$t = file_get_contents(plugins_url('/default-content/footer.php', __FILE__));
			$m = new Mustache_Engine();
			$wrapper = getbapisolutiondata();
			//print_r($wrapper);
			$string = $m->render($t, $wrapper);
			echo $string;			
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
			'Bookt Homepage Slideshow', // Name
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
			'Bookt Homepage Logo With Tagline', // Name
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
			'Bookt Homepage Logo', // Name
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
			'Bookt Homepage Search', // Name
			array( 'description' => __( 'Homepage Search', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		//$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		//if ( ! empty( $title ) )
			//echo $before_title . $title . $after_title;
		//?>
        <div id="bapi-hp-search"></div>
        <script type="text/javascript">
			$(document).ready(function () {
				BAPI.UI.createSearchWidget('#bapi-hp-search', { "searchurl": "/rentalsearch", "template": BAPI.templates.get('tmpl-search-homepage') });
			});
        </script>
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
 * Adds BAPI_Prop_Inquiry widget.
 */
class BAPI_Prop_Inquiry extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_prop_inquiry', // Base ID
			'Bookt Property Inquiry Form', // Name
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
        <div id="inquiryform"></div>
        <script>
		$(document).ready(function () {
			var pkid = <?= get_post_meta(get_the_ID(),'property_id',true) ?>;
			$(document).ready(function () {
				BAPI.UI.createInquiryForm('#inquiryform', { "pikd": pkid, "hasdatesoninquiryform": true });
			});
		});
		</script>
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
			$title = __( 'Property Inquiry', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Prop_Inquiry



/**
 * Adds BAPI_Inquiry_Form widget.
 */
class BAPI_Inquiry_Form extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_inquiry_form', // Base ID
			'Bookt Inquiry Form', // Name
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
        <div id="inquiryform"></div>
        <script>
		$(document).ready(function () {
			BAPI.UI.createInquiryForm('#inquiryform', { "hasdatesoninquiryform": false });
		});
		</script>
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
 * Adds BAPI_Prop_Quote widget.
 */
class BAPI_Prop_Quote extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_prop_quote', // Base ID
			'Bookt Property Rate Quote', // Name
			array( 'description' => __( 'Rate Quote', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		/*if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;*/
		?>
        <style type="text/css">
			#prop-quote h2{font-size:1.5em;}
		</style>
        <div id="prop-quote">
        	<h2></h2>
            <h5></h5>
        </div>
        <script type="text/javascript">
			$(document).ready(function () {
				//Not sure when  BAPI object is present, so for now the step of loading data in to this widget is handled by the property detail page code.
			});
        </script>
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
			$title = __( 'Property Quote', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class BAPI_Prop_Quote





/**
 * Adds BAPI_Featured_Properties widget.
 */
class BAPI_Featured_Properties extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'bapi_featured_properties', // Base ID
			'Bookt Featured Properties', // Name
			array( 'description' => __( 'Bookt Featured Properties', 'text_domain' ), ) // Args
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
        <div class="featuredproperties"></div>
		<script>
			$(document).ready(function () {
				BAPI.UI.createSummaryWidget('.featuredproperties',
				{
		   			searchoptions: { "pagesize": <?= $pagesize ?>, "sort": "random" },
					"entity": BAPI.entities.property,
					"template": BAPI.templates.get('tmpl-featuredproperties-horiz')
				});
			});
        </script>
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
			'Bookt Predefined Searches', // Name
			array( 'description' => __( 'Bookt Property Finders', 'text_domain' ), ) // Args
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
        <div class="propertyfinders"></div>
		<script>
			$(document).ready(function () {
				BAPI.UI.createSummaryWidget('.propertyfinders',
				{
		   			searchoptions: { "pagesize": <?= $pagesize ?>, "sort": "random" },
					"entity": BAPI.entities.searches,
					"template": BAPI.templates.get('tmpl-searches-horiz')
				});
			});
        </script>
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
			'Bookt Specials', // Name
			array( 'description' => __( 'Bookt Specials', 'text_domain' ), ) // Args
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
        <div class="specials-widget"></div>
		<script>
			$(document).ready(function () {
				BAPI.UI.createSummaryWidget('.specials-widget',
				{
		   			searchoptions: { "pagesize": <?= $pagesize ?>, "sort": "random" },
					"entity": BAPI.entities.specials,
					"template": BAPI.templates.get('tmpl-specials-vert')
				});
			});
        </script>
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
			'Bookt Similar Properties', // Name
			array( 'description' => __( 'Bookt Similar Properties', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title',$instance['title']);
		$pagesize = esc_textarea($instance['text']);
		echo $before_widget;
		if(!empty($title))
			//echo $before_title.$title.$after_title;
		?>
        <div id="similarprops"></div>
		<script>
			var pkid = <?= get_post_meta(get_the_ID(),'property_id',true) ?>;
			$(document).ready(function () {
				BAPI.UI.createSimilarPropertiesWidget('#similarprops', pkid, null);  
			});
        </script>
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
			$title = __( 'Featured Properties', 'text_domain' );
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
			'Bookt Weather', // Name
			array( 'description' => __( 'Bookt Weather', 'text_domain' ), ) // Args
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
        <div id="weather-widget"></div>
		<script>
			$(document).ready(function () {
				// weather widget uses code found here: http://www.zazar.net/developers/jquery/zweatherfeed/
				// lookup woid here: http://woeid.rosselliot.co.nz/
				var woid = '<?= $woid ?>';
				if (woid!='') {
					BAPI.UI.createWeatherWidget('#weather-widget', ['<?= $woid ?>'], { link: false, woeid: true });
				}
			});
        </script>
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
			$title = __( 'Weather', 'text_domain' );
		}
		if ( isset( $instance[ 'text' ] ) ) {
			$woid =  esc_textarea($instance['text']);
		}
		else {
			$woid = __( '2450022', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'WOID:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $woid ); ?>" />
        
		</p>
		<?php 
	}

} // class BAPI_Weather_Widget

?>