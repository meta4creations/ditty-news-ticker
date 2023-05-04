<?php
/**
 * Ditty DB Items class
 *
 * This class is for interacting with the layout meta database table
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty DB Items
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.13
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Ditty_DB_Items extends Ditty_DB {

	/**
	 * Get things started
	 *
	 * @since   3.0
	*/
	public function __construct() {
		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'ditty_items';
		$this->primary_key = 'item_id';
		$this->version     = '1.2';

		add_action( 'plugins_loaded', array( $this, 'register_table' ), 11 );
	}

	/**
	 * Get table columns and data types
	 *
	 * @since   3.0.13
	*/
	public function get_columns() {
		return array(
			'item_id'     		=> '%d',
			'parent_id'     	=> '%d',
			'item_type'  			=> '%s',
			'item_value'  		=> '%s',
			'ditty_id' 				=> '%d',
			'layout_value'		=> '%s',
			'attribute_value'	=> '%s',
			'item_index'  		=> '%d',
			'item_author'  		=> '%d',
			'date_created'  	=> '%s',
			'date_modified' 	=> '%s',
		);
	}
	
	/**
	 * Get default column values
	 *
	 * @since   3.0.13
	*/
	public function get_column_defaults() {
		return array(
			'item_id'     		=> 0,
			'parent_id'     	=> 0,
			'item_type'  			=> '',
			'item_value'  		=> '',
			'ditty_id' 				=> 0,
			'layout_value'		=> '',
			'attribute_value'	=> '',
			'item_index'  		=> 0,
			'item_author'  		=> 0,
			'date_created'  	=> date( 'Y-m-d H:i:s' ),
			'date_modified' 	=> date( 'Y-m-d H:i:s' ),
		);
	}
	
	/**
	 * Checks if a item exists
	 *
	 * @since   3.0
	*/
	public function exists( $value = '', $field = 'item_id' ) {

		$columns = $this->get_columns();
		if ( ! array_key_exists( $field, $columns ) ) {
			return false;
		}

		return (bool) $this->get_column_by( 'item_id', $field, $value );
	}
	
	/**
	 * Retrieve all rows by a specific column / value
	 *
	 * @since   3.0
	 * @return  object
	 */
	public function get_rows_by( $column, $row_id ) {
		global $wpdb;
		$column = esc_sql( $column );
		
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $column = %s ORDER BY item_index DESC;", $row_id ) );
	}
	
	/**
	 * Get items for a post
	 *
	 * @since   3.0
	 * @return  object
	 */
	public function get_items( $ditty_id, $item_type='all', $item_order='ASC' ) {
		global $wpdb;
		
		if( $item_type == 'all' ) {
			$query = $wpdb->prepare("SELECT * FROM $this->table_name WHERE ditty_id = %s ORDER BY item_index {$item_order};", $ditty_id);	
		} else {
			$query = $wpdb->prepare("SELECT * FROM $this->table_name WHERE ditty_id = %s AND item_type = %s ORDER BY item_index {$item_order};", $ditty_id, $item_type);	
		}	
		
		return $wpdb->get_results( $query );
	}
	
	/**
	 * Search items
	 *
	 * @since   3.0
	 * @return  object
	 */
	public function search_items( $search, $item_type='all', $item_order='ASC' ) {
		global $wpdb;	
		$search = '%'.$search.'%';
		
		if( $item_type == 'all' ) {
			$query = $wpdb->prepare( "SELECT * FROM $this->table_name WHERE LOWER(item_value) LIKE LOWER(%s) ORDER BY item_index {$item_order};", $search );
		} else {
			if ( is_array( $item_type ) ) {
				$item_type = array_map( 'esc_attr', $item_type );
				$item_type = implode( "','", $item_type );
			}
			$query = $wpdb->prepare( "SELECT * FROM $this->table_name WHERE LOWER(item_value) LIKE LOWER(%s) AND item_type IN('{$item_type}') ORDER BY item_index {$item_order};", $search );
		}	
		
		return $wpdb->get_results( $query );
	}

	/**
	 * Register the table with $wpdb so the metadata api can find it
	 *
	 * @since   3.0
	*/
	public function register_table() {
		global $wpdb;
		$wpdb->items = $this->table_name;
	}

	/**
	 * Create the table
	 *
	 * @since   3.0.13
	*/
	public function create_table() {

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );	
		
		$sql = "CREATE TABLE {$this->table_name} (
			item_id bigint(20) NOT NULL AUTO_INCREMENT,
			parent_id bigint(20) NOT NULL,
			item_type varchar(255),
			item_value longtext,
			ditty_id bigint(20) NOT NULL,
			layout_value longtext,
			attribute_value longtext,
			item_index bigint(20),
			item_author bigint(20),
			date_created datetime NOT NULL,
			date_modified datetime NOT NULL,
			PRIMARY KEY  (item_id),
			KEY ditty_id (ditty_id)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}
}
