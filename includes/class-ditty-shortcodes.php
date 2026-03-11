<?php

use ScssPhp\ScssPhp\Compiler;
use Padaliyajay\PHPAutoprefixer\Autoprefixer;

/**
 * Ditty Render Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Shortcodes
 * @copyright   Copyright (c) 2023, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1.29
*/

class Ditty_Shortcodes {

	/**
	 * Get things started
	 * @access  public
	 * @since   3.1.29
	 */
	public function __construct() {
		
    // Shortcodes
    add_shortcode( 'ditty_ticker', [$this, 'do_ticker_shortcode'] );

    // Shortcodes
		add_shortcode( 'ditty', [$this, 'do_ditty_shortcode'] );
	}

  /**
	 * Display the Ditty via shortcode
	 *
	 * @since    3.0
	 * @access   public
	 * @var      html
	 */
	public function do_ditty_shortcode( $atts ) {
		if ( ! is_admin() ) {
      return ditty_render( $atts );
		}
	}

  /**
	 * Display the Ditty via shortcode
	 *
	 * @since    3.1.29
	 * @access   public
	 * @var      html
	 */
	public function do_ticker_shortcode( $atts, $content ) {
    $ticker = new Ditty_Display_Type_Ticker();
    $display_defaults = $ticker->shortcode_atts();
    $title_defaults = [
      'title' => '',
      'titleDisplay' => 'none',
			'titleContentsSize' => 'stretch',
			'titleContentsPosition' => 'start',
			'titleElement' => 'h3',
			'titleElementPosition' => 'start',
			'titleElementVerticalPosition' => 'start',
    ];
    $display_args = shortcode_atts( $display_defaults, $atts );
    $title_args = shortcode_atts( $title_defaults, $atts );
    
    $items = explode( "\n", str_replace( "\r", "", $content ) );
    $items = array_filter( $items, 'trim' );

    if ( ! empty( $items ) ) {
      return $this->render( $items, $display_args, $title_args );
    }
	}

	public function render( $items, $display_args, $title_args ) {
    $html = '';
    //$html .= $this->display_css_output( $parsed_atts['display_id'], $css_settings );
		$html .= '<div class="ditty">';
      if ( '' != $title_args['title'] ) {
        $html .= '<div class="ditty__title">';
          $html .= '<div class="ditty__title__contents">';
            $html .= "<{$title_args['titleElement']} class='ditty__title__element'>";
              $html .= wp_kses_post( $title_args['title'] );
            $html .= "</{$title_args['titleElement']}>";
          $html .= '</div>';
        $html .= '</div>';
      }
			$html .= '<div class="ditty__contents">';
        $html .= '<div class="ditty__items">';
          if ( is_array( $items ) && count( $items ) > 0 ) {
            foreach ( $items as $item ) {
              $html .= '<div class="ditty__item">';
                $html .= wp_kses_post( $item );
              $html .= '</div>';
            }
          }
        $html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
}