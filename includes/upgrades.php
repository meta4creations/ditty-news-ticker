<?php

/**
 * Run updates
 *
 * @since  3.0.13
 * @return void
 */
function ditty_updates() {
	if ( wp_doing_ajax() ) {
		return false;
	}
	$current_version = get_option( 'ditty_plugin_version', '0' );

	if ( version_compare( $current_version, '3.0', '<' ) ) {
		ditty_v3_upgrades();
	}
	if ( version_compare( $current_version, '3.0.6', '<' ) ) {
		ditty_v3_0_6_upgrades();
	}
	if ( version_compare( $current_version, '3.0.14', '<' ) ) {
		ditty_v3_0_14_upgrades();
	}
	if ( version_compare( $current_version, '3.1', '<' ) ) {
		//ditty_v3_1_upgrades();
	}

	if ( DITTY_VERSION != $current_version ) {
		update_option( 'ditty_plugin_version_upgraded_from', $current_version );
		update_option( 'ditty_plugin_version', DITTY_VERSION );
	}
}
add_action( 'admin_init', 'ditty_updates' );

/**
 * Version 3.1 Updates
 *
 * @since  3.1
 * @return void
 */
function ditty_v3_1_upgrades() {
	
	// Update the Ditty preview padding
	$args = array(
		'post_type' => 'ditty',
	);
	$dittys = get_posts( $args );
	if ( is_array( $dittys ) && count( $dittys ) > 0 ) {
		foreach ( $dittys as $i => $ditty ) {
			$settings = get_post_meta( $ditty->ID, '_ditty_settings', true );
			if ( ! is_array( $settings ) ) {
				$settings = array();
			}
			$padding = isset( $settings['previewPadding'] ) ? $settings['previewPadding'] : [];
			$settings['previewPadding'] = [
				'top' => isset( $padding['paddingTop'] ) ? $padding['paddingTop'] : 0,
				'left' => isset( $padding['paddingLeft'] ) ? $padding['paddingLeft'] : 0,
				'right' => isset( $padding['paddingRight'] ) ? $padding['paddingRight'] : 0,
				'bottom' => isset( $padding['paddingBottom'] ) ? $padding['paddingBottom'] : 0,
			];
			update_post_meta( $ditty->ID, '_ditty_settings', $settings );
		}
	}
}

/**
 * Version 3.0.13 Updates
 *
 * @since  3.0.13
 * @return void
 */
function ditty_v3_0_14_upgrades() {
	
	// Update the database
	$db_items = new Ditty_DB_Items();
	@$db_items->create_table();
	
	// Add uniq_ids to each Layout & Display
	$args = array(
		'posts_per_page' => -1,
		'post_type' => array( 'ditty_layout', 'ditty_display' ),
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key' 	=> '_ditty_uniq_id',
				'compare'	=> 'NOT EXISTS',
			)
		),
	);
	$posts = get_posts( $args );
	if ( is_array( $posts ) && count( $posts ) > 0 ) {
		foreach ( $posts as $i => $post ) {
			ditty_maybe_add_uniq_id( $post->ID );
		}
	}
	
	// Add uniq_ids and init data to each Ditty
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'ditty',
		'post_status' => 'any',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' 		=> '_ditty_uniq_id',
				'compare'	=> 'NOT EXISTS',
			),
			array(
				'key' 		=> '_ditty_init',
				'compare'	=> 'NOT EXISTS',
			)
		),
	);
	$posts = get_posts( $args );
	if ( is_array( $posts ) && count( $posts ) > 0 ) {
		foreach ( $posts as $i => $post ) {
			ditty_maybe_add_uniq_id( $post->ID );
			update_post_meta( $post->ID, '_ditty_init', 'yes' );

			// Add a date created and modified based on post created time
			$all_meta = Ditty()->db_items->get_items( $post->ID );
			if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
				foreach ( $all_meta as $i => $meta ) {
					$add_data = array(
						'item_author' 	=> intval( $post->post_author ),
						'date_created' 	=> sanitize_text_field( $post->post_date_gmt ),
						'date_modified' => sanitize_text_field( $post->post_date_gmt ),
					);
					Ditty()->db_items->update( $meta->item_id, $add_data, 'item_id' );
				} 
			}
		}
	}
}

/**
 * Version 3.0.6 Updates
 *
 * @since  3.0.6
 * @return void
 */
function ditty_v3_0_6_upgrades() {
	$ditty_notices = get_option( 'ditty_notices', array() );
	$args = array(
		'post_type' => 'ditty_news_ticker',
	);
	$news_tickers = get_posts( $args );
	if ( is_array( $news_tickers ) && count( $news_tickers ) > 0 ) {
		$ditty_notices['v3_0_6'] = 'v3_0_6';
		update_option( 'ditty_notices', $ditty_notices );
	}
}

/**
 * Version 3.0 Updates
 *
 * @since  3.0
 * @return void
 */
function ditty_v3_upgrades() {
	
	// Update extension licenses
	$licenses = ( is_multisite() ) ? get_site_option( 'mtphr_edd_licenses' ) : get_option( 'mtphr_edd_licenses' );
	$license_data = ( is_multisite() ) ? get_site_option( 'mtphr_edd_license_data' ) : get_option( 'mtphr_edd_license_data' );
	$updated_licenses = array();
	if ( is_array( $licenses ) && count( $licenses ) > 0 ) {
		foreach ( $licenses as $slug => $license ) {
			$updated_slug = '';
			switch ( $slug ) {
				case 'ditty-facebook-ticker':
					$updated_slug = 'facebook';
					break;
				case 'ditty-image-ticker':
					$updated_slug = 'images';
					break;
				case 'ditty-instagram-ticker':
					$updated_slug = 'instagram';
					break;
				case 'ditty-mega-ticker':
					$updated_slug = 'mega';
					break;
				case 'ditty-posts-ticker':
					$updated_slug = 'posts';
					break;
				case 'ditty-rss-ticker':
					$updated_slug = 'rss';
					break;
				case 'ditty-timed-ticker':
					$updated_slug = 'timing';
					break;
				case 'ditty-twitter-ticker':
					$updated_slug = 'twitter';
					break;
				default:
					break;
			}
			if ( '' != $updated_slug ) {
				$updated_license = array(
					'key' => $license
				);
				if ( isset( $license_data[$slug] ) ) {
					$updated_license['status'] = isset( $license_data[$slug]->license ) ? esc_attr( $license_data[$slug]->license ) : false;
					$updated_license['expires'] = isset( $license_data[$slug]->expires ) ? sanitize_text_field( $license_data[$slug]->expires ) : false;
				}
				$updated_licenses[$updated_slug] = $updated_license;
			}
		}
	}
	if ( is_multisite() ) {
		update_site_option( 'ditty_licenses', $updated_licenses );
	} else {
		update_option( 'ditty_licenses', $updated_licenses );
	}

	// Setup the Ditty Custom Post Types
	ditty_setup_post_types();
	flush_rewrite_rules( false );
	
	// Create Ditty roles
	$roles = new Ditty_Roles();
	$roles->add_caps();
	
	// Create the item databases		
	$db_items = new Ditty_DB_Items();
	@$db_items->create_table();
	
	$db_item_meta = new Ditty_DB_Item_Meta();
	@$db_item_meta->create_table();
	
	// Install default layouts
	Ditty()->layouts->install_default( 'default' );
	Ditty()->layouts->install_default( 'default_image' );
	Ditty()->layouts->install_default( 'default_post' );
	Ditty()->displays->install_default( 'ticker', 'default' );
	Ditty()->displays->install_default( 'list', 'default' );
	Ditty()->displays->install_default( 'list', 'default_slider' );
	
	// Set variation defaults
	ditty_set_variation_default( 'default', 'default', 'default' );
	ditty_set_variation_default( 'wp_editor', 'default', 'default' );
	ditty_set_variation_default( 'posts_feed', 'default', 'default_post' );
	
	// If News Tickers exists, enabled legacy code
	$args = array(
		'post_type' => 'ditty_news_ticker',
	);
	$news_tickers = get_posts( $args );
	if ( is_array( $news_tickers ) && count( $news_tickers ) > 0 ) {
		ditty_settings( 'ditty_news_ticker', '1' );
	}
}

/**
 * Add upgrade notices
 *
 * @since    3.0.13
*/
function ditty_upgrade_notices() {
	$ditty_upgrades = get_option( 'ditty_upgrades', array() );
	$ditty_upgrades = array(
		'3_1' => 'testing',
	);
	if ( ! empty( $ditty_upgrades ) ) {
		?>
		<div class="notice notice-info ditty-dashboard-notice ditty-dashboard-notice--upgrade">
			<div class="ditty-dashboard-notice__content">
				<p><?php printf( __( 'Ditty v%s requires updates. Click the button below to get started!', 'ditty-news-ticker' ), ditty_version() ); ?></p>
				<p class="ditty-upgrade__element">
					<a href="#" class="button ditty-upgrade__start"><?php _e( 'Start Update', 'ditty-news-ticker' ); ?></a>
					<span class="ditty-upgrade__bar">
						<span class="ditty-upgrade__progress"></span>
					</span>
				</p>
			</div>
		</div>
		<?php
	}
}
//add_action( 'admin_notices', 'ditty_upgrade_notices' );