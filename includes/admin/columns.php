<?php

/**
 * Set custom edit screen columns
 *
 * @since    3.1.5
 * @var      array    $new_columns
*/
function ditty_manage_posts_columns( $columns, $post_type = false ) {
	$new_columns = array();
	foreach( $columns as $key => $value ) {
		$new_columns[$key] = $value;
		if( 'title' === $key ) {
			switch( $post_type ) {
				case 'ditty_display':
					$new_columns['ditty_display_description'] = __( 'Description', 'ditty-news-ticker' );
					//$new_columns['ditty_display_version'] = __( 'Version', 'ditty-news-ticker' );
					$new_columns['ditty_display_type'] = __( 'Display Type', 'ditty-news-ticker' );
					break;
				case 'ditty_layout':
					$new_columns['ditty_layout_description'] = __( 'Description', 'ditty-news-ticker' );
					//$new_columns['ditty_layout_version'] = __( 'Version', 'ditty-news-ticker' );
					//$new_columns['ditty_layout_template'] = __( 'Layout Template', 'ditty-news-ticker' );
					break;
				case 'ditty':
					$new_columns['ditty_display_type'] = __( 'Display Type', 'ditty-news-ticker' );
					$new_columns['ditty_display'] = __( 'Display', 'ditty-news-ticker' );
					$new_columns['ditty_shortcode'] = __( 'Shortcode', 'ditty-news-ticker' );
					break;
			}
			
		}
	}
	return $new_columns;
}
add_filter( 'manage_posts_columns', 'ditty_manage_posts_columns', 10, 2 );

/**
 * Display the custom edit screen columns
 *
 * @since    3.1
*/
function ditty_manage_posts_custom_column( $column, $post_id ) {
	global $post;
	switch ( $column ) {	
		case 'ditty_display_type':
			if ( 'ditty_display' === $post->post_type ) {
				$display_type = get_post_meta( $post_id, '_ditty_display_type', true );
			} elseif ( 'ditty' === $post->post_type ) {
				$display_id = get_post_meta( $post_id, '_ditty_display', true );
				if ( is_array( $display_id ) ) {
					$display_type = $display_id['type'];
				} else {
					if ( 'publish' != get_post_status( $display_id ) ) {
						$display_type = false;
					} else {
						$display_type = get_post_meta( $display_id, '_ditty_display_type', true );
					}
				}
			}
			if ( ! $display_type ) {
				echo '---';
			} else {
				$label = $display_type;
				$display_types = ditty_display_types();
				foreach( $display_types as $slug => $display ) {
					if( $display_type === $slug ) {
						$label = $display['label'];
					}
				}
				echo "<a href='edit.php?post_type={$post->post_type}&ditty_display_type={$display_type}'>".$label."</a>";
			}
			break;
		case 'ditty_layout_template':
			$meta = get_post_meta( $post_id, '_ditty_layout_template', true );
			echo $meta;
			break;
		case 'ditty_layout_description':
			$description = get_post_meta( $post_id, '_ditty_layout_description', true );
			echo $description;
			break;
		case 'ditty_layout_version':
			$meta = get_post_meta( $post_id, '_ditty_layout_version', true );
			$label = $meta;
			echo $label;
			break;
		case 'ditty_display':
			$display_id = get_post_meta( $post_id, '_ditty_display', true );
			if ( $display_id ) {
				if ( is_array( $display_id ) ) {
					echo esc_html__( 'Custom', 'ditty-news-ticker' );
				} else {
					if ( 'publish' != get_post_status( $display_id ) ) {
						echo '---';
					} else {
						echo "<a href='edit.php?post_type={$post->post_type}&ditty_display={$display_id}'>" .get_the_title( $display_id ) . "</a> - <a href='" . get_edit_post_link( $display_id ) . "'>" . __( 'Edit', 'ditty-news-ticker' ) . "</a>";
					}
				}
			}
			break;
		case 'ditty_display_description':
			$description = get_post_meta( $post_id, '_ditty_display_description', true );
			echo $description;
			break;
		case 'ditty_display_version':
			$meta = get_post_meta( $post_id, '_ditty_display_version', true );
			$label = $meta;
			echo $label;
			break;
		case 'ditty_shortcode':
			echo "[ditty id={$post_id}]";
			break;
		default:
			break;
	}
}
add_action( 'manage_posts_custom_column',  'ditty_manage_posts_custom_column', 10, 2 );

/**
 * Add sortable columns
 *
 * @since    3.0
 * @var      array    $new_columns
*/
function ditty_display_sortable_columns( $columns ) {
	$columns['ditty_display_type'] = 'ditty_display_type';
	$columns['ditty_display_version'] = 'ditty_display_version';
	return $columns;
}
add_filter( 'manage_edit-ditty_display_sortable_columns', 'ditty_display_sortable_columns' );

function ditty_layout_sortable_columns( $columns ) {
	$columns['ditty_layout_template'] = 'ditty_layout_template';
	$columns['ditty_layout_version'] = 'ditty_layout_version';
	return $columns;
}
add_filter( 'manage_edit-ditty_layout_sortable_columns', 'ditty_layout_sortable_columns' );

function ditty_sortable_columns( $columns ) {
	$columns['ditty_display'] = 'ditty_display';
	$columns['ditty_display_type'] = 'ditty_display_type';
	return $columns;
}
add_filter( 'manage_edit-ditty_sortable_columns', 'ditty_sortable_columns' );

/**
 * Set the custom column order
 *
 * @since    3.0
 * @var      array    $vars
*/
function ditty_column_order_request( $vars ) {
	if ( isset( $vars['orderby'] ) && 'ditty_display_type' === $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key'	=> '_ditty_display_type',
			'orderby' 	=> 'meta_value',
		) );
	} elseif ( isset( $vars['orderby'] ) && 'ditty_layout_template' === $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key'	=> '_ditty_layout_template',
			'orderby' 	=> 'meta_value',
		) );
	} elseif ( isset( $vars['orderby'] ) && 'ditty_layout_version' === $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key'	=> '_ditty_layout_version',
			'orderby' 	=> 'meta_value',
		) );
	} elseif ( isset( $vars['orderby'] ) && 'ditty_display_version' === $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key'	=> '_ditty_display_version',
			'orderby' 	=> 'meta_value',
		) );
	} elseif ( isset( $vars['orderby'] ) && 'ditty_display' === $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key'	=> '_ditty_display',
			'orderby' 	=> 'meta_value',
		) );
	}

	return $vars;
}
add_filter( 'request', 'ditty_column_order_request' );

/**
 * Add custom edit screen filters
 *
 * @since    3.0
*/
function ditty_display_admin_screen_filters() {
	global $typenow;
	
	if( $typenow == 'ditty_display' || $typenow == 'ditty' ) {
		$display_type = isset( $_GET['ditty_display_type'] ) ? esc_html( $_GET['ditty_display_type'] ) : '';
		$display_types = ditty_display_types();	
		echo '<select name="ditty_display_type">';
			echo '<option value="">' . __( 'Show all Types', 'ditty-news-ticker' ) . '</option>';
			if( is_array( $display_types ) && count( $display_types ) > 0 ) {
				foreach( $display_types as $slug => $display ) {
					echo '<option value="' . $slug . '" '.selected( $slug, $display_type, false ) . '>' . $display['label'] . '</option>';
				}
			}
		echo '</select>';
		
	} elseif( $typenow == 'ditty_layout' ) {
		$layout_template = isset( $_GET['ditty_layout_template'] ) ? esc_html( $_GET['ditty_layout_template'] ) : '';
		$layout_templates = ditty_layout_templates();	
		echo '<select name="ditty_layout_template">';
			echo '<option value="">' . __( 'Show all Templates', 'ditty-news-ticker' ) . '</option>';
			if( is_array( $layout_templates ) && count( $layout_templates ) > 0 ) {
				foreach( $layout_templates as $template_slug => $template_data ) {
					echo '<option value="' . $template_slug . '" '.selected( $template_slug, $layout_template, false ) . '>' . $template_data['label'] . '</option>';
				}
			}
		echo '</select>';
	}
}
add_action( 'restrict_manage_posts', 'ditty_display_admin_screen_filters' );

/**
 * Filter the Ditty posts
 *
 * @since    3.0
 * @var      array    $qv
*/
function ditty_edit_parse_query( $query ) {  
  global $pagenow;
  $qv = &$query->query_vars;
  
  if ( $pagenow=='edit.php' && isset( $qv['post_type'] ) && 'ditty_display' == $qv['post_type'] ) { 
  	$meta_query = array();
	  if( isset( $_GET['ditty_display_type'] ) && $_GET['ditty_display_type'] != '' ) {
	  	$meta_query[] = array(
	  		'key' 	=> '_ditty_display_type',
				'value' => esc_html( $_GET['ditty_display_type'] ),
	  	);
	  }
	  if( count( $meta_query ) > 0 ) {
		  $qv['meta_query'] = $meta_query;
	  }
		
  } elseif ( $pagenow=='edit.php' && isset( $qv['post_type'] ) && 'ditty_layout' == $qv['post_type'] ) { 
		$meta_query = array();
		if( isset( $_GET['ditty_layout_template'] ) && $_GET['ditty_layout_template'] != '' ) {
			$meta_query[] = array(
				'key' 	=> 'ditty_layout_template',
				'value' => esc_html( $_GET['ditty_layout_template'] ),
			);
		}
		if( count( $meta_query ) > 0 ) {
			$qv['meta_query'] = $meta_query;
		}
	} elseif ( $pagenow=='edit.php' && isset( $qv['post_type'] ) && 'ditty' == $qv['post_type'] ) { 
		$meta_query = array();
		if( isset( $_GET['ditty_display'] ) && $_GET['ditty_display'] != '' ) {
			$meta_query[] = array(
				'key' 	=> '_ditty_display',
				'value' => esc_html( $_GET['ditty_display'] ),
			);
		}
		if( isset( $_GET['ditty_display_type'] ) && $_GET['ditty_display_type'] != '' ) {
			$meta_query[] = array(
				'key' 	=> '_ditty_display',
				'value' => esc_html( $_GET['ditty_display_type'] . '--' ),
				'compare' => 'LIKE'
			);
		}
		if( count( $meta_query ) > 0 ) {
			$qv['meta_query'] = $meta_query;
		}
	}
}
add_filter( 'parse_query', 'ditty_edit_parse_query' );