<?php

namespace Ditty\Admin\Notices;

/**
 * Get all notices
 * 
 * @since    3.1.25
 */
function get_notices() {
  $api_notices = ditty_api_notices();
  $ditty_dismissed_notices = get_option( 'ditty_dismissed_notices', array() );
  $ditty_notices = get_option( 'ditty_notices', array() );

  if ( is_array( $api_notices ) && count( $api_notices ) > 0 ) {
    foreach ( $api_notices as $api_notice ) {
      if ( ! isset( $ditty_dismissed_notices[$api_notice['id']] ) ) {
        $ditty_notices[] = $api_notice;
      }
    }
  }

  return $ditty_notices;
}

/**
 * Get all notices
 * 
 * @since    3.1.25
 */
function add_notice( $notice ) {
  // $notice = [
  //   'type,
  //   'id',
  //   'title',
  //   'content'
  //   'cta_text',
  //   'cta_link'
  // ];
  if ( ! isset( $notice['id'] ) ) {
    return false;
  }
  $notice['source'] = 'internal';
  $ditty_notices = get_option( 'ditty_notices', array() );
  $ditty_notices[$notice['id']] = $notice;
  update_option( 'ditty_notices', $ditty_notices );
}

/**
 * Display a notice
 * 
 * @since    3.1.25
 */
function display_notice( $notice ) {
  $type = isset( $notice['type'] ) ? $notice['type'] : 'info';
  $source = isset( $notice['source'] ) ? $notice['source'] : 'internal';
  $cta_link = isset( $notice['cta_link'] ) ? $notice['cta_link'] : false;
  if ( isset( $notice['campaign_name'] ) ) {
    $cta_link = add_query_arg( [
      'utm_source' => 'ditty',
      'utm_medium' => 'wp_dashboard',
      'utm_campaign' => $notice['campaign_name'],
    ], $cta_link );
  }
  $campaign_name = isset( $notice['campaign_name'] ) ? $notice['campaign_name'] : 'campaign_name';

  ?>
    <div class="notice notice-<?php echo esc_attr( $type ); ?> ditty-dashboard-notice" data-notice_id="<?php echo esc_attr( $notice['id'] ); ?>">
      <div class="ditty-dashboard-notice__content">
        <p class="ditty-dashboard-notice__heading">
          <span class="ditty-d"><svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 69.31 71.1" fill="currentColor" width="20"><path d="M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM61.91 65.6a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7ZM61.91 43.1a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7ZM61.91 20.6a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7Z"/></svg></span>
          <strong><?php echo sanitize_text_field( $notice['title'] ); ?></strong>
        </p>
        <?php echo wpautop( wp_kses_post( $notice['content'] ) ); ?>
      </div>
      <?php
      if ( $cta_link && isset( $notice['cta_text'] ) ) {
        ?>
        <p class="ditty-dashboard-notice__cta">
          <a href="<?php echo esc_url( $cta_link ); ?>" class="ditty-button"><?php echo sanitize_text_field( $notice['cta_text'] ); ?></a>
      </p>
        <?php
      }
      ?>
      <a href="<?php echo esc_url( add_query_arg( 'ditty_close_notice', $notice['id'] ) ); ?>" class="ditty-dashboard-notice__close" data-id="<?php echo esc_attr( $notice['id'] ); ?>" data-source="<?php echo esc_attr( $source ); ?>"><span><?php _e( 'Close', 'ditty-news-ticker' ); ?></span><i class="dashicons dashicons-dismiss"></i></a>
    </div>
  <?php
}

/**
 * Display all dashboard notices
 *
 * @since    3.1.25
*/
function display_notices() {
  $notices = get_notices();
  if ( is_array( $notices ) && count( $notices ) > 0 ) {
    foreach ( $notices as $n ) {
      display_notice( $n );
    }
  }
}
add_action( 'admin_notices', 'Ditty\Admin\Notices\display_notices' );


/**
 * Close and remove a notice
 *
 * @since    3.1.25
*/
function notice_close() {
	check_ajax_referer( 'ditty', 'security' );
	$notice_id_ajax = isset( $_POST['id'] ) ? $_POST['id'] : false;
  $notice_source_ajax = isset( $_POST['source'] ) ? $_POST['source'] : false;
	if ( ! $notice_id_ajax || ! $notice_source_ajax ) {
		wp_die();
	}
  if ( 'api' == $notice_source_ajax ) {
    $ditty_dismissed_notices = get_option( 'ditty_dismissed_notices', array() );
    $ditty_dismissed_notices[$notice_id_ajax] = $notice_id_ajax;
    update_option( 'ditty_dismissed_notices', $ditty_dismissed_notices );
  } else {
    $ditty_notices = get_option( 'ditty_notices', array() );
    if ( isset( $ditty_notices[$notice_id_ajax] ) ) {
      unset( $ditty_notices[$notice_id_ajax] );
    }
    update_option( 'ditty_notices', $ditty_notices );
  }

  wp_send_json( [
    'id' => $notice_id_ajax,
  ] );
}
add_action( 'wp_ajax_ditty_notice_close', 'Ditty\Admin\Notices\notice_close' );