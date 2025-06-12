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
  private $settings;
  private $display;
  private $display_type;
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
    
    $display = false;
    $display_type = false;
    $display_settings = [];
    $custom_display_settings = [];

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
          $custom_display_settings = $display_settings['settings'];
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
    $this->display_settings = wp_parse_args( $custom_display_settings, $display_settings );

    // Add the display scripts
    $this->add_display_scripts( $display_type );
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
    global $ditty_display_scripts;
    if ( empty( $ditty_display_scripts ) ) {
      $ditty_display_scripts = array();
    }
    $ditty_display_scripts[$display_type] = $display_type;
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

  public function get_id() {
    return $this->id;
  }

  public function get_el_id() {
    return $this->el_id;
  }

  public function get_uniq_id() {
    return $this->el_id;
  }

  public function get_class() {
    return $this->class;
  }

  public function get_display() {
    return $this->display;
  }

  public function get_display_settings( $url_encoded = false ) {
    return $url_encoded ? htmlspecialchars( json_encode( $this->display_settings ), ENT_QUOTES, 'UTF-8' ) : $this->display_settings;
  }

  public function render() {
    if ( $this->get_id() ) {
      $atts = array(
        'id'										=> $this->get_el_id(),
        'class' 								=> $this->get_class(),
        'data-id' 							=> $this->get_id(),
        'data-uniqid' 					=> $this->get_uniq_id(),
        //'data-display' 					=> $this->get_display(),
        'data-display_settings' => $this->get_display_settings( true ),
        // 'data-layout_settings' 	=> ( '' != $args['layout'] ) ? $args['layout'] : false,
        // 'data-show_editor' 			=> ( 0 != intval( $args['show_editor'] ) ) ? '1' : false,
        // 'data-ajax_load' 				=> $ajax_load,
        // 'data-live_updates' 		=> $live_updates,
      );

      $html = '<div ' . ditty_attr_to_html( $atts ) . '>';
        $html .= ditty_edit_links( $this->get_id() );
      $html .= '</div>';
      return $html;
    }
  }
}
