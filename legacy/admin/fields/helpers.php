<?php
	
/* --------------------------------------------------------- */
/* !Create the metaboxes - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_metabox') ) {
function mtphr_dnt_metabox( $id='', $fields=false ) {
	
	if( $id == '' || !$fields ) {
		return;
	}
	
	echo '<div id="'.esc_attr($id).'" class="mtphr-dnt-metabox">';
	
	// Loop through the fiels
	if( is_array($fields) && count($fields) > 0 ) {
		foreach( $fields as $i=>$field ) {
			
			// Set some default values
			$defaults = array(
				'id' => '',
				'heading' => '',
				'description' => '',
				'help' => '',
				'type' => '',
				'name' => '',
				'value' => ''
			);
			$field = wp_parse_args( $field, $defaults );
			
			$id = (isset($field['id']) && $field['id'] != '') ? ' id="'.esc_attr($field['id']).'"' : '';
			echo '<div'.$id.' class="mtphr-dnt-field-group mtphr-dnt-clearfix">';
			
				// Create the label
				echo mtphr_dnt_heading( $field['heading'], $field['description'], $field['help'] );
				
				// Create the field
				if( function_exists('mtphr_dnt_field_'.$field['type']) ) {
					call_user_func( 'mtphr_dnt_field_'.$field['type'], $field );
				}
				
			echo '</div>';

		}
	}
	
	echo '</div>';
	
}
}

	
/* --------------------------------------------------------- */
/* !Return re-formatted classes - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_class_main') ) {
function mtphr_dnt_field_class_main( $class ) {
	
	$class = preg_replace( '%\[%', '_', $class );
	$class = preg_replace( '%\]\[%', '_', $class );
	$class = preg_replace( '%\]%', '', $class );
	$class = ltrim( $class, '_' );
	
	return esc_attr( $class );
}
}
if( !function_exists('mtphr_dnt_field_class') ) {
function mtphr_dnt_field_class( $field ) {
	
	if( !isset($field['name']) || !isset($field['type']) ) {
		return;
	}
	
	$name = $field['name'];
	$type = $field['type'];
	$class = isset($args['class']) ? $field['class'] : $field['name'];
		
	return 'mtphr-dnt-field mtphr-dnt-field-type-'.$type.' mtphr-dnt-field-'.mtphr_dnt_field_class_main( $class );
}
}
if( !function_exists('mtphr_dnt_list_item_class') ) {
function mtphr_dnt_list_item_class( $field ) {
	
	if( !isset($field['name']) || !isset($field['type']) ) {
		return;
	}
	
	$name = $field['name'];
	$type = $field['type'];
	$class = isset($args['class']) ? $field['class'] : $field['name'];
	
	return 'mtphr-dnt-list-field mtphr-dnt-list-field-type-'.$type.' mtphr-dnt-list-field-'.mtphr_dnt_field_class_main( $class );
}
}
if( !function_exists('mtphr_dnt_sort_item_class') ) {
function mtphr_dnt_sort_item_class( $field ) {
	
	if( !isset($field['name']) || !isset($field['type']) ) {
		return;
	}
	
	$name = $field['name'];
	$type = $field['type'];
	$class = isset($args['class']) ? $field['class'] : $field['name'];
	
	return 'mtphr-dnt-sort-field mtphr-dnt-sort-field-type-'.$type.' mtphr-dnt-sort-field-'.mtphr_dnt_field_class_main( $class );
}
}


/* --------------------------------------------------------- */
/* !Return a list of attributes - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_atts') ) {
function mtphr_dnt_field_atts( $field ) {
	
	$html = '';
	
	if( isset($field['atts']) ) {
		if( is_array($field['atts']) && count($field['atts']) > 0 ) {
			foreach( $field['atts'] as $i=>$att ) {
				$html .= esc_attr($i).'="'.esc_attr($att).'" ';
			}
		}
	}
	
	return $html;
}
}


/* --------------------------------------------------------- */
/* !Create a metabox heading - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_heading') ) {
function mtphr_dnt_heading( $heading='', $description='', $help='' ) {
	
	if( $heading == '' && $description == '' ) {
		return;	
	}
	
	$html = '';
	$html .= '<div class="mtphr-dnt-heading mtphr-dnt-clearfix">';
		if( $heading != '' ) {
			$html .= '<h3>'.$heading.'</h3>';
		}
		if( $help != '' ) {
			$html .= '<a href="#" class="mtphr-dnt-help" data-tooltip="'.$help.'"><i class="dashicons dashicons-editor-help"></i></a>';
		}
		if( $description != '' ) {
			$html .= '<p>'.$description.'</p>';
		}
	$html .= '</div>';
	
	return $html;
}
}


/* --------------------------------------------------------- */
/* !Create a metabox subheading - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_subheading') ) {
function mtphr_dnt_subheading( $args=array() ) {
	
	if( isset($args['subheading']) && $args['subheading'] != '' ) {
		
		$html = '<div class="mtphr-dnt-subheading">';
			$html .= '<span class="mtphr-dnt-subheading-label">'.$args['subheading'].'</span>';
			if( isset($args['subhelp']) && $args['subhelp'] != '' ) {
				$html .= '<a href="#" class="mtphr-dnt-help" data-tooltip="'.$args['subhelp'].'"><i class="dashicons dashicons-editor-help"></i></a>';
			}
		$html .= '</div>';
		
		return $html;
	
	}
}
}


/* --------------------------------------------------------- */
/* !Append fields to another field - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_append') ) {
function mtphr_dnt_field_append( $parent_field ) {
	
	if( isset($parent_field['append']) && is_array($parent_field['append']) ) {
		if( is_array($parent_field['append']) && count($parent_field['append']) > 0 ) {
			foreach( $parent_field['append'] as $i=>$field ) {
				
				// Create the field
				if( function_exists('mtphr_dnt_field_'.$field['type']) ) {
					
					$field['subheading'] = isset($field['heading']) ? $field['heading'] : '';
					$field['subhelp'] = isset($field['help']) ? $field['help'] : '';
					
					call_user_func( 'mtphr_dnt_field_'.$field['type'], $field );
				}
				
			}
		}
	}
}
}


/* --------------------------------------------------------- */
/* !Render a single image - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_render_single_image') ) {
function mtphr_dnt_render_single_image( $id, $title = true ) {

	$img = get_post( $id );
	echo '<span class="mtphr-dnt-single-image">';
		echo wp_get_attachment_image( $id, array( 300, 80 ) );
		if( $title ) {
			echo '<span class="mtphr-dnt-single-image-title">'.$img->post_title.'</span>';
		}
		echo '<a href="#" class="mtphr-dnt-delete"><i class="dashicons dashicons-no"></i></a>';
	echo '</span>';

}
}


/* --------------------------------------------------------- */
/* !Setup the animation selects - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_animate_select') ) {
function mtphr_dnt_animate_select( $name, $value, $default=false ) {

	$options = array(
		'Attention Seekers' => array(
			'bounce',
			'flash',
			'pulse',
			'rubberBand',
			'shake',
			'swing',
			'tada',
			'wobble'
		),
		'Bouncing Entrances' => array(
			'bounceIn',
			'bounceInDown',
			'bounceInLeft',
			'bounceInRight',
			'bounceInUp'
		),	
		'Bouncing Exits' => array(
			'bounceOut',
			'bounceOutDown',
			'bounceOutLeft',
			'bounceOutRight',
			'bounceOutUp'
		),	
		'Fading Entrances' => array(
			'fadeIn',
			'fadeInDown',
			'fadeInDownBig',
			'fadeInLeft',
			'fadeInLeftBig',
			'fadeInRight',
			'fadeInRightBig',
			'fadeInUp',
			'fadeInUpBig'
		),
		'Fading Exits' => array(
			'fadeOut',
			'fadeOutDown',
			'fadeOutDownBig',
			'fadeOutLeft',
			'fadeOutLeftBig',
			'fadeOutRight',
			'fadeOutRightBig',
			'fadeOutUp',
			'fadeOutUpBig'
		),
		'Flippers' => array(
			'flip',
			'flipInX',
			'flipInY',
			'flipOutX',
			'flipOutY'
		),	
		'Lightspeed' => array(
			'lightSpeedIn',
			'lightSpeedOut'
		),
		'Rotating Entrances' => array(
			'rotateIn',
			'rotateInDownLeft',
			'rotateInDownRight',
			'rotateInUpLeft',
			'rotateInUpRight'
		),
		'Rotating Exits' => array(
			'rotateOut',
			'rotateOutDownLeft',
			'rotateOutDownRight',
			'rotateOutUpLeft',
			'rotateOutUpRight'
		),	
		'Specials' => array(
			'hinge',
			'rollIn',
			'rollOut'
		),
		'Zoom Entrances' => array(
			'zoomIn',
			'zoomInDown',
			'zoomInLeft',
			'zoomInRight',
			'zoomInUp'
		),
		'Zoom Exits' => array(
			'zoomOut',
			'zoomOutDown',
			'zoomOutLeft',
			'zoomOutRight',
			'zoomOutUp'
		)
	);
	
	$html = '';
	$html .= '<select name="'.$name.'">';
		if( $default ) {
			$html .= '<option value="" '.selected('', $value, false).'>'.__('Use Default Value', 'ditty-news-ticker').'</option>';
		}
		$html .= '<option value="none" '.selected('none', $value, false).'>'.__('None', 'ditty-news-ticker').'</option>';
		if( is_array($options) && count($options) > 0 ) {
			foreach( $options as $label=>$group ) {
				$html .= '<optgroup label="'.$label.'">';
					if( is_array($group) && count($group) > 0 ) {
						foreach( $group as $i=>$option ) {
							$html .= '<option value="'.$option.'" '.selected($option, $value, false).'>'.$option.'</option>';
						}
					}
				$html .= '</optgroup>';
			}
		}
	$html .= '</select>';
	
	return $html;
}
}


/* --------------------------------------------------------- */
/* !List helper functions - 2.1.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_list_heading') ) {
function mtphr_dnt_list_heading( $name, $fields=array(), $val=false ) {

	$classes = array();
	$classes[] = 'mtphr-dnt-list-heading';
	$classes[] = 'mtphr-dnt-clearfix';
	$classes = apply_filters( 'mtphr_dnt_list_heading_class', $classes,  $name, $fields, $val );
	if( !empty( $class ) ) {
		if( !is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}
	$classes = array_map( 'esc_attr', $classes );

	$html = '<div class="'.join( ' ', $classes ).'">';
		ob_start();
		do_action( 'mtphr_dnt_list_heading', $name, $fields, $val );
		$html .= ob_get_clean();
	$html .= '</div>';
	return $html;
}
}

if( !function_exists('mtphr_dnt_list_item') ) {
function mtphr_dnt_list_item( $name, $fields=array(), $val=false ) {
	
	$classes = array();
	$classes[] = 'mtphr-dnt-list-item';
	$classes[] = 'mtphr-dnt-clearfix';
	$classes = apply_filters( 'mtphr_dnt_list_item_class', $classes, $name, $fields, $val );
	if( !empty( $class ) ) {
		if( !is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}
	$classes = array_map( 'esc_attr', $classes );
	
	ob_start();

	echo '<div class="'.join( ' ', $classes ).'">';
		echo mtphr_dnt_list_heading( $name, $fields, $val );
			echo '<div class="mtphr-dnt-list-item-contents">';
			// If this is a single field
			if( isset($fields['type']) && !is_array($fields['type']) ) {
				
				// Create the field
				if( function_exists('mtphr_dnt_field_'.$fields['type']) ) {
					$fields['subheading'] = isset($fields['heading']) ? $fields['heading'] : '';
					$fields['subhelp'] = isset($fields['help']) ? $fields['help'] : '';
					$fields['name'] = $name.'[]';
					$fields['value'] = $val;
					call_user_func( 'mtphr_dnt_field_'.$fields['type'], $fields );	
				}
			
			// If this is multiple fields
			} else {
					
				if( is_array($fields) && count($fields) > 0 ) {
					foreach( $fields as $fname=>$field ) {
						
						// Set some default values
						$defaults = array(
							'heading' => '',
							'type' => '',
							'help' => ''
						);
						$field = wp_parse_args( $field, $defaults );
		
						// Append the field data
						$field['name'] = $name.'['.$fname.']';
						$field['value'] = isset($val[$fname]) ? $val[$fname] : '';
						$field['atts'] = array(
							'data-name' => $name,
							'data-key' => $fname
						);
											
						// Set a field class
						$class = mtphr_dnt_list_item_class( $field );
						echo '<div class="'.$class.'">';
						
							// Create the field
							if( function_exists('mtphr_dnt_field_'.$field['type']) ) {
								$field['subheading'] = isset($field['heading']) ? $field['heading'] : '';
								$field['subhelp'] = isset($field['help']) ? $field['help'] : '';
								call_user_func( 'mtphr_dnt_field_'.$field['type'], $field );	
							}
							
						echo '</div>';
					}
				}
			}
	
		echo '</div>';
	echo '</div>';	

	echo  apply_filters( 'mtphr_dnt_list_item', ob_get_clean(), $name, $fields, $val, $classes );
}
}


/* --------------------------------------------------------- */
/* !Sort helper functions - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_sort_checkbox') ) {
function mtphr_dnt_sort_checkbox( $name, $val, $optional_fields=false, $args=array() ) {

	$heading = isset($args['heading']) ? $args['heading'] : '';
	$help = isset($args['help']) ? $args['help'] : '';
	$optional = $optional_fields ? ' optional' : '';
	$val = $optional_fields ? $val : 'on';
	
	$html = '';
	$html .= '<div class="mtphr-dnt-sort-heading'.$optional.'">';
		$html .= '<i class="dashicons dashicons-menu"></i>';
		$html .= '<div href="#" class="mtphr-dnt-sort-heading-title">';
		if( $optional_fields ) {
			$html .= '<i class="dashicons dashicons-yes"></i> ';
		}
		$html .= $heading.'</div>';
		if( $help != '' ) {
			$html .= '<span class="mtphr-dnt-help" data-tooltip="'.$help.'"><i class="dashicons dashicons-editor-help"></i></span>';
		}
		$html .= '<input type="hidden" name="'.$name.'" value="'.$val.'" />';
	$html .= '</div>';
	return $html;
}
}


/* --------------------------------------------------------- */
/* !Select helper functions - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_select_help') ) {
function mtphr_dnt_select_help( $option ) {
	
	$label_split = explode(':|:', $option);
	$label = $label_split[0];
	$help = '';
	if( count($label_split) == 2 ) {
		$help = ' data-help="'.$label_split[1].'"';
	}
	
	return array(
		'label' => $label,
		'help' => $help
	);
}
}
