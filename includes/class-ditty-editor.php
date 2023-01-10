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
				$display_type = get_post_meta( $post->ID, '_ditty_display_type', true );
				$display_settings = get_post_meta( $post->ID, '_ditty_display_settings', true );
				if ( ! is_array( $display_settings ) ) {
					$display_settings = array();
				}
				$display_data[] = array(
					'id' => $post->ID,
					'type' => $display_type,
					'title' => $post->post_title,
					'description' => get_post_meta( $post->ID, '_ditty_display_description', true ),
					'settings' => $display_settings,
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

	/**
	 * Get all item type data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	public function item_type_data() {	
		$item_types = ditty_item_types();
		$item_type_data = array();
		if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
			foreach ( $item_types as $i => $type ) {
				if ( $type['type'] == 'default' || $type['type'] == 'wp_editor' ) {
					continue;
				}
				$item_type_object = ditty_item_type_object( $type['type'] );
				$default_settings = $item_type_object->default_settings();
				$type['settings'] = $this->format_js_fields( $item_type_object->fields( $default_settings ) );
				$item_type_data[] = $type;
			}
		}
		return array_values( $item_type_data );
	}

	/**
	 * Get all display type data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	public function display_type_data() {	
		$display_types = ditty_display_types();
		$display_type_data = array();
		if ( is_array( $display_types ) && count( $display_types ) > 0 ) {
			foreach ( $display_types as $i => $type ) {
				if ( $type['type'] == 'ticker' || $type['type'] == 'list' ) {
					continue;
				}
				$display_type_object = ditty_display_type_object( $type['type'] );
				$default_settings = $display_type_object->default_settings();
				$type['settings'] = $this->format_js_fields( $display_type_object->fields( $default_settings ) );
				$display_type_data[] = $type;
				//echo '<pre>';print_r($type);echo '</pre>';
			}
		}
		return array_values( $display_type_data );
	}

	// Convert fields for js
	private function convert_js_field_keys( &$field ) {
		if ( isset( $field['multiple_fields'] ) ) {
			$field['multipleFields'] = $field['multiple_fields'];
			unset( $field['multiple_fields'] );
		}
		if ( isset( $field['default_state'] ) ) {
			$field['defaultState'] = $field['default_state'];
			unset( $field['default_state'] );
		}
		if ( isset( $field['clone_button'] ) ) {
			$field['cloneButton'] = $field['clone_button'];
			unset( $field['clone_button'] );
		}
		if ( isset( $field['js_options'] ) ) {
			if ( is_array( $field['js_options'] ) && count( $field['js_options'] ) > 0 ) {
				foreach ( $field['js_options'] as $key => $value ) {
					$field[$key] = $value;
				}
			}
			unset( $field['js_options'] );
		}
	}
	private function format_js_field( $field ) {
		$this->convert_js_field_keys( $field );
		if ( isset( $field['fields'] ) && is_array( $field['fields'] ) && count( $field['fields'] ) > 0 ) {
			$field['fields'] = array_values( $field['fields'] );
			
			foreach ( $field['fields'] as $i => &$f ) {
				$this->convert_js_field_keys( $f );
				if ( isset( $f['type'] ) && 'group' == $f['type'] ) {
					$f = $this->format_js_field( $f );
				}
			}
		}
		return $field;
	}
	private function format_js_fields( $fields ) {
		if ( is_array( $fields ) && count( $fields ) > 0 ) {
			foreach ( $fields as $i => &$field ) {
				$field = $this->format_js_field( $field );
			}
		}
		return array_values( $fields );
	}
	
}