<?php
	
/**
 * Return the settings defaults
 *
 * @since    3.1.15
*/
function ditty_settings_defaults( $key = false ) {	
	$defaults = array(
		'live_refresh'					=> 10,
		'variation_defaults'		=> [],
    'permissions'           => [],
		'global_ditty'					=> [],
		'ditty_news_ticker' 		=> 'disabled',
		'disable_fontawesome' 	=> 'enabled',
    'disable_googlefonts'   => 'enabled',
		'notification_email' 		=> '',
		'edit_links'						=> 'disabled',
	);
	$defaults = apply_filters( 'ditty_settings_defaults', $defaults );
		if ( $key ) {
			if ( isset( $defaults['key'] ) ) {
				return $defaults['key'];
			}
		} else {
			return $defaults;
		}
}

/**
 * Get Ditty settings
 *
 * @since    3.1.15
*/
function get_ditty_settings( $key=false ) {
	global $ditty_settings;
	if ( empty( $ditty_settings ) ) {
		$ditty_settings = get_option( 'ditty_settings', array() );
	}
  $ditty_settings = shortcode_atts( ditty_settings_defaults(), $ditty_settings );
	if ( $key ) {
		if ( isset( $ditty_settings[$key] ) ) {
			return $ditty_settings[$key];
		}
	} else {
		return $ditty_settings;
	}
}

/**
 * Set Ditty settings
 *
 * @since    3.1.15
*/
function update_ditty_settings( $key, $value='' ) {
	global $ditty_settings;
	if ( empty( $ditty_settings ) ) {
		$ditty_settings = get_option( 'ditty_settings', array() );
	}
	if ( is_array( $key ) ) {
		foreach ( $key as $k => $v ) {
			$ditty_settings[$k] = $v;
		}
    $ditty_settings = shortcode_atts( ditty_settings_defaults(), $ditty_settings );
		update_option( 'ditty_settings', $ditty_settings );
	} else {
		if ( $value ) {
			$ditty_settings[$key] = $value;
      $ditty_settings = shortcode_atts( ditty_settings_defaults(), $ditty_settings );
			update_option( 'ditty_settings', $ditty_settings );
		}
	}	
	if ( is_array( $key ) ) {
		return $ditty_settings;
	} else {
		if ( isset( $ditty_settings[$key] ) ) {
			return $ditty_settings[$key];
		}
	}
}

/**
 * Return the single settings defaults
 *
 * @since    3.1
*/
function ditty_single_settings_defaults() {	
	$defaults = array(
		'status'					=> 'publish',
		'ajax_loading'		=> 'no',
		'live_updates'		=> 'no',
    'orderby'         => 'list',
    'order'           => 'desc',
	);
	return apply_filters( 'ditty_single_settings_defaults', $defaults );
}

/**
 * Return a single Ditty setting
 *
 * @since    3.0.13
*/
function ditty_single_settings( $ditty_id, $key = false ) {
	global $ditty_single_settings;
	if ( ! isset( $ditty_single_settings[$ditty_id] ) ) {
		$ditty_single_settings[$ditty_id] = get_post_meta( $ditty_id, '_ditty_settings', true );
	}
	if ( ! is_array( $ditty_single_settings[$ditty_id] )  ) {
		$ditty_single_settings[$ditty_id] = array();
	}
	$ditty_single_settings[$ditty_id] = wp_parse_args( $ditty_single_settings[$ditty_id], ditty_single_settings_defaults() );
	if ( $key ) {
		if ( isset( $ditty_single_settings[$ditty_id][$key] ) ) {
			return $ditty_single_settings[$ditty_id][$key];
		}
	} else {
		return $ditty_single_settings[$ditty_id];
	}
}

/**
 * Return an array of item types
 * 
 * @since   3.1
*/
function ditty_item_types() {
	$item_types = [];
	$item_types['default'] = array(
		'type' 						=> 'default',
		'label' 					=> __( 'Default', 'ditty-news-ticker' ),
		'icon' 						=> 'faPencil',
		'description' 		=> __( 'Manually add HTML to the item.', 'ditty-news-ticker' ),
		'class_name'			=> 'Ditty_Item_Type_Default',
		'ditty_version' 	=> '3.1'
	);
	$item_types['wp_editor'] = array(
		'type' 				=> 'wp_editor',
		'label' 			=> __( 'WP Editor', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-edit',
		'description' => __( 'Manually add rich text content to the item.', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Item_Type_WP_Editor',
		'ditty_version' 	=> '3.1'
	);
	$item_types['html'] = array(
		'type' 				=> 'html',
		'label' 			=> __( 'HTML', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-code',
		'description' => __( 'Manually add custom HTML to the item.', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Item_Type_Html',
		'ditty_version' 	=> '3.1'
	);
	$item_types['posts_feed'] = array(
		'type' 				=> 'posts_feed',
		'label' 			=> __( 'WP Posts Feed (Lite)', 'ditty-news-ticker' ),
		'icon' 				=> 'fab fa-wordpress',
		'description' => __( 'Add a WP Posts feed.', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Item_Type_Posts_Lite',
    'is_lite'     => true,
	);
	$item_types = apply_filters( 'ditty_item_types', $item_types );
	ksort( $item_types );
	return $item_types;
}

/**
 * Return a type class object
 *
 * @since    3.1.20
 * @var      object	$type_object    
*/
function ditty_item_type_object( $type ) {
	$item_types = ditty_item_types();
	if ( isset( $item_types[$type] ) && isset( $item_types[$type]['class_name'] ) && class_exists( $item_types[$type]['class_name'] ) ) {
		$type_object = new $item_types[$type]['class_name'];
		return $type_object;
	}
}

/**
 * Return an array of ditty displays
 * 
 * @since   3.1
 */
function ditty_display_types() {
	$display_types = array();	
	$display_types['ticker'] = array(
		'label' 			=> __( 'Ticker', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-ellipsis-h',
		'description' => __( 'Basic news ticker display.', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Display_Type_Ticker',
	);
	$display_types['list'] = array(
		'label' 			=> __( 'List', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-list',
		'description' => __( 'Display items in a static list.', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Display_Type_List',
	);

	$display_types = apply_filters( 'ditty_display_types', $display_types );
	if ( is_array( $display_types ) && count( $display_types ) > 0 ) {
		foreach ( $display_types as $slug => &$display_type ) {
			if ( ! isset( $display_type['type'] ) ) {
				$display_type['type'] = $slug;
			}
		}
	}
	return apply_filters( 'ditty_display_types', $display_types );
}

/**
 * Return a display class object
 *
 * @since    3.1
 * @var      object	$display_object    
*/
function ditty_display_type_object( $type ) {
	$display_types = ditty_display_types();
	if ( isset( $display_types[$type] ) && isset( $display_types[$type]['class_name'] ) && class_exists( $display_types[$type]['class_name'] ) ) {
		$display_object = new $display_types[$type]['class_name'];
		return $display_object;
	}
}

/**
 * Add in legacy licenses
 *
 * @since    3.0
*/
function ditty_extension_legacy_licenses( $licenses ) {
	$legacy_licenses = apply_filters( 'mtphr_dnt_license_data', array() );
	if ( is_array( $legacy_licenses ) && count( $legacy_licenses ) > 0 ) {
		foreach ( $legacy_licenses as $slug => $legacy_license ) {
			if ( $updated_slug = ditty_updated_extension_slug( $slug ) ) {
				if ( ! isset( $licenses[$updated_slug] ) ) {
					if ( ! isset( $legacy_license['item_id'] ) ) {
						$legacy_license['item_id'] = ditty_updated_extension_id( $slug );
					}
					$licenses[$updated_slug] = $legacy_license;
				}
			}
		}
	}
	return $licenses;
}

/**
 * Return an array of Ditty extension licenses
 *
 * @since    3.0
*/
function ditty_extension_licenses() {
	$licenses = apply_filters( 'ditty_extension_licenses', array() );
	$licenses = ditty_extension_legacy_licenses( $licenses );
	return $licenses;
}

/**
 * Add in legacy licenses
 *
 * @since    3.0
*/
function ditty_legacy_extensions( $extensions ) {
	$legacy_extensions = apply_filters( 'mtphr_dnt_license_data', array() );
	if ( is_array( $legacy_extensions ) && count( $legacy_extensions ) > 0 ) {
		foreach ( $legacy_extensions as $slug => $legacy_extension ) {
			if ( $updated_slug = ditty_updated_extension_slug( $slug ) ) {
				if ( isset( $extensions[$updated_slug] ) ) {
					unset( $extensions[$updated_slug]['preview'] );
				}
			}
		}
	}
	return $extensions;
}

/**
 * Return an array of Ditty Extensions
 *
 * @since    3.0
*/
function ditty_extensions() {
	$extensions = array(
		'facebook' => array(
			'icon' 		=> 'fab fa-facebook',
			'name' 		=> __( 'Facebook', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-facebook-ticker/',
		),
		'facebook' => array(
			'icon' 		=> 'fab fa-facebook',
			'name' 		=> __( 'Facebook', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-facebook-ticker/',
		),
		'grid' => array(
			'icon' 		=> 'fas fa-th',
			'name' 		=> __( 'Grid Display', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-grid-display/',
		),
		'images' => array(
			'icon' 		=> 'fas fa-image',
			'name' 		=> __( 'Images', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-image-ticker/',
		),
		'instagram' => array(
			'icon' 		=> 'fab fa-instagram',
			'name' 		=> __( 'Instagram', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-instagram-ticker/',
		),
		'posts' => array(
			'icon' 		=> 'fab fa-wordpress',
			'name' 		=> __( 'Posts', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-posts-ticker/',
		),
		'rss' => array(
			'icon' 		=> 'fas fa-rss',
			'name' 		=> __( 'RSS', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-rss-ticker/',
		),
		'timing' => array(
			'icon' 		=> 'fas fa-clock',
			'name' 		=> __( 'Timing', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-timed-ticker/',
		),
		'twitter' => array(
			'icon' 		=> 'fab fa-twitter',
			'name' 		=> __( 'Twitter', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-twitter-ticker/',
		),
	);
	$extensions = apply_filters( 'ditty_extensions', $extensions );
	$extensions = ditty_legacy_extensions( $extensions );
	ksort( $extensions );
	
	// Set the live extensions to be first
	$live_extensions = array();
	$preview_extensions = array();
	if ( is_array( $extensions ) && count( $extensions ) > 0 ) {
		foreach ( $extensions as $slug => $data ) {
			if ( isset( $data['preview'] ) ) {
				$preview_extensions[$slug] = $data;
			} else {
				$live_extensions[$slug] = $data;
			}
		}
	}
	return $live_extensions + $preview_extensions;
}

/**
 * Return an updated legacy slug
 * @since    3.0
*/
function ditty_updated_extension_slug( $slug ) {	
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
		return $updated_slug;
	}
}

/**
 * Return an updated legacy id
 * @since    3.0
*/
function ditty_updated_extension_id( $slug ) {	
	$updated_id = false;
	switch ( $slug ) {
		case 'ditty-facebook-ticker':
			$updated_id = 1534;
			break;
		case 'ditty-image-ticker':
			$updated_id = 1548;
			break;
		case 'ditty-instagram-ticker':
			$updated_id = 2134;
			break;
		case 'ditty-mega-ticker':
			$updated_id = 1547;
			break;
		case 'ditty-posts-ticker':
			$updated_id = 1551;
			break;
		case 'ditty-rss-ticker':
			$updated_id = 1549;
			break;
		case 'ditty-timed-ticker':
			$updated_id = 12470;
			break;
		case 'ditty-twitter-ticker':
			$updated_id = 1550;
			break;
		default:
			break;
	}
	return $updated_id;
}

/**
 * Return all possible item type variations
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_variation_types( $item_type = false ) {
	if ( $item_type ) {
		if ( $item_type_object = ditty_item_type_object( $item_type ) ) {
			return $item_type_object->get_layout_variation_types();
		}
	} else {
		$item_types = ditty_item_types();
		$layout_variation_types = array();
		if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
			foreach ( $item_types as $item_type => $data ) {
				if ( $item_type_object = ditty_item_type_object( $item_type ) ) {
					$layout_variation_types[$item_type] = $item_type_object->get_layout_variation_types();
				}
			}
		}
		return $layout_variation_types;
	}
}

/**
 * Check if a layout type exists
 *
 * @since    3.0
 * @access   public
 * @var      bool
*/
function ditty_layout_posts( $atts = array() ) {
	$defaults = array(
		'template' 	=> false,
		'version'		=> false,
		'fields'		=> 'all',
		'return'		=> 'posts',
	);
	$args = shortcode_atts( $defaults, $atts );

	$query_args = array(
		'posts_per_page' 	=> -1,
		'post_type' 			=> 'ditty_layout',
		'post_status'			=> 'publish',
		'orderby'					=> 'title',
		'order'						=> 'ASC',
		'fields' 					=> esc_html( $args['fields'] ),
	);
	$meta_query = array();
	if ( $args['template'] ) {
		$meta_query['template'] = array(
			'key' 	=> '_ditty_layout_template',
			'value'	=> esc_html( $args['template'] ),
		);
	}
	if ( $args['version'] ) {
		$meta_query['version'] = array(
			'key' 	=> '_ditty_layout_version',
			'value'	=> esc_html( $args['version'] ),
		);
	}
	$query_args['meta_query'] = $meta_query;
	$layouts = get_posts( $query_args );
	
	if ( 'versions' == $args['return'] ) {
		$layout_versions = array();
		if ( is_array( $layouts ) && count( $layouts ) > 0 ) {
			foreach ( $layouts as $i => $layout_id ) {
				$version = get_post_meta( $layout_id, '_ditty_layout_version', true );
				$layout_versions[] = $version;
			}
		}
		if ( ! empty( $layout_versions ) ) {
			return $layout_versions;
		}
	} else {
		return $layouts;
	}
}

/**
 * Check if a display type exists
 *
 * @since    3.0.17
 * @access   public
 * @var      bool
*/
function ditty_display_posts( $atts = array() ) {
	$defaults = array(
		'display_type'	=> false,
		'template' 			=> false,
		'version'				=> false,
		'fields'				=> 'all',
		'return'				=> 'posts',
	);
	$args = shortcode_atts( $defaults, $atts );
		
	$query_args = array(
		'posts_per_page' 	=> -1,
		'post_type' 			=> 'ditty_display',
		'post_status'			=> 'publish',
		'fields' 					=> esc_html( $args['fields'] ),
	);
	$meta_query = array();
	if ( $args['display_type'] ) {
		$meta_query['type'] = array(
			'key' 	=> '_ditty_display_type',
			'value'	=> esc_html( $args['display_type'] ),
		);
	}
	if ( $args['template'] ) {
		$meta_query['template'] = array(
			'key' 	=> '_ditty_display_template',
			'value'	=> esc_html( $args['template'] ),
		);
	}
	if ( $args['version'] ) {
		$meta_query['version'] = array(
			'key' 	=> '_ditty_display_version',
			'value'	=> esc_html( $args['version'] ),
		);
	}
	$query_args['meta_query'] = $meta_query;
	$displays = get_posts( $query_args );
	
	if ( 'versions' == $args['return'] ) {
		$display_versions = array();
		if ( is_array( $displays ) && count( $displays ) > 0 ) {
			foreach ( $displays as $i => $display_id ) {
				$version = get_post_meta( $display_id, '_ditty_display_version', true );
				$display_versions[] = $version;
			}
		}
		if ( ! empty( $display_versions ) ) {
			return $display_versions;
		}
	} else {
		return $displays;
	}
}

/**
 * Check if a display exists by selector
 * @since    3.0
*/
function ditty_display_exists( $display_id ) {	
	if ( 'publish' == get_post_status( $display_id ) ) {
		return true;
	}
}

/**
 * Check if a display type exists
 * @since    3.1
*/
function ditty_display_type_exists( $type ) {	
	$display_types = ditty_display_types();
	return isset( $display_types[$type] );
}

/**
 * Setup general text strings
 *
 * @since    3.0
 * @access   public
 * @var      string $strings,     
*/
function ditty_admin_strings( $slug = false ) {	
	global $ditty_admin_strings;
	if ( empty( $ditty_admin_strings ) ) {			
		$strings = array(
			'extension_valid'								=> __( 'License is active.', 'ditty-news-ticker' ),
			'extension_generic_error' 			=> __( 'An error occurred, please try again.', 'ditty-news-ticker' ),
			'extension_remote_error' 				=> __( 'An error occurred, please try again.', 'ditty-news-ticker' ),
			'extension_expired' 						=> __( 'Your license key expired on %s.', 'ditty-news-ticker' ),
			'extension_disabled' 						=> __( 'Your license key has been disabled.', 'ditty-news-ticker' ),
			'extension_revoked' 						=> __( 'Your license key has been disabled.', 'ditty-news-ticker' ),
			'extension_missing' 						=> __( 'Invalid license.', 'ditty-news-ticker' ),
			'extension_invalid' 						=> __( 'Your license is not active for this URL.', 'ditty-news-ticker' ),
			'extension_site_inactive' 			=> __( 'Your license is not active for this URL.', 'ditty-news-ticker' ),
			'extension_item_name_mismatch' 	=> __( 'This appears to be an invalid license key.', 'ditty-news-ticker' ),
			'extension_no_activations_left' => __( 'Your license key has reached its activation limit.', 'ditty-news-ticker' ),
			'extension_deactivated'					=> __( 'License is deactivated.', 'ditty-news-ticker' ),
			'extension_failed'							=> __( 'Update failed.', 'ditty-news-ticker' ),
			'settings_save'									=> __( 'Save Settings', 'ditty-news-ticker' ),
			'settings_saving'								=> __( 'Saving...', 'ditty-news-ticker' ),
			'settings_changed'							=> __( 'Settings have changed, make sure to save them!', 'ditty-news-ticker' ),
			'settings_updated'							=> __( 'Settings Updated!', 'ditty-news-ticker' ),
			'settings_error'								=> __( 'Error Updating!', 'ditty-news-ticker' ),
		);		
		$ditty_admin_strings = apply_filters( 'ditty_admin_strings', $strings );
	}	
	if ( $slug ) {
		if ( isset( $ditty_admin_strings[$slug] ) ) {
			return $ditty_admin_strings[$slug];
		}
	} else {
		return $ditty_admin_strings;
	}
}

/**
 * Setup general text strings
 *
 * @since    3.0
 * @access   public
 * @var      string $strings,     
*/
function ditty_strings( $slug = false ) {	
	global $ditty_strings;
	if ( empty( $ditty_strings ) ) {			
		$strings = array(
			'add_title'								=> __( 'Add title', 'ditty-news-ticker' ),
			'confirm_delete_item'			=> __( 'Are you sure you want to delete this Item? This action cannot be undone.', 'ditty-news-ticker' ),
			'confirm_delete_display'	=> __( 'Are you sure you want to delete this Display?', 'ditty-news-ticker' ),
			'confirm_delete_layout'		=> __( 'Are you sure you want to delete this Layout?', 'ditty-news-ticker' ),
			'layout_css_error'				=> __( 'There is an error in your css.<br/>Click to close this message.', 'ditty-news-ticker' ),
		);		
		$ditty_strings = apply_filters( 'ditty_strings', $strings );
	}	
	if ( $slug ) {
		if ( isset( $ditty_strings[$slug] ) ) {
			return $ditty_strings[$slug];
		}
	} else {
		return $ditty_strings;
	}
}

/**
 * Return an array of item types with just the type and labels
 *
 * @since    3.0
 * @access   public
 * @var      array $ditty_types_simple    
*/
function ditty_types_simple() {
	
	$item_types = ditty_item_types();
	$item_types_simple = array();
	
	if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
		foreach ( $item_types as $i => $type ) {
			$item_types_simple[ $ditty_type['type'] ] = $type['label'];
		}
	}
	
	return $item_types_simple;
}


/**
 * Check if a type exists
 *
 * @since    3.0
 * @access   public
 * @var      bool
*/
function ditty_type_exists( $slug ) {
	$item_types = ditty_item_types();
	return array_key_exists( $slug, $item_types );
}

/**
 * Parse custom layouts into an array
 *
 * @since    3.0.10
 * @access   public
 * @var      bool
*/
function ditty_parse_custom_layouts( $layout_settings ) {
	$layouts = array();
	if ( ctype_digit( $layout_settings ) ) {
		$variation_array = array(
			'default' => $layout_settings,
		);
		$layouts['all'] = $variation_array;
	} else {
		parse_str( html_entity_decode( $layout_settings ), $custom_layout_settings );
		if ( is_array( $custom_layout_settings ) && count( $custom_layout_settings ) > 0 ) {
			foreach ( $custom_layout_settings as $item_type => $variations ) {
				$variation = explode( '|', $variations );
				if ( is_array( $variation ) && count( $variation ) > 0 ) {
					$variation_array = array();
					foreach ( $variation as $variation_data ) {
						$varation_values = explode( ':', $variation_data );
						if ( count( $varation_values ) > 1 ) {
							$variation_array[$varation_values[0]] = $varation_values[1];
						}
					}
					$layouts[$item_type] = $variation_array;
				}
			}
		}
	}
	return $layouts;
}

/**
 * Return item data for a Ditty
 *
 * @since    3.1
 * @access   public
 * @var      array    $items_meta    Array of items connected to a Ditty
 */
function ditty_items_meta( $ditty_id = false ) {	
	$ditty_id = $ditty_id ? $ditty_id : get_the_id();
	global $ditty_items_meta;
	
	if ( empty( $ditty_items_meta ) ) {
		$ditty_items_meta = array();
	}	
	if ( ! isset( $ditty_items_meta[$ditty_id] ) ) {
		$normalized_meta = array();
		$all_meta = Ditty()->db_items->get_items( $ditty_id );
		if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
			foreach ( $all_meta as $i => $meta ) {
				$meta->item_value = $meta->item_value ? ditty_to_array( $meta->item_value ) : false;
				$meta->layout_value = $meta->layout_value ? ditty_to_array( $meta->layout_value ) : false;
				$meta->attribute_value = $meta->attribute_value ? ditty_to_array( $meta->attribute_value ) : false;
				unset( $meta->layout_id ); // TODO: Maybe remove?
				$normalized_meta[] = apply_filters( 'ditty_item_meta', $meta, $meta->item_id, $ditty_id );
			} 
		}
		$ditty_items_meta[$ditty_id] = apply_filters( 'ditty_items_meta', $normalized_meta, $ditty_id );
	}
	return $ditty_items_meta[$ditty_id];
}

/**
 * Return item data by id
 *
 * @since    3.0
 * @access   public
 * @var      array    $meta    Array of items connected to a Ditty
 */
function ditty_item_meta( $item_id ) {	
	$meta = Ditty()->db_items->get( $item_id );
	$ditty_id = ( isset( $meta->ditty_id ) ) ? $meta->ditty_id : 0;
	$value = ditty_to_array( $meta->item_value );
	$meta->item_value = $value;
	return apply_filters( 'ditty_item_meta', $meta, $item_id, $ditty_id );
}

/**
 * Return the default layout
 *
 * @since    3.0.13
 * @access   public
 * @var      int    $layout_id
 */
function ditty_get_default_layout() {	
	$variation_defaults = get_ditty_settings( 'variation_defaults' );
	$layout_id = ( isset( $variation_defaults['default'] ) && isset( $variation_defaults['default']['default'] ) ) ? $variation_defaults['default']['default'] : 0;
	if ( ! $layout_id || 0 == $layout_id ) {
		$atts = array(
			'template' 	=> 'default',
			'fields'		=> 'ids',
		);
		if ( $layouts = ditty_layout_posts( $atts ) ) {
			return reset( $layouts );
		}
	}
	return $layout_id;
}

/**
 * Return an array of new item meta
 *
 * @since    3.0.13
 * @access   public
 * @var      array    $item_meta    Array of item data
 */
function ditty_get_new_item_meta( $ditty_id ) {	
	$item_type_object = ditty_item_type_object( 'default' );
	$item_value = $item_type_object->default_settings();
	$meta = array(
		'item_id' 			=> uniqid( 'new-' ),
		'item_type' 		=> 'default',
		'item_value' 		=> $item_value,
		'item_author'		=> get_current_user_id(),
		'ditty_id' 			=> $ditty_id,
		'layout_value' 	=> array( 'default' => ditty_get_default_layout() ),
	);
	return apply_filters( 'ditty_editor_new_item_meta', $meta, $ditty_id );
}

/**
 * Check if you're on a display post
 *
 * @since    3.0
 * @var      bool    
*/
function ditty_is_display_post() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}
	
	$screen = get_current_screen();
	if( is_object( $screen ) && 'ditty_display' == $screen->post_type && 'post' == $screen->base ) {
		return true;
	}
}

/**
 * Check if you're on a layout post
 *
 * @since    3.0
 * @var      bool    
*/
function ditty_is_layout_post() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}
	
	$screen = get_current_screen();
	if( is_object( $screen ) && 'ditty_layout' == $screen->post_type && 'post' == $screen->base ) {
		return true;
	}
}


/**
 * Check if you're on a Ditty post
 *
 * @since    3.0
 * @var      bool    
*/
function is_ditty_post() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return false;
	}
	$screen = get_current_screen();
	if( is_object( $screen ) && 'ditty' == $screen->post_type && 'post' == $screen->base ) {
		return true;
	}
}

/**
 * Check if a Ditty post exists
 *
 * @since    3.0
 * @var      bool
*/
function ditty_exists( $id ) {
	return is_string( get_post_status( $id ) );
}

/**
 * Replace template tags
 *
 * @since    3.0
 * @var      string
*/
// function ditty_replace_html_tags( $tags, $value, $string ) {	
// 	$content = preg_replace_callback( "/{([A-z0-9\-\_]+)}/s", function ( $matches ) use( $tags, $value ) {
// 			
// 			// Get tag
// 			$tag = $matches[1];
// 			
// 			// Return tag if tag not set
// 			if ( ! array_key_exists( $tag, $tags ) ) {
// 				return $matches[0];
// 			}
// 			
// 			return call_user_func( $tags[$tag]['func'], $value );
// 			
//   	}, $string
// 	);
// 	
// 	// Remove multiple white-spaces, tabs and new-lines
// 	$pattern = '/\s+/S';
// 	$content = preg_replace( $pattern, ' ', $content );
// 	
// 	// Strip out whitespace
// 	//$content = preg_replace( "/[\r\n]+/", "", $content );
// 
// 	return $content;
// }

/**
 * Return an array of easing options
 *
 * @since    3.0
 * @var      $eases array
*/
function ditty_ease_array() {
	$eases = array(
		'linear','swing','jswing','easeInQuad','easeInCubic','easeInQuart','easeInQuint','easeInSine','easeInExpo','easeInCirc','easeInElastic','easeInBack','easeInBounce','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc','easeOutElastic','easeOutBack','easeOutBounce','easeInOutQuad','easeInOutCubic','easeInOutQuart','easeInOutQuint','easeInOutSine','easeInOutExpo','easeInOutCirc','easeInOutElastic','easeInOutBack','easeInOutBounce'
	);
	return array_combine( $eases, $eases );
}

/**
 * Return an array of slider transitions
 *
 * @since    3.0
 * @var      $eases array
*/
function ditty_slider_transitions() {
	$transitions = array(
		'fade' 				=> __( 'Fade', 'ditty-news-ticker' ),
		'slideLeft' 	=> __( 'Slide Left', 'ditty-news-ticker' ),
		'slideRight' 	=> __( 'Slide Right', 'ditty-news-ticker' ),
		'slideDown' 	=> __( 'Slide Down', 'ditty-news-ticker' ),
		'slideUp' 		=> __( 'Slide Up', 'ditty-news-ticker' ),
	);
	return $transitions;
}

/**
 * Return an array of border style options
 *
 * @since    3.0
 * @var      $eases array
*/
function ditty_border_styles_array() {
	$styles = array(
		'none', 'dotted','dashed','solid','double','groove','ridge','inset','outset','hidden'
	);
	return array_combine( $styles, array_map( 'ucfirst', $styles ) );
}

/**
 * Prepare display items
 *
 * @since    3.0.21
 * @var      $eases array
*/
function ditty_prepare_display_items( $item ) {
	if ( is_object( $item ) ) {
		$item = ( array ) $item;
	}
	$prepared_items = array();
	if ( ! $item_type_object 	= ditty_item_type_object( $item['item_type'] ) ) {
		return $prepared_items;
	}
	$defaults 						= $item_type_object->default_settings();
	$args 								= wp_parse_args( $item['item_value'], $defaults );
	$item['item_value'] 	= $args;
	$item['custom_meta'] 	= apply_filters( 'ditty_display_item_custom_meta', [], $item, $item_type_object );
	return $item_type_object->prepare_items( $item );
}

/**
 * Render the Ditty container
 *
 * @since    3.1.18
 */
function ditty_render( $atts ) {
	global $ditty_singles;
	if ( empty( $ditty_singles ) ) {
		$ditty_singles = array();
	}

	$defaults = array(
		'id' 								=> '',
		'display' 					=> '',
		'display_settings' 	=> '',
		'layout' 						=> '',
		'uniqid' 						=> '',
		'class' 						=> '',
		'el_id'							=> '',
		'show_editor' 			=> 0,
		'ajax_loading'			=> '',
		'live_updates'			=> '',
	);
	$args = shortcode_atts( $defaults, $atts );

	// Check for WPML language posts
	//$args['id'] = function_exists('icl_object_id') ? icl_object_id( $args['id'], 'ditty', true ) : $args['id'];

	// Make sure the ditty exists & is published
	if ( ! ditty_exists( intval( $args['id'] ) ) ) {
		return false;
	}
	if ( ! is_admin() && 'publish' !== get_post_status( intval( $args['id'] ) ) ) {
		return false;
	}

	if ( '' == $args['uniqid'] ) {
		$args['uniqid'] = uniqid( 'ditty-' );
	}

	$class = 'ditty ditty--pre';
	if ( '' != $args['class'] ) {
		$class .= ' ' . esc_attr( $args['class'] );
	}
	
	$ditty_settings = get_post_meta( $args['id'], '_ditty_settings', true );
	$ajax_load 			= ( isset( $ditty_settings['ajax_loading'] ) && 'yes' == $ditty_settings['ajax_loading'] ) ? '1' : false;
	if ( 'yes' == $args['ajax_loading'] || 'no' == $args['ajax_loading'] ) {
		$ajax_load = ( 'yes' == $args['ajax_loading'] ) ? '1' : false;
	}

	$live_updates 	= ( isset( $ditty_settings['live_updates'] ) && 'yes' == $ditty_settings['live_updates'] ) ? '1' : false;
	if ( 'yes' == $args['live_updates'] || 'no' == $args['live_updates'] ) {
		$live_updates = ( 'yes' == $args['live_updates'] ) ? '1' : false;
	}
  
  // Possibly load custom display type
  $force_display_type = false;
  if ( isset( $args['display_settings'] ) ) {
    $custom_display_array = json_decode( $args['display_settings'], true );
    if ( json_last_error() == JSON_ERROR_NONE ) {
      if ( isset( $custom_display_array['type'] ) && ditty_display_type_exists( $custom_display_array['type'] ) ) {
        $force_display_type = $custom_display_array['type'];
      }
    }
  }
	ditty_add_scripts( $args['id'], $args['display'], $force_display_type );
	
	$ditty_atts = array(
		'id'										=> ( '' != $args['el_id'] ) ? sanitize_title( $args['el_id'] ) : false,
		'class' 								=> $class,
		'data-id' 							=> $args['id'],
		'data-uniqid' 					=> $args['uniqid'],
		'data-display' 					=> ( '' != $args['display'] ) ? $args['display'] : false,
		'data-display_settings' => ( '' != $args['display_settings'] ) ? htmlspecialchars( $args['display_settings'], ENT_QUOTES, 'UTF-8' ) : false,
		'data-layout_settings' 	=> ( '' != $args['layout'] ) ? $args['layout'] : false,
		'data-show_editor' 			=> ( 0 != intval( $args['show_editor'] ) ) ? '1' : false,
		'data-ajax_load' 				=> $ajax_load,
		'data-live_updates' 		=> $live_updates,
	);

	if ( 0 == $ajax_load ) {
		$ditty_singles[] = $ditty_atts;
	}

	$html = '<div ' . ditty_attr_to_html( $ditty_atts ) . '>';
		$html .= ditty_edit_links( $args['id'] );
	$html .= '</div>';
	return $html;
}

/**
 * Parse ditty script types and add to global
 *
 * @since    3.1
 */
function ditty_add_scripts( $ditty_id, $display = false, $display_type = false ) {
	global $ditty_item_scripts;
	if ( empty( $ditty_item_scripts ) ) {
		$ditty_item_scripts = array();
	}
	global $ditty_display_scripts;
	if ( empty( $ditty_display_scripts ) ) {
		$ditty_display_scripts = array();
	}
  // global $ditty_google_fonts;
	// if ( empty( $ditty_google_fonts ) ) {
	// 	$ditty_google_fonts = array();
	// }
	
	// Store the item types
	$items = Ditty()->db_items->get_items( $ditty_id );
	if ( is_array( $items ) && count( $items ) > 0 ) {
		foreach ( $items as $i => $item ) {
			if ( $item_type_object = ditty_item_type_object( $item->item_type ) ) {
				if ( $script_id = $item_type_object->get_script_id() ) {
					$ditty_item_scripts[$script_id] = $script_id;
				}
			}
		}
	}

  // Find the display type
  if ( ! $display_type ) {
    if ( ! $display ) {
      $display = get_post_meta( $ditty_id, '_ditty_display', true );
    }
    $display_settings = false;
    $display_type = false;
    if ( is_array( $display ) ) {
      //$display_settings = isset( $display['settings'] ) ? $display['settings'] : [];
      $display_type = isset( $display['type'] ) ? $display['type'] : $display_type;
    } else {
      if ( 'publish' == get_post_status( $display ) ) {
        //$display_settings = get_post_meta( $display, '_ditty_display_settings', true );
        $display_type = get_post_meta( $display, '_ditty_display_type', true );  
      }
    }
  }
  
	$ditty_display_scripts[$display_type] = $display_type;

  // Add google fonts 
  // if ( ( $display_settings['itemTypography']['fontType'] ?? '') === 'google' ) {
  //   $ditty_google_fonts[$display_settings['itemTypography']['fontFamily']] = $display_settings['itemTypography'];
  // }
  // if ( ( $display_settings['titleTypography']['fontType'] ?? '') === 'google' ) {
  //   $ditty_google_fonts[$display_settings['titleTypography']['fontFamily']] = $display_settings['titleTypography'];
  // }
}

/**
 * Return formatted content
 *
 * @since    3.0
 */
function ditty_formatted_content( $content, $args=array() ) {
	$defaults = array(
		'wp_kses_post' 	=> true,
		'stripslashes' 	=> true,
		'wptexturize' 	=> true,
		'convert_chars' => true,
		'wpautop'				=> true,
		'do_shortcode'	=> true,
	);
	$atts = wp_parse_args( $args, $defaults );
	
	if ( $atts['wp_kses_post'] ) {
		$content = wp_kses_post( $content );
	}
	if ( $atts['stripslashes'] ) {
		$content = stripslashes( $content );
	}
	if ( $atts['wptexturize'] ) {
		$content = wptexturize( $content );
	}
	if ( $atts['convert_chars'] ) {
		$content = convert_chars( $content );
	}
	if ( $atts['wpautop'] ) {
		$content = wpautop( $content );
	}
	if ( $atts['do_shortcode'] ) {
		$content = do_shortcode( $content );
	}
	return $content;
}

/**
 * Check if a Ditty license is active
 *
 * @since    3.0
 * @access   public
 * @var      bool
*/
function ditty_get_license_status( $extension ) {
	return Ditty()->extensions->get_license_status( $extension );
}

/**
 * Convert an array to element attributes
 *
 * @since    3.0
 * @access   public
 * @var      bool
*/
function ditty_attr_to_html( $attr = array() ) {
	$html_array = array();
	if ( is_array( $attr ) && count( $attr ) > 0 ) {
		foreach ( $attr as $name => $value ) {
			if ( false === $value ) {
				continue;
			}
			$html_array[] = $name . '="' . esc_attr( $value ) . '"';
		}
	}
	if ( ! empty( $html_array ) ) {
		return implode( ' ', $html_array );
	}
}

/**
 * Get global Ditty
 *
 * @since    3.0
 * @var      array	$parsed_atts
*/
function ditty_get_globals() {
	$prepared_ditty = array();
	$global_ditty = get_ditty_settings( 'global_ditty' );
	if ( is_array( $global_ditty ) && count( $global_ditty ) > 0 ) {
		foreach ( $global_ditty as $i => &$ditty ) {
			$ditty_settings = get_post_meta( $ditty['ditty'], '_ditty_settings', true );
			$ditty['selector'] = html_entity_decode( $ditty['selector'] );
			$ditty['live_updates'] = ( isset( $ditty_settings['live_updates'] ) && 'yes' == $ditty_settings['live_updates'] ) ? '1' : 0;		
			if ( $edit_link = ditty_edit_links( $ditty['ditty'] ) ) {
				$ditty['edit_links'] = html_entity_decode( $edit_link );
			}
			$prepared_ditty[] = $ditty;
		}
	}
	return $prepared_ditty;
}

/**
 * Convert an array to element attributes
 *
 * @since    3.0
 * @var      string
*/
function ditty_help_icon( $str = '' ) {
	if ( '' === $str ) {
		return false;
	}
	return '<i class="ditty-help-icon protip fas fa-question-circle" data-pt-title="' . $str . '"></i>';
}

/**
 * Encrypt values
 *
 * @since    3.0
 * @var      string $output
*/
function ditty_encrypt( $string = '', $key_1 = 'pbQttfc*y2bdNV', $key_2 = '3tq!D6AK@XpVz4' ) {
	$key = hash( 'sha256', $key_1 );
	$iv = substr( hash( 'sha256', $key_2 ), 0, 16 );
	$output = base64_encode( openssl_encrypt( $string, "AES-256-CBC", $key, 0, $iv ) );
	return $output;
}

/**
 * Decrypt values
 *
 * @since    3.0
 * @var      string $output
*/
function ditty_decrypt( $string = '', $key_1 = 'pbQttfc*y2bdNV', $key_2 = '3tq!D6AK@XpVz4' ) {
	$key = hash( 'sha256', $key_1 );
	$iv = substr( hash( 'sha256', $key_2 ), 0, 16 );
	$output = openssl_decrypt( base64_decode( $string ), "AES-256-CBC", $key, 0, $iv );
	return $output;
}

/**
 * Add a uniq_id to a post if it doesn't exist
 *
 * @since    3.0.17
 * @var      boolean
*/
function ditty_maybe_add_uniq_id( $post_id ) {
	$uniq_id = get_post_meta( $post_id, '_ditty_uniq_id', true );
	if ( ! $uniq_id ) {
		$uniq_id = $post_id . current_time( 'timestamp', true );
		update_post_meta( $post_id, '_ditty_uniq_id', $uniq_id );
	}
	return $uniq_id;
}

/**
 * Check if Ditty News Ticker is enabled
 *
 * @since    3.1.6
 * @var      boolean
*/
function ditty_news_ticker_enabled() {
	if ( 'enabled' == get_ditty_settings( 'ditty_news_ticker' ) ) {
		return true;
	}
}

/**
 * Check if Font Awesome is disabled
 *
 * @since    3.1.6
 * @var      boolean
*/
function ditty_fontawesome_enabled() {
	if ( 'enabled' == get_ditty_settings( 'disable_fontawesome' ) ) {
		return true;
	}
}

/**
 * Write to the Ditty log
 *
 * @since    3.0.13
*/
function ditty_log( $log = false ) {
	if ( $log ) {
		Ditty()->write_log( $log );
	}
}

/**
 * Retrieve meta field for a item.
 *
 * @since   3.0
 */
function ditty_item_get_meta( $item_id, $meta_key = '', $single = true ) {
	return Ditty()->db_item_meta->get_meta( $item_id, $meta_key, $single );
}

/**
 * Add meta data field to a item.
 *
 * @since   3.0.16
 */
function ditty_item_add_meta( $item_id, $meta_key = '', $meta_value = false, $unique = false ) {
	if ( ! $meta_value ) {
		return false;
	}
	return Ditty()->db_item_meta->add_meta( $item_id, $meta_key, $meta_value, $unique );
}

/**
 * Update item meta field based on item ID.
 *
 * @since   3.0.16
 */
function ditty_item_update_meta( $item_id, $meta_key = '', $meta_value = false, $prev_value = '' ) {
	if ( ! $meta_value ) {
		return false;
	}
	return Ditty()->db_item_meta->update_meta( $item_id, $meta_key, $meta_value, $prev_value );
}

/**
 * Remove metadata matching criteria from an item.
 *
 * @since   3.0
 */
function ditty_item_delete_meta( $item_id, $meta_key = '', $meta_value = '' ) {
	return Ditty()->db_item_meta->delete_meta( $item_id, $meta_key, $meta_value );
}

/**
 * Get all item meta for a specified item.
 *
 * @since   3.1
 */
function ditty_item_custom_meta( $item_id ) {
	$meta = Ditty()->db_item_meta->custom_meta( $item_id );
	$mapped_meta = [];
	if ( is_array( $meta ) && count( $meta ) > 0 ) {
		foreach ( $meta as $data ) {
			$mapped_meta[$data->meta_key] = ditty_to_array( $data->meta_value );
		}
	}
	return $mapped_meta;
}

/**
 * Get all metadatafrom an item.
 *
 * @since   3.0.17
 */
function ditty_item_get_all_meta( $item_id ) {
	return Ditty()->db_item_meta->custom_meta( $item_id );
}

/**
 * Remove all metadatafrom an item.
 *
 * @since   3.0.17
 */
function ditty_item_delete_all_meta( $item_id ) {
	$meta = ditty_item_get_all_meta( $item_id );
	if ( is_array( $meta ) && count( $meta ) > 0 ) {
		foreach ( $meta as $data ) {
			if ( isset( $data->meta_key ) ) {
				Ditty()->db_item_meta->delete_meta( $item_id, $data->meta_key );
			}			
		}
	}
}

/**
 * Return the Ditty logo svg
 *
 * @since   3.0
 */
function ditty_svg_logo() {
	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 258.8 99.21" fill="currentColor"><path d="M0,49.5C0,32.3,8.6,20.4,24.6,20.4a19.93,19.93,0,0,1,6.6,1V3.1H45V62.3l1,10.3H34.2l-.9-5.2h-.5a15.21,15.21,0,0,1-13,6.8C3.8,74.2,0,61.5,0,49.5Zm31.2,7.4V31.7a13.7,13.7,0,0,0-6-1.3c-8.7,0-11.3,8.7-11.3,17.8,0,8.5,1.9,15.8,8.9,15.8C27.9,64,31.2,60.2,31.2,56.9Z"/><path d="M55.7,7.4A7.33,7.33,0,0,1,63.4,0c4.6,0,7.8,3.3,7.8,7.4s-3.2,7.4-7.8,7.4S55.7,11.7,55.7,7.4ZM70.5,21.9V72.6H56.4V21.9Z"/><path d="M95.8,3.1V21.9H112V3.1h14.1V21.9h13V32.8h-13V55.9c0,5.9,2.6,7.6,6.4,7.6a11.9,11.9,0,0,0,6.1-1.9l3.2,9c-3,2-8.2,3.5-13.3,3.5-15.2,0-16.5-8.7-16.5-17.8V32.8H95.8V55.9c0,5.9,2,7.6,5.7,7.6a11.64,11.64,0,0,0,5.7-1.6l2.1,9.4c-2.6,1.7-7.4,2.8-11.1,2.8-15.1,0-16.4-8.7-16.4-17.8V3.1Z"/><path d="M149.6,85.81c0-7.21,4.4-12.81,10.3-17.11-8.4-1.3-13-5.9-13-16V21.9h14V51.6c0,5.4.5,9.1,7,9.1,4,0,7.7-3.2,7.7-8.3V21.9h14V64.2a108.13,108.13,0,0,1-.9,13.9c-1.5,13.5-8.9,21.11-22.4,21.11C155.2,99.21,149.6,94,149.6,85.81Zm26.3-9.11V67.2c-7.4,3.5-14,8.5-14,16.11,0,3.9,2.2,5.79,6,5.79C173.8,89.1,175.9,84.4,175.9,76.7Z"/><path d="M198.7,66.8a7,7,0,0,1,7.4-7.2c5,0,7.7,2.8,7.7,7.1s-2.6,7.5-7.4,7.5C201.3,74.2,198.7,71.1,198.7,66.8Z"/><path d="M221.2,66.8a7,7,0,0,1,7.4-7.2c5,0,7.7,2.8,7.7,7.1s-2.6,7.5-7.4,7.5C223.8,74.2,221.2,71.1,221.2,66.8Z"/><path d="M243.7,66.8a7,7,0,0,1,7.4-7.2c5,0,7.7,2.8,7.7,7.1s-2.6,7.5-7.4,7.5C246.3,74.2,243.7,71.1,243.7,66.8Z"/></svg>';
}

/**
 * Return the Ditty d svg
 *
 * @since   3.0
 */
function ditty_svg_d() {
	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 69.31 71.1" fill="currentColor"><path d="M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM61.91 65.6a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7ZM61.91 43.1a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7ZM61.91 20.6a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7Z"/></svg>';
}

/**
 * Check if we are previewing a ditty
 *
 * @since   3.1
 */
function is_ditty_preview() {
	if ( isset( $_GET['ditty_edit'] ) && isset( $_GET['ditty_edit_id'] ) ) {
		return $_GET['ditty_edit_id'];
	}
}

/**
 * Check if we are on a ditty edit page
 *
 * @since   3.1
 */
function ditty_editing() {
	$page = isset( $_GET['page'] ) ? $_GET['page'] : false;
	$id = isset( $_GET['id'] ) ? $_GET['id'] : false;
	if ( 'ditty' == $page && $id ) {
		return $id;
	} elseif( 'ditty-new' == $page ) {
		return 'ditty-new';
	}
}

/**
 * Check if we are on a display edit page
 *
 * @since   3.1
 */
function ditty_display_editing() {
	$page = isset( $_GET['page'] ) ? $_GET['page'] : false;
	$id = isset( $_GET['id'] ) ? $_GET['id'] : false;
	if ( 'ditty_display' == $page && $id ) {
		return $id;
	} elseif( 'ditty_display-new' == $page ) {
		return 'ditty_display-new';
	}
}

/**
 * Check if we are on a layout edit page
 *
 * @since   3.1
 */
function ditty_layout_editing() {
	$page = isset( $_GET['page'] ) ? $_GET['page'] : false;
	$id = isset( $_GET['id'] ) ? $_GET['id'] : false;
	if ( 'ditty_layout' == $page && $id ) {
		return $id;
	} elseif( 'ditty_layout-new' == $page ) {
		return 'ditty_layout-new';
	}
}

/**
 * Check if we are on a ditty edit page
 *
 * @since   3.0.32
 */
function ditty_edit_links( $ditty_id ) {
	if ( ! is_admin() && current_user_can( 'edit_ditty', $ditty_id ) && 'enabled' === get_ditty_settings( 'edit_links' ) ) {
		$html = '<div class="ditty__edit-links">';
      $html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 69.31 71.1" fill="currentColor"><path d="M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM61.91 65.6a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7ZM61.91 43.1a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7ZM61.91 20.6a7 7 0 0 1-7.2-7.4c0-5 2.8-7.7 7.1-7.7s7.5 2.6 7.5 7.4c0 5.1-3.1 7.7-7.4 7.7Z"/></svg>';
			$html .= '<a href="' . esc_url( get_edit_post_link( $ditty_id ) ) . '">' . __('Edit Ditty', 'ditty-news-ticker') . '</a>';
			$display = get_post_meta( $ditty_id, '_ditty_display', true );
			if ( ! is_array( $display ) && $edit_link = get_edit_post_link( $display ) ) {
				$html .= '<a href="' . esc_url( $edit_link ) . '">' . __('Edit Display', 'ditty-news-ticker') . '</a>';
			}
		$html .= '</div>';
		return $html;
	}
}

/**
 * Return the current Ditty version
 *
 * @since   3.1
 */
function ditty_version() {
	return DITTY_VERSION;
}

/**
 * Return the default Ditty display type
 *
 * @since   3.1
 */
function ditty_default_display_type() {
	return apply_filters( 'ditty_default_display_type', 'list' );
}

/**
 * Return display data
 *
 * @since   3.1.19
 */
function ditty_display_data( $display ) {
  $display_id = 'custom';
	$display_type = ditty_default_display_type();
  $display_settings = [];
  if ( is_array( $display ) ) {
    $display_type = isset( $display['type'] ) ? $display['type'] : $display_type;
    $display_settings = isset( $display['settings'] ) ? $display['settings'] : [];
  } else {
    if ( 'publish' == get_post_status( $display ) ) {
      $display_id = $display;
      $display_type = get_post_meta( $display, '_ditty_display_type', true );
      $display_settings = get_post_meta( $display, '_ditty_display_settings', true );
    }
  }
  if ( ! ditty_display_type_exists( $display_type ) ) {
    $display_type = ditty_default_display_type();
    $display_type_object = ditty_display_type_object( $display_type );
    $display_settings = $display_type_object->default_settings();
  }

  return [
    'id'       => $display_id,
    'type'     => $display_type,
    'settings' => $display_settings,
  ];
}

/**
 * Return the default Ditty display type
 *
 * @since   3.1.6
 */
function ditty_default_item_type() {
	return apply_filters( 'ditty_default_item_type', 'default' );
}

/**
 * Register Ditty styles
 *
 * @since   3.1
 */
function ditty_register_style( $type, $args ) {
	Ditty()->scripts->register_style( $type, $args );
}

/**
 * Register Ditty scripts
 *
 * @since   3.1
 */
function ditty_register_script( $type, $args ) {
	Ditty()->scripts->register_script( $type, $args );
}

/**
 * Sanitize settings
 * *
 * @since   3.1.45
 */
function ditty_sanitize_setting( $value ) {
	if ( is_array( $value ) ) {
		return ditty_sanitize_settings( $value );
	} else {
		return wp_kses_post( $value );
	}
}

/**
 * Sanitize settings
 * *
 * @since   3.1
 */
function ditty_sanitize_settings( $values, $filter = false ) {
	if ( is_array( $values ) ) {
		$sanitized_values = [];
		if ( count( $values ) > 0 ) {
			foreach ( $values as $key => $value ) {
				$sanitized_values[$key] = ditty_sanitize_setting( $value );
			}
		}
	} else {
		$sanitized_values = ditty_sanitize_setting( $values );
	}
	return $filter ? apply_filters( 'ditty_sanitize_settings', $sanitized_values, $values, $filter ) : $sanitized_values;
}

/**
 * Sanitize settings
 * *
 * @since   3.1.18
 */
function ditty_get_image_dimensions( $image_url ) {
	$response = wp_remote_get($image_url);
	$image_data = wp_remote_retrieve_body($response);
	$temp_image = tmpfile();
	fwrite($temp_image, $image_data);
	$temp_image_path = stream_get_meta_data($temp_image)['uri'];
	if ( $image_info = @getimagesize($temp_image_path) ) {
    return [
      'width' => $image_info[0],
      'height' => $image_info[1],
    ];
  }	
}

/**
 * Redirect Ditty post type edit screens
 * *
 * @since   3.1.19
 */
function ditty_edit_post_type_redirects( $ditty_post_type ) {
	$action = isset( $_GET['action'] ) ? $_GET['action'] : false;
  if ( ! is_admin() || 'trash' == $action || 'delete' == $action ) {
    return false;
  }
  global $pagenow;
  if ( $pagenow === 'post.php' ) {
    $post_id = isset( $_GET['post'] ) ? $_GET['post'] : 0;
    if ( $ditty_post_type == get_post_type( $post_id ) ) {
      wp_safe_redirect( add_query_arg( ['page' => $ditty_post_type, 'id' => $post_id], admin_url( 'admin.php' ) ) );
      exit;
    }
  }
  if ( $pagenow === 'post-new.php' ) {
    $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : false;
    if ( $ditty_post_type == $post_type ) {
			$args = $_GET;
			$args['page'] = "{$ditty_post_type}-new";
			unset($args['post_type']);
      wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
      exit;
    }
  }

  // Redirect to new Ditty if trying to view non-existent post
  $page = isset( $_GET['page'] ) ? $_GET['page'] : false;
  $id = isset( $_GET['id'] ) ? $_GET['id'] : false;
  if ( $ditty_post_type == $page && ( ! get_post_status( $id ) || $ditty_post_type != get_post_type( $id ) ) ) {
    wp_safe_redirect( add_query_arg( ['page' => "{$ditty_post_type}-new" ], admin_url( 'admin.php' ) ) );
    exit;
  } 	
}

/**
 * Return variation defaults ensuring they exist
 * *
 * @since   3.1.15
 */
function ditty_get_variation_defaults() {
	$variation_defaults = get_ditty_settings( 'variation_defaults' );
	$sanitized_variation_defaults = [];
	if ( is_array( $variation_defaults ) && count( $variation_defaults ) > 0 ) {
		foreach ( $variation_defaults as $item_type => $defaults ) {
			$sanitized_defaults = [];
			if ( is_array( $defaults ) && count( $defaults ) > 0 ) {
				foreach ( $defaults as $variation => $layout_id ) {
					if ( ! $layout_id || 'publish' !== get_post_status( $layout_id ) ) {
						continue;
					}
					$sanitized_defaults[$variation] = $layout_id;
				}
			}
			$sanitized_variation_defaults[$item_type] = $sanitized_defaults;
		}
	}
	return $sanitized_variation_defaults;
}

/**
 * Return an array value and possibly unserialize
 */
function ditty_to_array( $value ) {
	if ( empty( $value ) ) {
		return [];
	} elseif ( is_array( $value ) ) {
		return $value;
	} elseif ( is_serialized( $value ) ) {
		$value = @unserialize(
			trim( $value ),
			array( 'allowed_classes' => false )
		);
		return is_array( $value ) ? $value : [];
	} elseif ( is_string( $value ) ) {
		$json_array = json_decode( $value, true );
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return $json_array;
		}
	}
	return [];
}

/**
 * Custom kses post to allow svgs
 */
function ditty_kses_post( $content ) {
  
  // Get the default allowed HTML tags from wp_kses_post
  $allowed_tags = wp_kses_allowed_html( 'post' );

  // Define the optimized SVG tags and attributes to allow
  $svg_tags = [
    'svg' => [
      'class'           => true,
      'aria-hidden'     => true,
      'aria-labelledby' => true,
      'role'            => true,
      'xmlns'           => true,
      'width'           => true,
      'height'          => true,
      'viewbox'         => true
    ],
    'g' => [
      'fill' => true
    ],
    'title' => [
      'title' => true
    ],
    'path' => [
      'd' => true, 
      'fill' => true  
    ]
  ];

  // Merge the SVG tags with the default allowed tags
  $allowed_tags = apply_filters( 'ditty_kses_post_allowed_tags', array_merge( $allowed_tags, $svg_tags ) );

  // Use wp_kses() with the extended allowed tags to filter the content
  return wp_kses( $content, $allowed_tags );
}
