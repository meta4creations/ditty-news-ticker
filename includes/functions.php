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
 * @since 1.4.2
 */
function get_mtphr_dnt_ticker( $id='', $class='', $atts=false ) {
	
	// Switch the post based on the selected language
	if( function_exists('icl_object_id') ) {
		$id = icl_object_id( $id, 'ditty_news_ticker', true );
	}
	

	// Get the post
	$ticker = get_post( $id );
	if( $ticker && $ticker->post_status == 'publish' ) {

		// Save the original $wp_query
		global $wp_query, $mtphr_dnt_ticker_types;
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
		
		// Get the total amount of ticks
		$total_ticks = count( $dnt_ticks );

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

				// Display the title
				if( isset($_mtphr_dnt_title) && $_mtphr_dnt_title ) {
			
					$inline_title = '';
					if( isset($_mtphr_dnt_inline_title) && $_mtphr_dnt_inline_title ) {
						$inline_title = ' mtphr-dnt-inline-title';
					}
		
					do_action( 'mtphr_dnt_title_before', $id, $meta_data );
					echo '<h3 class="mtphr-dnt-title'.$inline_title.'">'.apply_filters( 'mtphr_dnt_ticker_title', $ticker->post_title ).'</h3>';
					do_action( 'mtphr_dnt_title_after', $id, $meta_data );
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
					do_action( 'mtphr_dnt_contents_before', $id, $meta_data );
					echo '<div class="mtphr-dnt-tick-contents">';
						do_action( 'mtphr_dnt_top', $id, $meta_data );
				
						// Print out the ticks
						if( is_array($dnt_ticks) ) {
							
							// Grab the paged ticks
							if( $_mtphr_dnt_mode == 'list' && (isset($_mtphr_dnt_list_tick_paging) && $_mtphr_dnt_list_tick_paging) ) {
								$page = isset( $_GET['tickpage'] ) ? $_GET['tickpage'] : 1;
								$offset = ($page-1) * $_mtphr_dnt_list_tick_count;
								$dnt_ticks = array_slice( $dnt_ticks, $offset, $_mtphr_dnt_list_tick_count );
							}
				
							// Randomize the ticks
							if( isset($_mtphr_dnt_shuffle) && $_mtphr_dnt_shuffle ) {
								shuffle( $dnt_ticks );
							}
							$total = count($dnt_ticks);
							foreach( $dnt_ticks as $i => $tick ) {
							
								$type = ( $_mtphr_dnt_type == 'mixed' ) ? $tick['type'] : $_mtphr_dnt_type;
								$tick = ( $_mtphr_dnt_type == 'mixed' ) ? $tick['tick'] : $tick;
				
								// Set the list spacing depending on the tick position
								if( $_mtphr_dnt_mode == 'list' ) {
									$spacing = ( $i != intval($total-1) ) ? 'margin-bottom:'.intval($_mtphr_dnt_list_tick_spacing).'px;' : '';
								}
								$spacing = apply_filters( 'mtphr_dnt_list_tick_spacing', $spacing, $i, $total );
								$tick_style = ( $width != '' || $height != '' || $spacing != '' ) ? ' style="'.$width.$height.$spacing.'"' : '';
				
								do_action( 'mtphr_dnt_tick_before', $id, $meta_data, $total, $i );
								echo '<div'.$tick_style.' '.mtphr_dnt_tick_class('mtphr-dnt-'.$type.'-tick mtphr-dnt-clearfix').'>';
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
					do_action( 'mtphr_dnt_contents_after', $id, $meta_data, $total_ticks );
				echo '</div>';
				do_action( 'mtphr_dnt_after', $id, $meta_data, $total_ticks );
	
			echo '</div>';
		echo '</div>';

		// Restore the original $wp_query
		$wp_query = null;
		$wp_query = $original_query;
		wp_reset_postdata();
	}

	// Return the output
	return ob_get_clean();
}



/* --------------------------------------------------------- */
/* !Create the default ticks - 1.3.1 */
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
					$text = convert_chars(wptexturize($text));

					// Get the contents
					if( $link = esc_url($tick['link']) ) {
						$nf = ( isset($tick['nofollow']) && $tick['nofollow'] ) ? ' rel="nofollow"' : '';
						$contents = '<a href="'.$link.'" target="'.$tick['target'].'"'.$nf.'>'.$text.'</a>';
					} else {
						$contents = $text;
					}
					$contents = apply_filters('mtphr_dnt_tick', $contents, $text, $link );

					// Save the output to the tick array
					$new_ticks[] = $contents;
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
/* !Create the mixed ticks - 1.4.0 */
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
		foreach( $meta_data['_mtphr_dnt_mixed_ticks'] as $i => $tick ) {

			// Make sure the tick type exists
			if( array_key_exists( $tick['type'], $types ) && !array_key_exists( $tick['type'], $ticks_cache ) ) {
			
				// Add to the global tick types
				$mtphr_dnt_ticker_types[$tick['type']] = $tick['type'];
				
				// Cache the appropriate ticks
				$meta_data['_mtphr_dnt_type'] = $tick['type'];
				$ticks_cache[$tick['type']] = apply_filters( 'mtphr_dnt_tick_array', array(), $id, $meta_data );
			}
			
			// Add the appropriate tick to the tick array
			if( isset($ticks_cache[$tick['type']][intval($tick['offset'])]) ) {
				$dnt_ticks[] = array(
					'type' => $tick['type'],
					'tick' => $ticks_cache[$tick['type']][intval($tick['offset'])]
				);
			}
		}
	}

	// Return the new ticks
	return $dnt_ticks;
}
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

