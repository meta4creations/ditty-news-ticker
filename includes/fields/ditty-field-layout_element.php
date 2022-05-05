<?php

/**
 * Ditty Layout Element Field Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Group
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.23
*/
class Ditty_Field_Layout_Element extends Ditty_Field {	
	
	public $type = 'layout_element';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'fields' 					=> array(),
			'collapsible' 		=> false,
			'default_state' 	=> 'expanded',
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
		$atts = $this->html_atts();
		if ( $this->args['collapsible'] ) {
			$atts['data-collapsible'] = $this->args['default_state'];
		}
		$html = '';
		$html .= '<div ' . ditty_attr_to_html( $atts ) . '>';
			$html .= '<div class="ditty-field__heading">';
				$html .= '<div class="ditty-switch">';
					$html .= '<div class="ditty-switch__elements">';
						$html .= '<div class="ditty-switch__bg"></div>';
						$html .= '<div class="ditty-switch__button"></div>';
					$html .= '</div>';
				$html .= '</div>';
				//$html .= '<i class="fas fa-pencil-ruler" data-class="fas fa-pencil-ruler"></i>';
				$html .= $this->label();	
				if ( $this->args['collapsible'] ) {
					$html .= '<a href="#" class="ditty-field__collapsible-toggle"><i class="fas fa-angle-down"></i></a>';
				}
				$html .= parent::help();
				$html .= parent::description();
			$html .= '</div>';
			$html .= $this->input_container();
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
		$inputs = array();

		foreach ( $this->args['fields'] as $field_args ) {
			$id = $field_args['id'];
			$field_args['id'] = "{$name}[{$id}]";
			$field_args['baseid'] = $id;
			$field_args['class'] = 'ditty-field-type--layout_element-child';
			$field_args['std'] = isset( $std[$id] ) ? $std[$id] : false;
			$inputs[] = ditty_field( $field_args );
		}

		$html = '<div class="ditty-input--layout_element__container">' . implode( ' ', $inputs ) . '</div>';
		return $html;
	}
}
