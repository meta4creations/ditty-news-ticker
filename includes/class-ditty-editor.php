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
		add_action( 'init', array( $this, 'editor_update' ) );

		// add_action( 'wp_head', array( $this, 'preview_styles' ) );
		// add_action( 'wp', array( $this, 'render_preview' ) );
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
			'tabs'				=> apply_filters( 'ditty_editor_tabs', array(), $ditty_id ),
			'panels' 			=> apply_filters( 'ditty_editor_panels', array(), $ditty_id ),
			'draft_data' 	=> ditty_get_draft_values(),
		);
		wp_send_json( $data );
	}	

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

	/**
	 * Get all display data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	public function display_data() {	
		$args = array(
			'posts_per_page' => -1,
			'orderby' 		=> 'post_title',
			'order' 			=> 'ASC',
			'post_type' 	=> 'ditty_display',
		);
		$posts = get_posts( $args );

		$display_data = array();
		if ( is_array( $posts ) && count( $posts ) > 0 ) {
			foreach ( $posts as $i => $post ) {
				$display_data[] = array(
					'id' => $post->ID,
					'type' => get_post_meta( $post->ID, '_ditty_display_type', true ),
					'title' => $post->post_title,
					'description' => get_post_meta( $post->ID, '_ditty_display_description', true ),
					'settings' => get_post_meta( $post->ID, '_ditty_display_settings', true ),
					'version' => get_post_meta( $post->ID, '_ditty_display_version', true ),
					'edit_url' => get_edit_post_link( $post->ID, 'code' ),
				);		
			}
		}
		return $display_data;
	}

	/**
	 * Get all layout data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	public function layout_data() {
		$args = array(
			'posts_per_page' => -1,
			'orderby' 		=> 'post_title',
			'order' 			=> 'ASC',
			'post_type' 	=> 'ditty_layout',
		);
		$posts = get_posts( $args );

		$layout_data = array();
		if ( is_array( $posts ) && count( $posts ) > 0 ) {
			foreach ( $posts as $i => $post ) {
				$layout_data[] = array(
					'id' => $post->ID,
					'title' => $post->post_title,
					'description' => get_post_meta( $post->ID, '_ditty_layout_description', true ),
					'html' => get_post_meta( $post->ID, '_ditty_layout_html', true ),
					'css' => get_post_meta( $post->ID, '_ditty_layout_css', true ),
					'version' => get_post_meta( $post->ID, '_ditty_layout_version', true ),
					'edit_url' => get_edit_post_link( $post->ID, 'code' ),
				);		
			}
		}
		return $layout_data;
	}
	
}