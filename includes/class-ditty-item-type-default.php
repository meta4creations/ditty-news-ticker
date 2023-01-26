<?php

/**
 * Ditty Default Type Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Default Type
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Item_Type_Default extends Ditty_Item_Type {
	
	/**
	 * Slug
	 *
	 * @since 3.0
	 */
	public $slug = 'default';

	/**
	 * Setup the fields
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function fields( $values = array() ) {	
		$fields = array(
			'content' => array(
				'type'	=> 'textarea',
				'id'		=> 'content',
				'name'	=> __( 'Content', 'ditty-news-ticker' ),
				'help'	=> __( 'Add the content of your item. HTML and inline styles are supported.', 'ditty-news-ticker' ),
				'std'		=> isset( $values['content'] ) ? $values['content'] : false,
			),
			'link_url' => array(
				'type'			=> 'text',
				'id'				=> 'link_url',
				'name'			=> __( 'Link', 'ditty-news-ticker' ),
				'help'			=> __( 'Add a custom link to your content. You can also add a link directly into your content.', 'ditty-news-ticker' ),
				'atts'			=> array(
					'type'	=> 'url',
				),
				'std'		=> isset( $values['link_url'] ) ? $values['link_url'] : false,
			),
			'link_title' => array(
				'type'			=> 'text',
				'id'				=> 'link_title',
				'name'			=> __( 'Title', 'ditty-news-ticker' ),
				'help'			=> __( 'Add a title to the custom lnk.', 'ditty-news-ticker' ),
				'std'			=> isset( $values['link_title'] ) ? $values['link_title'] : false,
			),
			'link_target' => array(
				'type'			=> 'select',
				'id'				=> 'link_target',
				'name'			=> __( 'Target', 'ditty-news-ticker' ),
				'help'			=> __( 'Set a target for your link.', 'ditty-news-ticker' ),
				'options'		=> array(
					'_self'		=> '_self',
					'_blank'	=> '_blank'
				),
				'std'		=> isset( $values['link_target'] ) ? $values['link_target'] : false,
			),
			'link_nofollow' => array(
				'type'			=> 'checkbox',
				'id'				=> 'link_nofollow',
				'name'			=> __( 'No Follow', 'ditty-news-ticker' ),
				'label'			=> __( 'Add "nofollow" to link', 'ditty-news-ticker' ),
				'help'			=> __( 'Enabling this setting will add an attribute called \'nofollow\' to your link. This tells search engines to not follow this link.', 'ditty-news-ticker' ),
				'std'		=> isset( $values['link_nofollow'] ) ? $values['link_nofollow'] : false,
			),
		);
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
	 * @since   3.0
	 */
	public function sanitize_settings( $values ) {
		$sanitized_fields = array(
			'content' 			=> isset( $values['content'] ) ? wp_kses_post( $values['content'] ) : false,
			'link_url' 			=> isset( $values['link_url'] ) ? esc_url_raw( $values['link_url'] ) : false,
			'link_title' 		=> isset( $values['link_title'] ) ? esc_attr( $values['link_title'] ) : false,
			'link_target' 	=> isset( $values['link_target'] ) ? esc_attr( $values['link_target'] ) : false,
			'link_nofollow'	=> isset( $values['link_nofollow'] ) ? esc_attr( $values['link_nofollow'] ) : false,
		);
		return $sanitized_fields;
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
}