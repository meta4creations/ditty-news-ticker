<?php

/**
 * Return an array default meta values
 * @since   3.0.30
 */
function mtphr_dnt_meta_defaults() {
	$defaults = array(
		'_mtphr_dnt_type' => 'default',
		'_mtphr_dnt_mode' => 'scroll',
		'_mtphr_dnt_scroll_direction' => 'left',
		'_mtphr_dnt_scroll_init' => '',
		'_mtphr_dnt_scroll_init_delay' => '2',
		'_mtphr_dnt_scroll_width' => 0,
		'_mtphr_dnt_scroll_height' => 0,
		'_mtphr_dnt_scroll_padding' => 0,
		'_mtphr_dnt_scroll_margin' => 0,
		'_mtphr_dnt_scroll_speed' => 10,
		'_mtphr_dnt_scroll_pause' => 0,
		'_mtphr_dnt_scroll_tick_spacing' => 0,
		'_mtphr_dnt_rotate_type' => 'fade',
		'_mtphr_dnt_rotate_directional_nav_reverse' => '',
		'_mtphr_dnt_rotate_height' => 0,
		'_mtphr_dnt_rotate_padding' => 0,
		'_mtphr_dnt_rotate_margin' => 0,
		'_mtphr_dnt_auto_rotate' => '',
		'_mtphr_dnt_rotate_delay' => 7,
		'_mtphr_dnt_rotate_pause' => '',
		'_mtphr_dnt_rotate_speed' => 10,
		'_mtphr_dnt_rotate_ease' => 'easeInOutQuint',
		'_mtphr_dnt_rotate_directional_nav' => '1',
		'_mtphr_dnt_rotate_directional_nav_hide' => '',
		'_mtphr_dnt_rotate_control_nav' => '1',
		'_mtphr_dnt_rotate_control_nav_type' => 'button',
		'_mtphr_dnt_rotate_disable_touchswipe' => '',
		'_mtphr_dnt_list_padding' => 0,
		'_mtphr_dnt_list_margin' => 0,
		'_mtphr_dnt_list_tick_spacing' => 10,
		'_mtphr_dnt_list_tick_paging' => '',
		'_mtphr_dnt_list_tick_count' => 10,
		'_mtphr_dnt_list_tick_prev_next' => '',
		'_mtphr_dnt_list_tick_prev_text' => __('« Previous', 'ditty-news-ticker'),
		'_mtphr_dnt_list_tick_next_text' => __('Next »', 'ditty-news-ticker'),
		'_mtphr_dnt_ajax' => '',
		'_mtphr_dnt_title' => '',
		'_mtphr_dnt_inline_title' => '',
		'_mtphr_dnt_hide' => '',
		'_mtphr_dnt_shuffle' => '',
		'_mtphr_dnt_reverse' => '',
		'_mtphr_dnt_ticker_width' => 0,
		'_mtphr_dnt_offset' => 20,
		'_mtphr_dnt_trim_ticks' => '',
		'_mtphr_dnt_pause_button' => '',
		'_mtphr_dnt_grid' => '',
		'_mtphr_dnt_grid_empty_rows' => '',
		'_mtphr_dnt_grid_equal_width' => '',
		'_mtphr_dnt_grid_cols' => 2,
		'_mtphr_dnt_grid_rows' => 2,
		'_mtphr_dnt_grid_padding' => 5,
		'_mtphr_dnt_grid_remove_padding' => '',
		'_mtphr_dnt_styled' => false,
	);
	return $defaults;
}

	
/**
 * Return an array of ticker types
 * @since   3.0
 */
function mtphr_dnt_types_array() {

	/* Create the types array. */
	$dnt_types_array = array();
	$dnt_types_array['default'] = array(
		'button' => __('Default', 'ditty-news-ticker'),
		'metabox_id' => 'mtphr-dnt-default-metabox',
		'icon' => 'dashicons dashicons-edit'
	);
	$dnt_types_array['mixed'] = array(
		'button' => __('Mixed', 'ditty-news-ticker'),
		'metabox_id' => 'mtphr-dnt-mixed-metabox',
		'icon' => 'dashicons dashicons-randomize'
	);
	
	return apply_filters('mtphr_dnt_types', $dnt_types_array);
}


/**
 * Return the labels of ticker types
 * @since   3.0
 */
function mtphr_dnt_types_labels() {
	
	$types = mtphr_dnt_types_array();
	$labels = array();
	
	if( is_array($types) && count($types) > 0 ) {
		foreach( $types as $i=>$type ) {
			$labels[$i] = $type['button'];
		}
	}
	
	return $labels;
}

/**
 * Return an array of ticker types
 * @since   3.0
 */
function dnt_types() {

	/* Create the types array. */
	$dnt_types = array();

	$dnt_types['default'] = array(
		'type' 				=> 'default',
		'label' 			=> __( 'Default', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-pencil-alt',
		'description' => __( 'Manually add HTML to the tick.', 'ditty-news-ticker' ),
		'class_name'	=> 'DNT_Type_Default',
	);
	$dnt_types['twitter_feed'] = array(
		'type' 				=> 'twitter_feed',
		'label' 			=> __('Twitter Feed', 'ditty-news-ticker'),
		'icon' 				=> 'fab fa-twitter',
		'description' => __( 'Display a Twitter feed using a set of parameters.', 'ditty-news-ticker' ),
		'class_name'	=> 'DNT_Type_Twitter_Feed',
	);
	
	$dnt_types['twitter_tweet'] = array(
		'type' 				=> 'twitter_tweet',
		'label' 			=> __('Single Tweet', 'ditty-news-ticker'),
		'icon' 				=> 'fab fa-twitter',
		'description' => __( 'Display a single Tweet by entering a URL or ID.', 'ditty-news-ticker' ),
		'class_name'	=> 'DNT_Type_Twitter_Tweet',
	);

	return apply_filters( 'dnt_types', $dnt_types );
}

/**
 * Return an array of ticker types
 * @since   3.0
 */
function dnt_fields() {

	/* Create the fields array. */
	$dnt_fields = array();
	$dnt_types = dnt_types();
	
	if ( is_array( $dnt_types ) && count( $dnt_types ) > 0 ) {
		foreach ( $dnt_types as $data ) {
			if ( isset( $data['class_name'] ) && class_exists( $data['class_name'] ) ) {
				
				$type = new $data['class_name'];
				$dnt_fields[ $data[ 'type' ] ] = $type->fields();
				
			}
		}
	}

	return apply_filters( 'dnt_fields', $dnt_fields );
}

/**
 * Return an array of ticks for a ticker
 * @since   3.0
 */
function dnt_ticks( $id=false ) {
	
	$ticker_id = $id ? $id : get_the_id();
	
	if ( 'ditty_news_ticker' != get_post_type( $ticker_id ) ) {
		return;
	}

	/* Get the ticks array */
	$dnt_ticks = array();

	return apply_filters( 'dnt_ticks', $dnt_ticks, $ticker_id );
}

/* --------------------------------------------------------- */
/* !Return an array of the current DNT modes - 1.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_modes_array') ) {
function mtphr_dnt_modes_array() {

	/* Create the modes array. */
	$dnt_modes_array = array();
	$dnt_modes_array['scroll'] = array(
		'button' => __('Scroll', 'ditty-news-ticker'),
		'metabox_id' => 'mtphr-dnt-scroll-metabox',
		'icon' => 'dashicons dashicons-leftright'
	);
	$dnt_modes_array['rotate'] = array(
		'button' => __('Rotate', 'ditty-news-ticker'),
		'metabox_id' => 'mtphr-dnt-rotate-metabox',
		'icon' => 'dashicons dashicons-update'
	);
	$dnt_modes_array['list'] = array(
		'button' => __('List', 'ditty-news-ticker'),
		'metabox_id' => 'mtphr-dnt-list-metabox',
		'icon' => 'dashicons dashicons-menu'
	);

	return apply_filters('mtphr_dnt_modes', $dnt_modes_array);
}
}



/* --------------------------------------------------------- */
/* !Return an array of the current DNT settings - 1.0.6 */
/* --------------------------------------------------------- */

function mtphr_dnt_settings_tabs() {

	$dnt_settings_array = array();
	$dnt_settings_array['general'] = 'mtphr_dnt_general_settings';

	return apply_filters('mtphr_dnt_settings', $dnt_settings_array);
}



/* --------------------------------------------------------- */
/* !Return the ticker class - 3.0.30 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_ticker_class') ) {
function mtphr_dnt_ticker_class( $id='', $class='', $meta_data = array() ) {

	return 'class="'.join( ' ', get_mtphr_dnt_ticker_class($id,$class,$meta_data) ).'"';
}
}

if( !function_exists('get_mtphr_dnt_ticker_class') ) {
function get_mtphr_dnt_ticker_class( $id='', $class='', $meta_data = array() ) {
	$defaults = mtphr_dnt_meta_defaults();
	$args = wp_parse_args( $meta_data, mtphr_dnt_meta_defaults() );
	
	$classes = array();

	$classes[] = 'mtphr-dnt';
	$classes[] = 'mtphr-dnt-'.$id;
	$classes[] = 'mtphr-dnt-'.$args['_mtphr_dnt_type'];
	$classes[] = 'mtphr-dnt-'.$args['_mtphr_dnt_mode'];

	if( $args['_mtphr_dnt_mode'] == 'scroll' ) {
		$classes[] = 'mtphr-dnt-'.$args['_mtphr_dnt_mode'].'-'.$args['_mtphr_dnt_scroll_direction'];
	}
	if( $args['_mtphr_dnt_mode'] == 'rotate' ) {
		$classes[] = 'mtphr-dnt-'.$args['_mtphr_dnt_mode'].'-'.$args['_mtphr_dnt_rotate_type'];
	}
	if( $args['_mtphr_dnt_trim_ticks'] ) {
		$classes[] = 'mtphr-dnt-trim-ticks';
	}

	// Set the styles class
	if( $args['_mtphr_dnt_styled'] ) {
		$classes[] = 'mtphr-dnt-styled';
	}

	if ( !empty( $class ) ) {
		if ( !is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( 'mtphr_dnt_ticker_class', $classes, $class, $meta_data );
}
}



/* --------------------------------------------------------- */
/* !Return the tick class - 1.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_tick_class') ) {
function mtphr_dnt_tick_class( $class='' ) {

	// Separates classes with a single space, collates classes for ditty ticker element
	return 'class="'.join( ' ', get_mtphr_dnt_tick_class($class) ).'"';
}
}

if( !function_exists('get_mtphr_dnt_tick_class') ) {
function get_mtphr_dnt_tick_class( $class='' ) {

	$classes = array();

	$classes[] = 'mtphr-dnt-tick';

	if ( !empty( $class ) ) {
		if ( !is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( 'mtphr_dnt_tick_class', $classes, $class );
}
}



/* --------------------------------------------------------- */
/* !Create the tick open structure - 3.0.30 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_tick_open') ) {
function mtphr_dnt_tick_open( $tick_obj, $i, $id, $meta_data, $total=false ) {
	$defaults = mtphr_dnt_meta_defaults();
	$args = wp_parse_args( $meta_data, $defaults );
	
	// Create and save element styles
	$width='';$height='';$spacing='';

	if( $args['_mtphr_dnt_mode'] == 'scroll' ) {
		$width = ( intval($args['_mtphr_dnt_scroll_width']) != 0 ) ? 'white-space:normal;width:'.intval($args['_mtphr_dnt_scroll_width']).'px;' : '';
		$height = ( intval($args['_mtphr_dnt_scroll_height']) != 0 ) ? 'height:'.intval($args['_mtphr_dnt_scroll_height']).'px;' : '';
	} elseif( $args['_mtphr_dnt_mode'] == 'rotate' ) {
		$height = ( intval($args['_mtphr_dnt_rotate_height']) != 0 ) ? 'height:'.intval($args['_mtphr_dnt_rotate_height']).'px;' : '';
	}

	// Filter the variables
	$width = apply_filters( 'mtphr_dnt_tick_width', $width );
	$height = apply_filters( 'mtphr_dnt_tick_height', $height );
	
	$type = ( is_array($tick_obj) && isset($tick_obj['type']) ) ? $tick_obj['type'] : $args['_mtphr_dnt_type'];
	$tick_class = ( is_array($tick_obj) && isset($tick_obj['tick_class']) ) ? $tick_obj['tick_class'] : '';
	$data_attributes = '';
	if( is_array($tick_obj) && isset($tick_obj['data']) && is_array($tick_obj['data']) ) {
		if( is_array($tick_obj['data']) && count($tick_obj['data']) > 0 ) {
			foreach( $tick_obj['data'] as $i=>$data ) {
				$data_attributes .= ' data-'.$i.'="'.$data.'"';
			}
		}
	}

	// Set the list spacing depending on the tick position
	$spacing = ( $args['_mtphr_dnt_mode'] == 'list' && ($i != intval($total-1)) ) ? 'margin-bottom:'.intval($args['_mtphr_dnt_list_tick_spacing']).'px;' : '';
	$spacing = apply_filters( 'mtphr_dnt_list_tick_spacing', $spacing, $i, $total );
	$tick_style = ( $width != '' || $height != '' || $spacing != '' ) ? ' style="'.$width.$height.$spacing.'"' : '';

	do_action( 'mtphr_dnt_tick_before', $id, $meta_data, $total, $i );
	echo '<div'.$tick_style.' '.mtphr_dnt_tick_class('mtphr-dnt-'.$type.'-tick mtphr-dnt-clearfix '.$tick_class).$data_attributes.'>';
	do_action( 'mtphr_dnt_tick_top', $id, $meta_data );
	
}
}



/* --------------------------------------------------------- */
/* !Create the tick close structure - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_tick_close') ) {
function mtphr_dnt_tick_close( $tick_obj, $i, $id, $meta_data, $total=false ) {
	
	do_action( 'mtphr_dnt_tick_bottom', $id, $meta_data );
	echo '</div>';
	do_action( 'mtphr_dnt_tick_after', $id, $meta_data, $total, $i );
}
}


/* --------------------------------------------------------- */
/* !Return an array of tickers - 2.0.6 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_get_tickers') ) {
function mtphr_dnt_get_tickers( $reverse = false ) {
	
	$args = array(
		'posts_per_page' => -1,
		'offset' => 0,
		'category' => '',
		'orderby' => 'title',
		'order' => 'ASC',
		'include' => '',
		'exclude' => '',
		'meta_key' => '',
		'meta_value' => '',
		'post_type' => 'ditty_news_ticker',
		'post_mime_type' => '',
		'post_parent' => '',
		'post_status' => 'publish',
		'suppress_filters' => true
	);
	$tickers = get_posts( $args );
	
	$tickers_array = $reverse ? array( __('Select a Ticker', 'ditty-news-ticker') => '' ) : array( '' => __('Select a Ticker', 'ditty-news-ticker') );
	if( is_array($tickers) && count($tickers) > 0 ) {
		foreach( $tickers as $i=>$ticker ) {
			if( $reverse ) {
				$tickers_array[$ticker->post_title] = $ticker->ID;
			} else {
				$tickers_array[$ticker->ID] = $ticker->post_title;
			}
		}
	} else {
		$tickers_array = array( __('No tickers found', 'ditty-news-ticker') );
	}

	return $tickers_array;
}
}


/* --------------------------------------------------------- */
/* !Convert links in strings - 2.0.11 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_convert_links') ) {
function mtphr_dnt_convert_links( $string, $blank=true ) {
	
	$string = make_clickable( $string );
	if( $blank ) {
		$string = preg_replace( '/<a /','<a target="_blank" ', $string );
	}

	return $string;
}
}


/**
 * Return strings
 * @since 2.1.17
 */
function mtphr_dnt_strings() {	
	
	$strings = array(
		'successful_activation' => __('Your license is activated!', 'ditty-news-ticker'),
		'unsuccessful_activation' => __('Sorry, this license is not valid.', 'ditty-news-ticker'),
		'successful_deactivation' => __('Your license has been deactivated.', 'ditty-news-ticker'),
		'unsuccessful_deactivation' => __('Sorry, something went wrong with the deactivation.', 'ditty-news-ticker'),
		'deactivate_license' => __('Deactivate License', 'ditty-news-ticker'),
		'activate_license' => __('Activate License', 'ditty-news-ticker'),
		'refresh_license' => __('Refresh License', 'ditty-news-ticker')
	);
	
	return apply_filters( 'mtphr_dnt_license_strings', $strings );
}


/**
 * Return a single string
 * @since 2.1.17
 */
function mtphr_dnt_string( $slug ) {	

	$strings = mtphr_dnt_strings();
	if( isset($strings[$slug]) ) {
		return $strings[$slug];
	} else {
		return sprintf(__('% string does not exist', 'ditty-news-ticker'), $slug);
	}
}

/**
 * Get an option value
 * @since 2.2.6
 */
function mtphr_dnt_get_option( $option, $default=false ) {
	if ( is_multisite() ) {
		return get_site_option( $option, $default );
	} else {
		return get_option( $option, $default );
	}
}


/**
 * Update an option value
 * @since 2.2.6
 */
function mtphr_dnt_update_option( $option, $value ) {
	if ( is_multisite() ) {
		return update_site_option( $option, $value );
	} else {
		return update_option( $option, $value );
	}
}

/**
 * Return an array value and possibly unserialize
 */
function mtphr_dnt_maybe_unserialize( $value ) {
	if ( is_serialized( $value ) ) {
		$value = @unserialize(
			trim( $value ),
			array( 'allowed_classes' => false )
		);
		return is_array( $value ) ? $value : [];
	} else {
		return $value;
	}
}