<?php

/**
 * Ditty Slider Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Slider
 * @copyright   Copyright (c) 2025, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.2
 */
class Ditty_Slider {

  private $type = 'keen';
  private $items;
  private $settings;

	public function __construct( $items, $args = [] ) {
    $this->items = $items;
    $this->settings = wp_parse_args( $args, $this->get_defaults() );
    if ( isset( $this->settings['type'] ) ) {
      $this->type = $this->settings['type'];
    }
	}

  /**
   * Set the slider type
   */
  public function set_type( $type ) {
    $this->type = $type;
  }

  /**
   * Return the slider type
   */
  public function get_type() {
    return $this->type;
  }

  /**
   * Return the items
   */
  public function get_items() {
    return $this->items;
  }

  /**
   * Return the default settings
   */
  private function get_defaults() {
    $defaults = [
      'type'              => 'keen',
      'class'             => '',
      'selector'          => '.ditty-item',
      'slideCount'        => 0,
      'initialSlide'      => 0,
      'autoheight'        => 0,
      'loop'              => 0,
      'mode'              => 'snap',
      'rubberband'        => 0,
      'vertical'          => 0,
      'animationDuration' => 1000,
      'animationEasing'   => 'easeInOutQuint',
      // Slides
      'slidesCenter'      => 0,
      'slidesPerView'     => 1,
      'slidesSpacing'     => 0,
      // Arrows
      'arrows'              => 0,
      'arrowPrevIcon'       => false,
      'arrowNextIcon'       => false,
      'arrowsPadding'       => [],
      'arrowBorderRadius'   => 0,
      'arrowIconWidth'      => '30px',
      'arrowIconColor'      => false,
      'arrowIconHoverColor' => false,
      'arrowBgColor'        => false,
      'arrowBgHoverColor'   => false,
      // Bullets
      'bullets'           => 0,
      // Breakpoints
      'data-breakpoints'  => [],
    ];
    
    return $defaults;
  }

  /**
   * Return the settings
   */
  private function get_settings() {
    return $this->settings;
  }

  /**
   * Render keen slider markup
   */
  public function render_keen( $atts = [] ) {

    $settings = $this->get_settings();
    $items = $this->get_items();

    $slide_count = $settings['slideCount'];
    $prev_icon = ( $settings['arrowPrevIcon'] ) ? $settings['arrowPrevIcon'] : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
      <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/>
    </svg>';
    $next_icon = ( $settings['arrowNextIcon'] ) ? $settings['arrowNextIcon'] : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
      <path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/>
    </svg>';
    
    $html = '';
    $html .= '<div ' . ditty_attr_to_html( $atts ) . '>';

      // Render the slider and items
      $html .= '<div class="dittySlider__slider keen-slider">';
        
      // If $items is an array, loop through and add them
      if ( is_array( $items ) ) {
        $slide_count = count( $items );
        if ( $slide_count > 0 ) {
          foreach ( $items as $item ) {
            $html .= $item;
          }
        }

      // Else add the content/slides
      } else {
        $html .= $items;
      } 
      $html .= '</div>';

      // Render the arrows
      if ( $settings['arrows'] && 'none' != $settings['arrows'] ) {
        $html .= '<div class="dittySlider__arrows">';
          $html .= '<button class="dittySlider__arrow dittySlider__arrow--left" aria-label="Previous slide">';
            $html .= ditty_kses_post( $prev_icon );
          $html .= '</button>';
          $html .= '<button class="dittySlider__arrow dittySlider__arrow--right" aria-label="Next slide">';
            $html .= ditty_kses_post( $next_icon );
          $html .= '</button>';
        $html .= '</div>';
      }

      // Render the bullets
      if ( $settings['bullets'] && 'none' != $settings['bullets'] ) {
        $html .= '<div class="dittySlider__bullets">';
          for ( $i = 0; $i < $slide_count; $i++ ) {
            $html .= '<button class="dittySlider__bullet" data-idx="' . esc_attr( $i ) . '"></button>';
          }
        $html .= '</div>';
      }

    $html .= '</div>';

    return $html;
  }

  /**
   * Render swiper slider markup
   */
  public function render_swiper( $atts = [] ) {

    $settings = $this->get_settings();
    $items = $this->get_items();

    $slide_count = $settings['slideCount'];
    $prev_icon = ( $settings['arrowPrevIcon'] ) ? $settings['arrowPrevIcon'] : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
      <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/>
    </svg>';
    $next_icon = ( $settings['arrowNextIcon'] ) ? $settings['arrowNextIcon'] : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
      <path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/>
    </svg>';

    $atts['class'] = $atts['class'] . ' swiper';
    
    $html = '';
    $html .= '<div ' . ditty_attr_to_html( $atts ) . '>';

      // Render the slider and items
      $html .= '<div class="dittySlider__slider swiper-wrapper">';
        
      // If $items is an array, loop through and add them
      if ( is_array( $items ) ) {
        $slide_count = count( $items );
        if ( $slide_count > 0 ) {
          foreach ( $items as $item ) {
            $html .= $item;
          }
        }

      // Else add the content/slides
      } else {
        $html .= $items;
      } 
      $html .= '</div>';

      // Render the arrows
      if ( $settings['arrows'] && 'none' != $settings['arrows'] ) {
        $html .= '<div class="swiper-button-prev"></div>';
        $html .= '<div class="swiper-button-next"></div>';
        // $html .= '<div class="dittySlider__arrows">';
        //   $html .= '<button class="dittySlider__arrow dittySlider__arrow--left" aria-label="Previous slide">';
        //     $html .= ditty_kses_post( $prev_icon );
        //   $html .= '</button>';
        //   $html .= '<button class="dittySlider__arrow dittySlider__arrow--right" aria-label="Next slide">';
        //     $html .= ditty_kses_post( $next_icon );
        //   $html .= '</button>';
        // $html .= '</div>';
      }

      // Render the bullets
      if ( $settings['bullets'] && 'none' != $settings['bullets'] ) {
        $html .= '<div class="swiper-pagination"></div>';
        // $html .= '<div class="dittySlider__bullets">';
        //   for ( $i = 0; $i < $slide_count; $i++ ) {
        //     $html .= '<button class="dittySlider__bullet" data-idx="' . esc_attr( $i ) . '"></button>';
        //   }
        // $html .= '</div>';
      }

    $html .= '</div>';

    return $html;
  }

  /**
   * Render the slider
   */
  public function render() {
    
    $type = $this->get_type();
    $settings = $this->get_settings();

    // Set the classes
    $class = 'dittySlider';
    if ( '' != $settings['class'] ) {
      $class .= ' ' . $settings['class'];
    }

    $arrows_padding = $settings['arrowsPadding'] ?? [];

    // Set the styles
    $styles = [
      '--ditty-slider--arrowsPadding-top' => $arrows_padding['top'] ?? 0,
      '--ditty-slider--arrowsPadding-right' => $arrows_padding['right'] ?? 0,
      '--ditty-slider--arrowsPadding-bottom' => $arrows_padding['bottom'] ?? 0,
      '--ditty-slider--arrowsPadding-left' => $arrows_padding['left'] ?? 0,
      //'--ditty-slider--arrowWidth' => isset( $attributes['arrowWidth'] ) ? "{$attributes['arrowWidth']}px" : '50px',
      //'--ditty-slider--arrowHeight' => isset( $attributes['arrowHeight'] ) ? "{$attributes['arrowHeight']}px" : '50px',
      '--ditty-slider--arrowBorderRadius' => $settings['arrowBorderRadius'] ?? false,
      '--ditty-slider--arrowIconWidth' => isset( $settings['arrowIconWidth'] ) ? "{$settings['arrowIconWidth']}px" : '30px',
      '--ditty-slider--arrowIconColor' => $settings['arrowIconColor'] ?? false,
      '--ditty-slider--arrowIconHoverColor' => $settings['arrowIconHoverColor'] ?? false,
      '--ditty-slider--arrowBgColor' => $settings['arrowBgColor'] ?? false,
      '--ditty-slider--arrowBgHoverColor' => $settings['arrowBgHoverColor'] ?? false,
    ];
    $styles_string = '';
    if ( is_array( $styles ) && count($styles ) > 0 ) {
      foreach ( $styles as $key => $value ) {
        if ( $value ) {
          $styles_string .= "{$key}:{$value};";
        } 
      }
    }

    $slider_atts = [
      'class'                   => $class,
      'style'                   => $styles_string,
      'data-type'               => $settings['type'],
      'data-selector'           => $settings['selector'],
      'data-initial'            => $settings['initialSlide'] ?? 0,
      'data-autoheight'         => ! empty( $settings['autoheight'] ) ? 'true' : 'false',
      'data-loop'               => ! empty( $settings['loop'] ) ? 'true' : 'false',
      'data-mode'               => $settings['mode'] ?? 'snap',
      'data-rubberband'         => ! empty( $settings['rubberband'] ) ? 'true' : 'false',
      'data-vertical'           => ! empty( $settings['vertical'] ) ? 'true' : 'false',
      'data-animation-duration' => $settings['animationDuration'] ?? 1000,
      'data-animation-easing'   => $settings['animationEasing']   ?? 'easeInOutQuint',
      // slides.*
      'data-center'             => ! empty( $settings['slidesCenter'] ) ? 'true' : 'false',
      'data-per-view'           => $settings['slidesPerView'] ?? 1,
      'data-spacing'            => $settings['slidesSpacing'] ?? 0,
      // the breakpoints array as a JSON string
      'data-breakpoints'        => ! empty( $settings['sliderBreakpoints'] )
        ? wp_json_encode( $settings['sliderBreakpoints'] )
        : '[]',
    ];

    if ( 'keen' == $type ) {
      return $this->render_keen( $slider_atts );
    } else {
      return $this->render_swiper( $slider_atts );
    }
  }
}
