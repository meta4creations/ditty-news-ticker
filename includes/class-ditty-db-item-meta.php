<?php
/**
 * Ditty DB Item Meta Class
 *
 * This class is for interacting with the layout meta database table
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Item Meta
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Ditty_DB_Item_Meta extends Ditty_DB {

	/**
	 * Get things started
	 *
	 * @since   3.0
	*/
	public function __construct() {
		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'ditty_itemmeta';
		$this->primary_key = 'item_id';
		$this->version     = '1.0';

		add_action( 'plugins_loaded', array( $this, 'register_table' ), 11 );
	}

	/**
	 * Get table columns and data types
	 *
	 * @since   3.0
	*/
	public function get_columns() {
		return array(
			'meta_id'     => '%d',
			'item_id'     => '%d',
			'meta_key'    => '%s',
			'meta_value'  => '%s',
		);
	}

	/**
	 * Register the table with $wpdb so the metadata api can find it
	 *
	 * @since   3.0
	*/
	public function register_table() {
		global $wpdb;
		$wpdb->itemmeta = $this->table_name;
	}

	/**
	 * Retrieve meta field for a item.
	 *
	 * For internal use only. Use Ditty_Item->get_item_meta() for public usage.
	 *
	 * @param   int    $item_id   		Item ID.
	 * @param   string $meta_key      The meta key to retrieve.
	 * @param   bool   $single        Whether to return a single value.
	 * @return  mixed                 Will be an array if $single is false. Will be value of meta data field if $single is true.
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function get_meta( $item_id = 0, $meta_key = '', $single = false ) {
		$item_id = $this->sanitize_item_id( $item_id );
		if ( false === $item_id ) {
			return false;
		}

		return get_metadata( 'item', $item_id, $meta_key, $single );
	}

	/**
	 * Add meta data field to a layout.
	 *
	 * For internal use only. Use Ditty_Item->add_item_meta() for public usage.
	 *
	 * @param   int    $item_id   		Item ID.
	 * @param   string $meta_key      Metadata key.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   bool   $unique        Optional, default is false. Whether the same key should not be added.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @access  private
	 * @since   3.0.randombytes_random16()
	 */
	public function add_meta( $item_id = 0, $meta_key = '', $meta_value = false, $unique = false ) {
		if ( ! $meta_value ) {
			return false;
		}
		$item_id = $this->sanitize_item_id( $item_id );
		if ( false === $item_id ) {
			return false;
		}

		return add_metadata( 'item', $item_id, $meta_key, $meta_value, $unique );
	}

	/**
	 * Update item meta field based on Item ID.
	 *
	 * For internal use only. Use Ditty_Item->update_item_meta() for public usage.
	 *
	 * Use the $prev_value parameter to differentiate between meta fields with the
	 * same key and Item ID.
	 *
	 * If the meta field for the item does not exist, it will be added.
	 *
	 * @param   int    $item_id   		Item Post ID.
	 * @param   string $meta_key      Metadata key.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   mixed  $prev_value    Optional. Previous value to check before removing.
	 * @return  bool                  False on failure, true if success.
	 *
	 * @access  private
	 * @since   3.0.16
	 */
	public function update_meta( $item_id = 0, $meta_key = '', $meta_value = false, $prev_value = '' ) {
		if ( ! $meta_value ) {
			return false;
		}
		$item_id = $this->sanitize_item_id( $item_id );
		if ( false === $item_id ) {
			return false;
		}

		return update_metadata( 'item', $item_id, $meta_key, $meta_value, $prev_value );
	}

	/**
	 * Remove metadata matching criteria from a item.
	 *
	 * For internal use only. Use Ditty_Item->delete_item_meta() for public usage.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching key, if needed.
	 *
	 * @param   int    $item_id   		Item ID.
	 * @param   string $meta_key      Metadata name.
	 * @param   mixed  $meta_value    Optional. Metadata value.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function delete_meta( $item_id = 0, $meta_key = '', $meta_value = '' ) {
		return delete_metadata( 'item', $item_id, $meta_key, $meta_value );
	}
	
	/**
	 * Retrieve all metadata by a specific item
	 *
	 * @since   3.0
	 * @return  object
	 */
	public function custom_meta( $item_id = 0 ) {
		global $wpdb;
		$item_id = esc_sql( $item_id );
		
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE item_id = %s;", $item_id ) );
	}

	/**
	 * Create the table
	 *
	 * @since   3.0
	*/
	public function create_table() {

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );	
		
		$sql = "CREATE TABLE {$this->table_name} (
			meta_id bigint(20) NOT NULL AUTO_INCREMENT,
			item_id bigint(20) NOT NULL,
			meta_key varchar(255) DEFAULT NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY item_id (item_id),
			KEY meta_key (meta_key)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

	/**
	 * Given a layout post ID, make sure it's a positive number, greater than zero before inserting or adding.
	 *
	 * @since  3.0
	 * @param  int|stirng $post		A passed layout post ID.
	 * @return int|bool           The normalized layout post ID or false if it's found to not be valid.
	 */
	private function sanitize_item_id( $item_id ) {
		if ( ! is_numeric( $item_id ) ) {
			return false;
		}

		$item_id = (int) $item_id;

		// We were given a non positive number
		if ( absint( $item_id ) !== $item_id ) {
			return false;
		}

		if ( empty( $item_id ) ) {
			return false;
		}

		return absint( $item_id );
	}

}
