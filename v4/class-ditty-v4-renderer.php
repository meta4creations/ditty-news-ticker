<?php
/**
 * Ditty V4 Renderer Class
 *
 * Shared rendering logic for Ditty displays (shortcodes and blocks).
 *
 * @package     Ditty
 * @subpackage  V4/Renderer
 * @copyright   Copyright (c) 2024, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Ditty_V4_Renderer {

	/**
	 * Track if assets have been enqueued
	 *
	 * @var bool
	 */
	private static $assets_enqueued = false;

	/**
	 * Normalize a style value to a string.
	 *
	 * @since 4.0
	 * @param mixed $value Style value that may be string or array.
	 * @return string Normalized style string or empty string.
	 */
	private static function normalize_style_value( $value ) {
		if ( is_array( $value ) ) {
			$order = [ 'top', 'right', 'bottom', 'left' ];
			$parts = [];
			foreach ( $order as $side ) {
				if ( isset( $value[ $side ] ) && '' !== $value[ $side ] ) {
					$parts[] = $value[ $side ];
				}
			}
			if ( empty( $parts ) ) {
				foreach ( $value as $item ) {
					if ( '' !== $item ) {
						$parts[] = $item;
					}
				}
			}
			return implode( ' ', array_map( 'strval', $parts ) );
		}

		if ( is_scalar( $value ) ) {
			return (string) $value;
		}

		return '';
	}

	/**
	 * Get default display attributes
	 *
	 * @since  4.0
	 * @return array Default attributes.
	 */
	public static function get_defaults() {
		return [
			// Display type
			'type'                         => 'ticker',

			// Ticker/Splide settings
			'direction'                    => 'left',
			'speed'                        => 10,
			'spacing'                      => 25,
			'hoverPause'                   => false,
			'cloneItems'                   => true,

			// Container styles
			'maxWidth'                     => '',
			'bgColor'                      => '',
			'padding'                      => '',
			'margin'                       => '',
			'borderColor'                  => '',
			'borderStyle'                  => '',
			'borderWidth'                  => '',
			'borderRadius'                 => '',

			// Contents styles
			'contentsBgColor'              => '',
			'contentsPadding'              => '',
			'contentsBorderColor'          => '',
			'contentsBorderStyle'          => '',
			'contentsBorderWidth'          => '',
			'contentsBorderRadius'         => '',

			// Title settings
			'title'                        => '',
			'titleDisplay'                 => 'none',
			'titleContentsSize'            => 'stretch',
			'titleContentsPosition'        => 'start',
			'titleElement'                 => 'h3',
			'titleElementPosition'         => 'start',
			'titleElementVerticalPosition' => 'start',
			'titleMinWidth'                => '',
			'titleMaxWidth'                => '',
			'titleMinHeight'               => '',
			'titleMaxHeight'               => '',
			'titleColor'                   => '',
			'titleBgColor'                 => '',
			'titlePadding'                 => '',
			'titleMargin'                  => '',
			'titleBorderColor'             => '',
			'titleBorderStyle'             => '',
			'titleBorderWidth'             => '',
			'titleBorderRadius'            => '',

			// Item styles
			'itemBgColor'                  => '',
			'itemPadding'                  => '',
			'itemBorderColor'              => '',
			'itemBorderStyle'              => '',
			'itemBorderWidth'              => '',
			'itemBorderRadius'             => '',
			'itemMaxWidth'                 => '',
			'itemElementsWrap'             => 'nowrap',
		];
	}

	/**
	 * Enqueue display assets
	 *
	 * @since  4.0
	 */
	public static function enqueue_assets() {
		if ( self::$assets_enqueued ) {
			return;
		}
  
		wp_enqueue_style(
			'ditty-v4',
			DITTY_URL . 'assets/build/dittyV4.css',
			[],
			filemtime( DITTY_DIR . 'assets/build/dittyV4.css' ),
		);

		wp_enqueue_script(
			'ditty-v4',
			DITTY_URL . 'assets/build/dittyV4.js',
			[],
      filemtime( DITTY_DIR . 'assets/build/dittyV4.js' ),
			true
		);

		self::$assets_enqueued = true;
	}

	/**
	 * Merge user args with defaults
	 *
	 * @since  4.0
	 * @param  array $args User arguments.
	 * @return array Merged arguments.
	 */
	public static function parse_args( $args ) {
		$defaults = self::get_defaults();
		return wp_parse_args( $args, $defaults );
	}

	/**
	 * Build display configuration array
	 *
	 * @since  4.0
	 * @param  array $args Display arguments.
	 * @return array Configuration for JS.
	 */
	public static function build_config( $args ) {
		// Normalize cloneItems to boolean/string
		$clone_items = $args['cloneItems'];
		if ( is_bool( $clone_items ) ) {
			$clone_items = $clone_items ? 'yes' : 'no';
		}

		return [
			'type'       => $args['type'],
			'direction'  => $args['direction'],
			'speed'      => $args['speed'],
			'spacing'    => $args['spacing'],
			'hoverPause' => ! empty( $args['hoverPause'] ),
			'cloneItems' => $clone_items,
		];
	}

	/**
	 * Build container inline styles
	 *
	 * @since  4.0
	 * @param  array $args Display arguments.
	 * @return string Inline style string.
	 */
	public static function build_container_styles( $args ) {
		$styles = [];

		if ( ! empty( $args['maxWidth'] ) ) {
			$value = self::normalize_style_value( $args['maxWidth'] );
			$styles[] = 'max-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['bgColor'] ) ) {
			$value = self::normalize_style_value( $args['bgColor'] );
			$styles[] = 'background-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['padding'] ) ) {
			$value = self::normalize_style_value( $args['padding'] );
			$styles[] = 'padding:' . esc_attr( $value );
		}
		if ( ! empty( $args['margin'] ) ) {
			$value = self::normalize_style_value( $args['margin'] );
			$styles[] = 'margin:' . esc_attr( $value );
		}
		if ( ! empty( $args['borderColor'] ) ) {
			$value = self::normalize_style_value( $args['borderColor'] );
			$styles[] = 'border-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['borderStyle'] ) ) {
			$value = self::normalize_style_value( $args['borderStyle'] );
			$styles[] = 'border-style:' . esc_attr( $value );
		}
		if ( ! empty( $args['borderWidth'] ) ) {
			$value = self::normalize_style_value( $args['borderWidth'] );
			$styles[] = 'border-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['borderRadius'] ) ) {
			$value = self::normalize_style_value( $args['borderRadius'] );
			$styles[] = 'border-radius:' . esc_attr( $value );
		}

		return ! empty( $styles ) ? implode( ';', $styles ) : '';
	}

	/**
	 * Build contents wrapper inline styles
	 *
	 * @since  4.0
	 * @param  array $args Display arguments.
	 * @return string Inline style string.
	 */
	public static function build_contents_styles( $args ) {
		$styles = [];

		if ( ! empty( $args['contentsBgColor'] ) ) {
			$value = self::normalize_style_value( $args['contentsBgColor'] );
			$styles[] = 'background-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['contentsPadding'] ) ) {
			$value = self::normalize_style_value( $args['contentsPadding'] );
			$styles[] = 'padding:' . esc_attr( $value );
		}
		if ( ! empty( $args['contentsBorderColor'] ) ) {
			$value = self::normalize_style_value( $args['contentsBorderColor'] );
			$styles[] = 'border-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['contentsBorderStyle'] ) ) {
			$value = self::normalize_style_value( $args['contentsBorderStyle'] );
			$styles[] = 'border-style:' . esc_attr( $value );
		}
		if ( ! empty( $args['contentsBorderWidth'] ) ) {
			$value = self::normalize_style_value( $args['contentsBorderWidth'] );
			$styles[] = 'border-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['contentsBorderRadius'] ) ) {
			$value = self::normalize_style_value( $args['contentsBorderRadius'] );
			$styles[] = 'border-radius:' . esc_attr( $value );
		}

		return ! empty( $styles ) ? implode( ';', $styles ) : '';
	}

	/**
	 * Build title inline styles
	 *
	 * @since  4.0
	 * @param  array $args Display arguments.
	 * @return string Inline style string.
	 */
	public static function build_title_styles( $args ) {
		$styles = [];

		if ( ! empty( $args['titleColor'] ) ) {
			$value = self::normalize_style_value( $args['titleColor'] );
			$styles[] = 'color:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleBgColor'] ) ) {
			$value = self::normalize_style_value( $args['titleBgColor'] );
			$styles[] = 'background-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['titlePadding'] ) ) {
			$value = self::normalize_style_value( $args['titlePadding'] );
			$styles[] = 'padding:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleMinWidth'] ) ) {
			$value = self::normalize_style_value( $args['titleMinWidth'] );
			$styles[] = 'min-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleMaxWidth'] ) ) {
			$value = self::normalize_style_value( $args['titleMaxWidth'] );
			$styles[] = 'max-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleMinHeight'] ) ) {
			$value = self::normalize_style_value( $args['titleMinHeight'] );
			$styles[] = 'min-height:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleMaxHeight'] ) ) {
			$value = self::normalize_style_value( $args['titleMaxHeight'] );
			$styles[] = 'max-height:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleBorderColor'] ) ) {
			$value = self::normalize_style_value( $args['titleBorderColor'] );
			$styles[] = 'border-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleBorderStyle'] ) ) {
			$value = self::normalize_style_value( $args['titleBorderStyle'] );
			$styles[] = 'border-style:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleBorderWidth'] ) ) {
			$value = self::normalize_style_value( $args['titleBorderWidth'] );
			$styles[] = 'border-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['titleBorderRadius'] ) ) {
			$value = self::normalize_style_value( $args['titleBorderRadius'] );
			$styles[] = 'border-radius:' . esc_attr( $value );
		}

		return ! empty( $styles ) ? implode( ';', $styles ) : '';
	}

	/**
	 * Build title wrapper inline styles
	 *
	 * @since  4.0
	 * @param  array $args Display arguments.
	 * @return string Inline style string.
	 */
	public static function build_title_wrapper_styles( $args ) {
		$styles = [];

		if ( ! empty( $args['titleMargin'] ) ) {
			$value = self::normalize_style_value( $args['titleMargin'] );
			$styles[] = 'margin:' . esc_attr( $value );
		}

		return ! empty( $styles ) ? implode( ';', $styles ) : '';
	}

	/**
	 * Build item inline styles
	 *
	 * @since  4.0
	 * @param  array $args Display arguments.
	 * @return string Inline style string.
	 */
	public static function build_item_styles( $args ) {
		$styles = [];

		if ( ! empty( $args['itemBgColor'] ) ) {
			$value = self::normalize_style_value( $args['itemBgColor'] );
			$styles[] = 'background-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['itemPadding'] ) ) {
			$value = self::normalize_style_value( $args['itemPadding'] );
			$styles[] = 'padding:' . esc_attr( $value );
		}
		if ( ! empty( $args['itemBorderColor'] ) ) {
			$value = self::normalize_style_value( $args['itemBorderColor'] );
			$styles[] = 'border-color:' . esc_attr( $value );
		}
		if ( ! empty( $args['itemBorderStyle'] ) ) {
			$value = self::normalize_style_value( $args['itemBorderStyle'] );
			$styles[] = 'border-style:' . esc_attr( $value );
		}
		if ( ! empty( $args['itemBorderWidth'] ) ) {
			$value = self::normalize_style_value( $args['itemBorderWidth'] );
			$styles[] = 'border-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['itemBorderRadius'] ) ) {
			$value = self::normalize_style_value( $args['itemBorderRadius'] );
			$styles[] = 'border-radius:' . esc_attr( $value );
		}
		if ( ! empty( $args['itemMaxWidth'] ) ) {
			$value = self::normalize_style_value( $args['itemMaxWidth'] );
			$styles[] = 'max-width:' . esc_attr( $value );
		}
		if ( ! empty( $args['itemElementsWrap'] ) && 'nowrap' === $args['itemElementsWrap'] ) {
			$styles[] = 'white-space:nowrap';
		}

		return ! empty( $styles ) ? implode( ';', $styles ) : '';
	}

	/**
	 * Render the display HTML
	 *
	 * @since  4.0
	 * @param  array $items Array of content items (HTML strings).
	 * @param  array $args  Display arguments.
	 * @return string HTML output.
	 */
	public static function render( $items, $args ) {
		if ( empty( $items ) ) {
			return '';
		}

		// Enqueue assets
		self::enqueue_assets();

		// Parse args with defaults
		$args = self::parse_args( $args );

		if ( 'ticker' === $args['type'] ) {
			return self::render_ticker( $items, $args );
		}

		return self::render_list( $items, $args );
	}

	/**
	 * Render ticker HTML (custom JS)
	 *
	 * @since  4.0
	 * @param  array $items Array of content items.
	 * @param  array $args  Display arguments.
	 * @return string HTML output.
	 */
	public static function render_ticker( $items, $args ) {
		$config           = self::build_config( $args );
		$container_styles = self::build_container_styles( $args );
		$contents_styles  = self::build_contents_styles( $args );
		$item_styles      = self::build_item_styles( $args );

		// Container class (no 'splide' class for ticker)
		$container_class = 'ditty-display ditty-type-ticker';

		// Build container opening tag
		$html = '<div class="' . esc_attr( $container_class ) . '"';
		$html .= ' data-ditty-config="' . esc_attr( wp_json_encode( $config ) ) . '"';
		if ( ! empty( $container_styles ) ) {
			$html .= ' style="' . $container_styles . '"';
		}
		$html .= '>';

		// Title section
		$html .= self::render_title( $args );

		// Contents wrapper
		$html .= '<div class="ditty-display__contents"';
		if ( ! empty( $contents_styles ) ) {
			$html .= ' style="' . $contents_styles . '"';
		}
		$html .= '>';

		// Items container (for ticker)
		$html .= '<div class="ditty-display__items">';

		foreach ( $items as $item ) {
			$html .= '<div class="ditty-display__item"';
			if ( ! empty( $item_styles ) ) {
				$html .= ' style="' . $item_styles . '"';
			}
			$html .= '>';
			$html .= $item; // Already sanitized or rendered block content
			$html .= '</div>';
		}

		$html .= '</div>'; // .ditty-display__items
		$html .= '</div>'; // .ditty-display__contents
		$html .= '</div>'; // .ditty-display

		return $html;
	}

	/**
	 * Render list/carousel HTML (Splide)
	 *
	 * @since  4.0
	 * @param  array $items Array of content items.
	 * @param  array $args  Display arguments.
	 * @return string HTML output.
	 */
	public static function render_list( $items, $args ) {
		$config           = self::build_config( $args );
		$container_styles = self::build_container_styles( $args );
		$contents_styles  = self::build_contents_styles( $args );
		$item_styles      = self::build_item_styles( $args );

		// Container class (includes 'splide' for Splide.js)
		$container_class = 'ditty-display ditty-type-' . esc_attr( $args['type'] ) . ' splide';

		// Build container opening tag
		$html = '<div class="' . esc_attr( $container_class ) . '"';
		$html .= ' data-ditty-config="' . esc_attr( wp_json_encode( $config ) ) . '"';
		if ( ! empty( $container_styles ) ) {
			$html .= ' style="' . $container_styles . '"';
		}
		$html .= '>';

		// Title section
		$html .= self::render_title( $args );

		// Contents wrapper
		$html .= '<div class="ditty-display__contents"';
		if ( ! empty( $contents_styles ) ) {
			$html .= ' style="' . $contents_styles . '"';
		}
		$html .= '>';

		// Splide track and slides
		$html .= '<div class="splide__track">';
		$html .= '<ul class="splide__list">';

		foreach ( $items as $item ) {
			$html .= '<li class="splide__slide">';
			$html .= '<div class="ditty-display__item"';
			if ( ! empty( $item_styles ) ) {
				$html .= ' style="' . $item_styles . '"';
			}
			$html .= '>';
			$html .= $item; // Already sanitized or rendered block content
			$html .= '</div>';
			$html .= '</li>';
		}

		$html .= '</ul>';
		$html .= '</div>'; // .splide__track

		$html .= '</div>'; // .ditty-display__contents
		$html .= '</div>'; // .ditty-display

		return $html;
	}

	/**
	 * Render the title section
	 *
	 * @since  4.0
	 * @param  array $args Display arguments.
	 * @return string HTML output.
	 */
	public static function render_title( $args ) {
		if ( 'none' === $args['titleDisplay'] || empty( $args['title'] ) ) {
			return '';
		}

		$title_wrapper_styles = self::build_title_wrapper_styles( $args );
		$title_styles         = self::build_title_styles( $args );

		$html = '<div class="ditty-display__title"';
		if ( ! empty( $title_wrapper_styles ) ) {
			$html .= ' style="' . $title_wrapper_styles . '"';
		}
		$html .= '>';

		$html .= '<div class="ditty-display__title__contents"';
		if ( ! empty( $title_styles ) ) {
			$html .= ' style="' . $title_styles . '"';
		}
		$html .= '>';

		$html .= sprintf(
			'<%1$s class="ditty-display__title__element">%2$s</%1$s>',
			esc_attr( $args['titleElement'] ),
			wp_kses_post( $args['title'] )
		);

		$html .= '</div>'; // .ditty-display__title__contents
		$html .= '</div>'; // .ditty-display__title

		return $html;
	}

}
