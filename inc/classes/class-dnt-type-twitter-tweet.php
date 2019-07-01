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
class DNT_Type_Twitter_Tweet extends DNT_Type {
	
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
		$this->label = __( 'Single Tweet', 'ditty-news-ticker' );
		$this->icon = 'fab fa-twitter';
	}
	
	/**
	 * Get things started
	 * @access  public
	 * @since   1.0
	 */
	public function fields() {
		
		$fields = array(
			'text' => array(
				'name'		=> __('Ticker Text', 'ditty-news-ticker'),
				'desc'		=> __('Add the content of your tick. HTML and inline styles are supported.', 'ditty-news-ticker'),
				'id'			=> 'text',
				'type'		=> 'textarea',
				'columns'	=> 12
			),
		);
		
		$this->fields = apply_filters( 'dnt_type_fields', $fields, 'default' );
		
		return $this->fields;	
	}
	
	
	
	
}