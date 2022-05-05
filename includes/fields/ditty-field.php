<?php

/**
 * Ditty Field Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field {
	
	public $type = 'text';
	public $args = array();
	public $common = array();
	
	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
		$this->common = array(
			'id'						=> '',
			'baseid' 				=> '',
			'name' 					=> '',
			'desc'					=> '',
			'input_desc'		=> '',
			'placeholder' 	=> '',
			'before'				=> '',
			'after'					=> '',
			'help'					=> '',
			'class'					=> '',
			'input_class'		=> '',
			'clone'					=> false,
			'clone_button'	=> __( 'Add More', 'ditty-news-ticker' ),
			'max_clone'			=> 0,
			'field_only'		=> false,
			'std'						=> '',
			'atts'					=> array(),
		);
	}
	
	/**
	 * Initialize the field
	 * @access  public
	 * @since   3.0
	 */
	public function init( $args ) {		
		$this->args = wp_parse_args( $args, $this->defaults() );
	}

	/**
	 * Return the default atts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function defaults() {
		return $this->common;
	}
	
	/**
	 * Sanitize field IDs
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function sanitize_id( $id ) {
		$id = str_replace( '[', '--', $id );
		$id = str_replace( ']', '', $id );
		return $id;
	}
	
	/**
	 * Return the html attributes
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function html_atts() {
		$id = $this->sanitize_id( $this->args['id'] );
		$classes = 'ditty-field ditty-field-type--' . $this->type . ' ' . $this->sanitize_id( 'ditty-field--' . $id );
		$classes .= ( '' != $this->args['baseid'] ) ? ' ditty-field--' . esc_attr( $this->args['baseid'] ) : '';
		$classes .= ( '' != $this->args['class'] ) ? ' ' . esc_attr( $this->args['class'] ) : '';
		$classes .= ( $this->args['clone'] ) ? ' ditty-field--clone-enabled' : '';
		
		$atts = array(
			'class' => $classes,
		);
		if ( $this->args['clone'] ) {
			$atts['data-clone_name'] 	= $this->args['id'];
			$atts['data-clone_args'] 	= htmlentities( json_encode( $this->args ) );
			$atts['data-clone_field'] = htmlentities( $this->input_wrap( $this->args['id'], false, 'clone' ) );
			$atts['data-clone_max' ]	= intval( $this->args['max_clone'] );
		}
		return $atts;
	}

	/**
	 * Return the html
	 *
	 * @since 3.0.13
	 * @return $html string
	 */
	public function html() {
		$html = '';
		if ( $this->args['field_only'] ) {
			$html .= '<div class="ditty-field-only ditty-field-only--' . $this->type . ' ditty-field-only--' . $this->args['id'] . '">';
				$html .= $this->input( $this->args['id'] );
			$html .= '</div>';
		} else {
			$html .= '<div ' . ditty_attr_to_html( $this->html_atts() ) . '>';
				if ( '' != $this->label() || '' != $this->description() ) {
					$html .= '<div class="ditty-field__heading">';
						$html .= $this->label();
						$html .= $this->help();
						$html .= $this->description();
					$html .= '</div>';
				}
				$html .= $this->input_container();
			$html .= '</div>';
		}
		return $html;
	}
	
	/**
	 * Return the label
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function label() {
		$html = '';
		if ( '' != $this->args['name'] || '' != $this->args['help'] ) {
			$html .= '<label class="ditty-field__label">';
			if ( '' != $this->args['name'] ) {
				$add_space = true;
				$html .= wp_kses_post( $this->args['name'] );
			}
			if ( '' != $this->args['help'] ) {
				if ( '' != $this->args['name'] ) {
					$html .= ' ';	
				}
				$html .= '<a href="#" class="ditty-help-icon protip" data-pt-title="' . esc_html__( 'Toggle Description', 'metaphoravada' ) . '"><i class="fas fa-question-circle"></i></a>';
			}
			$html .= '</label>';
		}
		return $html;
	}
	
	/**
	 * Return the help
	 *
	 * @since 3.0.13
	 * @return $html string
	 */
	public function help() {
		$html = '';
		if ( '' != $this->args['help'] ) {
			$html .= '<p class="ditty-field__help">';
				$html .= wp_kses_post( $this->args['help'] );
			$html .= '</p>';
		}
		return $html;
	}
	
	/**
	 * Return the description
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function description() {
		$html = '';
		if ( '' != $this->args['desc'] ) {
			$html .= '<p class="ditty-field__description">';
				$html .= wp_kses_post( $this->args['desc'] );
			$html .= '</p>';
		}
		return $html;
	}
	
	/**
	 * Return the input description
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input_description() {
		$html = '';
		if ( '' != $this->args['input_desc'] ) {
			$html .= '<p class="ditty-field__input__description">';
				$html .= wp_kses_post( $this->args['input_desc'] );
			$html .= '</p>';
		}
		return $html;
	}

	/**
	 * Return the input container
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input_container() {
		$html = '';
			$html .= '<div class="ditty-field__input__container">';
				if ( $this->args['clone'] ) {
					if ( is_array( $this->args['std'] ) && count( $this->args['std'] ) > 0 ) {
						foreach ( $this->args['std'] as $i => $std ) {
							$html .= $this->input_wrap( "{$this->args['id']}[{$i}]", $std );
						}
					}	 else {
						$html .= $this->input_wrap( "{$this->args['id']}[0]" );
					}
					$html .= '<div class="ditty-field__actions">';
						$html .= '<a href="#" class="ditty-button ditty-button--primary ditty-field__actions__clone">' . sanitize_text_field( $this->args['clone_button'] ) . '</a>';
					$html .= '</div>';
				} else {
					$html .= $this->input_wrap( $this->args['id'], $this->args['std'] );
				}
			$html .= '</div>';
		return $html;
	}
	
	/**
	 * Return the input actions
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input_actions() {
		$html = '';
			
		if ( $this->args['clone'] ) {
			$html .= '<div class="ditty-field__input__actions">';
				$html .= '<a href="#" class="ditty-field__input__action--arrange protip" data-pt-title="' . __( 'Re-arrange', 'ditty-news-ticker' ) . '"><i class="fas fa-bars" data-class="fas fa-bars"></i></a>';
				$html .= '<a href="#" class="ditty-field__input__action--remove protip" data-pt-title="' . __( 'Delete', 'ditty-news-ticker' ) . '"><i class="fas fa-minus-circle" data-class="fas fa-minus-circle"></i></a>';
				$html .= '<a href="#" class="ditty-field__input__action--add protip" data-pt-title="' . __( 'Add', 'ditty-news-ticker' ) . '"><i class="fas fa-plus-circle" data-class="fas fa-plus-circle"></i></a>';
				$html .= '<a href="#" class="ditty-field__input__action--clone protip" data-pt-title="' . __( 'Clone', 'ditty-news-ticker' ) . '"><i class="fas fa-clone" data-class="fas fa-clone"></i></a>';	
			$html .= '</div>';
		}
		
		return $html;
	}
	
	/**
	 * Return a single input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input_wrap( $name, $std = false, $clone = 'orig' ) {
		$html = '';
		
		$extra_classes = '';
		if ( $this->args['clone'] ) {
			$extra_classes .= ' ditty-input--clone ditty-input--clone--' . $clone;
		}
		$atts = array(
			'class' => 'ditty-field__input ditty-input--' . $this->type . $extra_classes,
		);
		if ( '' != $this->args['baseid'] ) {
			$atts['data-baseid'] 	= $this->args['baseid'];
		}
		
		$html .= '<div ' . ditty_attr_to_html( $atts ) . '>';
			$html .= $this->input_actions();
			$html .= $this->input_before();
			$html .= '<span class="ditty-field__input__primary">' . $this->input( $name, $std ) . '</span>';
			$html .= $this->input_after();
			$html .= $this->input_description();
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Return the input before
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input_before() {
		$html = '';
		if ( $this->args['before'] ) {
			$html = '<span class="ditty-field__input__before">' . $this->args['before'] . '</span>';
		}
		return $html;
	}
	
	/**
	 * Return the input after
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input_after() {
		$html = '';
		if ( $this->args['after'] ) {
			$html = '<span class="ditty-field__input__after">' . $this->args['after'] . '</span>';
		}
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
		
		$atts = array(
			'name' => $name,
			'type' => 'text',
			'class' => ( $this->args['input_class'] ) ? $this->args['input_class'] : false,
			'placeholder' => ( $this->args['placeholder'] ) ? $this->args['placeholder'] : false,
			'value' => htmlspecialchars( $std ),
		);
		if ( is_array( $this->args['atts'] ) && count( $this->args['atts'] ) > 0 ) {
			foreach ( $this->args['atts'] as $key => $value ) {
				$atts[$key] = $value;
			}
		}
		$html .= '<input ' . ditty_attr_to_html( $atts ) . ' />';
		return $html;
	}
	
}
