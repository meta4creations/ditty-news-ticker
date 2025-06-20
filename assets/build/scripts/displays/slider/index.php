<?php

/**
 * Ditty Display Type Slider Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Type Slider
 * @copyright   Copyright (c) 2025, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.2
 */
class Ditty_Display_Type_Slider extends Ditty_Display_Type {

	/**
	 * Type
	 *
	 * @since 3.2
	 */
	public $type = 'slider';
	public $js_settings = true;
  
  /**
	 * Get things started
	 * @access  public
	 * @since   3.2
	 */
	public function __construct() {
		parent::__construct();
	}

  /**
	 * Return the default shortcode attributes
	 * @access  public
	 * @since   3.2
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
