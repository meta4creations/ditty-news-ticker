<?php

/**
 * Ditty Field Textarea Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Textarea
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Textarea extends Ditty_Field {	
	
	public $type = 'textarea';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'cols' => 60,
			'rows' => 4,
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
		$placeholder = ( '' != $this->args['placeholder'] ) ? ' placeholder="' . sanitize_text_field( $this->args['placeholder'] ) . '"' : false;
		$html .= '<textarea name="' . $name . '"' . $placeholder . '>' . $std . '</textarea>';
		return $html;
	}
	
}
