<?php

/**
 * Return the settings defaults
 *
 * @since    3.0
*/
function ditty_settings_defaults() {	
	$defaults = array(
		'live_refresh'				=> 10,
		'default_display'			=> false,
		'ditty_display_ui'		=> 'disabled',
		'ditty_layout_ui'			=> 'disabled',
		'ditty_layouts_sass'	=> false,
		'global_ditty'				=> array(),
		'ditty_news_ticker' 	=> '',
		'notification_email' 	=> '',
	);
	return apply_filters( 'ditty_settings_defaults', $defaults );
}
	
/**
 * Return or set plugin settings
 *
 * @since    3.0
*/
function ditty_settings( $key=false, $value='' ) {
	global $ditty_settings;
	if ( empty( $ditty_settings ) ) {
		$ditty_settings = get_option( 'ditty_settings', array() );
	}
	if ( $key ) {
		if ( is_array( $key ) ) {
			foreach ( $key as $k => $v ) {
				$ditty_settings[$k] = $v;
			}
			update_option( 'ditty_settings', $ditty_settings );
		} else {
			if ( $value ) {
				$ditty_settings[$key] = $value;
				update_option( 'ditty_settings', $ditty_settings );
			}
		}
	}
	$ditty_settings = wp_parse_args( $ditty_settings, ditty_settings_defaults() );
	if ( $key && ! is_array( $key ) ) {
		if ( isset( $ditty_settings[$key] ) ) {
			return $ditty_settings[$key];
		}
	} else {
		return $ditty_settings;
	}
}

/**
 * Return an array of item types
 * 
 * @since   3.0
*/
function ditty_item_types() {
	$item_types = array();
	$item_types['default'] = array(
		'type' 				=> 'default',
		'label' 			=> __( 'Default', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-pencil-alt',
		'description' => __( 'Manually add HTML to the item.', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Item_Type_Default',
		//'class_path'	=> DITTY_DIR . 'includes/class-ditty-item-type-default.php',
	);
	$item_types['wp_editor'] = array(
		'type' 				=> 'wp_editor',
		'label' 			=> __( 'WP Editor', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-edit',
		'description' => __( 'Manually add wp editor content to the item.', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Item_Type_WP_Editor',
		//'class_path'	=> DITTY_DIR . 'includes/class-ditty-item-type-wp-editor.php',
	);
	return apply_filters( 'ditty_item_types', $item_types );
}

/**
 * Return a type class object
 *
 * @since    3.0
 * @var      object	$type_object    
*/
function ditty_item_type_object( $type ) {
	$item_types = ditty_item_types();
	if ( isset( $item_types[$type] ) && class_exists( $item_types[$type]['class_name'] ) ) {
		$type_object = new $item_types[$type]['class_name'];
		return $type_object;
	}
}

/**
 * Return an array of ditty displays
 * 
 * @since   3.0  
 */
function ditty_display_types() {
	$display_types = array();	
	$display_types['ticker'] = array(
		'label' 			=> __( 'Ticker', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-ellipsis-h',
		'description' => __( 'Basic news ticker display', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Display_Type_Ticker',
		//'class_path'	=> DITTY_DIR . 'includes/class-ditty-display-type-ticker.php',
	);
	$display_types['list'] = array(
		'label' 			=> __( 'List', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-list',
		'description' => __( 'Display items in a static list', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Display_Type_List',
		//'class_path'	=> DITTY_DIR . 'includes/class-ditty-display-type-list.php',
	);
	return apply_filters( 'ditty_display_types', $display_types );
}

/**
 * Return a display class object
 *
 * @since    3.0
 * @var      object	$display_object    
*/
function ditty_display_type_object( $type ) {
	$display_types = ditty_display_types();
	if ( isset( $display_types[$type] ) && class_exists( $display_types[$type]['class_name'] ) ) {
		$display_object = new $display_types[$type]['class_name'];
		return $display_object;
	}
}

/**
 * Return an array of ditty layouts
 * 
 * @since   3.0  
 */
function ditty_layout_types() {
	$layout_types = array();	
	$layout_types['default'] = array(
		'label' 			=> __( 'Default', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-pencil-alt',
		'description' => __( 'Display a default item', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Layout_Type_Default',
		//'class_path'	=> DITTY_DIR . 'includes/class-ditty-layout-type-default.php',
	);
	$layout_types['wp_editor'] = array(
		'label' 			=> __( 'WP Editor', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-edit',
		'description' => __( 'Display a WP Editor item', 'ditty-news-ticker' ),
		'class_name'	=> 'Ditty_Layout_Type_WP_Editor',
		//'class_path'	=> DITTY_DIR . 'includes/class-ditty-layout-type-wp-editor.php',
	);
	// $layout_types['wp_post'] = array(
	// 	'label' 			=> __( 'WP Post', 'ditty-news-ticker' ),
	// 	'icon' 				=> 'fab fa-wordpress-simple',
	// 	'description' => __( 'Display a single WordPress post', 'ditty-news-ticker' ),
	// 	'class_name'	=> 'Ditty_Layout_Type_WP_Post',
	// 	'class_path'	=> DITTY_DIR . 'includes/class-ditty-layout-type-wp-post.php',
	// );
	return apply_filters( 'ditty_layout_types', $layout_types );
}

/**
 * Return a layout class object
 *
 * @since    3.0
 * @var      object	$layout_object    
*/
function ditty_layout_type_object( $type ) {
	$layout_types = ditty_layout_types();
	if ( isset( $layout_types[$type] ) && class_exists( $layout_types[$type]['class_name'] ) ) {
		$layout_object = new $layout_types[$type]['class_name'];
		return $layout_object;
	}
}

/**
 * Return an array of Ditty Extensions
 *
 * @since    3.0
*/
function ditty_extensions() {
	$extensions = array(
		// 'facebook' => array(
		// 	'icon' 		=> 'fab fa-facebook-f',
		// 	'name' 		=> __( 'Facebook', 'ditty-news-ticker' ),
		// 	'preview' => true,
		// 	'url' 		=> 'https://www.dittyticker.com/facebook',
		// ),
		'instagram' => array(
			'icon' 		=> 'fab fa-instagram',
			'name' 		=> __( 'Instagram', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-instagram/',
		),
		// 'images' => array(
		// 	'icon' 		=> 'fas fa-image',
		// 	'name' 		=> __( 'Images', 'ditty-news-ticker' ),
		// 	'preview' => true,
		// 	'url' 		=> 'https://www.dittyticker.com/images',
		// ),
		'rss' => array(
			'icon' 		=> 'fas fa-rss',
			'name' 		=> __( 'RSS', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-rss/',
		),
		'twitter' => array(
			'icon' 		=> 'fab fa-twitter',
			'name' 		=> __( 'Twitter', 'ditty-news-ticker' ),
			'preview' => true,
			'url' 		=> 'https://www.metaphorcreations.com/downloads/ditty-twitter/',
		),
	);
	$extensions = apply_filters( 'ditty_extensions', $extensions );
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
 * Set the global draft values
 * @since    3.0
*/
function ditty_set_draft_values( $values ) {	
	global $ditty_draft_values;
	$ditty_draft_values = $values;
}

/**
 * Get the global draft values
 * @since    3.0
*/
function ditty_get_draft_values() {	
	global $ditty_draft_values;
	if ( ! empty( $ditty_draft_values ) ) {
		return $ditty_draft_values;
	}	
}

/**
 * Get the item draft values
 * @since    3.0
*/
function ditty_draft_item_get_data( $item_id, $key = false ) {	
	$draft_values = ditty_get_draft_values();
	if ( ! $draft_values ) {
		return false;
	}
	if ( ! isset( $draft_values['items'] ) ) {
		return false;
	}
	if ( ! isset( $draft_values['items'][$item_id] ) ) {
		return false;
	}
	if ( ! isset( $draft_values['items'][$item_id]['data'] ) ) {
		return false;
	}
	if ( $key ) {
		if ( isset( $draft_values['items'][$item_id]['data'][$key] ) ) {
			return $draft_values['items'][$item_id]['data'][$key];
		}
	} else {
		return $draft_values['items'][$item_id]['data'];
	}
}

/**
 * Get item draft meta
 * @since    3.0
*/
function ditty_draft_item_get_meta( $item_id, $key = false ) {	
	$draft_values = ditty_get_draft_values();
	if ( ! $draft_values ) {
		return false;
	}
	if ( ! isset( $draft_values['items'] ) ) {
		return false;
	}
	if ( ! isset( $draft_values['items'][$item_id] ) ) {
		return false;
	}
	if ( ! isset( $draft_values['items'][$item_id]['meta'] ) ) {
		return false;
	}
	if ( $key ) {
		if ( isset( $draft_values['items'][$item_id]['meta'][$key] ) ) {
			return $draft_values['items'][$item_id]['meta'][$key];
		}
	} else {
		return $draft_values['items'][$item_id]['meta'];
	}
}

/**
 * Update item draft meta
 * @since    3.0
*/
function ditty_draft_item_update_meta( $item_id, $key = false, $value ) {	
	$draft_values = ditty_get_draft_values();
	if ( ! $draft_values ) {
		$draft_values = array();
	}
	if ( ! isset( $draft_values['items'] ) ) {
		$draft_values['items'] = array();
	}
	if ( ! isset( $draft_values['items'][$item_id] ) ) {
		$draft_values['items'][$item_id] = array();
	}
	if ( ! isset( $draft_values['items'][$item_id]['meta'] ) ) {
		$draft_values['items'][$item_id]['meta'] = array();
	}
	if ( $key ) {
		$draft_values['items'][$item_id]['meta'][$key] = $value;
	} else {
		$draft_values['items'][$item_id]['meta'] = $value;
	}
	ditty_set_draft_values( $draft_values );
}

/**
 * Get the layout draft values
 * @since    3.0
*/
function ditty_draft_layout_get( $layout_id = false, $key = false ) {	
	$draft_values = ditty_get_draft_values();
	if ( ! $draft_values ) {
		return false;
	}
	if ( ! isset( $draft_values['layouts'] ) ) {
		return false;
	}
	if ( ! $layout_id ) {
		return $draft_values['layouts'];
	}
	if ( ! isset( $draft_values['layouts'][$layout_id] ) ) {
		return false;
	}
	if ( $key ) {
		if ( isset( $draft_values['layouts'][$layout_id][$key] ) ) {
			return $draft_values['layouts'][$layout_id][$key];
		}
	} else {
		return $draft_values['layouts'][$layout_id];
	}
}

/**
 * Update a layout draft
 * @since    3.0
*/
function ditty_draft_layout_update( $layout_id, $key = false, $value ) {	
	$draft_values = ditty_get_draft_values();
	if ( ! $draft_values ) {
		$draft_values = array();
	}
	if ( ! isset( $draft_values['layouts'] ) ) {
		$draft_values['layouts'] = array();
	}
	if ( ! isset( $draft_values['layouts'][$layout_id] ) ) {
		$draft_values['layouts'][$layout_id] = array();
	}
	if ( $key ) {
		$draft_values['layouts'][$layout_id][$key] = $value;
	} else {
		$draft_values['layouts'][$layout_id] = $value;
	}
	ditty_set_draft_values( $draft_values );
}

/**
 * Get the display draft values
 * @since    3.0
*/
function ditty_draft_display_get( $display_id, $key = false ) {	
	$draft_values = ditty_get_draft_values();
	if ( ! $draft_values ) {
		return false;
	}
	if ( ! isset( $draft_values['displays'] ) ) {
		return false;
	}
	if ( ! isset( $draft_values['displays'][$display_id] ) ) {
		return false;
	}
	if ( $key ) {
		if ( isset( $draft_values['displays'][$display_id][$key] ) ) {
			return $draft_values['displays'][$display_id][$key];
		}
	} else {
		return $draft_values['displays'][$display_id];
	}
}

/**
 * Update a display draft
 * @since    3.0
*/
function ditty_draft_display_update( $display_id, $key = false, $value ) {	
	$draft_values = ditty_get_draft_values();
	if ( ! $draft_values ) {
		$draft_values = array();
	}
	if ( ! isset( $draft_values['displays'] ) ) {
		$draft_values['displays'] = array();
	}
	if ( ! isset( $draft_values['displays'][$display_id] ) ) {
		$draft_values['displays'][$display_id] = array();
	}
	if ( $key ) {
		$draft_values['displays'][$display_id][$key] = $value;
	} else {
		$draft_values['displays'][$display_id] = $value;
	}
	ditty_set_draft_values( $draft_values );
}

/**
 * Check if a layout exists for a layout type
 * @since    3.0
*/
function ditty_layout_exists( $layout_id, $layout_type ) {	
	$draft = ditty_draft_layout_get( $layout_id );
	if ( $draft && 'DELETE' === $draft ) {
		return false;
	}
	if ( strpos( $layout_id, 'new-' ) !== false ) {
		if ( isset( $draft['layout_type'] ) && $layout_type == $draft['layout_type'] ) {
			return true;
		}
	} elseif( $post_status = get_post_status( $layout_id ) ) {
		if ( 'publish' == $post_status && $layout_type == get_post_meta( $layout_id, '_ditty_layout_type', true ) ) {
			return true;
		}
	} else {
		$layout_type_object = ditty_layout_type_object( $layout_type );
		$templates = $layout_type_object->templates();
		if ( is_array( $templates ) && count( $templates ) > 0 ) {
			foreach ( $templates as $slug => $template ) {
				if ( $layout_id == $slug ) {
					return true;
					break;
				}
			}
		}
	}
}

/**
 * Check if a layout type exists
 *
 * @since    3.0
 * @access   public
 * @var      bool
*/
function ditty_layouts_with_type( $layout_type, $layout_template = false, $layout_version = false, $return = 'ids' ) {
	$args = array(
		'posts_per_page' 	=> -1,
		'post_type' 			=> 'ditty_layout',
		'post_status'			=> 'publish',
		'fields' 					=> 'ids',
	);
	$meta_query = array();
	$meta_query['type'] = array(
		'key' 	=> '_ditty_layout_type',
		'value'	=> $layout_type,
	);
	if ( $layout_template ) {
		$meta_query['template'] = array(
			'key' 	=> '_ditty_layout_template',
			'value'	=> $layout_template,
		);
	}
	if ( $layout_version ) {
		$meta_query['version'] = array(
			'key' 	=> '_ditty_layout_version',
			'value'	=> $layout_version,
		);
	}
	$args['meta_query'] = $meta_query;
	$layouts = get_posts( $args );
	
	if ( 'versions' == $return ) {
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
 * @since    3.0
 * @access   public
 * @var      bool
*/
function ditty_displays_with_type( $display_type, $display_template = false, $display_version = false, $return = 'ids' ) {
	$args = array(
		'posts_per_page' 	=> -1,
		'post_type' 			=> 'ditty_display',
		'post_status'			=> 'publish',
		'fields' 					=> 'ids',
	);
	$meta_query = array();
	$meta_query['type'] = array(
		'key' 	=> '_ditty_display_type',
		'value'	=> $display_type,
	);
	if ( $display_template ) {
		$meta_query['template'] = array(
			'key' 	=> '_ditty_display_template',
			'value'	=> $display_template,
		);
	}
	if ( $display_version ) {
		$meta_query['version'] = array(
			'key' 	=> '_ditty_display_version',
			'value'	=> $display_version,
		);
	}
	$args['meta_query'] = $meta_query;
	$displays = get_posts( $args );
	
	if ( 'versions' == $return ) {
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
 * Return the default layouts
 *
 * @since    3.0
 * @var      array	$default_layouts    
*/
function ditty_default_layouts() {
	$default_layouts = array();	
	$layout_types = ditty_layout_types();
	if ( is_array( $layout_types ) && count( $layout_types ) > 0 ) {
		foreach ( $layout_types as $layout_type => $data ) {
			$type_object = ditty_layout_type_object( $layout_type );
			$templates = $type_object->templates();
			
			$default_layouts[$layout_type] = array(
				'label' => $data['label'],
				'templates' => $templates,
			);
		}
	}
	
	return $default_layouts;
}

/**
 * Return the default displays
 *
 * @since    3.0
 * @var      array	$default_displays    
*/
function ditty_default_displays() {
	$default_displays = array();	
	$display_types = ditty_display_types();
	if ( is_array( $display_types ) && count( $display_types ) > 0 ) {
		foreach ( $display_types as $display_type => $data ) {
			$type_object = ditty_display_type_object( $display_type );
			$templates = $type_object->templates();
			
			$default_displays[$display_type] = array(
				'label' => $data['label'],
				'templates' => $templates,
			);
		}
	}
	
	return $default_displays;
}

/**
 * Return the default display for Dittys
 *
 * @since    3.0
 * @var      bool
*/
function ditty_default_display( $post_id ) {
	$display_types = ditty_display_types();
	
	// Check if saved default display exists
	$default_display = ditty_settings( 'default_display' );
	if ( $default_display && 'publish' == get_post_status( $default_display ) ) {
		$display_type == get_post_meta( $default_display, '_ditty_display_type', true );
		if ( array_key_exists( $display_type, $display_types ) ) {
			return $default_display;
		}
	}
	
	$display_id = Ditty()->displays->install_default( 'ticker', 'default' );
	return $display_id;
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
 * Return item data for a Ditty
 *
 * @since    3.0
 * @access   public
 * @var      array    $items_meta    Array of items connected to a Ditty
 */
function ditty_items_meta( $ditty_id=false ) {	
	$ditty_id = $ditty_id ? $ditty_id : get_the_id();
	global $items_meta;
	
	if ( empty( $items_meta ) ) {
		$items_meta = array();
	}	
	if ( ! isset( $items_meta[$ditty_id] ) ) {
		$normalized_meta = array();
		$all_meta = Ditty()->db_items->get_items( $ditty_id );
		if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
			foreach ( $all_meta as $i => $meta ) {
				$value = maybe_unserialize( $meta->item_value );
				$meta->item_value = $value;
				unset( $meta->layout_id ); // TODO: Maybe remove?
				$normalized_meta[] = apply_filters( 'ditty_item_meta', $meta, $meta->item_id, $ditty_id );
			} 
		}
		$items_meta[$ditty_id] = apply_filters( 'ditty_items_meta', $normalized_meta, $ditty_id );
	}
	return $items_meta[$ditty_id];
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
	$value = maybe_unserialize( $meta->item_value );
	$meta->item_value = $value;
	return apply_filters( 'ditty_item_meta', $meta, $item_id, $ditty_id );
}

/**
 * Return an array of new item meta
 *
 * @since    3.0
 * @access   public
 * @var      array    $item_meta    Array of item data
 */
function ditty_get_new_item_meta( $ditty_id ) {	
	$item_type_object = ditty_item_type_object( 'default' );
	$item_value = $item_type_object->default_settings();
	$meta = array(
		'item_id' => uniqid( 'new-' ),
		'item_type' => 'default',
		'item_value' => $item_value,
		'ditty_id' => $ditty_id,
		'layout_id'	=> 'default',
		'layout_value' => 'default',
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
function ditty_replace_html_tags( $tags, $value, $string ) {	
	$content = preg_replace_callback( "/{([A-z0-9\-\_]+)}/s", function ( $matches ) use( $tags, $value ) {
			
			// Get tag
			$tag = $matches[1];
			
			// Return tag if tag not set
			if ( ! array_key_exists( $tag, $tags ) ) {
				return $matches[0];
			}
			
			return call_user_func( $tags[$tag]['func'], $value );
			
  	}, $string
	);
	
	// Remove multiple white-spaces, tabs and new-lines
	$pattern = '/\s+/S';
	$content = preg_replace( $pattern, ' ', $content );
	
	// Strip out whitespace
	//$content = preg_replace( "/[\r\n]+/", "", $content );

	return $content;
}

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
 * Sanitize array values
 *
 * @since    3.0
 * @var      $eases array
*/
function ditty_sanitize_array( $values ) {
	
	// Just in case it's not an array
	if ( ! is_array( $values ) ) {
		return $values;
	}
	
	$sanitized_values = array();
	if ( is_array( $values ) && count( $values ) > 0 ) {
		foreach ( $values as $key => $value ) {
			$sanitized_values[$key] = sanitize_text_field( $value );
		}
	}
	
	return $sanitized_values;
}

/**
 * Render the Ditty container
 *
 * @since    3.0
 */
function ditty_render( $atts ) {

	$defaults = array(
		'id' 								=> '',
		'display' 					=> '',
		'display_settings' 	=> '',
		'uniqid' 						=> '',
		'class' 						=> '',
		'show_editor' 			=> 0,
		'force_load' 				=> 0,
	);
	$args = shortcode_atts( $defaults, $atts );

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
	
	ditty_add_scripts( $args['id'], $args['display']);
	
	$atts = array(
		'class' 								=> $class,
		'data-id' 							=> $args['id'],
		'data-display' 					=> ( '' != $args['display'] ) ? $args['display'] : false,
		'data-display_settings' => ( '' != $args['display_settings'] ) ? $args['display_settings'] : false,
		'data-show_editor' 			=> ( 0 != intval( $args['show_editor'] ) ) ? '1' : false,
		'data-force_load' 			=> ( 0 != intval( $args['force_load'] ) ) ? '1' : false,
	);
	return '<div ' . ditty_attr_to_html( $atts ) . '></div>';
}

/**
 * Parse ditty script types and add to global
 *
 * @since    3.0
 */
function ditty_add_scripts( $ditty_id, $display = '' ) {
		
	global $ditty_item_scripts;
	if ( empty( $ditty_item_scripts ) ) {
		$ditty_item_scripts = array();
	}
	global $ditty_display_scripts;
	if ( empty( $ditty_display_scripts ) ) {
		$ditty_display_scripts = array();
	}
	
	// Store the item types
	$items = Ditty()->db_items->get_items( $ditty_id );
	if ( is_array( $items ) && count( $items ) > 0 ) {
		foreach ( $items as $i => $item ) {
			$item_type = $item->item_type;
			$ditty_item_scripts[$item_type] = $item_type;
		}
	}
	
	// Store the display types
	if ( '' === $display ) {
		$display = get_post_meta( $ditty_id, '_ditty_display', true );
	}
	$display_obj = new Ditty_Display( $display );
	$display_type = $display_obj->get_display_type();
	$ditty_display_scripts[$display_type] = $display_type;
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
 * Parse layout atts
 *
 * @since    3.0
 * @var      array	$parsed_atts
*/
function ditty_layout_parse_atts( $atts = array(), $s ) {
	$parsed_atts = array();
	if ( is_array( $atts ) && count( $atts ) > 0 ) {
		foreach ( $atts as $key => $value ) {
			if ( $custom_value = $s->getParameter( $key ) ) {
				$parsed_atts[$key] = $custom_value;
			} else {
				$parsed_atts[$key] = $value;
			}
		}
	}
	return $parsed_atts;
}

/**
 * Get global Ditty
 *
 * @since    3.0
 * @var      array	$parsed_atts
*/
function ditty_get_globals() {
	$prepared_ditty = array();
	$global_ditty = ditty_settings( 'global_ditty' );
	if ( is_array( $global_ditty ) && count( $global_ditty ) > 0 ) {
		foreach ( $global_ditty as $i => &$ditty ) {
			$ditty['selector'] = html_entity_decode( $ditty['selector'] );
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
 * Decrypt values
 *
 * @since    3.0
 * @var      string $output
*/
function ditty_news_ticker_enabled() {
	if ( '' != ditty_settings( 'ditty_news_ticker' ) ) {
		return true;
	}
}

/**
 * Return the current version
 *
 * @since    3.0
 * @var      string $output
*/
function ditty_version() {
	return DITTY_VERSION;
}

/**
 * Retrieve meta field for a item.
 *
 * @since   3.0
 */
function ditty_item_get_meta( $item_id, $meta_key = '', $single = true ) {
	if ( $draft_meta = ditty_draft_item_get_meta( $item_id, $meta_key ) ) {
		return $draft_meta;
	} else {
		return Ditty()->db_item_meta->get_meta( $item_id, $meta_key, $single );
	}
}

/**
 * Add meta data field to a item.
 *
 * @since   3.0
 */
function ditty_item_add_meta( $item_id, $meta_key = '', $meta_value, $unique = false ) {
	return Ditty()->db_item_meta->add_meta( $item_id, $meta_key, $meta_value, $unique );
}

/**
 * Update item meta field based on item ID.
 *
 * @since   3.0
 */
function ditty_item_update_meta( $item_id, $meta_key = '', $meta_value, $prev_value = '' ) {
	return Ditty()->db_item_meta->update_meta( $item_id, $meta_key, $meta_value, $prev_value );
}

/**
 * Remove metadata matching criteria from a item.
 *
 * @since   3.0
 */
function ditty_item_delete_meta( $item_id, $meta_key = '', $meta_value = '' ) {
	return Ditty()->db_item_meta->delete_meta( $item_id, $meta_key, $meta_value );
}

/**
 * Get all item meta for a specified item.
 *
 * @since   3.0
 */
function ditty_item_custom_meta( $item_id ) {
	return Ditty()->db_item_meta->custom_meta( $item_id );
}