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
	public $layout_type;
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
	 * Return the layout type
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $layout_type
	 * 
	 */
	public function get_layout_type() {
		return $this->layout_type;
	}
	
	/**
	 * Return the script slug
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $layout_type
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
	 * @since   3.0
	 */
	public function settings( $item_values = false, $action = 'render' ) {	
		$values = $this->get_values( $item_values );
		$fields = $this->fields( $values );
		ditty_fields( $fields, $values, $action );
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
	 * Update values sent from the editor
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function sanitize_settings( $values ) {
		$fields = $this->fields();
		return ditty_sanitize_fields( $fields, $values );
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
		return array( $meta );
	}

}