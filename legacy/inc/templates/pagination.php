<?php

// Get and extract the metadata array into variables
global $mtphr_dnt_meta_data;
$defaults = mtphr_dnt_meta_defaults();
$args = wp_parse_args( $mtphr_dnt_meta_data, mtphr_dnt_meta_defaults() );

if( $args['_mtphr_dnt_mode'] == 'list' && isset($args['_mtphr_dnt_list_tick_paging']) && $args['_mtphr_dnt_list_tick_paging'] ) {
	
	$spacing = 'margin-top:'.intval($args['_mtphr_dnt_list_tick_spacing']).'px;';
	$total_pages = ceil( $args['_mtphr_dnt_total_ticks']/$args['_mtphr_dnt_list_tick_count'] );
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
		'prev_next'    => $args['_mtphr_dnt_list_tick_prev_next'],
		'prev_text'    => $args['_mtphr_dnt_list_tick_prev_text'],
		'next_text'    => $args['_mtphr_dnt_list_tick_next_text'],
		'type'         => 'plain',
		'add_args'     => false,
		'add_fragment' => ''
	);
	
	echo '<div class="mtphr-dnt-tick-paging" style="'.$spacing.'">';
		echo paginate_links( apply_filters('mtphr_dnt_tick_paging_args', $args) );
	echo '</div>';
}