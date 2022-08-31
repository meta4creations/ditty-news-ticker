<?php

namespace Metaphor_Creations\Ditty\Blocks\Ditty;

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
	register_block_type( DITTY_DIR . '/build/blocks/ditty', [
		'render_callback' => __NAMESPACE__ . '\render_dynamic_block',
	] );
}
add_action( 'init', __NAMESPACE__ . '\register_dynamic_block' );

/**
 * Server rendering for /blocks/examples/12-dynamic
 */
function render_dynamic_block( $atts ) {
	if ( is_admin() ) {
		return false;
	}
	$args = array(
		'id' 			=> isset( $atts['ditty'] ) 			    ? intval( $atts['ditty'] ) : false,
		'display' => isset( $atts['display'] ) 		    ? sanitize_text_field( $atts['display'] ) : false,
    'el_id'		=> isset( $atts['customID'] ) 	    ? sanitize_title( $atts['customID'] ) : false,
		'class'		=> isset( $atts['customClasses'] ) 	? esc_attr( $atts['customClasses'] ) : false,
	);
	return ditty_render( $args );
}