<?php
/**
 * Display Item Block - Server-Side Render
 *
 * Outputs the .ditty__item wrapper with block supports.
 *
 * @package Ditty
 * @subpackage Blocks
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content (rendered inner block).
 * @var WP_Block $block      Block instance.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get item settings from parent display block context
$item_max_width     = isset( $block->context['dittyDisplay/itemMaxWidth'] ) ? $block->context['dittyDisplay/itemMaxWidth'] : '';
$item_elements_wrap = isset( $block->context['dittyDisplay/itemElementsWrap'] ) ? $block->context['dittyDisplay/itemElementsWrap'] : 'nowrap';
$spacing            = isset( $block->context['dittyDisplay/spacing'] ) ? intval( $block->context['dittyDisplay/spacing'] ) : 25;
$direction          = isset( $block->context['dittyDisplay/direction'] ) ? $block->context['dittyDisplay/direction'] : 'left';


// Build padding styles for spacing on the outermost div
// Apply padding based on direction (left padding for left direction, right padding for right direction)
$wrapper_styles = [];
if ( $spacing > 0 ) {
	if ( 'left' === $direction ) {
		$wrapper_styles[] = 'padding-right:' . esc_attr( $spacing ) . 'px';
	} else {
		$wrapper_styles[] = 'padding-left:' . esc_attr( $spacing ) . 'px';
	}
}

// Build wrapper attributes
$wrapper_style_attr = ! empty( $wrapper_styles ) ? ' style="' . implode( ';', $wrapper_styles ) . '"' : '';
$wrapper_attributes = 'class="wp-block-ditty-display-item ditty__item" style="' . implode( ';', $wrapper_styles ) . '"';


// Build inline styles for the inner elements div (max-width, white-space)
$elements_styles = [];

if ( ! empty( $item_max_width ) ) {
	$elements_styles[] = 'max-width:' . esc_attr( $item_max_width );
}

if ( 'nowrap' === $item_elements_wrap ) {
	$elements_styles[] = 'white-space:nowrap';
}

$elements_classes = [ 'ditty-item__elements' ];
$elements_attributes = get_block_wrapper_attributes( [
	'class' => implode( ' ', $elements_classes ),
  'style' => implode( ';', $elements_styles ),
] );

$pattern = '/wp-block-ditty-display-item\s?/';
$elements_attributes = preg_replace( $pattern, '', $elements_attributes );

// Render inner block content
$inner_content = '';
if ( ! empty( $block->inner_blocks ) ) {
	$inner_content = render_block( $block->inner_blocks[0]->parsed_block );
}

printf(
	'<div %1$s><div %2$s>%3$s</div></div>',
	$wrapper_attributes,
	$elements_attributes,
	$inner_content
);
