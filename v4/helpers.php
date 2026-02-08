<?php
namespace Ditty\V4;

/**
 * Get the value of a spacing preset
 *
 * @param string $slug The slug of the spacing preset
 * @return int|null The value of the spacing preset
 */
function get_spacing_preset_value( $slug ) {
  if ( is_string( $slug ) && 0 === strpos( $slug, 'var:preset|spacing|' ) ) {
    $slug = str_replace( 'var:preset|spacing|', '', $slug );
  }
  
  $settings = wp_get_global_settings( array( 'spacing', 'spacingSizes' ) );

  // 1. Check Theme presets first (higher priority)
  if ( ! empty( $settings['theme'] ) ) {
    foreach ( $settings['theme'] as $preset ) {
      if ( $preset['slug'] === (string) $slug ) {
        return $preset['size'];
      }
    }
  }

  // 2. Fallback to Default presets
  if ( ! empty( $settings['default'] ) ) {
    foreach ( $assettings['default'] as $preset ) {
      if ( $preset['slug'] === (string) $slug ) {
        return $preset['size'];
      }
    }
  }

  return null;
}

/**
 * Render items with a ditty layout
 *
 * This helper function handles the complete workflow of rendering items
 * with a ditty_layout, including CSS management for both editor and frontend.
 *
 * @param array    $items             Array of items to render (e.g., WP_Post objects).
 * @param int      $layout_id         The ID of the ditty_layout to use for rendering.
 * @param callable $render_callback   Callback function to render each item with the layout.
 *                                     Signature: function( $item, $renderer, $layout_id ) : array
 *                                     Should return array with 'html', 'css', and 'layout_id' keys.
 * @param array    $args {
 *     Optional. Additional arguments.
 *
 *     @type string $block_modifier    Additional CSS class modifier for the block (e.g., 'posts-feed').
 *     @type string $wrapper_element   HTML element to wrap each item (default: 'li').
 *     @type string $wrapper_class     CSS class for the wrapper element (default: 'splide__slide').
 *     @type bool   $setup_postdata    Whether to call setup_postdata() for each item (default: false).
 * }
 * @return void Outputs the rendered HTML directly.
 */
function render_items_with_layout( $items, $layout_id, $render_callback, $args = array() ) {
	// Return early if no items or invalid layout
	if ( empty( $items ) || ! is_callable( $render_callback ) ) {
		return;
	}

	// Parse arguments with defaults
	$args = wp_parse_args(
		$args,
		array(
			'block_modifier'  => '',
			'wrapper_element' => 'li',
			'wrapper_class'   => 'splide__slide',
			'setup_postdata'  => false,
		)
	);

	// Initialize the layout renderer
	if ( ! class_exists( 'Ditty_V4_Layout_Renderer' ) ) {
		require_once DITTY_DIR . 'v4/class-ditty-v4-layout-renderer.php';
	}
	$renderer = new \Ditty_V4_Layout_Renderer();

	// Check if we have a valid layout ID
	$use_layout = ( $layout_id > 0 );

	if ( ! $use_layout ) {
		return;
	}

	// Buffer the items HTML
	ob_start();
	$layout_css = '';

	foreach ( $items as $item ) {
		// Setup postdata if needed (for WP_Post objects)
		if ( $args['setup_postdata'] && $item instanceof \WP_Post ) {
			setup_postdata( $item );
		}

		// Call the render callback
		$result = call_user_func( $render_callback, $item, $renderer, $layout_id );

		// Validate result
		if ( ! is_array( $result ) || empty( $result['html'] ) ) {
			continue;
		}

		// Capture CSS from the first render
		if ( empty( $layout_css ) && ! empty( $result['css'] ) ) {
			$layout_css = $result['css'];
		}

		// Build the wrapper with layout-specific class
		$wrapper_classes = array(
			'wp-block-ditty-display-item',
			'ditty-item',
			'ditty-layout--' . esc_attr( $result['layout_id'] ),
		);

		// Add block modifier if provided
		if ( ! empty( $args['block_modifier'] ) ) {
			$wrapper_classes[] = 'wp-block-ditty-display-item--' . esc_attr( $args['block_modifier'] );
		}

		$wrapper_class = implode( ' ', $wrapper_classes );

		// Output the item with layout rendering
		if ( ! empty( $args['wrapper_element'] ) ) {
			printf( '<%s class="%s">', esc_attr( $args['wrapper_element'] ), esc_attr( $args['wrapper_class'] ) );
		}
		echo '<div class="' . esc_attr( $wrapper_class ) . '">';
		echo '<div class="ditty-item__elements">';
		echo $result['html'];
		echo '</div>';
		echo '</div>';
		if ( ! empty( $args['wrapper_element'] ) ) {
			printf( '</%s>', esc_attr( $args['wrapper_element'] ) );
		}
	}

	// Reset postdata if we used it
	if ( $args['setup_postdata'] ) {
		wp_reset_postdata();
	}

	$items_html = ob_get_clean();

	// Handle CSS output for both frontend and editor
	handle_layout_css( $layout_id, $layout_css );

	// Output the items HTML
	echo $items_html;
}

/**
 * Handle layout CSS output for both editor and frontend contexts
 *
 * @param int    $layout_id  The layout ID.
 * @param string $layout_css The CSS to output.
 * @return void
 */
function handle_layout_css( $layout_id, $layout_css ) {
	if ( empty( $layout_css ) ) {
		return;
	}

	// Check if we're in the block editor context
	$is_editor = defined( 'REST_REQUEST' ) && REST_REQUEST;

	if ( $is_editor ) {
		// Track which layouts have already output their CSS in this request
		static $rendered_layouts = array();

		// In editor: output inline styles directly so ServerSideRender can display them
		// Only output once per layout ID per request
		if ( ! in_array( $layout_id, $rendered_layouts, true ) ) {
			echo '<style id="ditty-layout-' . esc_attr( $layout_id ) . '-inline-css">';
			echo wp_strip_all_tags( $layout_css );
			echo '</style>';
			$rendered_layouts[] = $layout_id;
		}
	} else {
		// On frontend: properly enqueue styles
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
}