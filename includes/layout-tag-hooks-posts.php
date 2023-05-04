<?php

/**
 * Add to the item tags for layouts
 * 
 * @since   3.0
 */
// function ditty_posts_lite_layout_tags( $tags, $item_type ) {
// 	if ( 'posts_feed' == $item_type || 'post' == $item_type ) {
// 		if ( isset( $tags['image'] ) ) {
// 			$tags['image']['atts']['size'] = 'large';
// 		}
// 	}
// 	return $tags;
// }
// add_filter( 'ditty_layout_tags', 'ditty_posts_lite_layout_tags', 10, 2 );

/**
 * Return a tag value
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_tag_value( $data, $key = false ) {
	if ( ! is_array( $data ) || ! isset( $data['item'] ) ) {
		return false;
	}
	$item = $data['item'];
	if ( is_object( $item ) ) {
		$item = ( array ) $item;
	}
	if ( $key ) {
		if ( ! isset( $item[$key] ) ) {
			return false;
		}
		return $item[$key];
	}
	return $item;
}

/**
 * Modify the layout user avatar
 *
 * @since    3.0.12
 * @var      html
*/
function ditty_posts_lite_layout_tag_author_avatar_data( $avatar_data, $item_type, $data, $atts ) {
	$types = array(
		'post',	
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {	
		$author_id = ditty_posts_lite_tag_value( $data, 'post_author' );
		$avatar_data = array(
			'src' => get_avatar_url( $author_id ),
			'alt'	=> get_the_author_meta( 'display_name', $author_id ),
		);
	}
	return $avatar_data;
}
add_filter( 'ditty_layout_tag_author_avatar_data', 'ditty_posts_lite_layout_tag_author_avatar_data', 10, 4 );

/**
 * Modify the layout author name
 *
 * @since    3.0.12
 * @var      html
*/
function ditty_posts_lite_layout_tag_author_name( $author_name, $item_type, $data, $atts ) {
	$types = array(
		'post',	
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		$author_id = ditty_posts_lite_tag_value( $data, 'post_author' );
		$author_name = get_the_author_meta( 'display_name', $author_id );
	}
	return $author_name;
}
add_filter( 'ditty_layout_tag_author_name', 'ditty_posts_lite_layout_tag_author_name', 10, 4 );

/**
 * Modify the layout author bio
 *
 * @since    3.0.12
 * @var      html
*/
function ditty_posts_lite_layout_tag_author_bio( $author_bio, $item_type, $data, $atts ) {
	$types = array(
		'post',	
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		$author_id = ditty_posts_lite_tag_value( $data, 'post_author' );
		$author_bio = get_the_author_meta( 'description', $author_id );
	}
	return $author_bio;
}
add_filter( 'ditty_layout_tag_author_bio', 'ditty_posts_lite_layout_tag_author_bio', 10, 4 );

/**
 * Modify the layout category data
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_layout_tag_category_data( $category_data, $item_type, $data, $atts ) {
	$types = array(
		'post',	
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		$category_data = array();
		$terms = get_the_terms( ditty_posts_lite_tag_value( $data, 'ID' ), 'category' );
		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			foreach ( $terms as $i => $term ) {
				$category_data[] = array(
					'label' => $term->name,
					'link'	=> get_term_link( $term->term_id ),
				);
			}
		}
	}
	return $category_data;
}
add_filter( 'ditty_layout_tag_category_data', 'ditty_posts_lite_layout_tag_category_data', 10, 4 );

/**
 * Modify the layout content
 *
 * @since    3.0.18
 * @var      html
*/
function ditty_posts_lite_layout_tag_content( $content, $item_type, $data, $atts ) {
	$types = array(
		'post',	
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		// Possibly show the exerpt
		if ( isset( $atts['content_display'] ) && 'excerpt' == $atts['content_display'] ) {
			$atts['excerpt_length'] = isset( $atts['excerpt_length'] ) 	? intval( $atts['excerpt_length'] ) 					: 200;
			$atts['more'] 					= isset( $atts['more'] ) 						? wp_filter_nohtml_kses( $atts['more'] ) 				: false;
			$atts['more_link']			= isset( $atts['more_link'] ) 			? esc_attr( $atts['more_link'] ) 							: false;
			$atts['more_before'] 		= isset( $atts['more_before'] ) 		? wp_filter_nohtml_kses( $atts['more_before'] ) : false;
			$atts['more_after'] 		= isset( $atts['more_after'] ) 			? wp_filter_nohtml_kses( $atts['more_after'] ) 	: false;
			$content = ditty_init_layout_tag_excerpt( $content, $item_type, $data, $atts );
		} else {
			$content = ditty_posts_lite_tag_value( $data, 'post_content' );
		}
	}
	return $content;
}
add_filter( 'ditty_layout_tag_content', 'ditty_posts_lite_layout_tag_content', 10, 4 );

/**
 * Modify the layout excerpt
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_layout_tag_excerpt_data( $excerpt_data, $item_type, $data, $atts ) {
	$types = array(
		'post',
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		$excerpt_data = false;
		if ( ! $excerpt_data = ditty_posts_lite_tag_value( $data, 'post_excerpt' ) ) {
			$excerpt_data = ditty_posts_lite_tag_value( $data, 'post_content' );
		}
	}
	return $excerpt_data;
}
add_filter( 'ditty_layout_tag_excerpt_data', 'ditty_posts_lite_layout_tag_excerpt_data', 10, 4 );

/**
 * Modify the layout tag link data
 *
 * @since    3.0.12
 * @var      html
*/
function ditty_posts_lite_layout_tag_link_data( $link_data, $item_type, $data, $atts, $prefix ) {
	$types = array(
		'post',
		'posts_feed',
	);
	if ( ! in_array(  $item_type, $types ) ) {
		return $link_data;	
	}
	if ( ! isset( $atts["{$prefix}link"] ) ) {
		return false;
	}

	$link = strval( $atts["{$prefix}link"] );
	$link_url = false;
	$link_title = false;
	switch( strval( $link ) ) {
		case '1':
		case 'true':
		case 'post':	
			$link_url = get_permalink( $data['item'] );
			$link_title = ditty_posts_lite_tag_value( $data, 'post_title' );
			break;
		case 'author':
			$author_id = ditty_posts_lite_tag_value( $data, 'post_author' );
			$link_url = get_author_posts_url( $author_id );
			$link_title = get_the_author_meta( 'display_name', $author_id );
			break;
		case 'author_link':
			$author_id = ditty_posts_lite_tag_value( $data, 'post_author' );
			if ( $user_url = get_the_author_meta( 'user_url', $author_id ) ) {
				$link_url = $user_url;
				$link_title = get_the_author_meta( 'display_name', $author_id );
			}
		default:
			break;
	}
	if ( $link_url ) {
		$link_data = array(
			'url' 		=> esc_url_raw( $link_url ),
			'title'		=> $link_title,
		);
		return $link_data;
	}
}
add_filter( 'ditty_layout_tag_link_data', 'ditty_posts_lite_layout_tag_link_data', 10, 5 );

/**
 * Modify the layout icon
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_layout_tag_icon( $icon, $item_type, $data, $atts ) {
	$types = array(
		'post',
		'posts_feed',
	);
	if ( ! in_array(  $item_type, $types ) ) {
		return $icon;	
	}
	return '<i class="fab fa-wordpress"></i>';
}
add_filter( 'ditty_layout_tag_icon', 'ditty_posts_lite_layout_tag_icon', 10, 4 );

/**
 * Modify the layout image tag data
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_layout_tag_image_data( $image_data, $item_type, $data, $atts ) {
	$types = array(
		'post',
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		if ( $thumbnail_id = get_post_thumbnail_id( $data['item'] ) ) {
			$size = isset( $atts['size'] ) ? sanitize_text_field( $atts['size'] ) : 'large';
			$image_source = wp_get_attachment_image_src( $thumbnail_id, $size, false );
			if ( is_array( $image_source ) ) {
				$image_data = array(
					'src' 		=> $image_source[0],
					'width' 	=> $image_source[1],
					'height' 	=> $image_source[2],
					'alt'			=>	ditty_posts_lite_tag_value( $data, 'post_title' ),
				);
			}
		}
	}
	return $image_data;
}
add_filter( 'ditty_layout_tag_image_data', 'ditty_posts_lite_layout_tag_image_data', 10, 4 );

/**
 * Modify the layout permalink
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_layout_tag_permalink( $permalink, $item_type, $data, $atts ) {
	$types = array(
		'post',
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		$permalink = get_permalink( ditty_posts_lite_tag_value( $data, 'ID' ) );
	}		
	return $permalink;
}
add_filter( 'ditty_layout_tag_permalink', 'ditty_posts_lite_layout_tag_permalink', 10, 4 );

/**
 * Modify the layout timestamp
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_layout_tag_timestamp( $timestamp, $item_type, $data, $atts ) {
	$types = array(
		'post',
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		$timestamp = strtotime( ditty_posts_lite_tag_value( $data, 'post_date' ) );
	}
	return $timestamp;	
}
add_filter( 'ditty_layout_tag_timestamp', 'ditty_posts_lite_layout_tag_timestamp', 10, 4 );

/**
 * Modify the layout title
 *
 * @since    3.0
 * @var      html
*/
function ditty_posts_lite_layout_tag_title( $title, $item_type, $data, $atts ) {
	$types = array(
		'post',
		'posts_feed',
	);
	if ( in_array(  $item_type, $types ) ) {
		$title = ditty_posts_lite_tag_value( $data, 'post_title' );
	}
	return $title;	
}
add_filter( 'ditty_layout_tag_title', 'ditty_posts_lite_layout_tag_title', 10, 4 );