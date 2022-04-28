<?php

/**
 * Ditty Item Type Posts Lite class
 *
 * @package     Ditty Posts
 * @subpackage  Classes/Ditty Item Type Posts Lite
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Item_Type_Posts_Lite extends Ditty_Item_Type {
	
	/**
	 * Slug
	 *
	 * @since 3.0
	 */
	public $slug = 'posts_feed';
	
	/**
	 * Prepare items for Ditty use
	 *
	 * @access public
	 * @since  3.0
	 * @return array
	 */
	public function prepare_items( $meta ) {
		$item_value = maybe_unserialize( $meta['item_value'] );

		// Set the query args
		$query_args = array(
			'posts_per_page' 	=> $item_value['limit'],
		);
		$ditty_posts_query = new WP_Query( $query_args );
			
		$prepared_meta = array();
		if ( $ditty_posts_query->have_posts() ) : while ( $ditty_posts_query->have_posts() ) : $ditty_posts_query->the_post();	
			global $post;
			$item_value 				= maybe_unserialize( $meta['item_value'] );
			$item_value['item'] = $post;
			
			$ditty_item 								= $meta;
			$ditty_item['item_uniq_id'] = $ditty_item['item_id'] . '_' . get_the_ID();
			$ditty_item['item_value'] 	= $item_value;

			$prepared_meta[] = $ditty_item;
		
		endwhile;
			wp_reset_postdata();
		else :
		endif;

		return $prepared_meta;
	}
	
	/**
	 * Setup the type settings
	 *
	 * @access  public
	 * @since   3.0.18
	 */
	public function fields( $values = array() ) {					
		$fields = array(
			'limit' => array(
				'type'	=> 'number',
				'id'		=> 'limit',
				'name'	=> __( 'Limit', 'ditty-news-ticker' ),
				'help'	=> __( 'Set the number of Posts to display.', 'ditty-news-ticker' ),
				'std'		=> isset( $values['limit'] ) ? $values['limit'] : false,
			),
			'titleSettings' 	=> method_exists( $this, 'title_settings' ) ? $this->title_settings( $values ) : false,
			'contentSettings' => method_exists( $this, 'content_settings' ) ? $this->content_settings( $values ) : false,
			'linkSettings' 		=> method_exists( $this, 'link_settings' ) ? $this->link_settings( $values ) : false,
		);
		return apply_filters( 'ditty_item_type_fields', $fields, $this, $values );
	}
	
	/**
	 * Set the allowed layout tags
	 *
	 * @access  public
	 * @since   3.0.21
	 */
	public function layout_tags() {					
		$allowed_tags = array(
			'author_avatar',
			'author_bio',
			'author_name',
			'categories',
			'content',
			'excerpt',
			'icon',
			'image',
			'image_url',
			'permalink',
			'time',
			'title',
		);
		return $allowed_tags;
	}
	
	/**
	 * Set the default field values
	 *
	 * @access  public
	 * @since   3.0.18
	 */
	public function default_settings() {		
		$defaults = array(
			'limit' 				=> 10,
			'content_display' 		=> 'full',
			'excerpt_length'			=> 200,
			'excerpt_element'			=> 'default',
			'more'								=> esc_html__( 'Read More', 'ditty-news-ticker' ),
			'more_link'						=> 'post',
			'more_before'					=> '...&nbsp;',
			'more_after'					=> '',
			'title_element'				=> 'default',
			'title_link'					=> 'default',
			'link_target' 				=> '_self',
			'link_nofollow'				=> '',
			//'layout_tag_title'		=> array(),
		);
		return apply_filters( 'ditty_type_default_settings', $defaults, $this->slug );
	}
	
	/**
	 * Set the default layout variation types
	 *
	 * @access  public
	 * @since   3.0.3
	 */
	public function get_layout_variation_types() {
		$layout_variations = array(
			'default' => array(
				'template'	=> 'default_post',
				'label'				=> __( 'Default', 'ditty-news-ticker' ),
				'description' => __( 'Default variation.', 'ditty-news-ticker' ),
			),
		);
		return apply_filters( 'ditty_item_type_variation_types', $layout_variations, $this );
	}
	
	/**
	 * Display the editor preview
	 *
	 * @since    3.0
	 * @access   public
	 * @var      string    $preview    The editor list display of a item
	*/
	public function editor_preview( $value ) {
		$defaults = $this->default_settings();
		$args 		= wp_parse_args( $value, $defaults );
		$preview 	= sprintf( __( 'Displaying %d Posts' ), $args['limit'] );
		return $preview;
	}
}