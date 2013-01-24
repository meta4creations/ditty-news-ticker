<?php
/**
 * Create the News Ticker post type
 *
 * @package Ditty News Ticker
 */
 
 
 
 
add_action( 'init','mtphr_dnt_posttype' );
/**
 * Add post types
 *
 * @since 1.0.0
 */
function mtphr_dnt_posttype() {

	$labels = array(
		'name' => __( 'News Tickers', 'rj' ),
		'singular_name' => __( 'News Ticker', 'rj' ),
		'add_new' => __( 'Add New', 'rj' ),
		'add_new_item' => __( 'Add New News Ticker', 'rj' ),
		'edit_item' => __( 'Edit News Ticker', 'rj' ),
		'new_item' => __( 'New News Ticker', 'rj' ),
		'view_item' => __( 'View News Ticker', 'rj' ),
		'search_items' => __( 'Search News Tickers', 'rj' ),
		'not_found' => __( 'No News Tickers Found', 'rj' ),
		'not_found_in_trash' => __( 'No News Tickers Found In Trash', 'rj' ),
		'parent_item_colon' => '',
		'menu_name' => __( 'News Tickers', 'rj' )
	);

	// Create the arguments
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'supports' => array( 'title' ),
		'show_in_nav_menus' => false
	);

	register_post_type( 'ditty_news_ticker', $args );	
}


