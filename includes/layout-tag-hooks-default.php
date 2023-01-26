<?php

/**
 * Modify the layout user avatar
 *
 * @since    3.0.13
 * @var      html
*/
function ditty_default_layout_tag_author_avatar_data( $avatar_data, $item_type, $data, $atts ) {
	$types = array(
		'default',	
		'wp_editor',
	);
	if ( in_array(  $item_type, $types ) ) {	
		if ( $item_author = ditty_layout_item_meta( $data, 'item_author' ) ) {
			$avatar_data = array(
				'src' => get_avatar_url( $item_author ),
				'alt'	=> get_the_author_meta( 'display_name', $item_author ),
			);
		}
	}
	return $avatar_data;
}
add_filter( 'ditty_layout_tag_author_avatar_data', 'ditty_default_layout_tag_author_avatar_data', 10, 4 );

/**
 * Modify the layout author name
 *
 * @since    3.0.13
 * @var      html
*/
function ditty_default_layout_tag_author_name( $author_name, $item_type, $data, $atts ) {
	$types = array(
		'default',	
		'wp_editor',
	);
	if ( in_array(  $item_type, $types ) ) {
		if ( $item_author = ditty_layout_item_meta( $data, 'item_author' ) ) {
			$author_name = get_the_author_meta( 'display_name', $item_author );
		}
	}
	return $author_name;
}
add_filter( 'ditty_layout_tag_author_name', 'ditty_default_layout_tag_author_name', 10, 4 );

/**
 * Modify the layout author bio
 *
 * @since    3.0.13
 * @var      html
*/
function ditty_default_layout_tag_author_bio( $author_bio, $item_type, $data, $atts ) {
	$types = array(
		'default',	
		'wp_editor',
	);
	if ( in_array(  $item_type, $types ) ) {
		if ( $item_author = ditty_layout_item_meta( $data, 'item_author' ) ) {
			$author_bio = get_the_author_meta( 'description', $item_author );
		}
	}
	return $author_bio;
}
add_filter( 'ditty_layout_tag_author_bio', 'ditty_default_layout_tag_author_bio', 10, 4 );

/**
 * Modify the layout content
 *
 * @since    3.0.16
 * @var      html
*/
function ditty_default_layout_tag_content( $content, $item_type, $data, $atts ) {
	if ( 'default' == $item_type ) {
		$content = $data['content'];
		$url = ( isset( $data['link_url'] ) && '' != $data['link_url'] ) ? $data['link_url'] : false;
		if ( $url ) {
			$target = isset( $data['link_target'] ) ? $data['link_target'] : '_self';
			$rel = isset( $data['link_nofollow'] ) ? 'nofollow' : '';
			$title = isset( $data['link_title'] ) ? $data['link_title'] : '';
			$content = sprintf( '<a href="%2$s" class="ditty-item__link" target="%3$s" rel="%4$s" title="%5$s">%1$s</a>', $content, $url, $target, $rel, $title );
		}
	} elseif ( 'wp_editor' == $item_type ) {
		$content = $data['content'];
	}	
	return $content;	
}
add_filter( 'ditty_layout_tag_content', 'ditty_default_layout_tag_content', 10, 4 );

/**
 * Modify the layout timestamp
 *
 * @since    3.0.13
 * @var      html
*/
function ditty_default_layout_tag_timestamp( $timestamp, $item_type, $data, $atts ) {
	$types = array(
		'default',	
		'wp_editor',
	);
	if ( in_array(  $item_type, $types ) ) {
		$timestamp = false;
		if ( isset( $atts['type'] ) && 'item_modified' == $atts['type'] ) {
			if ( $date_modified = ditty_layout_item_meta( $data, 'date_modified' ) ) {
				$timestamp = strtotime( get_date_from_gmt( $date_modified ) );
			}
		}
		if ( ! $timestamp && $date_created = ditty_layout_item_meta( $data, 'date_created' ) ) {
			$timestamp = strtotime( get_date_from_gmt( $date_created ) );
		}
	}
	return $timestamp;	
}
add_filter( 'ditty_layout_tag_timestamp', 'ditty_default_layout_tag_timestamp', 10, 4 );