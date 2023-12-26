<?php

namespace Ditty\Translators\WPML;

add_filter( 'ditty_translation_language', __NAMESPACE__ . '\translation_language', 10, 2 );
add_filter( 'ditty_active_translation_languages', __NAMESPACE__ . '\active_translation_languages', 10, 2 );
add_action( 'ditty_save_title_translation', __NAMESPACE__ . '\save_title_translation', 10, 3 );
add_action( 'ditty_save_item_translation', __NAMESPACE__ . '\save_item_translation', 10, 3 );
add_action( 'ditty_delete_item_translation', __NAMESPACE__ . '\delete_item_translation', 10, 3 );
add_action( 'ditty_delete_post_translations', __NAMESPACE__ . '\delete_post_translations', 10, 2 );
add_action( 'ditty_delete_language_transients', __NAMESPACE__ . '\delete_language_transients', 10, 2 );
add_action( 'wpml_st_add_string_translation', __NAMESPACE__ . '\translation_updated' );
add_filter( 'ditty_translate_title', __NAMESPACE__ . '\translate_title', 10, 3 );
add_filter( 'ditty_translate_item', __NAMESPACE__ . '\translate_item', 10, 3 );

/**
 * Get the current language
 * 
 * @since   3.1.25
 */
function translation_language( $language, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return $language;
  }
	return apply_filters( 'wpml_current_language', null );
}

/**
 * Get all active languages
 * 
 * @since   3.1.25
 */
function get_active_translation_languages() {
  return apply_filters( 'wpml_active_languages', null, null );
}
function active_translation_languages( $languages, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return $languages;
  }
	return get_active_translation_languages();
}

/**
 * Save the title translation
 *
 * @since   3.1.25
 */
function save_title_translation( $title, $post_id, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return false;
  }

	$package = array(
    'kind' => __( 'Ditty', 'ditty-news-ticker' ),
    'name' => $post_id,
    'title' => sprintf( __( 'Ditty ID: %d' ), $post_id ),
  );
  do_action( 'wpml_register_string', $title, "ditty_title", $package, 'ditty_title', 'LINE' );
}

/**
 * Save the item translation
 *
 * @since   3.1.25
 */
function save_item_translation( $item, $keys, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return false;
  }

	$ditty_id = $item['ditty_id'];
  $item_id = $item['item_id'];
  $item_value = isset( $item['item_value'] ) ? $item['item_value'] : false;
  if ( $item_value && is_array( $keys ) && count( $keys ) > 0 ) {
    $package = array(
      'kind' => __( 'Ditty', 'ditty-news-ticker' ),
      'name' => $ditty_id,
      'title' => sprintf( __( 'Ditty ID: %d' ), $ditty_id ),
    );

    foreach ( $keys as $key_id => $key ) {
      if ( isset( $item_value[$key_id] ) ) {
        $string_value = $item_value[$key_id];
        $label = sprintf( __( 'Item %d: %s', 'ditty-news-ticker' ), $item_id, $key['label'] );
        do_action( 'wpml_register_string', $string_value, "item_{$item_id}_{$key_id}", $package, $label, $key['type'] );
      }
    }
  }
}

/**
 * Delete an item translation
 *
 * @since   3.1.25
 */
function delete_item_translation( $item, $keys, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return false;
  }
  global $wpdb;

	$names_to_delete = [];
  foreach ( $keys as $key_id => $key ) {
    $names_to_delete[] = "item_{$item['item_id']}_{$key_id}";
  }
  
  $placeholders = array_fill(0, count($names_to_delete), '%s');
  
  // Get IDs of of strings
  $sql = "SELECT id FROM {$wpdb->prefix}icl_strings WHERE name IN (" . implode(', ', $placeholders) . ")";
  $ids = $wpdb->get_col( $wpdb->prepare($sql, $names_to_delete) );
  
  // Delete translations of string
  $id_placeholders = array_fill(0, count( $ids ), '%s');
  $sql = "DELETE FROM {$wpdb->prefix}icl_string_translations WHERE string_id IN (" . implode(', ', $id_placeholders) . ")";
  $results = $wpdb->query( $wpdb->prepare( $sql, $ids ) );
  
  // Delete the strings
  $sql = "DELETE FROM {$wpdb->prefix}icl_strings WHERE name IN (" . implode(', ', $placeholders) . ")";
  $results = $wpdb->query( $wpdb->prepare( $sql, $names_to_delete ) );
}

/**
 * Delete a ditty package
 * 
 * @since   3.1.25
 */
function delete_post_translations( $post_id, $post_type ) {
  if ( 'ditty' == $post_type ) {
	  do_action( 'wpml_delete_package', $post_id, __( 'Ditty', 'ditty-news-ticker' ) );
  }
}

/**
 * Delete transients
 * 
 * @since   3.1.25
 */
function delete_transients( $ditty_id ) {
  $languages = get_active_translation_languages();
  if ( is_array( $languages ) && count( $languages ) > 0 ) {
    foreach ( $languages as $language => $data ) {
      $transient_name = "ditty_display_items_{$ditty_id}_{$language}";
      delete_transient( $transient_name );
    }
  }
}
function delete_language_transients( $ditty_id, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return false;
  }
	delete_transients( $ditty_id );
}

/**
 * Clear language transients when translations are updated
 * 
 * @since   3.1.25
 */
function translation_updated( $st_id ) {

  // Get IDs of of strings
  global $wpdb;
  $st_id = intval($st_id); // Make sure $st_id is an integer (or sanitize appropriately)
  $sql = $wpdb->prepare("SELECT string_id FROM {$wpdb->prefix}icl_string_translations WHERE id = %d", $st_id);
  $ids = $wpdb->get_col($sql);
  if ( empty( $ids ) ) {
    return false;
  }

  // Get contexts of strings
  $id = intval($ids[0]); // Make sure $st_id is an integer (or sanitize appropriately)
  $sql = $wpdb->prepare("SELECT context FROM {$wpdb->prefix}icl_strings WHERE id = %d", $id);
  $contexts = $wpdb->get_col($sql);

  if ( empty( $contexts ) ) {
    return false;
  }

  $ditty_id = substr( $contexts[0], 6);

  delete_transients( $ditty_id );
}

/**
 * Translate a ditty title
 * 
 * @since   3.1.25
 */
function translate_title( $post_title, $post_id, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
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
 * Translate a ditty item
 * 
 * @since   3.1.25
 */
function translate_item( $item, $keys, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return $item;
  }

	$item_id = $item['item_id'];
  $item_value = isset( $item['item_value'] ) ? $item['item_value'] : false;

  $package = array(
    'kind' => __( 'Ditty', 'ditty-news-ticker' ),
    'name' => $item['ditty_id'],
    'title' => sprintf( __( 'Ditty ID: %d' ), $item['ditty_id'] ),
  );

  if ( $item_value && is_array( $keys ) && count( $keys ) > 0 ) {
    foreach ( $keys as $key_id => $key ) {
      if ( isset( $item_value[$key_id] ) ) {
        $original_value = $item_value[$key_id];
        $translated_string = apply_filters( 'wpml_translate_string', $original_value, "item_{$item_id}_{$key_id}", $package );
        $item_value[$key_id] = $translated_string;
        $item['item_value'] = $item_value;
      }
    }
  }
  return $item;
}