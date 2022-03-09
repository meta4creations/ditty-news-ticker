<?php

/**
 * Setup the import and export fields
 *
 * @since    3.0.17
*/
function ditty_settings_import_export() {
	$fields = array(
		'ditty_import_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'ditty_import_heading',
			'name' 		=> esc_html__( 'Ditty Import', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Ditty you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Ditty posts. You can optionally include the connected Layouts and Displays for each Ditty.", 'ditty-news-ticker' ),
		),
		'ditty_export_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'ditty_export_heading',
			'name' 		=> esc_html__( 'Ditty Export', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Ditty you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Ditty posts. You can optionally include the connected Layouts and Displays for each Ditty.", 'ditty-news-ticker' ),
		),
		'ditty_export_ditty_ids' => array(
			'name' 				=> esc_html__( 'Ditty Posts', 'ditty-news-ticker' ),
			'desc'				=> esc_html__( "Select the Ditty you would like to export. All connected Layouts and Displays will automatically be exported as well.", 'ditty-news-ticker' ),
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_export_ditty_ids',
			'options'			=> ditty_export_ditty_options(),
			//'inline' 			=> true,
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_layout_ids' => array(
			'name' 				=> esc_html__( 'Layout Posts', 'ditty-news-ticker' ),
			'desc'				=> esc_html__( "Select the Layouts you would like to export.", 'ditty-news-ticker' ),
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_export_layout_ids',
			'options'			=> ditty_export_layout_options(),
			//'inline' 			=> true,
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_display_ids' => array(
			'name' 				=> esc_html__( 'Display Posts', 'ditty-news-ticker' ),
			'desc'				=> esc_html__( "Select the Displays you would like to export.", 'ditty-news-ticker' ),
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_export_display_ids',
			'options'			=> ditty_export_display_options(),
			//'inline' 			=> true,
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_button' => array(
			'type'				=> 'button',
			'id' 					=> 'ditty_export_button',
			'name' 				=> ' ',
			'label'				=> esc_html__( 'Export Selected Posts', 'ditty-news-ticker' ) . ' <i class="fas fa-sync-alt fa-spin"></i>',
			'icon_after' 	=> 'fas fa-sync-alt fa-spin',
			'input_class'	=> 'ditty-export-button',
			'atts' 				=> array(
				'disabled'					=> 'disabled',
				'type'							=> 'submit',
				//'value'							=> 'ditty',
			),
		),
	);
	ditty_fields( $fields );
	echo '<input type="hidden" name="ditty_export_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';
}

/**
 * Create the Ditty export fields
 *
 * @since    3.0.17
 */
function ditty_export_ditty_options() {
	$args = array(
		'posts_per_page' => -1,
		'orderby' => 'post_date',
		'post_type' => 'ditty',
	);
	$posts = get_posts( $args );
	
	$options = array(
		'select_all' => esc_html__( 'Select all Ditty', 'ditty-news-ticker' ),
	);
	if ( is_array( $posts ) && count( $posts ) > 0 ) {
		foreach ( $posts as $i => $post ) {
			$options[$post->ID] = $post->post_title;
		}
	}
	return $options;
}

/**
 * Create the Layout export fields
 *
 * @since    3.0.17
 */
function ditty_export_layout_options() {
	$layouts = ditty_layout_posts();
	$options = array(
		'select_all' => esc_html__( 'Select all Layouts', 'ditty-news-ticker' ),
	);
	if ( is_array( $layouts ) && count( $layouts ) > 0 ) {
		foreach ( $layouts as $i => $layout ) {
			$version = get_post_meta( $layout->ID, '_ditty_layout_version', true );
			$version_string = '';
			if ( $version ) {
				$version_string = " <small class='ditty-layout-version'>(v{$version})</small>";
			}
			$options[$layout->ID] = $layout->post_title . $version_string;
		}
	}
	return $options;
}

/**
 * Create the Display export fields
 *
 * @since    3.0.17
 */
function ditty_export_display_options() {
	$displays = ditty_display_posts();
	$options = array(
		'select_all' => esc_html__( 'Select all Displays', 'ditty-news-ticker' ),
	);
	if ( is_array( $displays ) && count( $displays ) > 0 ) {
		foreach ( $displays as $i => $display ) {
			$version = get_post_meta( $display->ID, '_ditty_display_version', true );
			$version_string = '';
			if ( $version ) {
				$version_string = " <small class='ditty-display-version'>(v{$version})</small>";
			}
			$options[$display->ID] = $display->post_title . $version_string;
		}
	}
	return $options;
}

/**
 * Create the export file
 *
 * @since    3.0.17
 */
function ditty_create_export_file() {
	if ( ! isset( $_POST['ditty_export_button'] ) ) {
		return false;
	}
	// verify nonce
	if ( ! isset( $_POST['ditty_export_nonce'] ) || ! wp_verify_nonce( $_POST['ditty_export_nonce'], basename( __FILE__ ) ) ) {
		return false;
	}
	$export = array();
	$ditty_ids = isset( $_POST['ditty_export_ditty_ids'] ) ? $_POST['ditty_export_ditty_ids'] : array();
	$layout_ids = isset( $_POST['ditty_export_layout_ids'] ) ? $_POST['ditty_export_layout_ids'] : array();
	$display_ids = isset( $_POST['ditty_export_display_ids'] ) ? $_POST['ditty_export_display_ids'] : array();
	
	if ( ! empty( $ditty_ids ) ) {
		if ( $ditty_data = ditty_export_ditty_posts( $ditty_ids ) ) {
			if ( isset( $ditty_data['ditty'] ) ) {
				$export['ditty'] = $ditty_data['ditty'];
			}
			if ( isset( $ditty_data['layout_ids'] ) ) {
				$layout_ids = array_merge( $layout_ids, $ditty_data['layout_ids'] );
				$layout_ids = array_unique( $layout_ids );
			}
			if ( isset( $ditty_data['display_ids'] ) ) {
				$display_ids = array_merge( $display_ids, $ditty_data['display_ids'] );
				$display_ids = array_unique( $display_ids );
			}
		}
	}
	
	if ( ! empty( $layout_ids ) ) {
		if ( $layout_data = ditty_export_ditty_layouts( $layout_ids ) ) {
			$export['layouts'] = $layout_data;
		}
	}
	
	if ( ! empty( $display_ids ) ) {
		if ( $display_data = ditty_export_ditty_displays( $display_ids ) ) {
			$export['displays'] = $display_data;
		}
	}

	if ( empty( $export ) ) {
		return false;
	}
	
	$export_json = json_encode( $export );
	$filename = 'ditty-export-' . date( 'Y-m-d' ) . '.json';
	$filename = sanitize_file_name( $filename );
	header( 'Content-Description: File Transfer' );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ), true );
	echo $export_json;
	die();
}
add_action( 'admin_init', 'ditty_create_export_file' );


/**
 * Export posts
 *
 * @since    3.0.17
 */
function ditty_export_ditty_posts( $post_ids ) {
	$export = array();
	$dittys = array();
	$displays = array();
	$layouts = array();
	
	if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
		foreach ( $post_ids as $i => $post_id ) {
			$uniq_id = ditty_maybe_add_uniq_id( $post_id );
			$display = get_post_meta( $post_id, '_ditty_display', true );
			$display_uniq_id = ditty_maybe_add_uniq_id( $display );
			
			// Store the display for possible export
			$displays[$display] = $display;
			
			$items = array();
			$all_meta = Ditty()->db_items->get_items( $post_id );
			if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
				foreach ( $all_meta as $i => $meta ) {
					if ( is_object( $meta ) ) {
						$meta = ( array ) $meta;
					}
					unset( $meta['item_id'] );
					unset( $meta['date_created'] );
					unset( $meta['date_modified'] );
					unset( $meta['ditty_id'] );
					$items[] = $meta;
					
					// Store the layouts for possible exports
					$layout_values = maybe_unserialize( $meta['layout_value'] );
					if ( is_array( $layout_values ) && count( $layout_values ) > 0 ) {
						foreach ( $layout_values as $i => $layout_id ) {
							$layouts[$layout_id] = $layout_id;
						}
					}	
				} 
			}
	
			$dittys[$uniq_id] = array(
				'label' 		=> get_the_title( $post_id ),
				'init'			=> get_post_meta( $post_id, '_ditty_init', true ),
				'settings'	=> get_post_meta( $post_id, '_ditty_settings', true ),
				'display'		=> $display_uniq_id,
				'items'			=> $items,
				'uniq_id'		=> $uniq_id,
			);
		}
	}
	if ( ! empty( $dittys ) ) {
		$export['ditty'] = $dittys;
	}
	if ( ! empty( $layouts ) ) {
		$export['layout_ids'] = array_values( $layouts );
	}
	if ( ! empty( $displays ) ) {
		$export['display_ids'] = array_values( $displays );
	}

	if ( ! empty( $export ) ) {
		return $export;
	}
}

/**
 * Export Layouts
 *
 * @since    3.0.17
 */
function ditty_export_ditty_layouts( $post_ids ) {
	$export = array();
	$layouts = array();

	if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
		foreach ( $post_ids as $i => $post_id ) {
			$uniq_id = ditty_maybe_add_uniq_id( $post_id );
			$layouts[$uniq_id] = array(
				'label' 			=> get_the_title( $post_id ),
				'description'	=> get_post_meta( $post_id, '_ditty_layout_description', true ),
				'html'				=> stripslashes( get_post_meta( $post_id, '_ditty_layout_html', true ) ),
				'css'					=> get_post_meta( $post_id, '_ditty_layout_css', true ),
				'version' 		=> get_post_meta( $post_id, '_ditty_layout_version', true ),
				'uniq_id'			=> $uniq_id,
			);
		}
	}
	
	if ( ! empty( $layouts ) ) {
		return $layouts;
	}
}

/**
 * Export Displays
 *
 * @since    3.0.17
 */
function ditty_export_ditty_displays( $post_ids ) {
	$export = array();
	$displays = array();
	
	if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
		foreach ( $post_ids as $i => $post_id ) {
			$uniq_id = ditty_maybe_add_uniq_id( $post_id );
			$displays[$uniq_id] = array(
				'label' 				=> get_the_title( $post_id ),
				'description'		=> get_post_meta( $post_id, '_ditty_display_description', true ),
				'display_type'	=> get_post_meta( $post_id, '_ditty_display_type', true ),
				'settings'			=> get_post_meta( $post_id, '_ditty_display_settings', true ),
				'version' 			=> get_post_meta( $post_id, '_ditty_display_version', true ),
				'uniq_id'				=> $uniq_id,
			);
		}
	}
	
	if ( ! empty( $displays ) ) {
		return $displays;
	}
}