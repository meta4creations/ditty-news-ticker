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
    add_action( 'wp_insert_post', [$this, 'save_title_translation'], 10, 2 );
    add_action( 'delete_post', [$this, 'delete_post_translations'] );
    add_filter( 'the_title', [$this, 'translate_title'], 10, 2 );
	}
	
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
   * 
   * @since   3.1.25
   */
  public function get_translation_language() {
    $translation_plugin = $this->get_translation_plugin();
    return apply_filters( 'ditty_translation_language', '', $translation_plugin );
  }

  /**
   * Get all active translation languages
   * 
   * @since   3.1.25
   */
  public function get_active_translation_languages() {
    $translation_plugin = $this->get_translation_plugin();
    return apply_filters( 'ditty_active_translation_languages', '', $translation_plugin );
  }

  /**
	 * Save ditty title translation
	 *
	 * @access  public
	 * @since   3.1.30
	 */
  public function save_title_translation( $post_id, $post ) {
    if ( 'ditty' != $post->post_type ) {
      return false;
    }
    $translation_plugin = $this->get_translation_plugin();
    do_action( 'ditty_save_title_translation', $post->post_title, $post_id , $translation_plugin );
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
    do_action( 'ditty_save_item_translation', $item, $keys, $translation_plugin );
  }

  /**
	 * Save item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function save_item_translations( $items ) {
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
	 * Save item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function save_ditty_translations( $ditty_id ) {

    // Save the title
    $post = get_post( $ditty_id );
    $this->save_title_translation( $ditty_id, $post );

    // Save the item translations
    $items_meta = ditty_items_meta( $ditty_id );    
    if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {
      foreach ( $items_meta as $i => $item ) {
        $this->save_item_translation( (array) $item );
      }
    }

    return 'success';
  }

  /**
	 * Delete a single item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
  public function delete_item_translation( $item ) {
    $item_type_object = ditty_item_type_object( $item['item_type'] );
    if ( ! $item_type_object ) {
      return false;
    }
    $keys = $item_type_object->is_translatable();
    if ( ! $keys ) {
      return false;
    }
    $translation_plugin = $this->get_translation_plugin();
    do_action( 'ditty_delete_item_translation', $item, $keys, $translation_plugin );
  }

  /**
	 * Delete post translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function delete_post_translations( $post_id ) {
    $post_type = get_post_type( $post_id );
    if ( 'ditty' != $post_type ) {
      return $post_id;
    }
    
    // Back ouf if user does not have permission
    if ( ! current_user_can( 'delete_dittys', $post_id ) ) {
      return $post_id;
    }

    do_action( 'ditty_delete_post_translations', $post_id, $post_type );
  }

  /**
	 * Delete transients
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function delete_language_transients( $ditty_id ) {
    $translation_plugin = $this->get_translation_plugin();
    do_action( 'ditty_delete_language_transients', $ditty_id, $translation_plugin );
  }

  /**
	 * Delete item translations
	 *
	 * @access  public
	 * @since   3.1.25
	 * @param   array
	 */
	public function delete_item_translations( $deleted_items ) {
    $sanitized_deleted_items = [];
    if ( is_array( $deleted_items ) ) {
      if ( count( $deleted_items ) > 0 ) {
        foreach ( $deleted_items as $deleted_item ) {
          $sanitized_deleted_items[] = (array) $deleted_item;
        }
      }
    } else {
      $sanitized_deleted_items[] = (array) $deleted_items;
    }
    if ( 0 == count( $sanitized_deleted_items ) ) {
      return false;
    }
    foreach ( $sanitized_deleted_items as $item ) {
      $this->delete_item_translation( $item );
    }
  }

  /**
	 * Translate items
	 *
	 * @access  public
	 * @since   3.1.27
	 * @param   array
	 */
  public function translate_title( $post_title, $post_id = null ) {
    if ( 'ditty' != get_post_type( $post_id ) ) {
      return $post_title;
    }
    $translation_plugin = $this->get_translation_plugin();
    return apply_filters( 'ditty_translate_title', $post_title, $post_id, $translation_plugin );
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
    $keys = $item_type_object->is_translatable();
    if ( ! $keys ) {
      return $prepared_item;
    }
    $translation_plugin = $this->get_translation_plugin();
    return apply_filters( 'ditty_translate_item', $prepared_item, $keys, $translation_plugin );
  }

}