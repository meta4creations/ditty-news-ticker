<?php

/**
 * Ditty Item Type Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Item Type
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Item_Type {

	public $slug = 'none';
	public $type;
	public $label;
	public $icon;
	public $description;
	public $script_id;

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {	
		$item_types = ditty_item_types();
		if ( isset( $item_types[$this->slug] ) ) {
			$this->type = $item_types[$this->slug]['type'];
			$this->label = $item_types[$this->slug]['label'];
			$this->icon = $item_types[$this->slug]['icon'];
			$this->description = $item_types[$this->slug]['description'];
		}
	}
	
	/**
	 * Prepare items for Ditty use
	 *
	 * @access public
	 * @since  3.1
	 * @return array
	 */
	public function prepare_items( $meta ) {
		$layout_value = maybe_unserialize( $meta['layout_value'] );
		
		$ditty_item	= $meta;
		$ditty_item['layout_variation'] = isset( $layout_value['default'] ) ? 'default' : false;
		$ditty_item['layout'] = isset( $layout_value['default'] ) ? $layout_value['default'] : false;

		return array( $ditty_item );
	}
	
	/**
	 * Return the type
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $type
	 */
	public function get_type() {
		return $this->type;
	}
	
	/**
	 * Return the label
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Return the icon
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $icon
	 */
	public function get_icon() {
		return $this->icon;
	}
	
	/**
	 * Return the description
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $description
	 * 
	 */
	public function get_description() {
		return $this->description;
	}
	
	/**
	 * Return the script id
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $script_id
	 * 
	 */
	public function get_script_id() {
		if ( ! empty( $this->script_id ) ) {
			return $this->script_id;
		}
	}
	
	/**
	 * Setup the type settings
	 *
	 * @access  public
	 * @since   3.0.22
	 */
	public function settings( $item_values = false, $action = 'render' ) {	
		$values = $this->get_values( $item_values );
		$fields = $this->fields( $values );
		if ( 'return' == $action ) {
			return ditty_fields( $fields, $values, $action );
		} else {
			ditty_fields( $fields, $values, $action );
		}
	}
	
	/**
	 * Set the allowed layout tags
	 *
	 * @access  public
	 * @since   3.0.21
	 */
	public function layout_tags() {					
		return array();
	}
	
	/**
	 * Get values to populate the metabox
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function get_values( $item_values=false ) {
		$defaults = $this->default_settings();
		if ( ! $item_values ) {
			return $defaults;
		}
		$values = wp_parse_args( $item_values, $defaults );
		return $values;
	}
	
	/**
	 * Get values to populate the metabox
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function get_layout_variation_types() {
		$layout_variations = array(
			'default' => array(
				'template'		=> 'default',
				'label'				=> __( 'Default', 'ditty-news-ticker' ),
				'description' => __( 'Default variation.', 'ditty-news-ticker' ),
			),
		);
		return apply_filters( 'ditty_item_type_variation_types', $layout_variations, $this );
	}
	
	/**
	 * Get values to populate the metabox
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function get_layout_variation_defaults( $type = false ) {
		global $ditty_layout_confirmed_defaults;
		if ( ! empty( $ditty_layout_confirmed_defaults ) ) {
			$ditty_layout_confirmed_defaults = array();
		}
		if ( ! isset( $ditty_layout_confirmed_defaults[$this->get_type()] ) ) {
			$ditty_layout_confirmed_defaults[$this->get_type()] = array();
			
			$all_variation_defaults = ditty_settings( 'variation_defaults' );
			$variation_defaults = isset( $all_variation_defaults[$this->get_type()] ) ? $all_variation_defaults[$this->get_type()] : array();
			$variation_types = $this->get_layout_variation_types();

			if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
				foreach ( $variation_types as $slug => $data ) {
					$ditty_layout_confirmed_defaults[$this->get_type()][$slug] = isset( $variation_defaults[$slug] ) ? $variation_defaults[$slug] : 0;
				}
			}		
		}
		
		if ( $type ) {
			if ( isset( $ditty_layout_confirmed_defaults[$this->get_type()][$type] ) ) {
				return $ditty_layout_confirmed_defaults[$this->get_type()][$type];
			}
		} else {
			return $ditty_layout_confirmed_defaults[$this->get_type()];
		}
	}
	
	/**
	 * Confirm the layout variations
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function confirm_layout_variations( $layout_value = array() ) {
		$defaults = $this->get_layout_variation_defaults();
		$args = shortcode_atts( $defaults, $layout_value );
		return $args;
	}
	
	/**
	 * Get layout variation data
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function get_layout_variation_data( $layout_value = array() ) {
		$variation_types = $this->get_layout_variation_types();
		$confirmed = $this->confirm_layout_variations( $layout_value );
		$variation_data = array();
		if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
			foreach ( $variation_types as $slug => $data ) {
				$variation_data[$slug] = $data;
				$variation_data[$slug]['template'] = $confirmed[$slug];
			}
		}
		return $variation_data;
	}

	/**
	 * Update values sent from the editor
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function sanitize_settings( $values ) {
		$fields = $this->fields();
		return ditty_sanitize_fields( $fields, $values, "ditty_item_type_{$this->get_type()}" );
	}
	
	/**
	 * Display the editor preview
	 *
	 * @since    3.0
	 * @access   public
	 * @var      string    $preview    The editor list display of a item
	*/
	public function editor_preview( $value ) {
		return '';
	}
	
	/**
	 * Return the item title settings
	 *
	 * @since    3.0.18
	 * @var      multiple    $updated_value
	*/
	public function title_settings( $values ) {
		$title_settings = array(
			'type' 							=> 'group',
			'id'								=> 'titleSettings',
			'collapsible'				=> true,
			'default_state'			=> 'expanded',
			'multiple_fields'		=> true,
			'name' 	=> __( 'Title Settings', 'ditty-news-ticker' ),
			'help' 	=> __( 'Configure settings for the title tag.', 'ditty-news-ticker' ),
			'fields' => array(
				'title_element' => array(
					'type'			=> 'select',
					'id'				=> 'title_element',
					'name'			=> esc_html__( 'Title Element', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Choose the html wrapper element for titles.', 'ditty-news-ticker' ),
					'std'				=> isset( $values['title_element'] ) ? $values['title_element'] : false,
					'options'		=> array(
						'default'		=> esc_html__( 'Use Default', 'ditty-news-ticker' ),
						'none'			=> esc_html__( 'None', 'ditty-news-ticker' ),
						'h1'				=> 'h1',
						'h2'				=> 'h2',
						'h3'				=> 'h3',
						'h4'				=> 'h4',
						'h5'				=> 'h5',
						'h6'				=> 'h6',
						'div'				=> 'div',
						'p'					=> 'p',
						'span'			=> 'span',
					),
				),
				'title_link' => array(
					'type'			=> 'radio',
					'id'				=> 'title_link',
					'name'			=> esc_html__( 'Title Link', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Add a link to the post.', 'ditty-news-ticker' ),
					'inline'		=> true,
					'std'				=> isset( $values['title_link'] ) ? $values['title_link'] : false,
					'options'		=> array(
						'default'	=> esc_html__( 'Default', 'ditty-news-ticker' ),
						'on'			=> esc_html__( 'On', 'ditty-news-ticker' ),
						'off'			=> esc_html__( 'Off', 'ditty-news-ticker' ),
					),
				),
			),
		);
		return $title_settings;
	}
	
	/**
	 * Return the item content settings
	 *
	 * @since    3.0.18
	 * @var      multiple    $updated_value
	*/
	public function content_settings( $values ) {
		$content_settings = array(
			'type' 							=> 'group',
			'id'								=> 'contentSettings',
			'collapsible'				=> true,
			'default_state'			=> 'expanded',
			'multiple_fields'		=> true,
			'name' 	=> esc_html__( 'Content Settings', 'ditty-news-ticker' ),
			'help' 	=> esc_html__( 'Configure the content settings for the feed items.', 'ditty-news-ticker' ),
			'fields' => array(
				'content_display' => array(
					'type'			=> 'radio',
					'id'				=> 'content_display',
					'name'			=> esc_html__( 'Content Display', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Configure settings for the post content.', 'ditty-news-ticker' ),
					'options'		=> array(
						'full'		=> esc_html__( 'Full Content', 'ditty-news-ticker' ),
						'excerpt'	=> esc_html__( 'Excerpt', 'ditty-news-ticker' ),
					),
					'inline' 		=> true,
					'std'		=> isset( $values['content_display'] ) ? $values['content_display'] : false,
				),
				'excerpt_element' => array(
					'type'			=> 'select',
					'id'				=> 'excerpt_element',
					'name'			=> esc_html__( 'Excerpt Element', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Choose the html wrapper element for excerpts.', 'ditty-news-ticker' ),
					'std'				=> isset( $values['excerpt_element'] ) ? $values['excerpt_element'] : false,
					'options'		=> array(
						'default'		=> esc_html__( 'Use Default', 'ditty-news-ticker' ),
						'none'			=> esc_html__( 'None', 'ditty-news-ticker' ),
						'h1'				=> 'h1',
						'h2'				=> 'h2',
						'h3'				=> 'h3',
						'h4'				=> 'h4',
						'h5'				=> 'h5',
						'h6'				=> 'h6',
						'div'				=> 'div',
						'p'					=> 'p',
						'span'			=> 'span',
					),
				),
				'excerpt_length' => array(
					'type'			=> 'number',
					'id'				=> 'excerpt_length',
					'name'			=> esc_html__( 'Excerpt Length', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Set the length of the excerpt.', 'ditty-news-ticker' ),
					'std'				=> isset( $values['excerpt_length'] ) ? $values['excerpt_length'] : false,
				),
				'more' => array(
					'type'			=> 'text',
					'id'				=> 'more',
					'name'			=> esc_html__( 'Read More Text', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Add read more text to the excerpt.', 'ditty-news-ticker' ),
					'std'				=> isset( $values['more'] ) ? $values['more'] : false,
				),
				'more_before' => array(
					'type'			=> 'text',
					'id'				=> 'more_before',
					'name'			=> esc_html__( 'Read More Before Text', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Add text before the Read More text.', 'ditty-news-ticker' ),
					'std'				=> isset( $values['more_before'] ) ? $values['more_before'] : false,
				),
				'more_after' => array(
					'type'			=> 'text',
					'id'				=> 'more_after',
					'name'			=> esc_html__( 'Read More After Text', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Add text after the Read More text.', 'ditty-news-ticker' ),
					'std'				=> isset( $values['more_after'] ) ? $values['more_after'] : false,
				),
				'more_link' => array(
					'type'			=> 'radio',
					'id'				=> 'more_link',
					'name'			=> esc_html__( 'Read More Link', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Link the read more text to the post.', 'ditty-news-ticker' ),
					'options'		=> array(
						'post'		=> esc_html__( 'Yes', 'ditty-news-ticker' ),
						'false'		=> esc_html__( 'No', 'ditty-news-ticker' ),
					),
					'inline' 		=> true,
					'std'				=> isset( $values['more_link'] ) ? $values['more_link'] : false,
				),
			),
		);
		return $content_settings;
	}
	
	/**
	 * Return the item link settings
	 *
	 * @since    3.0.18
	 * @var      multiple    $updated_value
	*/
	public function link_settings( $values ) {
		$link_settings = array(
			'type' 							=> 'group',
			'id'								=> 'linkSettings',
			'collapsible'				=> true,
			'default_state'			=> 'expanded',
			'multiple_fields'		=> true,
			'name' 	=> esc_html__( 'Link Settings', 'ditty-news-ticker' ),
			'help' 	=> esc_html__( 'Configure the link settings for the item elements.', 'ditty-news-ticker' ),
			'fields' => array(
				'link_target' => array(
					'type'			=> 'select',
					'id'				=> 'link_target',
					'name'			=> esc_html__( 'Link Target', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Set a target for your links.', 'ditty-news-ticker' ),
					'options'		=> array(
						'_self'		=> '_self',
						'_blank'	=> '_blank'
					),
					'std'		=> isset( $values['link_target'] ) ? $values['link_target'] : false,
				),
				'link_nofollow' => array(
					'type'			=> 'checkbox',
					'id'				=> 'link_nofollow',
					'name'			=> esc_html__( 'Link No Follow', 'ditty-news-ticker' ),
					'label'			=> esc_html__( 'Add "nofollow" to link', 'ditty-news-ticker' ),
					'help'			=> esc_html__( 'Enabling this setting will add an attribute called \'nofollow\' to your links. This tells search engines to not follow this link.', 'ditty-news-ticker' ),
					'std'		=> isset( $values['link_nofollow'] ) ? $values['link_nofollow'] : false,
				),
			),
		);
		return $link_settings;
	}
}