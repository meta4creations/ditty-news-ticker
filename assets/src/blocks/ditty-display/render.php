<?php
/**
 * Ditty Display Block - Server-Side Render
 *
 * This block provides the outer wrapper with block supports styling.
 * The Display Title and Display Contents child blocks render themselves.
 *
 * @package Ditty
 * @subpackage Blocks
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content (rendered inner blocks).
 * @var WP_Block $block      Block instance.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Enqueue the display assets
Ditty_V4_Renderer::enqueue_assets();

// Get display attributes for the JS config
$type        = isset( $attributes['type'] ) ? $attributes['type'] : 'ticker';
$direction   = isset( $attributes['direction'] ) ? $attributes['direction'] : 'left';
$speed       = isset( $attributes['speed'] ) ? intval( $attributes['speed'] ) : 10;
$spacing     = isset( $attributes['spacing'] ) ? intval( $attributes['spacing'] ) : 25;
$hover_pause = ! empty( $attributes['hoverPause'] );
$clone_items = isset( $attributes['cloneItems'] ) ? $attributes['cloneItems'] : true;

// Build JS config
$config = [
	'type'       => $type,
	'direction'  => $direction,
	'speed'      => $speed,
	'spacing'    => $spacing,
	'hoverPause' => $hover_pause,
	'cloneItems' => $clone_items ? 'yes' : 'no',
];

// Build wrapper classes
$wrapper_classes = [
	'ditty',
	'ditty-type-' . esc_attr( $type ),
];

if ( 'list' === $type ) {
	$wrapper_classes[] = 'splide';
}

// Get wrapper attributes from block supports
$wrapper_attributes = get_block_wrapper_attributes( [
	'class'            => implode( ' ', $wrapper_classes ),
	'data-ditty-config' => wp_json_encode( $config ),
] );

// The $content variable contains the rendered inner blocks (Display Title and Display Contents)
// which have their own render.php files that handle their output

printf(
	'<div %1$s>%2$s</div>',
	$wrapper_attributes,
	$content
);
