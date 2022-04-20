<?php
	
/**
 * Delete items from deleted Dittys
 *
 * @since    3.0
 * @access   public
 * @var      null
*/
function ditty_delete_post_items( $post_id ) {
	global $post;
	if ( isset( $post->post_type ) && ! ( 'ditty' == $post->post_type ) ) {
		return $post_id;
	}
	
	// don't save if user doesn't have permission
	if ( ! current_user_can( 'delete_dittys', $post_id ) ) {
		return $post_id;
	}
	
	// Delete a Ditty's items
	$items_meta = ditty_items_meta( $post_id );
	if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {
		foreach ( $items_meta as $i => $item ) {
			ditty_item_delete_all_meta( $item->item_id );
			Ditty()->db_items->delete( $item->item_id );
		}
	}	
}
add_action( 'delete_post', 'ditty_delete_post_items' );

/**
 * Check post content for Ditty blocks
 *
 * @since    3.0
 * @access   public
 * @var      null
*/
function ditty_check_content_for_blocks() {
	
	// Parse post content for Ditty blocks
	if ( is_admin() ) {
		return false;
	}
	
	// Parse widgets for Ditty blocks
	$widget_blocks = get_option( 'widget_block' );
	if ( is_array( $widget_blocks ) && count( $widget_blocks ) > 0 ) {
		foreach ( $widget_blocks as $i => $widget_block ) {
			if ( ! is_array( $widget_block ) ) {
				continue;
			}
			$blocks = parse_blocks( $widget_block['content'] );
			if ( is_array( $blocks ) && count( $blocks ) > 0 ) {
				foreach ( $blocks as $i => $block ) {
					if ( 'metaphorcreations/ditty-block' === $block['blockName'] ) {
						$ditty = $block['attrs']['ditty'];
						$display = isset( $block['attrs']['display'] ) ? $block['attrs']['display'] : '';
						ditty_add_scripts( $ditty, $display );
					}	
				}
			}
		}
	}

	// Parse post content for Ditty blocks
	if ( ! is_singular() ) {
		return false;
	}
	global $post;
	$blocks = parse_blocks( $post->post_content );
	if ( is_array( $blocks ) && count( $blocks ) > 0 ) {
		foreach ( $blocks as $i => $block ) {
			if ( 'metaphorcreations/ditty-block' === $block['blockName'] ) {
				$ditty = $block['attrs']['ditty'];
				$display = isset( $block['attrs']['display'] ) ? $block['attrs']['display'] : '';
				ditty_add_scripts( $ditty, $display );
			}	
		}
	}
}
add_filter( 'wp', 'ditty_check_content_for_blocks' );


/**
 * Customize wp_kses allowed html
 *
 * @since    3.0
 * @access   public
 * @var      array    $allowed
*/
function ditty_kses_allowed_html( $allowed, $context ) {
	if ( is_array( $context ) ) {
  	return $allowed;
  }

  if ( 'post' == $context ) {
	  $allowed['a']['nofollow'] = true;
  }

  return $allowed;
}
add_filter( 'wp_kses_allowed_html', 'ditty_kses_allowed_html', 10, 2 );

/**
 * Add to the item tags for default item type layouts
 * 
 * @since   3.0.13
 */
function ditty_default_layout_tags( $tags, $item_type ) {
	if ( 'default' == $item_type || 'wp_editor' == $item_type ) {
		if ( isset( $tags['time'] ) ) {
			$tags['time']['atts']['type'] = 'item_created';
		}
	}
	return $tags;
}
add_filter( 'ditty_layout_tags', 'ditty_default_layout_tags', 10, 2 );

/**
 * Filter the available item tags for layout editing
 * 
 * @since   3.0.13
 */
function ditty_default_layout_tags_list( $tags, $item_type ) {
	if ( 'default' == $item_type ||  'wp_editor' == $item_type ) {
		$allowed_tags = array(
			'content',
			'time',
			'author_avatar',
			'author_bio',
			'author_name',
		);
		$tags = array_intersect_key( $tags, array_flip( $allowed_tags ) );
	}
	return $tags;
}
add_filter( 'ditty_layout_tags_list', 'ditty_default_layout_tags_list', 10, 2 );

/**
 * Add custom classes to style menu items
 *
 * @since    3.0
 * @access   public
 * @var      array    $allowed
*/
function ditty_dashboard_menu_classes( $classes ) {
	if ( 'disabled' == ditty_settings( 'ditty_layout_ui' ) ) {
		$classes .= ' ditty_layout_ui--disabled';
	}
	if ( 'disabled' == ditty_settings( 'ditty_display_ui' ) ) {
		$classes .= ' ditty_display_ui--disabled';
	}
	return $classes;
}
add_filter( 'admin_body_class', 'ditty_dashboard_menu_classes', 99 );

/**
 * Reorder the menu items
 *
 * @since    3.0.20
*/
function ditty_dashboard_menu_order( $menu_ord ) {
		global $submenu;
		$current_menu = $submenu['edit.php?post_type=ditty'];
		$current_order = array();
		$new_menu = array();
		$extra_items = array();
		$order = apply_filters( 'ditty_dashboard_menu_order', array(
			'edit.php?post_type=ditty',
			'post-new.php?post_type=ditty',
			'edit.php?post_type=ditty_layout',
			'edit.php?post_type=ditty_display',
			'ditty_extensions',
			'ditty_settings',
			'ditty_export',
			'edit.php?post_type=ditty_news_ticker',
			'mtphr_dnt_settings',
		), $current_menu );
		
		// Find any extra items that aren't in the order list
		foreach ( $current_menu as $i => $item ) {
			$current_order[] = $item[2];
			if ( ! in_array( $item[2], $order ) ) {
				$extra_items[] = $item;
			}
		}
		
		// Trim out any extra items in the order so we don't hit an infinite loop
		$order = array_intersect( $order, $current_order );
		
		// Set the order of the new menu
		while( count( $order ) > 0 ) {
			foreach ( $current_menu as $i => $item ) {
				if ( count( $order) > 0 && $order[0] == $item[2] ) {
					$new_menu[] = $item;
					array_shift( $order );
				}
			}
		}
		
		// Add extra menu items not in the order list
		foreach ( $extra_items as $i => $item ) {
			$new_menu[] = $item;
		}
		
		// Set the new menu
		$submenu['edit.php?post_type=ditty'] = $new_menu;

		return $menu_ord;
}
add_filter( 'custom_menu_order', 'ditty_dashboard_menu_order' );

/**
	 * Reorder the Ditty submenus
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $allowed
	*/
// function ditty_set_menu_order() {
// 	global $submenu;
// 	$new_sub_menu = [];
// 	foreach ( $submenu as $menu_name => $menu_items ) {
// 		if ( 'edit.php?post_type=ditty' === $menu_name ) {
// 			if ( is_array( $menu_items ) && count( $menu_items ) > 0 ) {
// 				foreach ( $menu_items as $order => $menu_item ) {
// 					switch( $menu_item[2] ) {
// 						case 'edit.php?post_type=ditty_layout':
// 							$new_sub_menu[11] = $menu_item;
// 							break;
// 						case 'edit.php?post_type=ditty_display':
// 							$new_sub_menu[12] = $menu_item;
// 							break;
// 						case 'ditty_extensions':
// 							$new_sub_menu[13] = $menu_item;
// 							break;
// 						case 'ditty_settings':
// 							$new_sub_menu[14] = $menu_item;
// 							break;
// 						case 'ditty_export':
// 							$new_sub_menu[15] = $menu_item;
// 							break;
// 						default:
// 							$new_sub_menu[$order] = $menu_item;
// 					}
// 				}
// 			}
// 			$submenu['edit.php?post_type=ditty'] = $new_sub_menu;
// 			break;
// 		}
// 	}
// }
// add_action('custom_menu_order', 'ditty_set_menu_order' );



function sdafdstest() {
	$custom = Ditty()->db_item_meta->custom_meta( 129 );
	if ( $custom ) {
		echo '<pre>';print_r('yes');echo '</pre>';
	} else {
		echo '<pre>';print_r('no');echo '</pre>';
	}
	if ( is_array( $custom ) && count( $custom ) > 0 ) {
		foreach ( $custom as $data ) {
			if ( isset( $data->meta_key ) ) {
				Ditty()->db_item_meta->delete_meta( 129, $data->meta_key );
			}			
		}
	}
}
//add_action( 'admin_init', 'sdafdstest' );
