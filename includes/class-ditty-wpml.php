<?php

/**
 * Ditty WMPL Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty WPML
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.9
*/

class Ditty_WPML {

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0.9
	 */
	public function __construct() {	
		add_action( 'icl_make_duplicate', array( $this, 'after_duplicate_post' ), 10, 4 );	
	}
	
	/**
	 * Add custom table data after WPML duplication
	 *
	 * @access  public
	 * @since   3.0.9
	 * @param   $html
	 */
	public function after_duplicate_post( $master_post_id, $lang, $post_array, $id ) {
		
		// Delete any existing Ditty items
		$items_meta = ditty_items_meta( $id );
		if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {
			foreach ( $items_meta as $i => $item ) {
				Ditty()->db_items->delete( $item->item_id );
			}
		}	
		
		// Duplicate and add master Ditty items
		$all_meta = Ditty()->db_items->get_items( $master_post_id );
		if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
			foreach ( $all_meta as $i => $meta ) {
				unset( $meta->item_id );
				$meta->ditty_id = $id;
				Ditty()->db_items->insert( $meta, 'item' );
			} 
		}
		
		// Delete possible transient for Ditty
		$transient_name = "ditty_display_items_{$id}";
		delete_transient( $transient_name );
	}

}