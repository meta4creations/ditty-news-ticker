<?php

/* --------------------------------------------------------- */
/* !Display a single image field via ajax - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_single_image_ajax() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'ditty-news-ticker', 'security' );

	// Get variables
	$attachment = $_POST['attachment'];

	// Display the image
	if( $attachment['type'] == 'image' ) {
		mtphr_dnt_render_single_image( $attachment['id'] );
	}

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_dnt_single_image_ajax', 'mtphr_dnt_single_image_ajax' );



/* --------------------------------------------------------- */
/* !Return a new editor via ajax - 2.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_wysiwyg_ajax() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'ditty-news-ticker', 'security' );

	// Get variables
	$name = $_POST['name'];
	
	$editor_settings = array(
		'textarea_name' => $name,
		'textarea_rows' => 2
	);

	// Display the image
	wp_editor( '', uniqid(), $editor_settings );

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_dnt_wysiwyg_ajax', 'mtphr_dnt_wysiwyg_ajax' );