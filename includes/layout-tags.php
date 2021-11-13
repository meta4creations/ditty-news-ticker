<?php
	
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