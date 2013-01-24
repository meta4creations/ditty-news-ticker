<?php
/**
 * Add custom taxonomies
 *
 * @package Ditty News Ticker
 */
 



add_action( 'init', 'mtphr_dnt_categories', 0 );
/**
 * Create a category taxonomy
 *
 * @since 1.0.0
 */
function mtphr_dnt_categories() {
  	
	// Create labels
	$labels = array(
		'name' => __('Categories', 'ditty-news-ticker'),
		'singular_name' => __('Category', 'ditty-news-ticker'),
		'search_items' =>  __('Search Categories', 'ditty-news-ticker'),
		'all_items' => __('All Categories', 'ditty-news-ticker'),
		'parent_item' => __('Parent', 'ditty-news-ticker'),
		'parent_item_colon' => __('Parent:', 'ditty-news-ticker'),
		'edit_item' => __('Edit Category', 'ditty-news-ticker'), 
		'update_item' => __('Update Category', 'ditty-news-ticker'),
		'add_new_item' => __('Add New Category', 'ditty-news-ticker'),
		'new_item_name' => __('New Category', 'ditty-news-ticker'),
		'menu_name' => __('Categories', 'ditty-news-ticker'),
	); 	 	
	
	// Create the arguments
	$args = array(
		'labels' => $labels
	); 
	
	// Register the taxonomy
	register_taxonomy( 'dnt_category', array( 'ditty_news_ticker' ), $args );
}
	
