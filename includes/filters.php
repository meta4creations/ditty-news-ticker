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
/* !Make a grid out of the ticks - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_tick_grid( $dnt_ticks, $id, $meta_data ) {

	// Extract the metadata array into variables
	extract( $meta_data );

	if( isset($_mtphr_dnt_grid) && $_mtphr_dnt_grid ) {

		$grid_ticks = array();
		
		$total = count( $dnt_ticks );
		$col_counter = 0;
		$row_counter = 0;
		
		$style = 'style="';
			$style .= ( $_mtphr_dnt_grid_equal_width ) ? 'width:'.(100/$_mtphr_dnt_grid_cols).'%;' : '';
			$style .= ( $_mtphr_dnt_grid_padding > 0 ) ? 'padding:'.$_mtphr_dnt_grid_padding.'px;' : '';
		$style .= '"';
		
		$data = '<table class="mtphr-dnt-grid">';
			$data .= '<tr class="mtphr-dnt-grid-row mtphr-dnt-grid-row-'.($row_counter+1).'">';
			
			if( is_array($dnt_ticks) ) {
				foreach( $dnt_ticks as $i=>$tick ) {
					
					// Get the type and tick
					$type = ( $_mtphr_dnt_type == 'mixed' ) ? $tick['type'] : $_mtphr_dnt_type;
					$tick = ( $_mtphr_dnt_type == 'mixed' ) ? $tick['tick'] : $tick;
	
					$data .= '<td class="mtphr-dnt-grid-item mtphr-dnt-grid-item-'.($col_counter+1).' mtphr-dnt-grid-item-'.$type.'" '.$style.'>'.$tick.'</td>';
					
					$col_counter++;
					
					if( (($i+1)%$_mtphr_dnt_grid_cols == 0) && ($i < $total-1) ) {
	
						$data .= '</tr>';
						
						$row_counter++;
						if( (($row_counter)%$_mtphr_dnt_grid_rows == 0) ) {
							$data .= '</table>';
							
							// Add to the tick array
							$grid_ticks[] = ( $_mtphr_dnt_type == 'mixed' ) ? array( 'type'=>'mixed-grid', 'tick'=>$data ) : $data;
							
							$data = '<table class="mtphr-dnt-grid">';
							$row_counter = 0;
						}
	
						$data .= '<tr class="mtphr-dnt-grid-row mtphr-dnt-grid-row-'.($row_counter+1).'">';
						$col_counter = 0;
					}
				}
			}
			
			// Fill any empty columns
			$empty_cols = $_mtphr_dnt_grid_cols - $col_counter;
			for( $i=0; $i<$empty_cols; $i++ ) {
				$data .= '<td class="mtphr-dnt-grid-item mtphr-dnt-grid-item-'.($col_counter+1).'" '.$style.'></td>';
				$col_counter++;
			}
			
			// Fill any emptry rows
			if( $_mtphr_dnt_grid_empty_rows ) {
				$empty_row = $_mtphr_dnt_grid_rows - ($row_counter+1);
				for( $i=0; $i<$empty_row; $i++ ) {
					$row_counter++;
					$col_counter = 0;
					$data .= '<tr class="mtphr-dnt-grid-row mtphr-dnt-grid-row-'.($row_counter+1).'">';
						for( $e=0; $e<$_mtphr_dnt_grid_cols; $e++ ) {
							$data .= '<td class="mtphr-dnt-grid-item mtphr-dnt-grid-item-'.($col_counter+1).'" '.$style.'></td>';
							$col_counter++;
						}
					$data .= '</tr>';
				}
			}
	
			$data .= '</tr>';
		$data .= '</table>';
		
		// Add to the tick array
		$grid_ticks[] = ( $_mtphr_dnt_type == 'mixed' ) ? array( 'type'=>'mixed-grid', 'tick'=>$data ) : $data;

		return $grid_ticks;
	}
	
	return $dnt_ticks;
}
add_filter( 'mtphr_dnt_tick_array_transform', 'mtphr_dnt_tick_grid', 10, 3 );


/* --------------------------------------------------------- */
/* !Add the control nav for rotating ticks - 1.4.5 */
/* --------------------------------------------------------- */

function mtphr_dnt_direction_nav( $id, $meta_data, $total ) {

	// Extract the metadata array into variables
	extract( $meta_data );

	// Add the control nav
	if( ($total > 1) && $_mtphr_dnt_mode == 'rotate' ) {

		// Add the directional nav
		if( isset($_mtphr_dnt_rotate_directional_nav) ) {
			if( $_mtphr_dnt_rotate_directional_nav ) {

				$hide = '';
				if( isset($_mtphr_dnt_rotate_directional_nav_hide) && $_mtphr_dnt_rotate_directional_nav_hide ) {
					$hide = ' mtphr-dnt-nav-hide';
				}
				echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-prev'.$hide.'" href="#" rel="nofollow">'.apply_filters( 'mtphr_dnt_direction_nav_prev', '<i class="mtphr-dnt-icon-arrow-left"></i>' ).'</a>';
				echo '<a class="mtphr-dnt-nav mtphr-dnt-nav-next'.$hide.'" href="#" rel="nofollow">'.apply_filters( 'mtphr_dnt_direction_nav_next', '<i class="mtphr-dnt-icon-arrow-right"></i>' ).'</a>';
			}
		}
	}
}
add_action( 'mtphr_dnt_contents_after', 'mtphr_dnt_direction_nav', 10, 3 );



/* --------------------------------------------------------- */
/* !Add the control nav for rotating ticks - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_control_nav( $id, $meta_data, $total ) {

	// Extract the metadata array into variables
	extract( $meta_data );

	// Add the control nav
	if( ($total > 1) && $_mtphr_dnt_mode == 'rotate' ) {
		if( isset($_mtphr_dnt_rotate_control_nav) && $_mtphr_dnt_rotate_control_nav ) {
		
			echo '<div class="mtphr-dnt-control-links">';
				for( $i=0; $i<$total; $i++ ) {
					$link = ( $_mtphr_dnt_rotate_control_nav_type == 'button' ) ? '<i class="mtphr-dnt-icon-button"></i>' : intval($i+1);
					echo '<a class="mtphr-dnt-control mtphr-dnt-control-'.$_mtphr_dnt_rotate_control_nav_type.'" href="'.$i.'" rel="nofollow">'.apply_filters( 'mtphr_dnt_control_nav', $link, $_mtphr_dnt_rotate_control_nav_type ).'</a>';
				}
			echo '</div>';
		}
	}
}
add_action( 'mtphr_dnt_after', 'mtphr_dnt_control_nav', 10, 3 );



/* --------------------------------------------------------- */
/* !Add the pagination for list ticks - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_pagination( $id, $meta_data, $total ) {

	// Extract the metadata array into variables
	extract( $meta_data );

	if( $_mtphr_dnt_mode == 'list' && isset($_mtphr_dnt_list_tick_paging) && $_mtphr_dnt_list_tick_paging ) {
		
		$spacing = 'margin-top:'.intval($_mtphr_dnt_list_tick_spacing).'px;';
		$total_pages = ceil( $total/$_mtphr_dnt_list_tick_count );
		$current_page = isset( $_GET['tickpage'] ) ? $_GET['tickpage'] : 1;
		
		$big = 999999999;
		$args = array(
			'base'         => str_replace( $big, '%#%', add_query_arg('tickpage', $big) ),
			'format'       => '?tickpage=%#%',
			'total'        => $total_pages,
			'current'      => $current_page,
			'show_all'		 => false,
			'end_size'     => 1,
			'mid_size'     => 2,
			'prev_next'    => $_mtphr_dnt_list_tick_prev_next,
			'prev_text'    => $_mtphr_dnt_list_tick_prev_text,
			'next_text'    => $_mtphr_dnt_list_tick_next_text,
			'type'         => 'plain',
			'add_args'     => false,
			'add_fragment' => ''
		);
		
		echo '<div class="mtphr-dnt-tick-paging" style="'.$spacing.'">';
			echo paginate_links( apply_filters( 'mtphr_dnt_tick_paging_args', $args ) );
		echo '</div>';
	}
}
add_action( 'mtphr_dnt_after', 'mtphr_dnt_pagination', 10, 3 );



/* --------------------------------------------------------- */
/* !Add tickers to the global - 1.4.2 */
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

