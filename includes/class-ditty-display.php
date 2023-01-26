<?php

/**
 * Ditty Display Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Display {
	
	private $display_id;
	private $display_object;
	private $display_type;
	private $icon;
	private $label;
	private $description;
	private $settings;
	private $version;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $display_id ) {
		
		// If this is a custom display
		if ( is_array( $display_id ) ) {
			$this->label 				= '';
			$this->description 	= '';
			$this->display_type = $display_id['type'];
			$this->settings 		= $display_id['settings'];
			$this->version			= '';
		
		// If this is a new display
		} elseif ( false !== strpos( $display_id, 'new-' ) ) {
			$this->parse_draft_data( $display_id );
		
		// Else, this is an existing display
		} elseif ( get_post( $display_id ) ) { 
			$this->construct_from_id( $display_id );
			$this->parse_draft_data( $display_id );
		}
		$this->construct_display_object_data();
		return $this;
	}
	
	/**
	 * Parse the draft data
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function parse_draft_data( $display_id ) {
		$this->display_id = $display_id;
		$draft_values = ditty_draft_display_get( $display_id );
		if ( ! $draft_values ) {
			return false;
		}
		$this->label 				= isset( $draft_values['label'] ) 				? $draft_values['label'] 				: $this->label;
		$this->description 	= isset( $draft_values['description'] ) 	? $draft_values['description'] 	: $this->description;
		$this->display_type = isset( $draft_values['display_type'] )	? $draft_values['display_type']	: $this->display_type;
		$this->settings 		= isset( $draft_values['settings'] ) 			? $draft_values['settings'] 		: $this->settings;
		$this->version			= isset( $draft_values['version'] )				? $draft_values['version'] 			: $this->version;
	}
	
	/**
	 * Construct class from ID
	 * @access private
	 * @since  3.0
	 */
	private function construct_from_id( $display_id ) {
		$this->display_id		= $display_id;
		$this->display_type = get_post_meta( $display_id, '_ditty_display_type', true );
		$this->label 				= get_the_title( $display_id );
		$this->description	= get_post_meta( $display_id, '_ditty_display_description', true );
		$this->settings 		= get_post_meta( $display_id, '_ditty_display_settings', true );
		$this->version 			= get_post_meta( $display_id, '_ditty_display_version', true );
	}
	
	/**
	 * Construct the type object data
	 * @access public
	 * @since  3.0
	 */
	public function construct_display_object_data() {
		if ( ! $display_object = $this->get_display_object() ) {
			return false;
		}
		$this->icon = $display_object->get_icon();	
	}
	
	/**
	 * Return the display object
	 * @access public
	 * @since  3.0
	 * @return int $display_object
	 */
	public function get_display_object() {
		if ( empty( $this->display_object ) ) {
			$this->display_object = ditty_display_type_object( $this->display_type );
		}
		return $this->display_object;
	}
	
	/**
	 * Return the base id
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_display_id() {
		return $this->display_id;
	}
	
	/**
	 * Set the base id
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function set_display_id( $display_id ) {
		$this->display_id = $display_id;
		return $this->display_id;
	}
	
	/**
	 * Return the display type
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_display_type() {
		return $this->display_type;
	}
	
	/**
	 * Return the display icon
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_icon() {
		return $this->icon;
	}
	
	/**
	 * Return the display label
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Set the display label
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function set_label( $label ) {
		if ( $label != $this->label ) {
			$sanitized_label = sanitize_text_field( $label );
			$this->label = $sanitized_label;
			return $this->label;
		}
	}
	
	/**
	 * Return the display description
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_description() {
		return $this->description;
	}
	
	/**
	 * Return the display settings
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_settings() {
		return $this->settings;
	}
	
	/**
	 * Return the version
	 * @access public
	 * @since  3.0
	 * @return string $version
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Set the version
	 * @access public
	 * @since  3.0
	 * @return string $version
	 */
	public function set_version( $version ) {
		$this->version = $version;
		return $this->version;
	}

	/**
	 * Return the display values
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_values() {
		$values = $this->get_settings();
		if ( ! is_array( $values ) ) {
			return array();
		}
		return $values;
	}
	
	/**
	 * Update the display settings
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function update_settings( $settings = array() ) {
		if ( ! is_array( $settings ) ) {
			return false;
		}
		$display_object = $this->get_display_object();
		$sanitized_settings = $display_object->sanitize_settings( $settings );
		$this->settings = $sanitized_settings;
		return $this->settings;
	}

	/**
	 * Return the display metabox
	 * @access public
	 * @since  3.0.14
	 * @return int $id
	 */
	public function object_settings( $action = 'render' ) {
		$display_object = $this->get_display_object();
		return $display_object->settings( $this->get_values(), $action );
	}

	/**
	 * Setup the display classes
	 * @access public
	 * @since  3.0
	 * @return string $classes
	 */
	public function get_editor_classes() {	
		$classes = array();
		$classes[] = 'ditty-data-list__item';
		$classes[] = 'ditty-editor-display';
		$classes[] = 'ditty-editor-display--' . esc_attr( $this->display_type );	
		$classes = apply_filters( 'ditty_display_classes', $classes );	
		return implode( ' ', $classes );
	}

	/**
	 * Render the admin edit row
	 * @access public
	 * @since  3.0
	 * @return html
	 */
	public function render_editor_list_item( $render='echo' ) {
		if ( 'return' == $render ) {
			ob_start();
		}
		$atts = array(
			'id'								=> 'ditty-editor-display--' . $this->get_display_id(),
			'class' 						=> $this->get_editor_classes(),
			'data-display_id' 	=> $this->get_display_id(),
			'data-display_type' => $this->get_display_type(),
		);
		?>
		<div <?php echo ditty_attr_to_html( $atts ); ?>>
			<?php do_action( 'ditty_editor_display_elements', $this ); ?>
		</div>
		<?php
		if ( 'return' == $render ) {
			return trim( ob_get_clean() );
		}
	}

}