<?php
namespace Ditty\Block\DittyDisplay;
use function Ditty\V4\get_spacing_preset_value;

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
\Ditty_V4_Renderer::enqueue_assets();

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

// Get carousel-specific attributes
$carousel_loop               = isset( $attributes['carouselLoop'] ) ? $attributes['carouselLoop'] : true;
$carousel_speed              = isset( $attributes['carouselSpeed'] ) ? intval( $attributes['carouselSpeed'] ) : 400;
$carousel_rewind             = ! empty( $attributes['carouselRewind'] );
$carousel_rewind_speed       = isset( $attributes['carouselRewindSpeed'] ) ? intval( $attributes['carouselRewindSpeed'] ) : 0;
$carousel_rewind_by_drag     = ! empty( $attributes['carouselRewindByDrag'] );
$carousel_height             = isset( $attributes['carouselHeight'] ) ? $attributes['carouselHeight'] : '';
$carousel_fixed_width        = isset( $attributes['carouselFixedWidth'] ) ? $attributes['carouselFixedWidth'] : '';
$carousel_fixed_height       = isset( $attributes['carouselFixedHeight'] ) ? $attributes['carouselFixedHeight'] : '';
$carousel_height_ratio       = isset( $attributes['carouselHeightRatio'] ) ? floatval( $attributes['carouselHeightRatio'] ) : 0;
$carousel_auto_width         = ! empty( $attributes['carouselAutoWidth'] );
$carousel_auto_height        = ! empty( $attributes['carouselAutoHeight'] );
$carousel_start              = isset( $attributes['carouselStart'] ) ? intval( $attributes['carouselStart'] ) : 0;
$carousel_per_page           = isset( $attributes['carouselPerPage'] ) ? intval( $attributes['carouselPerPage'] ) : 1;
$carousel_per_move           = isset( $attributes['carouselPerMove'] ) ? intval( $attributes['carouselPerMove'] ) : 0;
$carousel_focus              = isset( $attributes['carouselFocus'] ) ? $attributes['carouselFocus'] : '';
$carousel_arrows             = isset( $attributes['carouselArrows'] ) ? $attributes['carouselArrows'] : true;
$carousel_pagination         = isset( $attributes['carouselPagination'] ) ? $attributes['carouselPagination'] : true;
$carousel_pagination_dir     = isset( $attributes['carouselPaginationDirection'] ) ? $attributes['carouselPaginationDirection'] : '';
$carousel_easing             = isset( $attributes['carouselEasing'] ) ? $attributes['carouselEasing'] : 'cubic-bezier(0.25, 1, 0.5, 1)';
$carousel_drag               = isset( $attributes['carouselDrag'] ) ? $attributes['carouselDrag'] : 'true';
$carousel_snap               = ! empty( $attributes['carouselSnap'] );
$carousel_autoplay           = isset( $attributes['carouselAutoplay'] ) ? $attributes['carouselAutoplay'] : true;
$carousel_interval           = isset( $attributes['carouselInterval'] ) ? intval( $attributes['carouselInterval'] ) : 3000;
$carousel_pause_on_hover     = isset( $attributes['carouselPauseOnHover'] ) ? $attributes['carouselPauseOnHover'] : true;
$carousel_pause_on_focus     = isset( $attributes['carouselPauseOnFocus'] ) ? $attributes['carouselPauseOnFocus'] : true;
$carousel_reset_progress     = isset( $attributes['carouselResetProgress'] ) ? $attributes['carouselResetProgress'] : true;
$carousel_direction          = isset( $attributes['carouselDirection'] ) ? $attributes['carouselDirection'] : 'ltr';
$carousel_update_on_move     = ! empty( $attributes['carouselUpdateOnMove'] );

// Get gap/spacing from block supports
$spacing     = 25; // Default value (only used if blockGap is not set at all)
$gap_value   = null;
$has_gap_set = isset( $attributes['style']['spacing']['blockGap'] );

if ( $has_gap_set ) {
	$gap_value = $attributes['style']['spacing']['blockGap'];

	// Resolve theme preset spacing values (e.g. "var:preset|spacing|20")
	if ( is_string( $gap_value ) && 0 === strpos( $gap_value, 'var:preset|spacing|' ) ) {
		$preset_slug = str_replace( 'var:preset|spacing|', '', $gap_value );
    $preset_value = get_spacing_preset_value( $preset_slug );

		if ( '' !== $preset_value ) {
			$gap_value = $preset_value;
		}
	}
  
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

// For carousel, prepare gap value in CSS format for Splide
$carousel_gap = null;
if ( 'list' === $type ) {
	if ( $has_gap_set && null !== $gap_value ) {
		// Use the gap value as-is (already in CSS format like "25px", "1rem", etc.)
		$carousel_gap = $gap_value;
	} else {
		// Default gap for carousel
		$carousel_gap = '25px';
	}
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

// Add carousel-specific config if type is list/carousel
if ( 'list' === $type ) {
	$config['carousel'] = [
		'loop'               => $carousel_loop,
		'speed'              => $carousel_speed,
		'rewind'             => $carousel_rewind,
		'rewindSpeed'        => $carousel_rewind_speed,
		'rewindByDrag'       => $carousel_rewind_by_drag,
		'height'             => $carousel_height,
		'fixedWidth'         => $carousel_fixed_width,
		'fixedHeight'        => $carousel_fixed_height,
		'heightRatio'        => $carousel_height_ratio,
		'autoWidth'          => $carousel_auto_width,
		'autoHeight'         => $carousel_auto_height,
		'start'              => $carousel_start,
		'perPage'            => $carousel_per_page,
		'perMove'            => $carousel_per_move,
		'focus'              => $carousel_focus,
		'arrows'             => $carousel_arrows,
		'pagination'         => $carousel_pagination,
		'paginationDirection' => $carousel_pagination_dir,
		'easing'             => $carousel_easing,
		'drag'               => $carousel_drag,
		'snap'               => $carousel_snap,
		'autoplay'           => $carousel_autoplay,
		'interval'           => $carousel_interval,
		'pauseOnHover'       => $carousel_pause_on_hover,
		'pauseOnFocus'       => $carousel_pause_on_focus,
		'resetProgress'      => $carousel_reset_progress,
		'direction'          => $carousel_direction,
		'updateOnMove'       => $carousel_update_on_move,
		'gap'                => $carousel_gap,
	];
}

// echo '<pre>';
// print_r( $config );
// echo '</pre>';
// die();

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
			// Gap is handled by Splide's gap option, not inline styles
			echo '<div class="splide__track">';
			echo '<ul class="splide__list">';
			
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
