<?php

/**
 * Export posts
 *
 * @since    3.0.17
 */
function ditty_export_posts_ajax() {
	check_ajax_referer( 'ditty', 'security' );
	if ( ! current_user_can( 'manage_ditty_settings' ) ) {
		return false;
	}
	$export_type_ajax 	= isset( $_POST['export_type'] ) 	? $_POST['export_type'] 	: false;	
	$export_posts_ajax 	= isset( $_POST['export_posts'] ) ? $_POST['export_posts'] 	: false;
	
	$export = array();
	
	switch( $export_type_ajax ) {
		case 'ditty':
			$export['ditty'] = ditty_export_ditty_posts( $export_posts_ajax );
			break;
		case 'layouts':
			$export['layouts'] = ditty_export_ditty_layouts( $export_posts_ajax );
			break;
		default:
			break;
	}
	
	set_transient( 'ditty_export', json_encode( $export ), MINUTE_IN_SECONDS );

	$json_data = array(
		'$export' => $export,
	);
	wp_send_json( $json_data );
}
add_action( 'wp_ajax_ditty_export_posts', 'ditty_export_posts_ajax' );

/**
 * Create the export file
 *
 * @since    3.0.17
 */
function ditty_create_export_file() {
	if ( ! isset( $_GET['ditty_export'] ) ) {
		return false;
	}
	
	$export = get_transient( 'ditty_export' );
	if ( $export ) {
		$file = 'ditty_export_' . current_time( 'timestamp' ) . '.json';
		$txt = fopen( $file, "w" ) or die( "Unable to open file!" );
		fwrite( $txt, $export );
		fclose( $txt );
		delete_transient( 'ditty_export' );
		
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . filesize( $file ) );
		header( "Content-Type: text/plain" );
		readfile( $file );
	}
}
add_action( 'admin_init', 'ditty_create_export_file' );


/**
 * Export posts
 *
 * @since    3.0.13
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
 * Export layouts
 *
 * @since    3.0.13
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