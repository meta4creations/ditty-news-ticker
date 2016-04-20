<?php
	
// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
extract( $mtphr_dnt_meta_data );

// Add the control nav
if( ($_mtphr_dnt_total_ticks > 1) && $_mtphr_dnt_mode == 'rotate' ) {
	
	if( isset($_mtphr_dnt_rotate_control_nav) && $_mtphr_dnt_rotate_control_nav ) {
	
		echo '<div class="mtphr-dnt-control-links">';
			for( $i=0; $i<$_mtphr_dnt_total_ticks; $i++ ) {
				$link = ( $_mtphr_dnt_rotate_control_nav_type == 'button' ) ? '<i class="mtphr-dnt-icon-button"></i>' : intval($i+1);
				echo '<a class="mtphr-dnt-control mtphr-dnt-control-'.$_mtphr_dnt_rotate_control_nav_type.'" href="'.$i.'" rel="nofollow">'.apply_filters( 'mtphr_dnt_control_nav', $link, $_mtphr_dnt_rotate_control_nav_type ).'</a>';
			}
		echo '</div>';
	}
}