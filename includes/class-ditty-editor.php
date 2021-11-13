<?php

/**
 * Ditty Editor Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Editor
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Editor {

	/**
	 * Get things started
	 * 
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_ditty_editor_load_contents', array( $this, 'editor_load_contents' ) );
		add_action( 'wp_ajax_noprive_ditty_editor_load_contents', array( $this, 'editor_load_contents' ) );
		//add_action( 'wp_ajax_ditty_editor_ajax', array( $this, 'editor_ajax' ) );
		//add_action( 'wp_ajax_noprive_ditty_editor_ajax', array( $this, 'editor_ajax' ) );
		add_action( 'init', array( $this, 'editor_update' ) );
	}
	
	/**
	 * Load the editor contents
	 *
	 * @access public
	 * @since  3.0
	 */
	public function editor_load_contents() {
		check_ajax_referer( 'ditty', 'security' );	
		$ditty_id = isset( $_POST['ditty_id'] ) ? intval( $_POST['ditty_id'] ) : false;	
		$data = array(
			'tabs'		=> apply_filters( 'ditty_editor_tabs', array(), $ditty_id ),
			'panels' 	=> apply_filters( 'ditty_editor_panels', array(), $ditty_id ),
		);
		wp_send_json( $data );
	}	
	
	/**
	 * Load the editor contents
	 *
	 * @access public
	 * @since  3.0
	 */
	// public function editor_ajax() {
	// 	check_ajax_referer( 'ditty', 'security' );
	// 	$hook_ajax = isset( $_POST['hook'] ) ? $_POST['hook'] : false;	
	// 	if ( ! $hook_ajax ) {
	// 		wp_die();
	// 	}
	// 	
	// 	// Set draft values
	// 	if ( isset( $_POST['draft_values'] ) ) {
	// 		ditty_set_draft_values( $_POST['draft_values'] );
	// 		unset( $_POST['draft_values'] );
	// 	}
	// 	unset( $_POST['action'] );
	// 	unset( $_POST['security'] );
	// 	unset( $_POST['hook'] );
	// 	
	// 	$return = apply_filters( $hook_ajax, $_POST );
	// 	$return['hook'] = $hook_ajax;
	// 	$return['draft_values'] = ditty_get_draft_values();
	// 	wp_send_json( $return );
	// }	
	
	/**
	 * Update a ditty from the editor
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_update() {
		
		if ( ! isset( $_POST['_ditty_editor_nonce'] ) ) {
			return false;
		}
		if ( ! wp_verify_nonce( $_POST['_ditty_editor_nonce'], 'ditty-editor' ) ) {
			return false;
		}

		$ditty_id = isset( $_POST['ditty_id'] ) ? $_POST['ditty_id'] : false;	
		do_action( 'ditty_editor_update', $ditty_id );
		
		$data = array(
			'ditty_id' => $ditty_id,
			'response' => __( 'Ditty updated', 'ditty-news-ticker' ),
		);
		wp_send_json( $data );
	}
	
	
	
	
	
	
	
}