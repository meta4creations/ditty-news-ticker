<?php

/**
 * Ditty Item Type Posts Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Posts
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Item_Type_Posts extends Ditty_Item_Type {
	
	/**
	 * Slug
	 *
	 * @since 3.0
	 */
	public $slug = 'posts';
	
	/**
	 * Prepare items for Ditty use
	 *
	 * @access public
	 * @since  3.0
	 * @return array
	 */
	public function prepare_items( $meta ) {
		if ( is_object( $meta ) ) {
			$meta = ( array ) $meta;
		}
		$prepared_meta = array();
		$limit = isset( $meta['item_value']['posts_limit'] ) ? intval( $meta['item_value']['posts_limit'] ) : 5;
		$args = array(
			'posts_per_page' => $limit,
		);
		$ditty_posts = get_posts( apply_filters( 'ditty_posts_args', $args, $meta ) );
		if ( is_array( $ditty_posts ) && count( $ditty_posts ) > 0 ) {
			foreach ( $ditty_posts as $i => $post ) {
				if ( is_object( $post ) ) {
					$post = ( array ) $post; 
				}	
				$ditty_item = $meta;
				$ditty_item['item_uniq_id'] = $ditty_item['item_id'] . '_' . $post['ID'];
				
				$item_value 								= maybe_unserialize( $ditty_item['item_value'] );
				$item_value['post'] 				= $post;
				$ditty_item['item_value'] 	= $item_value;
				$prepared_meta[] = $ditty_item;
			}
		}
		return apply_filters( 'ditty_posts_prepared_meta', $prepared_meta );
	}

	/**
	 * Setup the type settings
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function settings( $item_values=false ) {	
		$values = $this->get_values( $item_values );
		$html = '';
		$fields = array(
			'posts_limit' => array(
				'type'	=> 'number',
				'id'		=> 'posts_limit',
				'name'	=> __( 'Limit', 'ditty-news-ticker' ),
				'help'	=> __( 'Set the number of latest posts to display.', 'ditty-news-ticker' ),
				'min'		=> 1,
				'step'	=> 1,
				'std'		=> $values['posts_limit'],	
			),
		);
		ditty_fields( $fields );
	}
	
	/**
	 * Set the metabox defaults
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function default_settings() {		
		$defaults = array(
			'posts_limit'	=> 5,
		);	
		return apply_filters( 'ditty_type_default_settings', $defaults, $this->slug );
	}
	
	/**
	 * Initialize the value for a item
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function init_item_value( $value = false ) {
		$defaults = $this->default_settings();
		if ( is_array( $value ) && isset( $value['posts_limit'] ) && '' != $value['posts_limit'] ) {
			return wp_parse_args( $value, $defaults );
		}
		return $defaults;
	}
	
	/**
	 * Sanitize user values
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function sanitize_settings( $values ) {		
		$sanized_values = array();
		$fields = $this->default_settings();
		if ( is_array( $fields ) && count( $fields ) > 0 ) {
			foreach ( $fields as $key => $default ) {
				if ( ! isset( $values[$key] ) ) {
					continue;
				}
				switch( $key ) {
					case 'posts_limit':
						$sanized_values[$key] = intval( $values[$key] );
						break;
					default:
						$sanized_values[$key] = wp_kses_post( $values[$key] );
						break;
				}
			}
		}
		return $sanized_values;
	}

	/**
	 * Display the editor preview
	 *
	 * @since    3.0
	 * @access   public
	 * @var      string    $preview    The editor list display of a item
	*/
	public function editor_preview( $value ) {
		$limit = isset( $value['posts_limit'] ) ? intval( $value['posts_limit'] ) : 5;
		$preview = sprintf( __( 'Displaying the latest %d posts' ), $limit );
		return $preview;	
	}	
}