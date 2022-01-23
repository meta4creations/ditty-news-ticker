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
	 * @since   3.0.12
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
			'contentSettings' => array(
				'type' 							=> 'group',
				'id'								=> 'contentSettings',
				'collapsible'				=> true,
				'default_state'			=> 'expanded',
				'multiple_fields'		=> true,
				'name' 	=> __( 'Content Settings', 'ditty-news-ticker' ),
				'help' 	=> __( 'Configure the content settings for the feed items.', 'ditty-news-ticker' ),
				'fields' => array(
					'content_display' => array(
						'type'			=> 'radio',
						'id'				=> 'content_display',
						'name'			=> __( 'Content Display', 'ditty-news-ticker' ),
						'help'			=> __( 'Configure settings for the post content.', 'ditty-news-ticker' ),
						'options'		=> array(
							'full'		=> __( 'Full Content', 'ditty-news-ticker' ),
							'excerpt'	=> __( 'Excerpt', 'ditty-news-ticker' ),
						),
						'inline' 		=> true,
						'std'		=> isset( $values['content_display'] ) ? $values['content_display'] : false,
					),
					'more_link' => array(
						'type'			=> 'radio',
						'id'				=> 'more_link',
						'name'			=> __( 'Read More Link', 'ditty-news-ticker' ),
						'help'			=> __( 'Link the read more text to the post.', 'ditty-news-ticker' ),
						'options'		=> array(
							'post'		=> __( 'Yes', 'ditty-news-ticker' ),
							'false'		=> __( 'No', 'ditty-news-ticker' ),
						),
						'inline' 		=> true,
						'std'				=> isset( $values['more_link'] ) ? $values['more_link'] : false,
					),
					'excerpt_length' => array(
						'type'			=> 'number',
						'id'				=> 'excerpt_length',
						'name'			=> __( 'Excerpt Length', 'ditty-news-ticker' ),
						'help'			=> __( 'Set the length of the excerpt.', 'ditty-news-ticker' ),
						'std'				=> isset( $values['excerpt_length'] ) ? $values['excerpt_length'] : false,
					),
					'more' => array(
						'type'			=> 'text',
						'id'				=> 'more',
						'name'			=> __( 'Read More Text', 'ditty-news-ticker' ),
						'help'			=> __( 'Add read more text to the excerpt.', 'ditty-news-ticker' ),
						'std'				=> isset( $values['more'] ) ? $values['more'] : false,
					),
					'more_before' => array(
						'type'			=> 'text',
						'id'				=> 'more_before',
						'name'			=> __( 'Read More Before Text', 'ditty-news-ticker' ),
						'help'			=> __( 'Add text before the Read More text.', 'ditty-news-ticker' ),
						'std'				=> isset( $values['more_before'] ) ? $values['more_before'] : false,
					),
					'more_after' => array(
						'type'			=> 'text',
						'id'				=> 'more_after',
						'name'			=> __( 'Read More After Text', 'ditty-news-ticker' ),
						'help'			=> __( 'Add text after the Read More text.', 'ditty-news-ticker' ),
						'std'				=> isset( $values['more_after'] ) ? $values['more_after'] : false,
					),
				),
			),
			'linkSettings' => array(
				'type' 							=> 'group',
				'id'								=> 'linkSettings',
				'collapsible'				=> true,
				'default_state'			=> 'expanded',
				'multiple_fields'		=> true,
				'name' 	=> __( 'Link Settings', 'ditty-news-ticker' ),
				'help' 	=> __( 'Configure the link settings for the feed items.', 'ditty-news-ticker' ),
				'fields' => array(
					'link_target' => array(
						'type'			=> 'select',
						'id'				=> 'link_target',
						'name'			=> __( 'Link Target', 'ditty-news-ticker' ),
						'help'			=> __( 'Set a target for your links.', 'ditty-news-ticker' ),
						'options'		=> array(
							'_self'		=> '_self',
							'_blank'	=> '_blank'
						),
						'std'		=> isset( $values['link_target'] ) ? $values['link_target'] : false,
					),
					'link_nofollow' => array(
						'type'			=> 'checkbox',
						'id'				=> 'link_nofollow',
						'name'			=> __( 'Link No Follow', 'ditty-news-ticker' ),
						'label'			=> __( 'Add "nofollow" to link', 'ditty-news-ticker' ),
						'help'			=> __( 'Enabling this setting will add an attribute called \'nofollow\' to your links. This tells search engines to not follow this link.', 'ditty-news-ticker' ),
						'std'		=> isset( $values['link_nofollow'] ) ? $values['link_nofollow'] : false,
					),
				),
			),
		);
		return $fields;
	}
	
	/**
	 * Set the default field values
	 *
	 * @access  public
	 * @since   3.0.12
	 */
	public function default_settings() {		
		$defaults = array(
			'limit' 				=> 10,
			'content_display' 		=> 'full',
			'excerpt_length'			=> 200,
			'more'								=> __( 'Read More', 'ditty-news-ticker' ),
			'more_link'						=> 'post',
			'more_before'					=> '...&nbsp;',
			'more_after'					=> '',
			'link_target' 	=> '_self',
			'link_nofollow'	=> '',
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