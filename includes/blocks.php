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
	// Block registration is now handled by v4/class-ditty-v4-blocks.php
	// register_block_type( DITTY_DIR . 'assets/build/scripts/blocks/ditty' );
}
