<?php

/**
 * Ditty Field Select Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Select
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Select extends Ditty_Field {
	
	public $type = 'select';

	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'options' => array(),
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
		$placeholder = ( '' != $this->args['placeholder'] ) ? $this->args['placeholder'] : false;
		$html .= '<select name="' . $name . '">';
			if ( is_array( $this->args['options'] ) && count( $this->args['options'] ) > 0 ) {
				if ( $placeholder ) {
					$html .= '<option value="">' . $placeholder . '</option>';
				}
				foreach ( $this->args['options'] as $value => $label ) {
					$sanitized_value = sanitize_text_field( $value );
					$html .= '<option value="' . $sanitized_value . '" ' . selected( $sanitized_value, $std, false ) . '>' . sanitize_text_field( $label ) . '</option>';
				}
			}
		$html .= '</select>';
		return $html;
	}
	
}
