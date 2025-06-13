<?php

/**
 * Ditty Display Type Slider Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Type Slider
 * @copyright   Copyright (c) 2025, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
class Ditty_Display_Type_Slider extends Ditty_Display_Type {

	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'slider';
	public $js_settings = true;
  private $render_items = [];
  private $render_settings = [];

  public function render( $items, $settings ) {

    Ditty()->scripts->enqueue_display( $this->type );

    echo '<pre>';print_r( $settings );echo '</pre>';

    $args = [
      'class'             => 'wp-block-mtphr-ditty-display dittySlider',
      'selector'          => '.ditty-item',
      'initialSlide'      => 0,
      'autoheight'        => 0,
      'loop'              => 0,
      'mode'              => 'snap',
      'rubberband'        => 0,
      'vertical'          => 0,
      'animationDuration' => isset( $settings['transitionSpeed'] ) ? $settings['transitionSpeed'] * 1000 : false,
      'animationEasing'   => $settings['transitionEase'] ?? false,
      // Slides
      'slidesCenter'      => 0,
      'slidesPerView'     => $settings['slidesPerView'] ?? false,
      'slidesPerView'     => $settings['slidesPerView'] ?? false,
      // Arrows
      'arrows'              => $settings['arrows'] ?? false,
      'arrowPrevIcon'       => false,
      'arrowNextIcon'       => false,
      'arrowsPadding'       => [
        'top' => '20px',
        'right' => '20px',
        'bottom' => '20px',
        'left' => '20px'
      ],
      'arrowBorderRadius'   => '50%',
      'arrowIconWidth'      => '30px',
      'arrowIconColor'      => $settings['arrowsIconColor'] ?? false,
      'arrowIconHoverColor' => $settings['arrowsIconColor'] ?? false,
      'arrowBgColor'        => $settings['arrowsBgColor'] ?? false,
      'arrowBgHoverColor'   => $settings['arrowsBgColor'] ?? false,
      // Bullets
      'bullets'             => $settings['bullets'] ?? false,
      // Breakpoints
      'data-breakpoints'  => [],
    ];

    echo '<pre>';print_r( $args );echo '</pre>';

    return ditty_slider( $items, $args );
  }

}
