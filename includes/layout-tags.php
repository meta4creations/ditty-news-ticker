<?php

/**
 * Return a rendered link
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_render_tag_link( $link, $html, $class, $data, $atts ) {
	$link_defaults = array(
		'url'					=> '',
		'target'			=> '',
		'rel'					=> '',
		'title'				=> '',
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
 * @since    3.0
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
	
	if ( isset( $args['wrapper'] ) && false != boolval( $args['wrapper'] ) ) {
		if ( $custom_classes = $args['class'] ) {
			$class .= ' ' . $custom_classes;
		}
		$html = sprintf( '<%4$s class="%5$s">%2$s%1$s%3$s</%4$s>', $html, $before, $after, $args['wrapper'], esc_attr( $class ) );
	}
	if ( $custom_wrapper ) {
		$html = sprintf( $custom_wrapper, $html );
	}
	return $html;
}

/**
 * The custom item content
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_default_tag_content( $data, $atts, $custom_wrapper = false ) {
	if ( ! isset( $data['content'] ) ) {
		return false;
	}
	$html = $data['content'];
	
	if ( 'true' == $atts['wpautop'] ) {
		$html = wpautop( $html );
	}
	$url = ( isset( $data['link_url'] ) && '' != $data['link_url'] ) ? $data['link_url'] : false;

	if ( $url ) {
		$target = isset( $data['link_target'] ) ? $data['link_target'] : '_self';
		$rel = isset( $data['link_nofollow'] ) ? 'nofollow' : '';
		$title = isset( $data['link_title'] ) ? $data['link_title'] : '';
		$html = sprintf( '<a href="%2$s" class="ditty-rss__author__link" target="%3$s" rel="%4$s" title="%5$s">%1$s</a>', $html, $url, $target, $rel, $title );
	}
	if ( $atts['wrapper'] && 'false' !== $atts['wrapper'] ) {
		$class = 'ditty-default__content';
		if ( $custom_classes = $atts['class'] ) {
			$class .= ' ' . $custom_classes;
		}
		$html = sprintf( '<%4$s class="%5$s">%2$s%1$s%3$s</%4$s>', $html, $atts['before'], $atts['after'], $atts['wrapper'], esc_attr( $class ) );
	}
	if ( $custom_wrapper ) {
		$html = sprintf( $custom_wrapper, $html );
	}
	return $html;
}

/**
 * The custom item content
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_wp_editor_tag_content( $data, $atts, $custom_wrapper = false ) {
	if ( ! isset( $data['content'] ) ) {
		return false;
	}
	$html = $data['content'];
	
	if ( $atts['wrapper'] && 'false' !== $atts['wrapper'] ) {
		$class = 'ditty-wp_editor__content';
		if ( $custom_classes = $atts['class'] ) {
			$class .= ' ' . $custom_classes;
		}
		$html = sprintf( '<%4$s class="%5$s">%2$s%1$s%3$s</%4$s>', $html, $atts['before'], $atts['after'], $atts['wrapper'], esc_attr( $class ) );
	}
	if ( $custom_wrapper ) {
		$html = sprintf( $custom_wrapper, $html );
	}
	return $html;
}