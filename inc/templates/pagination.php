<?php

// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
extract( $mtphr_dnt_meta_data );

if( $_mtphr_dnt_mode == 'list' && isset($_mtphr_dnt_list_tick_paging) && $_mtphr_dnt_list_tick_paging ) {
	
	$spacing = 'margin-top:'.intval($_mtphr_dnt_list_tick_spacing).'px;';
	$total_pages = ceil( $_mtphr_dnt_total_ticks/$_mtphr_dnt_list_tick_count );
	$current_page = isset( $_GET['tickpage'] ) ? intval($_GET['tickpage']) : 1;
	
	$big = 999999999;
	$args = array(
		'base'         => str_replace( $big, '%#%', esc_url(add_query_arg('tickpage', $big)) ),
		'format'       => '?tickpage=%#%',
		'total'        => $total_pages,
		'current'      => $current_page,
		'show_all'		 => false,
		'end_size'     => 1,
		'mid_size'     => 2,
		'prev_next'    => $_mtphr_dnt_list_tick_prev_next,
		'prev_text'    => $_mtphr_dnt_list_tick_prev_text,
		'next_text'    => $_mtphr_dnt_list_tick_next_text,
		'type'         => 'plain',
		'add_args'     => false,
		'add_fragment' => ''
	);
	
	echo '<div class="mtphr-dnt-tick-paging" style="'.$spacing.'">';
		echo paginate_links( apply_filters('mtphr_dnt_tick_paging_args', $args) );
	echo '</div>';
}