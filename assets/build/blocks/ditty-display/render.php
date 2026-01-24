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
$hover_pause = ! empty( $attributes['hoverPause'] );
$clone_items = isset( $attributes['cloneItems'] ) ? $attributes['cloneItems'] : true;
$item_max_width = isset( $attributes['itemMaxWidth'] ) ? $attributes['itemMaxWidth'] : '';
$min_height = isset( $attributes['minHeight'] ) ? $attributes['minHeight'] : '';
$fill_height = ! empty( $attributes['fillHeight'] );
$is_vertical = ( 'ticker' === $type && ( 'up' === $direction || 'down' === $direction ) );

// Get gap/spacing from block supports
$spacing     = 25; // Default value (only used if blockGap is not set at all)
$gap_value   = null;
$has_gap_set = isset( $attributes['style']['spacing']['blockGap'] );

if ( $has_gap_set ) {
	$gap_value = $attributes['style']['spacing']['blockGap'];
  
	// Parse the gap value (could be like "25px" or "1.5rem" or just "25")
	if ( is_numeric( $gap_value ) ) {
		// Explicitly cast to int, preserving 0
		$spacing = intval( $gap_value );
	} elseif ( is_string( $gap_value ) ) {
		// Handle string values like "0px", "25px", etc.
		// Special case: if the string is exactly "0" or starts with "0px"
		if ( preg_match( '/(\d+(?:\.\d+)?)/', $gap_value, $matches ) ) {
			$spacing = floatval( $matches[1] );
		}
	}
}

if ( 0 === $spacing ) {
	$spacing = -1;
}

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
	'ditty-display',
	'ditty-type-' . esc_attr( $type ),
];

if ( 'list' === $type ) {
	$wrapper_classes[] = 'splide';
}

// Render display-item blocks (they handle their own wrapper and styling)
$items = [];
if ( ! empty( $block->inner_blocks ) ) {
	foreach ( $block->inner_blocks as $inner_block ) {
		// Render using the WP_Block instance to preserve context from parent.
		$rendered = $inner_block->render();

		$rendered = trim( $rendered );
		if ( ! empty( $rendered ) ) {
			$items[] = $rendered;
		}
	}
}

// Build shared vertical styles (wrapper, contents, items)
$vertical_styles = [];
if ( $is_vertical ) {
	if ( $fill_height ) {
    $vertical_styles['flex-direction'] = 'flex-direction:column';
		$vertical_styles['flex'] = 'flex:1';
    $vertical_styles['height'] = 'height:100%';
	}
	if ( ! empty( $min_height ) ) {
		$vertical_styles['min-height'] = 'min-height:' . esc_attr( $min_height );
	}
}

// Get wrapper attributes from block supports
$wrapper_attributes = get_block_wrapper_attributes( [
	'class'            => implode( ' ', $wrapper_classes ),
	'data-ditty-config' => wp_json_encode( $config ),
	'style'            => ! empty( $vertical_styles ) ? implode( '; ', array_values( $vertical_styles ) ) . ';' : '',
] );

// Remove min-height from vertical styles
unset( $vertical_styles['min-height'] );

// Start output
echo '<div ' . $wrapper_attributes . '>';

// Render contents wrapper with items
if ( ! empty( $items ) ) {
	$contents_style_attr = ! empty( $vertical_styles ) ? ' style="' . implode( '; ', array_values( $vertical_styles ) ) . ';"' : '';
	echo '<div class="ditty-display__contents"' . $contents_style_attr . '>';
	
	// Build gap style for items container
	// Note: gap only works for carousel (splide), not ticker (absolute positioning)
	$gap_style = '';
	if ( $has_gap_set && null !== $gap_value ) {
		// Use the explicitly set gap value (including 0)
		$gap_style = ' style="gap:' . esc_attr( $gap_value ) . '"';
	} else {
		// Use default 25px gap when not explicitly set
		$gap_style = ' style="gap:25px"';
	}
	
	if ( 'ticker' === $type ) {
		// Ticker structure (gap doesn't work with absolute positioning, spacing handled by JS padding)
		// Apply height styles for vertical tickers
		$items_style_attr = ! empty( $vertical_styles ) ? ' style="' . implode( '; ', array_values( $vertical_styles ) ) . ';"' : '';
		
		echo '<div class="ditty-display__items"' . $items_style_attr . '>';
		
		foreach ( $items as $item ) {
			// display-item block already outputs .ditty__item wrapper
			echo $item;
		}
		
		echo '</div>'; // .ditty-display__items
	} else {
		// Carousel/Splide structure
		echo '<div class="splide__track">';
		echo '<ul class="splide__list"' . $gap_style . '>';
		
		foreach ( $items as $item ) {
			// Wrap each display-item in splide__slide
			echo '<li class="splide__slide">';
			echo $item;
			echo '</li>';
		}
		
		echo '</ul>';
		echo '</div>'; // .splide__track
	}
	
	echo '</div>'; // .ditty-display__contents
}

echo '</div>'; // .ditty-display
