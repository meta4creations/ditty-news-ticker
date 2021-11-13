<?php

/**
 * Ditty Field Spacing Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Spacing
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Spacing extends Ditty_Field {
	
	public $type = 'spacing';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'options' => array(
				'paddingTop'		=> __( 'Top', 'ditty-news-ticker' ),
				'paddingBottom'	=> __( 'Bottom', 'ditty-news-ticker' ),
	      'paddingLeft'		=> __( 'Left', 'ditty-news-ticker' ),
	      'paddingRight'	=> __( 'Right', 'ditty-news-ticker' ),
			),
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
		
		$inputs = array();
		
		foreach ( $this->args['options'] as $key => $label ) {
			$value = isset( $std[ $key ] ) ? $std[ $key ] : '';
			$atts = array(
				'id' 					=> "{$name}[{$key}]",
				'placeholder' => $label,
				'std'					=> $value,
			);
			$input = new Ditty_Field_Text();
			$input->init( $atts );	
			$inputs[] = $input->html();
		}
		
		$html = '<div class="ditty-input--spacing__group">' . implode( ' ', $inputs ) . '</div>';
		return $html;	
	}
	
}
