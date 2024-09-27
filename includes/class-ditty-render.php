<?php

use ScssPhp\ScssPhp\Compiler;
use Padaliyajay\PHPAutoprefixer\Autoprefixer;

/**
 * Ditty Render Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Render
 * @copyright   Copyright (c) 2023, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1.19
*/

class Ditty_Render {

  private $items = [];

	/**
	 * Get things started
	 * @access  public
	 * @since   3.1
	 */
	public function __construct( $items = [] ) {
    $this->items = $items;
	}

	public function render() {
		// $parsed_atts = $this->parse_render_atts( $atts );
		// $ditty = get_post( $parsed_atts['ditty'] );

    // $css_settings = [
    //   'title' => $parsed_atts['title_settings'],
    // ];

    $styles = [];
    $items = [];
    if ( is_array( $this->items ) && count( $this->items ) > 0 ) {
      foreach ( $this->items as $item ) {
        $display_item = new Ditty_Display_Item( $item );
				$display_item_data = $display_item->ditty_data();
        if ( ! isset( $styles[$display_item_data['layout_id']] ) ) {
          $styles[$display_item_data['layout_id']] = '<style id="ditty-layoutx--' . esc_attr( $display_item_data['layout_id'] ) . '">' . ditty_kses_post( $display_item_data['css'] ) . '</style>';
        }
        $items[] = ditty_kses_post( $display_item_data['html'] );
      }
    }

    $html = '';
    $html .= implode( '', $styles );

    //$html .= $this->display_css_output( $parsed_atts['display_id'], $css_settings );
		//$html .= '<div ' . ditty_attr_to_html( $parsed_atts['html_atts'] ) . '>';
    $html .= '<div class="ditty">';
			// $html .= '<div class="ditty__title">';
			// 	$html .= '<div class="ditty__title__contents">';
			// 		$html .= "<{$parsed_atts['title_settings']['titleElement']} class='ditty__title__element'>";
      //       $html .= $ditty->post_title;
      //     $html .= "</{$parsed_atts['title_settings']['titleElement']}>";
			// 	$html .= '</div>';
			// $html .= '</div>';
			$html .= '<div class="ditty__contents">';
				//$html .= '<div class="ditty__page">';
					$html .= '<div class="ditty__items">';
            $html .= implode( '', $items );
          $html .= '</div>';
				//$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Setup and parse the render atts
	 *
	 * @since    3.2
	 * @access   public
	 * @var      html
	 */
	public function parse_render_atts( $atts ) {
		$defaults = array(
			'id' 								=> '',
			'display' 					=> '',
			'layout' 	          => '',
			'uniqid' 						=> '',
			'class' 						=> '',
			'el_id'							=> '',
      'ajax_loading'			=> '',
		  'live_updates'			=> '',
		);
		$args = shortcode_atts( $defaults, $atts );
		
		// Check for WPML language posts
		$args['id'] = function_exists('icl_object_id') ? icl_object_id( $args['id'], 'ditty', true ) : $args['id'];
	
		// Make sure the ditty exists & is published
		if ( ! ditty_exists( intval( $args['id'] ) ) ) {
			return [
        'error' => sprintf( __( 'Ditty (%d) does not exist', 'ditty-news-ticker'), $args['id'] ),
      ];
		}
		if ( ! is_admin() && 'publish' !== get_post_status( intval( $args['id'] ) ) ) {
			return [
        'error' => sprintf( __( 'Ditty (%d) is not published', 'ditty-news-ticker'), $args['id'] ),
      ];
		}
	
		if ( '' == $args['uniqid'] ) {
			$args['uniqid'] = uniqid( 'ditty-' );
		}
	
		$class = 'ditty ditty--pre';
		if ( '' != $args['class'] ) {
			$class .= ' ' . esc_attr( $args['class'] );
		}

    $ditty_settings = get_post_meta( $args['id'], '_ditty_settings', true );
    $ajax_load 			= ( isset( $ditty_settings['ajax_loading'] ) && 'yes' == $ditty_settings['ajax_loading'] ) ? '1' : false;
    if ( 'yes' == $args['ajax_loading'] || 'no' == $args['ajax_loading'] ) {
      $ajax_load = ( 'yes' == $args['ajax_loading'] ) ? '1' : false;
    }

    $live_updates 	= ( isset( $ditty_settings['live_updates'] ) && 'yes' == $ditty_settings['live_updates'] ) ? '1' : false;
    if ( 'yes' == $args['live_updates'] || 'no' == $args['live_updates'] ) {
      $live_updates = ( 'yes' == $args['live_updates'] ) ? '1' : false;
    }

    // Get the items
		$items = Ditty()->singles->get_display_items( $args['id'], 'force' );
		
    // Get the display data
    $display = ( '' != $args['display'] ) ? $args['display'] : get_post_meta( $args['id'], '_ditty_display', true );
    $display_data = ditty_display_data( $display );
    $display_id = ( 'custom' == $display_data['id'] ) ? $args['id'] : $display_data['id'];
    $display_type = $display_data['type'];
		$display_settings = $display_data['settings'];
    
    // Get the title settings
		$title_settings	= $this->title_settings( $display_settings );
	
		$html_atts = array(
			'id'										=> ( '' != $args['el_id'] ) ? sanitize_title( $args['el_id'] ) : false,
			'class' 								=> $class,
			'data-id' 							=> $args['id'],
			'data-uniqid' 					=> $args['uniqid'],
			'data-display' 					=> $display_id,
			'data-type'							=> $display_type,
			//'data-settings' 				=> htmlspecialchars( json_encode( $display_settings ) ),
			'data-title'						=> $title_settings['titleDisplay'],
			'data-ajax_load' 				=> $ajax_load,
			'data-live_updates' 		=> $live_updates,
		);
    
    // Add additional title atts
    if ('none' != $title_settings['titleDisplay'] ) {
      $html_atts['data-title_position'] = $title_settings['titleContentsPosition'];
      $html_atts['data-title_horizontal_position'] = $title_settings['titleElementPosition'];
      $html_atts['data-title_vertical_position'] = $title_settings['titleElementVerticalPosition'];
    }
	
		// Add scripts
		ditty_add_scripts( $args['id'], $args['display']);
	
		return array(
			'ditty' => $args['id'],
			'ditty_settings' => $ditty_settings,
			'display_id' => $display_id,
			'display_type' => $display_type,
			'display_settings' => $display_settings,
			'title_settings' => $title_settings,
			'items' => $items,
			'html_atts' => $html_atts,
		);
	}

  /**
	 * Get a display's title settings
	 *
	 * @since    3.1.19
	 */
  public function title_settings( $settings ) {
    $defaults = [
      'titleDisplay' => 'none',
      'titleContentsSize' => 'stretch',
      'titleContentsPosition' => 'start',
      'titleElement' => 'h3',
      'titleElementPosition' => 'start',
      'titleElementVerticalPosition' => 'start',
      'titleFontSize' => false,
      'titleLineHeight' => false,
      'titleColor' => false,
      'titleLinkColor' => false,
      'titleMinWidth' => false,
      'titleMaxWidth' => false,
      'titleMinHeight' => false,
      'titleMaxHeight' => false,
      'titleBgColor' => false,
      'titleMargin' => false,
      'titlePadding' => false,
      'titleBorderColor' => false,
      'titleBorderWidth' => false,
      'titleBorderRadius' => false,
    ];
    return shortcode_atts( $defaults, $settings );
  }

  /**
	 * Render the display styles
	 *
	 * @since    3.1.19
	 */
  public function display_css_output( $display_id, $settings ) {
    global $ditty_display_css;
    if ( empty( $ditty_display_css ) ) {
      $ditty_display_css = [];
    }
    if ( isset( $ditty_display_css[$display_id] ) ) {
      return false;
    }

    $title = $settings['title'];
    $style = "
.ditty[data-display='{$display_id}'] {
  .ditty__title__element { 
    font-size: {$title['titleFontSize']};
    line-height: {$title['titleLineHeight']};
    color: {$title['titleColor']};
  }
}";

    // Compile the sass & remove whitespace
    try {
      $scss = new Compiler();
      $compiled_styles = $scss->compileString( $style )->getCss();
    } catch ( \Exception $e ) {
      return false;
    }

    // Add auto-prefixes
    $autoprefixer = new Autoprefixer( $compiled_styles );
    $prefixed_css = $autoprefixer->compile();
        
    // Remove multiple white-spaces, tabs and new-lines
    $final_css = preg_replace( '/\s+/S', ' ', $prefixed_css );
    $style_element = "<style id='ditty-display--{$display_id}' class='ditty-display-styles'>" . wp_kses_post( trim( $final_css ) ) . "</style>";

    $ditty_display_css[$display_id] = $display_id;
    return $style_element;
  }
}