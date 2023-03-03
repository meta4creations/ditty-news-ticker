<?php
/**
 * Ditty API Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty_API
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1
*/
class Ditty_API {

	private $version;
  
  /**
	 * Get things started
	 * @access  public
	 * @since   3.1
	 */
	public function __construct() {
		$this->version = '1';	
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}
	
	/**
	 * Add to the update queue
	 *
	 * @access public
	 * @since  3.1
	 */
	public function register_routes() {
		register_rest_route( 'dittyeditor/v' . $this->version, 'save', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_ditty' ),
			'permission_callback' => array( $this, 'save_ditty_permissions_check' ),
    ) );
		register_rest_route( 'dittyeditor/v' . $this->version, 'saveDisplay', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_display' ),
			'permission_callback' => array( $this, 'save_display_permissions_check' ),
    ) );
		register_rest_route( 'dittyeditor/v' . $this->version, 'saveLayout', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_layout' ),
			'permission_callback' => array( $this, 'save_layout_permissions_check' ),
    ) );
		register_rest_route( 'dittyeditor/v' . $this->version, 'saveSettings', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_settings' ),
			'permission_callback' => array( $this, 'save_settings_permissions_check' ),
    ) );
		register_rest_route( 'dittyeditor/v' . $this->version, 'displayItems', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'get_display_items' ),
			'permission_callback' => array( $this, 'save_ditty_permissions_check' ),
    ) );
	}

	/**
	 * Check the Ditty permissions of the user
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_ditty_permissions_check( $request ) {
		$params = $request->get_params();
		$apiData = isset( $params['apiData'] ) ? $params['apiData'] : array();
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		if ( ! user_can( $userId, 'edit_dittys' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allow to edit Ditty.', 'ditty-news-ticker' ), array( 'status' => 401 ) );
		}
		return true;
	}

	/**
	 * Check the Display permissions of the user
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_display_permissions_check( $request ) {
		$params = $request->get_params();
		$apiData = isset( $params['apiData'] ) ? $params['apiData'] : array();
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		if ( ! user_can( $userId, 'edit_ditty_displays' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allow to edit Displays.', 'ditty-news-ticker' ), array( 'status' => 401 ) );
		}
		return true;
	}

	/**
	 * Check the Layout permissions of the user
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_layout_permissions_check( $request ) {
		$params = $request->get_params();
		$apiData = isset( $params['apiData'] ) ? $params['apiData'] : array();
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		if ( ! user_can( $userId, 'edit_ditty_layouts' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allow to edit Layouts.', 'ditty-news-ticker' ), array( 'status' => 401 ) );
		}
		return true;
	}

	/**
	 * Check the Layout permissions of the user
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_settings_permissions_check( $request ) {
		$params = $request->get_params();
		$apiData = isset( $params['apiData'] ) ? $params['apiData'] : array();
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		if ( ! user_can( $userId, 'manage_ditty_settings' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allow to edit Layouts.', 'ditty-news-ticker' ), array( 'status' => 401 ) );
		}
		return true;
	}

	/**
	 * Save updated Ditty values
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_ditty( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_api_data', __( 'No data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		if ( 0 == $userId ) {
			return new WP_Error( 'no_userId', __( 'No user id', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$id = isset( $apiData['id'] ) ? $apiData['id'] : 0;
		// if ( 0 == $id ) {
		// 	return new WP_Error( 'no_id', __( 'No Ditty id', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		// }
		$is_new_ditty = ( 'ditty-new' === $id );
		$items = isset( $apiData['items'] ) ? $apiData['items'] : array();
		$deletedItems = isset( $apiData['deletedItems'] ) ? $apiData['deletedItems'] : array();
		$display = isset( $apiData['display'] ) ? $apiData['display'] : false;
		$settings = isset( $apiData['settings'] ) ? $apiData['settings'] : false;
		$title = isset( $apiData['title'] ) ? $apiData['title'] : false;

		$updates = array();
		$errors = array();
		
		if ( $is_new_ditty ) {
			$id = wp_insert_post( [
				'post_type' => 'ditty',
				'post_status' => 'publish',
				'post_title' => $title,
			] );
			$updates['new'] = $id;
		} elseif ( $title ) {	
			$ditty_post_data = array(
				'ID' => $id,
				'post_title' => $title,
			);
			if ( wp_update_post( $ditty_post_data ) ) {
				$updates['title'] = $title;
			} else {
				$errors['title'] = $title;
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
				$sanitized_item = $serialized_item = Ditty()->singles->sanitize_item_data( $item );
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
			$display = isset( $display['id'] ) ? $display['id'] : $display;
			if ( update_post_meta( $id, '_ditty_display', $display ) ) {
				$updates['display'] = $display;
			} else {
				$errors['display'] = $display;
			}
		}

		// Sanitize & update settings
		if ( $settings ) {	
			$sanitized_settings = Ditty()->singles->sanitize_settings( $settings );
			if ( update_post_meta( $id, '_ditty_settings', $sanitized_settings ) ) {
				$updates['settings'] = $sanitized_settings;
			} else {
				$errors['settings'] = $sanitized_settings;
			}
		}

		$data = array(
			'id'			=> $id,
			'user_id' => $userId,
			'updates' => $updates,
			'errors'	=> $errors,
			'apiData'	=> $apiData,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Save updated Display values
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_display( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

		if ( ! isset( $apiData['display'] ) ) {
			return new WP_Error( 'no_id', __( 'No Display data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		$display_title = isset( $apiData['title'] ) ? $apiData['title'] : false;
		$display_description = isset( $apiData['description'] ) ? $apiData['description'] : false;
		$display = isset( $apiData['display'] ) ? $apiData['display'] : array();
		$display_id = isset( $display['id'] ) ? $display['id'] : false;
		$display_type = isset( $display['type'] ) ? $display['type'] : false;
		$display_settings = isset( $display['settings'] ) ? $display['settings'] : false;

		$updates = array();
		$errors = array();

		if ( $display_id ) {
			if ( $display_title ) {
				$postarr = array(
					'ID'					=> $display_id,
					'post_title'	=> $display_title,
				);
				wp_update_post( $postarr );
			}
		} else {
			$postarr = array(
				'post_type'		=> 'ditty_display',
				'post_status'	=> 'publish',
				'post_title'	=> $display_title,
			);
			$display_id = wp_insert_post( $postarr );
			$updates['id'] = $display_id;
			$updates['title'] = $display_title;
			$updates['edit_url'] = admin_url( "post.php?post={$layout_id}&action=edit" );
		}

		// Update a display description
		if ( $display_description ) {
			$sanitized_description = wp_kses_post( $display_description );
			update_post_meta( $display_id, '_ditty_display_description', $sanitized_description );
			$updates['description'] = $sanitized_description;
		}
		
		// Update a display type
		if ( $display_type ) {
			update_post_meta( $display_id, '_ditty_display_type', $display_type );
			$updates['type'] = $display_type;
		}

		// Update a display settings
		if ( $display_settings ) {
			update_post_meta( $display_id, '_ditty_display_settings', $display_settings );
			$updates['settings'] = $display_settings;
		}

		$data = array(
			'updates' => $updates,
			'errors'	=> $errors,
			'apiData'	=> $apiData,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Save updated Layout values
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_layout( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

		if ( ! isset( $apiData['layout'] ) ) {
			return new WP_Error( 'no_id', __( 'No Layout data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		$layout_title = isset( $apiData['title'] ) ? $apiData['title'] : false;
		$layout_description = isset( $apiData['description'] ) ? $apiData['description'] : false;
		$layout = isset( $apiData['layout'] ) ? $apiData['layout'] : array();
		$layout_id = isset( $layout['id'] ) ? $layout['id'] : false;
		$layout_html = isset( $layout['html'] ) ? $layout['html'] : false;
		$layout_css = isset( $layout['css'] ) ? $layout['css'] : false;

		$updates = array();
		$errors = array();

		if ( $layout_id ) {
			if ( $layout_title ) {
				$postarr = array(
					'ID'					=> $layout_id,
					'post_title'	=> $layout_title,
				);
				wp_update_post( $postarr );
			}
		} else {
			$postarr = array(
				'post_type'		=> 'ditty_layout',
				'post_status'	=> 'publish',
				'post_title'	=> $layout_title,
			);
			$layout_id = wp_insert_post( $postarr );
			$updates['id'] = $layout_id;
			$updates['title'] = $layout_title;
			$updates['edit_url'] = admin_url( "post.php?post={$layout_id}&action=edit" );
		}

		// Update the layout description
		if ( $layout_description ) {
			$sanitized_description = wp_kses_post( $layout_description );
			update_post_meta( $layout_id, '_ditty_layout_description', $sanitized_description );
			$updates['description'] = $sanitized_description;
		}
		
		// Update the layout type
		if ( $layout_html ) {
			$html = stripslashes( $layout_html );
			update_post_meta( $layout_id, '_ditty_layout_html', wp_kses_post( $html ) );
			$updates['html'] = $html;
		}

		// Update the layout settings
		if ( $layout_css ) {
			update_post_meta( $layout_id, '_ditty_layout_css', wp_kses_post( $layout_css ) );
			$updates['css'] = $layout_css;
		}

		$data = array(
			'updates' => $updates,
			'errors'	=> $errors,
			'apiData'	=> $apiData,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Save updated settings
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_settings( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

		if ( ! isset( $apiData['settings'] ) ) {
			return new WP_Error( 'no_settings', __( 'No settings', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		$settings = isset( $apiData['settings'] ) ? $apiData['settings'] : false;

		$updates = array();
		$errors = array();

		if ( $saved_settings = Ditty()->settings->save( $settings ) ) {
			$updates['settings'] = $saved_settings;
		} else {
			$errors['settings'] = $settings;
		}

		$data = array(
			'updates' => $updates,
			'errors'	=> $errors,
			'apiData'	=> $apiData,
			'settings' => $settings,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Save updated Layout values
	 *
	 * @access public
	 * @since  3.1
	 */
	public function get_display_items( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

		if ( ! isset( $apiData['items'] ) ) {
			return new WP_Error( 'no_id', __( 'No Items data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$items = isset( $apiData['items'] ) ? $apiData['items'] : [];
		$layouts = isset( $apiData['layouts'] ) ? $apiData['layouts'] : false;

		$display_items = array();
		$display_items_grouped = array();
		$preview_items = array();
		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $item ) {
				$item = ( array ) $item;
				if ( $item_type_object = ditty_item_type_object($item['item_type']) ) {
					$preview_items[$item['item_id']] = $item_type_object->editor_preview( $item['item_value'] );
				}

				$grouped_displays = [];			
				$prepared_items = ditty_prepare_display_items( $item );
				if ( is_array( $prepared_items ) && count( $prepared_items ) > 0 ) {
					foreach ( $prepared_items as $prepared_item ) {
						$display_item = new Ditty_Display_Item( $prepared_item, $layouts );
						$ditty_data = $display_item->ditty_data();
						$display_items[] = $ditty_data;
						$grouped_displays[] = $ditty_data;
					}
				}
				$display_items_grouped[$item['item_id']] = $grouped_displays;
			}
		}
	
		$updates = array();
		$errors = array();

		$data = array(
			'errors'	=> $errors,
			'display_items'	=> $display_items,
			'display_items_grouped'	=> $display_items_grouped,
			'preview_items'	=> $preview_items,
		);

		return rest_ensure_response( $data );
	}

}