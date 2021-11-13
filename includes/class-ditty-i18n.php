<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.metaphorcreations.com
 * @since      3.0
 *
 * @package    Ditty
 * @subpackage Ditty/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      3.0
 * @package    Ditty
 * @subpackage Ditty/includes
 * @author     Metaphor Creations <joe@metaphorcreations.com>
 */
class Ditty_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    3.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ditty-news-ticker',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
