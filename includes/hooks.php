<?php
namespace Ditty\Hooks;

add_action( 'delete_post', __NAMESPACE__ . '\delete_post_items' );
add_filter( 'wp_kses_allowed_html', __NAMESPACE__ . '\kses_allowed_html', 10, 2 );
add_filter( 'ditty_layout_tags', __NAMESPACE__ . '\default_layout_tags', 10, 2 );
add_filter( 'admin_body_class', __NAMESPACE__ . '\dashboard_menu_classes', 99 );
add_filter( 'custom_menu_order', __NAMESPACE__ . '\dashboard_menu_order' );
add_action( 'admin_menu', __NAMESPACE__ . '\dashboard_custom_menu_classes', 99 );

/**
 * Delete items from deleted Dittys
 *
 * @since    3.0
 * @access   public
 * @var      null
*/
function delete_post_items( $post_id ) {
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

/**
 * Customize wp_kses allowed html
 *
 * @since    3.0
 * @access   public
 * @var      array    $allowed
*/
function kses_allowed_html( $allowed, $context ) {
	if ( is_array( $context ) ) {
  	return $allowed;
  }

  if ( 'post' == $context ) {
	  $allowed['a']['nofollow'] = true;
  }

  return $allowed;
}

/**
 * Add to the item tags for default item type layouts
 * 
 * @since   3.0.13
 */
function default_layout_tags( $tags, $item_type ) {
	if ( 'default' == $item_type || 'wp_editor' == $item_type ) {
		if ( isset( $tags['time'] ) ) {
			$tags['time']['atts']['type'] = 'item_created';
		}
	}
	return $tags;
}

/**
 * Add custom classes to style menu items
 *
 * @since    3.1.15
 * @access   public
 * @var      array    $allowed
*/
function dashboard_menu_classes( $classes ) {
	if ( isset( $_GET['dittyDev'] ) ) {
		$classes .= ' dittyDev';
	}
	return $classes;
}

/**
 * Reorder the menu items
 *
 * @since    3.0.22
*/
function dashboard_menu_order( $menu_ord ) {
	global $submenu;

	if ( ! isset( $submenu['edit.php?post_type=ditty'] ) ) {
		return $menu_ord;
	}
	
	$current_menu = $submenu['edit.php?post_type=ditty'];
	$new_menu = array();
	$extra_items = array();
	$order = apply_filters( 'dashboard_menu_order', array(
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

/**
 * Add to the admin menu classes for Ditty
 * 
 * @since    3.1.19 
 */
function dashboard_custom_menu_classes() {
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