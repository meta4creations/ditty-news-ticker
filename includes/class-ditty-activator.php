<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      3.0
 * @package    Ditty
 * @subpackage Ditty/includes
 * @author     Metaphor Creations <joe@metaphorcreations.com>
 */
class Ditty_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    3.0
	 */
	public static function activate() {
		self::setup_sites();
	}
	
	/**
	 * Setup this site and all multisite sites
	 *
	 * @since     3.0
	 * @access   	private
	 */
	public static function setup_sites( $network_wide = false ) {
		
		global $wpdb;

		if( is_multisite() && $network_wide ) {
	
			foreach( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
	
				switch_to_blog( $blog_id );
				self::run_install();
				restore_current_blog();
			}
	
		} else {
	
			self::run_install();
		}
	}
	
	/**
	 * Run the install process
	 *
	 * @since    3.0
	 * @access   private
	 * @return   string    The name of the plugin.
	 */
	public static function run_install() {

		// Load the DB classes
		require_once DITTY_DIR . 'includes/class-ditty-db.php';
		require_once DITTY_DIR . 'includes/class-ditty-db-items.php';
		require_once DITTY_DIR . 'includes/class-ditty-db-item-meta.php';

		// Create the item databases
		$db_items = new Ditty_DB_Items();
		$db_items->create_table();

		$db_item_meta = new Ditty_DB_Item_Meta();
		$db_item_meta->create_table();
	}

}
