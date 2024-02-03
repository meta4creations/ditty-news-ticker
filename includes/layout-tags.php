<?php
/**
 * Return the layout link options
 *
 * @since    3.1
 * @var      html
*/	
function ditty_layout_link_options( $item_type = false ) {
	$link_options = apply_filters( 'ditty_layout_link_options', [], $item_type );
	if ( ! is_array( $link_options ) ) {
		$link_options = ['true' => 'true', 'none' => 'none'];
	}
	if ( ! isset( $link_options['none'] ) ) {
		$link_options['none'] = 'none';
	}
	return $link_options;
}

/**
 * Return all possible layout tags
 *
 * @since    3.1
 * @var      html
*/	
function ditty_layout_tags( $item_type = false, $item_value = false ) {	

	// Get the link options
	$link_options = ditty_layout_link_options( $item_type );

	$after_settings = Ditty()->layouts->tag_attribute_default_settings( 'after' );
	$before_settings = Ditty()->layouts->tag_attribute_default_settings( 'before' );
	$class_settings = Ditty()->layouts->tag_attribute_default_settings( 'class' );
	$excerpt_length_settings = Ditty()->layouts->tag_attribute_default_settings( 'excerpt_length', 200 );
	$fit_settings = Ditty()->layouts->tag_attribute_default_settings( 'fit' );
	$height_settings = Ditty()->layouts->tag_attribute_default_settings( 'height' );
	$link_settings = Ditty()->layouts->tag_attribute_default_settings( 'link', 'none', $link_options );
	$link_after_settings = Ditty()->layouts->tag_attribute_default_settings( 'link_after' );
	$link_before_settings = Ditty()->layouts->tag_attribute_default_settings( 'link_before' );
	$link_rel_settings = Ditty()->layouts->tag_attribute_default_settings( 'link_rel' );
	$link_target_settings = Ditty()->layouts->tag_attribute_default_settings( 'link_target' );
	$more_settings = Ditty()->layouts->tag_attribute_default_settings( 'more', '...' );
	$more_before_settings =  Ditty()->layouts->tag_attribute_default_settings( 'more_before' );
	$more_after_settings =  Ditty()->layouts->tag_attribute_default_settings( 'more_after' );
	$more_link_settings =  Ditty()->layouts->tag_attribute_default_settings( 'more_link', 'true', $link_options );
	$more_link_target_settings = Ditty()->layouts->tag_attribute_default_settings( 'more_link_target' );
	$more_link_rel_settings = Ditty()->layouts->tag_attribute_default_settings( 'more_link_rel' );
	$width_settings = Ditty()->layouts->tag_attribute_default_settings( 'width' );
	$wpautop_settings = Ditty()->layouts->tag_attribute_default_settings( 'wpautop' );
	$wrapper_settings = Ditty()->layouts->tag_attribute_default_settings( 'wrapper' );

	$tags = array(
		'author_avatar' => array(
			'tag' 				=> 'author_avatar',
			'description' => __( "Render the item's author avatar", 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper'			=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'width'				=> $width_settings,
				'height'			=> $height_settings,
				'fit'					=> $fit_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'author_banner' => array(
			'tag' 				=> 'author_banner',
			'description' => __( "Render the item's author banner", 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper'			=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'width'				=> $width_settings,
				'height'			=> $height_settings,
				'fit'					=> $fit_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'author_bio' => array(
			'tag' 				=> 'author_bio',
			'description' => __( "Render the item's author biography", 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'author_name' => array(
			'tag' 				=> 'author_name',
			'description' => __( "Render the item's author name", 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'author_screen_name' => array(
			'tag' 				=> 'author_screen_name',
			'description' => __( "Render the item's author screen name", 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'caption' => array(
			'tag' 				=> 'caption',
			'description' => __( 'Render the item caption.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'wpautop'			=> $wpautop_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'categories' => array(
			'tag' 				=> 'categories',
			'description' => __( 'Render the item categories', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link_target' => $link_target_settings,
				'separator'		=> Ditty()->layouts->tag_attribute_default_settings( 'separator', ', ' ),
				'class'				=> $class_settings,
			),
		),
		'content' => array(
			'tag' 				=> 'content',
			'description' => __( 'Render the item content.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper'						=> $wrapper_settings,
				'before'						=> $before_settings,
				'after'							=> $after_settings,
				'class'							=> $class_settings,
				'content_display'		=> Ditty()->layouts->tag_attribute_default_settings( 'content_display', 'full' ),
				'excerpt_length'		=> $excerpt_length_settings,
				'more'							=> $more_settings,
				'more_before'				=> $more_before_settings,
				'more_after'				=> $more_after_settings,
				'more_link'					=> $more_link_settings,
				'more_link_target' 	=> $more_link_target_settings,
				'more_link_rel'			=> $more_link_rel_settings,
				'strip_images'			=> [
					'type' => "select",
					'id' =>  "strip_images",
					'options' => [
						'no',
						'yes',
					],
					'help' =>  __(
						"Remove all images from the content.",
						"ditty-news-ticker"
					),
					'std' => 'no',
				],
			),
		),
		'custom_field' => array(
			'tag' 				=> 'custom_field',
			'description' => __( 'Render a custom field for the item', 'ditty-news-ticker' ),
			'atts'				=> array(
				'id'			=> '',
				'wrapper' => $wrapper_settings,
				'before'	=> $before_settings,
				'after'		=> $after_settings,
				'class'		=> $class_settings,
			),
		),
		'excerpt' => array(
			'tag' 				=> 'excerpt',
			'description' => __( 'Render the item excerpt.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 					=> $wrapper_settings,
				'wpautop' 					=> $wpautop_settings,
				'before'						=> $before_settings,
				'after'							=> $after_settings,
				'excerpt_length'		=> $excerpt_length_settings,
				'more'							=> $more_settings,
				'more_before'				=> $more_before_settings,
				'more_after'				=> $more_after_settings,
				'more_link'					=> $more_link_settings,
				'more_link_target' 	=> $more_link_target_settings,
				'more_link_rel'			=> $more_link_rel_settings,
				'class'							=> $class_settings,
			),
		),
		'icon' => array(
			'tag' 				=> 'icon',
			'description' => __( 'Render the item icon.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'image' => array(
			'tag' 				=> 'image',
			'description' => __( 'Render the item image.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper'			=> $wrapper_settings,
        'default_src' => [
					'type'  => 'text',
					'id'    =>  'default_src',
					'help'  =>  __(
						'Add a default image source if one does not exist for the item.',
						'ditty-news-ticker'
					),
				],
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'width'				=> $width_settings,
				'height'			=> $height_settings,
				'fit'					=> $fit_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'image_url' => array(
			'tag' 				=> 'image_url',
			'description' => __( 'Render the item image url.', 'ditty-news-ticker' ),
		),
		'permalink' => array(
			'tag' 				=> 'permalink',
			'description' => __( 'Render the item permalink.', 'ditty-news-ticker' ),
		),
		'source' => array(
			'tag' 				=> 'source',
			'description' => __( 'Render the item source.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'terms' => array(
			'tag' 				=> 'terms',
			'description' => __( 'Render the item terms', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'taxonomy'		=> [
					'type' => "text",
					'id' =>  "taxonomy",
					'help' =>  __(
						"Add the slug of the taxonomy you would like to show.",
						"ditty-news-ticker"
					),
					'std' => '',
				],
				'includes'		=> [
					'type' => "text",
					'id' =>  "includes",
					'help' =>  __(
						"Only show the terms if they include slugs contained in this field contained here. Separate multiple by commas (,).",
						"ditty-news-ticker"
					),
					'std' => '',
				],
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'separator'		=> Ditty()->layouts->tag_attribute_default_settings( 'separator', ', ' ),
				'class'				=> $class_settings,
			),
		),
		'time' => array(
			'tag' 				=> 'time',
			'description' => __( 'Render the item date/time.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
				'ago'					=> Ditty()->layouts->tag_attribute_default_settings( 'ago' ),
				'format' 			=> Ditty()->layouts->tag_attribute_default_settings( 'format', get_option( 'date_format' ) ),
				'ago_string' 	=> Ditty()->layouts->tag_attribute_default_settings( 'ago_string', __( '%s ago', 'ditty-news-ticker' ) ),
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
    'timestamp' => array(
			'tag' 				=> 'timestamp',
			'description' => __( 'Render the item timestamp.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> $wrapper_settings,
        'format' 			=> Ditty()->layouts->tag_attribute_default_settings( 'format', get_option( 'date_format' ) ),
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
		'title' => array(
			'tag' 				=> 'title',
			'description' => __( 'Render the item title.', 'ditty-news-ticker' ),
			'atts'				=> array(
				'wrapper' 		=> Ditty()->layouts->tag_attribute_default_settings( 'wrapper', 'h3' ),
				'before'			=> $before_settings,
				'after'				=> $after_settings,
				'link'				=> $link_settings,
				'link_target' => $link_target_settings,
				'link_rel'		=> $link_rel_settings,
				'link_before'	=> $link_before_settings,
				'link_after'	=> $link_after_settings,
				'class'				=> $class_settings,
			),
		),
	);
	$tags = apply_filters( 'ditty_layout_tags', $tags, $item_type, $item_value );
	ksort( $tags );
	return $tags;
}