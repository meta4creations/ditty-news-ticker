<?php

/* --------------------------------------------------------- */
/* !Return an array of the current DNT types - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_types_array') ) {
function mtphr_dnt_types_array() {

	/* Create the types array. */
	$dnt_types_array = array();
	$dnt_types_array['default'] = array(
		'button' => __('Default', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_default_metabox' )
	);
	$dnt_types_array['mixed'] = array(
		'button' => __('Mixed', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_mixed_metabox' )
	);
	
	return apply_filters('mtphr_dnt_types', $dnt_types_array);
}
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
		'metaboxes' => array( 'mtphr_dnt_scroll_settings_metabox' )
	);
	$dnt_modes_array['rotate'] = array(
		'button' => __('Rotate', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_rotate_settings_metabox' )
	);
	$dnt_modes_array['list'] = array(
		'button' => __('List', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_list_settings_metabox' )
	);

	return apply_filters('mtphr_dnt_modes', $dnt_modes_array);
}
}



/* --------------------------------------------------------- */
/* !Return the ticker class - 1.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_ticker_class') ) {
function mtphr_dnt_ticker_class( $id='', $class='', $meta_data ) {

	return 'class="'.join( ' ', get_mtphr_dnt_ticker_class($id,$class,$meta_data) ).'"';
}
}

if( !function_exists('get_mtphr_dnt_ticker_class') ) {
function get_mtphr_dnt_ticker_class( $id='', $class='', $meta_data ) {

	// Extract the metadata array into variables
	extract( $meta_data );

	$classes = array();

	$classes[] = 'mtphr-dnt';
	$classes[] = 'mtphr-dnt-'.$id;
	$classes[] = 'mtphr-dnt-'.$_mtphr_dnt_type;
	$classes[] = 'mtphr-dnt-'.$_mtphr_dnt_mode;

	if( $_mtphr_dnt_mode == 'scroll' ) {
		$classes[] = 'mtphr-dnt-'.$_mtphr_dnt_mode.'-'.$_mtphr_dnt_scroll_direction;
	}
	if( $_mtphr_dnt_mode == 'rotate' ) {
		$classes[] = 'mtphr-dnt-'.$_mtphr_dnt_mode.'-'.$_mtphr_dnt_rotate_type;
	}

	// Set the styles class
	if( isset($_mtphr_dnt_styled) ) {
		if( $_mtphr_dnt_styled ) {
			$classes[] = 'mtphr-dnt-styled';
		}
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

	return apply_filters( 'mtphr_dnt_ticker_class', $classes, $class );
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

