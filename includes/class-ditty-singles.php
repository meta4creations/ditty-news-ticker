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
	 * @since  3.1.19
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
    ditty_edit_post_type_redirects( 'ditty' );
	}
	
	/**
	 * Add custom Ditty pages
	 * @access  public
	 *
	 * @since   3.1
	 */
	public function add_admin_pages() {
		add_submenu_page(
			'edit.php?post_type=ditty',
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			'edit_dittys',
			'ditty',
			array( $this, 'page_display' )
		);
		
		add_submenu_page(
			'edit.php?post_type=ditty',
			esc_html__( 'New Ditty', 'ditty-news-ticker' ),
			esc_html__( 'New Ditty', 'ditty-news-ticker' ),
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
    if ( ditty_editing() ) {
      $classes .= ' ditty-page--ditty';
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
			if ( 'publish' == get_post_status( $display_ajax ) ) {
				$display_settings = get_post_meta( $display_ajax, '_ditty_display_settings', true );
				$display_type = get_post_meta( $display_ajax, '_ditty_display_type', true );
			}
		}
		
		// Make sure the display settings is an array
		if ( ! isset( $display_settings ) || ! is_array( $display_settings ) ) {
			$display_settings = [];
		}
    $ditty_settings = get_post_meta( $id_ajax, '_ditty_settings', true );
    $display_settings['orderby'] = isset( $ditty_settings['orderby'] ) ? $ditty_settings['orderby'] : 'list';
    $display_settings['order'] = isset( $ditty_settings['order'] ) ? $ditty_settings['order'] : 'desc';

		if ( ! isset( $display_type ) || ! ditty_display_type_exists( $display_type ) ) {
			$display_type = 'default';
		}

		// Setup the ditty values
		$status = get_post_status( $id_ajax );
		$args 									= $display_settings;
		$args['id'] 						= $id_ajax;
		$args['uniqid'] 				= $uniqid_ajax;
		$args['title'] 					= get_the_title( $id_ajax );
		$args['status'] 				= $status;
		$args['display'] 				= is_array( $display_ajax ) ? $id_ajax : $display_ajax;
		$args['showEditor'] 		= $editor_ajax;

		$items = $this->get_display_items( $id_ajax, 'cache', $custom_layout_settings_ajax );
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
		
		// Make sure the display settings is an array
		if ( ! is_array( $display_settings ) ) {
			$display_settings = [];
		}
    $ditty_settings = get_post_meta( $ditty_id, '_ditty_settings', true );
    $display_settings['orderby'] = isset( $ditty_settings['orderby'] ) ? $ditty_settings['orderby'] : 'list';
    $display_settings['order'] = isset( $ditty_settings['order'] ) ? $ditty_settings['order'] : 'desc';

		if ( ! $display_type || ! ditty_display_type_exists( $display_type ) ) {
			$display_type = 'default';
		}
	
		// Setup the ditty values
		$status = get_post_status( $ditty_id );
		$args = $display_settings;	
		$args['id'] 				= $ditty_id;
		$args['uniqid'] 		= $uniqid;
		$args['title'] 			= get_the_title( $ditty_id );
		$args['status'] 		= $status;
		$args['display'] 		= is_array( $display_id ) ? $ditty_id : $display_id;

		$items = $this->get_display_items( $ditty_id, 'cache', $custom_layout_settings );
		if ( ! is_array( $items ) ) {
			$items = array();
		}
		$args['items'] = $items;

    if ( $custom_display_settings ) {
      $custom_display_array = json_decode( html_entity_decode( $custom_display_settings ), true );
      if ( json_last_error() == JSON_ERROR_NONE ) {
        if ( isset( $custom_display_array['type'] ) && ditty_display_type_exists( $custom_display_array['type'] ) ) {
          $display_type = $custom_display_array['type'];
        }
        if ( isset( $custom_display_array['settings'] ) ) {
          $args = wp_parse_args( $custom_display_array['settings'], $args );
        }
      } else {
        $args = $this->parse_custom_display_settings( $args, $custom_display_settings );
      }
    }

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
				$updated_items[$ditty_id] = $this->get_display_items( $ditty_id, 'cache', $layout_settings );
			}
		}
		$data = array(
			'updated_items' => $updated_items,
		);	
		wp_send_json( $data );
	}

	/**
	 * Order items based on parent ids
	 *
	 * @since    3.1
	 * @access   private
	 * @var      array   	$display_items    Array of item objects
	 */
	private function group_child_items( $items ) {
		$parent_items = [];
		$child_groups = [];

		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $item ) {
				if ( ! isset( $item->parent_id ) || 0 == $item->parent_id ) {
					$parent_items[] = $item;
				} else {
					if (! isset( $child_groups[$item->parent_id] ) ) {
						$child_groups[$item->parent_id] = [];
					}
					$child_groups[$item->parent_id][] = $item;
				}
			}
		}

		$updated_items = array_reduce( $parent_items, function( $items_list, $item ) use ( $child_groups ) {
			$items_list[] = $item;
			if ( isset( $child_groups[$item->item_id] ) && is_array( $child_groups[$item->item_id] ) && count( $child_groups[$item->item_id] ) > 0 ) {
				foreach ( $child_groups[$item->item_id] as $child_item ) {
					$items_list[] = $child_item;
				}
			}
			return $items_list;
		}, []);

		return $updated_items;
	}

	/**
	 * Return display items for a specific Ditty
	 *
	 * @since    3.1.25
	 * @access   public
	 * @var      array   	$display_items    Array of item objects
	 */
	public function get_display_items( $ditty_id, $load_type = 'cache', $custom_layouts = false ) {
		$load_type = 'force';

    if ( $translation_language = Ditty()->translations->get_translation_language() ) {
      $transient_name = "ditty_display_items_{$ditty_id}_{$translation_language}";
    } else {
      $transient_name = "ditty_display_items_{$ditty_id}";
    }

		// Check for custom layouts
		$custom_layout_array = array();
		if ( $custom_layouts ) {
			$transient_name .= "_{$custom_layouts}";
			$custom_layout_array = ditty_parse_custom_layouts( $custom_layouts );
		}
		
		// Get the display items
		$display_items = get_transient( $transient_name );
		if ( ! $display_items || 'force' == $load_type ) {
			$items_meta = $this->group_child_items( ditty_items_meta( $ditty_id ) );
			$display_items = array();
			if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {
				foreach ( $items_meta as $i => $item_meta ) {

					// If the item is disabled, don't render
					$item_disabled = array_unique( apply_filters( 'ditty_item_disabled', array(), $item_meta->item_id, (array) $item_meta, $items_meta ) );
					if ( $item_disabled && count( $item_disabled ) > 0 ) {
						continue;
					}

					// Unpack the layout variations
					$layout_value = ditty_to_array( $item_meta->layout_value );
					
					// Add custom layouts
					if ( ! empty( $custom_layout_array ) ) {
						if ( isset( $custom_layout_array['all'] ) ) {
							$layout_value = $custom_layout_array['all'];
						} elseif ( isset( $custom_layout_array[$item_meta->item_type] ) ) {
							$layout_value = $custom_layout_array[$item_meta->item_type];
						}
					}
					
					// De-serialize the attribute values
					$attribute_value = ditty_to_array( $item_meta->attribute_value );

					// Get and loop through prepared items
					$prepared_items = ditty_prepare_display_items( $item_meta );
					if ( is_array( $prepared_items ) && count( $prepared_items ) > 0 ) {
						foreach ( $prepared_items as $i => $prepared_meta ) {
							$prepared_meta['attribute_value'] = $attribute_value;
							$prepared_meta['layout_value'] = $layout_value;
							$display_item = new Ditty_Display_Item( $prepared_meta );
							$ditty_data = $display_item->ditty_data();
							$display_items[] = $ditty_data;
						}
					}
				}
			}
			$display_items = apply_filters( 'ditty_display_items', $display_items, $ditty_id );
			set_transient( $transient_name, $display_items, ( MINUTE_IN_SECONDS * intval( get_ditty_settings( 'live_refresh' ) ) ) );
		}
		return $display_items;
	}

	/**
	 * Delete display item transients
	 *
	 * @access  public
	 * @since   3.1
	 * @param   array
	 */
	public function delete_items_cache( $ditty_id = false ) {
		if ( $ditty_id ) {
			$transient_name = "ditty_display_items_{$ditty_id}";
			delete_transient( $transient_name );

      // Delete language transients
      Ditty()->translations->delete_language_transients( $ditty_id );

		} else {
			global $wpdb;
			$all_transients = $wpdb->get_results( "SELECT option_name AS name, option_value AS value FROM $wpdb->options WHERE option_name LIKE '_transient_ditty_display_items_%'" );
			if ( is_array( $all_transients ) && count( $all_transients ) > 0 ) {
				foreach( $all_transients as $i => $transient ) {	
					$name = substr( $transient->name, 11 );
					delete_transient( $name );
				}
			}
		}
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
	 * Sanitize an item's layout value
	 *
	 * @access public
	 * @since  3.1
	 */
	public function sanitize_item_layout_value( $layout_value ) {
		$sanitized_layout_value = [];
		if ( is_array( $layout_value ) && count( $layout_value ) > 0 ) {
			foreach ( $layout_value as $variation => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_layout_value[esc_attr( $variation )] = [
						'html' => wp_kses_post( stripslashes( $value['html'] ) ),
						'css' => wp_kses_post( stripslashes( $value['css'] ) ),
					];
				} else {
					$sanitized_layout_value[esc_attr( $variation )] = esc_attr( $value );
				}
			}
		}
    if ( ! empty( $sanitized_layout_value ) ) {
		  return $sanitized_layout_value;
    }
	}
	
	/**
	 * Sanitize an item's attribute value
	 *
	 * @access public
	 * @since  3.1
	 */
	public function sanitize_item_attribute_value( $attribute_value ) {
		$sanitized_attribute_value = false;
		if ( is_array( $attribute_value ) && count( $attribute_value ) > 0 ) {
			$sanitized_attribute_value = [];
			foreach ( $attribute_value as $tag => $attributes ) {
				$sanitized_attributes = [];
				if ( is_array( $attributes ) && count( $attributes ) > 0 ) {
					foreach ( $attributes as $key => $data ) {
						$sanitized_attribute = [
							'value' => isset( $data['value'] ) ? esc_attr( $data['value'] ) : '',
						];
						if ( isset( $data['customValue'] ) && false !== $data['customValue'] ) {
							$sanitized_attribute['customValue'] = '1';
						}
						$sanitized_attributes[esc_attr($key)] = $sanitized_attribute;
					}
				}
				if ( isset( $tag['disabled'] ) ) {
					$sanitized_attributes['disabled'] = true;
				}
				$sanitized_attribute_value[esc_attr($tag)] = $sanitized_attributes;
			}
		}
		return $sanitized_attribute_value;
	}
	
	/**
	 * Sanitize item values before saving to the database
	 *
	 * @access public
	 * @since  3.0.17
	 */
	public function sanitize_item_data( $item_data ) {
		$sanitized_item = array();
		
		// Sanitize the Ditty ID
		if ( isset( $item_data['ditty_id'] ) ) {
			$sanitized_item['ditty_id'] = intval( $item_data['ditty_id'] );
		}
	
		// Sanitize the item ID
		if ( isset( $item_data['item_id'] ) ) {
			$sanitized_item['item_id'] = esc_attr( $item_data['item_id'] );
		}
	
		// Sanitize the item ID
		if ( isset( $item_data['parent_id'] ) ) {
			$sanitized_item['parent_id'] = esc_attr( $item_data['parent_id'] );
		}
	
		// Sanitize the item index
		if ( isset( $item_data['item_index'] ) ) {
			$sanitized_item['item_index'] = intval( $item_data['item_index'] );
		}
	
		// Sanitize the item type
		if ( isset( $item_data['item_type'] ) ) {
			$sanitized_item['item_type'] = esc_attr( $item_data['item_type'] );
		}
	
		// Sanitize the item value
		if ( isset( $item_data['item_value'] ) && isset( $item_data['item_type'] ) ) {
			if ( $item_type_object = ditty_item_type_object( $item_data['item_type'] ) ) {
				$sanitized_item['item_value'] = $item_type_object->sanitize_settings( $item_data['item_value'] );
			}
		}
	
		// Sanitize the layout value
		if ( isset( $item_data['layout_value'] ) ) {
			$sanitized_item['layout_value'] = $this->sanitize_item_layout_value( $item_data['layout_value'] );
		}
	
		// Sanitize the attribute value
		if ( isset( $item_data['attribute_value'] ) ) {
			$sanitized_item['attribute_value'] = $this->sanitize_item_attribute_value( $item_data['attribute_value'] );
		}
	
		// Sanitize the item author
		if ( isset( $item_data['item_author'] ) ) {
			$sanitized_item['item_author'] = intval( $item_data['item_author'] );
		}
		return $sanitized_item;
	}

	/**
	 * Save a Ditty
	 *
	 * @access  public
	 * @since   3.1.9
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
		$title = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : false;
		$status = isset( $data['status'] ) ? $data['status'] : false;
    $urlParams = isset( $data['urlParams'] ) ? $data['urlParams'] : false;

		$author = false;
		if ( 'ditty-new' != $id ) {
			$ditty_post = get_post( $id );
			$ditty_author = $ditty_post->post_author;
			$author = 0 == $ditty_author ? $userId : false;
		}

		$updates = array(
			'items' => [],
		);
		$errors = array(
			'items' => [],
		);
		
		if ( $is_new_ditty ) {
			$id = wp_insert_post( [
				'post_type' => 'ditty',
				'post_status' => $status ? $status : 'publish',
				'post_title' => $title,
				'post_author' => $userId,
			] );
			$updates['new'] = $id;
      $updates['title'] = $title;
		} elseif ( $title || $status || $author ) {	
			$postarr = array(
				'ID' => $id,
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

		$new_item_swaps = [];

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

				$item_id = $item['item_id'];

				// Set the modified date of the item
				if ( isset( $item['item_value'] ) ) {
					$item['date_modified'] = date( 'Y-m-d H:i:s' );
				} elseif ( isset( $item['attribute_value'] ) ) {
					$item['date_modified'] = date( 'Y-m-d H:i:s' );
				}

				// Pull any item meta updates before saving item
				$item_meta = isset($item['meta']) ? $item['meta'] : false;

				// Sanitize the data
				$sanitized_item  = $this->sanitize_item_data( $item );

				// Possibly update the parent id
				if ( isset($sanitized_item['parent_id']) && false !== strpos( $sanitized_item['parent_id'], 'new-' ) ) {
					if ( isset( $new_item_swaps[$sanitized_item['parent_id']] ) ) {
						$new_parent_id = $new_item_swaps[$sanitized_item['parent_id']];
						$sanitized_item['parent_id'] = $new_parent_id;
						$sanitized_item['new_parent_id'] = strval( $new_parent_id );
					}
				}

				// Serialize the data
				$serialized_item = $sanitized_item;

				if ( isset( $sanitized_item['item_value'] ) ) {
					$serialized_item['item_value'] = json_encode( $sanitized_item['item_value'] );
					
					// Return new item previews
					if ( $item_type_object = ditty_item_type_object( $sanitized_item['item_type'] ) ) {
						$sanitized_item['editor_preview'] = $item_type_object->editor_preview( $sanitized_item['item_value'] );
					}
				}
				if ( isset( $sanitized_item['layout_value'] ) ) {
					$serialized_item['layout_value'] = json_encode( $sanitized_item['layout_value'] );
				}
				if ( isset( $sanitized_item['attribute_value'] ) ) {
					$serialized_item['attribute_value'] = json_encode( $sanitized_item['attribute_value'] );
				}

				$update_item = false;
				$error_item = false;

				if ( false !== strpos( $item['item_id'], 'new-' ) ) {
					if ( $new_item_id = Ditty()->db_items->insert( apply_filters( 'ditty_item_db_data', $serialized_item, $id ), 'item' ) ) {
						$new_item_swaps[$item_id] = $new_item_id;
						$item_id = $new_item_id;
						$sanitized_item['new_id'] = strval( $new_item_id );
						$updates['items'][$item_id] = $sanitized_item;
					} else {
						$errors['items'][$item_id] = $item;
					}
				} elseif ( Ditty()->db_items->update( $sanitized_item['item_id'], apply_filters( 'ditty_item_db_data', $serialized_item, $id ), 'item_id' ) ) {
					$updates['items'][$item_id] = $sanitized_item;
				} else {
					$errors['items'][$item_id] = $item;
				}
				
				// Update item meta
				if ($item_meta && is_array( $item_meta ) && count( $item_meta ) > 0 ) {
					foreach ( $item_meta as $meta_key => $meta_value ) {
						if ( 'meta_updates' == $meta_key ) {
							continue;
						}
						$sanitized_meta_value = ditty_sanitize_settings( $meta_value );
						if ( ditty_item_update_meta( $item_id, $meta_key, $sanitized_meta_value ) ) {
							if ( ! isset( $updates['items'][$item_id] ) ) {
								$updates['items'][$item_id] = $sanitized_item;
							}
							if ( ! isset( $updates['items'][$item_id]['meta'] ) ) {
								$updates['items'][$item_id]['meta'] = [];
							}
							$updates['items'][$item_id]['meta'][$meta_key] = $sanitized_meta_value;
						} else {
							if ( ! isset( $errors['items'][$item_id] ) ) {
								$errors['items'][$item_id] = $item;
							}
							if ( ! isset( $errors['items'][$item_id]['meta'] ) ) {
								$errors['items'][$item_id]['meta'] = [];
							}
							$errors['items'][$item_id]['meta'][$meta_key] = $meta_value;
						}		
					}
				}
			}
		}

		// Check for updates to disabled items
		// if ( isset( $item_id ) && isset( $updates['items'][$item_id] ) ) {
		// 	$updates['items'][$item_id]['is_disabled'] = array_unique( apply_filters( 'ditty_item_disabled', array(), $item_id, $updates['items'][$item_id] ) );
		// }

		// Update the item array to remove keys before sending back to js
		if ( count( $updates['items'] ) > 0 ) {
			$updated_items = [];
			foreach ( $updates['items'] as $i => $updated_item ) {
				if ( $item_type_object = ditty_item_type_object( $updated_item['item_type'] ) ) {
					$updated_items[] = $item_type_object->editor_meta( $updated_item );
				}
			}
			$updates['items'] = $updated_items;
		} else {
			unset( $updates['items'] );
		}

		if ( count( $errors['items'] ) > 0 ) {
			$errors['items'] = array_values( $errors['items'] );
		} else {
			unset( $errors['items'] );
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

    // Maybe save translation items
    if ( isset( $updates['items'] ) ) {
      $updated_items = [];
      if ( is_array( $updates['items'] ) && count( $updates['items'] ) > 0 ) {
        foreach ( $updates['items'] as $item ) {
					if ( ! isset( $item['item_value'] ) ) {
						continue;
					}
          if ( isset( $item['new_id'] ) ) {
            $item['item_id'] = $item['new_id'];
          }
          $item['ditty_id'] = $id;
          $updated_items[] = $item;
        }
      }
      Ditty()->translations->save_item_translations( $updated_items );
    }

    // Maybe delete translation items
    if ( isset( $updates['deletedItems'] ) ) {
      Ditty()->translations->delete_item_translations( $updates['deletedItems'] );
    }

		// Update display
		if ( $display ) {
			if ( isset( $display['id'] ) ) {
				$display = intval( $display['id'] );
			} else {
				$display_type = isset( $display['type'] ) ? esc_attr( $display['type'] ) : '';
				$display = [
					'type' => $display_type,
					'settings' => isset( $display['settings'] ) ? ditty_sanitize_settings( $display['settings'], "display_{$display_type}" ) : [],
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

    // If url params, allow other scripts to access on save
    if ( $urlParams ) {
      $urlParams = do_action( 'ditty_save_url_params', $id, $urlParams );
    }

		$this->delete_items_cache( $id);

		return array(
			'updates' => $updates,
			'errors'	=> $errors,
		);
	}
}