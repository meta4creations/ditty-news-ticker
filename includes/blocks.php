<?php

/**
 * Register dynamic blocks.
 *
 * @since 3.0.31
 * @return void
 */
function ditty_register_dynamic_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type( DITTY_DIR . '/build/blocks/ditty', [
		'render_callback' => 'ditty_render_dynamic_block_ditty',
	] );
}
add_action( 'init', 'ditty_register_dynamic_blocks' );

/**
 * Render the ditty block
 *
 * @since 3.0.31
 * @return void
 */
function ditty_render_dynamic_block_ditty( $atts ) {
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