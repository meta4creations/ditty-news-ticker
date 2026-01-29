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

// Initialize the layout renderer
if ( ! class_exists( 'Ditty_V4_Layout_Renderer' ) ) {
	require_once DITTY_DIR . 'v4/class-ditty-v4-layout-renderer.php';
}
$renderer = new Ditty_V4_Layout_Renderer();

// Check if we have a valid layout ID
$use_layout = ( $layout_id > 0 );

// If using a layout, buffer output and properly enqueue CSS
if ( $use_layout ) {
	// Buffer the posts HTML
	ob_start();
	$layout_css = '';
	
	foreach ( $posts as $post ) {
		setup_postdata( $post );
		
		// Render the post using the layout
		$result = $renderer->render_post_with_layout( $post, $layout_id );
		
		// Capture CSS from the first render
		if ( empty( $layout_css ) && ! empty( $result['css'] ) ) {
			$layout_css = $result['css'];
		}
		
		// Build the wrapper with layout-specific class
		$wrapper_class = 'wp-block-ditty-display-item wp-block-ditty-display-item--posts-feed ditty-display__item ditty-layout--' . esc_attr( $result['layout_id'] );
		
		// Output the post with layout rendering
		echo '<li class="splide__slide">';
		echo '<div class="' . esc_attr( $wrapper_class ) . '">';
		echo '<div class="ditty-display__item__elements">';
		echo $result['html'];
		echo '</div>';
		echo '</div>';
		echo '</li>';
	}
	
	$posts_html = ob_get_clean();
	
	// Enqueue CSS properly for both frontend and editor
	if ( ! empty( $layout_css ) ) {
		// Ensure ditty-v4 styles are enqueued
		if ( ! wp_style_is( 'ditty-v4', 'enqueued' ) ) {
			\Ditty_V4_Renderer::enqueue_assets();
		}
		
		// Add inline styles to the ditty-v4 handle
		// Use a unique handle for this specific layout to avoid conflicts
		$inline_style_handle = 'ditty-layout-' . $layout_id;
		
		// Check if already added to avoid duplicates
		global $wp_styles;
		if ( ! isset( $wp_styles->registered[ $inline_style_handle ] ) ) {
			// Register a dummy style handle
			wp_register_style( $inline_style_handle, false );
			wp_enqueue_style( $inline_style_handle );
			wp_add_inline_style( $inline_style_handle, $layout_css );
		}
	}
	
	echo $posts_html;
	
} else {
	// Fallback: render without layout (basic post content)
	foreach ( $posts as $post ) {
		setup_postdata( $post );
		
		$wrapper_attributes = 'class="wp-block-ditty-display-item ditty-display__item"';
		$post_content       = apply_filters( 'the_content', $post->post_content );
		
		printf(
			'<li class="splide__slide"><div %1$s><div class="ditty-display__item__elements">%2$s</div></div></li>',
			$wrapper_attributes,
			$post_content
		);
	}
}

// Reset post data
wp_reset_postdata();
