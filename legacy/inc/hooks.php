<?php
	
/**
 * Add a custom Unyson extension location - 2.2
 * @since 2.2
 */
function mtphr_dnt_unyson_extension( $locations ) {
  $locations[DITTY_DIR.'legacy/inc/builders/unyson'] = plugins_url('ditty-news-ticker/inc/builders/unyson');
  return $locations;
}
add_filter( 'fw_extensions_locations', 'mtphr_dnt_unyson_extension' );

/**
 * Add redirects from old settings to new settings
 *
 * @since    3.0
 * @access   public
 * @var      array    $allowed
*/
function ditty_news_ticker_redirects() {
  $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
  $page = isset( $_GET['page'] ) ? $_GET['page'] : '';
  $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
    
  if ( 'ditty_news_ticker' == $post_type && 'mtphr_dnt_settings' == $page && '' != $tab ) {
    $redirect = add_query_arg( array(
      'post_type' => 'ditty',
      'page'	=> $page,
      'tab' => $tab,
    ), admin_url( 'edit.php' ) );
    wp_safe_redirect( $redirect );
    exit;
  }
}
add_filter( 'admin_init', 'ditty_news_ticker_redirects' );