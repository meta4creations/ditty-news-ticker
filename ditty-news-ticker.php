<?php
/*
Plugin Name: Ditty News Ticker
Plugin URI: http://dittynewsticker.com/
Description: Ditty News Ticker is a multi-functional data display plugin
Version: 1.1.4
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
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

/*
Fugue Icons

Copyright (C) 2011 Yusuke Kamiyamane. All rights reserved.
The icons are licensed under a Creative Commons Attribution
3.0 license. <http://creativecommons.org/licenses/by/3.0/>

<http://p.yusukekamiyamane.com/>
*/




/**
 * Define constants
 *
 * @since 1.0.0
 */ 
if ( WP_DEBUG ) {
	define ( 'MTPHR_DNT_VERSION', '1.1.4-'.time() );
} else {
	define ( 'MTPHR_DNT_VERSION', '1.1.4' );
}
define ( 'MTPHR_DNT_DIR', plugin_dir_path(__FILE__) );
define ( 'MTPHR_DNT_URL', plugins_url().'/ditty-news-ticker' );




/**
 * Include files
 *
 * @since 1.0.0
 */ 

if( is_admin() ) {

	// Load admin code
	require_once( MTPHR_DNT_DIR.'includes/metaboxer/metaboxer.php' );
	require_once( MTPHR_DNT_DIR.'includes/metaboxer/metaboxer-class.php' );
	require_once( MTPHR_DNT_DIR.'includes/meta-boxes.php' );
	require_once( MTPHR_DNT_DIR.'includes/settings.php' );
	//require_once( MTPHR_DNT_DIR.'includes/help.php' );
	require_once( MTPHR_DNT_DIR.'includes/edit-columns.php' );
}

// Load the general functions
require_once( MTPHR_DNT_DIR.'includes/scripts.php' );
require_once( MTPHR_DNT_DIR.'includes/post-types.php' );
require_once( MTPHR_DNT_DIR.'includes/functions.php' );
require_once( MTPHR_DNT_DIR.'includes/shortcodes.php' );
require_once( MTPHR_DNT_DIR.'includes/widget.php' );



