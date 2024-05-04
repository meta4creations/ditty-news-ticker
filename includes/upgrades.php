<?php

/**
 * Run updates
 */
function ditty_updates() {
	if ( wp_doing_ajax() ) {
		return false;
	}
	$current_version = get_option( 'ditty_plugin_version', '0' );

	if ( version_compare( $current_version, '3.0', '<' ) ) {
		ditty_v3_upgrades();
	}

  if ( version_compare( $current_version, '0', '>' ) ) {
    if ( version_compare( $current_version, '3.0.6', '<' ) ) {
      ditty_v3_0_6_upgrades();
    }
    if ( version_compare( $current_version, '3.0.14', '<' ) ) {
      ditty_v3_0_14_upgrades();
    }
    if ( version_compare( $current_version, '3.1', '<' ) ) {
      ditty_v3_1_upgrades();
    }
    if ( version_compare( $current_version, '3.1.6', '<' ) ) {
      ditty_v3_1_6_upgrades();
    }
    if ( version_compare( $current_version, '3.1.19', '<' ) ) {
      ditty_v3_1_19_upgrades();
    }
    if ( version_compare( $current_version, '3.1.24', '<' ) ) {
      ditty_v3_1_24_upgrades();
    }
    if ( version_compare( $current_version, '3.1.30', '<' ) ) {
      ditty_v3_1_30_upgrades();
    }
  }
	if ( DITTY_VERSION != $current_version ) {
		do_action( 'ditty_version_update', DITTY_VERSION, $current_version );
		update_option( 'ditty_plugin_version_upgraded_from', $current_version );
		update_option( 'ditty_plugin_version', DITTY_VERSION );
	}
}
add_action( 'admin_init', 'ditty_updates' );

/**
 * Version 3.1.30 Updates
 * Clean up WPML tables
 */
function ditty_v3_1_30_upgrades() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'icl_strings';
	$query = $wpdb->prepare('SHOW TABLES LIKE %s', $table_name);
	if ( ! $wpdb->get_var( $query ) == $table_name ) {
		return false;
	}

	// Get IDs of of strings
	$sql = "SELECT * FROM {$wpdb->prefix}icl_strings WHERE context LIKE %s";
	$like_pattern = '%ditty-%';
	$results = $wpdb->get_results( $wpdb->prepare($sql, $like_pattern) );
	
	$icl_string_ids = [];	
	if ( is_array( $results ) && count( $results ) > 0 ) {
		foreach ( $results as $i => $result ) {
			$id = substr( $result->context, 6 );
			$post_type = get_post_type( $id );
			if ( 'ditty' != $post_type ) {
				$icl_string_ids[] = $result->id;
			}
		}
	}
	
	// Delete the translations & strings
	if ( ! empty( $icl_string_ids ) ) {
		
		// Delete the translations
		$id_placeholders = array_fill(0, count( $icl_string_ids ), '%s');
		$sql = "DELETE FROM {$wpdb->prefix}icl_string_translations WHERE string_id IN (" . implode(', ', $id_placeholders) . ")";
		$results = $wpdb->query( $wpdb->prepare( $sql, $icl_string_ids ) );
		
		// Delete the strings
		$sql = "DELETE FROM {$wpdb->prefix}icl_strings WHERE id IN (" . implode(', ', $id_placeholders) . ")";
		$results = $wpdb->query( $wpdb->prepare( $sql, $icl_string_ids ) );
	}
	
	// Delete packages
	// Get IDs of of packages
	$sql = "SELECT * FROM {$wpdb->prefix}icl_string_packages WHERE kind_slug = %s";
	$results = $wpdb->get_results( $wpdb->prepare($sql, 'ditty' ) );
	
	$icl_package_ids = [];	
	if ( is_array( $results ) && count( $results ) > 0 ) {
		foreach ( $results as $i => $result ) {
			$post_type = get_post_type( $result->name );
			if ( 'ditty' != $post_type ) {
				$icl_package_ids[] = $result->ID;
			}
		}
	}
	
	if ( ! empty( $icl_package_ids ) ) {
		
		// Delete the translations
		$id_placeholders = array_fill(0, count( $icl_package_ids ), '%s');
		$sql = "DELETE FROM {$wpdb->prefix}icl_string_packages WHERE ID IN (" . implode(', ', $id_placeholders) . ")";
		$results = $wpdb->query( $wpdb->prepare( $sql, $icl_package_ids ) );
	}
}

/**
 * Version 3.1.24 Updates
 */
function ditty_v3_1_24_upgrades() {
  // Update the title font-size and line-height to typography settings
	$args = array(
		'post_type' => 'ditty',
		'post_status' => 'any',
	);
	$dittys = get_posts( $args );
	if ( is_array( $dittys ) && count( $dittys ) > 0 ) {
		foreach ( $dittys as $ditty ) {
      $display = get_post_meta(  $ditty->ID, '_ditty_display', true );
      if ( is_array( $display ) ) {
        $settings = isset( $display['settings'] ) ? $display['settings'] : [];  
        $font_size = isset( $settings['titleFontSize'] ) ? $settings['titleFontSize'] : false;
        $line_height = isset( $settings['titleLineHeight'] ) ? $settings['titleLineHeight'] : false;
        if ( $font_size || $line_height ) {
          $typography = [
            'fontSize' => $font_size,
            'lineHeight' => $line_height,
          ];
          $settings['titleTypography'] = $typography;
          $display['settings'] = $settings;
          update_post_meta(  $ditty->ID, '_ditty_display', $display );
        }
      }	
		}
	}

  $args = array(
		'post_type' => 'ditty_display',
		'post_status' => 'any',
	);
	$displays = get_posts( $args );
	if ( is_array( $displays ) && count( $displays ) > 0 ) {
		foreach ( $displays as $display ) {
      $settings = get_post_meta(  $display->ID, '_ditty_display_settings', true );
      $font_size = isset( $settings['titleFontSize'] ) ? $settings['titleFontSize'] : false;
      $line_height = isset( $settings['titleLineHeight'] ) ? $settings['titleLineHeight'] : false;
      if ( $font_size || $line_height ) {
        $typography = [
          'fontSize' => $font_size,
          'lineHeight' => $line_height,
        ];
        $settings['titleTypography'] = $typography;
        update_post_meta(  $display->ID, '_ditty_display_settings', $settings );
      }
		}
	}
}

/**
 * Version 3.1.19 Updates
 *
 * @since  3.1.19
 * @return void
 */
function ditty_v3_1_19_upgrades() {
  // Convert the display shuffle to orderby random
	$args = array(
		'post_type' => 'ditty',
		'post_status' => 'any',
	);
	$dittys = get_posts( $args );
	if ( is_array( $dittys ) && count( $dittys ) > 0 ) {
		foreach ( $dittys as $ditty ) {
      $display = get_post_meta(  $ditty->ID, '_ditty_display', true );
      if ( is_array( $display ) ) {
        $settings = isset( $display['settings'] ) ? $display['settings'] : [];
        if ( isset( $settings['shuffle'] ) && '1' == $settings['shuffle'] ) {
          $settings['orderby'] = 'random';
          unset( $settings['shuffle'] );
          $display['settings'] = $settings;
          update_post_meta(  $ditty->ID, '_ditty_display', $display );
        } 
      }	
		}
	}

  $args = array(
		'post_type' => 'ditty_display',
		'post_status' => 'any',
	);
	$displays = get_posts( $args );
	if ( is_array( $displays ) && count( $displays ) > 0 ) {
		foreach ( $displays as $display ) {
      $settings = get_post_meta(  $display->ID, '_ditty_display_settings', true );
      if ( isset( $settings['shuffle'] ) && '1' == $settings['shuffle'] ) {
        $settings['orderby'] = 'random';
        unset( $settings['shuffle'] );
        update_post_meta(  $display->ID, '_ditty_display_settings', $settings );
      } 
		}
	}
}

/**
 * Version 3.1.6 Updates
 *
 * @since  3.1.6
 * @return void
 */
function ditty_v3_1_6_upgrades() {
	$disable_fontawesome = get_ditty_settings( 'disable_fontawesome' );
	$ditty_news_ticker = get_ditty_settings( 'ditty_news_ticker' );
	$disable_fontawesome_update = ( '1' == $disable_fontawesome ) ? 'disabled' : 'enabled';
	$ditty_news_ticker_update = ( '1' == $ditty_news_ticker ) ? 'enabled' : 'disabled';
	update_ditty_settings( [
		'disable_fontawesome' => $disable_fontawesome_update,
		'ditty_news_ticker' => $ditty_news_ticker_update,
	] );
}

/**
 * Version 3.1 Updates
 *
 * @since  3.1
 * @return void
 */
function ditty_v3_1_tag_upgrades( $attribute_value, $tag, $attribute, $value ) {
	if ( ! isset( $attribute_value[$tag] ) ) {
		$attribute_value[$tag] = [];
	}
	$attribute_value[$tag][$attribute] = [
		'customValue' => '1',
		'value' => $value
	];
	return $attribute_value;
}
function ditty_v3_1_item_tag_upgrades( $item ) {
	$item_value = $item->item_value;
	$attribute_value = $item->attribute_value ? $item->attribute_value : [];
	if ( is_array( $item_value ) && count( $item_value ) > 0 ) {
		foreach ( $item_value as $key => $value ) {
			if ( '' == $value || 'default' == $value ) {
				continue;
			}
			switch( $key ) {
				case 'title_element':
					$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'title', 'wrapper', $value );
					break;
				case 'title_link':
					$modified_value = ( 'off' == $value ) ? 'none' : 'true';
					$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'title', 'link', $modified_value );
					break;
				case 'content_display':
					if ( 'excerpt' == $value ) {
						$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'content', 'content_display', $value );
					}
					break;
				case 'excerpt_element':
					if ( isset( $item_value['content_display'] ) && 'excerpt' == $item_value['content_display'] ) {
						$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'content', 'wrapper', $value );
					}
					break;
				case 'excerpt_length':
					if ( isset( $item_value['content_display'] ) && 'excerpt' == $item_value['content_display'] ) {
						$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'content', 'excerpt_length', $value );
					}
					break;
				case 'more':
					if ( isset( $item_value['content_display'] ) && 'excerpt' == $item_value['content_display'] ) {
						$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'content', 'more', $value );
					}
					break;
				case 'more_before':
					if ( isset( $item_value['content_display'] ) && 'excerpt' == $item_value['content_display'] ) {
						$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'content', 'more_before', $value );
					}
					break;
				case 'more_after':
					if ( isset( $item_value['content_display'] ) && 'excerpt' == $item_value['content_display'] ) {
						$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'content', 'more_after', $value );
					}
					break;
				case 'more_link':
					if ( isset( $item_value['content_display'] ) && 'excerpt' == $item_value['content_display'] ) {
						$modified_value = ( 'false' == $value ) ? 'none' : 'true';
						$attribute_value = ditty_v3_1_tag_upgrades( $attribute_value, 'content', 'more_link', $modified_value );
					}
					break;
			}
		}
	}
	if ( ! empty( $attribute_value ) ) {
		$sanitized__attribute_value = Ditty()->singles->sanitize_item_attribute_value( $attribute_value, $item->item_type );
		$updated_item = [
			'attribute_value' => json_encode( $sanitized__attribute_value ),
		];
		Ditty()->db_items->update( $item->item_id, $updated_item, 'item_id' );	
		return $updated_item;
	}
}
function ditty_v3_1_upgrades() {

	// Update the database - KEEP
	$db_items = new Ditty_DB_Items();
	@$db_items->create_table();

	// Delete the deprecated layout_id columns - KEEP
	global $wpdb;
	$table_name = $wpdb->prefix . 'ditty_items';
	$column_name = 'layout_id';
	if ($wpdb->get_var("SHOW COLUMNS FROM $table_name LIKE '$column_name'") === $column_name) {
		$wpdb->query("ALTER TABLE $table_name DROP COLUMN $column_name");
	}

	// Update custom tag attributes - KEEP
	$args = array(
		'post_type' => 'ditty',
		'post_status' => 'any',
	);
	$dittys = get_posts( $args );
	if ( is_array( $dittys ) && count( $dittys ) > 0 ) {
		foreach ( $dittys as $ditty ) {
			$items_meta = ditty_items_meta( $ditty->ID );
			if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {
				foreach ( $items_meta as $item ) {
					ditty_v3_1_item_tag_upgrades( $item );
				}
			}			
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
	
	// If News Tickers exists, enabled legacy code
	$args = array(
		'post_type' => 'ditty_news_ticker',
	);
	$news_tickers = get_posts( $args );
	if ( is_array( $news_tickers ) && count( $news_tickers ) > 0 ) {
		update_ditty_settings( 'ditty_news_ticker', 'enabled' );
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