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
	public function input( $name, $standard = false ) {
		$inputs = array();
		foreach ( $this->args['options'] as $key => $label ) {
			$std = ( is_array( $standard ) && isset( $standard[ $key ] ) ) ? $standard[ $key ] : false;
			$atts = array(
				'id' 					=> "{$name}[{$key}]",
				'label' 			=> $label,
				'value'				=> $key,
				'std'					=> $std,
			);
			$input = new Ditty_Field_Checkbox();
			$input->init( $atts );	
			$inputs[] = $input->html();
		}
		
		$html = '<div class="ditty-input--checkboxes__group">' . implode( ' ', $inputs ) . '</div>';
		return $html;	
	}
	
}
