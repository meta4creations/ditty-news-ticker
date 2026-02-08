<?php
$type = $attributes['type'] ?? 'slider';
$style = $attributes['style'] ?? [];
$border = $style['border'] ?? [];
$arrows_padding = $attributes['arrowsPadding'] ?? [];
$slide_count = substr_count( $content, 'ditty-item' );

$args = $attributes;
$args['class'] = 'wp-block-mtphr-ditty-display dittySlider';
$args['slideCount'] = $slide_count;

echo ditty_slider( $content, $args );


//echo '<pre>';print_r( $attributes );echo '</pre>';

// Add custom styles
// $styles = [
//   '--ditty-slider--arrowsPadding-top' => $arrows_padding['top'] ?? 0,
//   '--ditty-slider--arrowsPadding-right' => $arrows_padding['right'] ?? 0,
//   '--ditty-slider--arrowsPadding-bottom' => $arrows_padding['bottom'] ?? 0,
//   '--ditty-slider--arrowsPadding-left' => $arrows_padding['left'] ?? 0,
//   //'--ditty-slider--arrowWidth' => isset( $attributes['arrowWidth'] ) ? "{$attributes['arrowWidth']}px" : '50px',
//   //'--ditty-slider--arrowHeight' => isset( $attributes['arrowHeight'] ) ? "{$attributes['arrowHeight']}px" : '50px',
//   '--ditty-slider--arrowBorderRadius' => $attributes['arrowBorderRadius'] ?? false,
//   '--ditty-slider--arrowIconWidth' => isset( $attributes['arrowIconWidth'] ) ? "{$attributes['arrowIconWidth']}px" : '30px',
//   '--ditty-slider--arrowIconColor' => $attributes['arrowIconColor'] ?? false,
//   '--ditty-slider--arrowIconHoverColor' => $attributes['arrowIconHoverColor'] ?? false,
//   '--ditty-slider--arrowBgColor' => $attributes['arrowBgColor'] ?? false,
//   '--ditty-slider--arrowBgHoverColor' => $attributes['arrowBgHoverColor'] ?? false,
// ];
// $styles_string = '';
// if ( is_array( $styles ) && count($styles ) > 0 ) {
//   foreach ( $styles as $key => $value ) {
//     if ( $value ) {
//       $styles_string .= "{$key}:{$value};";
//     } 
//   }
// }

// Set up the block attributes
// $block_attributes = get_block_wrapper_attributes([
//   'class'           => 'ditty dittySlider',
//   'style'           => $styles_string,
//   'data-type'       => $type,
//   'data-initial'    => $attributes['initialSlide'] ?? 0,
//   'data-autoheight' => ! empty( $attributes['autoheight'] ) ? 'true' : 'false',
//   'data-loop'       => ! empty( $attributes['loop'] ) ? 'true' : 'false',
//   'data-mode'       => $attributes['mode'] ?? 'snap',
//   'data-rubberband' => ! empty( $attributes['rubberband'] ) ? 'true' : 'false',
//   'data-vertical'   => ! empty( $attributes['vertical'] ) ? 'true' : 'false',
//   'data-animation-duration' => $attributes['animationDuration'] ?? 1000,
//   'data-animation-easing'   => $attributes['animationEasing']   ?? 'easeInOutQuint',
//   // slides.*
//   'data-center'     => ! empty( $attributes['slidesCenter'] ) ? 'true' : 'false',
//   'data-per-view'   => $attributes['slidesPerView'] ?? 1,
//   'data-spacing'    => $attributes['slidesSpacing'] ?? 0,
//   // the breakpoints array as a JSON string
//   'data-breakpoints'=> ! empty( $attributes['sliderBreakpoints'] )
//     ? wp_json_encode( $attributes['sliderBreakpoints'] )
//     : '[]',
// ] );

// // output the wrapper with all our data attributes
// echo '<div ' . $block_attributes . '>';
//   switch ( $type ) {
//     case 'slider':
//       include_once( __DIR__ . '/types/slider.php' );
//       break;
//     default:
//       do_action( 'dittyPro/block/display', $type, $attributes );
//       break;
//   }
// echo '</div>';
