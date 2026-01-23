<?php
/**
 * Ditty Display Title Block - Server-Side Render
 *
 * @package Ditty
 * @subpackage Blocks
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get context from parent block
$show_title = isset( $block->context['dittyDisplay/showTitle'] ) ? $block->context['dittyDisplay/showTitle'] : true;

// If title should be hidden, return empty
if ( ! $show_title ) {
	return;
}

// Get attributes
$title_content = isset( $attributes['content'] ) ? $attributes['content'] : '';
$level         = isset( $attributes['level'] ) ? intval( $attributes['level'] ) : 3;

// If no content, don't render
if ( empty( $title_content ) ) {
	return;
}

// Get wrapper attributes from block supports
$wrapper_attributes = get_block_wrapper_attributes( [
	'class' => 'ditty__title',
] );

// Build the tag
$tag = 'h' . $level;

printf(
	'<%1$s %2$s>%3$s</%1$s>',
	esc_attr( $tag ),
	$wrapper_attributes,
	wp_kses_post( $title_content )
);
