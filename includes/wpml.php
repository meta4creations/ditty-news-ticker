<?php

namespace Ditty\WPML;

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
add_filter( 'ditty_translation_language', 'Ditty\WPML\translation_language', 10, 3 );

/**
 * Get the current language
 * 
 * @since   3.1.25
 */
function active_translation_languages( $languages, $translation_plugin ) {
  if ( 'wpml' != $translation_plugin ) {
    return $languages;
  }
	return apply_filters( 'wpml_active_languages', null, null );
}
add_filter( 'ditty_active_translation_languages', 'Ditty\WPML\active_translation_languages', 10, 2 );

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
add_action( 'ditty_save_title_translation', 'Ditty\WPML\save_title_translation', 10, 3 );

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

    foreach ( $keys as $key_id => $key_label ) {
      if ( isset( $item_value[$key_id] ) ) {
        $string_value = $item_value[$key_id];
        $label = sprintf( __( 'Item %d: %s', 'ditty-news-ticker' ), $item_id, $key_label );
        do_action( 'wpml_register_string', $string_value, "item_{$item_id}_{$key_id}", $package, $label, 'LINE' );
      }
    }
  }
}
add_action( 'ditty_save_item_translation', 'Ditty\WPML\save_item_translation', 10, 3 );

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
  foreach ( $keys as $key_id => $key_label ) {
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
add_action( 'ditty_delete_item_translation', 'Ditty\WPML\delete_item_translation', 10, 3 );

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
add_action( 'ditty_delete_post_translations', 'Ditty\WPML\delete_post_translations', 10, 2 );

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
add_filter( 'ditty_translate_title', 'Ditty\WPML\translate_title', 10, 3 );

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
      foreach ( $keys as $key_id => $key_label ) {
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
add_filter( 'ditty_translate_item', 'Ditty\WPML\translate_item', 10, 3 );