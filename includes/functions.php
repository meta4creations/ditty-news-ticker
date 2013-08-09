<?php
/**
 * General functions
 *
 * @package Ditty News Ticker
 */




 /**
 * Display the ticker
 *
 * @since 1.0.0
 */
function ditty_news_ticker( $id='', $class='', $atts=false ) {

	// Display the ticker
	echo get_mtphr_dnt_ticker( $id, $class, $atts );
}

/**
 * Return the ticker
 *
 * @since 1.1.8
 */
function get_mtphr_dnt_ticker( $id='', $class='', $atts=false ) {

	// Get the post
	$ticker = get_post( $id );
	if( $ticker && $ticker->post_status == 'publish' ) {

		// Save the original $wp_query
		global $wp_query;
		$original_query = $wp_query;
		$wp_query = null;
		$wp_query = new WP_Query();

		// Get all the custom data
		$custom_fields = get_post_custom( $id );
		$meta_data = array();
		foreach( $custom_fields as $key => $value ) {
			$meta_data[$key] = maybe_unserialize( $value[0] );
		}

		// Override meta data with supplied attributes
		if( is_array($atts) ) {
			foreach( $atts as $key => $value ) {
				$meta_data["_mtphr_dnt_{$key}"] = $value;
			}
		}

		// Extract the metadata array into variables
		extract( $meta_data );

		// Create an empty array to save ticks
		$dnt_ticks = array();

		// Get the ticks
		if( is_array($_mtphr_dnt_ticks) ) {
			foreach( $_mtphr_dnt_ticks as $i => $tick ) {

				if( $text = wp_kses_post($tick['tick']) ) {

					// Get the contents
					if( $link = esc_url($tick['link']) ) {
						$nf = ( isset($tick['nofollow']) && $tick['nofollow'] ) ? ' rel="nofollow"' : '';
						$contents = '<a href="'.$link.'" target="'.$tick['target'].'"'.$nf.'>'.$text.'</a>';
					} else {
						$contents = $text;
					}
					$contents = apply_filters('mtphr_dnt_tick', $contents, $text, $link );

					// Save the output to the tick array
					$dnt_ticks[] = $contents;
				}
			}
		}

		// Filter the ticks
		$dnt_ticks = apply_filters( 'mtphr_dnt_tick_array', $dnt_ticks, $id, $meta_data );

		ob_start();

		// Create the opening div
		$tick_id = 'mtphr-dnt-'.$id;
		// Add a unique id
		if( isset($_mtphr_dnt_unique_id) ) {
			if( $_mtphr_dnt_unique_id != '' ) {
				$tick_id = 'mtphr-dnt-'.$id.'-'.sanitize_html_class( $_mtphr_dnt_unique_id );
			}
		}

		// Check for a set width
		$ticker_width = '';
		if( isset($_mtphr_dnt_ticker_width) ) {
			if( $_mtphr_dnt_ticker_width != 0 ) {
				$ticker_width = ' style="width:'.intval($_mtphr_dnt_ticker_width).'px"';
			}
		}

		echo '<div'.$ticker_width.' id="'.$tick_id.'" '.mtphr_dnt_ticker_class( $id, $class, $meta_data ).'>';
		echo '<div class="mtphr-dnt-wrapper mtphr-dnt-clearfix">';

		// Display the title
		if( isset($_mtphr_dnt_title) ) {
			if( $_mtphr_dnt_title ) {

				$inline_title = '';
				if( isset($_mtphr_dnt_inline_title) ) {
					if( $_mtphr_dnt_inline_title ) {
						$inline_title = ' mtphr-dnt-inline-title';
					}
				}

				do_action( 'mtphr_dnt_title_before', $id, $meta_data );
				echo '<h3 class="mtphr-dnt-title'.$inline_title.'">'.apply_filters( 'mtphr_dnt_ticker_title', $ticker->post_title ).'</h3>';
				do_action( 'mtphr_dnt_title_after', $id, $meta_data );
			}
		}

		// Create and save element styles
		$margin='';$padding='';$width='';$height='';$spacing='';

		if( $_mtphr_dnt_mode == 'scroll' ) {
			$padding = ( intval($_mtphr_dnt_scroll_padding) != 0 ) ? 'padding-top:'.intval($_mtphr_dnt_scroll_padding).'px;padding-bottom:'.intval($_mtphr_dnt_scroll_padding).'px;' : '';
			$margin = ( intval($_mtphr_dnt_scroll_margin) != 0 ) ? 'margin-top:'.intval($_mtphr_dnt_scroll_margin).'px;margin-bottom:'.intval($_mtphr_dnt_scroll_margin).'px;' : '';
			$width = ( intval($_mtphr_dnt_scroll_width) != 0 ) ? 'white-space:normal;width:'.intval($_mtphr_dnt_scroll_width).'px;' : '';
			$height = ( intval($_mtphr_dnt_scroll_height) != 0 ) ? 'height:'.intval($_mtphr_dnt_scroll_height).'px;' : '';
		} elseif( $_mtphr_dnt_mode == 'rotate' ) {
			$padding = ( intval($_mtphr_dnt_rotate_padding) != 0 ) ? 'padding-top:'.intval($_mtphr_dnt_rotate_padding).'px;padding-bottom:'.intval($_mtphr_dnt_rotate_padding).'px;' : '';
			$margin = ( intval($_mtphr_dnt_rotate_margin) != 0 ) ? 'margin-top:'.intval($_mtphr_dnt_rotate_margin).'px;margin-bottom:'.intval($_mtphr_dnt_rotate_margin).'px;' : '';
			$height = ( intval($_mtphr_dnt_rotate_height) != 0 ) ? 'height:'.intval($_mtphr_dnt_rotate_height).'px;' : '';
		} elseif(  $_mtphr_dnt_mode == 'list' ) {
			$padding = ( intval($_mtphr_dnt_list_padding) != 0 ) ? 'padding-top:'.intval($_mtphr_dnt_list_padding).'px;padding-bottom:'.intval($_mtphr_dnt_list_padding).'px;' : '';
			$margin = ( intval($_mtphr_dnt_list_margin) != 0 ) ? 'margin-top:'.intval($_mtphr_dnt_list_margin).'px;margin-bottom:'.intval($_mtphr_dnt_list_margin).'px;' : '';
		}

		// Filter the variables
		$padding = apply_filters( 'mtphr_dnt_tick_container_padding', $padding );
		$margin = apply_filters( 'mtphr_dnt_tick_container_margin', $margin );
		$width = apply_filters( 'mtphr_dnt_tick_width', $width );
		$height = apply_filters( 'mtphr_dnt_tick_height', $height );

		// Create the container style
		$container_style = ( $padding != '' || $margin != '' ) ? ' style="'.$padding.$margin.'"' : '';

		// Open the ticker container
		do_action( 'mtphr_dnt_before', $id, $meta_data );
		echo '<div class="mtphr-dnt-tick-container"'.$container_style.'>';
		echo '<div class="mtphr-dnt-tick-contents">';
		do_action( 'mtphr_dnt_top', $id, $meta_data );

		// Print out the ticks
		if( is_array($dnt_ticks) ) {

			// Randomize the ticks
			if( isset($_mtphr_dnt_shuffle) && $_mtphr_dnt_shuffle ) {
				shuffle( $dnt_ticks );
			}
			$total = count($dnt_ticks);
			foreach( $dnt_ticks as $i => $tick ) {

				// Set the list spacing depending on the tick position
				if( $_mtphr_dnt_mode == 'list' ) {
					$spacing = ( $i != intval($total-1) ) ? 'margin-bottom:'.intval($_mtphr_dnt_list_tick_spacing).'px;' : '';
				}
				$spacing = apply_filters( 'mtphr_dnt_list_tick_spacing', $spacing, $i, $total );
				$tick_style = ( $width != '' || $height != '' || $spacing != '' ) ? ' style="'.$width.$height.$spacing.'"' : '';

				do_action( 'mtphr_dnt_tick_before', $id, $meta_data, $total, $i );
				echo '<div'.$tick_style.' '.mtphr_dnt_tick_class('mtphr-dnt-clearfix').'>';
				do_action( 'mtphr_dnt_tick_top', $id, $meta_data );

				echo $tick;

				do_action( 'mtphr_dnt_tick_bottom', $id, $meta_data );
				echo '</div>';
				do_action( 'mtphr_dnt_tick_after', $id, $meta_data, $total, $i );
			}
		}

		// Close the ticker container
		do_action( 'mtphr_dnt_bottom', $id, $meta_data );
		echo '</div>';
		// Add the directional nav
		if( is_array($dnt_ticks) && $_mtphr_dnt_mode == 'rotate' ) {
			if( isset($_mtphr_dnt_rotate_directional_nav) ) {
				if( $_mtphr_dnt_rotate_directional_nav ) {

					$hide = '';
					if( isset($_mtphr_dnt_rotate_directional_nav_hide) ) {
						$hide = $_mtphr_dnt_rotate_directional_nav_hide ? ' mtphr-dnt-nav-hide' : '';
					}
					echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-prev'.$hide.'" href="#" rel="nofollow">'.apply_filters( 'mtphr_dnt_direction_nav_prev', '' ).'</a>';
					echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-next'.$hide.'" href="#" rel="nofollow">'.apply_filters( 'mtphr_dnt_direction_nav_next', '' ).'</a>';
				}
			}
		}
		echo '</div>';
		do_action( 'mtphr_dnt_after', $id, $meta_data );

		// Add the control nav
		if( is_array($dnt_ticks) && $_mtphr_dnt_mode == 'rotate' ) {
			if( isset($_mtphr_dnt_rotate_control_nav) ) {
				if( $_mtphr_dnt_rotate_control_nav ) {

					echo '<div class="mtphr-dnt-control-links">';
					foreach( $dnt_ticks as $i => $tick ) {
						echo '<a class="mtphr-dnt-control mtphr-dnt-control-'.$_mtphr_dnt_rotate_control_nav_type.'" href="'.$i.'" rel="nofollow">'.apply_filters( 'mtphr_dnt_control_nav', intval($i+1) ).'</a>';
					}
					echo '</div>';
				}
			}
		}

		// Close the ticker
		echo '</div></div>';

		// Restore the original $wp_query
		$wp_query = null;
		$wp_query = $original_query;
		wp_reset_postdata();
	}

	// Add to the global script variable
	if( $_mtphr_dnt_mode == 'scroll' || $_mtphr_dnt_mode == 'rotate' ) {

		global $mtphr_dnt_ticker_scripts;

		$ticker = '#mtphr-dnt-'.$id;

		// Add a unique id class, if there is one
		if( isset($_mtphr_dnt_unique_id) ) {
			if( $_mtphr_dnt_unique_id != '' ) {
				$ticker = '#mtphr-dnt-'.$id.'-'.sanitize_html_class( $_mtphr_dnt_unique_id );
			}
		}

		$scroll_pause = 0; $scroll_init = 0;
		if( isset($_mtphr_dnt_scroll_pause) ) {
			$scroll_pause = $_mtphr_dnt_scroll_pause ? 1 : 0;
		}
		if( isset($_mtphr_dnt_scroll_init) ) {
			$scroll_init = $_mtphr_dnt_scroll_init ? 1 : 0;
		}
		$rotate = 0; $rotate_pause = 0; $nav_autohide = 0; $nav_reverse = 0;
		if( isset($_mtphr_dnt_auto_rotate) ) {
			$rotate = $_mtphr_dnt_auto_rotate ? 1 : 0;
		}
		if( isset($_mtphr_dnt_rotate_pause) ) {
			$rotate_pause = $_mtphr_dnt_rotate_pause ? 1 : 0;
		}
		if( isset($_mtphr_dnt_rotate_directional_nav_reverse) ) {
			$nav_reverse = $_mtphr_dnt_rotate_directional_nav_reverse ? 1 : 0;
		}
		$offset = isset($_mtphr_dnt_offset) ? intval($_mtphr_dnt_offset) : 20;
		$mtphr_dnt_ticker_scripts[] = array(
			'ticker' => $ticker,
			'id' => $id,
			'type' => $_mtphr_dnt_mode,
			'scroll_direction' => $_mtphr_dnt_scroll_direction,
			'scroll_speed' => intval($_mtphr_dnt_scroll_speed),
			'scroll_pause' => $scroll_pause,
			'scroll_spacing' => intval($_mtphr_dnt_scroll_tick_spacing),
			'scroll_init' => $scroll_init,
			'rotate_type' => $_mtphr_dnt_rotate_type,
			'auto_rotate' => $rotate,
			'rotate_delay' => intval($_mtphr_dnt_rotate_delay),
			'rotate_pause' => $rotate_pause,
			'rotate_speed' => intval($_mtphr_dnt_rotate_speed),
			'rotate_ease' => $_mtphr_dnt_rotate_ease,
			'nav_reverse' => $nav_reverse,
			'offset' => $offset
		);
	}

	// Return the output
	return ob_get_clean();
}




/**
 * Return the ticker class
 *
 * @since 1.0.9
 */
function mtphr_dnt_ticker_class( $id='', $class='', $meta_data ) {

	// Separates classes with a single space, collates classes for ditty ticker element
	return 'class="'.join( ' ', get_mtphr_dnt_ticker_class($id,$class,$meta_data) ).'"';
}

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




/**
 * Return the tick class
 *
 * @since 1.0.0
 */
function mtphr_dnt_tick_class( $class='' ) {

	// Separates classes with a single space, collates classes for ditty ticker element
	return 'class="'.join( ' ', get_mtphr_dnt_tick_class($class) ).'"';
}

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




/**
 * Minify scripts for output
 *
 * @since 1.0.0
 */
function mtphr_dnt_compress_script( $str ) {

	$lines = explode( "\n", $str );
	$output = '';
	foreach( $lines as $line ) {
		if( substr(trim($line), 0, 3) != '// ' ) {
			$output .= trim( $line );
		}
	}

	return $output;
}




/**
 * Return an array of the current DNT types
 *
 * @since 1.0.0
 */
function mtphr_dnt_types_array() {

	/* Create the types array. */
	$dnt_types_array = array();
	$dnt_types_array['default'] = array(
		'button' => __('Default', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_type_default' )
	);

	return apply_filters('mtphr_dnt_types', $dnt_types_array);
}




/**
 * Return an array of the current DNT modes
 *
 * @since 1.0.0
 */
function mtphr_dnt_modes_array() {

	/* Create the modes array. */
	$dnt_modes_array = array();
	$dnt_modes_array['scroll'] = array(
		'button' => __('Scroll', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_mode_scroll' )
	);
	$dnt_modes_array['rotate'] = array(
		'button' => __('Rotate', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_mode_rotate' )
	);
	$dnt_modes_array['list'] = array(
		'button' => __('List', 'ditty-news-ticker'),
		'metaboxes' => array( 'mtphr_dnt_mode_list' )
	);

	return apply_filters('mtphr_dnt_modes', $dnt_modes_array);
}




/**
 * Return an array of the current DNT settings
 *
 * @since 1.0.6
 */
function mtphr_dnt_settings_tabs() {

	$dnt_settings_array = array();
	$dnt_settings_array['general'] = 'mtphr_dnt_general_settings';

	return apply_filters('mtphr_dnt_settings', $dnt_settings_array);
}




add_action( 'plugins_loaded', 'mtphr_dnt_localization' );
/**
 * Setup localization
 *
 * @since 1.1.5
 */
function mtphr_dnt_localization() {
	load_plugin_textdomain( 'ditty-news-ticker', false, 'ditty-news-ticker/languages/' );
}



