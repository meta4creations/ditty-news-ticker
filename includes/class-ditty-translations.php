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
	 * Delete display item transients
	 *
	 * @access  public
	 * @since   3.1
	 * @param   array
	 */
	public function maybe_delete_translations( $deleted_items = [] ) {
    $translation_plugin = $this->get_translation_plugin();
    switch( $translation_plugin ) {
      case 'wpml':
        $this->wpml_delete_translations( $deleted_items );
        break;
      default:
        break;
    }
  }

  private function wpml_delete_translations( $deleted_items ) {
    if ( is_array( $deleted_items ) && count( $deleted_items ) > 0 ) {
      $translation_ids = [];
      ditty_log($translation_ids);
      foreach ( $deleted_items as $deleted_item ) {
        $item_id = $deleted_item['item_id'];
        if ( $item_type_object = ditty_item_type_object( $deleted_item['item_type'] ) ) {
          if ( $keys = $item_type_object->is_translatable() ) {
            foreach ( $keys as $key ) {
              $translation_ids[] = "item_{$item_id}_{$key}";
            }
          }
        }
      }
    
      if ( defined( 'WPML_ST_PATH' ) && function_exists( 'wpml_unregister_string_multi' ) ) {
        require_once WPML_ST_PATH . '/inc/admin-texts/wpml-admin-text-configuration.php';
        ditty_log($translation_ids);
        wpml_unregister_string_multi( $translation_ids );
      }
    }
  }

}