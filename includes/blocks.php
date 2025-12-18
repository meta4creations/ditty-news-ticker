<?php
namespace Ditty\Blocks;

add_action( 'init', __NAMESPACE__ . '\register_blocks' );

/**
 * Register dynamic blocks.
 *
 * @since 3.1.32
 * @return void
 */
function register_blocks() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type( DITTY_DIR . 'assets/build/blocks/ditty' );
}
