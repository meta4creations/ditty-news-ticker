<?php
/*
Plugin Name: Ditty News Ticker
Plugin URI: http://dittynewsticker.com/
Description: Ditty News Ticker is a multi-functional data display plugin
Text Domain: ditty-news-ticker
Domain Path: languages
Version: 2.1.10
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
Contributors: metaphorcreations
License: GPL2
*/

/*
Copyright 2012 Metaphor Creations  (email : joe@metaphorcreations.com)

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



define ( 'MTPHR_DNT_VERSION', '2.1.10' );
define ( 'MTPHR_DNT_DIR', trailingslashit(plugin_dir_path(__FILE__)) );


/* --------------------------------------------------------- */
/* !Include files - 1.5.0 */
/* --------------------------------------------------------- */

// Load the general functions
require_once( MTPHR_DNT_DIR.'includes/helpers.php' );
require_once( MTPHR_DNT_DIR.'includes/post-types.php' );
require_once( MTPHR_DNT_DIR.'includes/settings.php' );
require_once( MTPHR_DNT_DIR.'includes/widget.php' );
require_once( MTPHR_DNT_DIR.'includes/composer.php' );

if( is_admin() ) {

	// Load admin specific code
	require_once( MTPHR_DNT_DIR.'includes/admin/ajax.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/meta-boxes.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/edit-columns.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/fields/helpers.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/fields/fields.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/filters.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/functions.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/upgrades.php' );
	require_once( MTPHR_DNT_DIR.'includes/admin/scripts.php' );
} else {
	
	// Load front-end specific code
	require_once( MTPHR_DNT_DIR.'includes/filters.php' );
	require_once( MTPHR_DNT_DIR.'includes/functions.php' );
	require_once( MTPHR_DNT_DIR.'includes/scripts.php' );
	require_once( MTPHR_DNT_DIR.'includes/shortcodes.php' );
	require_once( MTPHR_DNT_DIR.'classes/class-mtphr-dnt.php' );
	require_once( MTPHR_DNT_DIR.'classes/class-mtphr-dnt-tick.php' );
	require_once( MTPHR_DNT_DIR.'classes/class-mtphr-dnt-image.php' );
	require_once( MTPHR_DNT_DIR.'classes/helpers/class-mtphr-dnt-string-replacement.php' );
	require_once( MTPHR_DNT_DIR.'includes/templates.php' );
}



/* --------------------------------------------------------- */
/* !Register the post type & flush the rewrite rules - 1.4.6 */
/* --------------------------------------------------------- */

function mtphr_dnt_activation() {
	mtphr_dnt_posttype();
	mtphr_dnt_custom_caps();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mtphr_dnt_activation' );



/* --------------------------------------------------------- */
/* !Flush the rewrite rules - 1.4.6 */
/* --------------------------------------------------------- */

function mtphr_dnt_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mtphr_dnt_deactivation' );



/* --------------------------------------------------------- */
/* !Setup localization - 1.1.5 */
/* --------------------------------------------------------- */

function mtphr_dnt_localization() {
	load_plugin_textdomain( 'ditty-news-ticker', false, 'ditty-news-ticker/languages/' );
}
add_action( 'plugins_loaded', 'mtphr_dnt_localization' );



/* --------------------------------------------------------- */
/* !Set a custom Unyson extension location - 2.0.6 */
/* --------------------------------------------------------- */

function mtphr_dnt_unyson_extension( $locations ) {
  $locations[ MTPHR_DNT_DIR.'unyson' ] = plugins_url('ditty-news-ticker/unyson');
  return $locations;
}
add_filter( 'fw_extensions_locations', 'mtphr_dnt_unyson_extension' );



/* --------------------------------------------------------- */
/* !Add capabilities - 2.1.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_custom_caps() {
	
	$caps_added = get_option( 'mtphr_dnt_caps', false );
	if( !$caps_added ) {
				
	  $admins = get_role( 'administrator' );
	  $editors = get_role( 'editor' );
	  $authors = get_role( 'author' );
	  $contributors = get_role( 'contributor' );
	  $subscribers = get_role( 'subscriber' );
	
		if( $admins ) {
		  $admins->add_cap( 'edit_ditty_news_tickers' );
		  $admins->add_cap( 'edit_others_ditty_news_tickers' );
		  $admins->add_cap( 'publish_ditty_news_tickers' );
		  $admins->add_cap( 'read_private_ditty_news_tickers' );
		  $admins->add_cap( 'read_ditty_news_tickers' );
		  $admins->add_cap( 'delete_ditty_news_tickers' );
		  $admins->add_cap( 'delete_private_ditty_news_tickers' );
		  $admins->add_cap( 'delete_published_ditty_news_tickers' );
		  $admins->add_cap( 'delete_others_ditty_news_tickers' );
		  $admins->add_cap( 'edit_private_ditty_news_tickers' );
		  $admins->add_cap( 'edit_published_ditty_news_tickers' );
		  $admins->add_cap( 'edit_published_ditty_news_tickers' ); 
		  $admins->add_cap( 'modify_ditty_news_ticker_settings' );
	  }
		
		if( $editors ) {
		  $editors->add_cap( 'edit_ditty_news_tickers' ); 
		  $editors->add_cap( 'edit_others_ditty_news_tickers' ); 
		  $editors->add_cap( 'publish_ditty_news_tickers' ); 
		  $editors->add_cap( 'read_private_ditty_news_tickers' ); 
		  $editors->add_cap( 'read_ditty_news_tickers' ); 
		  $editors->add_cap( 'delete_ditty_news_tickers' ); 
		  $editors->add_cap( 'delete_private_ditty_news_tickers' ); 
		  $editors->add_cap( 'delete_published_ditty_news_tickers' ); 
		  $editors->add_cap( 'delete_others_ditty_news_tickers' ); 
		  $editors->add_cap( 'edit_private_ditty_news_tickers' ); 
		  $editors->add_cap( 'edit_published_ditty_news_tickers' ); 
	  }
	  
	  if( $authors ) {
		  $authors->add_cap( 'edit_ditty_news_tickers' ); 
		  $authors->add_cap( 'publish_ditty_news_tickers' ); 
		  $authors->add_cap( 'read_ditty_news_tickers' ); 
		  $authors->add_cap( 'delete_ditty_news_tickers' ); 
		  $authors->add_cap( 'delete_published_ditty_news_tickers' ); 
		  $authors->add_cap( 'edit_published_ditty_news_tickers' ); 
	  }
	  
	  if( $contributors ) {
		  $contributors->add_cap( 'edit_ditty_news_tickers' ); 
		  $contributors->add_cap( 'read_ditty_news_tickers' ); 
		  $contributors->add_cap( 'delete_ditty_news_tickers' ); 
	  }
	  
	  if( $subscribers ) {
	  	$subscribers->add_cap( 'read_ditty_news_tickers' ); 
	  }
 
	  update_option( 'mtphr_dnt_caps', 'added' );
  }
  
  if( $caps_added != '2_1_1' ) {
	  
	  $admins = get_role( 'administrator' );
	  if( $admins ) {
		  $admins->add_cap( 'modify_ditty_news_ticker_settings' );
	  }
	  
	  update_option( 'mtphr_dnt_caps', '2_1_1' );
	}
}
add_action( 'init', 'mtphr_dnt_custom_caps');

