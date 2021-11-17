<?php

/**
 * Ditty Layout Type Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Layout Type
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Layout_Type {

	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'none';
	
	/**
	 * Default variation
	 *
	 * @since 3.0
	 */
	public $default_variation = 'default';
	
	/**
	 * Default variation
	 *
	 * @since 3.0
	 */
	public $default_template = 'default';
	
	/**
	 * Label
	 *
	 * @since 3.0
	 */
	public $label;
	
	/**
	 * Fields
	 *
	 * @since 3.0
	 */
	public $icon;
	
	/**
	 * Description
	 *
	 * @since 3.0
	 */
	public $description;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {		
		$layout_types = ditty_layout_types();
		if ( isset( $layout_types[$this->type] ) ) {
			$this->label = $layout_types[$this->type]['label'];
			$this->icon = $layout_types[$this->type]['icon'];
			$this->description = $layout_types[$this->type]['description'];
		}
	}
	
	/**
	 * Return an array of variation types
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function variation_types() {	
		$types = array(
			'default' => 'default',
		);
		return apply_filters( 'ditty_variation_types', $types, $this );
	}
	
	/**
	 * Return an array of default layouts
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function variation_defaults() {
		$all_variation_defaults = ditty_settings( 'variation_defaults' );
		$variation_defaults = isset( $all_variation_defaults[$this->get_type()] ) ? $all_variation_defaults[$this->get_type()] : array();
		$variation_types = $this->variation_types();
		
		$confirmed_defaults = array();
		$defaults_updated = false;
		if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
			foreach ( $variation_types as $variation_type => $template ) {
				$default = isset( $variation_defaults[$variation_type] ) ? $variation_defaults[$variation_type] : false;
				if ( $default && 'publish' == get_post_status( $default ) ) {
					$confirmed_defaults[$variation_type] = $default;
				} else {
					$layout_id = Ditty()->layouts->install_default( $this->get_type(), $template );
					$confirmed_defaults[$variation_type] = $layout_id;
					$defaults_updated = true;
				}
			}
		}	
		if ( $defaults_updated ) {	
			$all_variation_defaults[$this->get_type()] = $confirmed_defaults;
			ditty_settings( 'variation_defaults', $all_variation_defaults );
		}	
		return apply_filters( 'ditty_variation_defaults', $confirmed_defaults, $this );
	}
	
	/**
	 * Return an array of variations
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function variations( $variation_value = false ) {
		$values = $this->get_variation_values( $variation_value );
		$variations = array(
			'default' => array(
				'label'				=> __( 'Default', 'ditty-facebook' ),
				'description' => __( 'Default variation.', 'ditty-facebook' ),
				'template' 		=> $values['default'],
			),
		);
		return apply_filters( 'ditty_layout_type_variations', $variations, $this->type );
	}
	
	/**
	 * Get values to populate the metabox
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function get_variation_values( $variation_value = array() ) {
		$defaults = $this->variation_defaults();
		$varation_types = $this->variation_types();
		$confirmed_values = array();
		if ( is_array( $varation_types ) && count( $varation_types ) > 0 ) {
			foreach ( $varation_types as $varation_type => $template ) {
				if ( isset( $variation_value[$varation_type] ) ) {
					if ( 'publish' == get_post_status( $variation_value[$varation_type] ) ) {
						$confirmed_values[$varation_type] = $variation_value[$varation_type];
					} else {
						$confirmed_values[$varation_type] = $defaults[$varation_type];
					}
				} else {
					$confirmed_values[$varation_type] = $defaults[$varation_type];
				}
			}
		}
		return $confirmed_values;
	}
	
	/**
	 * Find the layout id based on layout and item values
	 *
	 * @access  public
	 * @since   3.0
	 * @return string $id
	 */
	public function parse_layout_id( $layout_value = array(), $item_value = array() ) {
		if ( isset( $layout_value[$this->default_variation] ) ) {
			return $layout_value[$this->default_variation];
		} else {
			return $this->default_template;
		}
	}
	
	/**
	 * Return the type
	 * @access  public
	 * @since   3.0
	 * @return string $type
	 */
	public function get_type() {
		return $this->type;
	}
	
	/**
	 * Return the label
	 * @access  public
	 * @since   3.0
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Return the icon
	 * @access  public
	 * @since   3.0
	 * @return string $icon
	 */
	public function get_icon() {
		return $this->icon;
	}
	
	/**
	 * Return the description
	 * @access  public
	 * @since   3.0
	 * @return string $description
	 * 
	 */
	public function get_description() {
		return $this->description;
	}
	
	/**
	 * Return the default template
	 * @since    3.0
	 * @access   public
	 * @var      string    $template
	*/
	public function get_default_template() {
		return $this->default_template;
	}

	/**
	 * Return a layout template
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $template
	*/
	public function get_template( $id ) {
		$templates = $this->templates();
		if ( isset( $templates[$id] ) ) {
			return $templates[$id];
		} else {
			return $templates[$this->get_default_template()];
		}
	}
	
	/**
	 * The defined tags for this type
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $tags
	*/
	public function html_tags() {
	}
	
	/**
	 * The defined css selectors for this type
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $tags
	*/
	public function css_selectors() {
	}

	/**
	 * Return an array of templates
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function templates() {	
	}

}