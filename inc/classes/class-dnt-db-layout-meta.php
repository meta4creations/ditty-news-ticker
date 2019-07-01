<?php
/**
 * Layout Meta DB class
 *
 * This class is for interacting with the layout meta database table
 *
 * @package     DNT
 * @subpackage  Classes/DNT Layout Meta
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DNT_DB_Layout_Meta extends DNT_DB {

	/**
	 * Get things started
	 *
	 * @since   3.0
	*/
	public function __construct() {
		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'dnt_layoutmeta';
		$this->primary_key = 'meta_id';
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
			'post_id' 		=> '%d',
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
		$wpdb->layoutmeta = $this->table_name;
	}

	/**
	 * Retrieve meta field for a layout.
	 *
	 * For internal use only. Use DNT_Layout->get_meta() for public usage.
	 *
	 * @param   int    $post_id   		Layout Post ID.
	 * @param   string $meta_key      The meta key to retrieve.
	 * @param   bool   $single        Whether to return a single value.
	 * @return  mixed                 Will be an array if $single is false. Will be value of meta data field if $single is true.
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function get_meta( $post_id = 0, $meta_key = '', $single = false ) {
		$post_id = $this->sanitize_post_id( $post_id );
		if ( false === $post_id ) {
			return false;
		}

		return get_metadata( 'layout', $post_id, $meta_key, $single );
	}

	/**
	 * Add meta data field to a layout.
	 *
	 * For internal use only. Use DNT_Layout->add_meta() for public usage.
	 *
	 * @param   int    $post_id   		Layout Post ID.
	 * @param   string $meta_key      Metadata key.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   bool   $unique        Optional, default is false. Whether the same key should not be added.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function add_meta( $post_id = 0, $meta_key = '', $meta_value, $unique = false ) {
		$post_id = $this->sanitize_post_id( $post_id );
		if ( false === $post_id ) {
			return false;
		}

		return add_metadata( 'layout', $post_id, $meta_key, $meta_value, $unique );
	}

	/**
	 * Update layout meta field based on Layout Post ID.
	 *
	 * For internal use only. Use DNT_Layout->update_meta() for public usage.
	 *
	 * Use the $prev_value parameter to differentiate between meta fields with the
	 * same key and Layout Post ID.
	 *
	 * If the meta field for the layout does not exist, it will be added.
	 *
	 * @param   int    $post_id   		Layout Post ID.
	 * @param   string $meta_key      Metadata key.
	 * @param   mixed  $meta_value    Metadata value.
	 * @param   mixed  $prev_value    Optional. Previous value to check before removing.
	 * @return  bool                  False on failure, true if success.
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function update_meta( $post_id = 0, $meta_key = '', $meta_value, $prev_value = '' ) {
		$post_id = $this->sanitize_post_id( $post_id );
		if ( false === $post_id ) {
			return false;
		}

		return update_metadata( 'layout', $post_id, $meta_key, $meta_value, $prev_value );
	}

	/**
	 * Remove metadata matching criteria from a layout.
	 *
	 * For internal use only. Use DNT_Layout->delete_meta() for public usage.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching key, if needed.
	 *
	 * @param   int    $post_id   		Layout Post ID.
	 * @param   string $meta_key      Metadata name.
	 * @param   mixed  $meta_value    Optional. Metadata value.
	 * @return  bool                  False for failure. True for success.
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function delete_meta( $post_id = 0, $meta_key = '', $meta_value = '' ) {
		return delete_metadata( 'layout', $post_id, $meta_key, $meta_value );
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
			post_id bigint(20) NOT NULL,
			meta_key varchar(255) DEFAULT NULL,
			meta_value longtext,
			PRIMARY KEY (meta_id),
			KEY post_id (post_id),
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
	private function sanitize_post_id( $post_id ) {
		if ( ! is_numeric( $post_id ) ) {
			return false;
		}

		$post_id = (int) $post_id;

		// We were given a non positive number
		if ( absint( $post_id ) !== $post_id ) {
			return false;
		}

		if ( empty( $post_id ) ) {
			return false;
		}

		return absint( $post_id );
	}

}
