<?php

/**
 * Ditty Singles Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Singles
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

class Ditty_Singles {

	/**
	 * Get things started
	 * @access  public
	 * @since   3.1
	 */
	public function __construct() {
	
		add_filter( 'get_edit_post_link', array( $this, 'modify_edit_post_link' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ), 10, 5 );
		add_action( 'admin_init', array( $this, 'edit_page_redirects' ) );

		// General hooks
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
		add_filter( 'post_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );
		add_action( 'mtphr_post_duplicator_created', array( $this, 'after_duplicate_post' ), 10, 3 );	

		// Shortcodes
		add_shortcode( 'ditty', array( $this, 'do_shortcode' ) );
		
		// Ajax
		add_action( 'wp_ajax_ditty_init', array( $this, 'init_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_init', array( $this, 'init_ajax' ) );
		add_action( 'wp_ajax_ditty_live_updates', array( $this, 'live_updates_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_live_updates', array( $this, 'live_updates_ajax' ) );
	}
	
	/**
	 * Modify the edit post link
	 *
	 * @access public
	 * @since  3.1
	 */
	public function modify_edit_post_link( $link, $post_id, $text ) {
		if ( 'ditty' == get_post_type( $post_id ) ) {
			return add_query_arg( ['page' => 'ditty', 'id' => $post_id], admin_url( 'admin.php' ) );
		}
		return $link;
	}
	
	/**
	 * Redirect Ditty edit pages to custom screens
	 * @access  public
	 *
	 * @since   3.1
	 */
	public function edit_page_redirects() {
		global $pagenow;
		if ( $pagenow === 'post.php' ) {
			$post_id = isset( $_GET['post'] ) ? $_GET['post'] : 0;
			if ( 'ditty' == get_post_type( $post_id ) ) {
				wp_safe_redirect( add_query_arg( ['page' => 'ditty', 'id' => $post_id], admin_url( 'admin.php' ) ) );
				exit;
			}
		}
		if ( $pagenow === 'post-new.php' ) {
			$post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : false;
			if ( 'ditty' == $post_type ) {
				wp_safe_redirect( add_query_arg( ['page' => 'ditty-new' ], admin_url( 'admin.php' ) ) );
				exit;
			}
		}
	}
	
	/**
	 * Add custom Ditty pages
	 * @access  public
	 *
	 * @since   3.1
	 */
	public function add_admin_pages() {
		add_submenu_page(
			null,
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			'edit_dittys',
			'ditty',
			array( $this, 'page_display' ),
		);
		
		add_submenu_page(
			null,
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			'edit_dittys',
			'ditty-new',
			array( $this, 'page_display' )
		);
	}
	
	/**
	 * Render the custom Ditty page
	 * @access  public
	 *
	 * @since   3.1
	 */
	public function page_display() {
		?>
		<div id="ditty-editor__wrapper" class="ditty-adminPage"></div>
		<?php
	}

	/**
	 * Duplicate Ditty items on Post Duplicator duplication
	 * 
	 * @since  3.0.13
	 * @return void
	 */
	public function after_duplicate_post( $original_id, $duplicate_id, $settings ) {
		if ( 'ditty' == get_post_type( $original_id ) && 'ditty' == get_post_type( $duplicate_id ) ) {
			
			// Duplicate and add original Ditty items
			$all_meta = Ditty()->db_items->get_items( $original_id );
			if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
				foreach ( $all_meta as $i => $meta ) {
					unset( $meta->item_id );
					unset( $meta->date_created );
					unset( $meta->date_modified );
					$meta->ditty_id = $duplicate_id;
					Ditty()->db_items->insert( $meta, 'item' );
				} 
			}
		}
	}

	/**
	 * Add to the admin body class
	 *
	 * @access public
	 * @since  3.0.13
	 */
	public function add_admin_body_class( $classes ) {
		$page = isset( $_GET['page'] ) ? $_GET['page'] : false;
		$pages = ['ditty', 'ditty-new', 'ditty_settings' ];
		if ( in_array( $page, $pages ) ) {
			$classes .= ' ditty-page';
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
		if ( $post->post_type == 'ditty' ) {
			//$id_string = sprintf( __( 'ID: %d', 'ditty-news-ticker' ), $post->ID );
			$id_array = array(
				'id' => sprintf( __( 'ID: %d', 'ditty-news-ticker' ), $post->ID ),
			);
			$actions = array_merge( $id_array, $actions );
		}
		return $actions;
	}
	
	/**
	 * Display the Ditty via shortcode
	 *
	 * @since    3.0
	 * @access   public
	 * @var      html
	 */
	public function do_shortcode( $atts ) {
		if ( ! is_admin() ) {
			return ditty_render( $atts );
		}
	}
	
	/**
	 * Setup and parse the render atts
	 *
	 * @since    3.2
	 * @access   public
	 * @var      html
	 */
	// public function parse_render_atts( $atts ) {
	// 	$defaults = array(
	// 		'id' 								=> '',
	// 		'display' 					=> '',
	// 		'display_settings' 	=> '',
	// 		'layout_settings' 	=> '',
	// 		'uniqid' 						=> '',
	// 		'class' 						=> '',
	// 		'el_id'							=> '',
	// 		'show_editor' 			=> 0,
	// 	);
	// 	$args = shortcode_atts( $defaults, $atts );
		
	// 	// Check for WPML language posts
	// 	$args['id'] = function_exists('icl_object_id') ? icl_object_id( $args['id'], 'ditty', true ) : $args['id'];
	
	// 	// Make sure the ditty exists & is published
	// 	if ( ! ditty_exists( intval( $args['id'] ) ) ) {
	// 		return false;
	// 	}
	// 	if ( ! is_admin() && 'publish' !== get_post_status( intval( $args['id'] ) ) ) {
	// 		return false;
	// 	}
	
	// 	if ( '' == $args['uniqid'] ) {
	// 		$args['uniqid'] = uniqid( 'ditty-' );
	// 	}
	
	// 	$class = 'ditty ditty--pre';
	// 	if ( '' != $args['class'] ) {
	// 		$class .= ' ' . esc_attr( $args['class'] );
	// 	}
		
	// 	$ditty_settings 		= get_post_meta( $args['id'], '_ditty_settings', true );
	// 	$ajax_load 					= ( isset( $ditty_settings['ajax_loading'] ) && 'yes' == $ditty_settings['ajax_loading'] ) ? '1' : 0;
	// 	$live_updates 			= ( isset( $ditty_settings['live_updates'] ) && 'yes' == $ditty_settings['live_updates'] ) ? '1' : 0;
	// 	$items 							= ditty_display_items( $args['id'], 'force' );
	// 	$display_id 				= ( $args['display'] != '' ) ? $args['display'] : get_post_meta( $args['id'], '_ditty_display', true );
	// 	$display_type 			= get_post_meta( $display_id, '_ditty_display_type', true );
	// 	$display_settings 	= get_post_meta( $display_id, '_ditty_display_settings', true );
	// 	$title_settings			= Ditty()->displays->title_settings( $display_settings );
	
	// 	$html_atts = array(
	// 		'id'										=> ( '' != $args['el_id'] ) ? sanitize_title( $args['el_id'] ) : false,
	// 		'class' 								=> $class,
	// 		'data-id' 							=> $args['id'],
	// 		'data-uniqid' 					=> $args['uniqid'],
	// 		'data-display' 					=> $display_id,
	// 		'data-type'							=> $display_type,
	// 		'data-settings' 				=> htmlspecialchars( json_encode( $display_settings ) ),
	// 		//'data-items' 						=> htmlspecialchars( json_encode( $items ) ),
	// 		'data-title'						=> $title_settings['titleDisplay'],
	// 		'data-title_position' 	=> $title_settings['titleElementPosition'],
	// 		//'data-display_settings' => ( '' != $args['display_settings'] ) ? $args['display_settings'] : false,
	// 		//'data-layout_settings' 	=> ( '' != $args['layout_settings'] ) ? $args['layout_settings'] : false,
	// 		//'data-show_editor' 			=> ( 0 != intval( $args['show_editor'] ) ) ? '1' : false,
	// 		'data-ajax_load' 				=> $ajax_load,
	// 		'data-live_updates' 		=> $live_updates,
	// 	);
	
	// 	// Add scripts
	// 	ditty_add_scripts( $args['id'], $args['display']);
	
	// 	return array(
	// 		'ditty' => $args['id'],
	// 		'ditty_settings' => $ditty_settings,
	// 		'display' => $display_id,
	// 		'display_type' => $display_type,
	// 		'display_settings' => $display_settings,
	// 		'title_settings' => $title_settings,
	// 		'items' => $items,
	// 		'html_atts' => $html_atts,
	// 	);
	// }
	
	/**
	 * Return an array of Dittys for select fields
	 *
	 * @access  public
	 * @since   3.0
	 * @param   array    $options.
	 */
	public function select_field_options() {	
		$options = array();
		$args = array(
			'posts_per_page' => -1,
			'orderby' 		=> 'post_title',
			'order' 			=> 'ASC',
			'post_type' 	=> 'ditty',
		);
		$posts = get_posts( $args );
		if ( is_array( $posts ) && count( $posts ) > 0 ) {
			foreach ( $posts as $i => $post ) {
				$options[$post->ID] = $post->post_title;
			}
		}	
		return $options;
	}
	
	/**
	 * Parse custom display settings
	 *
	 * @access public
	 * @since  3.0
	 */
	public function parse_custom_display_settings( $args, $display_settings ) {
		if ( '' != $display_settings && 'false' != $display_settings ) {
			parse_str( html_entity_decode( $display_settings ), $custom_display_settings );
			if ( is_array( $custom_display_settings ) && count( $custom_display_settings ) > 0 ) {
				foreach ( $custom_display_settings as $key => $value ) {
					$parts = explode( '|', $value );
					if ( is_array( $parts ) && count( $parts ) > 0 ) {
						foreach ( $parts as $subvalue ) {
							$subparts = explode( ':', $subvalue );
							if ( count( $subparts ) > 1 ) {
								if ( ! isset( $args[$key] ) ) {
									$args[$key] = array();
								}
								if ( is_array( $args[$key] ) ) {
									$args[$key][$subparts[0]] = $subparts[1];
								}
							} else {
								$args[$key] = $subparts[0];
							}
						}
					}
				}
			}
		}
		return $args;
	}
	
	/**
	 * Return data for a Ditty to load via ajax
	 *
	 * @access public
	 * @since  3.1
	 */
	public function init_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$id_ajax 												= isset( $_POST['id'] ) 							? intval( $_POST['id'] ) 									: false;
		$uniqid_ajax 										= isset( $_POST['uniqid'] ) 					? esc_attr( $_POST['uniqid'] ) 						: false;
		$display_ajax 									= isset( $_POST['display'] ) 					? esc_attr( $_POST['display'] ) 					: false;
		$custom_display_settings_ajax 	= isset( $_POST['display_settings'] ) ? esc_attr( $_POST['display_settings'] ) 	: false;
		$custom_layout_settings_ajax 		= isset( $_POST['layout_settings'] ) 	? esc_attr( $_POST['layout_settings'] ) 	: false;
		$editor_ajax 										= isset( $_POST['editor'] )						? intval( $_POST['editor'] ) 							: false;

		// Get the display attributes
		if ( ! $display_ajax ) {
			$display_ajax = get_post_meta( $id_ajax, '_ditty_display', true );
		}

		if ( is_array( $display_ajax ) ) {
			$display_settings = isset( $display_ajax['settings'] ) ? $display_ajax['settings'] : [];
			$display_type = isset( $display_ajax['type'] ) ? $display_ajax['type'] : false;
		} else {
			if ( 'publish' == get_post_status( $display_id ) ) {
				$display_settings = get_post_meta( $display_ajax, '_ditty_display_settings', true );
				$display_type = get_post_meta( $display_ajax, '_ditty_display_type', true );
			}
		}

		if ( ! $display_type || ! ditty_display_type_exists( $display_type ) ) {
			// DO SOMETHING HERE
		}

		// Setup the ditty values
		$status = get_post_status( $id_ajax );
		$args 									= $display_settings;
		$args['id'] 						= $id_ajax;
		$args['uniqid'] 				= $uniqid_ajax;
		$args['title'] 					= get_the_title( $id_ajax );
		$args['status'] 				= $status;
		$args['display'] 				= $display_ajax;
		$args['showEditor'] 		= $editor_ajax;

		$items = ditty_display_items( $id_ajax, 'force', $custom_layout_settings_ajax );
		if ( ! is_array( $items ) ) {
			$items = array();
		}
		$args['items'] = $items;
		$args = $this->parse_custom_display_settings( $args, $custom_display_settings_ajax );

		do_action( 'ditty_init', $id_ajax );
		
		$data = array(
			'display_type' 	=> $display_type,
			'args' 					=> $args,
		);
		wp_send_json( $data );
	}
	
	/**
	 * Return data for a Ditty to load via ajax
	 *
	 * @access public
	 * @since  3.1
	 */
	public function init( $atts ) {
		if ( ! $atts['data-id'] ) {
			return false;
		}

		$ditty_id 								= $atts['data-id'];
		$uniqid 									= isset( $atts['data-uniqid'] ) 					? $atts['data-uniqid'] 						: false;
		$display_id 							= isset( $atts['data-display'] ) 					? $atts['data-display'] 					: false;
		$custom_display_settings 	= isset( $atts['data-display_settings'] )	? $atts['data-display_settings']	: false;
		$custom_layout_settings 	= isset( $atts['data-layout_settings'] ) 	? $atts['data-layout_settings'] 	: false;

		// If the Ditty is not published, exit
		if ( 'publish' != get_post_status( $ditty_id ) ) {
			return false;
		}
	
		// Get the display attributes
		if ( ! $display_id ) {
			$display_id = get_post_meta( $ditty_id, '_ditty_display', true );
		}
		
		if ( is_array( $display_id ) ) {
			$display_settings = isset( $display_id['settings'] ) ? $display_id['settings'] : [];
			$display_type = isset( $display_id['type'] ) ? $display_id['type'] : false;
		} else {
			if ( 'publish' == get_post_status( $display_id ) ) {
				$display_settings = get_post_meta( $display_id, '_ditty_display_settings', true );
				$display_type = get_post_meta( $display_id, '_ditty_display_type', true );
			}
		}

		if ( ! $display_type || ! ditty_display_type_exists( $display_type ) ) {
			// DO SOMETHING HERE
		}
	
		// Setup the ditty values
		$status = get_post_status( $ditty_id );
		$args = $display_settings;	
		$args['id'] 				= $ditty_id;
		$args['uniqid'] 		= $uniqid;
		$args['title'] 			= get_the_title( $ditty_id );
		$args['status'] 		= $status;
		$args['display'] 		= is_array( $display_id ) ? $ditty_id : $display_id;

		$items = ditty_display_items( $ditty_id, 'force', $custom_layout_settings );
		if ( ! is_array( $items ) ) {
			$items = array();
		}
		$args['items'] = $items;

		$args = $this->parse_custom_display_settings( $args, $custom_display_settings );
	
		do_action( 'ditty_init', $ditty_id );
		
		?>
		$( 'div[data-uniqid="<?php echo esc_attr( $uniqid ); ?>"]' ).ditty_<?php echo esc_attr( $display_type ); ?>(<?php echo json_encode( $args ); ?>);
		<?php
	}
	
	/**
	 * Return live updates
	 *
	 * @access public
	 * @since  3.0.11
	 */
	public function live_updates_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$live_ids = isset( $_POST['live_ids'] ) ? $_POST['live_ids'] 	: false;
		if ( ! $live_ids ) {
			wp_die();
		}
		$updated_items = array();
		if ( is_array( $live_ids ) && count( $live_ids ) > 0 ) {
			foreach ( $live_ids as $ditty_id => $data ) {
				$layout_settings = isset( $data['layout_settings'] ) ? $data['layout_settings'] : false;
				$updated_items[$ditty_id] = ditty_display_items( $ditty_id, 'cache', $layout_settings );
			}
		}
		$data = array(
			'updated_items' => $updated_items,
		);	
		wp_send_json( $data );
	}
	
	/**
	 * Sanitize setting values before saving to the database
	 *
	 * @access public
	 * @since  3.1
	 */
	public function sanitize_settings( $settings ) {	
		$sanitized_settings = array();
		if ( is_array( $settings ) && count( $settings ) > 0 ) {
			foreach ( $settings as $setting => $value ) {
				switch( $setting ) {
					case 'previewPadding':
						$sanitized_padding = array();
						if ( is_array( $value ) && count( $value ) > 0 ) {
							foreach ( $value as $key => $val ) {
								$sanitized_padding[$key] = sanitize_text_field( $val );
							}
						}
						$sanitized_settings[$setting] = $sanitized_padding;
						break;
					default:
						$sanitized_settings[$setting] = sanitize_text_field( $value );
						break;
				}
			}
		}
		return $sanitized_settings;
	}
	
	/**
	 * Sanitize item values before saving to the database
	 *
	 * @access public
	 * @since  3.0.17
	 */
	public function sanitize_item_data( $item_data ) {
		$item_type 				= isset( $item_data['item_type'] ) ? $item_data['item_type'] : false;
		$item_value 			= isset( $item_data['item_value'] ) ? $item_data['item_value'] : false;
		$layout_value 		= isset( $item_data['layout_value'] ) ? $item_data['layout_value'] : false;
		$attribute_value 	= isset( $item_data['attribute_value'] ) ? $item_data['attribute_value'] : false;
		
		// Sanitize values by item type
		$sanitized_item_value = false;
		if ( $item_type && $item_value ) {
			if ( $item_type_object = ditty_item_type_object( $item_type ) ) {
				$sanitized_item_value = $item_type_object->sanitize_settings( $item_value );
			}
		}
		
		// Sanitize the layout values
		$sanitized_layout_value = false;
		if ( is_array( $layout_value ) && count( $layout_value ) > 0 ) {
			foreach ( $layout_value as $variation => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_layout_value[esc_attr( $variation )] = json_encode( [
						'html' => wp_kses_post( $value['html'] ),
						'css' => wp_kses_post( $value['css'] ),
					] );
				} else {
					$sanitized_layout_value[esc_attr( $variation )] = esc_attr( $value );
				}
			}
		}

		// Sanitize attribute value
		$sanitized_attribute_value = false;
		if ( $item_type && $attribute_value ) {
			$sanitized_attribute_value = $attribute_value;
			// if ( $item_type_object = ditty_item_type_object( $item_type ) ) {
			// 	$sanitized_item_value = $item_type_object->sanitize_settings( $item_value );
			// }
		}
		
		$sanitized_item = array();
		if ( isset( $item_data['ditty_id'] ) ) {
			$sanitized_item['ditty_id'] = intval( $item_data['ditty_id'] );
		}
		if ( isset( $item_data['item_id'] ) ) {
			$sanitized_item['item_id'] = esc_attr( $item_data['item_id'] );
		}
		if ( isset( $item_data['item_index'] ) ) {
			$sanitized_item['item_index'] = intval( $item_data['item_index'] );
		}
		if ( isset( $item_data['item_type'] ) ) {
			$sanitized_item['item_type'] = esc_attr( $item_data['item_type'] );
		}
		if ( isset( $item_data['item_value'] ) ) {
			$sanitized_item['item_value'] = $sanitized_item_value;
		}
		if ( isset( $item_data['layout_id'] ) ) {
			$sanitized_item['layout_id'] = esc_attr( $item_data['layout_id'] );
		}
		if ( isset( $item_data['layout_value'] ) ) {
			$sanitized_item['layout_value'] = $sanitized_layout_value;
		}
		if ( isset( $item_data['attribute_value'] ) ) {
			$sanitized_item['attribute_value'] = $sanitized_attribute_value;
		}
		if ( isset( $item_data['item_author'] ) ) {
			$sanitized_item['item_author'] = intval( $item_data['item_author'] );
		}
		return $sanitized_item;
	}

	/**
	 * Save a Ditty
	 *
	 * @access  public
	 * @since   3.1
	 * @param   array
	 */
	public function save( $data ) {	
		$userId = isset( $data['userId'] ) ? $data['userId'] : 0;
		$id = isset( $data['id'] ) ? $data['id'] : 0;
		$is_new_ditty = ( 'ditty-new' === $id );
		$items = isset( $data['items'] ) ? $data['items'] : array();
		$deletedItems = isset( $data['deletedItems'] ) ? $data['deletedItems'] : array();
		$display = isset( $data['display'] ) ? $data['display'] : false;
		$settings = isset( $data['settings'] ) ? $data['settings'] : false;
		$title = isset( $data['title'] ) ? $data['title'] : false;
		$status = isset( $data['status'] ) ? $data['status'] : false;

		$updates = array();
		$errors = array();
		
		if ( $is_new_ditty ) {
			$id = wp_insert_post( [
				'post_type' => 'ditty',
				'post_status' => $status ? $status : 'draft',
				'post_title' => $title,
			] );
			$updates['new'] = $id;
		} elseif ( $title || $status ) {	
			$postarr = array(
				'ID' => $id,
			);
			if ( $title ) {
				$postarr['post_title'] = $title;
			}
			if ( $status ) {
				$postarr['post_status'] = $status;
			}
			if ( wp_update_post( $postarr ) ) {
				if ( $title ) {
					$updates['title'] = $title;
				}
				if ( $status ) {
					$updates['status'] = $status;
				}
			} else {
				if ( $title ) {
					$errors['title'] = $title;
				}
				if ( $status ) {
					$errors['status'] = $status;
				}
			}
		}

		// Update items
		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $i => $item ) {
				if ( is_object( $item ) ) {
					$item = ( array ) $item;
				}

				// Set the Ditty ID of new Ditty
				if ( $is_new_ditty ) {
					$item['ditty_id'] = $id;
				}

				//Set the modified date of the item
				if ( isset( $item['item_value'] ) ) {
					$item['date_modified'] = date( 'Y-m-d H:i:s' );
				} elseif ( isset( $item['attribute_value'] ) ) {
					$item['date_modified'] = date( 'Y-m-d H:i:s' );
				}

				// Sanitize & serialize data
				$sanitized_item = $serialized_item = $this->sanitize_item_data( $item );
				if ( isset( $sanitized_item['item_value'] ) ) {
					$serialized_item['item_value'] = maybe_serialize( $sanitized_item['item_value'] );
					
					// Return new item previews
					if ( $item_type_object = ditty_item_type_object( $sanitized_item['item_type'] ) ) {
						$sanitized_item['editor_preview'] = $item_type_object->editor_preview( $sanitized_item['item_value'] );
					}
				}
				if ( isset( $sanitized_item['layout_value'] ) ) {
					$serialized_item['layout_value'] = maybe_serialize( $sanitized_item['layout_value'] );
				}
				if ( isset( $sanitized_item['attribute_value'] ) ) {
					$serialized_item['attribute_value'] = maybe_serialize( $sanitized_item['attribute_value'] );
				}

				if ( false !== strpos( $item['item_id'], 'new-' ) ) {
					if ( $new_item_id = Ditty()->db_items->insert( apply_filters( 'ditty_item_db_data', $serialized_item, $id ), 'item' ) ) {
						if ( ! isset( $updates['items'] ) ) {
							$updates['items'] = [];
						}
						$item['new_id'] = strval( $new_item_id );
						$updates['items'][] = $sanitized_item;
					} else {
						if ( ! isset( $errors['items'] ) ) {
							$errors['items'] = [];
						}
						$errors['items'][] = $item;
					}
				} elseif ( Ditty()->db_items->update( $sanitized_item['item_id'], apply_filters( 'ditty_item_db_data', $serialized_item, $id ), 'item_id' ) ) {
					if ( ! isset( $updates['items'] ) ) {
						$updates['items'] = [];
					}
					$updates['items'][] = $sanitized_item;
				} else {
					if ( ! isset( $errors['items'] ) ) {
						$errors['items'] = [];
					}
					$errors['items'][] = $item;
				}
			}
		}

		// Delete items
		if ( is_array( $deletedItems ) && count( $deletedItems ) > 0 ) {
			foreach ( $deletedItems as $i => $deletedItem ) {
				ditty_item_delete_all_meta( $deletedItem['item_id'] );
				if ( Ditty()->db_items->delete( $deletedItem['item_id'] ) ) {
					if ( ! isset( $updates['deletedItems'] ) ) {
						$updates['deletedItems'] = [];
					}
					$updates['deletedItems'][] = $deletedItem;
				} else {
					if ( ! isset( $errors['deletedItems'] ) ) {
						$errors['deletedItems'] = [];
					}
					$errors['deletedItems'][] = $deletedItem;
				}
			}
		}

		// Update display
		if ( $display ) {
			if ( isset( $display['id'] ) ) {
				$display = intval( $display['id'] );
			} else {
				$display_type = isset( $display['type'] ) ? esc_attr( $display['type'] ) : '';
				$display = [
					'type' => $display_type,
					'settings' => isset( $display['settings'] ) ? ditty_sanitize_settings( $display['settings'], "display_${$display_type}" ) : [],
				];
			}
			if ( update_post_meta( $id, '_ditty_display', $display ) ) {
				$updates['display'] = $display;
			} else {
				$errors['display'] = $display;
			}
		}

		// Sanitize & update settings
		if ( $settings ) {	
			$sanitized_settings = $this->sanitize_settings( $settings );
			if ( update_post_meta( $id, '_ditty_settings', $sanitized_settings ) ) {
				$updates['settings'] = $sanitized_settings;
			} else {
				$errors['settings'] = $sanitized_settings;
			}
		}

		return array(
			'updates' => $updates,
			'errors'	=> $errors,
		);
	}
}