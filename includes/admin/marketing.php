<?php

/**
 * Return an array of item types
 * 
 * @since   3.1.22
*/
function ditty_api_marketing() {
	global $ditty_api_marketing;
	if ( empty( $ditty_api_marketing ) ) {
		$transient_name = "ditty_api_marketing";
		$ditty_api_marketing = get_transient( $transient_name );
		if ( ! $ditty_api_marketing ) {
			$response = wp_safe_remote_get( 'https://www.metaphorcreations.com/wp-json/dittysales/v1/marketing' );
			if ( is_wp_error( $response ) ) {
				$ditty_api_marketing = [];
			} else {
				$data = wp_remote_retrieve_body( $response );
				$ditty_api_marketing = json_decode( $data, true );
			}
      set_transient( $transient_name, $ditty_api_marketing, DAY_IN_SECONDS );
		}
	}
	return $ditty_api_marketing;
}

/**
 * Return an array of item types
 * 
 * @since   3.1.25
*/
function ditty_api_notices() {
  $marketing = ditty_api_marketing();
  if ( isset( $marketing['notices'] ) ) {
    return array_values( $marketing['notices'] );
  }
}

/**
 * Return an array of item types
 * 
 * @since   3.1.22
*/
function ditty_api_item_types() {
  $marketing = ditty_api_marketing();
  if ( isset( $marketing['item_types'] ) ) {
    return array_values( $marketing['item_types'] );
  }
}

/**
 * Return an array of display types
 * 
 * @since   3.1.22
*/
function ditty_api_display_types() {
  $marketing = ditty_api_marketing();
  if ( isset( $marketing['display_types'] ) ) {
    return array_values( $marketing['display_types'] );
  }
}

