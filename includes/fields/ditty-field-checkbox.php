<?php

/**
 * Ditty Field Checkbox Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Checkbox
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Checkbox extends Ditty_Field {
	
	public $type = 'checkbox';

	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'label' => '',
			'value' => '1',
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
		$id = uniqid( 'ditty-input--' );
		$value = sanitize_text_field( $this->args['value'] );
		$html .= '<input id="ditty-input--' . $id . '" name="' . $name . '" type="checkbox" value="' . $value . '" ' . checked( $value, $std, false ) . ' />';
		if ( '' != $this->args['label'] ) {
			$html .= ' <label for="ditty-input--' . $id . '">' . sanitize_text_field( $this->args['label'] ) . '</label>';
		}
		return $html;
	}
	
}
