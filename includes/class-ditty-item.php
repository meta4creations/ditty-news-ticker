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
	private $layout_id;
	private $layout_value;
	private $layout_type;
	private $item_value;
	private $item_index;
	private $icon;
	private $label;
	private $item_type_object;
	private $layout_type_object;
	private $layout_object;
	//private $api_id;
	private $mode;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $id = false ) {
		$this->mode					= 'live';
		$this->item_id 			= -1;
		$this->item_uniq_id = -1;
		$this->item_type 		= 'default';
		$this->layout_id 		= 'default';
		$this->layout_value = 'default';
		$this->layout_type 	= 'default';
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
		$meta['ditty_id'] 		= isset( $draft_values['ditty_id'] ) 			? $draft_values['ditty_id'] 		: ( isset( $meta['ditty_id'] ) 		? $meta['ditty_id'] 		: $this->ditty_id );
		$meta['item_type'] 		= isset( $draft_values['item_type'] ) 		? $draft_values['item_type']		: ( isset( $meta['item_type'] ) 		? $meta['item_type'] 		: $this->item_type );
		$meta['item_value'] 	= isset( $draft_values['item_value'] ) 		? $draft_values['item_value'] 	: ( isset( $meta['item_value'] ) 		? $meta['item_value'] 	: $this->item_value );
		$meta['layout_id'] 		= isset( $draft_values['layout_id'] ) 		? $draft_values['layout_id'] 		: ( isset( $meta['layout_id'] ) 		? $meta['layout_id'] 		: $this->layout_id );
		$meta['layout_value']	= isset( $draft_values['layout_value'] )	? $draft_values['layout_value'] : ( isset( $meta['layout_value'] )	? $meta['layout_value'] : $this->layout_value );
		$meta['item_index']		= isset( $draft_values['item_index'] ) 		? $draft_values['item_index'] 	: ( isset( $meta['item_index'] )		? $meta['item_index']		: $this->item_index );
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
			$this->item_id 			= isset( $meta['item_id'] ) 			? $meta['item_id']			: $this->item_id;
			$this->item_uniq_id = isset( $meta['item_uniq_id'] )	? $meta['item_uniq_id']	: $this->item_id;
			$this->ditty_id 		= isset( $meta['ditty_id'] ) 			? $meta['ditty_id'] 		: $this->ditty_id;
			$this->item_type 		= isset( $meta['item_type'] ) 		? $meta['item_type'] 		: $this->item_type;
			$this->layout_id 		= isset( $meta['layout_id'] ) 		? $meta['layout_id'] 		: $this->layout_id;
			$this->item_value 	= isset( $meta['item_value'] ) 		? maybe_unserialize( $meta['item_value'] ) : false;
			$this->item_index 	= isset( $meta['item_index'] ) 		? $meta['item_index'] : $this->item_index;
			if ( $item_type_object 	= $this->get_type_object() ) {
				$this->icon 				= $item_type_object->get_icon();
				$this->label 				= $item_type_object->get_label();
				$this->layout_type 	= $item_type_object->get_layout_type();
				if ( $layout_type_object = $this->get_layout_type_object() ) {
					$layout_value = isset( $meta['layout_value'] ) ? maybe_unserialize( $meta['layout_value'] ) : array();
					$this->layout_value = $layout_type_object->get_variation_values( $layout_value );
					if ( $layout_object = $this->get_layout_object() ) {
						$this->layout_id = $layout_object->get_layout_id();
					}
				}
			}
		}
		//$this->ensure_item_value();
	}
	
	/**
	 * Return the database data for the item
	 * @access public
	 * @since  3.0
	 * @return string $db_data
	 */
	public function get_db_data() {
		$db_data = array(
			'item_id' 			=> $this->get_id(),
			'item_type' 		=> $this->get_type(),
			'item_value' 		=> $this->get_value(),
			'ditty_id' 			=> $this->get_ditty_id(),
			'layout_id'			=> $this->get_layout_id(),
			'layout_value' 	=> $this->get_layout_value(),
			'item_index'		=> $this->get_index(),	
		);
		return $db_data;
	}

	/**
	 * Return the item mode
	 * @access public
	 * @since  3.0
	 * @return string $mode
	 */
	public function get_mode() {
		return $this->mode;
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
	 * Return the layout type object
	 * @access public
	 * @since  3.0
	 * @return int $layout_type_object
	 */
	public function get_layout_type_object() {
		if ( ! $this->layout_type_object ) {
			 $this->layout_type_object = ditty_layout_type_object( $this->layout_type );
		}
		return $this->layout_type_object;
	}
	
	/**
	 * Return the layout object
	 * @access public
	 * @since  3.0
	 * @return int $layout_type_object
	 */
	public function get_layout_object() {
		if ( ! $this->layout_object ) {
			$this->layout_object = new Ditty_Layout( $this->layout_value, $this->layout_type, $this->item_value );
		}
		return $this->layout_object;
	}
	
	/**
	 * Return the metabox values
	 * @access public
	 * @since  3.0
	 * @return int $ditty_id
	 */
	// public function get_metabox_values() {
	// 	$item_type_object = $this->get_type_object();
	// 	return $item_type_object->metabox( $this->get_value() );
	// }
	
	/**
	 * Return the item type settings fields
	 * @access public
	 * @since  3.0
	 * @return int $ditty_id
	 */
	public function get_setting_fields() {
		$item_type_object = $this->get_type_object();
		return $item_type_object->settings( $this->get_value() );
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
			$this->layout_type 				= $item_type_object->get_layout_type();
			$this->layout_object			= null;
			if ( $layout_type_object = $this->get_layout_type_object() ) {
				$this->set_layout_value( $layout_type_object->variation_defaults() );
			}		
		}

		// Make sure item values exist
		//$this->ensure_item_value( 'force' );
	}
	
	/**
	 * Return the item layout id
	 *
	 * @access public
	 * @since  3.0
	 * @return int/string $layout_id
	 */
	public function get_layout_id() {
		return $this->layout_id;
	}
	
	/**
	 * Set the item layout id
	 *
	 * @access public
	 * @since  3.0
	 * @return null
	 */
	// public function set_layout_id( $layout_id ) {
	// 	$this->layout_id = $layout_id;
	// }
	
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
	 * Set the item layout value by id
	 *
	 * @access public
	 * @since  3.0
	 * @return null
	 */
	// public function set_layout_variation_id( $layout_id, $variation_id = 'default' ) {
	// 	$layout_value = $this->get_layout_value();
	// 	if ( ! is_array( $layout_value ) ) {
	// 		$layout_value = array();
	// 	}
	// 	$layout_value[$variation_id] = $layout_id;		
	// 	$layout_type_object = $this->get_layout_type_object();
	// 	$layout_value = $layout_type_object->get_variation_values( $layout_value );
	// 	$this->layout_value = $layout_value;
	// }
	
	/**
	 * Return the item layout type
	 * @access public
	 * @since  3.0
	 * @return int/string $layout_type
	 */
	// public function get_layout_type() {
	// 	return $this->layout_type;
	// }
	
	/**
	 * Set the item layout type
	 * @access public
	 * @since  3.0
	 * @return int/string $layout_type
	 */
	// public function set_layout_type( $layout_type ) {
	// 	$this->layout_type = $layout_type;
	// }
	
	/**
	 * Make sure there is a item value
	 * @access public
	 * @since  3.0
	 * @return array $sanitized_item_value
	 */
	// private function ensure_item_value( $type = '' ) {
	// 	$item_type_object = $this->get_type_object();
	// 	if ( $item_type_object ) {
	// 		$value = $item_type_object->init_item_value( $this->get_value() );
	// 		$this->set_item_value( $value );
	// 	}
	// }
	
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
	 * @since  3.0
	 * @return array $sanitized_item_value
	 */
	public function set_item_value( $item_value = array() ) {
		$sanitized_item_value = $this->item_type_object->sanitize_settings( $item_value );		
		$this->item_value = maybe_serialize( $sanitized_item_value );
		return $sanitized_item_value;
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
			'layout_id'			=> $this->get_layout_id(),
			'layout_value'	=> $this->get_layout_value(),
		);
		return $values;
	}
	
	/**
	 * Retrieve meta field for a item.
	 *
	 * @param   string $meta_key      The meta key to retrieve.
	 * @param   bool   $single        Whether to return a single value.
	 * @return  mixed                 Will be an array if $single is false. Will be value of meta data field if $single is true.
	 *
	 * @since   3.0
	 */
	// public function get_meta( $meta_key = '', $single = true ) {
	// 	return ditty_item_get_meta( $this->item_id, $meta_key, $single );
	// }

	/**
	 * Add meta data field to a item.
	 *
	 * @param   string $meta_key      Metadata name.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   bool   $unique        Optional, default is false. Whether the same key should not be added.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @since   3.0
	 */
	// public function add_meta( $meta_key = '', $meta_value, $unique = false ) {
	// 	ditty_item_add_meta( $this->item_id, $meta_key, $meta_value, $unique );
	// }

	/**
	 * Update item meta field based on item ID.
	 *
	 * @param   string $meta_key      Metadata key.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   mixed  $prev_value    Optional. Previous value to check before removing.
	 * @return  bool                  False on failure, true if success.
	 *
	 * @since   3.0
	 */
	// public function update_meta( $meta_key = '', $meta_value, $prev_value = '' ) {
	// 	return ditty_item_update_meta( $this->item_id, $meta_key, $meta_value, $prev_value );
	// }

	/**
	 * Remove metadata matching criteria from a item.
	 *
	 * @param   string $meta_key      Metadata name.
	 * @param   mixed  $meta_value    Optional. Metadata value.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @since   3.0
	 */
	// public function delete_meta( $meta_key = '', $meta_value = '' ) {
	// 	return ditty_item_delete_meta( $this->item_id, $meta_key, $meta_value );
	// }
	
	/**
	 * Return all custom meta for the item
	 * @access public
	 * @since  3.0
	 * @return string $mode
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
		$classes[] = 'ditty-editor-item--' . esc_attr( $this->item_type );
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
			'data-item_type' 		=> $this->item_type,
			'data-item_value' 	=> json_encode( $this->item_value ),
			'data-layout_id' 		=> $this->layout_id,
			'data-layout_type' 	=> $this->layout_type,
			'data-layout_value' => json_encode( $this->layout_value ),
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
	 * Return the display meta
	 *
	 * @access public
	 * @since  3.0
	 * @return array $display_meta
	 */
	public function get_display_meta() {
		$item_type_object = $this->get_type_object();
		if ( ! $item_type_object ) {
			return false;
		}
		$display_meta = $item_type_object->prepare_items( $this->get_values() );
		return $display_meta;
	}
	
	/**
	 * Return the display items
	 *
	 * @access public
	 * @since  3.0
	 * @return array $display_items
	 */
	public function get_display_items() {
		$display_meta = $this->get_display_meta();
		$display_items = array();
		
		if ( is_array( $display_meta ) && count( $display_meta ) > 0 ) {
			foreach ( $display_meta as $i => $meta ) {
				
				$mode = ( 'draft' == $this->mode ) ? 'edit' : 'live';
				$display_item = new Ditty_Display_Item( $meta, $mode );
				if ( $data = $display_item->compile_data() ) {
					$display_items[] = $data;
				}
			}
		}
		return $display_items;
	}
	
}