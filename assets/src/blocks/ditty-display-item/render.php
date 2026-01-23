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

// Build wrapper attributes (spacing handled by blockGap on parent)
$wrapper_attributes = 'class="wp-block-ditty-display-item ditty-display__item"';


// Build inline styles for the inner elements div (max-width, white-space)
$elements_styles = [];

if ( ! empty( $item_max_width ) ) {
	$elements_styles[] = 'max-width:' . esc_attr( $item_max_width );
}

if ( 'nowrap' === $item_elements_wrap ) {
	$elements_styles[] = 'white-space:nowrap';
}

$elements_classes = [ 'ditty-display__item__elements' ];
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
