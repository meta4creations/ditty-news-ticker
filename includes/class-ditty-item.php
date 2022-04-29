<?php
/**
 * Ditty Item Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Item
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Item {

	private $item_id;
	private $item_uniq_id;
	private $ditty_id;
	private $item_type;
	private $layout_value;
	private $item_value;
	private $item_index;
	private $icon;
	private $label;
	private $item_type_object;
	private $item_author;
	private $date_created;
	private $date_modified;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $id = false ) {
		$this->item_id 			= -1;
		$this->item_uniq_id = -1;
		$this->item_type 		= 'default';
		$this->layout_value = false;
		$this->icon 				= 'fas fa-exclamation-circle';
		$this->label 				= __( 'No text set...', 'ditty-news-ticker' );
		$this->item_value 	= '';
		$this->index 				= 0;

		if ( is_ditty_post() ) {
			$this->ditty_id = isset( $_GET['post'] ) ? $_GET['post'] : false;
		}
		if ( is_array( $id ) ) {
			$meta = $this->parse_draft_data( $id['item_id'], $id );
		} elseif ( is_object( $id ) ) {
			$meta = $this->parse_draft_data( $id->item_id, $id );
		} elseif ( is_numeric( $id ) ) {
			$meta = $this->parse_draft_data( $id, Ditty()->db_items->get( $id ) );
		} elseif ( strpos( $id, 'new-' ) !== false ) {
			$meta = $this->parse_draft_data( $id );
		}
		if ( $meta ) {
			$this->construct_from_meta( $meta );
		}
	}
	
	/**
	 * Parse the draft data
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function parse_draft_data( $item_id, $meta = array() ) {
		$draft_values = ditty_draft_item_get_data( $item_id );
		
		if ( is_object( $meta ) ) {
			$meta = ( array ) $meta;
		}
		if ( ! $draft_values ) {
			return $meta;
		}
		$meta['item_id'] 			= isset( $draft_values['item_id'] ) 			? $draft_values['item_id'] 			: ( isset( $meta['item_id'] ) 			? $meta['item_id'] 			: $this->item_id );
		$meta['ditty_id'] 		= isset( $draft_values['ditty_id'] ) 			? $draft_values['ditty_id'] 		: ( isset( $meta['ditty_id'] ) 			? $meta['ditty_id'] 		: $this->ditty_id );
		$meta['item_type'] 		= isset( $draft_values['item_type'] ) 		? $draft_values['item_type']		: ( isset( $meta['item_type'] ) 		? $meta['item_type'] 		: $this->item_type );
		$meta['item_value'] 	= isset( $draft_values['item_value'] ) 		? $draft_values['item_value'] 	: ( isset( $meta['item_value'] ) 		? $meta['item_value'] 	: $this->item_value );
		$meta['layout_value']	= isset( $draft_values['layout_value'] )	? $draft_values['layout_value'] : ( isset( $meta['layout_value'] )	? $meta['layout_value'] : $this->layout_value );
		$meta['item_index']		= isset( $draft_values['item_index'] ) 		? $draft_values['item_index'] 	: ( isset( $meta['item_index'] )		? $meta['item_index']		: $this->item_index );
		$meta['item_author']	= isset( $draft_values['item_author'] ) 	? $draft_values['item_author'] 	: ( isset( $meta['item_author'] )		? $meta['item_author']	: $this->item_author );
		return $meta;
	}
	
	/**
	 * Construct the class from meta
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function construct_from_meta( $meta ) {
		if ( ! $meta ) {
			return false;
		}	
		if ( is_object( $meta ) ) {
			$meta = ( array ) $meta;
		}
		if ( is_array( $meta ) ) {
			$this->item_id 				= isset( $meta['item_id'] ) 			? $meta['item_id']				: $this->item_id;
			$this->item_uniq_id 	= isset( $meta['item_uniq_id'] )	? $meta['item_uniq_id']		: $this->item_id;
			$this->ditty_id 			= isset( $meta['ditty_id'] ) 			? $meta['ditty_id'] 			: $this->ditty_id;
			$this->item_type 			= isset( $meta['item_type'] ) 		? $meta['item_type'] 			: $this->item_type;
			$this->layout_value 	= isset( $meta['layout_value'] ) 	? maybe_unserialize( $meta['layout_value'] ) : $this->layout_value;
			$this->item_value 		= isset( $meta['item_value'] ) 		? maybe_unserialize( $meta['item_value'] ) : false;
			$this->item_index 		= isset( $meta['item_index'] ) 		? $meta['item_index'] 		: $this->item_index;
			$this->item_author 		= isset( $meta['item_author'] ) 	? $meta['item_author'] 		: $this->item_author;
			$this->date_created 	= isset( $meta['date_created'] ) 	? $meta['date_created'] 	: date( 'Y-m-d H:i:s' );
			$this->date_modified 	= isset( $meta['date_modified'] ) ? $meta['date_modified'] 	: date( 'Y-m-d H:i:s' );
			if ( $item_type_object 	= $this->get_type_object() ) {
				$this->icon 				= $item_type_object->get_icon();
				$this->label 				= $item_type_object->get_label();
				$this->layout_value = $item_type_object->confirm_layout_variations( $this->layout_value );
			}
		}
	}
	
	/**
	 * Return the database data for the item
	 * @access public
	 * @since  3.0.13
	 * @return string $db_data
	 */
	public function get_db_data() {
		$db_data = array(
			'item_id' 				=> $this->get_id(),
			'item_type' 			=> $this->get_type(),
			'item_value' 			=> $this->get_value(),
			'ditty_id' 				=> $this->get_ditty_id(),
			'layout_value' 		=> $this->get_layout_value(),
			'item_index'			=> $this->get_index(),
			'item_author'			=> $this->get_item_author(),
			'date_created'		=> $this->get_date_created(),
			'date_modified'		=> $this->get_date_modified(),
		);
		return $db_data;
	}
	
	/**
	 * Return the item id
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_id() {
		return $this->item_id;
	}
	
	/**
	 * Set the item id
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function set_id( $item_id ) {
		$this->item_id = $item_id;
		return $this->item_id;
	}
	
	/**
	 * Return the item base id
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_uniq_id() {
		return $this->item_uniq_id;
	}
	
	/**
	 * Return the Ditty id
	 * @access public
	 * @since  3.0
	 * @return int $ditty_id
	 */
	public function get_ditty_id() {
		return $this->ditty_id;
	}
	
	/**
	 * Return the Ditty id
	 * @access public
	 * @since  3.0
	 * @return int $ditty_id
	 */
	public function set_ditty_id( $ditty_id ) {
		$this->ditty_id = $ditty_id;
		return $this->ditty_id;
	}
	
	/**
	 * Return the item type object
	 * @access public
	 * @since  3.0
	 * @return int $item_type_object
	 */
	public function get_type_object() {
		if ( ! $this->item_type_object ) {
			 $this->item_type_object = ditty_item_type_object( $this->item_type );
		}
		return $this->item_type_object;
	}

	/**
	 * Return the item type settings fields
	 * @access public
	 * @since  3.0
	 * @return int $ditty_id
	 */
	public function get_setting_fields() {
		$item_type_object = $this->get_type_object();
		$settings = $item_type_object->settings( $this->get_value(), 'return' );
		return $settings;
	}

	/**
	 * Return the item type
	 * @access public
	 * @since  3.0
	 * @return string $type
	 */
	public function get_type() {
		return $this->item_type;
	}

	/**
	 * Set the item type
	 * @access public
	 * @since  3.0
	 * @return null
	 */
	public function set_item_type( $item_type ) {
		$item_types = ditty_item_types();	
		if ( ! isset( $item_types[$item_type] ) ) {
			return false;
		}
		if ( $item_type == $this->item_type ) {
			return false;
		}
		$this->item_type = $item_type;
		
		if ( $item_type_object = ditty_item_type_object( $this->item_type ) ) {
			$this->item_type_object 	= $item_type_object;
			$this->icon 							= $item_type_object->get_icon();
			$this->label 							= $item_type_object->get_label();
			$this->layout_value				= $item_type_object->get_layout_variation_defaults();	
		}
	}
	
	/**
	 * Return the item layout value
	 *
	 * @access public
	 * @since  3.0
	 * @return array $layout_value
	 */
	public function get_layout_value() {
		return $this->layout_value;
	}
	
	/**
	 * Set the item layout value
	 *
	 * @access public
	 * @since  3.0
	 * @return null
	 */
	public function set_layout_value( $layout_value ) {
		$this->layout_value = $layout_value;
	}
	
	/**
	 * Return the item value
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_value() {
		return stripslashes_deep( maybe_unserialize( $this->item_value ) );
	}
	
	/**
	 * Set the item value
	 * @access public
	 * @since  3.0.13
	 * @return array $sanitized_item_value
	 */
	public function set_item_value( $item_value = array() ) {
		if ( $item_type_object = $this->get_type_object() ) {
			$sanitized_item_value = $item_type_object->sanitize_settings( $item_value );		
			$this->item_value = maybe_serialize( $sanitized_item_value );
			
			// Set the modified time
			$this->set_date_modified();
			
			// Return the value
			return $sanitized_item_value;
		}
	}
	
	/**
	 * Return the item index
	 * @access public
	 * @since  3.0
	 * @return int $item_index
	 */
	public function get_index() {
		if ( $this->item_index ) {
			return $this->item_index;
		} else {
			return 0;
		}
	}
	
	/**
	 * Set the item value
	 * @access public
	 * @since  3.0
	 * @return int $item_index
	 */
	public function set_item_index( $item_index ) {
		$this->item_index = $item_index;
		return $this->item_index;
	}
	
	/**
	 * Return the item author
	 * @access public
	 * @since  3.0.13
	 * @return int $item_author
	 */
	public function get_item_author() {
		if ( $this->item_author ) {
			return $this->item_author;
		}
	}
	
	/**
	 * Set the date created
	 * @access public
	 * @since  3.0.13
	 * @return int $item_author
	 */
	public function set_item_author( $author = false ) {
		if ( $author ) {
			$this->item_author = intval( $author );
		} else {
			$this->item_author = get_current_user_id();
		}
		return $this->item_author;
	}
	
	/**
	 * Return the date created
	 * @access public
	 * @since  3.0.13
	 * @return date $date_created
	 */
	public function get_date_created() {
		if ( $this->date_created ) {
			return $this->date_created;
		} else {
			return date( 'Y-m-d H:i:s' );
		}
	}
	
	/**
	 * Set the date created
	 * @access public
	 * @since  3.0.13
	 * @return date $date_created
	 */
	public function set_date_created( $date = false ) {
		if ( $date ) {
			$this->date_created = sanitize_text_field( $date );
		} else {
			$this->date_created = date( 'Y-m-d H:i:s' );
		}
		return $this->date_created;
	}
	
	/**
	 * Return the date modified
	 * @access public
	 * @since  3.0.13
	 * @return date $date_modified
	 */
	public function get_date_modified() {
		if ( $this->date_modified ) {
			return $this->date_modified;
		} else {
			return date( 'Y-m-d H:i:s' );
		}
	}
	
	/**
	 * Set the date modified
	 * @access public
	 * @since  3.0.13
	 * @return date $date_created
	 */
	public function set_date_modified( $date = false ) {
		if ( $date ) {
			$this->date_modified = sanitize_text_field( $date );
		} else {
			$this->date_modified = date( 'Y-m-d H:i:s' );
		}
		return $this->date_modified;
	}
	
	/**
	 * Return the item icon
	 * @access public
	 * @since  3.0
	 * @return string $icon
	 */
	public function get_icon() {
		return $this->icon;
	}
	
	/**
	 * Return the item label
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Get the current item values
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function get_values() {
		$values = array(
			'item_id' 			=> $this->get_id(),
			'ditty_id' 			=> $this->get_ditty_id(),
			'item_type' 		=> $this->get_type(),
			'item_value' 		=> $this->get_value(),
			'layout_value'	=> $this->get_layout_value(),
			'item_author' 	=> $this->get_item_author(),
			'date_created'	=> $this->get_date_created(),
			'date_modified'	=> $this->get_date_modified(),
		);
		return $values;
	}
	
	/**
	 * Return all custom meta for the item
	 * @access public
	 * @since  3.0
	 * @return string meta
	 */
	public function custom_meta() {
		return ditty_item_custom_meta( $this->item_id );
	}

	/**
	 * Return the item preview
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_preview() {	
		if ( $item_type_object = $this->get_type_object() ) {
			return stripslashes( $item_type_object->editor_preview( $this->get_value() ) );
		} else {
			return sprintf( __( '<strong>%s</strong> item type does not exist!', 'ditty-news-ticker' ), $this->get_type() );
		}
	}

	/**
	 * Setup the edito item classes
	 * @access public
	 * @since  3.0
	 * @return string $classes
	 */
	public function get_editor_classes() {	
		$classes = array();
		$classes[] = 'ditty-data-list__item';
		$classes[] = 'ditty-editor-item';
		$classes[] = 'ditty-editor-item--' . esc_attr( $this->get_type() );
		if ( ! $this->get_type_object() ) {
			$classes[] = 'ditty-editor-item--error';
		}
		if ( apply_filters( 'ditty_editor_item_disabled', false, $this->get_id() ) ) {
			$classes[] = 'ditty-editor-item--disabled';
		}
		$classes = apply_filters( 'ditty_editor_item_classes', $classes, $this );
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
			'id'								=> 'ditty-editor-item--' . $this->get_id(),
			'class' 						=> $this->get_editor_classes(),
			'data-ditty_id' 		=> $this->get_ditty_id(),
			'data-item_id' 			=> $this->get_id(),
			'data-item_type' 		=> $this->get_type(),
			'data-item_value' 	=> json_encode( $this->get_value() ),
			'data-layout_value' => json_encode( $this->get_layout_value() ),
		);
		?>	
		<div <?php echo ditty_attr_to_html( $atts ); ?>>
			<?php do_action( 'ditty_editor_item_elements', $this ); ?>
		</div>
		<?php
		if ( 'return' == $render ) {
			return trim( ob_get_clean() );
		}
	}

	/**
	 * Return the display items
	 *
	 * @access public
	 * @since  3.0
	 * @return array $display_items
	 */
	public function get_display_items() {
		$display_meta = ditty_prepare_display_items( $this->get_values() );
		$display_items = array();
		if ( is_array( $display_meta ) && count( $display_meta ) > 0 ) {
			foreach ( $display_meta as $i => $meta ) {
				$display_item = new Ditty_Display_Item( $meta );
				if ( $data = $display_item->compile_data() ) {
					$display_items[] = $data;
				}
			}
		}
		return $display_items;
	}
	
}