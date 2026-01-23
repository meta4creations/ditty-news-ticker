<?php
/**
 * Ditty V4 Loader
 *
 * @package     Ditty
 * @subpackage  V4
 * @copyright   Copyright (c) 2024, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load V4 classes (order matters - renderer first as it's used by others)
require_once DITTY_DIR . 'v4/class-ditty-v4-renderer.php';
require_once DITTY_DIR . 'v4/class-ditty-v4-shortcodes.php';
require_once DITTY_DIR . 'v4/class-ditty-v4-blocks.php';

// Initialize V4 components
new Ditty_V4_Shortcodes();
new Ditty_V4_Blocks();
