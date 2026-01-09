<?php

/**
 * Plugin Name:       Ditty
 * Plugin URI:        https://www.metaphorcreations.com/ditty
 * Description:       Ditty offers a range of content display options, including its signature news ticker and customizable layouts.
 * Version:           3.1.63
 * Author:            Metaphor Creations
 * Author URI:        https://www.metaphorcreations.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Tested up to:      6.9
 * Text Domain:       ditty-news-ticker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Plugin version.
if ( ! defined( 'DITTY_VERSION' ) ) {
	define( 'DITTY_VERSION', '3.1.63' );
}

// Plugin Folder Path.
if ( ! defined( 'DITTY_DIR' ) ) {
	define( 'DITTY_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL.
if ( ! defined( 'DITTY_URL' ) ) {
	define( 'DITTY_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File.
if ( ! defined( 'DITTY_FILE' ) ) {
	define( 'DITTY_FILE', __FILE__ );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ditty-activator.php
 */
function ditty_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ditty-activator.php';
	Ditty_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ditty-deactivator.php
 */
function ditty_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ditty-deactivator.php';
	Ditty_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ditty_activate' );
register_deactivation_hook( __FILE__, 'ditty_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ditty.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function Ditty() {
	return Ditty::instance();
}
Ditty();
