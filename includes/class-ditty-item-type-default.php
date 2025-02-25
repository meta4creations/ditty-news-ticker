<?php

/**
 * Ditty Default Type Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Default Type
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1
*/
class Ditty_Item_Type_Default extends Ditty_Item_Type {
	
	/**
	 * Slug
	 *
	 * @since 3.0
	 */
	public $slug = 'default';

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function __construct() {	
		parent::__construct();
		add_filter( 'ditty_layout_tags', [$this, 'layout_tags'], 10, 2 );
	}

  /**
	 * Prepare items for Ditty use
	 *
	 * @access public
	 * @since  3.1.54
	 * @return array
	 */
	public function prepare_items( $meta ) {
    $value = isset( $meta['item_value'] ) ? $meta['item_value'] : [];
    $content = isset( $value['content'] ) ? $value['content'] : false;
    if ( ! $content ) {
      return false;
    }

		$layout_value = ditty_to_array( $meta['layout_value'] );
		
		$ditty_item	= $meta;
		$ditty_item['layout_variation'] = isset( $layout_value['default'] ) ? 'default' : false;
    $ditty_item['timestamp'] = isset( $meta['date_created'] ) ? strtotime( $meta['date_created'] ) : false;

    // Translate items
    $ditty_item = Ditty()->translations->translate_item( $ditty_item );

		return array( $ditty_item );
	}

  /**
	 * Set the translatable fields
	 *
	 * @access  public
	 * @since   3.1.25
	 */
  public function is_translatable() {
    return [
      'content' => [
        'label' => __( 'Content', 'ditty-news-ticker' ),
        'type' => 'AREA',
      ],
      'link_title' => [
        'label' => __( 'Title', 'ditty-news-ticker' ),
        'type' => 'LINE',
      ]
    ];
  }

	/**
	 * Setup the fields
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function fields( $values = array() ) {	
		$fields = [
			[
				'id' => 'settings',
				'label' => __("Settings", "ditty-news-ticker"),
				'name' => __("Settings", "ditty-news-ticker"),
				'description' => __( 'Configure the settings of the default item.', "ditty-news-ticker" ),
				'icon' => 'fa-sliders',
				'fields' => [
					'content' => array(
						'type'	=> 'textarea',
						'id'		=> 'content',
						'name'	=> __( 'Content', 'ditty-news-ticker' ),
						'help'	=> __( 'Add the content of your item. HTML and inline styles are supported.', 'ditty-news-ticker' ),
					),
					'link_url' => array(
						'type'			=> 'text',
						'id'				=> 'link_url',
						'name'			=> __( 'Link', 'ditty-news-ticker' ),
						'help'			=> __( 'Add a custom link to your content. You can also add a link directly into your content.', 'ditty-news-ticker' ),
						'atts'			=> array(
							'type'	=> 'url',
						),
					),
					'link_title' => array(
						'type'			=> 'text',
						'id'				=> 'link_title',
						'name'			=> __( 'Link Title', 'ditty-news-ticker' ),
						'help'			=> __( 'Add a title to the custom lnk.', 'ditty-news-ticker' ),
					),
					'link_target' => array(
						'type'			=> 'select',
						'id'				=> 'link_target',
						'name'			=> __( 'Link Target', 'ditty-news-ticker' ),
						'help'			=> __( 'Set a target for your link.', 'ditty-news-ticker' ),
						'options'		=> array(
							'_self'		=> '_self',
							'_blank'	=> '_blank'
						),
					),
					'link_nofollow' => array(
						'type'			=> 'checkbox',
						'id'				=> 'link_nofollow',
						'name'			=> __( 'Link No Follow', 'ditty-news-ticker' ),
						'label'			=> __( 'Add "nofollow" to link', 'ditty-news-ticker' ),
						'help'			=> __( 'Enabling this setting will add an attribute called \'nofollow\' to your link. This tells search engines to not follow this link.', 'ditty-news-ticker' ),
					),
          'editor_label' => array(
            'type'	=> 'text',
            'id'		=> 'editor_label',
            'name'	=> __( 'Label', 'ditty-news-ticker' ),
            'help'	=> __( 'Add a custom label to display in the item list.', 'ditty-news-ticker' ),
          ),
				]
			]
		];
		return $fields;
	}
	
	/**
	 * Set the default field values
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function default_settings() {		
		$defaults = array(
			'content' 			=> __( 'This is a sample item. Please edit me!', 'ditty-news-ticker' ),
			'link_url' 			=> '',
			'link_title' 		=> '',
			'link_target' 	=> '_self',
			'link_nofollow'	=> '',
		);
		return apply_filters( 'ditty_type_default_settings', $defaults, $this->slug );
	}

	/**
	 * Sanitize the settings
	 *
	 * @access  public
	 * @since   3.1.5
	 */
	public function sanitize_settings( $values ) {
		$sanitized_fields = array(
			'content' 			=> isset( $values['content'] ) ? wp_encode_emoji( ditty_sanitize_setting( stripslashes( $values['content'] ) ) ) : false,
			'link_url' 			=> isset( $values['link_url'] ) ? esc_url_raw( $values['link_url'] ) : false,
			'link_title' 		=> isset( $values['link_title'] ) ? esc_attr( $values['link_title'] ) : false,
			'link_target' 	=> isset( $values['link_target'] ) ? esc_attr( $values['link_target'] ) : false,
			'link_nofollow'	=> isset( $values['link_nofollow'] ) ? esc_attr( $values['link_nofollow'] ) : false,
      'editor_label'  => isset( $values['editor_label'] ) ? sanitize_text_field( $values['editor_label'] ) : false,
		);
		return $sanitized_fields;
	}
	
	/**
	 * Modify the item before sending to the editor
	 *
	 * @since    3.1.13
	 * @access   public
	 * @var      string    $item
	*/
	public function editor_meta( $item ) {
		if ( is_array( $item ) ) {
			if ( isset( $item['item_value'] ) && isset( $item['item_value']['content'] ) ) {
				$item['item_value']['content'] = ditty_sanitize_setting( html_entity_decode( $item['item_value']['content'] ) );
			}
		} else {
			if ( isset( $item->item_value ) && isset( $item->item_value['content'] ) ) {
				$item->item_value['content'] = ditty_sanitize_setting( html_entity_decode( $item->item_value['content'] ) );
			}
		}
		return $item;
	}
	
	/**
	 * Display the editor preview
	 *
	 * @since    3.0
	 * @access   public
	 * @var      string    $preview    The editor list display of a item
	*/
	public function editor_preview( $value ) {
		if ( ! isset( $value['content'] ) || '' == $value['content'] ) {
			return __( 'No text set...', 'ditty-news-ticker' );
		}	
		$preview = stripslashes( wp_html_excerpt( $value['content'], 200, '...' ) );	
		return $preview;
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
			'content',
			'time',
			'author_avatar',
			'author_bio',
			'author_name',
		);
		$tags = array_intersect_key( $tags, array_flip( $allowed_tags ) );
		
		$tags['time']['atts'] = array_intersect_key( $tags['time']['atts'], array_flip( array(
			'wrapper',
			'ago',
			'format',
			'ago_string',
			'before',
			'after',
			'class',
		) ) );

		$tags['content']['atts'] = array_intersect_key( $tags['content']['atts'], array_flip( array(
			'wrapper',
			'before',
			'after',
			'class',
		) ) );

		return $tags;
	}
}