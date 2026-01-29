<?php
/**
 * Ditty V4 Layout Renderer Class
 *
 * Simplified layout rendering system for blocks that reuses existing
 * ditty_layout posts and the tag processing system.
 *
 * @package     Ditty
 * @subpackage  V4/Layout_Renderer
 * @copyright   Copyright (c) 2024, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Parser\RegularParser;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Thunder\Shortcode\Syntax\Syntax;

class Ditty_V4_Layout_Renderer {

	/**
	 * Cache for layout data to avoid repeated queries
	 *
	 * @var array
	 */
	private $layout_cache = array();

	/**
	 * Cache for compiled CSS to avoid repeated compilation
	 *
	 * @var array
	 */
	private $css_cache = array();

	/**
	 * Render a post with a specific layout
	 *
	 * Main public method that handles the complete rendering process.
	 *
	 * @since  4.0
	 * @param  WP_Post $post       The post object to render.
	 * @param  int     $layout_id  The ditty_layout post ID.
	 * @param  string  $item_type  The item type for filtering (default: 'posts_feed').
	 * @return array  Array with 'html', 'css', and 'layout_id' keys.
	 */
	public function render_post_with_layout( $post, $layout_id, $item_type = 'posts_feed' ) {
		// Get the layout data
		$layout_data = $this->get_layout_data( $layout_id );

		if ( empty( $layout_data ) ) {
			return array(
				'html'      => '<p>' . __( 'Layout not found', 'ditty-news-ticker' ) . '</p>',
				'css'       => '',
				'layout_id' => $layout_id,
			);
		}

		// Prepare post data for tag processing
		$data = $this->prepare_post_data( $post );

		// Process the layout tags
		$processed_html = $this->process_layout_tags( 
			$layout_data['html'], 
			$data, 
			$item_type 
		);

		// Compile the CSS
		$compiled_css = $this->compile_css( $layout_data['css'], $layout_id );

		return array(
			'html'      => $processed_html,
			'css'       => $compiled_css,
			'layout_id' => $layout_id,
		);
	}

	/**
	 * Get layout data from a ditty_layout post
	 *
	 * Fetches the _ditty_layout_html and _ditty_layout_css meta values
	 * and caches them to avoid repeated queries.
	 *
	 * @since  4.0
	 * @param  int $layout_id The ditty_layout post ID.
	 * @return array|bool Array with 'html' and 'css' keys, or false if not found.
	 */
	private function get_layout_data( $layout_id ) {
		// Check cache first
		if ( isset( $this->layout_cache[ $layout_id ] ) ) {
			return $this->layout_cache[ $layout_id ];
		}

		// Verify the layout post exists
		$layout_post = get_post( $layout_id );
		if ( ! $layout_post || 'ditty_layout' !== $layout_post->post_type ) {
			$this->layout_cache[ $layout_id ] = false;
			return false;
		}

		// Get the layout meta
		$html = get_post_meta( $layout_id, '_ditty_layout_html', true );
		$css  = get_post_meta( $layout_id, '_ditty_layout_css', true );

		$layout_data = array(
			'html' => $html ? stripslashes( $html ) : '',
			'css'  => $css ? stripslashes( $css ) : '',
		);

		// Cache the result
		$this->layout_cache[ $layout_id ] = $layout_data;

		return $layout_data;
	}

	/**
	 * Prepare post data in the format expected by layout tag filters
	 *
	 * Structures the data to match the format used by existing filters
	 * in includes/layout-tag-hooks-posts.php
	 *
	 * @since  4.0
	 * @param  WP_Post $post The post object.
	 * @return array Structured data array.
	 */
	private function prepare_post_data( $post ) {
		// Basic post data structure
		$data = array(
			'item'      => $post,
			'item_meta' => array(
				'item_id'   => $post->ID,
				'item_type' => 'posts_feed',
			),
		);

		return $data;
	}

	/**
	 * Process layout tags using the Thunder Shortcode library
	 *
	 * This method processes the {tag} placeholders in the layout HTML,
	 * leveraging the existing ditty_layout_tags() system and filters.
	 *
	 * @since  4.0
	 * @param  string $html      The layout HTML with tags.
	 * @param  array  $data      The post data array.
	 * @param  string $item_type The item type for filtering.
	 * @return string Processed HTML with tags replaced.
	 */
	private function process_layout_tags( $html, $data, $item_type ) {
		// Get the available tags for this item type
		$tags = ditty_layout_tags( $item_type, $data );

		if ( ! is_array( $tags ) || empty( $tags ) ) {
			return $html;
		}

		// Create the handler container for shortcode processing
		$handlers = new HandlerContainer();

		// Add a handler for each tag
		foreach ( $tags as $tag_data ) {
			$handlers->add(
				$tag_data['tag'],
				function ( ShortcodeInterface $s ) use ( $tag_data, $data, $item_type ) {
					return $this->process_single_tag( $s, $tag_data, $data, $item_type );
				}
			);
		}

		// Create the processor with the custom syntax matching Ditty's format
		$syntax    = new Syntax( '{', '}', '/', '=', '"' );
		$processor = new Processor( new RegularParser( $syntax ), $handlers );

		// Process the HTML and return
		return stripslashes( $processor->process( $html ) );
	}

	/**
	 * Process a single layout tag
	 *
	 * Handles attribute parsing and applies the appropriate filters
	 * to render the tag content.
	 *
	 * @since  4.0
	 * @param  ShortcodeInterface $s         The shortcode object.
	 * @param  array              $tag_data  The tag configuration.
	 * @param  array              $data      The post data.
	 * @param  string             $item_type The item type.
	 * @return string|bool The rendered tag content or false.
	 */
	private function process_single_tag( $s, $tag_data, $data, $item_type ) {
		// Get default attributes from tag configuration
		$defaults = isset( $tag_data['atts'] ) ? $tag_data['atts'] : array();

		// Parse attributes from the shortcode (maintains array structure)
		$atts = $this->parse_tag_attributes( $defaults, $s );

		// Flatten attributes to simple values (like get_layout_att_values)
		$atts = $this->flatten_tag_attributes( $atts );

		// Apply the general layout tag atts filter
		$atts = apply_filters( 
			'ditty_layout_tag_atts', 
			$atts, 
			$tag_data['tag'], 
			$item_type, 
			$data 
		);

		// Get the shortcode content (for wrapper tags)
		$content = $s->getContent();

		// Check if there's a custom function for this tag
		if ( isset( $tag_data['func'] ) && function_exists( $tag_data['func'] ) ) {
			return call_user_func( 
				$tag_data['func'], 
				$tag_data['tag'], 
				$item_type, 
				$data, 
				$atts, 
				$content 
			);
		}

		// Otherwise, use the standard tag rendering
		return $this->render_tag( 
			$tag_data['tag'], 
			$item_type, 
			$data, 
			$atts, 
			$content 
		);
	}

	/**
	 * Parse tag attributes from shortcode parameters
	 *
	 * Merges default attributes with parameters provided in the shortcode.
	 * Maintains the array structure (with 'std' keys) for further processing.
	 *
	 * @since  4.0
	 * @param  array              $defaults Default attribute values.
	 * @param  ShortcodeInterface $s        The shortcode object.
	 * @return array Parsed attributes (may contain nested arrays).
	 */
	private function parse_tag_attributes( $defaults, $s ) {
		$parsed_atts = array();

		if ( ! is_array( $defaults ) ) {
			return $parsed_atts;
		}

		foreach ( $defaults as $key => $value ) {
			// Start with the default structure
			$parsed_atts[ $key ] = $defaults[ $key ];
			
			// Check if the shortcode has this parameter
			$custom_value = $s->getParameter( $key );

			if ( null !== $custom_value ) {
				$parsed_value = $custom_value;
			} else {
				// Use default value
				if ( is_array( $value ) ) {
					$parsed_value = isset( $value['std'] ) ? $value['std'] : '';
				} else {
					$parsed_value = $value;
				}
			}

			// Store the parsed value
			if ( is_array( $defaults[ $key ] ) ) {
				$parsed_atts[ $key ]['std'] = $parsed_value;
			} else {
				$parsed_atts[ $key ] = $parsed_value;
			}
		}

		return $parsed_atts;
	}

	/**
	 * Flatten tag attributes to simple key-value pairs
	 *
	 * Converts the nested array structure (with 'std' keys) to flat values.
	 * This matches the behavior of Ditty_Display_Item::get_layout_att_values().
	 *
	 * @since  4.0
	 * @param  array $atts The parsed attributes.
	 * @return array Flattened attributes.
	 */
	private function flatten_tag_attributes( $atts ) {
		$final_att_values = array();

		if ( ! is_array( $atts ) || empty( $atts ) ) {
			return $final_att_values;
		}

		foreach ( $atts as $key => $value ) {
			if ( is_array( $value ) ) {
				$final_att_values[ $key ] = isset( $value['std'] ) ? $value['std'] : '';
			} else {
				$final_att_values[ $key ] = $value;
			}
		}

		return $final_att_values;
	}

	/**
	 * Render a layout tag using the standard Ditty filter system
	 *
	 * Applies the ditty_layout_tag_{tag_name} filter and wraps
	 * the output with the standard rendering function.
	 *
	 * @since  4.0
	 * @param  string $tag        The tag name.
	 * @param  string $item_type  The item type.
	 * @param  array  $data       The post data.
	 * @param  array  $atts       The tag attributes.
	 * @param  string $content    The tag content (for wrapper tags).
	 * @return string|bool The rendered tag HTML or false.
	 */
	private function render_tag( $tag, $item_type, $data, $atts = array(), $content = '' ) {
		// Apply the tag-specific filter
		$output = apply_filters( 
			"ditty_layout_tag_{$tag}", 
			false, 
			$item_type, 
			$data, 
			$atts 
		);

		if ( ! $output ) {
			return false;
		}

		// Apply wpautop if requested
		if ( isset( $atts['wpautop'] ) && 'true' === strval( $atts['wpautop'] ) ) {
			$output = wpautop( $output );
		}

		// Use the standard Ditty rendering function to wrap the output
		if ( function_exists( 'ditty_layout_render_tag' ) ) {
			return ditty_layout_render_tag( 
				$output, 
				"ditty-item__{$tag}", 
				$item_type, 
				$data, 
				$atts, 
				$content 
			);
		}

		return $output;
	}

	/**
	 * Compile layout CSS with proper scoping for blocks
	 *
	 * Wraps the CSS with a layout-specific class to scope the styles.
	 * For blocks, we use .ditty-display as the parent selector instead of .ditty
	 *
	 * @since  4.0
	 * @param  string $css       The raw CSS from the layout.
	 * @param  int    $layout_id The layout ID for scoping.
	 * @return string Compiled and scoped CSS.
	 */
	private function compile_css( $css, $layout_id ) {
		// Check cache first
		if ( isset( $this->css_cache[ $layout_id ] ) ) {
			return $this->css_cache[ $layout_id ];
		}

		if ( empty( $css ) ) {
			$this->css_cache[ $layout_id ] = '';
			return '';
		}

		// For blocks, we use .ditty-display as the parent selector
		// This matches the wrapper class on the ditty-display block
		$compiled_css = '.ditty-display .ditty-layout--' . $layout_id . '{';
		$compiled_css .= html_entity_decode( $css );
		$compiled_css .= '}';

		// Try to compile SCSS if the Ditty_Layouts class is available
		if ( class_exists( 'Ditty' ) && 
		     isset( Ditty()->layouts ) && 
		     method_exists( Ditty()->layouts, 'compile_layout_style' ) ) {
			
			// Get a temporary compilation to process SCSS
			$temp_css = Ditty()->layouts->compile_layout_style( $css, $layout_id );
			
			// Replace .ditty with .ditty-display for block compatibility
			$compiled_css = str_replace( '.ditty .ditty-layout--', '.ditty-display .ditty-layout--', $temp_css );
		}

		// Cache the result
		$this->css_cache[ $layout_id ] = $compiled_css;

		return $compiled_css;
	}

	/**
	 * Clear the internal caches
	 *
	 * Useful for testing or when layout data changes.
	 *
	 * @since  4.0
	 */
	public function clear_cache() {
		$this->layout_cache = array();
		$this->css_cache    = array();
	}
}
