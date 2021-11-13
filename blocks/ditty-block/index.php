<?php

namespace Metaphor_Creations\Ditty\Blocks\Ditty_Block;

/**
 * Register the dynamic block.
 *
 * @since 1.0
 * @return void
 */
function register_dynamic_block() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	// Hook server side rendering into render callback
	register_block_type( 'metaphorcreations/ditty-block', [
		'render_callback' => __NAMESPACE__ . '\render_dynamic_block',
	] );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\register_dynamic_block' );

/**
 * Server rendering for /blocks/examples/12-dynamic
 */
function render_dynamic_block( $atts ) {
	if ( is_admin() ) {
		return false;
	}
	$args = array(
		'id' 			=> isset( $atts['ditty'] ) 			? intval( $atts['ditty'] ) : false,
		'display' => isset( $atts['display'] ) 		? sanitize_text_field( $atts['display'] ) : false,
		'class'		=> isset( $atts['className'] ) 	? sanitize_text_field( $atts['className'] ) : false,
	);
	return ditty_render( $args );
}
