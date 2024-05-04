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
	 * Get things started
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function __construct() {	
		parent::__construct();
		add_filter( 'ditty_layout_link_options', [$this, 'layout_link_options'], 10, 2 );
		add_filter( 'ditty_layout_tags', [$this, 'layout_tags'], 10, 2 );
	}
	
	/**
	 * Prepare items for Ditty use
	 *
	 * @access public
	 * @since  3.0
	 * @return array
	 */
	public function prepare_items( $meta ) {
		$item_value = ditty_to_array( $meta['item_value'] );
		$layout_value = ditty_to_array( $meta['layout_value'] );
		

		// Set the query args
		$query_args = array(
			'posts_per_page' 	=> $item_value['limit'],
      'post_type' => 'post',
      'post_status' => 'publish',
		);
		$ditty_posts_query = new WP_Query( $query_args );
			
		$prepared_meta = array();
		if ( $ditty_posts_query->have_posts() ) : while ( $ditty_posts_query->have_posts() ) : $ditty_posts_query->the_post();	
			global $post;
			$item_value 				= ditty_to_array( $meta['item_value'] );
			$item_value['item'] = $post;
			$item_value['item']->permalink = get_permalink( $post );
			$item_value['item']->author = [
				'avatar_url' => get_avatar_url( $post->post_author ),
				'name' => get_the_author_meta( 'display_name', $post->post_author ),
				'bio' => get_the_author_meta( 'description', $post->post_author ),
				'posts_url' => get_author_posts_url( $post->post_author ),
				'link_url' => get_the_author_meta( 'user_url', $post->post_author ),
			];
			$ditty_item 								= $meta;
			$ditty_item['item_uniq_id'] = $ditty_item['item_id'] . '_' . get_the_ID();
			$ditty_item['item_value'] 	= $item_value;

			// Find the variation & layout - for future use
			$ditty_item['layout_variation'] = isset( $layout_value['default'] ) ? 'default' : false;
			$ditty_item['layout'] = isset( $layout_value['default'] ) ? $layout_value['default'] : false;

      // Add the timestamp
      $ditty_item['timestamp'] = strtotime( $post->post_date_gmt );

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
	 * @since   3.1
	 */
	public function fields( $values = array() ) {					
		$fields = array(
			'limit' => array(
				'type'	=> 'number',
				'id'		=> 'limit',
				'name'	=> __( 'Limit', 'ditty-news-ticker' ),
				'help'	=> __( 'Set the number of Posts to display.', 'ditty-news-ticker' ),
			),
		);
		return apply_filters( 'ditty_item_type_fields', $fields, $this, $values );
	}
	
	/**
	 * Set the default field values
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function default_settings() {		
		$defaults = array(
			'limit' 				=> 10,
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
		$preview 	= sprintf( __( 'Displaying %d Posts', 'ditty-news-ticker' ), $args['limit'] );
		return $preview;
	}


	/**
	 * Return the layout tags
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function layout_link_options( $link_options, $item_type ) {
		if ( $item_type != $this->get_type() ) {
			return $link_options;
		}
		return [
			'true' => 'post',
			'author' => 'author',
			'author_link' => 'author_link',
			'none' => 'none',
		];
	}
	
	/**
	 * Return the layout tags
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function layout_tags( $tags, $item_type ) {
		if ( $item_type != $this->get_type() ) {
			return $tags;
		}
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
		$tags = array_intersect_key( $tags, array_flip( $allowed_tags ) );

		$tags['image']['atts']['size'] = 'large';
		$tags['image']['atts']['link_target']['std'] = '_self';
		return $tags;
	}

	/**
	 * Return the default layout
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function default_layout() {
		$default_layout = array(
			'html' => '{image link="post"}
{icon}
<div class="ditty-item-heading">
	{author_avatar width="50px" height="50px" fit="cover" link="author"}	
	<div class="ditty-item-heading__content">
		{author_name link="author"}
		{time link="post"}
	</div>
</div>
{title link="post"}
{content}',
			'css' => '.ditty-item__elements {
	position: relative;
	font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", sans-serif;
	font-size: 15px;
	line-height: 1.3125;
}
.ditty-item__elements a {
	text-decoration: none;
}
.ditty-item__image {
	overflow: hidden;
	margin-bottom: 15px;
}
.ditty-item__image img {
	display: block;
	width: 100%;
	height: auto;
	line-height: 0;
	transition: transform .75s ease; 
}
.ditty-item__image a:hover img {
	transform: scale(1.05);
}
.ditty-item__icon {
	display: none;
	position: absolute;
	top: 15px;
	left: 15px;
	font-size: 25px;
	line-height: 25px;
	color: #FFF;
	opacity: .8;
	text-shadow: 0 0 2px rgba( 0, 0, 0, .3 );
	pointer-events: none;
}
.ditty-item__icon a {
	color: #FFF;
}
.ditty-item__image + .ditty-item__icon {
	display: block;
}
.ditty-item-heading {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: flex-start;
	margin-bottom: 15px;
}
.ditty-item__author_avatar {
	flex: 0 0 auto;
	margin-right: 10px;
}
.ditty-item__author_avatar img {
	display: block;
	line-height: 0;
	border-radius: 50%;
}
.ditty-item__author_name {
	font-weight: 500;
}
.ditty-item__author_name a {
	color: #050505;
}
.ditty-item__time {
		font-size: 13px;
		font-weight: 300;
}
.ditty-item__time a {
		color: #6B6D71;
		text-decoration: none;
}
.ditty-item__time a:hover {
	text-decoration: underline;
}
.ditty-item__title {
	font-size: 18px;
	margin: 0;
}
.ditty-item__content,
.ditty-item__excerpt {
	font-size: 15px;
	line-height: 1.3125;
	margin: 5px 0 0 0;
}
.ditty-item__content p {
	font-size: 15px;
	line-height: 1.3125;
	margin-top: 0;
}
.ditty-item__content p:last-child {
	margin-bottom: 0;
}',
		);
		return $default_layout;
	}
}