<?php
/*
Plugin Name: Ditty News Ticker
Plugin URI: http://dittynewsticker.com/
Description: Ditty News Ticker is a multi-functional data display plugin
Text Domain: ditty-news-ticker
Domain Path: languages
Version: 2.2.8
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
Contributors: metaphorcreations
License: GPL2
*/


// Exit if accessed directly.
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Ditty_News_Ticker' ) ) :


/**
 * Main Ditty_News_Ticker Class.
 * @since 3.0
 */
final class Ditty_News_Ticker {	
	
	/**
	 * @var Ditty_News_Ticker The one true Ditty_News_Ticker
	 * @since 3.0
	 */
	private static $instance;
	
	
	/**	
	 * Main Ditty_News_Ticker Instance.
	 * @since 1.0
	 */
	public static function instance() {
		
		if( ! isset( self::$instance ) && ! ( self::$instance instanceof Ditty_News_Ticker ) ) {
			
			self::$instance = new Ditty_News_Ticker;
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();
		}
		
		do_action( 'dnt_init' );

		return self::$instance;
	}
	
	
	/**
	 * Setup plugin constants.
	 * @since 3.0
	 */
	private function setup_constants() {

		// Plugin version
		if ( ! defined( 'MTPHR_DNT_VERSION' ) ) {
			define( 'MTPHR_DNT_VERSION', '2.2.8' );
		}

		// Plugin Folder Path
		if ( ! defined( 'MTPHR_DNT_DIR') ) {
			define( 'MTPHR_DNT_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'MTPHR_DNT_URL') ) {
			define( 'MTPHR_DNT_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}
		
		// Plugin Root File
		if ( ! defined( 'MTPHR_DNT_FILE') ) {
			define( 'MTPHR_DNT_FILE', __FILE__ );
		}
		
		// Store URL
		if ( ! defined( 'MTPHR_DNT_STORE_URL') ) {
			define( 'MTPHR_DNT_STORE_URL', 'https://www.metaphorcreations.com' );
		}
	}
	
	
	/**
	 * Include required files
	 * @since 3.0
	 */
	private function includes() {
		
		// Load the general functions
		require_once MTPHR_DNT_DIR . 'eddsl/eddsl.php';

		require_once MTPHR_DNT_DIR . 'inc/composer.php';
		require_once MTPHR_DNT_DIR . 'inc/helpers.php';
		require_once MTPHR_DNT_DIR . 'inc/hooks.php';
		require_once MTPHR_DNT_DIR . 'inc/post-types.php';
		require_once MTPHR_DNT_DIR . 'inc/settings.php';
		require_once MTPHR_DNT_DIR . 'inc/static.php';
		require_once MTPHR_DNT_DIR . 'inc/widget.php';
		
		if( is_admin() ) {
		
			// Load admin specific code
			require_once MTPHR_DNT_DIR . 'inc/admin/ajax.php';
			require_once MTPHR_DNT_DIR . 'inc/admin/meta-boxes.php';
			require_once MTPHR_DNT_DIR . 'inc/admin/edit-columns.php';
			require_once MTPHR_DNT_DIR . 'inc/admin/fields/helpers.php';
			require_once MTPHR_DNT_DIR . 'inc/admin/fields/fields.php';
			require_once MTPHR_DNT_DIR . 'inc/admin/filters.php';
			require_once MTPHR_DNT_DIR . 'inc/admin/functions.php';
			require_once MTPHR_DNT_DIR . 'inc/admin/upgrades.php';
			
		} else {
			
			// Load front-end specific code
			require_once MTPHR_DNT_DIR . 'inc/filters.php';
			require_once MTPHR_DNT_DIR . 'inc/functions.php';
			require_once MTPHR_DNT_DIR . 'inc/shortcodes.php';
			require_once MTPHR_DNT_DIR . 'inc/classes/class-mtphr-dnt.php';
			require_once MTPHR_DNT_DIR . 'inc/classes/class-mtphr-dnt-tick.php';
			require_once MTPHR_DNT_DIR . 'inc/classes/class-mtphr-dnt-image.php';
			require_once MTPHR_DNT_DIR . 'inc/classes/helpers/class-mtphr-dnt-string-replacement.php';
			require_once MTPHR_DNT_DIR . 'inc/templates.php';
		}
		
		require_once MTPHR_DNT_DIR . 'inc/classes/class-mtphr-dnt-roles.php';
		require_once MTPHR_DNT_DIR . 'inc/install.php';
	}
	
	
	/**
	 * Loads the plugin language files.
	 * @since 3.0
	 */	
	public function load_textdomain() {
		
		load_plugin_textdomain( 'ditty-news-ticker', false, 'ditty-news-ticker/languages/' );
	}
}

endif; // End if class_exists check.


/**
 * The main function for that returns Ditty_News_Ticker
 * @since 3.0
 */
function DNT() {
	return Ditty_News_Ticker::instance();
}

// Get Ditty News Ticker Running
DNT();