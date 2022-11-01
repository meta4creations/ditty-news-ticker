<?php
	
// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
$defaults = mtphr_dnt_meta_defaults();
$args = wp_parse_args( $mtphr_dnt_meta_data, mtphr_dnt_meta_defaults() );

// Display the title
if( isset($args['_mtphr_dnt_title']) && $args['_mtphr_dnt_title'] ) {

	$inline_title = ( isset($args['_mtphr_dnt_inline_title']) && $args['_mtphr_dnt_inline_title'] ) ? ' mtphr-dnt-inline-title' : '';
	
	do_action( 'mtphr_dnt_title_before', $args['_mtphr_dnt_id'], $mtphr_dnt_meta_data );
	echo '<h3 class="mtphr-dnt-title'.$inline_title.'">'.apply_filters( 'mtphr_dnt_ticker_title', get_the_title($args['_mtphr_dnt_id']) ).'</h3>';
	do_action( 'mtphr_dnt_title_after', $args['_mtphr_dnt_id'], $mtphr_dnt_meta_data );
}