<?php

/**
 * Add custom post types
 * @since   3.0.26
 */

function ditty_setup_post_types() {
	
	$is_settings_page = ( is_admin() && isset( $_GET['post_type'] ) && 'ditty' == $_GET['post_type'] && isset( $_GET['page'] ) && 'ditty_settings' == $_GET['page'] ) ? true : false;
		
	//The icon in Base64 format
	$icon_base64 = 'PHN2ZyBkYXRhLW5hbWU9IkxheWVyIDEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDY5LjMxIDcxLjEiIGZpbGw9ImN1cnJlbnRDb2xvciI+PHBhdGggZD0iTTAgNDYuNGMwLTE3LjIgOC42LTI5LjEgMjQuNi0yOS4xYTE5LjkzIDE5LjkzIDAgMCAxIDYuNiAxVjBINDV2NTkuMmwxIDEwLjNIMzQuMmwtLjktNS4yaC0uNWExNS4yMSAxNS4yMSAwIDAgMS0xMyA2LjhDMy44IDcxLjEgMCA1OC40IDAgNDYuNFptMzEuMiA3LjRWMjguNmExMy43IDEzLjcgMCAwIDAtNi0xLjNjLTguNyAwLTExLjMgOC43LTExLjMgMTcuOCAwIDguNSAxLjkgMTUuOCA4LjkgMTUuOCA1LjEgMCA4LjQtMy44IDguNC03LjFaTTYxLjkxIDY1LjZhNyA3IDAgMCAxLTcuMi03LjRjMC01IDIuOC03LjcgNy4xLTcuN3M3LjUgMi42IDcuNSA3LjRjMCA1LjEtMy4xIDcuNy03LjQgNy43Wk02MS45MSA0My4xYTcgNyAwIDAgMS03LjItNy40YzAtNSAyLjgtNy43IDcuMS03LjdzNy41IDIuNiA3LjUgNy40YzAgNS4xLTMuMSA3LjctNy40IDcuN1pNNjEuOTEgMjAuNmE3IDcgMCAwIDEtNy4yLTcuNGMwLTUgMi44LTcuNyA3LjEtNy43czcuNSAyLjYgNy41IDcuNGMwIDUuMS0zLjEgNy43LTcuNCA3LjdaIi8+PC9zdmc+';
	
	//The icon in the data URI scheme
	$icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;

	// Ditty
	$labels = array(
		'name' 								=> __( 'Ditty', 'ditty-news-ticker' ),
		'singular_name' 			=> __( 'Ditty', 'ditty-news-ticker' ),
		'add_new' 						=> __( 'Add New', 'ditty-news-ticker' ),
		'add_new_item' 				=> __( 'Add New Ditty', 'ditty-news-ticker' ),
		'edit_item' 					=> __( 'Edit Ditty', 'ditty-news-ticker' ),
		'new_item' 						=> __( 'New Ditty', 'ditty-news-ticker' ),
		'view_item' 					=> __( 'View Ditty', 'ditty-news-ticker' ),
		'search_items' 				=> __( 'Search Ditty', 'ditty-news-ticker' ),
		'not_found' 					=> __( 'No Ditty Found', 'ditty-news-ticker' ),
		'not_found_in_trash' 	=> __( 'No Ditty Found In Trash', 'ditty-news-ticker' ),
		'parent_item_colon' 	=> '',
		'menu_name' 					=> __( 'Ditty', 'ditty-news-ticker' )
	);

	// Create the arguments
	$args = array(
		'labels' 							=> $labels,
		'public' 							=> false,
		'show_ui' 						=> true,
		'capability_type' 		=> 'ditty',
		'map_meta_cap' 				=> true,
		'show_in_menu' 				=> true, 
		'query_var' 					=> true,
		'rewrite' 						=> false,
		'menu_icon' 					=> $icon_data_uri,
		'supports'						=> array( 'title', 'author' ),
		'show_in_rest' 				=> true,
	);
	register_post_type( 'ditty', $args );	
	
	
	// Layout
	$labels = array(
		'name' 								=> __( 'Layouts', 'ditty-news-ticker' ),
		'singular_name' 			=> __( 'Layout', 'ditty-news-ticker' ),
		'add_new' 						=> __( 'Add New', 'ditty-news-ticker' ),
		'add_new_item' 				=> __( 'Add New Layout', 'ditty-news-ticker' ),
		'edit_item' 					=> __( 'Edit Layout', 'ditty-news-ticker' ),
		'new_item' 						=> __( 'New Layout', 'ditty-news-ticker' ),
		'view_item' 					=> __( 'View Layout', 'ditty-news-ticker' ),
		'search_items' 				=> __( 'Search Layouts', 'ditty-news-ticker' ),
		'not_found' 					=> __( 'No Layouts Found', 'ditty-news-ticker' ),
		'not_found_in_trash' 	=> __( 'No Layouts Found In Trash', 'ditty-news-ticker' ),
		'parent_item_colon' 	=> '',
		'menu_name' 					=> __( 'Layouts', 'ditty-news-ticker' )
	);

	// Create the arguments
	$args = array(
		'labels' 							=> $labels,
		'public' 							=> false,
		'show_ui' 						=> true,
		'capability_type' 		=> 'ditty_layout',
		'map_meta_cap' 				=> true,
		'show_in_menu' 				=> 'edit.php?post_type=ditty', 
		'query_var' 					=> true,
		'rewrite' 						=> false,
		'supports' 						=> array( 'title' ),
		'show_in_rest' 				=> true,
	);
	register_post_type( 'ditty_layout', $args );
	
	
	// Display
	$labels = array(
		'name' 								=> __( 'Displays', 'ditty-news-ticker' ),
		'singular_name' 			=> __( 'Display', 'ditty-news-ticker' ),
		'add_new' 						=> __( 'Add New', 'ditty-news-ticker' ),
		'add_new_item' 				=> __( 'Add New Display', 'ditty-news-ticker' ),
		'edit_item' 					=> __( 'Edit Display', 'ditty-news-ticker' ),
		'new_item' 						=> __( 'New Display', 'ditty-news-ticker' ),
		'view_item' 					=> __( 'View Display', 'ditty-news-ticker' ),
		'search_items' 				=> __( 'Search Displays', 'ditty-news-ticker' ),
		'not_found' 					=> __( 'No Displays Found', 'ditty-news-ticker' ),
		'not_found_in_trash' 	=> __( 'No Displays Found In Trash', 'ditty-news-ticker' ),
		'parent_item_colon' 	=> '',
		'menu_name' 					=> __( 'Displays', 'ditty-news-ticker' )
	);

	// Create the arguments
	$args = array(
		'labels' 							=> $labels,
		'public' 							=> false,
		'show_ui' 						=> true,
		'capability_type' 		=> 'ditty_display',
		'map_meta_cap' 				=> true,
		'show_in_menu' 				=> 'edit.php?post_type=ditty', 
		'query_var' 					=> true,
		'rewrite' 						=> false,
		'supports' 						=> array( 'title' ),
		'show_in_rest' 				=> true,
	);
	register_post_type( 'ditty_display', $args );
}
add_action( 'init', 'ditty_setup_post_types' );


/**
 * Modify the updated text
 * @since   3.0
 */
function ditty_updated_messages( $messages ) {

  $messages['ditty'][1] = __('Ditty Updated!', 'ditty-news-ticker');
  $messages['ditty_layout'][1] = __('Layout Updated!', 'ditty-news-ticker');
  $messages['ditty_display'][1] = __('Display Updated!', 'ditty-news-ticker');

  return $messages;
}
add_filter( 'post_updated_messages', 'ditty_updated_messages' );


