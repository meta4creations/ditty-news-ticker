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
 * Add global css selectors
 *
 * @since    3.0
 * @access   public
 * @var      array    $allowed
*/
function ditty_layout_css_selectors( $selectors ) {
	$globals = array(
		'elements' => array(
			'selector' 				=> '.ditty-item__elements',
			'description' => __( 'The wrapper around all item elements.', 'ditty-news-ticker' ),
		),
	);
	return $globals + $selectors;
}
add_filter( 'ditty_layout_css_selectors', 'ditty_layout_css_selectors' );

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

function ditty_search_test() {
	$item_types = 'twitter_feed';
	$items = Ditty()->db_items->search_items( 'blacklivesmatter', $item_types );
	if ( is_array( $items ) && count( $items ) > 0 ) {
		foreach ( $items as $i => $item ) {
			echo '<pre>';print_r($item);echo '</pre>';
/*
			if ( ditty_exists( $item->ditty_id ) ) {
				echo '<pre>';print_r( $item->ditty_id );echo '</pre>';
			} else {
				echo '<pre>';print_r( $item->item_id . ' does not exists' );echo '</pre>';
				Ditty()->db_items->delete( $item->item_id );
			}
*/
		}
	}
}
//add_action( 'wp', 'ditty_search_test' );