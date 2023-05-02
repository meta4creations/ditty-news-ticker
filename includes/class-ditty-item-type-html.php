<?php

/**
 * Ditty Item Type HTML Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty HTML Type
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1
*/
class Ditty_Item_Type_Html extends Ditty_Item_Type_Default {
	
	/**
	 * Slug
	 *
	 * @since 3.1
	 */
	public $slug = 'html';
	
	/**
	 * Setup the type settings
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function fields( $values = array() ) {	
		$fields = array(
			'content' => array(
				'type'	=> 'custom_html',
				'id'		=> 'content',
				'name'	=> __( 'Content', 'ditty-news-ticker' ),
				'help'	=> __( 'Add the custom html for your item.', 'ditty-news-ticker' ),
				'raw'		=> true,
			),
		);
		return $fields;
	}

	/**
	 * Set the default field values
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function default_settings() {		
		$defaults = array(
			'content' => '<p>' . __( 'This is custom HTML. Please edit me!', 'ditty-news-ticker' ) . '</p>',
		);	
		return apply_filters( 'ditty_type_default_settings', $defaults, $this->slug );
	}

	/**
	 * Update values sent from the editor
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function sanitize_settings( $values ) {
		$sanitized_settings = [];
		$sanitized_settings['content'] = isset( $values ) ? wp_encode_emoji( wp_kses_post( stripslashes( $values['content'] ) ) ) : '';
		return $sanitized_settings;
	}

	/**
	 * Display the editor preview
	 *
	 * @since    3.1
	 * @access   public
	 * @var      string    $preview    The editor list display of a item
	*/
	public function editor_preview( $value ) {
		if ( ! isset( $value['content'] ) || '' == $value['content'] ) {
			return __( 'No content set...', 'ditty-news-ticker' );
		}
		
		$preview = stripslashes( wp_html_excerpt( $value['content'], 200, '...' ) );
		return $preview;	
	}
}