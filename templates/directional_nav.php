<?php

// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
extract( $mtphr_dnt_meta_data );

// Add the directional nav
if( ($_mtphr_dnt_total_ticks > 1) && $_mtphr_dnt_mode == 'rotate' ) {

	if( isset($_mtphr_dnt_rotate_directional_nav) && $_mtphr_dnt_rotate_directional_nav ) {

		$hide = '';
		if( isset($_mtphr_dnt_rotate_directional_nav_hide) && $_mtphr_dnt_rotate_directional_nav_hide ) {
			$hide = ' mtphr-dnt-nav-hide';
		}
		echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-prev'.$hide.'" href="#" rel="nofollow">'.apply_filters( 'mtphr_dnt_direction_nav_prev', '<i class="mtphr-dnt-icon-arrow-left"></i>' ).'</a>';
		echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-next'.$hide.'" href="#" rel="nofollow">'.apply_filters( 'mtphr_dnt_direction_nav_next', '<i class="mtphr-dnt-icon-arrow-right"></i>' ).'</a>';
	}
}