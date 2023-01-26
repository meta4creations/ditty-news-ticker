<?php
/**
 * Ditty Display Item Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Item
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Display_Item {
	
	private $layout_id;
	private $layout_value;
	private $layout_object;
	private $item_id;
	private $item_uniq_id;
	private $item_type;
	private $item_type_object;
	private $item_value;
	private $item_author;
	private $ditty_id;
	private $has_error;
	private $custom_classes;
	private $date_created;
	private $date_modified;
	
	/**
	 * Get things started\*
	 * @access  public
	 * @since   3.0.13
	 */
	public function __construct( $meta ) {	
		$this->layout_value 	= isset( $meta['layout_value'] ) 		? maybe_unserialize( $meta['layout_value'] ) 	: false;
		$this->item_value 		= isset( $meta['item_value'] ) 			? maybe_unserialize( $meta['item_value'] )		: '';		
		$this->item_id 				= isset( $meta['item_id'] ) 				? $meta['item_id'] 				: -1;
		$this->item_uniq_id 	= isset( $meta['item_uniq_id'] ) 		? $meta['item_uniq_id'] 	: $this->item_id;
		$this->item_type 			= isset( $meta['item_type'] ) 			? $meta['item_type'] 			: false;
		$this->ditty_id 			= isset( $meta['ditty_id'] ) 				? $meta['ditty_id'] 			: -1;
		$this->has_error 			= isset( $meta['has_error'] ) 			? $meta['has_error']			: false;
		$this->custom_classes = isset( $meta['custom_classes'] ) 	? $meta['custom_classes']	: false;
		$this->item_author		= isset( $meta['item_author'] ) 		? intval( $meta['item_author'] )	: false;
		$this->date_created		= isset( $meta['date_created'] ) 		? $meta['date_created']	: false;
		$this->date_modified	= isset( $meta['date_modified'] ) 	? $meta['date_modified']	: false;
		$this->parse_layout_id();
	}
	
	/**
	 * Return the database data for the item
	 *
	 * @access public
	 * @since  3.0.13
	 * @return string $db_data
	 */
	public function get_db_data() {
		$db_data = array(
			'item_id' 				=> $this->get_id(),
			'item_type' 			=> $this->get_item_type(),
			'item_value' 			=> $this->get_value(),
			'item_author'			=> $this->get_item_author(),
			'ditty_id' 				=> $this->get_ditty_id(),
			'layout_value' 		=> $this->get_layout_value(),
			'date_created'		=> $this->get_date_created(),
			'date_modified'		=> $this->get_date_modified(),
		);
		return $db_data;
	}

	/**
	 * Return the item id
	 *
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_id() {
		return $this->item_id;
	}
	
	/**
	 * Return the item uniq id
	 *
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_uniq_id() {
		return $this->item_uniq_id;
	}
	
	/**
	 * Return the item parent id
	 *
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_parent_id() {
		return 0;
	}
	
	/**
	 * Return the Ditty id
	 *
	 * @access public
	 * @since  3.0.13
	 * @return int $ditty_id
	 */
	public function get_ditty_id() {
		return $this->ditty_id;
	}
	
	/**
	 * Return the item value
	 *
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_value() {
		return maybe_unserialize( $this->item_value );
	}
	
	/**
	 * Return the item type
	 *
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_item_type() {
		return $this->item_type;
	}
	
	/**
	 * Return the item author
	 *
	 * @access public
	 * @since  3.0.13
	 * @return int $item_author
	 */
	public function get_item_author() {
		return $this->item_author;
	}

	/**
	 * Return the layout id
	 *
	 * @access public
	 * @since  3.0
	 * @return int $layout_id
	 */
	public function get_layout_id() {
		return $this->layout_id;
	}
	
	/**
	 * Return the layout value
	 *
	 * @access public
	 * @since  3.0
	 * @return int $layout_id
	 */
	public function get_layout_value() {
		return $this->layout_value;
	}
	
	/**
	 * Return the date created
	 *
	 * @access public
	 * @since  3.0.13
	 * @return date $date_created
	 */
	public function get_date_created() {
		return $this->date_created;
	}
	
	/**
	 * Return the date modified
	 *
	 * @access public
	 * @since  3.0.13
	 * @return date $date_modified
	 */
	public function get_date_modified() {
		return $this->date_modified;
	}

	/**
	 * Return the layout css
	 *
	 * @access public
	 * @since  3.0
	 * @return html
	 */
	public function get_layout_css() {
		if ( $layout_object = $this->get_layout_object() ) {
			$layout_css = $layout_object->get_css_compiled();
			return $layout_css;
		}
	}
	
	/**
	 * Return the item type object
	 *
	 * @access public
	 * @since  3.0
	 * @return int $item_type_object
	 */
	private function get_type_object() {
		if ( ! $this->item_type_object ) {
			 $this->item_type_object = ditty_item_type_object( $this->item_type );
		}
		return $this->item_type_object;
	}

	/**
	 * Return the layout object
	 *
	 * @access public
	 * @since  3.0
	 * @return int $layout_object
	 */
	public function get_layout_object() {
		if ( ! $this->layout_object ) {
			$this->layout_object = new Ditty_Layout( $this->get_layout_id(), $this->get_item_type(), $this->item_value, $this->get_db_data() );
		}
		return $this->layout_object;
	}
	
	/**
	 * Confirm that the layout exists
	 *
	 * @access public
	 * @since  3.0.13
	 * @return int $id
	 */
	private function parse_layout_id() {
		$layout_value 		= $this->get_layout_value();
		$layout_id 				= isset( $layout_value['default'] ) ? $layout_value['default'] : 0;
		$this->layout_id 	= apply_filters( 'ditty_display_item_layout_id', $layout_id, $this );
		if ( ! $this->layout_id || ( false === strpos( $layout_id, 'new-' ) && 'publish' != get_post_status( $this->layout_id ) ) ) {
			$default_layout = ditty_get_default_layout();
			if ( $default_layout && 'publish' == get_post_status( $this->layout_id )  ) {
				$this->layout_id = $default_layout;
			} else {
				$this->item_value = array( 'ditty_feed_error' => sprintf( __( 'Choose a layout to render your %s item!', 'ditty-news-ticker' ), $this->get_item_type() ) );
				$this->has_error = true;
			}
		}
	}

	/**
	 * Return custom classes
	 *
	 * @access private
	 * @since  3.0
	 * @return string $classes
	 */
	private function get_custom_classes() {	
		if ( $this->custom_classes ) {
			$classes = explode( ' ', $this->custom_classes );
			return $classes;
		}
	}

	/**
	 * Setup the item classes
	 *
	 * @access private
	 * @since  3.0
	 * @return string $classes
	 */
	private function get_classes() {	
		$classes = array();
		$classes[] = 'ditty-item';
		$classes[] = 'ditty-item--' . esc_attr( $this->item_id );
		if ( $this->item_id != $this->item_uniq_id ) {
			$classes[] = 'ditty-item--' . esc_attr( $this->item_uniq_id );
		}
		$classes[] = 'ditty-item-type--' . esc_attr( $this->item_type );
		if ( $this->layout_id ) {
			$classes[] = 'ditty-layout--' . esc_attr( $this->layout_id );
		} else {
			$classes[] = 'ditty-layout--default';
		}
		if ( $this->has_error ) {
			$classes[] = 'ditty-item--error';
		}
		if ( $custom_classes = $this->get_custom_classes() ) {
			$classes = array_merge( $classes, $custom_classes );
		}		
		$classes = apply_filters( 'ditty_display_item_classes', $classes, $this->item_id );	
		return implode( ' ', $classes );
	}

	/**
	 * Render the item via layout
	 *
	 * @access public
	 * @since  3.1
	 * @return html
	 */
	public function get_elements() {
		if ( $layout_object = $this->get_layout_object() ) {
			return do_shortcode( $layout_object->render() );
		}
	}

	/**
	 * Render the item via layout
	 *
	 * @access public
	 * @since  3.0
	 * @return html
	 */
	public function render_html( $render='echo' ) {
		$html = '';
		if ( $layout_object = $this->get_layout_object() ) {
			$atts = array(
				'class' 						=> $this->get_classes(),
				'data-item_id' 			=> $this->get_id(),
				'data-item_uniq_id' => $this->get_uniq_id(),
				'data-parent_id' 		=> $this->get_parent_id(),
				'data-item_type' 		=> $this->get_item_type(),
				'data-layout_id' 		=> $this->get_layout_id(),
			);
			
			$html .= '<div ' . ditty_attr_to_html( $atts ) . '>';	
				$html .= '<div class="ditty-item__elements">';
					$html .= $this->get_elements();
				$html .= '</div>';
			$html .= '</div>';
		}
		
		// Filter the html
		$html = apply_filters( 'ditty_render_item', $html, $this );
		
		if ( 'echo' == $render ) {
			echo $html;
		} else {
			return $html;
		}
	}
	
	/**
	 * Compile the layout data
	 *
	 * @access public
	 * @since  3.0
	 * @return html
	 */
	public function compile_data( $type = 'php' ) {
		if ( 'javascript' == $type ) {
			if ( $elements = $this->get_elements() ) {
				$data = array(
					'item_id'	 		=> ( string ) $this->get_id(),
					'uniq_id'	 		=> ( string ) $this->get_uniq_id(),
					'parent_id'	 	=> ( string ) $this->get_parent_id(),
					'elements' 		=> $elements,
					'css'					=> $this->get_layout_css(),
					'layout_id'		=> $this->get_layout_id(),
					'is_disabled' => array_unique( apply_filters( 'ditty_item_disabled', array(), $this->get_id() ) ),
				);
				return $data;
			}
		} else {
			if ( $html = $this->render_html( 'return' ) ) {
				$data = array(
					'id'	 				=> ( string ) $this->get_id(),
					'uniq_id'	 		=> ( string ) $this->get_uniq_id(),
					'parent_id'	 	=> ( string ) $this->get_parent_id(),
					'html' 				=> $html,
					'css'					=> $this->get_layout_css(),
					'layout_id'		=> $this->get_layout_id(),
					'is_disabled' => array_unique( apply_filters( 'ditty_item_disabled', array(), $this->get_id() ) ),
				);
				return $data;
			}
		}
	}

}