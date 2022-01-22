<?php

/**
 * Ditty Field Radio Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Radio
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Radio extends Ditty_Field {
	
	public $type = 'radio';

	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'options' => array(),
			'inline' => false,
		);
		return wp_parse_args( $atts, $this->common );
	}
	
	/**
	 * Return a single input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input_wrap( $name, $std = false, $clone = 'orig' ) {
		$html = '';
		
		$extra_classes = '';
		if ( $this->args['clone'] ) {
			$extra_classes .= ' ditty-input--clone ditty-input--clone--' . $clone;
		}
		if ( $this->args['inline'] ) {
			$extra_classes .= ' ditty-input--' . $this->type . '--inline';
		}
		$atts = array(
			'class' => 'ditty-field__input ditty-input--' . $this->type . $extra_classes,
			'role'	=> 'radiogroup',
		);
		if ( '' != $this->args['baseid'] ) {
			$atts['data-baseid'] 	= $this->args['baseid'];
		}
		
		$html .= '<div ' . ditty_attr_to_html( $atts ) . '>';
			$html .= $this->input_actions();
			$html .= $this->input_before();
			$html .= '<span class="ditty-field__input__primary">' . $this->input( $name, $std ) . '</span>';
			$html .= $this->input_after();
			$html .= $this->input_description();
		$html .= '</div>';
		return $html;
	}

	/**
	 * Return the input
	 *
	 * @since 3.0.12
	 * @return $html string
	 */
	public function input( $name, $std = false ) {
		$html = '';	
		if ( is_array( $this->args['options'] ) && count( $this->args['options'] ) > 0 ) {
			foreach ( $this->args['options'] as $value => $label ) {
				$input_id = uniqid( 'ditty-input--' );
				$html .= '<span class="ditty-input--radio__option ditty-input--radio__option--' . esc_attr( $value ) . '">';
					$sanitized_value = sanitize_text_field( $value );
					$html .= '<input id="' . esc_attr( $input_id ) . '" name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $sanitized_value ) . '" ' . checked( $sanitized_value, $this->args['std'], false ) . ' /> <label for="' . esc_attr( $input_id ) . '">' . sanitize_text_field( $label ) . '</label>';
				$html .= '</span>';
			}
		}
		return $html;
	}
	
}
