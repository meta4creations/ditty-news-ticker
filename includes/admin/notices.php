<?php

/**
 * Add dashboard notices
 *
 * @since    3.0.6
*/
function ditty_dashboard_notices() {
	$ditty_notices = get_option( 'ditty_notices', array() );
	
	// Remove notice
	$close_notice = isset( $_GET['ditty_close_notice'] ) ? $_GET['ditty_close_notice'] : false;
	if ( $close_notice && isset( $ditty_notices[$close_notice] ) ) {
		unset( $ditty_notices[$close_notice] );
		update_option( 'ditty_notices', $ditty_notices );
	}

	if ( isset( $ditty_notices['v3_0_6'] ) ) {
		?>
			<div class="notice notice-info ditty-dashboard-notice" data-notice_id="v3_0_6">
				<div class="ditty-dashboard-notice__content">
					<p><?php printf( __( 'Ditty v%s', 'ditty-news-ticker' ), ditty_version() ); ?></p>
					<p><?php _e( '<strong>Ditty News Ticker</strong> has now become <strong>Ditty</strong>!', 'ditty-news-ticker' ); ?></p>
					<p><?php _e( "This major upgrade includes a complete rebuild of the Ditty News Ticker package. Due to the size of the ugrade we opted to create a new <strong>Ditty</strong> post type that you can start using right away. Ditty offers you more control over the display and look of your content, along with many highly requested features.", 'ditty-news-ticker' ); ?></p>
					<ul class="ditty-features-list">
						<li><i class="fas fa-redo-alt"></i> <?php _e( 'Live Updates', 'ditty-news-ticker' ); ?></li>
						<li><i class="fas fa-edit"></i> <?php _e( 'Live Editing', 'ditty-news-ticker' ); ?></li>
						<li><i class="fas fa-plus-square"></i> <?php _e( 'Global Rendering', 'ditty-news-ticker' ); ?></li>
						<li><i class="fas fa-random"></i> <?php _e( 'Mix & Match Content', 'ditty-news-ticker' ); ?></li>
						<li><i class="fas fa-tablet-alt"></i> <?php _e( 'Customized Displays', 'ditty-news-ticker' ); ?></li>
						<li><i class="fas fa-pencil-ruler"></i> <?php _e( 'Customized Layouts', 'ditty-news-ticker' ); ?></li>
					</ul>
					<p><?php _e( "Don't worry, all of your existing <strong>News Tickers</strong> will still work! Although, we do urge you to start upgrading and updating your tickers to the new <strong>Ditty</strong> post type. <strong>Ditty News Ticker</strong> is now relegated to legacy code and there will be very limited updates from this point on. Most development time will now be assigned to <strong>Ditty</strong> along with existing and new <strong>Ditty</strong> extensions. Legacy <strong>News Tickers</strong> can be enabled on the <strong>Advanced</strong> tab of the <strong>Ditty > Settings</strong> page.", 'ditty-news-ticker' ); ?></p>
				</div>
				<a href="<?php echo add_query_arg( 'ditty_close_notice', 'v3_0_6' ); ?>" class="ditty-dashboard-notice__close"><?php _e( 'Close', 'ditty-news-ticker' ); ?><i class="dashicons dashicons-dismiss"></i></a>
			</div>
		<?php
	}
}
add_action( 'admin_notices', 'ditty_dashboard_notices' );


/**
 * Close and save a notice
 *
 * @since    3.0.6
*/
function ditty_notice_close_ajax() {
	check_ajax_referer( 'ditty', 'security' );
	$notice_id_ajax = isset( $_POST['notice_id'] ) ? $_POST['notice_id'] : false;
	if ( ! $notice_id_ajax ) {
		wp_die();
	}
	$ditty_notices = get_option( 'ditty_notices', array() );
	unset( $ditty_notices[$notice_id_ajax] );
	update_option( 'ditty_notices', $ditty_notices );
}
add_action( 'wp_ajax_ditty_notice_close', 'ditty_notice_close_ajax' );