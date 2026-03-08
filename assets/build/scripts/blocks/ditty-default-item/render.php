<?php
/**
 * Default Item Block - Server-Side Render
 *
 * Renders a single default text item, optionally using a ditty_layout template.
 *
 * @package Ditty
 * @subpackage Blocks
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load v4 helpers
require_once DITTY_DIR . 'v4/helpers.php';

$item_content  = isset( $attributes['content'] ) ? $attributes['content'] : '';
$link_url      = isset( $attributes['link_url'] ) ? $attributes['link_url'] : '';
$link_title    = isset( $attributes['link_title'] ) ? $attributes['link_title'] : '';
$link_target   = isset( $attributes['link_target'] ) ? $attributes['link_target'] : '_self';
$link_nofollow = isset( $attributes['link_nofollow'] ) ? $attributes['link_nofollow'] : '';
$layout_id     = isset( $attributes['layout'] ) ? intval( $attributes['layout'] ) : 0;

if ( empty( $item_content ) ) {
	return;
}

$use_layout = ( $layout_id > 0 );

if ( $use_layout ) {
	if ( ! class_exists( 'Ditty_V4_Layout_Renderer' ) ) {
		require_once DITTY_DIR . 'v4/class-ditty-v4-layout-renderer.php';
	}
	$renderer = new Ditty_V4_Layout_Renderer();

	$data = array(
		'content'       => $item_content,
		'link_url'      => $link_url,
		'link_title'    => $link_title,
		'link_target'   => $link_target,
		'link_nofollow' => $link_nofollow,
	);

	$result = $renderer->render_data_with_layout( $data, $layout_id, 'default' );

	if ( ! empty( $result['html'] ) ) {
		$wrapper_class = 'wp-block-ditty-display-item ditty-item ditty-layout--' . esc_attr( $result['layout_id'] );

		Ditty\V4\handle_layout_css( $layout_id, $result['css'] );

		printf(
			'<div class="%1$s"><div class="ditty-item__elements">%2$s</div></div>',
			esc_attr( $wrapper_class ),
			$result['html']
		);
	}
} else {
	$rendered_content = do_shortcode( stripslashes( $item_content ) );

	if ( ! empty( $link_url ) ) {
		$rel = $link_nofollow ? 'nofollow' : '';
		$rendered_content = sprintf(
			'<a href="%2$s" class="ditty-item__link" target="%3$s" rel="%4$s" title="%5$s">%1$s</a>',
			$rendered_content,
			esc_url( $link_url ),
			esc_attr( $link_target ),
			esc_attr( $rel ),
			esc_attr( $link_title )
		);
	}

	printf(
		'<div class="wp-block-ditty-display-item ditty-item"><div class="ditty-item__elements">%s</div></div>',
		$rendered_content
	);
}
