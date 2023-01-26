<?php

// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
$defaults = mtphr_dnt_meta_defaults();
$args = wp_parse_args( $mtphr_dnt_meta_data, mtphr_dnt_meta_defaults() );

// Add the directional nav
if( ($args['_mtphr_dnt_total_ticks'] > 1) && $args['_mtphr_dnt_mode'] == 'rotate' ) {

	if( isset($args['_mtphr_dnt_rotate_directional_nav']) && $args['_mtphr_dnt_rotate_directional_nav'] ) {

		$hide = '';
		if( isset($args['_mtphr_dnt_rotate_directional_nav_hide']) && $args['_mtphr_dnt_rotate_directional_nav_hide'] ) {
			$hide = ' mtphr-dnt-nav-hide';
		}
		echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-prev'.$hide.'" href="#" rel="nofollow" aria-label="' . __( 'Previous', 'ditty-news-ticker' ) . '">'.apply_filters( 'mtphr_dnt_direction_nav_prev', '<i class="mtphr-dnt-icon-arrow-left"></i>' ).'</a>';
		echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-next'.$hide.'" href="#" rel="nofollow" aria-label="' . __( 'Next', 'ditty-news-ticker' ) . '">'.apply_filters( 'mtphr_dnt_direction_nav_next', '<i class="mtphr-dnt-icon-arrow-right"></i>' ).'</a>';
	}
}