<?php

/**
 * Ditty Code Field Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Code
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Code extends Ditty_Field {	
	
	public $type = 'code';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'rows' => 4,
			'cols' => 60,
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
		$atts['rows'] = $this->args['rows'];
		$atts['cols'] = $this->args['cols'];
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
		$html .= '<textarea name="' . $name . '" ' . ditty_attr_to_html( $this->attributes() ) . '>' . stripslashes( $std ) . '</textarea>';
		return $html;
	}
}
