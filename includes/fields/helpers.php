<?php

/**
 * Display a field
 *
 * @access  public
 * @since   3.0
 */
function ditty_field( $args = array() ) {
	if ( empty( $args ) ) {
		return false;
	}
	if ( ! isset( $args['type'] ) ) {
		return '<div class="ditty-field ditty-field--error"><i class="fas fa-exclamation-circle"></i>' . __( 'No field type specified.', 'ditty-news-ticker' ) . '</div>';
	}
	$type = ucfirst( $args['type'] );
	$class_name = "Ditty_Field_{$type}";
	if ( ! class_exists( $class_name ) ) {
		return '<div class="ditty-field ditty-field--error"><i class="fas fa-exclamation-circle"></i> ' . sprintf( __( '%s class does not exists.', 'ditty-news-ticker' ), $class_name ) . '</div>';
	}
	$class = new $class_name;
	
	$class->init( $args );
	return $class->html();
}

/**
 * Display multiple fields
 *
 * @access  public
 * @since   3.0.12
 */
function ditty_fields( $fields = array(), $values = array(), $action = 'render' ) {
	$rendered_fields = '';
	if ( is_array( $fields ) && count( $fields ) > 0 ) {
		foreach ( $fields as &$field ) {
			if ( ! is_array( $field ) ) {
				continue;
			}
			if ( isset( $values[$field['id']] ) ) {
				$field['std'] = $values[$field['id']];
			}
			$rendered_fields .= ditty_field( $field );;
		}
	}
	if ( 'return' == $action ) {
		return $rendered_fields;
	} else {
		echo $rendered_fields;
	}
}

/**
 * Sanitize an input
 *
 * @access  public
 * @since   3.0
 */
function ditty_sanitize_input( $field, $value ) {
	$sanitize_type = isset( $field['sanitize'] ) ? $field['sanitize'] : $field['type'];
	$sanitized_value = false;
	switch( $sanitize_type ) {
		case 'attr':
			$sanitized_value = esc_attr( $value );
			break;
		case 'email':
			if ( is_email( $value ) ) {
				$sanitized_value = sanitize_text_field( $value );
			} else {
				$sanitized_value = false;
			}
			break;
		case 'number':
			$sanitized_value = intval( $value );
			break;
		case 'spacing':
		case 'radius':
		case 'checkboxes':
			if ( is_array( $value ) && count( $value ) > 0 ) {
				foreach ( $value as $key => $val ) {
					$sanitized_value[$key] = sanitize_text_field( $val );
				}
			}
			break;
		case 'checkbox':
		case 'radio':
		case 'slider':
		case 'text':
			$sanitized_value = sanitize_text_field( $value );
			break;
		case 'url':
			$sanitized_value = esc_url_raw( $value );
			break;
		default:
			$sanitized_value = wp_kses_post( $value );
			break;
	}
	return $sanitized_value;
}

/**
 * Sanitize group field
 *
 * @access  public
 * @since   3.0
 */
function ditty_sanitize_group( $group = array(), $values = array() ) {
	$sanitized_values = array();
	if ( isset( $group['fields'] ) && is_array( $group['fields'] ) && count( $group['fields'] ) > 0 ) {
		foreach ( $group['fields'] as $field ) {
			if ( ! isset( $values[$field['id']] ) ) {
				continue;
			}
			$sanitized_values[$field['id']] = ditty_sanitize_input( $field, $values[$field['id']] );
		}
	}
	return $sanitized_values;
}

/**
 * Sanitize clone field
 *
 * @access  public
 * @since   3.0
 */
function ditty_sanitize_clone( $field = array(), $values = array() ) {
	$sanitized_values = array();
	if ( is_array( $values ) && count( $values ) > 0 ) {
		foreach ( $values as $index => $value ) {
			if( 'group' == $field['type'] ) {
				$sanitized_value = ditty_sanitize_group( $field, $value );
			} else {
				$sanitized_value = ditty_sanitize_input( $field, $value );
			}
			if ( $sanitized_value ) {
				$sanitized_values[$index] = $sanitized_value;
			}
		}
	}
	return $sanitized_values;
}

/**
 * Sanitize a single fields
 *
 * @access  public
 * @since   3.0
 */
function ditty_sanitize_field( $field = array(), $values = array() ) {
	$sanitized_value = false;
	if ( isset( $field['clone'] ) && true == $field['clone'] ) {
		$sanitized_value = ditty_sanitize_clone( $field, $values );
	} elseif( 'group' == $field['type'] ) {
		$sanitized_value = ditty_sanitize_group( $field, $values );
	} else {
		$sanitized_value = ditty_sanitize_input( $field, $values );
	}
	return $sanitized_value;
}

/**
 * Sanitize fields
 *
 * @access  public
 * @since   3.0.16
 */
function ditty_sanitize_fields( $fields = array(), $values = array(), $id = '' ) {
	$sanitized_values = array();
	if ( is_array( $fields ) && count( $fields ) > 0 ) {
		foreach ( $fields as $field ) {
			if ( ! is_array( $field ) ) {
				continue;
			}
			if ( 'group' == $field['type'] && isset( $field['multiple_fields'] ) && true == $field['multiple_fields'] ) {
				if ( isset( $field['fields'] ) && is_array( $field['fields'] ) && count( $field['fields'] ) > 0 ) {
					foreach ( $field['fields'] as $group_field ) {
						if ( ! isset( $values[$group_field['id']] ) ) {
							continue;
						}
						$sanitized_values[$group_field['id']] = ditty_sanitize_field( $group_field, $values[$group_field['id']] );
					}
				}
			} else {
				if ( ! isset( $values[$field['id']] ) ) {
					continue;
				}
				$sanitized_values[$field['id']] = ditty_sanitize_field( $field, $values[$field['id']] );
			}
		}		
	}
	return apply_filters( 'ditty_sanitize_fields', $sanitized_values, $fields, $values, $id );
}