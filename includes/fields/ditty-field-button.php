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
class Ditty_Field_Button extends Ditty_Field {
	
	public $type = 'button';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'label'				=> __( 'Button', 'ditty-news-ticker' ),
			'link'				=> '#',
			'size' 				=> 'default',
			'priority' 		=> 'default',
			'full_width'	=> false,
			'icon_before' => '',
			'icon_after'	=> '',
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
		$icon_before = ( $this->args['icon_before'] ) ? '<i class="' . esc_attr( $this->args['icon_before'] ) . '"></i>' : '';
		$icon_after = ( $this->args['icon_after'] ) ? '<i class="' . esc_attr( $this->args['icon_after'] ) . '"></i>' : '';

		$class = 'ditty-button';
		if ( $this->args['size'] ) {
			$class .= ' ditty-button--' . $this->args['size'];
		}
		if ( $this->args['priority'] ) {
			$class .= ' ditty-button--' . $this->args['priority'];
		}
		if ( $this->args['full_width'] ) {
			$class .= ' ditty-button--wide';
		}
		if ( $this->args['input_class'] ) {
			$class .= ' ' . $this->args['input_class'];
		}
		$atts = array(
			'name' 	=> $name,
			'class' => $class,
			'href' 	=>  $this->args['link'],
		);
		if ( is_array( $this->args['atts'] ) && count( $this->args['atts'] ) > 0 ) {
			foreach ( $this->args['atts'] as $key => $value ) {
				$atts[$key] = $value;
			}
		}

		$html .= '<button ' . ditty_attr_to_html( $atts ) . '><span class="ditty-button__contents">' . $icon_before . '<span ditty-button__label>' . sanitize_text_field( $this->args['label'] ) . '</span>' . $icon_after . '</span></button>';
		return $html;
	}
	
}
