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
	 * @since  3.0
	 * @return array
	 */
	public function prepare_items( $meta ) {
		$ditty_item	= $meta;
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
}