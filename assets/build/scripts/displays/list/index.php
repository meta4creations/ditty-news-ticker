<?php

/**
 * Ditty Display Type List Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Type List
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
class Ditty_Display_Type_List extends Ditty_Display_Type {

	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'list';
	public $js_settings = true;

	/**
	 * Set the default settings
	 * @access  public
	 * @since   3.1
	 */
	public function default_settings() {
    $config = json_decode( file_get_contents( __DIR__ . '/display.json' ), true );
    $defaults = $config['defaults'] ?? [];
    return apply_filters( 'ditty_display_default_settings', $defaults, $this->type );
	}
}
