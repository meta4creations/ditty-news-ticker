<?php

 
/* --------------------------------------------------------- */
/* !Add the post type - 2.1.11 */
/* --------------------------------------------------------- */

/**
 * Add custom post types
 * @since   3.0
 */

function mtphr_dnt_setup_post_types() {
	
	// ditty_news_ticker
	$labels = array(
		'name' => __( 'News Tickers', 'ditty-news-ticker' ),
		'singular_name' => __( 'News Ticker', 'ditty-news-ticker' ),
		'add_new' => __( 'Add New', 'ditty-news-ticker' ),
		'add_new_item' => __( 'Add New News Ticker', 'ditty-news-ticker' ),
		'edit_item' => __( 'Edit News Ticker', 'ditty-news-ticker' ),
		'new_item' => __( 'New News Ticker', 'ditty-news-ticker' ),
		'view_item' => __( 'View News Ticker', 'ditty-news-ticker' ),
		'search_items' => __( 'Search News Tickers', 'ditty-news-ticker' ),
		'not_found' => __( 'No News Tickers Found', 'ditty-news-ticker' ),
		'not_found_in_trash' => __( 'No News Tickers Found In Trash', 'ditty-news-ticker' ),
		'parent_item_colon' => '',
		'menu_name' => __( 'News Tickers', 'ditty-news-ticker' )
	);

	// Create the arguments
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'capability_type' => 'ditty_news_ticker',
		'map_meta_cap' => true,
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'menu_icon' => 'dashicons-ditty-news-ticker',
		'supports' => array( 'title', 'author' ),
		'show_in_nav_menus' => false,
	);

	register_post_type( 'ditty_news_ticker', $args );	
	
	// ditty_layout
	$labels = array(
		'name' => __( 'Layouts', 'ditty-news-ticker' ),
		'singular_name' => __( 'Layout', 'ditty-news-ticker' ),
		'add_new' => __( 'Add New', 'ditty-news-ticker' ),
		'add_new_item' => __( 'Add New Layout', 'ditty-news-ticker' ),
		'edit_item' => __( 'Edit Layout', 'ditty-news-ticker' ),
		'new_item' => __( 'New Layout', 'ditty-news-ticker' ),
		'view_item' => __( 'View Layout', 'ditty-news-ticker' ),
		'search_items' => __( 'Search Layouts', 'ditty-news-ticker' ),
		'not_found' => __( 'No Layouts Found', 'ditty-news-ticker' ),
		'not_found_in_trash' => __( 'No Layouts Found In Trash', 'ditty-news-ticker' ),
		'parent_item_colon' => '',
		'menu_name' => __( 'Layouts', 'ditty-news-ticker' )
	);

	// Create the arguments
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'capability_type' => 'ditty_layout',
		'map_meta_cap' => true,
		'show_in_menu' => 'edit.php?post_type=ditty_news_ticker', 
		'query_var' => true,
		'rewrite' => true,
		'supports' => array( 'title' ),
		'show_in_nav_menus' => false,
	);

	register_post_type( 'ditty_layout', $args );	
}
add_action( 'init','mtphr_dnt_setup_post_types' );



/**
 * Modify the updated text
 * @since   3.0
 */
function mtphr_dnt_updated_messages( $messages ) {

  $messages['ditty_news_ticker'][1] = __('Ditty News Ticker Updated!', 'ditty-news-ticker');
  $messages['ditty_layout'][1] = __('Layout Updated!', 'ditty-news-ticker');

  return $messages;
}
add_filter( 'post_updated_messages', 'mtphr_dnt_updated_messages' );


