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
	echo get_mtphr_dnt_ticker( $id, $class, $atts );	
}

/**
 * Return the ticker
 *
 * @since 2.0.9
 */
function get_mtphr_dnt_ticker( $id='', $class='', $atts=false ) {
	
/*
	// Get the current mode for the ticker
	$mode = ( is_array($atts) && isset($atts['mode']) ) ? $atts['mode'] : get_post_meta( $id, '_mtphr_dnt_mode', true );
	
	// Return the appropriate ticker mode
	if( function_exists('render_mtphr_dnt_'.$mode.'_ticker') ) {
		return call_user_func( 'render_mtphr_dnt_'.$mode.'_ticker', $id, $class, $atts );
	} else {
		return render_mtphr_dnt_ticker( $id, $class, $atts );
	}
*/
	
	//$ticker = new MTPHR_DNT();
	
	// Check for WPML language posts
	$id = function_exists('icl_object_id') ? icl_object_id( $id, 'ditty_news_ticker', true ) : $id;
	
	// Get the current mode for the ticker
	$mode = ( is_array($atts) && isset($atts['mode']) ) ? $atts['mode'] : get_post_meta( $id, '_mtphr_dnt_mode', true );
	
	// Make sure the ticker exists and is published
	$ticker = get_post( $id );
	if( $ticker && $ticker->post_status == 'publish' ) {
		
		global $mtphr_dnt_meta_data;
		
		// Store all the custom fields in a metadata array
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
		
		// Return the appropriate ticker mode
		if( function_exists('render_mtphr_dnt_'.$mode.'_ticker') ) {
			return call_user_func( 'render_mtphr_dnt_'.$mode.'_ticker', $id, $class, $meta_data );
		} else {
			return render_mtphr_dnt_ticker( $id, $class, $meta_data );
		}

	}
	
}



/**
 * Render the ticker
 *
 * @since 2.1.23
 */
function render_mtphr_dnt_ticker( $id='', $class='', $meta_data=false ) {
	
	//$ticker = new MTPHR_DNT( $id, $class, $atts );
	//echo '<pre>';print_r($ticker);echo '</pre>';
	
	$html = '';

	// Save the original $wp_query
	global $wp_query, $mtphr_dnt_ticker_types, $mtphr_dnt_meta_data;
	$original_query = $wp_query;
	$wp_query = null;
	$wp_query = new WP_Query();

	// Extract the metadata array into variables
	extract( $meta_data );
	
	// Add to the global ticker types
	$mtphr_dnt_ticker_types[$_mtphr_dnt_type] = $_mtphr_dnt_type;
	
	// Get the ticks to display
	if( $_mtphr_dnt_type == 'mixed' ) {
		$dnt_ticks = mtphr_dnt_mixed_ticks( $id, $meta_data );
	} else {
		$dnt_ticks = apply_filters( 'mtphr_dnt_tick_array', array(), $id, $meta_data );
	}
	
	// Transform the tick array
	$dnt_ticks = apply_filters( 'mtphr_dnt_tick_array_transform', $dnt_ticks, $id, $meta_data );
	
	// Add the post amount of ticks to the metadata
	$total_ticks = count( $dnt_ticks );
	
	// Add the post id & total ticks to the metadata
	$meta_data['_mtphr_dnt_id'] = $id;
	$meta_data['_mtphr_dnt_total_ticks'] = $total_ticks;
	
	// Save the metadata in a global variable
	$mtphr_dnt_meta_data = $meta_data;

	if( !(isset($_mtphr_dnt_hide) && $_mtphr_dnt_hide == 'on') || $total_ticks > 0 ) {
		
		ob_start();
	
		// Add a unique id
		$tick_id = 'mtphr-dnt-'.$id;
		if( isset($_mtphr_dnt_unique_id) && ($_mtphr_dnt_unique_id != '') ) {
				$tick_id = 'mtphr-dnt-'.$id.'-'.sanitize_html_class( $_mtphr_dnt_unique_id );
		}
	
		// Check for a set width
		$ticker_width = '';
		if( isset($_mtphr_dnt_ticker_width) && ($_mtphr_dnt_ticker_width != 0) ) {
			$ticker_width = ' style="width:'.intval($_mtphr_dnt_ticker_width).'px"';
		}
	
		echo '<div'.$ticker_width.' id="'.$tick_id.'" '.mtphr_dnt_ticker_class( $id, $class, $meta_data ).'>';
			echo '<div class="mtphr-dnt-wrapper mtphr-dnt-clearfix">';
	
				// Create and save element styles
				$margin='';$padding='';
			
				if( $_mtphr_dnt_mode == 'scroll' ) {
					$padding = ( intval($_mtphr_dnt_scroll_padding) != 0 ) ? 'padding-top:'.intval($_mtphr_dnt_scroll_padding).'px;padding-bottom:'.intval($_mtphr_dnt_scroll_padding).'px;' : '';
					$margin = ( intval($_mtphr_dnt_scroll_margin) != 0 ) ? 'margin-top:'.intval($_mtphr_dnt_scroll_margin).'px;margin-bottom:'.intval($_mtphr_dnt_scroll_margin).'px;' : '';
				} elseif( $_mtphr_dnt_mode == 'rotate' ) {
					$padding = ( intval($_mtphr_dnt_rotate_padding) != 0 ) ? 'padding-top:'.intval($_mtphr_dnt_rotate_padding).'px;padding-bottom:'.intval($_mtphr_dnt_rotate_padding).'px;' : '';
					$margin = ( intval($_mtphr_dnt_rotate_margin) != 0 ) ? 'margin-top:'.intval($_mtphr_dnt_rotate_margin).'px;margin-bottom:'.intval($_mtphr_dnt_rotate_margin).'px;' : '';
				} elseif(  $_mtphr_dnt_mode == 'list' ) {
					$padding = ( intval($_mtphr_dnt_list_padding) != 0 ) ? 'padding-top:'.intval($_mtphr_dnt_list_padding).'px;padding-bottom:'.intval($_mtphr_dnt_list_padding).'px;' : '';
					$margin = ( intval($_mtphr_dnt_list_margin) != 0 ) ? 'margin-top:'.intval($_mtphr_dnt_list_margin).'px;margin-bottom:'.intval($_mtphr_dnt_list_margin).'px;' : '';
				}
			
				// Filter the variables
				$padding = apply_filters( 'mtphr_dnt_tick_container_padding', $padding );
				$margin = apply_filters( 'mtphr_dnt_tick_container_margin', $margin );
			
				// Create the container style
				$container_style = ( $padding != '' || $margin != '' ) ? ' style="'.$padding.$margin.'"' : '';
		
				// Open the ticker container
				do_action( 'mtphr_dnt_before', $id, $meta_data );
				echo '<div class="mtphr-dnt-tick-container"'.$container_style.'>';
					do_action( 'mtphr_dnt_contents_before', $id, $meta_data );
					echo '<div class="mtphr-dnt-tick-contents">';
						do_action( 'mtphr_dnt_top', $id, $meta_data );
				
						// Print out the ticks
						if( is_array($dnt_ticks) ) {
							
							// Grab the paged ticks
							if( $_mtphr_dnt_mode == 'list' && (isset($_mtphr_dnt_list_tick_paging) && $_mtphr_dnt_list_tick_paging) ) {
								$page = isset( $_GET['tickpage'] ) ? intval($_GET['tickpage']) : 1;
								$offset = ($page-1) * $_mtphr_dnt_list_tick_count;
								$dnt_ticks = array_slice( $dnt_ticks, $offset, $_mtphr_dnt_list_tick_count );
							}
							
							// Reverse the ticker
							if( isset($_mtphr_dnt_reverse) && $_mtphr_dnt_reverse ) {
								$dnt_ticks = array_reverse( $dnt_ticks );
							}
				
							// Randomize the ticks
							if( isset($_mtphr_dnt_shuffle) && $_mtphr_dnt_shuffle ) {
								shuffle( $dnt_ticks );
							}
							$total = count($dnt_ticks);
							
							$html .= ob_get_clean();
							
							foreach( $dnt_ticks as $i => $tick_obj ) {
								
								ob_start();
							
								mtphr_dnt_tick_open( $tick_obj, $i, $id, $meta_data, $total );
								
								$tick = ( is_array($tick_obj) && isset($tick_obj['tick']) ) ? $tick_obj['tick'] : $tick_obj;
								echo $tick;
								
								mtphr_dnt_tick_close( $tick_obj, $i, $id, $meta_data, $total );
								
								$html .= ob_get_clean();
							}
							
							ob_start();
						}
				
						// Close the ticker container
						do_action( 'mtphr_dnt_bottom', $id, $meta_data );
					echo '</div>';
					do_action( 'mtphr_dnt_contents_after', $id, $meta_data, $total_ticks );
				echo '</div>';
				do_action( 'mtphr_dnt_after', $id, $meta_data, $total_ticks );
	
			echo '</div>';
		echo '</div>';

		$html .= ob_get_clean();
	}
	
	// Restore the original $wp_query
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();

	return $html;
}


/* --------------------------------------------------------- */
/* !Render the rotate ticker - 2.1.14 */
/* --------------------------------------------------------- */

function render_mtphr_dnt_rotate_ticker( $id='', $class='', $meta_data=false ) {
	
	$html = '';

	// Save the original $wp_query
	global $mtphr_dnt_ticker_types, $mtphr_dnt_meta_data, $mtphr_dnt_rotate_ticks;

	// Extract the metadata array into variables
	extract( $meta_data );
	
	// Add to the global ticker types
	$mtphr_dnt_ticker_types[$_mtphr_dnt_type] = $_mtphr_dnt_type;
	
	// Get the ticks to display
	if( $_mtphr_dnt_type == 'mixed' ) {
		$dnt_ticks = mtphr_dnt_mixed_ticks( $id, $meta_data );
	} else {
		$dnt_ticks = apply_filters( 'mtphr_dnt_tick_array', array(), $id, $meta_data );
	}
	
	// Transform the tick array
	$dnt_ticks = apply_filters( 'mtphr_dnt_tick_array_transform', $dnt_ticks, $id, $meta_data );
	
	// Reverse the ticker
	if( isset($_mtphr_dnt_reverse) && $_mtphr_dnt_reverse ) {
		$dnt_ticks = array_reverse( $dnt_ticks );
	}
	
	// Randomize the ticks
	if( isset($_mtphr_dnt_shuffle) && $_mtphr_dnt_shuffle ) {
		shuffle( $dnt_ticks );
	}
	
	// Add the post amount of ticks to the metadata
	$total_ticks = count( $dnt_ticks );
	
	// Add the post id & total ticks to the metadata
	$meta_data['_mtphr_dnt_id'] = $id;
	$meta_data['_mtphr_dnt_total_ticks'] = $total_ticks;
	
	// Save the metadata in a global variable
	$mtphr_dnt_meta_data = $meta_data;

	ob_start();

	// Add a unique id
	$tick_id = 'mtphr-dnt-'.$id;
	if( isset($_mtphr_dnt_unique_id) && ($_mtphr_dnt_unique_id != '') ) {
			$tick_id = 'mtphr-dnt-'.$id.'-'.sanitize_html_class( $_mtphr_dnt_unique_id );
	}
	
	// Add to the global carousel ticks
	$mtphr_dnt_rotate_ticks[$tick_id] = array();

	// Check for a set width
	$ticker_width = '';
	if( isset($_mtphr_dnt_ticker_width) && ($_mtphr_dnt_ticker_width != 0) ) {
		$ticker_width = ' style="width:'.intval($_mtphr_dnt_ticker_width).'px"';
	}

	echo '<div'.$ticker_width.' id="'.$tick_id.'" '.mtphr_dnt_ticker_class( $id, $class, $meta_data ).'>';
		echo '<div class="mtphr-dnt-wrapper mtphr-dnt-clearfix">';

			// Create and save element styles
			$padding = ( intval($_mtphr_dnt_rotate_padding) != 0 ) ? 'padding-top:'.intval($_mtphr_dnt_rotate_padding).'px;padding-bottom:'.intval($_mtphr_dnt_rotate_padding).'px;' : '';
			$margin = ( intval($_mtphr_dnt_rotate_margin) != 0 ) ? 'margin-top:'.intval($_mtphr_dnt_rotate_margin).'px;margin-bottom:'.intval($_mtphr_dnt_rotate_margin).'px;' : '';
		
			// Filter the variables
			$padding = apply_filters( 'mtphr_dnt_tick_container_padding', $padding );
			$margin = apply_filters( 'mtphr_dnt_tick_container_margin', $margin );
		
			// Create the container style
			$container_style = ( $padding != '' || $margin != '' ) ? ' style="'.$padding.$margin.'"' : '';
	
			// Open the ticker container
			do_action( 'mtphr_dnt_before', $id, $meta_data );
			echo '<div class="mtphr-dnt-tick-container"'.$container_style.'>';
				do_action( 'mtphr_dnt_contents_before', $id, $meta_data );
				echo '<div class="mtphr-dnt-tick-contents">';
					do_action( 'mtphr_dnt_top', $id, $meta_data );
			
					// Print out the ticks
					if( is_array($dnt_ticks) ) {
						
						$html .= ob_get_clean();
						
						foreach( $dnt_ticks as $i => $tick_obj ) {
							
							ob_start();
						
							mtphr_dnt_tick_open( $tick_obj, $i, $id, $meta_data, $total_ticks );
							
							$tick = ( is_array($tick_obj) && isset($tick_obj['tick']) ) ? $tick_obj['tick'] : $tick_obj;
							echo $tick;
							
							mtphr_dnt_tick_close( $tick_obj, $i, $id, $meta_data, $total_ticks );
							
							$html .= $mtphr_dnt_rotate_ticks[$tick_id][] = ob_get_clean();
						}
						
						ob_start();
					}
			
					// Close the ticker container
					do_action( 'mtphr_dnt_bottom', $id, $meta_data );
				echo '</div>';
				do_action( 'mtphr_dnt_contents_after', $id, $meta_data, $total_ticks );
			echo '</div>';
			do_action( 'mtphr_dnt_after', $id, $meta_data, $total_ticks );

		echo '</div>';
	echo '</div>';
	
	$html .= ob_get_clean();

	return $html;
}



/* --------------------------------------------------------- */
/* !Create the default ticks - 2.1.7 */
/* --------------------------------------------------------- */

function mtphr_dnt_default_ticks( $ticks, $id, $meta_data ) {

	if( $meta_data['_mtphr_dnt_type'] == 'default' ) {

		// Create an empty array to save ticks
		$new_ticks = array();

		// Get the ticks
		if( isset($meta_data['_mtphr_dnt_ticks']) && is_array($meta_data['_mtphr_dnt_ticks']) ) {
			foreach( $meta_data['_mtphr_dnt_ticks'] as $i => $tick ) {

				if( $text = $tick['tick'] ) {
					if( isset($meta_data['_mtphr_dnt_line_breaks']) && $meta_data['_mtphr_dnt_line_breaks'] ) {
						$text = nl2br($tick['tick']);
					}
					$text = do_shortcode(convert_chars(wptexturize($text)));

					// Get the contents
					if( $link = esc_url($tick['link']) ) {
						$nf = ( isset($tick['nofollow']) && $tick['nofollow'] ) ? ' rel="nofollow"' : '';
						$contents = '<a href="'.$link.'" target="'.$tick['target'].'"'.$nf.'>'.$text.'</a>';
					} else {
						$contents = $text;
					}
					$contents = apply_filters('mtphr_dnt_tick', $contents, $text, $link );

					// Save the output to the tick array
					$new_ticks[] = array(
						'tick' => $contents,
						'type' => 'default',
						'meta' => $tick
					);
				}
			}
		}

		// Return the new ticks
		return $new_ticks;
	}

	return $ticks;
}
add_filter( 'mtphr_dnt_tick_array', 'mtphr_dnt_default_ticks', 10, 3 );



/* --------------------------------------------------------- */
/* !Create the mixed ticks - 2.1.9 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_mixed_ticks') ) {
function mtphr_dnt_mixed_ticks( $id, $meta_data ) {

	global $mtphr_dnt_ticker_types;

	// Get all active ticker types
	$types = mtphr_dnt_types_array();
	
	// Create a cache of the ticks
	$ticks_cache = array();
	
	// Create an empty array to save ticks
	$dnt_ticks = array();

	// Get the ticks
	if( isset($meta_data['_mtphr_dnt_mixed_ticks']) && is_array($meta_data['_mtphr_dnt_mixed_ticks']) && count($meta_data['_mtphr_dnt_mixed_ticks']) > 0 ) {
		
		// Add a prefilter to the metadata
		$meta_data =  apply_filters( 'mtphr_dnt_mixed_ticks_meta', $meta_data, $id );
								
		foreach( $meta_data['_mtphr_dnt_mixed_ticks'] as $i => $tick ) {
			
			// Make sure the tick type exists
			if( array_key_exists( $tick['type'], $types ) && !array_key_exists( $tick['type'], $ticks_cache ) ) {
			
				// Add to the global tick types
				$mtphr_dnt_ticker_types[$tick['type']] = $tick['type'];
				
				// Cache the appropriate ticks
				$meta_data['_mtphr_dnt_type'] = $tick['type'];
				$ticks_cache[$tick['type']] = apply_filters( 'mtphr_dnt_tick_array', array(), $id, $meta_data );
			}

			// Add all ticks of the selected type to the array
			if( isset($tick['all']) && $tick['all'] == 'on' && isset($ticks_cache[$tick['type']]) ) {
			
				if( is_array($ticks_cache[$tick['type']]) && count($ticks_cache[$tick['type']]) > 0 ) {
					foreach( $ticks_cache[$tick['type']] as $i=>$mixed_tick ) {
						
						$mixed_tick = apply_filters( 'mtphr_dnt_mixed_tick', $mixed_tick, $tick );
						$type = (is_array($mixed_tick) && isset($mixed_tick['type'])) ? $mixed_tick['type'] : '';
						$content = (is_array($mixed_tick) && isset($mixed_tick['tick'])) ? $mixed_tick['tick'] : $mixed_tick;
						$meta = (is_array($mixed_tick) && isset($mixed_tick['meta']) && is_array($mixed_tick['meta'])) ? $mixed_tick['meta'] : array();
						$dnt_ticks[] = array(
							'type' => $type,
							'tick' => $content,
							'meta' => $meta
						);
					}
				}
				
			// Or, add just the select offset to the array
			} elseif( isset($ticks_cache[$tick['type']][intval($tick['offset'])]) ) {
				
				$mixed_tick = apply_filters( 'mtphr_dnt_mixed_tick', $ticks_cache[$tick['type']][intval($tick['offset'])], $tick );
				$type = (is_array($mixed_tick) && isset($mixed_tick['type'])) ? $mixed_tick['type'] : '';
				$content = (is_array($mixed_tick) && isset($mixed_tick['tick'])) ? $mixed_tick['tick'] : $mixed_tick;
				$meta = (is_array($mixed_tick) && isset($mixed_tick['meta']) && is_array($mixed_tick['meta'])) ? $mixed_tick['meta'] : array();
				$dnt_ticks[] = array(
					'type' => $type,
					'tick' => $content,
					'meta' => $meta
				);
			}
		}
	}

	// Return the new ticks
	return apply_filters( 'mtphr_dnt_mixed_tick_array', $dnt_ticks, $id, $meta_data );
}
}
