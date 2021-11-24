<?php

/**
 * Return the layout tags list
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tags_list( $item_type = false ) {
	$tags = ditty_layout_tags( $item_type );
	$tags_list = '';
	if ( is_array( $tags ) && count( $tags ) > 0 ) {
		$tags_list .= '<ul class="ditty-editor-options__tags__list ditty-editor-options__tags__list--' . $item_type . '">';
		foreach ( $tags as $data ) {
			$atts = array(
				'class' => 'ditty-editor-options__tag protip',
				'data-pt-title' => $data['description'],
				'data-atts' => ( isset( $data['atts'] ) ) ? htmlentities( json_encode( $data['atts'] ) ) : false,
			);
			$tags_list .= '<li ' . ditty_attr_to_html( $atts ) . '>{' . $data['tag'] . '}</li>';
		}
		$tags_list .= '</ul>';
	}
	return $tags_list;
}

/**
 * Return the layout selectors list
 *
 * @since    3.0
 * @var      html
*/
function get_css_selectors_list( $item_type = false ) {
	$tags = ditty_layout_tags( $item_type );
	$selectors_list = '';
	if ( is_array( $tags ) && count( $tags ) > 0 ) {
		$selectors_list .= '<ul class="ditty-editor-options__tags__list ditty-editor-options__tags__list--' . $item_type . '">';
		foreach ( $tags as $data ) {
			$selectors_list .= '<li class="ditty-editor-options__tag">.ditty-item__' . $data['tag'] . '</li>';
		}
		$selectors_list .= '</ul>';
	}
	return $selectors_list;
}

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
		'target' 	=> isset( $data["{$prefix}link_target"] ) ? esc_attr( $data["{$prefix}link_target"] ) : '_blank',
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
function ditty_layout_render_tag( $html, $class, $item_type, $data, $atts = array(), $custom_wrapper = false, $prefix = '' ) {
	$link_data = apply_filters( 'ditty_layout_tag_link_data', array(), $item_type, $data, $atts, $prefix );
	if ( ! empty( $link_data ) ) {
		$html = ditty_layout_render_tag_link( $link_data, $html, "{$class}__link", $data, $atts, $prefix );
	}
	return ditty_layout_render_tag_wrapper( $html, $class, $atts, $custom_wrapper );
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