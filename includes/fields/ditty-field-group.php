<?php

/**
 * Ditty Group Field Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Group
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Group extends Ditty_Field {	
	
	public $type = 'group';
	
	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		$atts = array(
			'fields' 					=> array(),
			'group_title' 		=> '',
			'collapsible' 		=> false,
			'default_state' 	=> 'expanded',
			'multiple_fields' => false,
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
			if ( '' != $this->label() || '' != $this->description() ) {
				$html .= '<div class="ditty-field__heading">';
					$html .= $this->label();				
					if ( $this->args['collapsible'] ) {
						$html .= '<a href="#" class="ditty-field__collapsible-toggle"><i class="fas fa-angle-down"></i></a>';
					}
					$html .= parent::help();
					$html .= parent::description();
				$html .= '</div>';
			}
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
		$values = $this->args['std'];

		if ( $this->args['multiple_fields'] ) {
			foreach ( $this->args['fields'] as $field_args ) {
				//$id = $field_args['id'];
				//$field_args['std'] = isset( $std[$id] ) ? $std[$id] : false;
				$inputs[] = ditty_field( $field_args );
			}
		} else {
			foreach ( $this->args['fields'] as $field_args ) {
				$id = $field_args['id'];
				$field_args['id'] = "{$name}[{$id}]";
				$field_args['baseid'] = $id;
				$field_args['class'] = 'ditty-field-type--group-child';
				$field_args['std'] = isset( $std[$id] ) ? $std[$id] : false;
				$inputs[] = ditty_field( $field_args );
			}
		}

		$html = '<div class="ditty-input--group__container">' . implode( ' ', $inputs ) . '</div>';
		return $html;
	}
}
