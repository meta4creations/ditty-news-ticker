<?php
	
// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
extract( $mtphr_dnt_meta_data );

// Display the title
if( isset($_mtphr_dnt_title) && $_mtphr_dnt_title ) {

	$inline_title = ( isset($_mtphr_dnt_inline_title) && $_mtphr_dnt_inline_title ) ? ' mtphr-dnt-inline-title' : '';
	
	do_action( 'mtphr_dnt_title_before', $_mtphr_dnt_id, $mtphr_dnt_meta_data );
	echo '<h3 class="mtphr-dnt-title'.$inline_title.'">'.apply_filters( 'mtphr_dnt_ticker_title', get_the_title($_mtphr_dnt_id) ).'</h3>';
	do_action( 'mtphr_dnt_title_after', $_mtphr_dnt_id, $mtphr_dnt_meta_data );
}