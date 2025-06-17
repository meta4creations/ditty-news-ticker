<?php

/**
 * Ditty Display Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display
 * @copyright   Copyright (c) 2025, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
class Ditty_Display {

  private $id;
  private $ditty_settings = [];
  private $display;
  private $display_type;
  private $display_type_object;
  private $display_settings = [];
  private $items = [];
  private $layout;
  private $uniqid;
  private $class;
  private $el_id;
  private $show_editor;
  private $ajax_loading;
  private $live_updates;

	public function __construct( $args = [] ) {

    // Make sure the Ditty exists
    if ( ! isset( $args['id'] ) || ! ditty_exists( intval( $args['id'] ) ) ) {
      return false;
    }

    // Make sure the Ditty is published for front end viewing
    if ( ! is_admin() && 'publish' !== get_post_status( intval( $args['id'] ) ) ) {
      return false;
    }

    $this->id = intval( $args['id'] );
    $this->layout = $args['layout'] ?? '';
    $this->uniqid = $args['uniqid'] ?? uniqid( 'ditty-' );
    $this->el_id = ( isset( $args['el_id'] ) && ( '' != $args['el_id'] ) ) ? sanitize_title( $args['el_id'] ) : false;

    $class = 'ditty ditty--pre';
    if ( isset( $args['class'] ) && '' != $args['class'] ) {
      $class .= ' ' . esc_attr( $args['class'] );
    }
    $this->class = $class;

    $this->init_settings( $args );
    $this->init_display( $args );
    $this->init_items();
	}

  /**
   * Initialize Ditty settings
   */
  private function init_settings( $args ) {

    $settings = get_post_meta( $this->get_id(), '_ditty_settings', true );
    $this->ditty_settings = $settings;
    
    // Set ajax loading
    $this->ajax_loading = ( isset( $settings['ajax_loading'] ) && 'yes' == $settings['ajax_loading'] ) ? '1' : false;
    if ( isset( $args['ajax_loading'] ) && ( 'yes' == $args['ajax_loading'] || 'no' == $args['ajax_loading'] ) ) {
      $this->ajax_loading = ( 'yes' == $args['ajax_loading'] ) ? '1' : false;
    }

    // Set live updates
    $this->live_updates = ( isset( $settings['live_updates'] ) && 'yes' == $settings['live_updates'] ) ? '1' : false;
    if ( isset( $args['live_updates'] ) && ( 'yes' == $args['live_updates'] || 'no' == $args['live_updates'] ) ) {
      $this->live_updates = ( 'yes' == $args['live_updates'] ) ? '1' : false;
    }

    // Set show editor
    if ( isset( $args['show_editor'] ) && 0 != intval( $args['show_editor'] ) ) {
      $this->live_updates = 1;
    }
  }

  /**
   * Initialize Display settings
   */
  private function init_display( $args ) {

    $ditty_settings = $this->get_ditty_settings();
    
    $display = false;
    $display_type = false;
    $display_settings = [];
    $custom_display_settings = [
      'orderby' => $ditty_settings['orderby'] ?? 'list',
      'order' => $ditty_settings['order'] ?? 'desc',
    ];

    // Get the display post
    if ( isset( $args['display'] ) && '' != $args['display'] && ditty_display_exists( intval( $args['display'] ) ) ) {
      $display = $args['display'];
    } else {
      $display = get_post_meta( $this->get_id(), '_ditty_display', true );
    }

    // Check for custom display type and settings
    if ( isset( $args['display_settings'] ) ) {
      $display_settings = json_decode( $args['display_settings'], true );
      if ( json_last_error() == JSON_ERROR_NONE ) {
        if ( isset( $display_settings['type'] ) && ditty_display_type_exists( $display_settings['type'] ) ) {
          $display_type = $display_settings['type'];
        }
        if ( isset( $display_settings['settings'] ) && is_array( $display_settings['settings'] ) ) {
          $custom_display_settings = wp_parse_args( $display_settings['settings'], $custom_display_settings );
        }
      }
    }

    // Compile data from the display post or object
    if ( $display ) {
      if ( is_array( $display ) ) {
        if ( ! $display_type ) {
          $display_type = isset( $display['type'] ) ? $display['type'] : false;
        }
        $display_settings = isset( $display['settings'] ) ? $display['settings'] : [];
      } else {
        if ( 'publish' == get_post_status( $display ) ) {
          if ( ! $display_type ) {
            $display_type = get_post_meta( $display, '_ditty_display_type', true );
          }
          $display_settings = get_post_meta( $display, '_ditty_display_settings', true );
        }
      }
    }

    $this->display = $display;
    $this->display_type = $display_type;
    $this->display_settings = $this->maybe_add_units( wp_parse_args( $custom_display_settings, $display_settings ) );
    $this->display_type_object = ditty_display_type_object( $display_type );
  }

  /**
   * Initialize Items
   */
  private function init_items() {
    $items = Ditty()->db_items->get_items( $this->get_id() );

    if ( is_array( $items ) && count( $items ) > 0 ) {
      foreach ( $items as $i => $item ) {
        if ( $item_type_object = ditty_item_type_object( $item->item_type ) ) {
          if ( $script_id = $item_type_object->get_script_id() ) {

            // Add item scripts
            $this->add_item_scripts( $script_id );
          }
        }
      }
    }

    $this->items = $items;
  }

  /**
   * Add display scripts
   */
  private function add_display_scripts( $display_type ) {
    Ditty()->scripts->enqueue_display( $display_type );
  }

  /**
   * Add item scripts
   */
  private function add_item_scripts( $script_id ) {
    global $ditty_item_scripts;
    if ( empty( $ditty_item_scripts ) ) {
      $ditty_item_scripts = array();
    }
    $ditty_item_scripts[$script_id] = $script_id;
  }

  /**
   * Add item scripts
   */
  private function add_ditty_single( $atts ) {
    global $ditty_singles;
    if ( empty( $ditty_singles ) ) {
      $ditty_singles = array();
    }
    $ditty_singles[] = $atts;
  }

  private function get_id() {
    return $this->id;
  }

  private function get_el_id() {
    return $this->el_id;
  }

  private function get_uniq_id() {
    return $this->uniqid;
  }

  private function get_class() {
    return $this->class;
  }

  private function get_display() {
    return $this->display;
  }

  private function get_display_id() {
    return is_array( $this->display ) ? $this->get_id() : $this->display;
  }

  private function get_display_type() {
    return $this->display_type;
  }

  private function get_display_type_object() {
    return $this->display_type_object;
  }

  private function get_display_render_method() {
    return $this->display_type_object->get_render_method();;
  }
  
  private function get_items() {
    return $this->items;
  }

  private function get_rendered_items() {
    return Ditty()->singles->get_display_items( $this->get_id() );
  }

  private function get_ajax_loading() {
    return $this->ajax_loading;
  }

  private function get_live_updates() {
    return $this->live_updates;
  }

  private function get_ditty_settings() {
    return $this->ditty_settings;
  }

  private function get_display_settings( $url_encoded = false ) {
    return $url_encoded ? htmlspecialchars( json_encode( $this->display_settings ), ENT_QUOTES, 'UTF-8' ) : $this->display_settings;
  }

  private function maybe_add_units( $settings ) {
    $unit_default = 'px';

    // Keys that should have units, with nested structure
    $keys_with_units = [
      'spacing',
      'breakPoints' => [
        'maxWidth',
        'spacing',
      ],
    ];

    // Top-level keys
    foreach ( $keys_with_units as $key => $subkeys ) {
      // Flat key
      if ( is_int( $key ) ) {
        $flat_key = $subkeys;

        if ( isset( $settings[ $flat_key ] ) ) {
          $settings[ $flat_key ] = $this->ensure_unit( $settings[ $flat_key ], $unit_default );
        }
      }
      // Nested keys
      else {
        if ( isset( $settings[ $key ] ) && is_array( $settings[ $key ] ) ) {
          foreach ( $settings[ $key ] as $index => $sub_setting ) {
            if ( is_array( $sub_setting ) ) {
              foreach ( $subkeys as $subkey ) {
                if ( isset( $sub_setting[ $subkey ] ) ) {
                  $settings[ $key ][ $index ][ $subkey ] = $this->ensure_unit( $sub_setting[ $subkey ], $unit_default );
                }
              }
            }
          }
        }
      }
    }
    return $settings;
  }

  /**
   * Ensure the value has a unit, unless it's "0"
   */
  private function ensure_unit( $value, $unit ) {
    if ( $value === '0' || $value === 0 ) {
      return '0';
    }
    if ( is_string( $value ) && preg_match( '/\d$/', $value ) ) {
      return $value . $unit;
    }
    return $value;
  }

  /**
   * Generate css vars
   */
  private function generate_css_vars( $settings, $prefix = '--ditty-' ) {
    $css_vars_settings = $this->get_display_type_object()->filter_css_vars_settings( $settings );
    $css_vars = '';

    foreach ($settings as $key => $value) {
      if ( is_int( $key ) ) {
        continue;
      }
      $css_key = $prefix . sanitize_title_with_dashes($key);
      if ( is_array( $value ) ) {
        // Recurse into sub-arrays
        $css_vars .= $this->generate_css_vars($value, $css_key . '-');
      } elseif ( $value !== '' ) {
        // Ensure value is sanitized and properly formatted
        $css_vars .= $css_key . ': ' . esc_attr( $value ) . '; ';
      }
    }

    return trim( $css_vars );
  }

  public function render() {

    // Add the display scripts
    $this->add_display_scripts( $this->get_display_type() );

    $render_method = $this->get_display_render_method();

    if ( 'v2' == $render_method ) {
      return $this->render_v2();
    } else {
      return $this->render_v1();
    }
  }

  /**
   * Use v1 render method
   */
  public function render_v1() {
    if ( $this->get_id() ) {
      $atts = array(
        'id'										=> $this->get_el_id(),
        'class' 								=> $this->get_class(),
        'data-id' 							=> $this->get_id(),
        'data-uniqid' 					=> $this->get_uniq_id(),
        'data-display_type'     => $this->get_display_type(),
        'data-display_settings' => $this->get_display_settings( true ),
        // 'data-layout_settings' 	=> ( '' != $args['layout'] ) ? $args['layout'] : false,
        'data-ajax_load' 				=> $this->get_ajax_loading(),
        'data-live_updates' 		=> $this->get_live_updates(),
      );

      if ( 1 != $this->get_ajax_loading() ) {
        $this->add_ditty_single( $atts );
      }

      $html = '<div ' . ditty_attr_to_html( $atts ) . '>';
        $html .= ditty_edit_links( $this->get_id() );
      $html .= '</div>';
      return $html;
    }
  }

  /**
   * Use v2 render method
   */
  public function render_v2() {
    $display_settings = $this->get_display_settings();
    $items = $this->get_rendered_items();
    $items_html = [];

    // Get the item html and add scripts
    if ( is_array( $items ) && ! empty( $items ) ) {
      foreach ( $items as $item ) {
        $items_html[] = $item['html'];
        Ditty()->scripts->add_layout_styles( $item['layout_id'], $item['css'] );
      }
    }

    // Add custom styles for the display
    $this->get_display_type_object()->custom_display_styles( $this->get_uniq_id(), $display_settings );

    $atts = [
      'id' => $this->get_el_id(),
      'class' => "ditty ditty-{$this->get_display_type()} ditty-grid--{$this->get_id()} ditty-grid--{$this->get_uniq_id()}",
      'style' => $this->generate_css_vars( $display_settings ),
      'data-display' => $this->get_display_id()
    ];
    
    $html = '';
    if ( is_array( $items_html ) && ! empty( $items_html ) ) {
      $html .= '<div '. ditty_attr_to_html( $atts ) . '>'; 

        $html .= '<div class="ditty__contents ditty-' . $this->get_display_type() . '__contents">';
          $html .= '<div class="ditty__page ditty-' . $this->get_display_type() . '__page">';
            $html .= '<div class="ditty__page__items ditty-' . $this->get_display_type() . '__page__items">';
              foreach ( $items_html as $item ) {
                $html .= $item;
              }
            $html .= '</div>';
          $html .= '</div>';
        $html .= '</div>';
        
      $html .= '</div>';
    }

    return $html;
  }
}
