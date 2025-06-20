<?php

/**
 * Ditty Display Type Ticker Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Type Ticker
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
class Ditty_Display_Type_Ticker extends Ditty_Display_Type {

	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'ticker';
	public $js_settings = true;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.1
	 */
	public function __construct() {
		parent::__construct();
	}

  /**
	 * Return the default shortcode attributes
	 * @access  public
	 * @since   3.1.29
	 */
  public function shortcode_atts() {
    return $this->default_settings();
  }

	/**
	 * Set the default settings
	 * @access  public
	 * @since   3.2
	 */
	public function default_settings() {
    $config = json_decode( file_get_contents( __DIR__ . '/display.json' ), true );
    $defaults = $config['defaults'] ?? [];
    return apply_filters( 'ditty_display_default_settings', $defaults, $this->type );
	}
}
