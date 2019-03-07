<?php
/*
Plugin Name: Ditty News Ticker
Plugin URI: http://dittynewsticker.com/
Description: Ditty News Ticker is a multi-functional data display plugin
Text Domain: ditty-news-ticker
Domain Path: languages
Version: 2.2
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

define( 'MTPHR_DNT_VERSION', '2.2' );
define( 'MTPHR_DNT_DIR', trailingslashit(plugin_dir_path( __FILE__ )) );
define( 'MTPHR_DNT_FILE', trailingslashit( __FILE__ ) );
define( 'MTPHR_DNT_STORE_URL', 'https://www.metaphorcreations.com' );
define( 'MTPHR_DNT_BUILD', 'legacy' );

/* --------------------------------------------------------- */
/* !Include files - 2.2 */
/* --------------------------------------------------------- */

// Load the general functions
require_once( MTPHR_DNT_DIR.'eddsl/eddsl.php' );
//require_once( MTPHR_DNT_DIR.'vendor/autoload.php' );
require_once( MTPHR_DNT_DIR.'inc/hooks.php' );

if( MTPHR_DNT_BUILD == 'legacy' ) {
	require_once( MTPHR_DNT_DIR.'legacy/init.php' );
}