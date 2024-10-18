<?php

/**
 * Return item meta
 *
 * @since    3.0.13
 * @var      html
*/
function ditty_layout_item_meta( $data, $key = false ) {
	if ( ! is_array( $data ) || ! isset( $data['item_meta'] ) ) {
		return false;
	}
	if ( $key ) {
		if ( ! isset( $data['item_meta'][$key] ) ) {
			return false;
		}
		return $data['item_meta'][$key];
	}
	return $data['item_meta'];
}

/**
 * Return a rendered link
 *
 * @since    3.1.47
 * @var      html
*/
function ditty_layout_render_tag_link( $link, $html, $class, $data, $atts, $prefix = '' ) {
	$link_defaults = array(
		'url'					=> '',
		'title'				=> '',
		'target' 	=> ( isset( $atts["{$prefix}link_target"] ) && '' != $atts["{$prefix}link_target"] ) ? esc_attr( $atts["{$prefix}link_target"] ) : '_self',
		'rel'			=> isset( $atts["{$prefix}link_rel"] ) ? esc_attr( $atts["{$prefix}link_rel"] ) : '',
	);
	if ( isset( $data['link_target'] ) && '' != $data['link_target'] ) {
		$link_defaults['target'] = $data['link_target'];
	}
	if ( isset( $data['link_nofollow'] ) && '1' == $data['link_nofollow'] ) {
		$link_defaults['rel'] = 'nofollow';
	}
	$link_args = shortcode_atts( $link_defaults, $link );

	$defaults = array(
		'link_before'	=> '',
		'link_after'	=> '',
	);
	$args = shortcode_atts( $defaults, $atts );
	
	$link_before = ( '' != $args['link_before'] ) ? "<span class='{$class}__before'>" . wp_filter_nohtml_kses( $args['link_before'] ) . '</span>' : '';
	$link_after = ( '' != $args['link_after'] ) ? "<span class='{$class}__after'>" . wp_filter_nohtml_kses( $args['link_after'] ) . '</span>' : '';
	
	$html = sprintf( '<a href="%4$s" class="%5$s" target="%6$s" rel="%7$s" title="%8$s">%2$s%1$s%3$s</a>', $html, $link_before, $link_after, esc_url( $link_args['url'] ), esc_attr( $class ), esc_attr( $link_args['target'] ), esc_attr( $link_args['rel'] ), esc_attr( $link_args['title'] ) );
	
	return $html;
}

/**
 * Return a rendered wrapper
 *
 * @since    3.0.35
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

	$before = ( '' != $args['before'] ) ? "<span class='{$class}__before'>" . wp_filter_nohtml_kses( $args['before'] ) . '</span>' : '';
	$after = ( '' != $args['after'] ) ? "<span class='{$class}__after'>" . wp_filter_nohtml_kses( $args['after'] ) . '</span>' : '';
	
	if ( isset( $args['wrapper'] ) && 'none' != strval( $args['wrapper'] ) && 'false' != strval( $args['wrapper'] ) && '' != strval( $args['wrapper'] ) ) {
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
 * Return a gallery object
 *
 * @since    3.1.47
 * @var      html
*/
function ditty_layout_tag_gallery( $media_data, $data, $atts ) {
  global $ditty_sliders;
  if ( empty( $ditty_sliders ) ) {
    $ditty_sliders = [];
  }
  if ( isset( $media_data['items'] ) && is_array( $media_data['items'] ) && count( $media_data['items'] ) > 0 ) {
    $items = [];
    $atts['fit'] = 'cover';
    foreach ( $media_data['items'] as $item ) {
      switch( $item['type'] ) {
        case 'image':
          $items[] = '<div class="keen-slider__slide ditty-gallery-item">' . ditty_layout_tag_image( $item, $data, $atts ) . '</div>';
          break;
        case 'video':
          break;
      }
    }

    $args = [
      'id' => uniqid( 'ditty-gallery-slider-' ),
      'selector' => '.ditty-gallery-item',
      'class' => 'ditty-gallery-slider',
      'settings' => [
        'loop' => true,
        'bullets' => true,
        'bulletsColor' => 'rgba(255,255,255,.5)',
        'bulletsColorActive' => '#FFF',
        'bulletsOverlay' => true,
        'bulletsSize' => '6px',
        'bulletsSpacing' => '3px',
      ]
    ];

    $ditty_sliders[] = $args;
    if ( function_exists( 'ditty_slider' ) ) {
      return ditty_slider( $items, $args );
    }
  }
}

/**
 * Return an image
 *
 * @since    3.1.47
 * @var      html
*/
function ditty_layout_tag_image( $image_data, $data = [], $atts = [] ) {
	if ( ! $image_data || ( is_array( $image_data ) && ( ! $image_data['src'] ) ) ) {
    $default_src = ( isset( $atts['default_src'] ) ) ? $atts['default_src'] : false;
    if ( $default_src ) {
      $image_data = is_array( $image_data) ? $image_data : [];
      $image_data['src'] = $default_src;
    } else {
      return false;
    }
	}

	$defaults = array(
		'width' 	      => '',
		'height' 	      => '',
		'fit' 		      => '',
    'aspect_ratio'  => '',
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
  if ( isset( $args['aspect_ratio'] ) && '' !=  $args['aspect_ratio'] ) {
		$style .= 'aspect-ratio:' . $args['aspect_ratio'] . ';';
	}
	$image_defaults = array(
		'src' 		=> '',
		'width' 	=> '',
		'height' 	=> '',
		'alt' 		=> '',
		'style'		=> ( '' != $style ) ? $style : false,
	);
	$image_args = shortcode_atts( $image_defaults, $image_data );
	if ( '' == $image_args['width'] && '' == $image_args['height'] ) {
		if ( $image_dimensions = ditty_get_image_dimensions( $image_args['src'] ) ) {
      $image_args['width'] = $image_dimensions['width'];
      $image_args['height'] = $image_dimensions['height'];
    }
	}
	$image = '<img ' . ditty_attr_to_html( $image_args ) . ' />';
	return $image;
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

/**
 * The data of the media
 *
 * @since    3.1.47
 * @var      html
*/
function ditty_layout_tag_media_data( $item_type, $data, $atts = array() ) {
	$media_data = apply_filters( 'ditty_layout_tag_media_data', array(), $item_type, $data, $atts );
	if ( ! empty( $media_data ) ) {
		return $media_data;
	}
}

/**
 * Return a video
 *
 * @since    3.1.47
 * @var      html
*/
function ditty_layout_tag_video( $video_data, $data, $atts ) {
	if ( ! $video_data || ( is_array( $video_data ) && ( ! $video_data['src'] ) ) ) {
    $default_src = ( isset( $atts['default_src'] ) ) ? $atts['default_src'] : false;
    if ( $default_src ) {
      $video_data = is_array( $video_data) ? $video_data : [];
      $video_data['src'] = $default_src;
    } else {
      return false;
    }
	}

	$defaults = array(
		'width' 	          => '',
		'height' 	          => '',
    'aspect_ratio'      => '',
    'video_autoplay'    => 'yes',
    'video_controls'    => 'no',
    'video_loop'        => 'yes',
    'video_playsinline' => 'yes',
    'video_muted'       => 'yes',
	);
	$args = shortcode_atts( $defaults, $atts );
	$style = '';
	if ( '' !=  $args['width'] ) {
		$style .= 'width:' . $args['width'] . ';';
	}
	if ( '' !=  $args['height'] ) {
		$style .= 'height:' . $args['height'] . ';';
	}
  if ( isset( $args['aspect_ratio'] ) && '' !=  $args['aspect_ratio'] ) {
		$style .= 'aspect-ratio:' . $args['aspect_ratio'] . ';';
	}
	$video_defaults = array(
		'src' 		    => '',
    'poster' 		  => '',
    'autoplay'    => ( 'yes' == $args['video_autoplay'] ),
    'controls'    => ( 'yes' == $args['video_controls'] ),
    'loop'        => ( 'yes' == $args['video_loop'] ),
    'playsinline' => ( 'yes' == $args['video_playsinline'] ),
    'muted'       => ( 'yes' == $args['video_muted'] ),
		'width' 	    => $args['width'],
		'height' 	    => $args['height'],
		'style'		    => ( '' != $style ) ? $style : false,
	);
	$video_args = shortcode_atts( $video_defaults, $video_data );
	// if ( '' == $video_args['width'] && '' == $video_args['height'] ) {
	// 	if ( $image_dimensions = ditty_get_image_dimensions( $image_args['src'] ) ) {
  //     $image_args['width'] = $image_dimensions['width'];
  //     $image_args['height'] = $image_dimensions['height'];
  //   }
	// }
	$video = '<video ' . ditty_attr_to_html( $video_args ) . ' />';
	return $video;
}