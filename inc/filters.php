<?php

/* --------------------------------------------------------- */
/* !Filter the post content to display the ticker - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_content( $content ) {

	if( get_post_type() == 'ditty_news_ticker' ) {
		return '[ditty_news_ticker id="'.get_the_id().'"]';
	}	
	
	return $content;
}
add_filter( 'the_content', 'mtphr_dnt_content' );



/* --------------------------------------------------------- */
/* !Add oEmbed to default ticks - 1.5.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_oembed() {
	
	global $wp_embed;
	add_filter( 'mtphr_dnt_tick', array( $wp_embed, 'autoembed' ), 8 );
}
add_action( 'init', 'mtphr_dnt_oembed' );



/* --------------------------------------------------------- */
/* !Make a grid out of the ticks - 2.2.8 */
/* --------------------------------------------------------- */

function mtphr_dnt_tick_grid( $dnt_ticks, $id, $meta_data ) {

	$defaults = array(
		'_mtphr_dnt_type' => 0,
		'_mtphr_dnt_grid' => 0,
		'_mtphr_dnt_grid_empty_rows' => 0,
		'_mtphr_dnt_grid_equal_width' => 0,
		'_mtphr_dnt_grid_cols' => 2,
		'_mtphr_dnt_grid_rows' => 2,
		'_mtphr_dnt_grid_padding' => 5,
		'_mtphr_dnt_grid_remove_padding' => 0,
	);	
	$args = wp_parse_args( $meta_data, $defaults );
	
	if( $args['_mtphr_dnt_grid'] ) {

		$grid_ticks = array();
		
		$total = count( $dnt_ticks );
		$col_counter = 0;
		$row_counter = 0;
		
		$style = 'style="';
			$style .= ( $args['_mtphr_dnt_grid_equal_width'] ) ? 'width:'.( 100 / $args['_mtphr_dnt_grid_cols'] ).'%;' : '';
			$style .= ( intval( $args['_mtphr_dnt_grid_padding'] ) > 0 ) ? 'padding:' . intval( $args['_mtphr_dnt_grid_padding'] ) . 'px;' : '';
		$style .= '"';
		
		$extra_classes = ( $args['_mtphr_dnt_grid_remove_padding'] ) ? ' mtphr-dnt-grid-remove-padding' : '';
		
		$data = '<table class="mtphr-dnt-grid'.$extra_classes.'">';
			$data .= '<tr class="mtphr-dnt-grid-row mtphr-dnt-grid-row-'.($row_counter+1).'">';
			
			if( is_array($dnt_ticks) ) {
				foreach( $dnt_ticks as $i=>$tick ) {
					
					// Get the type and tick
					$type = ( is_array($tick) && isset($tick['type']) ) ? $tick['type'] : $args['_mtphr_dnt_type'];
					$tick = ( is_array($tick) && isset($tick['tick']) ) ? $tick['tick'] : $tick;
	
					$data .= '<td class="mtphr-dnt-grid-item mtphr-dnt-grid-item-'.($col_counter+1).' mtphr-dnt-grid-item-'.$type.'" '.$style.'>'.$tick.'</td>';
					
					$col_counter++;
					
					if( ( ( $i+1 ) % $args['_mtphr_dnt_grid_cols'] == 0 ) && ( $i < $total-1 ) ) {
	
						$data .= '</tr>';
						
						$row_counter++;
						if( ( ( $row_counter ) % $args['_mtphr_dnt_grid_rows'] == 0 ) ) {
							$data .= '</table>';
							
							// Add to the tick array
							$grid_ticks[] = ( 'mixed' == $args['_mtphr_dnt_type'] ) ? array( 'type'=>'mixed-grid', 'tick'=>$data ) : $data;
							
							$data = '<table class="mtphr-dnt-grid'.$extra_classes.'">';
							$row_counter = 0;
						}
	
						$data .= '<tr class="mtphr-dnt-grid-row mtphr-dnt-grid-row-'.($row_counter+1).'">';
						$col_counter = 0;
					}
				}
			}
			
			// Fill any empty columns
			$empty_cols = $args['_mtphr_dnt_grid_cols'] - $col_counter;
			for( $i=0; $i<$empty_cols; $i++ ) {
				$data .= '<td class="mtphr-dnt-grid-item mtphr-dnt-grid-item-'.($col_counter+1).'" '.$style.'></td>';
				$col_counter++;
			}
			
			// Fill any emptry rows
			if( $args['_mtphr_dnt_grid_empty_rows'] ) {
				$empty_row = $args['_mtphr_dnt_grid_rows'] - ( $row_counter + 1 );
				for( $i=0; $i<$empty_row; $i++ ) {
					$row_counter++;
					$col_counter = 0;
					$data .= '<tr class="mtphr-dnt-grid-row mtphr-dnt-grid-row-'.($row_counter+1).'">';
						for( $e=0; $e < $args['_mtphr_dnt_grid_cols']; $e++ ) {
							$data .= '<td class="mtphr-dnt-grid-item mtphr-dnt-grid-item-'.($col_counter+1).'" '.$style.'></td>';
							$col_counter++;
						}
					$data .= '</tr>';
				}
			}
	
			$data .= '</tr>';
		$data .= '</table>';
		
		// Add to the tick array
		$grid_ticks[] = ( 'mixed' == $args['_mtphr_dnt_type'] ) ? array( 'type'=>'mixed-grid', 'tick'=>$data ) : $data;

		return $grid_ticks;
	}
	
	return $dnt_ticks;
}
add_filter( 'mtphr_dnt_tick_array_transform', 'mtphr_dnt_tick_grid', 10, 3 );



/* --------------------------------------------------------- */
/* !Add tickers to the global - 1.5.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_add_to_global( $id, $meta_data ) {

	// Extract the metadata array into variables
	extract( $meta_data );

	// Add to the global script variable
	if( $_mtphr_dnt_mode == 'scroll' || $_mtphr_dnt_mode == 'rotate' ) {

		global $mtphr_dnt_ticker_scripts;

		// Add a unique id class, if there is one
		if( isset($_mtphr_dnt_unique_id) ) {
			if( $_mtphr_dnt_unique_id != '' ) {
				$id = $id.'-'.sanitize_html_class( $_mtphr_dnt_unique_id );
			}
		}
		
		$ticker = '#mtphr-dnt-'.$id;

		$scroll_pause = 0; $scroll_init = 0; $disable_touchswipe = 0;
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
		if( isset($_mtphr_dnt_rotate_disable_touchswipe) ) {
			$disable_touchswipe = $_mtphr_dnt_rotate_disable_touchswipe ? 1 : 0;
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
			'disable_touchswipe' => $disable_touchswipe,
			'offset' => $offset
		);
	}
}
add_action( 'mtphr_dnt_after', 'mtphr_dnt_add_to_global', 10, 2 );



/* --------------------------------------------------------- */
/* !Add an edit link to the tickers - 1.4.5 */
/* --------------------------------------------------------- */

function mtphr_dnt_tick_edit_link( $id ) {
	if( current_user_can('edit_pages') ) {
		$settings = mtphr_dnt_general_settings();
		if( isset($settings['edit_links']) && $settings['edit_links'] ) {
			echo '<a class="mtphr-dnt-edit-link" href="'.get_edit_post_link( $id ).'">'.__('<i class="mtphr-dnt-icon-gear"></i> Edit ticker', 'ditty-news-ticker').'</a>';
		}
	}
}
add_action( 'mtphr_dnt_before', 'mtphr_dnt_tick_edit_link' );



/* --------------------------------------------------------- */
/* !Display the ticker title - 1.5.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_tick_title() {
	mtphr_dnt_get_template_part( 'title' );
}
add_action( 'mtphr_dnt_before', 'mtphr_dnt_tick_title' );



/* --------------------------------------------------------- */
/* !Add the control nav for rotating ticks - 1.5.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_direction_nav() {
	mtphr_dnt_get_template_part( 'directional_nav' );
}
add_action( 'mtphr_dnt_contents_after', 'mtphr_dnt_direction_nav' );



/* --------------------------------------------------------- */
/* !Add the control nav for rotating ticks - 1.5.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_control_nav() {
	mtphr_dnt_get_template_part( 'control_nav' );
}
add_action( 'mtphr_dnt_after', 'mtphr_dnt_control_nav' );



/* --------------------------------------------------------- */
/* !Add the pagination for list ticks - 1.5.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_pagination() {
	mtphr_dnt_get_template_part( 'pagination' );
}
add_action( 'mtphr_dnt_after', 'mtphr_dnt_pagination' );



/* --------------------------------------------------------- */
/* !Add a play/pause button - 2.0.4 */
/* --------------------------------------------------------- */

function mtphr_dnt_playpause() {
	
	// Get and extract the metadata array into variables
	global $mtphr_dnt_meta_data;
	extract( $mtphr_dnt_meta_data );
	
	if( $_mtphr_dnt_mode == 'scroll' || ($_mtphr_dnt_mode == 'rotate' && $_mtphr_dnt_auto_rotate) ) {
		if( isset($_mtphr_dnt_pause_button) && $_mtphr_dnt_pause_button ) {
			mtphr_dnt_get_template_part( 'play_pause' );
		}
	}
}
add_action( 'mtphr_dnt_after', 'mtphr_dnt_playpause' );


/**
 * Remove the widget if there are no ticks
 *
 * @access  public
 * @since   2.1.10
 */

function mtphr_dnt_remove_widgets( $params ) {

	if( !is_admin() ) {
		
		$i = count($params);
		while( $i --> 0 ) {
			if( isset($params[$i]['widget_id']) && (strpos($params[$i]['widget_id'], 'mtphr-dnt-widget') !== false) ) {
				echo '<pre>';print_r($params);echo '</pre>';
				//unset( $params[$i] );
			}
		}
	}

	return $params;
}
//add_filter( 'dynamic_sidebar_params', 'mtphr_dnt_remove_widgets' );


