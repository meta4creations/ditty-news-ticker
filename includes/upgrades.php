<?php

/**
 * Run updates
 *
 * @since  3.0
 * @return void
 */
function ditty_updates() {
	$save_version = false;
	$current_version = get_option( 'ditty_version', '0' );
	if ( version_compare( $current_version, '3.0', '<' ) ) {
		ditty_v30_upgrades( false );
	}
	
	if ( DITTY_VERSION != $current_version ) {
		update_option( 'ditty_version_upgraded_from', $current_version );
		update_option( 'ditty_version', DITTY_VERSION );
	}
}
add_action( 'admin_init', 'ditty_updates' );

/**
 * Version 3.0 Updates
 *
 * @since  3.0
 * @return void
 */
function ditty_v30_upgrades( $run_install = false ) {
	
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
					$updated_slug = 'image';
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
	Ditty()->layouts->install_default( 'default', 'default' );
	Ditty()->layouts->install_default( 'wp_editor', 'default' );
	Ditty()->displays->install_default( 'ticker', 'default' );
	Ditty()->displays->install_default( 'list', 'default' );
	Ditty()->displays->install_default( 'list', 'default_slider' );
}