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
