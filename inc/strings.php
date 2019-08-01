<?php

/**
 * Centralized location for all strings
 * 
 * @since  3.0
 * @return string $sring
 * @return array	$strings
 */
function dnt_strings( $slug=false ) {
	
	$strings = array(
		'add_new_tick' 		=> __( 'Add New Tick', 'ditty-news-ticker' ),
		'add_tick' 				=> __( 'Add Tick', 'ditty-news-ticker' ),
		'cancel' 					=> __( 'Cancel', 'ditty-news-ticker' ),
		'edit_tick' 			=> __( 'Edit Tick', 'ditty-news-ticker' ),
		'insert_new_tick' => __( 'Insert New Tick', 'ditty-news-ticker' ),
		'update_tick' 		=> __( 'Update Tick', 'ditty-news-ticker' ),
	);
	
	$strings = apply_filters( 'dnt_strings', $strings );
	
	if ( $slug ) {
		
		if ( isset( $strings[$slug] ) ) {
			return $strings[$slug];
		}
		
	} else {
		
		return $strings;
	}
}