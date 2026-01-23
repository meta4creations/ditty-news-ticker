<?php
/**
 * Ditty V4 Blocks Class
 *
 * Handles block registration for Gutenberg blocks.
 *
 * @package     Ditty
 * @subpackage  V4/Blocks
 * @copyright   Copyright (c) 2024, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Ditty_V4_Blocks {

	/**
	 * Get things started
	 *
	 * @access public
	 * @since  4.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_blocks' ] );
	}

	/**
	 * Register blocks
	 *
	 * @access public
	 * @since  4.0
	 */
	public function register_blocks() {
		$blocks = [
			'ditty-display',
			'ditty-display-title',
			'ditty-display-contents',
		];

		foreach ( $blocks as $block ) {
			$block_path = DITTY_DIR . 'assets/build/blocks/' . $block;

			if ( file_exists( $block_path . '/block.json' ) ) {
				register_block_type( $block_path );
			}
		}
	}
}
