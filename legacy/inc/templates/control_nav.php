<?php
	
// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
$defaults = mtphr_dnt_meta_defaults();
$args = wp_parse_args( $mtphr_dnt_meta_data, mtphr_dnt_meta_defaults() );

// Add the control nav
if( ($args['_mtphr_dnt_total_ticks'] > 1) && $args['_mtphr_dnt_mode'] == 'rotate' ) {
	
	if( isset($args['_mtphr_dnt_rotate_control_nav']) && $args['_mtphr_dnt_rotate_control_nav'] ) {
	
		echo '<div class="mtphr-dnt-control-links">';
			for( $i=0; $i<$args['_mtphr_dnt_total_ticks']; $i++ ) {
				$link = ( $args['_mtphr_dnt_rotate_control_nav_type'] == 'button' ) ? '<i class="mtphr-dnt-icon-button"></i>' : intval($i+1);
				echo '<a class="mtphr-dnt-control mtphr-dnt-control-'.$args['_mtphr_dnt_rotate_control_nav_type'].'" href="'.$i.'" rel="nofollow">'.apply_filters( 'mtphr_dnt_control_nav', $link, $args['_mtphr_dnt_rotate_control_nav_type'] ).'</a>';
			}
		echo '</div>';
	}
}