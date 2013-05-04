<?php
/**
 * Put all the Metaboxer admin function here fields here
 *
 * @package  Ditty News Ticker
 * @author   Metaphor Creations
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 */



/**
 * Create a field container and switch.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_container( $field, $context ) {

	global $post;

	$default = isset( $field['default'] ) ? $field['default'] : '';
	$value = ( get_post_meta( $post->ID, $field['id'], true ) != '' ) ? get_post_meta( $post->ID, $field['id'], true ) : $default;
	$display = isset( $field['display'] ) ? $field['display'] : '';
	?>
	<tr class="mtphr-dnt-metaboxer-field mtphr-dnt-metaboxer-field-<?php echo $field['type']; ?> mtphr-dnt-metaboxer<?php echo $field['id']; ?><?php if( isset($field['class']) ) { echo ' '.$field['class']; } ?> clearfix">

		<?php
		$content_class = 'mtphr-dnt-metaboxer-field-content mtphr-dnt-metaboxer-field-content-full mtphr-dnt-metaboxer-'.$field['type'].' clearfix';
		$content_span = ' colspan="2"';
		$label = false;

		if ( isset($field['name']) || isset($field['description']) ) {

			$content_class = 'mtphr-dnt-metaboxer-field-content mtphr-dnt-metaboxer-'.$field['type'].' clearfix';
			$content_span = '';
			$label = true;
			?>

			<?php if( $context == 'side' || $display == 'vertical' ) { ?><td><table><tr><?php } ?>

			<td class="mtphr-dnt-metaboxer-label">
				<?php if( isset($field['name']) ) { ?><label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label><?php } ?>
				<?php if( isset($field['description']) ) { ?><small><?php echo $field['description']; ?></small><?php } ?>
			</td>

			<?php if( $context == 'side' || $display == 'vertical' ) { echo '</tr>'; } ?>

			<?php
		}
		?>

		<?php if( $label ) { if( $context == 'side' || $display == 'vertical' ) { echo '<tr>'; } } ?>

		<td<?php echo $content_span; ?> class="<?php echo $content_class; ?>" id="<?php echo $post->ID; ?>">
			<?php
			// Call the function to display the field
			if ( function_exists('mtphr_dnt_metaboxer_'.$field['type']) ) {
				call_user_func( 'mtphr_dnt_metaboxer_'.$field['type'], $field, $value );
			}
			?>
		</td>

		<?php if( $label ) { if( $context == 'side' || $display == 'vertical' ) { echo '</tr></table></td>'; } } ?>

	</tr>
	<?php
}




/**
 * Append fields
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_append_field( $field ) {

	// Add appended fields
	if( isset($field['append']) ) {

		$fields = $field['append'];
		$settings = ( isset($field['option'] ) ) ? $field['option'] : false;

		if( is_array($fields) ) {

			foreach( $fields as $id => $field ) {

				// Get the value
				if( $settings) {
					$options = get_option( $settings );
					$value = isset( $options[$id] ) ? $options[$id] : get_option( $id );
				} else {
					global $post;
					$value = get_post_meta( $post->ID, $id, true );
				}

				// Set the default if no value
				if( $value == '' && isset($field['default']) ) {
					$value = $field['default'];
				}

				if( isset($field['type']) ) {

					if( $settings ) {
						$field['id'] = $settings.'['.$id.']';
						$field['option'] = $settings;
					} else {
						$field['id'] = $id;
					}

					// Call the function to display the field
					if ( function_exists('mtphr_dnt_metaboxer_'.$field['type']) ) {
						echo '<div class="mtphr-dnt-metaboxer-appended mtphr-dnt-metaboxer'.$field['id'].'">';
						call_user_func( 'mtphr_dnt_metaboxer_'.$field['type'], $field, $value );
						echo '</div>';
					}
				}
			}
		}
	}
}




/**
 * Renders a checkbox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_checkbox( $field, $value='' ) {

	$output = '';
	$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
	$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';

	if( isset($field['options']) ) {

		$break = '<br/>';
		if ( isset($field['display']) ) {
			if( $field['display'] == 'inline' ) {
				$break = '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}
		foreach( $field['options'] as $i => $option ) {
			$checked = ( isset($value[$i]) ) ? 'checked="checked"' : '';
			$output .= '<label><input name="'.$field['id'].'['.$i.']" id="'.$field['id'].'['.$i.']" type="checkbox" value="1" '.$checked.' /> '.$option.'</label>'.$break;
		}

	} else {

		$checked = ( $value == 1 ) ? 'checked="checked"' : '';
		$output .= '<label><input name="'.$field['id'].'" id="'.$field['id'].'" type="checkbox" value="1" '.$checked.' />';
		if( isset($field['label']) ) {
			$output .= ' '.$field['label'];
		}
		$output .= '</label>';
	}

	echo $before.$output.$after;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders a file attachment
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_file( $field, $value='' ) {

	// Check if there's actually a file
	$file = false;
	if( $value != '' ) {
		$file = get_post( $value );
	}

	// If there isn't a file reset the value
	if( !$file ) {
		$value = '';
	}
	?>

	<input class="mtphr-dnt-metaboxer-file-value" type="hidden" id="<?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>" value="<?php echo $value; ?>" />

	<?php
	echo isset( $field['button'] ) ? '<a href="#" class="button mtphr-dnt-metaboxer-file-upload">'.$field['button'].'</a>' : '<a href="#" class="button custom-media-upload">Insert File</a>';

	if( $file ) {

		$type = explode( '/', $file->post_mime_type );

		// Display the file
		echo mtphr_dnt_metaboxer_file_display( $file->ID, $type[0], $file->guid, $file->post_title, $file -> post_excerpt, $file->post_content );
	}

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}

add_action( 'wp_ajax_mtphr_dnt_metaboxer_ajax_file_display', 'mtphr_dnt_metaboxer_ajax_file_display' );
/**
 * Ajax function used to delete attachments
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_ajax_file_display() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'mtphr_dnt', 'security' );

	// Get variables
	$id  = $_POST['id'];
	$type  = $_POST['type'];
	$url  = $_POST['url'];
	$title  = $_POST['title'];
	$caption  = $_POST['caption'];
	$description  = $_POST['description'];

	// Display the file
	mtphr_dnt_metaboxer_file_display( $id, $type, $url, $title, $caption, $description );

	die(); // this is required to return a proper result
}

// Display the file
function mtphr_dnt_metaboxer_file_display( $id, $type, $url, $title, $caption, $description ) {

	$src = '';
	switch( $type ) {

		case 'image':
			$att = wp_get_attachment_image_src( $id, 'thumbnail' );
			$src = $att[0];
			break;

		case 'application':
			$att = wp_get_attachment_image_src( $id, 'thumbnail', true );
			$src = $att[0];
			break;
	}
	?>
	<table class="mtphr-dnt-metaboxer-file-table">
		<tr>
			<td class="mtphr-dnt-metaboxer-file-display">
				<a href="<?php echo $url; ?>" target="_blank" class="clearfix">
					<img class="custom_media_image" src="<?php echo $src; ?>" />
					<span class="mtphr-dnt-metaboxer-file-title"><strong>Title:</strong> <?php echo $title; ?></span><br/>
					<?php if( $caption != '' ) { ?>
					<span class="mtphr-dnt-metaboxer-file-caption"><strong>Caption:</strong> <?php echo $caption; ?></span><br/>
					<?php }
					if( $description != '' ) { ?>
					<span class="mtphr-dnt-metaboxer-file-description"><strong>Description:</strong> <?php echo $description; ?></span>
					<?php } ?>
				</a>
			</td>
			<td class="mtphr-dnt-metaboxer-file-delete">
				<a href="#"></a>
			</td>
		</tr>
	</table>
	<?php
}



/**
 * Renders an html field.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_html( $field, $value='' ) {

	// Echo the html
	echo $value;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders an image attachment
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_image( $field, $value='' ) {

	// Check if there's actually a file
	$image = false;
	if( $value != '' ) {
		$image = get_post( $value );
	}

	// If there isn't a file reset the value
	if( !$image ) {
		$value = '';
	}
	?>

	<div class="mtphr-dnt-metaboxer-image-contents">
	<input class="mtphr-dnt-metaboxer-image-value" type="hidden" id="<?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>" value="<?php echo $value; ?>" />

	<?php
	echo isset( $field['button'] ) ? '<a href="#" class="button mtphr-dnt-metaboxer-image-upload">'.$field['button'].'</a>' : '<a href="#" class="button mtphr-dnt-metaboxer-image-upload">Insert Image</a>';

	if( $image ) {

		$type = explode( '/', $image->post_mime_type );

		// Display the file
		echo mtphr_dnt_metaboxer_image_display( $image->ID, $type[0], $image->guid, $image->post_title, $image -> post_excerpt, $image->post_content );
	}
	?>

	</div>

	<?php
	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}

add_action( 'wp_ajax_mtphr_dnt_metaboxer_ajax_image_display', 'mtphr_dnt_metaboxer_ajax_image_display' );
/**
 * Ajax function used to delete attachments
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_ajax_image_display() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'neuron', 'security' );

	// Get variables
	$id  = $_POST['id'];
	$type  = $_POST['type'];
	$url  = $_POST['url'];
	$title  = $_POST['title'];
	$caption  = $_POST['caption'];
	$description  = $_POST['description'];

	// Display the file
	mtphr_dnt_metaboxer_image_display( $id, $type, $url, $title, $caption, $description );

	die(); // this is required to return a proper result
}

// Display the file
function mtphr_dnt_metaboxer_image_display( $id, $type, $url, $title, $caption, $description ) {

	$src = '';
	switch( $type ) {

		case 'image':
			$att = wp_get_attachment_image_src( $id, 'thumbnail' );
			$src = $att[0];
			break;

		case 'application':
			$att = wp_get_attachment_image_src( $id, 'thumbnail', true );
			$src = $att[0];
			break;
	}
	?>
	<table class="mtphr-dnt-metaboxer-image-table">
		<tr>
			<td class="mtphr-dnt-metaboxer-image-display">
				<a href="<?php echo esc_url( get_edit_post_link( $id ) ) ?>" target="_blank" class="clearfix">
					<img src="<?php echo $src; ?>" />
				</a>
			</td>
			<td class="mtphr-dnt-metaboxer-image-delete">
				<a href="#"></a>
			</td>
		</tr>
	</table>
	<?php
}



/**
 * Renders an image select
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_image_select( $field, $value='' ) {
	$output = '<input type="hidden" id="'.$field['id'].'" name="'.$field['id'].'" value="'.$value.'" />';
	foreach ( $field['options'] as $option ) {
		$selected = ( $value == $option['value'] ) ? 'selected' : '';
		$output .= '<a class="mtphr-dnt-metaboxer-image-select-link '.$selected.'" href="'.$option['value'].'"><img src="'.$option['path'].'" /><small>'.$option['label'].'</small></a>';
	}
	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders a list.
 *
 * @since 1.0.2
 */
function mtphr_dnt_metaboxer_list( $field, $value='' ) {

	$output = '<table>';

	$headers = false;
	$header_str = '';
	foreach( $field['structure'] as $id => $str ) {

		$header_str .= '<th>';
		if( isset($str['header']) ) {
			$headers = true;
			$header_str .= $str['header'];
		}
		$header_str .= '</th>';
	}
	if( $headers ) {
		$output .= '<tr><td class="mtphr-dnt-metaboxer-list-item-handle"></td>'.$header_str.'</tr>';
	}

	$buttons = '<td class="mtphr-dnt-metaboxer-list-item-delete"><a href="#">Delete</a></td><td class="mtphr-dnt-metaboxer-list-item-add"><a href="#">Add</a></td>';
	if( is_array($value) ) {
		foreach( $value as $i=>$v ) {
			$structure = mtphr_dnt_metaboxer_list_structure( $i, $field, $v );
			$output .= '<tr class="mtphr-dnt-metaboxer-list-item"><td class="mtphr-dnt-metaboxer-list-item-handle"><span></span></td>'.$structure.$buttons.'</tr>';
		}
	}

	// If nothing is being output make sure one field is showing
	if( $value == '' || count($value) == 0 ) {
		$structure = mtphr_dnt_metaboxer_list_structure( 0, $field );
		$output .= '<tr class="mtphr-dnt-metaboxer-list-item"><td class="mtphr-dnt-metaboxer-list-item-handle"><span></span></td>'.$structure.$buttons.'</tr>';
	}

	$output .= '</table>';

	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}

// Add the list structure
function mtphr_dnt_metaboxer_list_structure( $pos, $fields, $m_value='' ) {

	$main_id = $fields['id'];

	// Add appended fields
	if( isset($fields['structure']) ) {

		$fields = $fields['structure'];
		$settings = ( isset($fields['option'] ) ) ? $fields['option'] : false;

		if( is_array($fields) ) {

			ob_start();

			foreach( $fields as $id => $field ) {

				// Get the value
				$value = isset($m_value[$id]) ? $m_value[$id] : '';

				// Get the width
				$width = isset($field['width']) ? ' style="width:'.$field['width'].'"' : '';

				if( isset($field['type']) ) {

					$field['id'] = $main_id.'['.$pos.']['.$id.']';

					// Call the function to display the field
					if ( function_exists('mtphr_dnt_metaboxer_'.$field['type']) ) {

						echo '<td'.$width.' class="mtphr-dnt-metaboxer-list-structure-item mtphr-dnt-metaboxer'.$main_id.'-'.$id.'" base="'.$main_id.'" field="'.$id.'">';
						call_user_func( 'mtphr_dnt_metaboxer_'.$field['type'], $field, $value );
						echo '</td>';
					}
				}
			}

			return ob_get_clean();
		}
	}
}



/**
 * Renders a metabox toggle.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_metabox_toggle( $field, $value='' ) {

	if( isset($field['options']) ) {

		$output = '';
		$output .= '<input type="hidden" id="'.$field['id'].'" name="'.$field['id'].'" value="'.$value.'" />';

		foreach( $field['options'] as $i => $option ) {

			$button = $option['button'];
			$metaboxes = $option['metaboxes'];
			$metabox_list = join( ',', $metaboxes );

			// Create a button
			$selected = ( $value == $i ) ? ' button-primary' : '';
			$output .= '<a href="'.$i.'" metaboxes="'.$metabox_list.'" class="mtphr-dnt-metaboxer-metabox-toggle button'.$selected.'">'.$button.'</a>&nbsp;';
		}

		echo $output;
	}

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders an number field.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_number( $field, $value='' ) {
	$style = ( isset($field['style']) ) ? ' style="'.$field['style'].'"' : '';
	$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
	$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';
	$output = $before.'<input name="'.$field['id'].'" id="'.$field['id'].'" type="number" value="'.$value.'" class="small-text"'.$style.'>'.$after;
	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders a radio custom field.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_radio( $field, $value='' ) {

	if( isset($field['options']) ) {

		$output = '';
		$break = '<br/>';
		if ( isset($field['display']) ) {
			if( $field['display'] == 'inline' ) {
				$break = '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}
		foreach( $field['options'] as $i => $option ) {
			$checked = ( $value == $i ) ? 'checked="checked"' : '';
			$output .= '<label><input name="'.$field['id'].'" id="'.$field['id'].'" type="radio" value="'.$i.'" '.$checked.' /> '.$option.'</label>'.$break;
		}
	}

	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders a select field.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_select( $field, $value='' ) {

	$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
	$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';

	$output = $before.'<select name="'.$field['id'].'" id="'.$field['id'].'">';

  if( $field['options'] ) {

  	$key_val = isset( $field['key_val'] ) ? true : false;

	  foreach ( $field['options'] as $key => $option ) {
	  	if( is_numeric($key) && !$key_val ) {
				$name = ( is_array( $option ) ) ? $option['name'] : $option;
				$val = ( is_array( $option ) ) ? $option['value'] : $option;
			} else {
				$name = $option;
				$val = $key;
			}
			$selected = ( $val == $value ) ? 'selected="selected"' : '';
			$output .= '<option value="'.$val.'" '.$selected.'>'.stripslashes( $name ).'</option>';
		}
	}
  $output .= '</select>'.$after;

	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders a sort.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_sort( $field, $value='' ) {

	global $post;

	$rows = array();
	if( is_array($value) ) {
		foreach( $value as $id ) {
			if( isset($field['rows'][$id]) ) {
				$rows[$id] = $field['rows'][$id];
			}
		}
	} else {
		$rows = $field['rows'];
	}

	foreach( $field['rows'] as $id=>$data ) {
		if( !isset($rows[$id]) ) {
			$rows[$id] = $data;
		}
	}

	$output = '<table>';

	foreach( $rows as $id => $data ) {

		$output .= '<tr class="mtphr-dnt-metaboxer-sort-item"><td class="mtphr-dnt-metaboxer-sort-item-handle"><span></span></td>';
		if( isset($data['name']) ) {
			$output .= '<td class="mtphr-dnt-metaboxer-sort-name">'.$data['name'].'</td>';
		}
		$output .= '<td><input name="'.$field['id'].'[]" id="'.$field['id'].'[]" type="hidden" value="'.$id.'">';

		// Find the value
		$data_value = get_post_meta( $post->ID, $data['id'], true );
		if( $data_value == '' && isset($data['default']) ) {
			$data_value = $data['default'];
		}

		ob_start();
		// Call the function to display the field
		if ( function_exists('mtphr_dnt_metaboxer_'.$data['type']) ) {
			call_user_func( 'mtphr_dnt_metaboxer_'.$data['type'], $data, $data_value );
		}
		$output .= ob_get_clean();

		$output .= '</td>';

		$output .= '</tr>';
	}

	$output .= '</table>';

	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders an text field.
 *
 * @since 1.0.1
 */
function mtphr_dnt_metaboxer_text( $field, $value='' ) {
	$size = ( isset($field['size']) ) ? $field['size'] : 40;
	$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
	$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';
	$text_align = ( isset($field['text_align']) ) ? ' style="text-align:'.$field['text_align'].'"' : '' ;
	$output = $before.'<input name="'.$field['id'].'" id="'.$field['id'].'" type="text" value="'.$value.'" size="'.$size.'"'.$text_align.'>'.$after;
	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders a textarea.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_textarea( $field, $value='' ) {
	$rows = ( isset($field['rows']) ) ? $field['rows'] : 5;
	$cols = ( isset($field['cols']) ) ? $field['cols'] : 40;
	$output = '<textarea name="'.$field['id'].'" id="'.$field['id'].'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea>';
	echo $output;

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}



/**
 * Renders a wysiwyg field.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_wysiwyg( $field, $value='' ) {
	$settings = array();
	$settings['media_buttons'] = true;
	$settings['textarea_rows'] = ( isset($field['rows']) ) ? $field['rows'] : 12;
	wp_editor( $value, $field['id'], $settings );

	// Add appended fields
	mtphr_dnt_metaboxer_append_field($field);
}




/**
 * Renders the code fields.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metaboxer_code( $field, $value='' ) {

	global $post;

	// Display the shortcode code
	if( $field['id'] == '_mtphr_dnt_shortcode' ) {

		echo '<pre><p>[ditty_news_ticker id="'.$post->ID.'"]</p></pre>';

	// Display the function code
	} elseif( $field['id'] == '_mtphr_dnt_function' ) {

		echo '<pre><p>&lt;?php if(function_exists(\'ditty_news_ticker\')){ditty_news_ticker('.$post->ID.');} ?&gt;</p></pre>';
	}

	// Display a "Select All" button
	$button = isset($field['button']) ? $field['button'] : __('Select Code', 'ditty-news-ticker');
	echo '<a href="#" class="button mtphr-dnt-metaboxer-code-select">'.$button.'</a>';
}

