<?php

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
			self::$instance->includes();
		}
		
		do_action( 'dnt_init' );

		return self::$instance;
	}
	
	/**
	 * Include required files
	 * @since 3.0
	 */
	private function includes() {
		require_once DITTY_DIR . 'legacy/inc/composer.php';
		require_once DITTY_DIR . 'legacy/inc/helpers.php';
		require_once DITTY_DIR . 'legacy/inc/hooks.php';
		require_once DITTY_DIR . 'legacy/inc/post-types.php';
		require_once DITTY_DIR . 'legacy/inc/settings.php';
		require_once DITTY_DIR . 'legacy/inc/static.php';
		require_once DITTY_DIR . 'legacy/inc/widget.php';
		require_once DITTY_DIR . 'legacy/inc/functions.php';
		
		if( is_admin() ) {
		
			// Load admin specific code
			require_once DITTY_DIR . 'legacy/inc/admin/ajax.php';
			require_once DITTY_DIR . 'legacy/inc/admin/meta-boxes.php';
			require_once DITTY_DIR . 'legacy/inc/admin/edit-columns.php';
			require_once DITTY_DIR . 'legacy/inc/admin/fields/helpers.php';
			require_once DITTY_DIR . 'legacy/inc/admin/fields/fields.php';
			require_once DITTY_DIR . 'legacy/inc/admin/filters.php';
			require_once DITTY_DIR . 'legacy/inc/admin/functions.php';
			require_once DITTY_DIR . 'legacy/inc/admin/upgrades.php';
			
		} else {
			
			// Load front-end specific code
			require_once DITTY_DIR . 'legacy/inc/filters.php';
			require_once DITTY_DIR . 'legacy/inc/shortcodes.php';
			require_once DITTY_DIR . 'legacy/inc/classes/class-mtphr-dnt.php';
			require_once DITTY_DIR . 'legacy/inc/classes/class-mtphr-dnt-tick.php';
			require_once DITTY_DIR . 'legacy/inc/classes/class-mtphr-dnt-image.php';
			require_once DITTY_DIR . 'legacy/inc/classes/helpers/class-mtphr-dnt-string-replacement.php';
			require_once DITTY_DIR . 'legacy/inc/templates.php';
		}
		
		require_once DITTY_DIR . 'legacy/inc/classes/class-mtphr-dnt-roles.php';
		require_once DITTY_DIR . 'legacy/inc/install.php';
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