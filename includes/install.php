<?php
/**
 * Install Function
 *
 * @package     MTPHR_DNT
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2017, Intrycks
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.1.12
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the post types, custom taxonomies,
 * flushing rewrite rules to initiate the new 'mtphr_dnt_ticket' slug and also
 * creates the plugin and populates the settings fields for those plugin
 * pages. After successful install, the user is redirected to the MTPHR_DNT Welcome
 * screen.
 *
 * @since 1.0
 * @global $wpdb
 * @global $mtphr_dnt_options
 * @param  bool $network_side If the plugin is being network-activated
 * @return void
 */
 
 
function mtphr_dnt_install( $network_wide = false ) {
	global $wpdb;

	if( is_multisite() && $network_wide ) {

		foreach( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {

			switch_to_blog( $blog_id );
			mtphr_dnt_run_install();
			restore_current_blog();
		}

	} else {

		mtphr_dnt_run_install();
	}
}
register_activation_hook( MTPHR_DNT_FILE, 'mtphr_dnt_install' );


/**
 * Run the MTPHR_DNT Install process
 *
 * @since 2.1.11
 * @return void
 */
function mtphr_dnt_run_install() {
	
	global $wpdb, $mtphr_dnt_options;
	
	if( empty($mtphr_dnt_options) ) {
		$mtphr_dnt_options = array();
	}

	// Setup the MTPHR_DNT Custom Post Types
	mtphr_dnt_setup_post_types();

	// Clear the permalinks
	flush_rewrite_rules( false );

	// Add Upgraded From Option
	$current_version = get_option( 'mtphr_dnt_version' );
	if( $current_version ) {
		update_option( 'mtphr_dnt_version_upgraded_from', $current_version );
	}

	update_option( 'mtphr_dnt_version', MTPHR_DNT_VERSION );

	// Create MTPHR_DNT roles
	$roles = new MTPHR_DNT_Roles;
	//$roles->add_roles();
	$roles->add_caps();
}


/**
 * When a new Blog is created in multisite, see if MTPHR_DNT is network activated, and run the installer
 *
 * @since  2.1.12
 * @param  int    $blog_id The Blog ID created
 * @param  int    $user_id The User ID set as the admin
 * @param  string $domain  The URL
 * @param  string $path    Site Path
 * @param  int    $site_id The Site ID
 * @param  array  $meta    Blog Meta
 * @return void
 */
function mtphr_dnt_new_blog_created( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

	if( is_plugin_active_for_network(plugin_basename(MTPHR_DNT_FILE)) ) {

		switch_to_blog( $blog_id );
		mtphr_dnt_install();
		restore_current_blog();
	}
}
add_action( 'wpmu_new_blog', 'mtphr_dnt_new_blog_created', 10, 6 );


/**
 * Install user roles on sub-sites of a network
 *
 * Roles do not get created when MTPHR_DNT is network activation so we need to create them during admin_init
 *
 * @since 2.1.11
 * @return void
 */
function mtphr_dnt_install_roles_on_network() {

	global $wp_roles;

	if( !is_object($wp_roles) ) {
		return;
	}

	if( empty($wp_roles->roles) || !array_key_exists('mtphr_dnt_ticket_agent', $wp_roles->roles) ) {

		// Create MTPHR_DNT roles
		$roles = new MTPHR_DNT_Roles;
		//$roles->add_roles();
		$roles->add_caps();
	}
}
add_action( 'admin_init', 'mtphr_dnt_install_roles_on_network' );
