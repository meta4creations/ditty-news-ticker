<?php

/**
 * Ditty Field Select Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Select
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Select extends Ditty_Field {
	
	public $type = 'select';

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
	 * Return an option array
	 *
	 * @since 3.0
	 * @return $html string
	 */
	private function render_option( $value, $data, $std = false ) {
		$sanitized_help = false;
		if ( is_array( $data ) ) {
			$sanitized_value 	= isset( $data['value'] ) ? esc_attr( $data['value'] ) : '';
			$sanitized_label 	= isset( $data['label'] ) ? sanitize_text_field( $data['label'] ) : '';
			$sanitized_help		= isset( $data['help'] ) ? esc_attr( $data['help'] ) : false;
		} else {
			$sanitized_value = esc_attr( $value );
			$sanitized_label = sanitize_text_field( $data );
		}
		$atts = array(
			'value' 		=> $sanitized_value,
			'data-help' => $sanitized_help,
		);
		return '<option ' . ditty_attr_to_html( $atts ) . ' ' . selected( $sanitized_value, $std, false ) . '>' . $sanitized_label . '</option>';
	}
	
	/**
	 * Return a select group
	 *
	 * @since 3.0
	 * @return $html string
	 */
	private function render_group( $value, $data, $std = false ) {
		$sanitized_label 	= isset( $data['label'] ) ? sanitize_text_field( $data['label'] ) : '';
		$html = '';
		$html .= '<optgroup label="' . $sanitized_label . '">';
		if ( is_array( $data['options'] ) && count( $data['options'] ) > 0 ) {
			foreach ( $data['options'] as $sub_value => $sub_data ) {
				$html .= $this->render_option( $sub_value, $sub_data, $std );
			}
		}
		$html .= '</optgroup>';
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
		$placeholder = ( '' != $this->args['placeholder'] ) ? $this->args['placeholder'] : false;
		$html .= '<select name="' . $name . '">';
			if ( is_array( $this->args['options'] ) && count( $this->args['options'] ) > 0 ) {
				if ( $placeholder ) {
					$html .= '<option value="">' . $placeholder . '</option>';
				}
				foreach ( $this->args['options'] as $value => $data ) {
					if ( is_array( $data ) && isset( $data['group'] ) ) {
						$html .= $this->render_group( $value, $data, $std );
					} else {
						$html .= $this->render_option( $value, $data, $std );
					}

					// 	echo '<optgroup label="'.$i.'">';
					// 	foreach( $option as $e=>$suboption ) {
					// 		$html .= mtphr_dnt_select_help( $suboption );
					// 		$v = $option_keys ? $e : $data['label'];
					// 		$html .= '<option value="'.$v.'" '.selected($v, $value, false).''.$data['help'].'>'.$data['label'].'</option>';
					// 	}
					// 	$html .= '</optgroup>';
					// } else {
					// 	$sanitized_value = sanitize_text_field( $value );
					// 	$html .= '<option value="' . $sanitized_value . '" ' . selected( $sanitized_value, $std, false ) . '>' . sanitize_text_field( $data ) . '</option>';
					// }
				}
			}
		$html .= '</select>';
		return $html;
	}
	
}
