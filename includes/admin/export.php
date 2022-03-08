<?php

/**
 * Setup the import and export fields
 *
 * @since    3.0.17
*/
function ditty_settings_import_export() {	
	$fields = array(
		'ditty_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> esc_html__( 'Ditty Import / Export', 'ditty-news-ticker' ),
		),
		'ditty_import' => array(
			'type'	=> 'html',
			'id' 		=> 'ditty_import',
			'name' 	=> esc_html__( 'Ditty Import', 'ditty-news-ticker' ),
			'help'	=> esc_html__( 'Import Ditty posts', 'ditty-news-ticker' ),
			'std'		=> 'Import button should go here',
		),
		'ditty_export' => array(
			'type'	=> 'html',
			'id' 		=> 'ditty_export',
			'name' 	=> esc_html__( 'Ditty Export', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Ditty you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Ditty posts. You can optionally include the connected Layouts and Displays for each Ditty.", 'ditty-news-ticker' ),
			'std'		=> ditty_export_ditty(),
		),
		'layout_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> esc_html__( 'Layout Import / Export', 'ditty-news-ticker' ),
		),
		'layout_import' => array(
			'type'	=> 'html',
			'id' 		=> 'layout_import',
			'name' 	=> esc_html__( 'Layout Import', 'ditty-news-ticker' ),
			'help'	=> esc_html__( 'Import Layout posts', 'ditty-news-ticker' ),
			'std'		=> 'Import button should go here',
		),
		'layout_export' => array(
			'type'	=> 'html',
			'id' 		=> 'layout_export',
			'name' 	=> esc_html__( 'Layout Export', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Layouts you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Layout posts.", 'ditty-news-ticker' ),
			'std'		=> ditty_export_layouts(),
		),
		'display_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> esc_html__( 'Display Import / Export', 'ditty-news-ticker' ),
		),
		'display_import' => array(
			'type'	=> 'html',
			'id' 		=> 'display_import',
			'name' 	=> esc_html__( 'Display Import', 'ditty-news-ticker' ),
			'help'	=> esc_html__( 'Import Display posts', 'ditty-news-ticker' ),
			'std'		=> 'Import button should go here',
		),
		'display_export' => array(
			'type'	=> 'html',
			'id' 		=> 'display_export',
			'name' 	=> esc_html__( 'Display Export', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Displays you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Display posts.", 'ditty-news-ticker' ),
			'std'		=> ditty_export_displays(),
		),
	);
	ditty_fields( $fields );
	echo '<input type="hidden" name="ditty_export_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';
}

/**
 * Create the Layout export fields
 *
 * @since    3.0.17
 */
function ditty_export_layouts() {
	$layouts = ditty_layout_posts();
	$options = array(
		'_select_all' => esc_html__( 'Select All', 'ditty-news-ticker' ),
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
	$fields = array(
		'ditty_layout_export_options' => array(
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_layout_export_options',
			'options'			=> $options,
			'inline' 			=> false,
			'field_only' 	=> true,
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_button' => array(
			'type'				=> 'button',
			'id' 					=> 'ditty_export_button',
			'label'				=> esc_html__( 'Export Layouts', 'ditty-news-ticker' ) . ' <i class="fas fa-sync-alt fa-spin"></i>',
			'field_only' 	=> true,
			'icon_after' 	=> 'fas fa-sync-alt fa-spin',
			'input_class'	=> 'ditty-export-button',
			'atts' 				=> array(
				'disabled'					=> 'disabled',
				'type'							=> 'submit',
				'value'							=> 'layouts',
			),
		),
	);
	$render_fields = ditty_fields( $fields, false, 'return' );
	return $render_fields;
}

/**
 * Create the Display export fields
 *
 * @since    3.0.17
 */
function ditty_export_displays() {
	$displays = ditty_display_posts();
	$options = array(
		'_select_all' => esc_html__( 'Select All', 'ditty-news-ticker' ),
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
	$fields = array(
		'ditty_display_export_options' => array(
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_display_export_options',
			'options'			=> $options,
			'inline' 			=> false,
			'field_only' 	=> true,
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_button' => array(
			'type'				=> 'button',
			'id' 					=> 'ditty_export_button',
			'label'				=> esc_html__( 'Export Displays', 'ditty-news-ticker' ) . ' <i class="fas fa-sync-alt fa-spin"></i>',
			'field_only' 	=> true,
			'icon_after' 	=> 'fas fa-sync-alt fa-spin',
			'input_class'	=> 'ditty-export-button',
			'atts' 				=> array(
				'disabled'					=> 'disabled',
				'type'							=> 'submit',
				'value'							=> 'displays',
			),
		),
	);
	$render_fields = ditty_fields( $fields, false, 'return' );
	return $render_fields;
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
	switch( $_POST['ditty_export_button'] ) {
		case 'ditty':
			//$export['ditty'] = ditty_export_ditty_posts(  );
			break;
		case 'layouts':
			if ( isset( $_POST['ditty_layout_export_options'] ) ) {
				$export['layouts'] = ditty_export_ditty_layouts( $_POST['ditty_layout_export_options'] );
			}	
			break;
		case 'displays':
			if ( isset( $_POST['ditty_display_export_options'] ) ) {
				$export['displays'] = ditty_export_ditty_displays( $_POST['ditty_display_export_options'] );
			}	
			break;
		default:
			break;
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
	$args = array(
		'posts_per_page' => -1,
		'orderby' => 'post_date',
		'post_type' => 'ditty',
	);
	$posts = get_posts( $args );
	
	$post_exports = array();
	if ( is_array( $posts ) && count( $posts ) > 0 ) {
		foreach ( $posts as $i => $post ) {
			$post_data = array();
			
			// Post object data
			$post_data['post'] = ( array ) $post;
			
			// Post custom meta
			$post_data['meta'] = get_post_custom( $post->ID );
			
			// Post items
			$items = ditty_items_meta( $post->ID );
			$post_data['items'] = ( array ) $items;
		}
	}
}

/**
 * Export Layouts
 *
 * @since    3.0.17
 */
function ditty_export_ditty_layouts( $post_ids ) {
	$layouts = array();
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
	return $layouts;
}

/**
 * Export Displays
 *
 * @since    3.0.17
 */
function ditty_export_ditty_displays( $post_ids ) {
	$displays = array();
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
	return $displays;
}