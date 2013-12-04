<?php

/* --------------------------------------------------------- */
/* !Display a mixed list item - 1.3.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_mixed_list_ajax() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'ditty-news-ticker', 'security' );
	
	// Get the DNT types
	$types = mtphr_dnt_types_array();
	unset($types['mixed']);

	// Display the field
	mtphr_dnt_render_mixed_tick( $types );

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_dnt_mixed_list_ajax', 'mtphr_dnt_mixed_list_ajax' );