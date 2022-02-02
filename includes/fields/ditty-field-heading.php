<?php

/**
 * Ditty Field Divider Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Heading
 * @copyright   Copyright (c) 2022, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.13
*/
class Ditty_Field_Heading extends Ditty_Field {	
	
	public $type = 'heading';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'element' => 'h3',
		);
		return wp_parse_args( $atts, $this->common );
	}

	/**
	 * Return the html
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function html() {
		$html = '';

		$id = parent::sanitize_id( $this->args['id'] );	
		$classes = 'ditty-field ditty-field-type--' . $this->type . ' ' . $this->sanitize_id( 'ditty-field--' . $id );
		$classes .= ( '' != $this->args['baseid'] ) ? ' ditty-field--' . esc_attr( $this->args['baseid'] ) : '';
		$classes .= ( '' != $this->args['class'] ) ? ' ' . esc_attr( $this->args['class'] ) : '';
		
		$atts = array(
			'class' => $classes,
		);
		$html .= '<div ' . ditty_attr_to_html( $atts ) . '>';
			$html .= '<div class="ditty-field__heading">';
				$html .= $this->label();
				$html .= parent::help();
				$html .= parent::description();
			$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Return the label
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function label() {
		$html = '';
		if ( '' != $this->args['name'] || '' != $this->args['help'] ) {
			$html .= '<' . $this->args['element'] . ' class="ditty-field__label">';
			if ( '' != $this->args['name'] ) {
				$add_space = true;
				$html .= wp_kses_post( $this->args['name'] );
			}
			if ( '' != $this->args['help'] ) {
				if ( '' != $this->args['name'] ) {
					$html .= ' ';	
				}
				$html .= '<a href="#" class="ditty-help-icon protip" data-pt-title="' . esc_html__( 'Toggle Description', 'metaphoravada' ) . '"><i class="fas fa-question-circle"></i></a>';
			}
			$html .= '</' . $this->args['element'] . '>';
		}
		return $html;
	}
	
}
