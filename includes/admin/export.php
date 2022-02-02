<?php

/**
 * Export posts
 *
 * @since    3.0.13
 */
function ditty_export_posts_ajax() {
	check_ajax_referer( 'ditty', 'security' );
	if ( ! current_user_can( 'manage_ditty_settings' ) ) {
		return false;
	}
	$export_type_ajax 		= isset( $_POST['export_type'] ) 		? $_POST['export_type'] 		: false;	
	$export_options_ajax 	= isset( $_POST['export_options'] ) ? $_POST['export_options'] 	: false;
		
	switch( $export_type_ajax ) {
		case 'ditty':
			$data = ditty_export_ditty_posts();
			break;
		default:
			break;
	}
		
	$json_data = apply_filters( 'ditty_settings_save', $_POST, array() );
	wp_send_json( $json_data );
}
add_action( 'wp_ajax_ditty_export_posts', 'ditty_export_posts_ajax' );

/**
 * Export posts
 *
 * @since    3.0.13
 */
function ditty_export_ditty_posts() {
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