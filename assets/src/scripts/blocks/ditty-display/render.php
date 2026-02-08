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

// Get slider-specific attributes
$slider_loop               = isset( $attributes['sliderLoop'] ) ? $attributes['sliderLoop'] : true;
$slider_speed              = isset( $attributes['sliderSpeed'] ) ? intval( $attributes['sliderSpeed'] ) : 400;
$slider_rewind             = ! empty( $attributes['sliderRewind'] );
$slider_rewind_speed       = isset( $attributes['sliderRewindSpeed'] ) ? intval( $attributes['sliderRewindSpeed'] ) : 0;
$slider_rewind_by_drag     = ! empty( $attributes['sliderRewindByDrag'] );
$slider_height             = isset( $attributes['sliderHeight'] ) ? $attributes['sliderHeight'] : '';
$slider_fixed_width        = isset( $attributes['sliderFixedWidth'] ) ? $attributes['sliderFixedWidth'] : '';
$slider_fixed_height       = isset( $attributes['sliderFixedHeight'] ) ? $attributes['sliderFixedHeight'] : '';
$slider_height_ratio       = isset( $attributes['sliderHeightRatio'] ) ? floatval( $attributes['sliderHeightRatio'] ) : 0;
$slider_auto_width         = ! empty( $attributes['sliderAutoWidth'] );
$slider_auto_height        = ! empty( $attributes['sliderAutoHeight'] );
$slider_start              = isset( $attributes['sliderStart'] ) ? intval( $attributes['sliderStart'] ) : 0;
$slider_per_page           = isset( $attributes['sliderPerPage'] ) ? intval( $attributes['sliderPerPage'] ) : 1;
$slider_per_move           = isset( $attributes['sliderPerMove'] ) ? intval( $attributes['sliderPerMove'] ) : 0;
$slider_focus              = isset( $attributes['sliderFocus'] ) ? $attributes['sliderFocus'] : '';
$slider_arrows             = isset( $attributes['sliderArrows'] ) ? $attributes['sliderArrows'] : true;
$slider_pagination         = isset( $attributes['sliderPagination'] ) ? $attributes['sliderPagination'] : true;
$slider_pagination_dir     = isset( $attributes['sliderPaginationDirection'] ) ? $attributes['sliderPaginationDirection'] : '';
$slider_easing             = isset( $attributes['sliderEasing'] ) ? $attributes['sliderEasing'] : 'cubic-bezier(0.25, 1, 0.5, 1)';
$slider_drag               = isset( $attributes['sliderDrag'] ) ? $attributes['sliderDrag'] : 'true';
$slider_snap               = ! empty( $attributes['sliderSnap'] );
$slider_autoplay           = isset( $attributes['sliderAutoplay'] ) ? $attributes['sliderAutoplay'] : true;
$slider_interval           = isset( $attributes['sliderInterval'] ) ? intval( $attributes['sliderInterval'] ) : 3000;
$slider_pause_on_hover     = isset( $attributes['sliderPauseOnHover'] ) ? $attributes['sliderPauseOnHover'] : true;
$slider_pause_on_focus     = isset( $attributes['sliderPauseOnFocus'] ) ? $attributes['sliderPauseOnFocus'] : true;
$slider_reset_progress     = isset( $attributes['sliderResetProgress'] ) ? $attributes['sliderResetProgress'] : true;
$slider_direction          = isset( $attributes['sliderDirection'] ) ? $attributes['sliderDirection'] : 'ltr';
$slider_update_on_move     = ! empty( $attributes['sliderUpdateOnMove'] );

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

// For slider, prepare gap value in CSS format for Splide
$slider_gap = null;
if ( 'slider' === $type ) {
	if ( $has_gap_set && null !== $gap_value ) {
		// Use the gap value as-is (already in CSS format like "25px", "1rem", etc.)
		$slider_gap = $gap_value;
	} else {
		// Default gap for slider
		$slider_gap = '25px';
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

// Add slider-specific config if type is slider
if ( 'slider' === $type ) {
	$config['slider'] = [
		'loop'               => $slider_loop,
		'speed'              => $slider_speed,
		'rewind'             => $slider_rewind,
		'rewindSpeed'        => $slider_rewind_speed,
		'rewindByDrag'       => $slider_rewind_by_drag,
		'height'             => $slider_height,
		'fixedWidth'         => $slider_fixed_width,
		'fixedHeight'        => $slider_fixed_height,
		'heightRatio'        => $slider_height_ratio,
		'autoWidth'          => $slider_auto_width,
		'autoHeight'         => $slider_auto_height,
		'start'              => $slider_start,
		'perPage'            => $slider_per_page,
		'perMove'            => $slider_per_move,
		'focus'              => $slider_focus,
		'arrows'             => $slider_arrows,
		'pagination'         => $slider_pagination,
		'paginationDirection' => $slider_pagination_dir,
		'easing'             => $slider_easing,
		'drag'               => $slider_drag,
		'snap'               => $slider_snap,
		'autoplay'           => $slider_autoplay,
		'interval'           => $slider_interval,
		'pauseOnHover'       => $slider_pause_on_hover,
		'pauseOnFocus'       => $slider_pause_on_focus,
		'resetProgress'      => $slider_reset_progress,
		'direction'          => $slider_direction,
		'updateOnMove'       => $slider_update_on_move,
		'gap'                => $slider_gap,
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

if ( 'slider' === $type ) {
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
			// Slider/Splide structure
			// Gap is handled by Splide's gap option, not inline styles
			echo '<div class="splide__track">';
			echo '<ul class="splide__list">';
        echo $content;
			echo '</ul>';
			echo '</div>'; // .splide__track
		}
		
		echo '</div>'; // .ditty-display__contents
	}

echo '</div>'; // .ditty-display
