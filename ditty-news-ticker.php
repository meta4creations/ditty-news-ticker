<?php
/*
Plugin Name: Ditty News Ticker
Plugin URI: http://dittynewsticker.com/
Description: Ditty News Ticker is a multi-functional data display plugin
Text Domain: ditty-news-ticker
Domain Path: languages
Version: 2.1.16
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
Contributors: metaphorcreations
License: GPL2
*/

/*
Copyright 2018 Metaphor Creations  (email : joe@metaphorcreations.com)

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



define( 'MTPHR_DNT_VERSION', '2.1.16' );
define( 'MTPHR_DNT_DIR', trailingslashit(plugin_dir_path( __FILE__ )) );
define( 'MTPHR_DNT_FILE', trailingslashit( __FILE__ ) );


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

require_once MTPHR_DNT_DIR.'classes/class-mtphr-dnt-roles.php';
require_once MTPHR_DNT_DIR.'includes/install.php';



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
