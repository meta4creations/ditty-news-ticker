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
 * Return a rendered tag
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_render_tag( $html, $class, $data, $atts = array(), $custom_wrapper = false, $prefix = '' ) {
	$link_data = apply_filters( 'ditty_layout_tag_link_data', array(), $data, $atts, $prefix );
	if ( ! empty( $link_data ) ) {
		$html = ditty_layout_render_tag_link( $link_data, $html, "{$class}__link", $data, $atts );
	}
	return ditty_layout_render_tag_wrapper( $html, $class, $atts, $custom_wrapper );
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




/**
 * The rendered image
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tag_image( $data, $atts, $custom_wrapper = false ) {
	if ( ! $image_data = ditty_layout_tag_image_data( $data, $atts ) ) {
		return false;
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
	$img = '<img ' . ditty_attr_to_html( $image_args ) . ' />';
	return ditty_layout_render_tag( $img, 'ditty-tag--image', $data, $atts, $custom_wrapper );
}

/**
 * The url of the image
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tag_image_url( $data, $atts = array(), $custom_wrapper = false ) {
	if ( ! $image_data = ditty_layout_tag_image_data( $data, $atts ) ) {
		return false;
	}
	$image_defaults = array(
		'src' 		=> '',
	);
	$image_args = shortcode_atts( $image_defaults, $image_data );
	return $image_args['src'];
}

/**
 * The data of the image
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tag_image_data( $data, $atts = array() ) {
	$image_data = apply_filters( 'ditty_layout_tag_image_data', array(), $data, $atts );
	if ( ! empty( $image_data ) ) {
		return $image_data;
	}
}

/**
 * Return an icon
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tag_icon( $data, $atts = array(), $custom_wrapper = false ) {
	if ( ! $icon = apply_filters( 'ditty_layout_tag_icon', false, $data, $atts ) ) {
		return false;
	}
	return ditty_layout_render_tag( $icon, 'ditty-tag--icon', $data, $atts, $custom_wrapper );
}

/**
 * The layout title
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tag_title( $data, $atts, $custom_wrapper = false ) {
	if ( ! $title = apply_filters( 'ditty_layout_tag_title', false, $data, $atts ) ) {
		return false;
	}	
	return ditty_layout_render_tag( $title, 'ditty-tag--title', $data, $atts, $custom_wrapper );
}

/**
 * The layout caption
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tag_caption( $data, $atts, $custom_wrapper = false ) {
	if ( ! $caption = apply_filters( 'ditty_layout_tag_caption', false, $data, $atts ) ) {
		return false;
	}	
	if ( 'true' == $atts['wpautop'] ) {
		$caption = wpautop( $caption );
	}
	return ditty_layout_render_tag( $caption, 'ditty-tag--caption', $data, $atts, $custom_wrapper );
}

/**
 * The layout date/time
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tag_time( $data, $atts, $custom_wrapper = false ) {
	if ( ! $timestamp = apply_filters( 'ditty_layout_tag_timestamp', false, $data, $atts ) ) {
		return false;
	}	
	if ( 'true' == $atts['ago'] ) {
		$time_ago = human_time_diff( $timestamp, current_time( 'timestamp', true ) );
		$html = sprintf( $atts['ago_string'], $time_ago );
	} else {
		$html = date( $atts['format'], $timestamp );
	}
	return ditty_layout_render_tag( $html, 'ditty-tag--time', $data, $atts, $custom_wrapper );
}

/**
 * The layout user name
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tag_user_name( $data, $atts, $custom_wrapper = false ) {
	if ( ! $user_name = apply_filters( 'ditty_layout_tag_user_name', false, $data, $atts ) ) {
		return false;
	}	
	return ditty_layout_render_tag( $user_name, 'ditty-tag--user_name', $data, $atts, $custom_wrapper );
}

/**
 * The layout user avatar
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tag_user_avatar( $data, $atts, $custom_wrapper = false ) {
	if ( ! $image_data = apply_filters( 'ditty_layout_tag_user_avatar_data', array(), $data, $atts ) ) {
		return false;
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
	$img = '<img ' . ditty_attr_to_html( $image_args ) . ' />';
	return ditty_layout_render_tag( $img, 'ditty-tag--user_avatar', $data, $atts, $custom_wrapper );
}

/**
 * The layout permalink
 *
 * @since    3.0
 * @var      html
*/	
function ditty_image_tag_permalink( $data, $atts, $custom_wrapper = false ) {
	if ( ! $permalink = apply_filters( 'ditty_layout_tag_permalink', false, $data, $atts ) ) {
		return false;
	}	
	return ditty_layout_render_tag( $permalink, 'ditty-tag--permalink', $data, $atts, $custom_wrapper );
}