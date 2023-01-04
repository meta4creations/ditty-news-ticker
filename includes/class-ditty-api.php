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
	 * Save updated Ditty values
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_ditty( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No Ditty id or data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		$id = isset( $apiData['id'] ) ? $apiData['id'] : array();
		$items = isset( $apiData['items'] ) ? $apiData['items'] : array();
		$deletedItems = isset( $apiData['deletedItems'] ) ? $apiData['deletedItems'] : array();
		$display = isset( $apiData['display'] ) ? $apiData['display'] : false;
		$settings = isset( $apiData['settings'] ) ? $apiData['settings'] : false;
		$title = isset( $apiData['title'] ) ? $apiData['title'] : false;

		$updates = array();
		$errors = array();

		// Update items
		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $i => $item ) {
				if ( is_object( $item ) ) {
					$item = ( array ) $item;
				}

				//Set the modified date of the item
				if ( isset( $item['item_value'] ) ) {
					$item['date_modified'] = date( 'Y-m-d H:i:s' );
				}

				if ( is_array( $item ) && count( $item ) > 0 ) {
					foreach ( $item as $key => $value ) {
						$item[$key] = maybe_serialize( $value );
					}
				}

				if ( false !== strpos( $item['item_id'], 'new-' ) ) {
					if ( $new_item_id = Ditty()->db_items->insert( apply_filters( 'ditty_item_db_data', $item, $id ), 'item' ) ) {
						if ( ! isset( $updates['items'] ) ) {
							$updates['items'] = [];
						}
						$item['new_id'] = strval( $new_item_id );
						$updates['items'][] = $item;
					} else {
						if ( ! isset( $errors['items'] ) ) {
							$errors['items'] = [];
						}
						$errors['items'][] = $item;
					}
				} elseif ( Ditty()->db_items->update( $item['item_id'], apply_filters( 'ditty_item_db_data', $item, $id ), 'item_id' ) ) {
					if ( ! isset( $updates['items'] ) ) {
						$updates['items'] = [];
					}
					$updates['items'][] = $item;
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

		// Update settings
		if ( $settings ) {	
			if ( update_post_meta( $id, '_ditty_settings', $settings ) ) {
				$updates['settings'] = $settings;
			} else {
				$errors['settings'] = $settings;
			}
		}

		// Update title
		if ( $title ) {	
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

		$data = array(
			'id'	=> $id,
			'user_id' => $userId,
			'updates' => $updates,
			'errors'	=> $errors,
			'apiData'	=> $apiData,
		);

		return rest_ensure_response( $data );
	}

	/**
	 * Save updated Ditty values
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
			$updates['edit_url'] = get_edit_post_link( $display_id, 'code' );
		}

		// Update a display description
		if ( isset( $display_description ) ) {
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

}