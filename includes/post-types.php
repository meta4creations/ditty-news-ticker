<?php

 
/* --------------------------------------------------------- */
/* !Add the post type - 2.1.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_posttype() {

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
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'menu_icon' => 'dashicons-ditty-news-ticker',
		'supports' => array( 'title', 'author' ),
		'rewrite' => array( 'slug' => __( 'ticker', 'ditty-news-ticker' ) ),
		'show_in_nav_menus' => true,
		'capabilities' => array(
			'edit_posts' => 'edit_ditty_news_tickers',
			'edit_others_posts' => 'edit_others_ditty_news_tickers',
			'publish_posts' => 'publish_ditty_news_tickers',
			'read_private_posts' => 'read_private_ditty_news_tickers',
			'read' => 'read_ditty_news_tickers',
			'delete_posts' => 'delete_ditty_news_tickers',
			'delete_private_posts' => 'delete_private_ditty_news_tickers',
			'delete_published_posts' => 'delete_published_ditty_news_tickers',
			'delete_others_posts' => 'delete_others_ditty_news_tickers',
			'edit_private_posts' => 'edit_private_ditty_news_tickers',
			'edit_published_posts' => 'edit_published_ditty_news_tickers',	
    ),
    // as pointed out by iEmanuele, adding map_meta_cap will map the meta correctly 
    'map_meta_cap' => true
	);

	register_post_type( 'ditty_news_ticker', $args );	
}
add_action( 'init','mtphr_dnt_posttype' );




/* --------------------------------------------------------- */
/* !Modify the updated text - 1.0.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_updated_messages( $messages ) {

  $messages['ditty_news_ticker'][1] = __('Ditty News Ticker Updated!', 'ditty-news-ticker');

  return $messages;
}
add_filter( 'post_updated_messages', 'mtphr_dnt_updated_messages' );


