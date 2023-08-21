<?php

/**
 * Ditty Translations Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Translations
 * @copyright   Copyright (c) 2023, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1.15
*/

class Ditty_Translations {

	/**
	 * Get things started
	 * @access  public
	 * @since   3.1.15
	 */
	public function __construct() {	
    add_action( 'wp_insert_post', [$this, 'save_title_translation'], 10, 3 );
    add_filter( 'the_title', [$this, 'translate_title'], 10, 2 );
    //add_action( 'init', [$this, 'testing'] );
	}
  
  // public function testing() {
  //   global $wpdb, $sitepress;
  //   if ( $ditty_id = ditty_editing() ) { 
  //     $package = array(
  //       'kind' => __( 'Ditty', 'ditty-news-ticker' ),
  //       'name' => $ditty_id,
  //       'title' => sprintf( __( 'Ditty ID: %d' ), $ditty_id ),
  //     );
  //     
  //     $p = new WPML_Package( $package );
  //     $p->flush_cache();
  //     
  //   }
  // }
	
	/**
   * Return variation defaults ensuring they exist
   * *
   * @since   3.1.25
   */
  public function get_translation_plugin() {
    if ( defined( 'WPML_PLUGIN_FILE' ) && defined( 'WPML_ST_VERSION' ) ) {
      return 'wpml';
    }
  }

  /**
   * Get the current language of a translation
   * *
   * @since   3.1.25
   */
  public function get_translation_language() {
    $translation_plugin = $this->get_translation_plugin();
    switch( $translation_plugin ) {
      case 'wpml':
        return apply_filters( 'wpml_current_language', null );
      default:
        break;
    }
  }

  /**
	 * Save ditty title translation
	 *
	 * @access  public
	 * @since   3.1.25
	 */
  public function save_title_translation( $post_id, $post, $update ) {
    $package = array(
      'kind' => __( 'Ditty', 'ditty-news-ticker' ),
      'name' => $post_id,
      'title' => sprintf( __( 'Ditty ID: %d' ), $post_id ),
    );
    $string_value = $post->post_title;
    do_action( 'wpml_register_string', $string_value, "ditty_title", $package, 'ditty_title', 'LINE' );
  }

  /**
	 * Save item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
  public function save_wpml_item_translation( $item, $keys ) {
    $ditty_id = $item['ditty_id'];
    $item_id = $item['item_id'];
    $item_value = isset( $item['item_value'] ) ? $item['item_value'] : false;
    if ( $item_value && is_array( $keys ) && count( $keys ) > 0 ) {
      $package = array(
        'kind' => __( 'Ditty', 'ditty-news-ticker' ),
        'name' => $ditty_id,
        'title' => sprintf( __( 'Ditty ID: %d' ), $ditty_id ),
      );

      foreach ( $keys as $key_id => $key_label ) {
        if ( isset( $item_value[$key_id] ) ) {
          $string_value = $item_value[$key_id];
          $label = sprintf( __( 'Item %d: %s', 'ditty-news-ticker' ), $item_id, $key_label );
          do_action( 'wpml_register_string', $string_value, "item_{$item_id}_{$key_id}", $package, $label, 'LINE' );
        }
      }
    }
  }

  /**
	 * Save single item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
  public function save_item_translation( $item ) {
    $item_type_object = ditty_item_type_object( $item['item_type'] );
    if ( ! $item_type_object ) {
      return false;
    }
    $keys = $item_type_object->is_translatable();
    if ( ! $keys ) {
      return false;
    }

    $translation_plugin = $this->get_translation_plugin();
    switch( $translation_plugin ) {
      case 'wpml':
        $this->save_wpml_item_translation( $item, $keys );
        break;
      default:
        break;
    }
  }

  /**
	 * Save item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function maybe_save_item_translations( $items ) {
    if ( is_array( $items ) ) {
      if ( count( $items ) > 0 ) {
        foreach ( $items as $item ) {
          $this->save_item_translation( $item );
        }
      }
    } else {
      $this->save_item_translation( $items );
    }
  }

  /**
	 * Delete title translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function delete_title_translations( $ditty_id ) {
    // $sanitized_deleted_items = [];
    // if ( is_array( $deleted_items ) && count( $deleted_items ) > 0 ) {
    //   foreach ( $deleted_items as $deleted_item ) {
    //     $sanitized_deleted_items[] = (array) $deleted_item;
    //   }
    // }

    // $translation_plugin = $this->get_translation_plugin();
    // switch( $translation_plugin ) {
    //   case 'wpml':
    //     $this->wpml_delete_translations( $sanitized_deleted_items );
    //     break;
    //   default:
    //     break;
    // }
  }

  /**
	 * Delete item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function delete_item_translations( $deleted_items = [] ) {
    $sanitized_deleted_items = [];
    if ( is_array( $deleted_items ) && count( $deleted_items ) > 0 ) {
      foreach ( $deleted_items as $deleted_item ) {
        $sanitized_deleted_items[] = (array) $deleted_item;
      }
    }
    $translation_plugin = $this->get_translation_plugin();
    switch( $translation_plugin ) {
      case 'wpml':
        $this->wpml_delete_translations( $sanitized_deleted_items );
        break;
      default:
        break;
    }
  }

  /**
	 * Delete WPML translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
  private function wpml_delete_translations( $deleted_items ) {
    global $wpdb;
    
    if ( is_array( $deleted_items ) && count( $deleted_items ) > 0 ) {
      foreach ( $deleted_items as $deleted_item ) {
        $item_type_object = ditty_item_type_object( $deleted_item['item_type'] );
        if ( ! $item_type_object ) {
          return false;
        }
        $keys = $item_type_object->is_translatable();
        if ( ! $keys ) {
          return false;
        }
        
        $names_to_delete = [];
        foreach ( $keys as $key_id => $key_label ) {
          $names_to_delete[] = "item_{$deleted_item['item_id']}_{$key_id}";
        }
        
        $placeholders = array_fill(0, count($names_to_delete), '%s');
        
        // Get IDs of of strings
        $sql = "SELECT id FROM {$wpdb->prefix}icl_strings WHERE name IN (" . implode(', ', $placeholders) . ")";
        $ids = $wpdb->get_col( $wpdb->prepare($sql, $names_to_delete) );
        ChromePhp::log( '$ids', $ids );
        
        // Delete translations of string
        $id_placeholders = array_fill(0, count( $ids ), '%s');
        $sql = "DELETE FROM {$wpdb->prefix}icl_string_translations WHERE string_id IN (" . implode(', ', $id_placeholders) . ")";
        $results = $wpdb->query( $wpdb->prepare( $sql, $ids ) );
        ChromePhp::log( 'icl_string_translations $results', $results );
        
        // Delete the strings
        $sql = "DELETE FROM {$wpdb->prefix}icl_strings WHERE name IN (" . implode(', ', $placeholders) . ")";
        $results = $wpdb->query( $wpdb->prepare( $sql, $names_to_delete ) );
        ChromePhp::log( 'icl_strings $results', $results );
        
        
        // $string_ids_query   = "SELECT name FROM {$wpdb->prefix}icl_strings WHERE string_package_id=%d";
        // $string_ids_prepare = $wpdb->prepare( $string_ids_query, $package_id );
        // $string_ids         = $wpdb->get_col( $string_ids_prepare );

      }
    }
  }

  /**
	 * Translate items
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
  public function translate_title( $post_title, $post_id ) {
    if ( 'ditty' != get_post_type( $post_id ) ) {
      return $post_title;
    }
    $package = array(
      'kind' => __( 'Ditty', 'ditty-news-ticker' ),
      'name' => $post_id,
      'title' => sprintf( __( 'Ditty ID: %d' ), $post_id ),
    );
    return apply_filters( 'wpml_translate_string', $post_title, "ditty_title", $package );
  }

  /**
	 * Translate items
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
  public function translate_item( $prepared_item ) {
    $item_type_object = ditty_item_type_object( $prepared_item['item_type'] );
    if ( ! $item_type_object ) {
      return $prepared_item;
    }

    if ( $keys = $item_type_object->is_translatable() ) {
      $item_id = $prepared_item['item_id'];
      $item_value = isset( $prepared_item['item_value'] ) ? $prepared_item['item_value'] : false;

      $package = array(
        'kind' => __( 'Ditty', 'ditty-news-ticker' ),
        'name' => $prepared_item['ditty_id'],
        'title' => sprintf( __( 'Ditty ID: %d' ), $prepared_item['ditty_id'] ),
      );

      if ( $item_value && is_array( $keys ) && count( $keys ) > 0 ) {
        foreach ( $keys as $key_id => $key_label ) {
          if ( isset( $item_value[$key_id] ) ) {
            $original_value = $item_value[$key_id];
            $translated_string = apply_filters( 'wpml_translate_string', $original_value, "item_{$item_id}_{$key_id}", $package );
            $item_value[$key_id] = $translated_string;
            $prepared_item['item_value'] = $item_value;
          }
        }
      }
    }
    return $prepared_item;
  }

}