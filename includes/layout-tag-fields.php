<?php

/**
 * Add fields to modify layout tag attributes
 *
 * @access  public
 * @since   3.0.21
 */
function ditty_item_type_layout_tag_fields( $fields, $item_type, $values ) {
	$all_tags = ditty_layout_tags( $item_type->get_type() );
	$item_type_tags = $item_type->layout_tags();
	$layout_tags = array_intersect_key( $all_tags, array_flip( $item_type_tags ) );
	
	if ( is_array( $layout_tags ) && count( $layout_tags ) > 0 ) {
		foreach ( $layout_tags as $tag => $data ) {
			$atts = isset( $data['atts'] ) ? $data['atts'] : array();
			if ( ! is_array( $atts ) || count( $atts ) < 1 ) {
				continue;
			}	
				
			$attribute_fields = array();
			foreach ( $atts as $att => $default ) {
				$name = ucwords( implode( ' ', explode( '_', $att ) ) );
				$field = array(
					'type'			=> 'text',
					'id'				=> $att,
					'name'			=> $name,
					'help'			=> sprintf( esc_html__( "Customize the '%s' attribute.", 'ditty-news-ticker' ), $att ),
					'std'				=> isset( $values[$att] ) ? $values[$att] : false,
				);
				switch( $att ) {
					case 'wrapper':
						$field['type'] 		= 'select';
						$field['options'] = ditty_layout_wrapper_options();
						break;
					case 'fit':
						$field['type'] 		= 'select';
						$field['options'] = ditty_layout_object_fit_options();
					break;
					case 'link_target':
						$field['type'] 		= 'select';
						$field['options'] = ditty_layout_link_target_options();
					break;
					case 'ago':
						$field['type'] 		= 'select';
						$field['options'] = array(
							'' 		=>  esc_html__( 'Use layout value', 'ditty-news-ticker' ),
							'no' 	=>  esc_html__( 'No', 'ditty-news-ticker' ),
							'yes' =>  esc_html__( 'Yes', 'ditty-news-ticker' ),
						);
					break;
					default:
						break;
				}
				$attribute_fields[$att] = $field;
			}
			
			//$tag_name = sprintf( esc_html__( '%s Settings', 'ditty-news-ticker' ), ucwords( implode( ' ', explode( '_', $tag ) ) ) );
			$tag_name = ucwords( implode( ' ', explode( '_', $tag ) ) );
			$fields["layout_tag_{$tag}"] = array(
				'type' 							=> 'layout_element',
				'tab'								=> esc_html__( 'Layout Elements', 'ditty-news-ticker' ),
				'id'								=> "layout_tag_{$tag}",
				'collapsible'				=> true,
				'default_state'			=> 'collapsed',
				'name' 	=> $tag_name,
				//'help' 	=> esc_html__( 'Configure the display of the author avatar tag.', 'ditty-news-ticker' ),
				'fields' => apply_filters( 'ditty_layout_tag_attribute_fields', $attribute_fields, $tag, $values ),
			);
		}
	}
	return $fields;
}
add_filter( 'ditty_item_type_fields', 'ditty_item_type_layout_tag_fields', 10, 3 );

/**
 * Modify the layout tag atts
 *
 * @access  public
 * @since   3.0.21
 */
function ditty_item_type_layout_tag_atts( $atts, $tag, $item_type, $data ) {
	if ( ! isset( $data["layout_tag_{$tag}"] ) ) {
		return $atts;
	}
	$trimmed_atts = array();
	if ( is_array( $data["layout_tag_{$tag}"] ) && count( $data["layout_tag_{$tag}"] ) > 0 ) {
		foreach ( $data["layout_tag_{$tag}"] as $att => $value ) {
			if ( '' != $value ) {
				$trimmed_atts[$att] = $value;
			}
		}
	}
	$parsed_atts = wp_parse_args( $trimmed_atts, $atts );
	return $parsed_atts;
}
add_filter( 'ditty_layout_tag_atts', 'ditty_item_type_layout_tag_atts', 10, 4 );

/**
 * Return layout wrapper options
 *
 * @access  public
 * @since   3.0.21
 */
function ditty_layout_wrapper_options() {
	$options = array(
		''			=> esc_html__( 'Use layout value', 'ditty-news-ticker' ),
		'none'	=> esc_html__( 'None', 'ditty-news-ticker' ),
		'div'		=> 'div',
		'p'			=> 'p',
		'span'	=> 'span',
		'h1'		=> 'h1',
		'h2'		=> 'h2',
		'h3'		=> 'h3',
		'h4'		=> 'h4',
		'h5'		=> 'h5',
		'h6'		=> 'h6',
	);
	return apply_filters( 'ditty_layout_wrapper_options', $options );
}

/**
 * Return layout object fit options
 *
 * @access  public
 * @since   3.0.21
 */
function ditty_layout_object_fit_options() {
	$options = array(
		'default'			=> esc_html__( 'Use layout value', 'ditty-news-ticker' ),
		'none'				=> esc_html__( 'None', 'ditty-news-ticker' ),
		'fill'				=> 'fill',
		'contain'			=> 'contain',
		'cover'				=> 'cover',
		'scale-down'	=> 'scale-down',
	);
	return apply_filters( 'ditty_layout_object_fit_options', $options );
}

/**
 * Return layout link target options
 *
 * @access  public
 * @since   3.0.21
 */
function ditty_layout_link_target_options() {
	$options = array(
		'default'	=> esc_html__( 'Use layout value', 'ditty-news-ticker' ),
		'_blank'	=> '_blank',
		'_self'		=> '_self',
	);
	return apply_filters( 'ditty_layout_link_target_options', $options );
}