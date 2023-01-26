<?php

/**
 * Ditty Field Divider Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Divider
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Divider extends Ditty_Field {	
	
	public $type = 'divider';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'height' => 50,
			'line' => true,
			'line_height' => 2,
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
		
		$height = intval( $this->args['height'] );
		if ( $height < 0 ) {
			$height = 30;
		}
		
		$id = parent::sanitize_id( $this->args['id'] );	
		$classes = 'ditty-field ditty-field-type--' . $this->type . ' ' . $this->sanitize_id( 'ditty-field--' . $id );
		$classes .= ( '' != $this->args['baseid'] ) ? ' ditty-field--' . esc_attr( $this->args['baseid'] ) : '';
		$classes .= ( '' != $this->args['class'] ) ? ' ' . esc_attr( $this->args['class'] ) : '';
		$classes .= ( $this->args['clone'] ) ? ' ditty-field--clone-enabled' : '';
		
		$atts = array(
			//'id' 		=> sanitize_id( 'ditty-field--' . $id ),
			'class' => $classes,
		);
		if ( $this->args['clone'] ) {
			$atts['data-clone_name'] 	= $this->args['id'];
			$atts['data-clone_args'] 	= htmlentities( json_encode( $this->args ) );
			$atts['data-clone_field'] = htmlentities( $this->input_wrap( $this->args['id'] ) );
			$atts['data-clone_max' ]	= intval( $this->args['max_clone'] );
			$atts['style' ]						= "height:{$height}px";
		}
		$html .= '<div ' . ditty_attr_to_html( $atts ) . '>';
			$html .= '<div class="ditty-field__heading">';
				$html .= parent::label();
				$html .= parent::description();
			$html .= '</div>';
			$html .= parent::input_container();
		$html .= '</div>';
		return $html;
	}

	/**
	 * Return the input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input( $name, $std = false ) {
		$html = '';
		
		$line_height = intval( $this->args['line_height'] );
		if ( $line_height < 0 ) {
			$line_height = 1;
		}
		if ( true == $this->args['line'] ) {
			$html .= '<div class="ditty-input--divider__line" style="height:' . $line_height . 'px"></div>';
		}
		return $html;
	}
	
}
