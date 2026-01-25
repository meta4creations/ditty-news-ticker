<?php
namespace Ditty\V4;

/**
 * Get the value of a spacing preset
 *
 * @param string $slug The slug of the spacing preset
 * @return int|null The value of the spacing preset
 */
function get_spacing_preset_value( $slug ) {
  if ( is_string( $slug ) && 0 === strpos( $slug, 'var:preset|spacing|' ) ) {
    $slug = str_replace( 'var:preset|spacing|', '', $slug );
  }
  
  $settings = wp_get_global_settings( array( 'spacing', 'spacingSizes' ) );

  // 1. Check Theme presets first (higher priority)
  if ( ! empty( $settings['theme'] ) ) {
    foreach ( $settings['theme'] as $preset ) {
      if ( $preset['slug'] === (string) $slug ) {
        return $preset['size'];
      }
    }
  }

  // 2. Fallback to Default presets
  if ( ! empty( $settings['default'] ) ) {
    foreach ( $assettings['default'] as $preset ) {
      if ( $preset['slug'] === (string) $slug ) {
        return $preset['size'];
      }
    }
  }

  return null;
}