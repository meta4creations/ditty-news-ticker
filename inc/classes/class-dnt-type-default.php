<?php

/**
 * Ditty News Ticker Default Type Class
 *
 * @package     Ditty News Ticker
 * @subpackage  Classes/Ditty News Ticker Default Type
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class DNT_Type_Default extends DNT_Type {
	
	/**
	 * Label
	 *
	 * @since 3.0
	 */
	private $label;
	
	/**
	 * Fields
	 *
	 * @since 3.0
	 */
	private $icon;
	
	/**
	 * Fields
	 *
	 * @since 3.0
	 */
	private $fields;

	/**
	 * Get things started
	 * @access  public
	 * @since   1.0
	 */
	public function __construct() {	
		$this->label = __( 'Default', 'ditty-news-ticker' );
		$this->icon = 'fas fa-pencil-alt';
	}
	
	/**
	 * Get things started
	 * @access  public
	 * @since   1.0
	 */
	public function fields() {
		
		$fields = array(
			'text' => array(
				'name'		=> __( 'Ticker Text', 'ditty-news-ticker' ),
				'desc'		=> __( 'Add the content of your tick. HTML and inline styles are supported.', 'ditty-news-ticker' ),
				'id'			=> 'text',
				'type'		=> 'textarea',
				'columns'	=> 12
			),
			'link' => array(
				'name'		=> __( 'Link', 'ditty-news-ticker' ),
				'desc'		=> __( 'Wrap a link around your tick content. You can also add a link directly into your content.', 'ditty-news-ticker' ),
				'id'			=> 'link',
				'type'		=> 'text',	
				'columns'	=> 12
			),
			'target' => array(
				'name'		=> __( 'Target', 'ditty-news-ticker' ),
				'desc'		=> __( 'Set a target for your link.', 'ditty-news-ticker' ),
				'id'			=> 'target',
				'type'		=> 'select',
				'options'	=> array(
					'_self'		=> '_self',
					'_blank'	=> '_blank'
				),
				'columns'	=> 12
			),
			'nofollow' => array(
				'name'		=> __( 'No Follow', 'ditty-news-ticker' ),
				'label'		=> __( 'Add "nofollow" to link', 'ditty-news-ticker' ),
				'desc'		=> __( 'Enabling this setting will add an attribute called \'nofollow\' to your link. This tells search engines to not follow this link.', 'ditty-news-ticker' ),
				'id'			=> 'nofollow',
				'type'		=> 'checkbox',	
				'columns'	=> 12
			),
		);
		
		$this->fields = apply_filters( 'dnt_type_fields', $fields, 'default' );
		
		return $this->fields;	
	}
	
	
	
	
}