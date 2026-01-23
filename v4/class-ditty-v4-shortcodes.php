<?php
/**
 * Ditty V4 Shortcodes Class
 *
 * @package     Ditty
 * @subpackage  V4/Shortcodes
 * @copyright   Copyright (c) 2024, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Ditty_V4_Shortcodes {

	/**
	 * Get things started
	 *
	 * @access public
	 * @since  4.0
	 */
	public function __construct() {
		add_shortcode( 'ditty_display', [ $this, 'ditty_display_shortcode' ] );
	}

	/**
	 * Display the Ditty via shortcode
	 *
	 * @since  4.0
	 * @access public
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content.
	 * @return string HTML output.
	 */
	public function ditty_display_shortcode( $atts, $content = '' ) {
		if ( is_admin() ) {
			return '';
		}

		// Get defaults and merge with shortcode atts
		$defaults = Ditty_V4_Renderer::get_defaults();

		// Convert boolean defaults to string for shortcode compatibility
		$shortcode_defaults = [];
		foreach ( $defaults as $key => $value ) {
			if ( is_bool( $value ) ) {
				$shortcode_defaults[ $key ] = $value ? 'yes' : '';
			} else {
				$shortcode_defaults[ $key ] = $value;
			}
		}

		$args = shortcode_atts( $shortcode_defaults, $atts );

		// Convert string values back to appropriate types
		$args['speed']      = intval( $args['speed'] );
		$args['spacing']    = intval( $args['spacing'] );
		$args['hoverPause'] = ! empty( $args['hoverPause'] );
		$args['cloneItems'] = 'yes' === $args['cloneItems'] || true === $args['cloneItems'];

		// Remove wpautop formatting (br tags) and normalize line breaks
		$content = preg_replace( '/<br\s*\/?>/i', "\n", $content );
		$content = trim( $content );
		$items   = explode( "\n", str_replace( "\r", '', $content ) );
		$items   = array_map( 'trim', $items );
		$items   = array_filter( $items );

		// Sanitize items
		$items = array_map( 'wp_kses_post', $items );

		if ( empty( $items ) ) {
			return '';
		}

		// Use the shared renderer
		return Ditty_V4_Renderer::render( $items, $args );
	}
}
