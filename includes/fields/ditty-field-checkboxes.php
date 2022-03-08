<?php

/**
 * Ditty Field Checkboxes Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Checkboxes
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Checkboxes extends Ditty_Field {
	
	public $type = 'checkboxes';

	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0.13
	 */
	public function defaults() {
		$atts = array(
			'options' => array(),
			'inline' => false,
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
		$std = ( $std ) ? $std : $this->args['std'];
		$html = '';	
		if ( is_array( $this->args['options'] ) && count( $this->args['options'] ) > 0 ) {
			$classes = 'ditty-input--checkboxes__group';
			if ( $this->args['inline'] ) {
				$classes .= ' ditty-input--checkboxes__group--inline';
			}
			if ( $this->args['input_class'] ) {
				$classes .= ' ' . $this->args['input_class'];
			}
			$html .= '<div class="' . esc_attr( $classes ) . '">';
				foreach ( $this->args['options'] as $value => $label ) {
					$input_id = uniqid( 'ditty-input--' );
					$html .= '<span class="ditty-input--checkboxes__option ditty-input--checkboxes__option--' . esc_attr( $value ) . '">';
						$sanitized_value = sanitize_text_field( $value );
						$curr_std = ( is_array( $std ) && in_array( $value, $std ) ) ? $value : false;
						$html .= '<input id="' . esc_attr( $input_id ) . '" name="' . esc_attr( "{$name}[{$value}]" ) . '" type="checkbox" value="' . esc_attr( $sanitized_value ) . '" ' . checked( $sanitized_value, $curr_std, false ) . ' /> <label for="' . esc_attr( $input_id ) . '">' . sanitize_text_field( $label ) . '</label>';
					$html .= '</span>';
				}
			$html .= '</div>';
		}
		return $html;
	}
	
}
