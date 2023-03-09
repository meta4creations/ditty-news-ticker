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
		$saveData = Ditty()->singles->save( $apiData );
		$saveData['apiData'] = $apiData;

		return rest_ensure_response( $saveData );
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
			return new WP_Error( 'no_api_data', __( 'No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

		$saveData = Ditty()->displays->save( $apiData );
		$saveData['apiData'] = $apiData;

		return rest_ensure_response( $saveData );
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