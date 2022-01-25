<?php

/**
 * Return a rendered link
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_render_tag_link( $link, $html, $class, $data, $atts, $prefix = '' ) {
	$link_defaults = array(
		'url'					=> '',
		'title'				=> '',
		'target' 	=> isset( $data["{$prefix}link_target"] ) ? esc_attr( $data["{$prefix}link_target"] ) : '_self',
		'rel'			=> isset( $data["{$prefix}link_rel"] ) ? esc_attr( $data["{$prefix}link_rel"] ) : '',
	);
	$link_args = shortcode_atts( $link_defaults, $link );

	$defaults = array(
		'link_before'	=> '',
		'link_after'	=> '',
	);
	$args = shortcode_atts( $defaults, $atts );
	
	$link_before = ( '' != $args['link_before'] ) ? "<span class='{$class}__before'>" . sanitize_text_field( $args['link_before'] ) . '</span>' : '';
	$link_after = ( '' != $args['link_after'] ) ? "<span class='{$class}__after'>" . sanitize_text_field( $args['link_after'] ) . '</span>' : '';
	
	$html = sprintf( '<a href="%4$s" class="%5$s" target="%6$s" rel="%7$s" title="%8$s">%2$s%1$s%3$s</a>', $html, $link_before, $link_after, $link_args['url'], $class, $link_args['target'], $link_args['rel'], $link_args['title'] );
	
	return $html;
}

/**
 * Return a rendered wrapper
 *
 * @since    3.0.12
 * @var      html
*/
function ditty_layout_render_tag_wrapper( $html, $class = '', $atts = array(), $custom_wrapper = false ) {
	$defaults = array(
		'wrapper' => false,
		'before'	=> '',
		'after'		=> '',
		'class'		=> '',
	);
	$args = shortcode_atts( $defaults, $atts );
	$before = ( '' != $args['before'] ) ? "<span class='{$class}__before'>" . sanitize_text_field( $args['before'] ) . '</span>' : '';
	$after = ( '' != $args['after'] ) ? "<span class='{$class}__after'>" . sanitize_text_field( $args['after'] ) . '</span>' : '';
	
	if ( isset( $args['wrapper'] ) && 'false' != strval( $args['wrapper'] ) && '' != strval( $args['wrapper'] ) ) {
		if ( isset( $args['class'] ) && '' != $args['class'] ) {
			$class .= ' ' . trim( $args['class'] );
		}
		if ( $custom_wrapper ) {
			$html = sprintf( $custom_wrapper, $html );
		}
		$html = sprintf( '<%4$s class="%5$s">%2$s%1$s%3$s</%4$s>', $html, $before, $after, $args['wrapper'], esc_attr( $class ) );
	} elseif ( $custom_wrapper ) {
		$html = sprintf( $custom_wrapper, $html );
	}
	return $html;
}

/**
 * Return a rendered tag
 *
 * @since    3.0.12
 * @var      html
*/
function ditty_layout_render_tag( $html, $class, $item_type, $data, $atts = array(), $custom_wrapper = false, $prefix = '' ) {
	$link_data = apply_filters( 'ditty_layout_tag_link_data', array(), $item_type, $data, $atts, $prefix );
	if ( ! empty( $link_data ) ) {
		$html = ditty_layout_render_tag_link( $link_data, $html, "{$class}__link", $data, $atts, $prefix );
	}
	if ( $html && '' != $html ) {
		return ditty_layout_render_tag_wrapper( $html, $class, $atts, $custom_wrapper );
	}
}

/**
 * The data of the author avatar
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tag_author_avatar_data( $item_type, $data, $atts = array() ) {
	$author_avatar_data = apply_filters( 'ditty_layout_tag_author_avatar_data', array(), $item_type, $data, $atts );
	if ( ! empty( $author_avatar_data ) ) {
		return $author_avatar_data;
	}
}

/**
 * The data of the author banner
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tag_author_banner_data( $item_type, $data, $atts = array() ) {
	$author_banner_data = apply_filters( 'ditty_layout_tag_author_banner_data', array(), $item_type, $data, $atts );
	if ( ! empty( $author_banner_data ) ) {
		return $author_banner_data;
	}
}

/**
 * The data of the image
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tag_image_data( $item_type, $data, $atts = array() ) {
	$image_data = apply_filters( 'ditty_layout_tag_image_data', array(), $item_type, $data, $atts );
	if ( ! empty( $image_data ) ) {
		return $image_data;
	}
}