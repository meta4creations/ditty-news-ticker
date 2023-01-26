<?php

/**
 * Ditty Slider Field Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Slider
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Slider extends Ditty_Field {	
	
	public $type = 'slider';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'js_options' => array(),
		);
		return wp_parse_args( $atts, $this->common );
	}
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	private function attributes() {
		$atts = array();
		if ( is_array( $this->args['js_options'] ) && count( $this->args['js_options'] ) > 0 ) {
			foreach ( $this->args['js_options'] as $option => $value ) {
				$atts["data-{$option}"] = $value;
			}
		}
		return $atts;
	}
	
	/**
	 * Return the input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input( $name, $std = false ) {
		$html = '';
		$html .= '<input name="' . $name . '" type="text" value="' . $std . '" ' . ditty_attr_to_html( $this->attributes() ) . ' />';
		return $html;
	}
}
