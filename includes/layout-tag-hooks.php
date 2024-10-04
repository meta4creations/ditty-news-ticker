<?php
add_filter( 'ditty_layout_tag_atts', 'ditty_modify_layout_tag_atts', 10, 4 );
add_filter( 'ditty_layout_tag_author_avatar', 'ditty_init_layout_tag_author_avatar', 10, 4 );
add_filter( 'ditty_layout_tag_author_banner', 'ditty_init_layout_tag_author_banner', 10, 4 );
add_filter( 'ditty_layout_tag_categories', 'ditty_init_layout_tag_categories', 10, 4 );
add_filter( 'ditty_layout_tag_excerpt', 'ditty_init_layout_tag_excerpt', 10, 4 );
add_filter( 'ditty_layout_tag_image', 'ditty_init_layout_tag_image', 10, 4 );
add_filter( 'ditty_layout_tag_image_url', 'ditty_init_layout_tag_image_url', 10, 4 );
add_filter( 'ditty_layout_tag_media', 'ditty_init_layout_tag_media', 10, 4 );
add_filter( 'ditty_layout_tag_terms', 'ditty_init_layout_tag_terms', 10, 4 );
add_filter( 'ditty_layout_tag_time', 'ditty_init_layout_tag_time', 10, 4 );

/**
 * Modify layout attributes
 *
 * @since    3.0
 * @var      html
*/
function ditty_modify_layout_tag_atts( $atts, $tag, $item_type, $data ) {
	switch( $tag ) {
		case 'custom_field':
			if ( isset( $atts['id'] ) && '' != $atts['id'] ) {
				$atts['class'] = "{$atts['class']} ditty-item__custom_field--{$atts['id']}";
			}
			break;
		case 'terms':
			if ( isset( $atts['term'] ) && '' != $atts['term'] ) {
				$atts['class'] = "{$atts['class']} ditty-item__terms--{$atts['term']}";
			}
			break;
		default:
			break;
	}
	return $atts;	
}

/**
 * Modify the layout author_avatar
 *
 * @since    3.0
 * @var      html
*/
function ditty_init_layout_tag_author_avatar( $author_avatar, $item_type, $data, $atts ) {
	if ( ! $author_avatar_data = ditty_layout_tag_author_avatar_data( $item_type, $data, $atts ) ) {
		return $author_avatar;
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
	$author_avatar_defaults = array(
		'src' 		=> '',
		'width' 	=> '',
		'height' 	=> '',
		'alt' 		=> '',
		'style'		=> ( '' != $style ) ? $style : false,
	);
	$author_avatar_args = shortcode_atts( $author_avatar_defaults, $author_avatar_data );
	$author_avatar = '<img ' . ditty_attr_to_html( $author_avatar_args ) . ' />';
	return $author_avatar;	
}

/**
 * Modify the layout author_banner
 *
 * @since    3.0
 * @var      html
*/
function ditty_init_layout_tag_author_banner( $author_banner, $item_type, $data, $atts ) {
	if ( ! $author_banner_data = ditty_layout_tag_author_banner_data( $item_type, $data, $atts ) ) {
		return $author_banner;
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
	$author_banner_defaults = array(
		'src' 		=> '',
		'width' 	=> '',
		'height' 	=> '',
		'alt' 		=> '',
		'style'		=> ( '' != $style ) ? $style : false,
	);
	$author_banner_args = shortcode_atts( $author_banner_defaults, $author_banner_data );
	$author_banner = '<img ' . ditty_attr_to_html( $author_banner_args ) . ' />';
	return $author_banner;	
}

/**
 * Modify the layout categories
 *
 * @since    3.0
 * @var      html
*/
function ditty_init_layout_tag_categories( $categories, $item_type, $data, $atts ) {
	if ( ! $category_data = apply_filters( 'ditty_layout_tag_category_data', array(), $item_type, $data, $atts ) ) {
		return $categories;
	}	
	$ignored = isset( $data['ignore'] ) ? array_map( 'trim', explode( ',', $data['ignore'] ) ) : array();
	if ( is_array( $category_data ) && count( $category_data ) > 0 ) {
		$categories_array = array();
		foreach ( $category_data as $i => $category ) {
			$category_label = $category['label'];
			if( in_array( $category_label, $ignored ) ) {
				continue;
			}
			if ( isset( $category['link'] ) ) {
				$target = isset( $atts['link_target'] ) ? esc_attr( $atts['link_target'] ) : '_self';
				$category_label = '<a href="' . esc_url_raw( $category['link'] ) . '" target="' . $target . '">' . $category_label . '</a>';
			}
			$categories_array[] = '<span class="ditty-rss__categories__item">' . $category_label . '</span>';
		}
		$separator = isset( $atts['separator'] ) ? sanitize_text_field( $atts['separator'] ) : ', ';
		$categories = implode( $separator, $categories_array );
	}
	return $categories;
}

/**
 * Modify the layout excerpt
 *
 * @since    3.0.12
 * @var      html
*/
function ditty_init_layout_tag_excerpt( $excerpt, $item_type, $data, $atts ) {
	if ( ! $excerpt_data = apply_filters( 'ditty_layout_tag_excerpt_data', '', $item_type, $data, $atts ) ) {
		return $excerpt;
	}	
	$more = '';
	if ( isset( $atts['more'] ) && '' != $atts['more'] ) {
		$more 				= $atts['more'];
		$more_before 	= isset( $atts['more_before'] ) ? sanitize_text_field( $atts['more_before'] ) : '';
		$more_after 	= isset( $atts['more_after'] ) 	? sanitize_text_field( $atts['more_after'] ) 	: '';
		$link_data = apply_filters( 'ditty_layout_tag_link_data', array(), $item_type, $data, $atts, 'more_' );
		if ( ! empty( $link_data ) ) {
			$more = ditty_layout_render_tag_link( $link_data, $more, 'ditty-item__excerpt__more__link', $data, $atts, 'more_' );
		}
		$more = "<span class='ditty-item__excerpt__more'>{$more_before}{$more}{$more_after}</span>";
	}
	$excerpt_length = ( isset( $atts['excerpt_length'] ) && 0 != intval( $atts['excerpt_length'] ) ) ? intval( $atts['excerpt_length'] ) : 200;
	$excerpt = '<span class="ditty-item__excerpt__content">' . wp_html_excerpt( $excerpt_data, $excerpt_length, $more ) . '</span>';
	return $excerpt;
}

/**
 * Modify the layout content
 *
 * @since    3.0.18
 * @var      html
*/
// function ditty_init_layout_tag_content( $content, $item_type, $data, $atts ) {
//   if ( isset( $atts['content_display'] ) && 'excerpt' == $atts['content_display'] ) {
//     $atts['excerpt_length'] = isset( $atts['excerpt_length'] ) 	? intval( $atts['excerpt_length'] ) 					  : 200;
//     $atts['more'] 					= isset( $atts['more'] ) 						? wp_filter_nohtml_kses( $atts['more'] ) 				: false;
//     $atts['more_link']			= isset( $atts['more_link'] ) 			? esc_attr( $atts['more_link'] ) 							  : false;
//     $atts['more_before'] 		= isset( $atts['more_before'] ) 		? wp_filter_nohtml_kses( $atts['more_before'] ) : false;
//     $atts['more_after'] 		= isset( $atts['more_after'] ) 			? wp_filter_nohtml_kses( $atts['more_after'] ) 	: false;
//     $content = ditty_init_layout_tag_excerpt( $content, $item_type, $data, $atts );
//   }
//   return $content;
// }
// add_filter( 'ditty_layout_tag_content', 'ditty_init_layout_tag_content', 99, 4 );

/**
 * Modify the layout image
 *
 * @since    3.1.18
 * @var      html
*/
function ditty_init_layout_tag_image( $image, $item_type, $data, $atts ) {
  $image_data = ditty_layout_tag_image_data( $item_type, $data, $atts );
  if ( $tag_image = ditty_layout_tag_image( $image_data, $data, $atts ) ) {
    return $tag_image;
  }
  return $image;
}

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

/**
 * Modify the layout media
 *
 * @since    3.1.47
 * @var      html
*/
function ditty_init_layout_tag_media( $media, $item_type, $data, $atts ) {
  $media_data = ditty_layout_tag_media_data( $item_type, $data, $atts );
  if ( ! is_array( $media_data ) || empty( $media_data ) ) {
    return false;
  }
  switch( $media_data['type'] ) {
    case 'gallery':
      return ditty_layout_tag_gallery( $media_data, $data, $atts );
    case 'image':
      return ditty_layout_tag_image( $media_data, $data, $atts );
    case 'video':
      return ditty_layout_tag_video( $media_data, $data, $atts );
    default:
      break;
  }
}

/**
 * Modify the layout terms
 *
 * @since    3.0
 * @var      html
*/
function ditty_init_layout_tag_terms( $terms, $item_type, $data, $atts ) {
	if ( ! $term_data = apply_filters( 'ditty_layout_tag_term_data', array(), $item_type, $data, $atts ) ) {
		return $terms;
	}	
	$ignored = isset( $data['ignore'] ) ? array_map( 'trim', explode( ',', $data['ignore'] ) ) : array();
	if ( is_array( $term_data ) && count( $term_data ) > 0 ) {
		$terms_array = array();
		foreach ( $term_data as $i => $term ) {
			$term_label = $term['label'];
			if( in_array( $term_label, $ignored ) ) {
				continue;
			}
			if ( isset( $term['link'] ) ) {
				$target = isset( $atts['link_target'] ) ? esc_attr( $atts['link_target'] ) : '_self';
				$term_label = '<a href="' . esc_url_raw( $term['link'] ) . '" target="' . $target . '">' . $term_label . '</a>';
			}
			$terms_array[] = '<span class="ditty-rss__term__item">' . $term_label . '</span>';
		}
		$separator = isset( $atts['separator'] ) ? sanitize_text_field( $atts['separator'] ) : ', ';
		$terms = implode( $separator, $terms_array );
	}
	return $terms;
}

/**
 * Modify the layout date/time
 *
 * @since    3.0
 * @var      html
*/
function ditty_init_layout_tag_time( $time, $item_type, $data, $atts ) {
	if ( ! $timestamp = apply_filters( 'ditty_layout_tag_timestamp', false, $item_type, $data, $atts ) ) {
		return $time;
	}	
	if ( 'true' == strval( $atts['ago'] ) ) {
		$time_ago = human_time_diff( $timestamp, current_time( 'timestamp', true ) );
		$time = sprintf( $atts['ago_string'], $time_ago );
	} else {
		$time = date( $atts['format'], $timestamp );
	}
	return $time;	
}