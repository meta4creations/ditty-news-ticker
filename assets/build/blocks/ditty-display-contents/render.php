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

// Get context from parent block
$type              = isset( $block->context['dittyDisplay/type'] ) ? $block->context['dittyDisplay/type'] : 'ticker';
$item_bg_color     = isset( $block->context['dittyDisplay/itemBgColor'] ) ? $block->context['dittyDisplay/itemBgColor'] : '';
$item_padding      = isset( $block->context['dittyDisplay/itemPadding'] ) ? $block->context['dittyDisplay/itemPadding'] : '';
$item_border_color = isset( $block->context['dittyDisplay/itemBorderColor'] ) ? $block->context['dittyDisplay/itemBorderColor'] : '';
$item_border_style = isset( $block->context['dittyDisplay/itemBorderStyle'] ) ? $block->context['dittyDisplay/itemBorderStyle'] : '';
$item_border_width = isset( $block->context['dittyDisplay/itemBorderWidth'] ) ? $block->context['dittyDisplay/itemBorderWidth'] : '';
$item_border_radius = isset( $block->context['dittyDisplay/itemBorderRadius'] ) ? $block->context['dittyDisplay/itemBorderRadius'] : '';
$item_max_width    = isset( $block->context['dittyDisplay/itemMaxWidth'] ) ? $block->context['dittyDisplay/itemMaxWidth'] : '';
$item_elements_wrap = isset( $block->context['dittyDisplay/itemElementsWrap'] ) ? $block->context['dittyDisplay/itemElementsWrap'] : 'nowrap';

// Build item styles
$item_styles = [];
if ( ! empty( $item_bg_color ) ) {
	$item_styles[] = 'background-color:' . esc_attr( $item_bg_color );
}
if ( ! empty( $item_padding ) ) {
	$item_styles[] = 'padding:' . esc_attr( $item_padding );
}
if ( ! empty( $item_border_color ) ) {
	$item_styles[] = 'border-color:' . esc_attr( $item_border_color );
}
if ( ! empty( $item_border_style ) ) {
	$item_styles[] = 'border-style:' . esc_attr( $item_border_style );
}
if ( ! empty( $item_border_width ) ) {
	$item_styles[] = 'border-width:' . esc_attr( $item_border_width );
}
if ( ! empty( $item_border_radius ) ) {
	$item_styles[] = 'border-radius:' . esc_attr( $item_border_radius );
}
if ( ! empty( $item_max_width ) ) {
	$item_styles[] = 'max-width:' . esc_attr( $item_max_width );
}
if ( 'nowrap' === $item_elements_wrap ) {
	$item_styles[] = 'white-space:nowrap';
}
$item_style_string = ! empty( $item_styles ) ? ' style="' . implode( ';', $item_styles ) . '"' : '';

// Collect items from inner blocks
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
	$html .= '<div class="ditty__items">';
	
	foreach ( $items as $item ) {
		$html .= '<div class="ditty__item"' . $item_style_string . '>';
		$html .= $item;
		$html .= '</div>';
	}
	
	$html .= '</div>'; // .ditty__items
} else {
	// Carousel/Splide structure
	$html .= '<div class="splide__track">';
	$html .= '<ul class="splide__list">';
	
	foreach ( $items as $item ) {
		$html .= '<li class="splide__slide">';
		$html .= '<div class="ditty__item"' . $item_style_string . '>';
		$html .= $item;
		$html .= '</div>';
		$html .= '</li>';
	}
	
	$html .= '</ul>';
	$html .= '</div>'; // .splide__track
}

$html .= '</div>'; // .ditty__contents

echo $html;
