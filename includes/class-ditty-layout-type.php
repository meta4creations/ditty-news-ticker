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
	 * Return an array of default layouts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function variation_defaults() {	
		$layouts = ditty_layouts_with_type( $this->get_type() );
		$variations = array();
		if ( ! empty( $layouts ) ) {
			$variations['default'] = end( $layouts );
		} else {
			$layout_id = Ditty()->layouts->install_default( $this->get_type(), 'default');
			$variations['default'] = $layout_id;
		}
		return apply_filters( 'ditty_variation_defaults', $variations, $this );
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
	public function get_variation_values( $variation_value = false ) {
		$defaults = $this->variation_defaults();
		if ( ! $variation_value ) {
			return $defaults;
		}
		$values = shortcode_atts( $defaults, $variation_value );
		$confirmed_values = array();
		if ( is_array( $values ) && count( $values ) > 0 ) {
			foreach ( $values as $variation_id => $layout_id ) {
				if ( ! isset( $defaults[$variation_id] ) ) {
					continue;
				}
				if ( ! ditty_layout_exists( $layout_id, $this->get_type() ) ) {
					$layout_id = $defaults[$variation_id];
				}
				if ( is_numeric( $layout_id ) ) {
					if ( 'publish' != get_post_status( $layout_id ) ) {
						$template = $defaults[$variation_id];
					}
				}
				$confirmed_values[$variation_id] = $layout_id;
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