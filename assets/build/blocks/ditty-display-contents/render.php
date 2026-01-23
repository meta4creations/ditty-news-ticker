<?php
/**
 * Ditty Display Contents Block - Server-Side Render
 *
 * Renders the display items in the appropriate structure for ticker or carousel.
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

// Get display type from parent context
$type = isset( $block->context['dittyDisplay/type'] ) ? $block->context['dittyDisplay/type'] : 'ticker';

// Render display-item blocks (they handle their own wrapper and styling)
$items = [];
if ( ! empty( $block->inner_blocks ) ) {
	foreach ( $block->inner_blocks as $inner_block ) {
		$rendered = render_block( $inner_block->parsed_block );
		$rendered = trim( $rendered );
		if ( ! empty( $rendered ) ) {
			$items[] = $rendered;
		}
	}
}

// If no items, don't render anything
if ( empty( $items ) ) {
	return;
}

// Get wrapper attributes from block supports (for the contents wrapper)
$wrapper_attributes = get_block_wrapper_attributes( [
	'class' => 'ditty__contents',
] );

// Start output
$html = '<div ' . $wrapper_attributes . '>';

if ( 'ticker' === $type ) {
	// Ticker structure
	$html .= '<div class="ditty-display__items">';

	foreach ( $items as $item ) {
		// display-item block already outputs .ditty-display__item wrapper
		$html .= $item;
	}

	$html .= '</div>'; // .ditty-display__items
} else {
	// Carousel/Splide structure
	$html .= '<div class="splide__track">';
	$html .= '<ul class="splide__list">';

	foreach ( $items as $item ) {
		// Wrap each display-item in splide__slide
		$html .= '<li class="splide__slide">';
		$html .= $item;
		$html .= '</li>';
	}

	$html .= '</ul>';
	$html .= '</div>'; // .splide__track
}

$html .= '</div>'; // .ditty-display__contents

echo $html;
