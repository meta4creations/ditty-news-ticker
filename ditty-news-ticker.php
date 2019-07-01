<?php
/*
Plugin Name: Ditty News Ticker
Plugin URI: http://dittynewsticker.com/
Description: Ditty News Ticker is a multi-functional data display plugin
Text Domain: ditty-news-ticker
Domain Path: languages
Version: 2.2.2
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
	 * DNT Roles Object.
	 *
	 * @var object|DNT_Roles
	 * @since 3.0
	 */
	public $roles;
	
	/**
	 * DNT API Object.
	 *
	 * @var object|EDD_API
	 * @since 3.0
	 */
	//public $api;
	
	/**
	 * DNT Layout Template Tags Object.
	 *
	 * @var object|DNT_Layout_Template_Tags
	 * @since 3.0
	 */
	public $layout_tags;
	
	/**
	 * DNT Layouts Object.
	 *
	 * @var object|DNT_Layouts
	 * @since 3.0
	 */
	public $layout;
	
	/**
	 * DNT Tickers Object.
	 *
	 * @var object|DNT_Tickers
	 * @since 3.0
	 */
	public $tickers;
	
	
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
			
			if ( 'legacy' != DNT_BUILD ) {	
				self::$instance->roles				= new DNT_Roles();
				//self::$instance->api          = new DNT_API();
				self::$instance->layout_tags	= new DNT_Layout_Template_Tags();
				self::$instance->layout 			= new DNT_Layout();
				self::$instance->layout_meta 	= new DNT_DB_Layout_Meta();
				self::$instance->tickers 			= new DNT_Tickers();
			}
		}
		
		do_action( 'dnt_init' );

		return self::$instance;
	}
	
	
	/**
	 * Setup plugin constants.
	 * @since 3.0
	 */
	private function setup_constants() {
		
		// Plugin build for testing
		if ( ! defined( 'DNT_BUILD' ) ) {
			define( 'DNT_BUILD', 'legacy' );
		}
		
		// Plugin version
		if ( ! defined( 'DNT_VERSION' ) ) {
			define( 'DNT_VERSION', '2.2.2' );
		}

		// Plugin Folder Path
		if ( ! defined( 'DNT_DIR') ) {
			define( 'DNT_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'DNT_URL') ) {
			define( 'DNT_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}
		
		// Plugin Root File
		if ( ! defined( 'DNT_FILE') ) {
			define( 'DNT_FILE', __FILE__ );
		}
		
		// Store URL
		if ( ! defined( 'DNT_STORE_URL') ) {
			define( 'DNT_STORE_URL', 'https://www.metaphorcreations.com' );
		}
	}
	
	
	/**
	 * Include required files
	 * @since 3.0
	 */
	private function includes() {
		
		// Load the general functions
		require_once DNT_DIR . 'eddsl/eddsl.php';
		require_once DNT_DIR . 'vendor/autoload.php';
		require_once DNT_DIR . 'inc/helpers.php';
		require_once DNT_DIR . 'inc/hooks.php';
		
		if ( 'legacy' == DNT_BUILD ) {	
			require_once DNT_DIR . 'legacy/init.php';	
		} else {		
			require_once DNT_DIR . 'inc/api.php';
			require_once DNT_DIR . 'inc/hooks-upgrade.php';
			require_once DNT_DIR . 'inc/post-types.php';
			require_once DNT_DIR . 'inc/static.php';
			require_once DNT_DIR . 'inc/strings.php';
			//require_once DNT_DIR . 'inc/classes/class-dnt-api.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-db.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-db-layout-meta.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-layout.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-layout-tags.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-tickers.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-tick.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-roles.php';
			
			require_once DNT_DIR . 'inc/classes/class-dnt-type.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-type-default.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-type-twitter-feed.php';
			require_once DNT_DIR . 'inc/classes/class-dnt-type-twitter-tweet.php';
			
			// Legacy
			require_once DNT_DIR . 'legacy/classes/class-mtphr-dnt-image.php';
			
			if ( is_admin() ) {
				require_once DNT_DIR . 'inc/admin/layouts/metabox.php';
				require_once DNT_DIR . 'inc/admin/tickers/metabox.php';
				
				require_once DNT_DIR . 'inc/admin-hooks.php';
				require_once DNT_DIR . 'inc/admin-functions.php';
			}
			
			require_once DNT_DIR . 'inc/install.php';
		}
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