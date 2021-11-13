<?php

/* --------------------------------------------------------- */
/* !Set custom edit screen columns - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_set_columns( $columns ){

	$new_columns = array();
	$i = 0;
	foreach( $columns as $key => $value ) {
		if( $i == 2 ) {
			$new_columns['dnt_type'] = __( 'Type', 'ditty-news-ticker' );
			$new_columns['dnt_mode'] = __( 'Mode', 'ditty-news-ticker' );
			$new_columns['dnt_shortcode'] = __( 'Shortcode', 'ditty-news-ticker' );
			$new_columns['dnt_function'] = __( 'Direct Function', 'ditty-news-ticker' );
		}
		$new_columns[$key] = $value;
		$i++;
	}
	return $new_columns;
}
add_filter( 'manage_ditty_news_ticker_posts_columns', 'mtphr_dnt_set_columns' );



/* --------------------------------------------------------- */
/* !Display the custom edit screen columns - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_dnt_display_columns( $column, $post_id ){

	global $post;

	switch ( $column ) {
			
		case 'dnt_type':
		
			// Get the current type
			$meta = get_post_meta( $post_id, '_mtphr_dnt_type', true );
			$label = $meta;
			$types = mtphr_dnt_types_array();
			foreach( $types as $i => $type ) {
				if( $meta == $i ) {
					$label = $type['button'];
				}
			}
			
			echo "<a href='edit.php?post_type={$post->post_type}&dnt_type={$meta}'>".$label."</a>";
			break;

		case 'dnt_mode':
			
			// Get the current mode
			$meta = get_post_meta( $post_id, '_mtphr_dnt_mode', true );
			$label = $meta;
			$modes = mtphr_dnt_modes_array();
			foreach( $modes as $i => $mode ) {
				if( $meta == $i ) {
					$label = $mode['button'];
				}
			}
			
			echo "<a href='edit.php?post_type={$post->post_type}&dnt_mode={$meta}'>".$label."</a>";
			break;
			
		case 'dnt_shortcode':
			echo '<pre><p>[ditty_news_ticker id="'.$post_id.'"]</p></pre>';
			break;
			
		case 'dnt_function':
			echo '<pre><p>&lt;?php if(function_exists(\'ditty_news_ticker\')){ditty_news_ticker('.$post->ID.');} ?&gt;</p></pre>';
			break;
	}
}
add_action( 'manage_ditty_news_ticker_posts_custom_column',  'mtphr_dnt_display_columns', 10, 2 );




/* --------------------------------------------------------- */
/* !Add sortable columns - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_sortable_columns( $columns ) {
	
	$columns['dnt_type'] = 'dnt_type';
	$columns['dnt_mode'] = 'dnt_mode';

	return $columns;
}
add_filter( 'manage_edit-ditty_news_ticker_sortable_columns', 'mtphr_dnt_sortable_columns' );



/* --------------------------------------------------------- */
/* !Set the custom column order - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_column_order_request( $vars ) {
	
	if ( isset( $vars['orderby'] ) && 'dnt_type' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_mtphr_dnt_type',
			'orderby' => 'meta_value'
		));
	}
	
	if ( isset( $vars['orderby'] ) && 'dnt_mode' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => '_mtphr_dnt_mode',
			'orderby' => 'meta_value'
		));
	}
	 
	return $vars;
}
add_filter( 'request', 'mtphr_dnt_column_order_request' );



/* --------------------------------------------------------- */
/* !Add custom edit screen filters - 1.4.5 */
/* --------------------------------------------------------- */

function mtphr_dnt_edit_screen_filters() {

	global $typenow;
	
	if( $typenow == 'ditty_news_ticker' ) {
		$dnt_type = isset($_GET['dnt_type']) ? esc_html($_GET['dnt_type']) : '';
		$dnt_mode = isset($_GET['dnt_mode']) ? esc_html($_GET['dnt_mode']) : '';
		
		$types = mtphr_dnt_types_array();
		$modes = mtphr_dnt_modes_array();
		
		echo '<select name="dnt_type">';
			echo '<option value="">'.__('Show all Types', 'ditty-news-ticker').'</option>';
			if( is_array($types) && count($types) > 0 ) {
				foreach( $types as $i=>$type ) {
					echo '<option value="'.$i.'" '.selected($i, $dnt_type, false).'>'.$type['button'].'</option>';
				}
			}
		echo '</select>';
		
		echo '<select name="dnt_mode">';
			echo '<option value="">'.__('Show all Modes', 'ditty-news-ticker').'</option>';
			if( is_array($modes) && count($modes) > 0 ) {
				foreach( $modes as $i=>$mode ) {
					echo '<option value="'.$i.'" '.selected($i, $dnt_mode, false).'>'.$mode['button'].'</option>';
				}
			}
		echo '</select>';
	}
}
add_action( 'restrict_manage_posts','mtphr_dnt_edit_screen_filters' );



/* --------------------------------------------------------- */
/* !Filter the list of tickers - 2.2.4 */
/* --------------------------------------------------------- */

function mtphr_dnt_parse_query( $query ) {
  
  global $pagenow;
  $qv = &$query->query_vars;
  
  if ( $pagenow=='edit.php' && isset( $qv['post_type'] ) && 'ditty_news_ticker' == $qv['post_type'] ) {
  
  	$meta_query = array();

	  if( isset($_GET['dnt_type']) && $_GET['dnt_type'] != '' ) {
	  	$meta_query[] = array(
	  		'key' => '_mtphr_dnt_type',
				'value' => esc_html($_GET['dnt_type']),
	  	);
	  }
	  
	  if( isset($_GET['dnt_mode']) && $_GET['dnt_mode'] != '' ) {
	  	$meta_query[] = array(
	  		'key' => '_mtphr_dnt_mode',
				'value' => esc_html($_GET['dnt_mode']),
	  	);
	  }
	  
	  if( count($meta_query) > 0 ) {
		  $qv['meta_query'] = $meta_query;
	  }
  }
}
add_filter( 'parse_query','mtphr_dnt_parse_query' );