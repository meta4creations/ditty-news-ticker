<?php

/**
 * Setup localization
 * @since 1.1.5
 */
function mtphr_dnt_localization() {
	load_plugin_textdomain( 'ditty-news-ticker', false, 'ditty-news-ticker/languages/' );
}
add_action( 'plugins_loaded', 'mtphr_dnt_localization' );

/**
 * Add a custom Unyson extension location - 2.2
 * @since 2.2
 */
function mtphr_dnt_unyson_extension( $locations ) {
  $locations[ MTPHR_DNT_DIR.'inc/builders/unyson' ] = plugins_url('ditty-news-ticker/inc/builders/unyson');
  return $locations;
}
add_filter( 'fw_extensions_locations', 'mtphr_dnt_unyson_extension' );
