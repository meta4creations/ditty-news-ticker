<?php
/**
 * Custom edit columns
 *
 * @package Ditty News Ticker
 */




add_filter( 'manage_ditty_news_ticker_posts_columns', 'mtphr_dnt_set_columns' );
/**
 * Set custom edit screen columns
 *
 * @since 1.0
 */
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




add_action( 'manage_ditty_news_ticker_posts_custom_column',  'mtphr_dnt_display_columns', 10, 2 );
/**
 * Display the custom edit screen columns
 *
 * @since 1.0.5
 */
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




add_filter( 'manage_edit-ditty_news_ticker_sortable_columns', 'mtphr_dnt_sortable_columns' );
/**
 * Add sortable columns
 *
 * @since 1.0.0
 */
function mtphr_dnt_sortable_columns( $columns ) {
	
	$columns['dnt_type'] = 'dnt_type';
	$columns['dnt_mode'] = 'dnt_mode';

	return $columns;
}




add_filter( 'request', 'mtphr_dnt_column_order_request' );
/**
 * Set the custom column order
 *
 * @since 1.0.0
 */
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




add_filter( 'parse_query','mtphr_dnt_parse_query' );
/**
 * Filter the list of tickers
 *
 * @since 1.0.0
 */
function mtphr_dnt_parse_query( $query ) {
  
  global $pagenow;
  $qv = &$query->query_vars;
  
  if ( $pagenow=='edit.php' && $qv['post_type']=='ditty_news_ticker' ) {
	  
	  if( isset($_GET['dnt_type']) ) {
	  	$qv['meta_key'] = '_mtphr_dnt_type';
	  	$qv['meta_value'] = $_GET['dnt_type'];
	  }
	  
	  if( isset($_GET['dnt_mode']) ) {
	  	$qv['meta_key'] = '_mtphr_dnt_mode';
	  	$qv['meta_value'] = $_GET['dnt_mode'];
	  }
  }
}



