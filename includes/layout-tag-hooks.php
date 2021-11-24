<?php

/**
 * Modify the layout image
 *
 * @since    3.0
 * @var      html
*/
function ditty_init_layout_tag_image( $image, $item_type, $data, $atts ) {
	if ( ! $image_data = ditty_layout_tag_image_data( $item_type, $data, $atts ) ) {
		return $image;
	}
	$defaults = array(
		'width' 	=> '',
		'height' 	=> '',
		'fit' 		=> '',
	);
	$args = shortcode_atts( $defaults, $atts );
	$style = '';
	if ( '' !=  $args['width'] ) {
		$style .= 'width:' . $args['width'] . ';';
	}
	if ( '' !=  $args['height'] ) {
		$style .= 'height:' . $args['height'] . ';';
	}
	if ( '' !=  $args['fit'] ) {
		$style .= 'object-fit:' . $args['fit'] . ';';
	}
	$image_defaults = array(
		'src' 		=> '',
		'width' 	=> '',
		'height' 	=> '',
		'alt' 		=> '',
		'style'		=> ( '' != $style ) ? $style : false,
	);
	$image_args = shortcode_atts( $image_defaults, $image_data );
	$image = '<img ' . ditty_attr_to_html( $image_args ) . ' />';
	return $image;	
}
add_filter( 'ditty_layout_tag_image', 'ditty_init_layout_tag_image', 10, 4 );

/**
 * Modify the layout image url
 *
 * @since    3.0
 * @var      html
*/
function ditty_init_layout_tag_image_url( $image_url, $item_type, $data, $atts ) {
	if ( ! $image_data = ditty_layout_tag_image_data( $item_type, $data, $atts ) ) {
		return $image_url;
	}
	if ( isset( $image_data['src'] ) ) {
		$image_url = $image_data['src'];
	}
	return $image_url;	
}
add_filter( 'ditty_layout_tag_image_url', 'ditty_init_layout_tag_image_url', 10, 4 );

/**
 * Modify the layout content
 *
 * @since    3.0
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
			$content = sprintf( '<a href="%2$s" class="ditty-rss__author__link" target="%3$s" rel="%4$s" title="%5$s">%1$s</a>', $content, $url, $target, $rel, $title );
		}
	} elseif ( 'wp_editor' == $item_type ) {
		$content = $data['content'];
	}	
	return $content;	
}
add_filter( 'ditty_layout_tag_content', 'ditty_default_layout_tag_content', 10, 4 );