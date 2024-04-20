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
	
	// All other functionality to hook in and grab item meta before deleting
	do_action( 'ditty_before_delete_post_items', $post_id, $items_meta );
	
	if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {

    // Delete the items
		foreach ( $items_meta as $i => $item ) {
			ditty_item_delete_all_meta( $item->item_id );
			Ditty()->db_items->delete( $item->item_id );
      
		}
	}	
	
	// All other functionality to hook in and grab item meta before deleting
	do_action( 'ditty_after_delete_post_items', $post_id, $items_meta );
}
add_action( 'delete_post', 'ditty_delete_post_items' );

/**
 * Check post content for Ditty blocks
 *
 * @since    3.0.29
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
			$blocks = isset( $widget_block['content'] ) ? parse_blocks( $widget_block['content'] ) : false;
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
	global $post;
	if ( ! is_singular() || ! is_object( $post ) ) {
		return false;
	}
	$blocks = parse_blocks( $post->post_content );
	if ( is_array( $blocks ) && count( $blocks ) > 0 ) {
		foreach ( $blocks as $i => $block ) {
			if ( 'metaphorcreations/ditty' === $block['blockName'] ) {
        if ( ! isset( $block['attrs']['ditty'] ) ) {
          continue;
        }
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
 * Add custom classes to style menu items
 *
 * @since    3.1.15
 * @access   public
 * @var      array    $allowed
*/
function ditty_dashboard_menu_classes( $classes ) {
	if ( isset( $_GET['dittyDev'] ) ) {
		$classes .= ' dittyDev';
	}
	return $classes;
}
add_filter( 'admin_body_class', 'ditty_dashboard_menu_classes', 99 );

/**
 * Reorder the menu items
 *
 * @since    3.0.22
*/
function ditty_dashboard_menu_order( $menu_ord ) {
	global $submenu;

	if ( ! isset( $submenu['edit.php?post_type=ditty'] ) ) {
		return $menu_ord;
	}
	
	$current_menu = $submenu['edit.php?post_type=ditty'];
	$new_menu = array();
	$extra_items = array();
	$order = apply_filters( 'ditty_dashboard_menu_order', array(
		'edit.php?post_type=ditty',
		'post-new.php?post_type=ditty',
		'?page=ditty-new',
		'edit.php?post_type=ditty_layout',
		'edit.php?post_type=ditty_display',
		'ditty_extensions',
		'ditty_settings',
		'ditty_export',
		'edit.php?post_type=ditty_news_ticker',
		'mtphr_dnt_settings',
	), $current_menu );
	
	// Find any extra items that aren't in the order list & find the new order
	if ( is_array( $current_menu ) && count( $current_menu ) > 0 ) {
		foreach ( $current_menu as $i => $item ) {
			if ( in_array( $item[2], $order ) ) {
				$key = array_search( $item[2], $order );
				$item['order'] = $key;
				$new_menu[] = $item;
			} else {
				$extra_items[] = $item;
			}
		}
	}
	
	// Sort the new menu by the order key
	usort( $new_menu, function( $a, $b ) {
		return $a['order'] - $b['order'];
	} );

	// Add extra menu items not in the order list
	if ( is_array( $extra_items ) && count( $extra_items ) > 0 ) {
		foreach ( $extra_items as $i => $item ) {
			$new_menu[] = $item;
		}
	}
	
	// Set the new menu
	$submenu['edit.php?post_type=ditty'] = $new_menu;

	return $menu_ord;
}
add_filter( 'custom_menu_order', 'ditty_dashboard_menu_order' );

/**
 * Add to the admin menu classes for Ditty
 * 
 * @since    3.1.19 
 */
function ditty_dashboard_custom_menu_classes() {
	global $menu, $submenu;
	$ditty_menu = isset( $submenu['edit.php?post_type=ditty'] ) ? $submenu['edit.php?post_type=ditty'] : false;
	if ( is_array( $ditty_menu ) && count( $ditty_menu ) > 0 ) {
		foreach ( $ditty_menu as &$menu_item ) {
      if ( isset( $menu_item[2] ) ) {
        $classes = isset( $menu_item[4] ) ? $menu_item[4] . ' ' : '';
        $classes .= 'ditty-menu--' . sanitize_title( $menu_item[2] );
        $menu_item[4] = $classes;
      }
		}
	}
	$submenu['edit.php?post_type=ditty'] = $ditty_menu;
}
add_action( 'admin_menu', 'ditty_dashboard_custom_menu_classes', 99 );




// add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
// function prefix_disable_gutenberg($current_status, $post_type)
// {
// 		// Use your post type key instead of 'product'
// 		if ($post_type === 'ditty_display') return false;
// 		return $current_status;
// }

function ditty_shortcode_test_display( $atts, $content = '' ) {
	$defaults = [
		'test' => 'test'
	];
	$args = shortcode_atts( $defaults, $atts );

	$html = '';
	$html .= "<p>This is a shortcode test, using the 'test' attribute: <strong>{$args['test']}</strong>.</p>";
	$html .= $content;

	return $html;
}
add_shortcode( 'ditty_shortcode_test', 'ditty_shortcode_test_display' );



/**
 * Maybe use php display method
 *
 * @since    3.1.19
 */
function ditty_maybe_php_display( $php_display, $display_data, $args ) {
  if ( 'list' == $display_data['type'] ) {
    return true;
  }
  return $php_display;
}
add_filter( 'ditty_php_display', 'ditty_maybe_php_display', 10, 3 );