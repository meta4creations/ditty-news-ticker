<?php

/**
 * Ditty News Ticker Layout Class
 *
 * @package     Ditty News Ticker
 * @subpackage  Classes/Ditty News Ticker Layouts
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * DNT_Layout Class
 *
 * @since 3.0
 */
class DNT_Layout {
	
	/**
	 * The layout ID
	 *
	 * @since 3.0
	 */
	public $id = 0;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $id = false ) {
		
		if ( $id ) {
			$this->id = $id;
		}
	}
	
	
	/**
	 * Retrieve layout meta field for a layout.
	 *
	 * @param   string $meta_key      The meta key to retrieve.
	 * @param   bool   $single        Whether to return a single value.
	 * @return  mixed                 Will be an array if $single is false. Will be value of meta data field if $single is true.
	 *
	 * @since   3.0
	 */
	public function get_meta( $meta_key = '', $single = true ) {
		return DNT()->layout_meta->get_meta( $this->id, $meta_key, $single );
	}

	/**
	 * Add meta data field to a layout.
	 *
	 * @param   string $meta_key      Metadata name.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   bool   $unique        Optional, default is false. Whether the same key should not be added.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @since   3.0
	 */
	public function add_meta( $meta_key = '', $meta_value, $unique = false ) {
		return DNT()->layout_meta->add_meta( $this->id, $meta_key, $meta_value, $unique );
	}

	/**
	 * Update layout meta field based on layout ID.
	 *
	 * @param   string $meta_key      Metadata key.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   mixed  $prev_value    Optional. Previous value to check before removing.
	 * @return  bool                  False on failure, true if success.
	 *
	 * @since   3.0
	 */
	public function update_meta( $meta_key = '', $meta_value, $prev_value = '' ) {
		return DNT()->layout_meta->update_meta( $this->id, $meta_key, $meta_value, $prev_value );
	}

	/**
	 * Remove metadata matching criteria from a layout.
	 *
	 * @param   string $meta_key      Metadata name.
	 * @param   mixed  $meta_value    Optional. Metadata value.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @since   3.0
	 */
	public function delete_meta( $meta_key = '', $meta_value = '' ) {
		return DNT()->layout_meta->delete_meta( $this->id, $meta_key, $meta_value );
	}
	
	
}