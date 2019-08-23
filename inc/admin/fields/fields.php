<?php


/* --------------------------------------------------------- */
/* !Container - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_container') ) {
function mtphr_dnt_field_container( $args=array() ) {
	mtphr_dnt_field_append( $args );
}
}



/* --------------------------------------------------------- */
/* !HTML - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_html') ) {
function mtphr_dnt_field_html( $args=array() ) {
	
	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		
		echo '<div class="'.$class.'">';
			echo $value;
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Number - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_number') ) {
function mtphr_dnt_field_number( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$width = isset($args['width']) ? 'style="width:'.sanitize_text_field($args['width']).'"' : '';
		$before = isset($args['before']) ? $args['before'].' ' : '';
		$after = isset($args['after']) ? ' '.$args['after'] : '';
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<label>'.$before.'<input type="number" name="'.$name.'" value="'.$value.'" '.$width.' '.mtphr_dnt_field_atts($args).' />'.$after.'</label>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Select - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_select') ) {
function mtphr_dnt_field_select( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$options = isset($args['options']) ? $args['options'] : '';
		$option_keys = isset($args['option_keys']) ? $args['option_keys'] : true;
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<select name="'.$name.'" '.mtphr_dnt_field_atts($args).'>';
				if( is_array($options) && count($options) > 0 ) {
					foreach( $options as $i=>$option ) {
						if( is_array($option) ) {
							echo '<optgroup label="'.$i.'">';
							foreach( $option as $e=>$suboption ) {
								$data = mtphr_dnt_select_help( $suboption );
								$v = $option_keys ? $e : $data['label'];
								echo '<option value="'.$v.'" '.selected($v, $value, false).''.$data['help'].'>'.$data['label'].'</option>';
							}
							echo '</optgroup>';
						} else {
							$data = mtphr_dnt_select_help( $option );
							$v = $option_keys ? $i : $data['label'];
							echo '<option value="'.$v.'" '.selected($v, $value, false).''.$data['help'].'>'.$data['label'].'</option>';
						}
					}
				}
			echo '</select>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Post order selects - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_post_order') ) {
function mtphr_dnt_field_post_order( $args=array() ) {

	if( isset($args['name']) && isset($args['order_name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$order_name = $args['order_name'];
		$order_value = isset($args['order_value']) ? $args['order_value'] : '';
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<select name="'.$name.'" '.mtphr_dnt_field_atts($args).'>';
				echo '<option value="ID" '.selected('ID', $value, false).'>'.__('ID', 'ditty-news-ticker').'</option>';
				echo '<option value="author" '.selected('author', $value, false).'>'.__('Author', 'ditty-news-ticker').'</option>';
				echo '<option value="title" '.selected('title', $value, false).'>'.__('Title', 'ditty-news-ticker').'</option>';
				echo '<option value="name" '.selected('name', $value, false).'>'.__('Name', 'ditty-news-ticker').'</option>';
				echo '<option value="date" '.selected('date', $value, false).'>'.__('Date', 'ditty-news-ticker').'</option>';
				echo '<option value="modified" '.selected('modified', $value, false).'>'.__('Modified', 'ditty-news-ticker').'</option>';
				echo '<option value="parent" '.selected('parent', $value, false).'>'.__('Parent', 'ditty-news-ticker').'</option>';
				echo '<option value="rand" '.selected('rand', $value, false).'>'.__('Random', 'ditty-news-ticker').'</option>';
				echo '<option value="comment_count" '.selected('comment_count', $value, false).'>'.__('Comment Count', 'ditty-news-ticker').'</option>';
				echo '<option value="menu_order" '.selected('menu_order', $value, false).'>'.__('Menu Order', 'ditty-news-ticker').'</option>';
			echo '</select>';
			echo '<select name="'.$order_name.'">';
				echo '<option value="ASC" '.selected('ASC', $order_value, false).'>'.__('Ascending', 'ditty-news-ticker').'</option>';
				echo '<option value="DESC" '.selected('DESC', $order_value, false).'>'.__('Descending', 'ditty-news-ticker').'</option>';
			echo '</select>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Textarea - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_textarea') ) {
function mtphr_dnt_field_textarea( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$placeholder = isset($args['placeholder']) ? 'placeholder="'.$args['placeholder'].'" ' : '';
		$cols = isset($args['cols']) ? $args['cols'] : 60;
		$rows = isset($args['rows']) ? $args['rows'] : 4;
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'" '.$placeholder.mtphr_dnt_field_atts($args).'>'.$value.'</textarea>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Text - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_text') ) {
function mtphr_dnt_field_text( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$placeholder = isset($args['placeholder']) ? 'placeholder="'.$args['placeholder'].'" ' : '';
		$width = isset($args['width']) ? ' style="width:'.$args['width'].';"' : '';
		$before = isset($args['before']) ? $args['before'].' ' : '';
		$after = isset($args['after']) ? ' '.$args['after'] : '';

		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			if( $before != '' || $after != '' ) {
				echo '<label>'.$before;
			}
			echo '<input type="text" name="'.$name.'" value="'.$value.'"'.$width.' '.$placeholder.mtphr_dnt_field_atts($args).' />';
			if( $before != '' || $after != '' ) {
				echo $after.'</label>';
			}
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !WYSIWYG */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_wysiwyg') ) {
function mtphr_dnt_field_wysiwyg( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		
		$editor_settings = array(
			'textarea_name' => $name,
			'textarea_rows' => 10
		);
		if( isset($args['editor_settings']) && is_array($args['editor_settings']) ) {
			$editor_settings = wp_parse_args($args['editor_settings'], $editor_settings);
		};
		
		echo '<div class="'.$class.'"'.mtphr_dnt_field_atts($args).'>';
			echo mtphr_dnt_subheading( $args );
			wp_editor( $value, uniqid(), $editor_settings );
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Codemirror - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_codemirror') ) {
function mtphr_dnt_field_codemirror( $args=array() ) {

	if( isset($args['name']) && isset($args['modes']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$cols = isset($args['cols']) ? $args['cols'] : 60;
		$rows = isset($args['rows']) ? $args['rows'] : 4;		
		$modes = isset($args['modes']) ? $args['modes'] : '';
		
		$mode_classes = 'mtphr-dnt-codemirror';
		if( is_array($modes) && count($modes) > 0 ) {
			foreach( $modes as $i=>$mode ) {
				$mode_classes .= ' mtphr-dnt-codemirror-'.$mode;
			}
		}
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<div class="'.$mode_classes.'">';
				echo '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'" '.mtphr_dnt_field_atts($args).'>'.$value.'</textarea>';
			echo '</div>';		
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Checkbox - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_checkbox') ) {
function mtphr_dnt_field_checkbox( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$value = ($value == '1') ? 'on' : $value;
		$label = isset($args['label']) ? $args['label'] : '';
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<label><input type="checkbox" name="'.$name.'" value="on" '.checked('on', $value, false).' '.mtphr_dnt_field_atts($args).' /> '.$label.'</label>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Checkboxes - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_checkboxes') ) {
function mtphr_dnt_field_checkboxes( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : array();
		$options = isset($args['options']) ? $args['options'] : '';
		
		echo '<div class="'.$class.' mtphr-dnt-clearfix">';
			echo mtphr_dnt_subheading( $args );
			if( is_array($options) && count($options) > 0 ) {
				foreach( $options as $i=>$option ) {
					$on = in_array($i, $value) ? $i : '';
					echo '<label><input type="checkbox" name="'.$name.'['.$i.']" value="'.$i.'" '.checked($i, $on, false).' '.mtphr_dnt_field_atts($args).' /> '.$option.'</label>';
				}
			}
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Radio buttons - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_radio_buttons') ) {
function mtphr_dnt_field_radio_buttons( $args=array() ) {

	if( isset($args['name']) && isset($args['options']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$options = isset($args['options']) ? $args['options'] : '';
		
		echo '<div class="'.$class.' mtphr-dnt-clearfix">';
			echo mtphr_dnt_subheading( $args );
			if( is_array($options) && count($options) > 0 ) {
				foreach( $options as $i=>$option ) {
					echo '<label><input type="radio" name="'.$name.'" value="'.$i.'" '.checked($i, $value, false).' '.mtphr_dnt_field_atts($args).' />&nbsp;'.$option.'</label>';
				}
			}
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Menu select - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_menu_select') ) {
function mtphr_dnt_field_menu_select( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$default = isset($args['default']) ? true : false;
		$menus = get_terms( 'nav_menu' );
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<select name="'.$name.'" '.mtphr_dnt_field_atts($args).'>';
				if( $default ) {
					echo '<option value="default" '.selected( 'default', $value, false ).'>'.__('Use default', 'ditty-news-ticker').'</option>';
				}
				echo '<option value="none" '.selected( 'none', $value, false ).'>'.__('None', 'ditty-news-ticker').'</option>';
				if( is_array($menus) && count($menus) > 0 ) {
					foreach( $menus as $i=>$menu ) {
						echo '<option value="'.$menu->slug.'" '.selected( $menu->slug, $value, false ).'>'.$menu->name.'</option>';
					}
				}
			echo '</select>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}



/* --------------------------------------------------------- */
/* !Widget area select - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_widget_area_select') ) {
function mtphr_dnt_field_widget_area_select( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		$default = isset($args['default']) ? true : false;
		$sidebars = $GLOBALS['wp_registered_sidebars'];
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<select name="'.$name.'" '.mtphr_dnt_field_atts($args).'>';
				if( $default ) {
					echo '<option value="default">'.__('Use default', 'ditty-news-ticker').'</option>';
				}
				if( is_array($sidebars) && count($sidebars) > 0 ) {
					foreach( $sidebars as $slug=>$sidebar ) {
						echo '<option value="'.$slug.'" '.selected( $slug, $value, false ).'>'.$sidebar['name'].'</option>';
					}
				}
			echo '</select>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Single image - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_image') ) {
function mtphr_dnt_field_image( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<input type="hidden" name="'.$name.'" value="'.$value.'" '.mtphr_dnt_field_atts($args).' />';
			if( $value != '' ) {
				mtphr_dnt_render_single_image( $value );
				echo '<a href="#" class="button mtphr-dnt-single-image-upload" style="display:none;">'.__('Add Image', 'ditty-news-ticker').'</a>';
			} else {
				echo '<a href="#" class="button mtphr-dnt-single-image-upload">'.__('Add Image', 'ditty-news-ticker').'</a>';
			}
		echo '</div>';
		
		mtphr_dnt_field_append( $args );

	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Color picker & opacity - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_minicolors') ) {
function mtphr_dnt_field_minicolors( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		$value = isset($args['value']) ? $args['value'] : '';
		
		$opacity_name = isset($args['opacity_name']) ? $args['opacity_name'] : '';
		$default = isset($args['default']) ? true : false;
		if( $default ) {
			$opacity_value = (isset($args['opacity_value']) && $args['opacity_value'] != '' ) ? $args['opacity_value'] : '';
		} else {
			$opacity_value = (isset($args['opacity_value']) && $args['opacity_value'] != '' ) ? $args['opacity_value'] : 100;
		}
		$default_checked = ($opacity_value != '') ? 'checked="checked"' : '';
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<table class="mtphr-dnt-minicolors-table">';
				echo '<tr>';
					echo '<td class="mtphr-dnt-minicolors-td">';
						echo '<div class="mtphr-dnt-minicolors">';
							echo '<input type="text" size="24" name="'.$name.'" value="'.$value.'" />';
						echo '</div>';
					echo '</td>';
					if( $opacity_name != '' ) {
						echo '<td class="mtphr-dnt-ui-slider-label-td">';
							if( $default ) {
								echo '<label><input type="checkbox" name="'.$opacity_name.'" value="'.$opacity_value.'" '.$default_checked.' />'.__('Override default opacity', 'ditty-news-ticker').'</label>';
							} else {
								echo '<label>'.__('Opacity', 'ditty-news-ticker').'</label>';
								echo '<input type="hidden" name="'.$opacity_name.'" value="'.$opacity_value.'" '.mtphr_dnt_field_atts($args).' />';
							}
						echo '</td>';
						echo '<td class="mtphr-dnt-ui-slider-td">';
							echo '<div class="mtphr-dnt-opacity-slider"></div>';
						echo '</td>';
						echo '<td class="mtphr-dnt-ui-slider-value-td">';
							echo '<span>'.$opacity_value.'%</span>';
						echo '</td>';
					}
				echo '</tr>';
			echo '</table>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );
		
	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !WOW Animations - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_wow_animations') ) {
function mtphr_dnt_field_wow_animations( $args=array() ) {

	if( isset($args['name']) || isset($args['content_name']) || isset($args['sidebar_name']) ) {
		
		$name = isset($args['name']) ? $args['name'] : '';
		$value = isset($args['value']) ? $args['value'] : '';
		
		$content_name = isset($args['content_name']) ? $args['content_name'] : '';
		$content_value = isset($args['content_value']) ? $args['content_value'] : '';
		
		$default = isset($args['default']) ? true : false;
		
		$class = mtphr_dnt_field_class( $args );
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<table class="mtphr-dnt-wow-animations-table">';
				echo '<tr>';
					echo '<th class="mtphr-dnt-wow-animations-heading"><small>'.__('Element', 'ditty-news-ticker').'</small></th>';
					echo '<th><small>'.__('Animation', 'ditty-news-ticker').'</small></th>';
					echo '<th><small>'.__('Delay', 'ditty-news-ticker').'</small></th>';
				echo '</tr>';
				
				if( $name != '' ) {
					$enabled = isset($value['enabled']) ? $value['enabled'] : '';
					$delay = isset($value['delay']) ? $value['delay'] : '';
					$animation = isset($value['animation']) ? $value['animation'] : '';
					echo '<tr>';
						echo '<th class="mtphr-dnt-wow-animations-heading">';
							echo __('Header', 'ditty-news-ticker');
						echo '</th>';
						echo '<td>';
							echo mtphr_dnt_animate_select($name.'[animation]', $animation, $default);
						echo '</td>';
						echo '<td>';
							echo '<label><input type="text" size="3" name="'.$name.'[delay]" value="'.$delay.'" /> '.__('seconds', 'ditty-news-ticker').'</label>';
						echo '</td>';
					echo '</tr>';
				}
				if( $content_name != '' ) {
					$enabled = isset($content_value['enabled']) ? $content_value['enabled'] : '';
					$delay = isset($content_value['delay']) ? $content_value['delay'] : '';
					$animation = isset($content_value['animation']) ? $content_value['animation'] : '';
					echo '<tr>';
						echo '<th class="mtphr-dnt-wow-animations-heading">';
							echo __('Content', 'ditty-news-ticker');
						echo '</th>';
						echo '<td>';
							echo mtphr_dnt_animate_select($content_name.'[animation]', $animation, $default);
						echo '</td>';
						echo '<td>';
							echo '<label><input type="text" size="3" name="'.$content_name.'[delay]" value="'.$delay.'" /> '.__('seconds', 'ditty-news-ticker').'</label>';
						echo '</td>';
					echo '</tr>';
				}
				
			echo '</table>';
		echo '</div>';
		
		mtphr_dnt_field_append( $args );
		
	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !List - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_list') ) {
function mtphr_dnt_field_list( $args=array() ) {

	$html = '';
	
	if( isset($args['name']) && isset($args['fields']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		
		$fields = $args['fields'];
		$value = isset($args['value']) ? $args['value'] : '';
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<div class="mtphr-dnt-list">';
				
				// Check for labels
				$total_fields = count($fields);
				$counter = 1;

				// Display the fields
				if( is_array($value) && count($value) > 0 ) {
					foreach( $value as $i=>$val ) {
						mtphr_dnt_list_item( $name, $fields, $val );
					}
				} else {
					mtphr_dnt_list_item( $name, $fields );
				}
		
			echo '</div>';	
		echo '</div>';	
		
		mtphr_dnt_field_append( $args );
		
	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}


/* --------------------------------------------------------- */
/* !Sort - 2.2.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_field_sort') ) {
function mtphr_dnt_field_sort( $args=array() ) {

	$html = '';
	
	if( isset($args['name']) && isset($args['items']) ) {
		
		$name = $args['name'];
		$class = mtphr_dnt_field_class( $args );
		
		$items = $args['items'];
		$value = isset($args['value']) ? $args['value'] : '';
		$optional_fields = isset($args['optional_fields']) ? $args['optional_fields'] : false;
		
		echo '<div class="'.$class.'">';
			echo mtphr_dnt_subheading( $args );
			echo '<div class="mtphr-dnt-sort">';
							
				// Display the fields
				if( is_array($value) && count($value) > 0 ) {
					foreach( $value as $i => $val ) {
					
						$active = ($val == 'on' || !$optional_fields) ? ' active' : ''; 
						
						$fields = isset($items[$i]['fields']) ? $items[$i]['fields'] : '';
						$has_fields = ( is_array( $fields ) && count( $fields ) > 0 ) ? ' has-fields' : '';
						
						echo '<div class="mtphr-dnt-sort-item'.$active.$has_fields.'">';
							echo mtphr_dnt_sort_checkbox( $name.'['.$i.']', $val, $optional_fields, $items[$i] );

							if( is_array( $fields ) && count( $fields ) > 0 ) {
								echo '<div class="mtphr-dnt-sort-item-fields'.$active.' mtphr-dnt-clearfix">';
								foreach( $fields as $fname=>$field ) {

									// Set some default values
									$defaults = array(
										'heading' => '',
										'type' => '',
										'help' => ''
									);
									$field = wp_parse_args( $field, $defaults );
									
									// Set a field class
									$class = mtphr_dnt_sort_item_class( $field );
									echo '<div class="'.$class.'">';
									
										// Create the field
										if( function_exists('mtphr_dnt_field_'.$field['type']) ) {
											$field['subheading'] = isset($field['heading']) ? $field['heading'] : '';
											$field['subhelp'] = isset($field['help']) ? $field['help'] : '';
											call_user_func( 'mtphr_dnt_field_'.$field['type'], $field );	
										}
										
									echo '</div>';									
								}
								echo '</div>';
							}

						echo '</div>';
						
					}
				}
		
			echo '</div>';	
		echo '</div>';	
		
		mtphr_dnt_field_append( $args );
		
	} else {
		echo __('Missing required data', 'ditty-news-ticker');
	}
}
}

