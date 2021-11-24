<?php

/**
 * The layout date/time
 *
 * @since    3.0
 * @var      html
*/
function ditty_layout_tag_time( $item_type, $data, $atts, $custom_wrapper = false ) {
	if ( ! $timestamp = apply_filters( 'ditty_layout_tag_timestamp', false, $item_type, $data, $atts ) ) {
		return false;
	}	
	if ( 'true' == $atts['ago'] ) {
		$time_ago = human_time_diff( $timestamp, current_time( 'timestamp', true ) );
		$html = sprintf( $atts['ago_string'], $time_ago );
	} else {
		$html = date( $atts['format'], $timestamp );
	}
	return ditty_layout_render_tag( $html, 'ditty-item__time', $item_type, $data, $atts, $custom_wrapper );
}

/**
 * The layout user avatar
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tag_user_avatar( $item_type, $data, $atts, $custom_wrapper = false ) {
	if ( ! $image_data = apply_filters( 'ditty_layout_tag_user_avatar_data', array(), $item_type, $data, $atts ) ) {
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
	return ditty_layout_render_tag( $img, 'ditty-item__user_avatar', $item_type, $data, $atts, $custom_wrapper );
}

/**
 * Return all possible layout tags
 *
 * @since    3.0
 * @var      html
*/	
function ditty_layout_tags( $item_type = false ) {	
	$tags = array(
		'caption' => array(
			'tag' 				=> 'caption',
			'description' => __( 'Render the item caption.', 'ditty-news-ticker' ),
			//'func'    		=> 'ditty_layout_tag_caption',
			'atts'				=> array(
				'wrapper' 		=> 'div',
				'wpautop'			=> '',
				'before'			=> '',
				'after'				=> '',
				'link'				=> '',
				'link_target' => '_blank',
				'link_rel'		=> '',
				'class'				=> '',
			),
		),
		'content' => array(
			'tag' 				=> 'content',
			'description' => __( 'Render the item content.', 'ditty-news-ticker' ),
			//'func'    		=> 'ditty_layout_default_tag_content',
			'atts'				=> array(
				'wrapper' => 'div',
				'wpautop' => false,
				'before'	=> '',
				'after'		=> '',
				'class'		=> '',
			),
		),
		'icon' => array(
			'tag' 				=> 'icon',
			'description' => __( 'Render the item icon.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> 'div',
				'before'			=> '',
				'after'				=> '',
				'link'				=> '',
				'link_target' => '_blank',
				'link_rel'		=> '',
				'class'				=> '',
			),
		),
		'image' => array(
			'tag' 				=> 'image',
			'description' => __( 'Render the item image.', 'ditty-news-ticker' ),
			'func'    		=> 'ditty_layout_tag_image',
			'atts'				=> array(
				'wrapper'			=> 'div',
				'before'			=> '',
				'after'				=> '',
				'width'				=> '',
				'height'			=> '',
				'fit'					=> '',
				'link'				=> '',
				'link_target' => '_blank',
				'link_rel'		=> '',
				'class'				=> '',
			),
		),
		'image_url' => array(
			'tag' 				=> 'image_url',
			'description' => __( 'Render the item image url.', 'ditty-news-ticker' ),
			'func'    		=> 'ditty_layout_tag_image_url',
		),
		'permalink' => array(
			'tag' 				=> 'permalink',
			'description' => __( 'Render the item permalink.', 'ditty-news-ticker' ),
			'func'    		=> 'ditty_image_tag_permalink',
		),
		'time' => array(
			'tag' 				=> 'time',
			'description' => __( 'Render the item date/time.', 'ditty-news-ticker' ),
			'func'    		=> 'ditty_layout_tag_time',
			'atts'				=> array(
				'wrapper' 		=> 'div',
				'format' 			=> get_option( 'date_format' ),
				'ago'					=> '',
				'ago_string' 	=> __( '%s ago', 'ditty-news-ticker' ),
				'before'			=> '',
				'after'				=> '',
				'link'				=> '',
				'link_target' => '_blank',
				'link_rel'		=> '',
				'class'				=> '',
			),
		),
		'title' => array(
			'tag' 				=> 'title',
			'description' => __( 'Render the item title.', 'ditty-news-ticker' ),
			'func'    		=> 'ditty_layout_tag_title',
			'atts'				=> array(
				'wrapper' 		=> 'h3',
				'before'			=> '',
				'after'				=> '',
				'link'				=> '',
				'link_target' => '_blank',
				'link_rel'		=> '',
				'class'				=> '',
			),
		),
		'user_avatar' => array(
			'tag' 				=> 'user_avatar',
			'description' => __( "Render the item's user avatar", 'ditty-news-ticker' ),
			'func'    		=> 'ditty_layout_tag_user_avatar',
			'atts'				=> array(
				'wrapper'			=> 'div',
				'before'			=> '',
				'after'				=> '',
				'width'				=> '',
				'height'			=> '',
				'fit'					=> '',
				'link'				=> 'user',
				'link_target' => '_blank',
				'link_rel'		=> '',
				'class'				=> '',
			),
		),
		'user_name' => array(
			'tag' 				=> 'user_name',
			'description' => __( "Render the item's user name", 'ditty-news-ticker' ),
			'func'    		=> 'ditty_layout_tag_user_name',
			'atts'				=> array(
				'wrapper' 		=> 'div',
				'before'			=> '',
				'after'				=> '',
				'link'				=> 'user', // post, user
				'link_target' => '_blank',
				'link_rel'		=> '',
				'class'				=> '',
			),
		),
	);
	return apply_filters( 'ditty_layout_tags', $tags, $item_type );
}