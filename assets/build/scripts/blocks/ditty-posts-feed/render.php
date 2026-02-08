<?php
/**
 * Posts Feed Block - Server-Side Render
 *
 * Fetches and displays recent blog posts using ditty_layout templates.
 *
 * @package Ditty
 * @subpackage Blocks
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load v4 helpers
require_once DITTY_DIR . 'v4/helpers.php';

// Get the limit and layout attributes
$limit     = isset( $attributes['limit'] ) ? intval( $attributes['limit'] ) : 5;
$layout_id = isset( $attributes['layout'] ) ? intval( $attributes['layout'] ) : 0;

// Query recent posts
$posts = get_posts(
	array(
		'numberposts' => $limit,
		'post_status' => 'publish',
		'orderby'     => 'date',
		'order'       => 'DESC',
	)
);

// If no posts found, return early
if ( empty( $posts ) ) {
	return;
}

// Check if we have a valid layout ID
$use_layout = ( $layout_id > 0 );

if ( $use_layout ) {
	// Use the reusable helper to render posts with layout
	Ditty\V4\render_items_with_layout(
		$posts,
		$layout_id,
		function( $post, $renderer, $layout_id ) {
			// Render the post using the layout
			return $renderer->render_post_with_layout( $post, $layout_id );
		},
		array(
			'block_modifier'  => 'posts-feed',
			'wrapper_element' => 'li',
			'wrapper_class'   => 'splide__slide',
			'setup_postdata'  => true,
		)
	);
} else {
	// Fallback: render without layout (basic post content)
	foreach ( $posts as $post ) {
		setup_postdata( $post );
		
		$wrapper_attributes = 'class="wp-block-ditty-display-item ditty-item"';
		$post_content       = apply_filters( 'the_content', $post->post_content );
		
		printf(
			'<li class="splide__slide"><div %1$s><div class="ditty-item__elements">%2$s</div></div></li>',
			$wrapper_attributes,
			$post_content
		);
	}
	
	// Reset post data
	wp_reset_postdata();
}
