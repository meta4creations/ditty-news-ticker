<?php

/**
 * Ditty Field Number Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Number
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Number extends Ditty_Field {
	
	public $type = 'number';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'min' => '0',
			'step' => '1',
		);
		return wp_parse_args( $atts, $this->common );
	}

	/**
	 * Return the input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input( $name, $std = false ) {
		$html = '';	
		$html .= '<input name="' . $name . '" step="' . intval( $this->args['step'] ) . '" min="' . intval( $this->args['min'] ) . '" type="number" value="' . intval( $std ) . '" />';
		return $html;
	}
	
}
