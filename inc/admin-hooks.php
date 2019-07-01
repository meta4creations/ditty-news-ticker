<?php
	
/**
 * Add tick tabs
 * 
 * @since  3.0
 * @return void
 */
add_filter( 'dnt_admin_tick_tabs', 'dnt_admin_tick_tab_type', 10, 2 ); // Add tick type tab
add_filter( 'dnt_admin_tick_tabs', 'dnt_admin_tick_tab_layout', 20 ); // Add tick layout tab
add_filter( 'dnt_admin_tick_tabs', 'dnt_admin_tick_tab_time', 30 ); // Add tick time tab

/**
 * Add tick tab panels
 * 
 * @since  3.0
 * @return void
 */
add_action( 'dnt_admin_tick_tab_panel', 'dnt_admin_tick_panel_type', 10 ); // Add tick type panel
add_action( 'dnt_admin_tick_tab_panel', 'dnt_admin_tick_panel_layout', 20 ); // Add tick layout panel
add_action( 'dnt_admin_tick_tab_panel', 'dnt_admin_tick_panel_time', 30 ); // Add tick time panel