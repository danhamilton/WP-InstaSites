<?php
/*
Plugin Name: Bookt API Wordpress Plugin
Plugin URI: http://www.bookt.com
Description: This plugin is intended for use by Bookt and Instamanager customers to display property and booking tools on their WP-hosted sites on any platform.
Version: 0.1
Author: Bookt LLC
Author URI: http://bookt.com
License: GPL2
*/

/*  Copyright 2012  Bookt LLC  (email : support@bookt.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
include_once(dirname( __FILE__ ).'/functions.php');
include_once(dirname( __FILE__ ).'/admin.php');
include_once(dirname( __FILE__ ).'/widgets.php');
include_once(dirname( __FILE__ ).'/sync.php');
include_once(dirname( __FILE__ ).'/google-xml-sitemap.php');
include_once(dirname( __FILE__ ).'/cdn-linker/wp-cdn-linker.php');
include_once(dirname( __FILE__ ).'/create-site.php');
include_once(dirname( __FILE__ ).'/shortcodes.php');
require_once('bapi-php/bapi.php');
require_once('init.php');

add_filter('home_url','home_url_cdn',1,2);
add_filter('wp_head','add_server_name_meta',1);
add_filter('redirect_canonical','bapi_redirect_fix',10,2);
add_filter('language_attributes','bapi_language_attributes',10);	// ensure output of proper language
add_filter('upload_mimes', 'custom_upload_mimes');
add_action('template_redirect', 'do_ossdl_off_ob_start');
add_action('wp_head','getconfig');
add_action('wp_head','bapi_getmeta',1);
add_action('wp_head','display_global_header',2);
add_action('init','bapi_create_site',1);  //Hook to add new sites
add_action('init','bapi_wp_site_options',1);  //Preload Site Data to help reduce DB usage
add_action('init','bapi_sync_coredata',2); 	// syncing BAPI core data
add_action('init','bapi_sync_entity',3);	// syncing BAPI entities (such as properties, developments, etc...)
add_action('init','urlHandler_bapitextdata',4);	// handler for /bapi.textdata.js
add_action('init','urlHandler_bapitemplates',4);	// handler for /bapi.templates.js
add_action('init','urlHandler_bapidefaultpages',4);	// handler for /bapi.init
add_action('init','urlHandler_sitelist',4);	// handler for /sitelist (possible warmup list)
add_action('init','disable_kses_content',20);
add_action('template_redirect', 'google_sitemap'); // sitemap handler

// create custom plugin settings menu
add_action('admin_menu', 'bapi_create_menu');
add_action('update_option_update_action', 'bapi_option_update', 10, 2);
add_action('update_option_property_category_name', 'bapi_option_category', 10, 2);

add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Header" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Footer" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_HP_Slideshow" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_HP_Logo" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_HP_LogoWithTagline" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_HP_Search" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Search" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Featured_Properties" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Inquiry_Form" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Similar_Properties" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Property_Finders" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Specials_Widget" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_Weather_Widget" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "BAPI_DetailOverview_Widget" );' ) );

require_once('mustache.php-2.1.0/src/Mustache/Autoloader.php');
Mustache_Autoloader::register();
?>