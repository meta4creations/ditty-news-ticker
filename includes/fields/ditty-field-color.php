<?php

/**
 * Ditty Field Color Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Color
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Color extends Ditty_Field {
	
	public $type = 'color';

	/**
	 * Return the input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input( $name, $std = false ) {
		$html = '';	
		$html .= '<input name="' . $name . '" type="text" value="' . sanitize_text_field( $std ) . '" />';
		return $html;
	}
	
}
