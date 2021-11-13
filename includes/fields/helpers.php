<?php

/**
 * Display a field
 *
 * @access  public
 * @since   3.0
 */
function ditty_field( $args = array() ) {
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
 * @since   3.0
 */
function ditty_fields( $fields = array(), $values = array(), $action = 'render' ) {
	if ( is_array( $fields ) && count( $fields ) > 0 ) {
		foreach ( $fields as &$field ) {
			if ( isset( $values[$field['id']] ) ) {
				$field['std'] = $values[$field['id']];
			}
			
			if ( 'return' == $action ) {
				return ditty_field( $field );
			} else {
				echo ditty_field( $field );
			}
		}
	}
}

/**
 * Sanitize field
 *
 * @access  public
 * @since   3.0
 */
function ditty_sanitize_field( $field = array(), $values = array() ) {
	$sanitize_type = isset( $field['sanitize'] ) ? $field['sanitize'] : $field['type'];
	$value = $values[$field['id']];
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
 * Sanitize field
 *
 * @access  public
 * @since   3.0
 */
function ditty_sanitize_group( $group = array(), $values = array(), $sanitized_values = array() ) {
	if ( isset( $group['fields'] ) && is_array( $group['fields'] ) && count( $group['fields'] ) > 0 ) {
		foreach ( $group['fields'] as $field ) {
			if ( ! isset( $values[$field['id']] ) ) {
				continue;
			}
			$sanitized_values[$field['id']] = ditty_sanitize_field( $field, $values );
		}
	}
	return $sanitized_values;
}

/**
 * Sanitize fields
 *
 * @access  public
 * @since   3.0
 */
function ditty_sanitize_fields( $fields = array(), $values = array() ) {
	$sanitized_values = array();
	if ( is_array( $fields ) && count( $fields ) > 0 ) {
		foreach ( $fields as $field ) {
			if ( 'group' == $field['type'] ) {
				$sanitized_values = ditty_sanitize_group( $field, $values, $sanitized_values );
			} else {
				if ( ! isset( $values[$field['id']] ) ) {
					continue;
				}
				$sanitized_values[$field['id']] = ditty_sanitize_field( $field, $values );
			}
		}
	}
	return apply_filters( 'ditty_sanitize_fields', $sanitized_values, $fields, $values );
}