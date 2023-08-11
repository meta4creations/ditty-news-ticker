<?php

function adfoaisdjfoajsdf() {
	if ( $ditty_id = ditty_editing() ) {
		echo '<pre>';print_r($_GET);echo '</pre>';	
		echo '<pre>';print_r($_POST);echo '</pre>';	
	}
}
//add_action( 'init', 'adfoaisdjfoajsdf' );

function force_classic_editor_for_custom_post_type( $editor ) {
		global $post;

		// Replace 'my_custom_post_type' with the slug of your custom post type.
		if ( $post->post_type === 'ditty' ) {
				return 'classic';
		}

		return $editor;
}
add_filter( 'wpml_should_use_translation_editor', 'force_classic_editor_for_custom_post_type' );



function ditty_save_wpml_data( $ditty_id, $params ) {
  
  return $params;
}
//add_filter( 'ditty_save_url_params', 'ditty_save_wpml_data' );

function ditty_wpml_make_post_duplicates( $post_id ) {
	ChromePhp::log( 'wpml $post_id', $post_id );
}
//add_action( 'wpml_make_post_duplicates', 'ditty_wpml_make_post_duplicates' );

// function force_native_editor_for_custom_post_type( $post_ID, $post, $update ) {
// 	if ( 'ditty' === $post->post_type ) {
// 		global $sitepress;
// 		if ( method_exists( $sitepress, 'get_wp_api' ) ) {
// 			$wp_api = $sitepress->get_wp_api();
// 			if ( method_exists( $wp_api, 'update_post_meta' ) ) {
// 				$wp_api->update_post_meta( $post_ID, '_wpml_trid', false );
// 				$wp_api->update_post_meta( $post_ID, '_wpml_language_abbreviation', false );
// 				$wp_api->update_post_meta( $post_ID, '_wpml_element_type', false );
// 			}
// 		}
// 	}
// }
// add_action( 'save_post', 'force_native_editor_for_custom_post_type', 10, 3 );
