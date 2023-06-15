<?php

/**
 * Ditty Displays Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Displays
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Displays {

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {

		add_filter( 'get_edit_post_link', array( $this, 'modify_edit_post_link' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ), 10, 5 );
		add_action( 'admin_init', array( $this, 'edit_page_redirects' ) );
		
		// General hooks
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
		add_filter( 'post_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );
		
		add_action( 'wp_ajax_ditty_install_display', array( $this, 'install_display' ) );
	}
	
	public function install_default( $display_type, $display_template = false, $display_version = false ) {	
		// Keep function to not cause fatal errors from other plugins
	}
	public function install_display() {
		// Keep function to not cause fatal errors from other plugins
	}

	/**
	 * Add to the admin body class
	 *
	 * @access public
	 * @since  3.0.13
	 */
	public function add_admin_body_class( $classes ) {
		if ( ditty_display_editing() ) {
			$classes .= ' ditty-page';
      $classes .= ' ditty-page--display';
		}
		return $classes;
	}
	
	/**
	 * Add the post ID to the list row actions
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function modify_list_row_actions( $actions, $post ) {
		if ( $post->post_type == 'ditty_display' ) {
			$id_array = array(
				'id' => sprintf( __( 'ID: %d', 'ditty-news-ticker' ), $post->ID ),
			);
			$actions = array_merge( $id_array, $actions );
		}
		return $actions;
	}

	/**
	 * Modify the edit post link
	 *
	 * @access public
	 * @since  3.1
	 */
	public function modify_edit_post_link( $link, $post_id, $text ) {
		if ( 'ditty_display' == get_post_type( $post_id ) ) {
			return add_query_arg( ['page' => 'ditty_display', 'id' => $post_id], admin_url( 'admin.php' ) );
		}
		return $link;
	}
	
	/**
	 * Redirect Ditty edit pages to custom screens
	 * @access  public
	 *
	 * @since   3.1.19
	 */
	public function edit_page_redirects() {
    ditty_edit_post_type_redirects( 'ditty_display' );
	}

	/**
	 * Add custom Ditty pages
	 * @access  public
	 *
	 * @since   3.1.19
	 */
	public function add_admin_pages() {
		add_submenu_page(
			'edit.php?post_type=ditty',
			esc_html__( 'Display', 'ditty-news-ticker' ),
			esc_html__( 'Display', 'ditty-news-ticker' ),
			'edit_ditty_displays',
			'ditty_display',
			array( $this, 'page_display' )
		);
		
		add_submenu_page(
			'edit.php?post_type=ditty',
			esc_html__( 'New Display', 'ditty-news-ticker' ),
			esc_html__( 'New Display', 'ditty-news-ticker' ),
			'edit_ditty_displays',
			'ditty_display-new',
			array( $this, 'page_display' )
		);
	}

	/**
	 * Render the custom new Display page
	 * @access  public
	 *
	 * @since   3.1
	 */
	public function page_display() {	
		?>
		<div id="ditty-display-editor__wrapper" class="ditty-adminPage"></div>
		<?php
	}
	
	/**
	 * Return an array of displays by type
	 *
	 * @access  public
	 * @since   3.0
	 * @param   array    $displays.
	 */
	public function get_displays_data() {	
		$displays = array();
		$display_types = ditty_display_types();
		ksort( $display_types );
		if ( is_array( $display_types ) && count( $display_types ) > 0 ) {
			foreach ( $display_types as $type_slug => $display_type ) {
				if ( ! $display_object = ditty_display_type_object( $type_slug ) ) {
					continue;
				}
				$args = array(
					'posts_per_page' => -1,
					'orderby' 		=> 'post_title',
					'order' 			=> 'ASC',
					'post_type' 	=> 'ditty_display',
					'meta_key'		=> '_ditty_display_type',
					'meta_value' 	=> $type_slug,
				);
				$posts = get_posts( $args );
				if ( is_array( $posts ) && count( $posts ) > 0 ) {
					foreach ( $posts as $i => $post ) {
						$version = get_post_meta( $post->ID, '_ditty_display_version', true );
						$version_string = '';
						if ( $version ) {
							$version_string = " <small class='ditty-display-version'>(v{$version})</small>";
						}
						$displays[] = array(
							'type_id' 			=> $type_slug,
							'type_label' 		=> $display_type['label'],
							'display_id' 		=> $post->ID,
							'display_label' => $post->post_title.$version_string,
						);
					}
				}
			}
		}	
		return $displays;
	}

	/**
	 * Return an array of displays for select fields
	 *
	 * @access  public
	 * @since   3.1.18
	 * @param   array    $options.
	 */
	public function select_field_options( $placeholder = false ) {	
		$options = array();
		if ( $placeholder ) {
			$options[''] = $placeholder;
		}
		
		$query_args = array(
			'posts_per_page' 	=> -1,
			'post_type' 			=> 'ditty_display',
			'post_status'			=> 'any',
			'orderby'					=> 'title',
			'order'						=> 'ASC',
		);
		if ( $displays = get_posts( $query_args ) ) {
			foreach ( $displays as $i => $display ) {
				$title = $display->post_title;
				if ( 'publish' != $display->post_status ) {
					$title .= " ({$display->post_status})";
				}
				$options[$display->ID] = $title;
			}
		}
		return $options;
	}

	/**
	 * Save a display
	 *
	 * @access  public
	 * @since   3.1.9
	 * @param   array
	 */
	public function save( $data ) {	
		$userId = isset( $data['userId'] ) ? $data['userId'] : 0;
		$title = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : false;
		$description = isset( $data['description'] ) ? $data['description']: false;
		$status = isset( $data['status'] ) ? esc_attr( $data['status'] ) : false;
		$editor_settings = isset( $data['editorSettings'] ) ? $data['editorSettings'] : false;

		$display = isset( $data['display'] ) ? $data['display'] : array();
		$display_id = isset( $display['id'] ) ? $display['id'] : 'ditty_display-new';
		$display_type = isset( $display['type'] ) ? $display['type'] : false;
		$display_settings = isset( $display['settings'] ) ? $display['settings'] : false;

		$author = false;
		if ( 'ditty_display-new' != $display_id ) {
			$display_post = get_post( $display_id );
			$display_author = $display_post->post_author;
			$author = 0 == $display_author ? $userId : false;
		}

		$updates = array();
		$errors = array();

		if ( $display_id && 'ditty_display-new' != $display_id ) {
			if ( $title || $status || $author ) {
				$postarr = array(
					'ID' => $display_id,
				);
				if ( $title ) {
					$postarr['post_title'] = $title;
				}
				if ( $status ) {
					$postarr['post_status'] = $status;
				}
				if ( $author ) {
					$postarr['post_author'] = $author;
				}
				if ( wp_update_post( $postarr ) ) {
					if ( $title ) {
						$updates['title'] = $title;
					}
					if ( $status ) {
						$updates['status'] = $status;
					}
					if ( $author ) {
						$updates['author'] = $author;
					}
				} else {
					if ( $title ) {
						$errors['title'] = $title;
					}
					if ( $status ) {
						$errors['status'] = $status;
					}
					if ( $author ) {
						$errors['author'] = $author;
					}
				}
			}
		} else {
			$postarr = array(
				'post_type'		=> 'ditty_display',
				'post_title'	=> $title,
				'post_status'	=> $status ? $status : 'publish',
				'post_author' => $userId,
			);
			$display_id = wp_insert_post( $postarr );
			$updates['new'] = $display_id;
			$updates['title'] = $title;
		}

		// Update a display description
		if ( $description ) {
			$sanitized_description = wp_kses_post( $description );
			if ( update_post_meta( $display_id, '_ditty_display_description', $sanitized_description ) ) {
				$updates['description'] = $sanitized_description;
			} else {
				$errors['description'] = $sanitized_description;
			}
		}
		
		// Update a display type
		if ( $display_type ) {
			$sanitized_display_type = esc_attr( $display_type );
			if ( update_post_meta( $display_id, '_ditty_display_type', $sanitized_display_type ) ) {
				$updates['type'] = $sanitized_display_type;
			} else {
				$errors['type'] = $sanitized_display_type;
			}
		}

		// Update a display settings
		if ( $display_settings ) {
			if ( isset( $display_settings['items'] ) ) {
				unset( $display_settings['items'] );
			}
			$sanitized_display_settings = ditty_sanitize_settings( $display_settings, "display_{$display_type}" );
			if ( update_post_meta( $display_id, '_ditty_display_settings', $sanitized_display_settings ) ) {
				$updates['settings'] = $sanitized_display_settings;
			} else {
				$errors['settings'] = $sanitized_display_settings;
			}
		}

		// Update the editor settings
		if ( $editor_settings ) {
			$sanitized_editor_settings = ditty_sanitize_settings( $editor_settings );
			if ( update_post_meta( $display_id, '_ditty_editor_settings', $sanitized_editor_settings ) ) {
				$updates['editorSettings'] = $sanitized_editor_settings;
			} else {
				$errors['editorSettings'] = $sanitized_editor_settings;
			}
		}

		return array(
			'updates' => $updates,
			'errors'	=> $errors,
		);
	}
}