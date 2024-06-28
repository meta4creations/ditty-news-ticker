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
		register_rest_route( "dittyeditor/v{$this->version}", 'save', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_ditty' ),
			'permission_callback' => array( $this, 'save_ditty_permissions_check' ),
    ) );
		register_rest_route( "dittyeditor/v{$this->version}", 'saveDisplay', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_display' ),
			'permission_callback' => array( $this, 'save_display_permissions_check' ),
    ) );
		register_rest_route( "dittyeditor/v{$this->version}", 'saveLayout', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_layout' ),
			'permission_callback' => array( $this, 'save_layout_permissions_check' ),
    ) );
		register_rest_route( "dittyeditor/v{$this->version}", 'saveSettings', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_settings' ),
			'permission_callback' => array( $this, 'save_settings_permissions_check' ),
    ) );
		register_rest_route( "dittyeditor/v{$this->version}", 'displayItems', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'get_display_items' ),
			'permission_callback' => array( $this, 'general_ditty_permissions_check' ),
    ) );
		register_rest_route( "dittyeditor/v{$this->version}", 'phpItemMods', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'php_item_mods' ),
			'permission_callback' => array( $this, 'general_ditty_permissions_check' ),
    ) );
    register_rest_route( "dittyeditor/v{$this->version}", 'refreshTranslations', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'refresh_translations' ),
			'permission_callback' => array( $this, 'save_ditty_permissions_check' ),
    ) );
    register_rest_route( "dittyeditor/v{$this->version}", 'dynamicLayoutTags', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'dynamic_layout_tags' ),
			'permission_callback' => array( $this, 'general_ditty_permissions_check' ),
    ) );
    // register_rest_route( "dittyeditor/v{$this->version}", 'defaultLayout', array(
    //   'methods' 	=> 'POST',
    //   'callback' 	=> array( $this, 'layout_tags' ),
		// 	'permission_callback' => array( $this, 'general_ditty_permissions_check' ),
    // ) );
	}

	/**
	 * General permissions
	 *
	 * @access public
	 * @since  3.1.9
	 */
	public function general_ditty_permissions_check( $request ) {
		return true;
	}

	/**
	 * Check the Ditty permissions of the user
	 *
	 * @access public
	 * @since  3.1.9
	 */
	public function save_ditty_permissions_check( $request ) {
		$params = $request->get_params();
		$apiData = isset( $params['apiData'] ) ? $params['apiData'] : array();
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		$ditty_id = isset( $apiData['id'] ) ? $apiData['id'] : 0;

    if ( ! current_user_can( 'edit_dittys' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allowed to edit Ditty.', 'ditty-news-ticker' ), array( 'status' => 403 ) );
		}
		
		if ( 'ditty-new' != $ditty_id ) {
			$ditty_post = get_post( $ditty_id );
			$ditty_author = $ditty_post->post_author;
			if ( 0 != $ditty_author && $userId != $ditty_author && ! current_user_can( 'edit_others_dittys' ) ) {
				return new WP_Error( 'rest_forbidden', esc_html__( "Sorry, you are not allowed to edit other authors' Ditty.", 'ditty-news-ticker' ), array( 'status' => 403 ) );
			}
		}

		return true;
	}

	/**
	 * Check the Display permissions of the user
	 *
	 * @access public
	 * @since  3.1.9
	 */
	public function save_display_permissions_check( $request ) {
		$params = $request->get_params();
		$apiData = isset( $params['apiData'] ) ? $params['apiData'] : array();	
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		$display = isset( $apiData['display'] ) ? $apiData['display'] : [];
		$display_id = isset( $display['id'] ) ? $display['id'] : 0;
		
		if ( 'ditty_display-new' != $display_id ) {
			$display_post = get_post( $display_id );
			$display_author = $display_post->post_author;
			if ( 0 != $display_author && $userId != $display_author && ! current_user_can( 'edit_others_ditty_displays' ) ) {
				return new WP_Error( 'rest_forbidden', esc_html__( "Sorry, you are not allowed to edit other authors' Displays.", 'ditty-news-ticker' ), array( 'status' => 403 ) );
			}
		}

		if ( ! current_user_can( 'edit_ditty_displays' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allowed to edit Displays.', 'ditty-news-ticker' ), array( 'status' => 403 ) );
		}
		return true;
	}

	/**
	 * Check the Layout permissions of the user
	 *
	 * @access public
	 * @since  3.1.9
	 */
	public function save_layout_permissions_check( $request ) {
		$params = $request->get_params();
		$apiData = isset( $params['apiData'] ) ? $params['apiData'] : array();
		$userId = isset( $apiData['userId'] ) ? $apiData['userId'] : 0;
		$layout = isset( $apiData['layout'] ) ? $apiData['layout'] : [];
		$layout_id = isset( $layout['id'] ) ? $layout['id'] : 0;
		
		if ( 'ditty_layout-new' != $layout_id ) {
			$layout_post = get_post( $layout_id );
			$layout_author = $layout_post->post_author;
			if ( 0 != $layout_author && $userId != $layout_author && ! current_user_can( 'edit_others_ditty_layouts' ) ) {
				return new WP_Error( 'rest_forbidden', esc_html__( "Sorry, you are not allowed to edit other authors' Layouts.", 'ditty-news-ticker' ), array( 'status' => 403 ) );
			}
		}
		
		if ( ! current_user_can( 'edit_ditty_layouts' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allowed to edit Layouts.', 'ditty-news-ticker' ), array( 'status' => 403 ) );
		}
		return true;
	}

	/**
	 * Check the Layout permissions of the user
	 *
	 * @access public
	 * @since  3.1
	 */
	public function save_settings_permissions_check() {
		if ( ! current_user_can( 'manage_ditty_settings' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Sorry, you are not allow to edit Settings.', 'ditty-news-ticker' ), array( 'status' => 403 ) );
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
			return new WP_Error( 'no_api_data', __( 'Ditty Error: No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

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
			return new WP_Error( 'no_api_data', __( 'Display Error: No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
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
			return new WP_Error( 'no_api_data', __( 'Layout Error: No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

		$saveData = Ditty()->layouts->save( $apiData );
		$saveData['apiData'] = $apiData;
		return rest_ensure_response( $saveData );
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
			return new WP_Error( 'no_api_data', __( 'Settings Error: No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];

		if ( ! isset( $apiData['settings'] ) ) {
			return new WP_Error( 'no_settings', __( 'Settings Error: No settings', 'ditty-news-ticker' ), array( 'status' => 404 ) );
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
	 * Get display items for a Ditty
	 *
	 * @access public
	 * @since  3.1.7
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

				// Check and possibly skip disabled display items
				$item_disabled = array_unique( apply_filters( 'ditty_item_disabled', array(), $item['item_id'], $item, $items ) );
				if ( count( $item_disabled ) == 0 ) {
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

	/**
	 * Check item changes with PHP
	 *
	 * @access public
	 * @since  3.1
	 */
	public function php_item_mods( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_data', sprintf(__( '%s: No api data', 'ditty-news-ticker' ), 'php_item_mods'), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];
		if ( ! isset( $apiData['item'] ) ) {
			return new WP_Error( 'no_item', sprintf(__( '%s: No item data', 'ditty-news-ticker' ), 'php_item_mods'), array( 'status' => 404 ) );
		}

		$item = $apiData['item'];
		$hook = isset( $apiData['hook'] ) ? $apiData['hook'] : false;

		if ( $hook ) {
			$filtered_item = apply_filters( "ditty_item_php_mods_{$hook}", $item );
		} else {
			$filtered_item = apply_filters( 'ditty_item_php_mods', $item );
		}

		$data = array(
			'item'	=> $filtered_item,
		);
		return rest_ensure_response( $data );
	}

  /**
	 * Refresh Translations
	 *
	 * @access public
	 * @since  3.1.25
	 */
	public function refresh_translations( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];
		if ( ! isset( $apiData['id'] ) ) {
			return new WP_Error( 'no_id', __( 'No Ditty Id', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
    $ditty_id = $apiData['id'];

    // Save strings
    $results = Ditty()->translations->save_ditty_translations( $ditty_id );

    // Delete transients
    Ditty()->translations->delete_language_transients( $ditty_id );

		$data = array(
			'results'	=> __( 'All strings refreshed!', 'ditty-news-ticker' ),
		);
		return rest_ensure_response( $data );
	}

  /**
	 * Return layout tags for item type
	 *
	 * @access public
	 * @since  3.1.43
	 */
	public function dynamic_layout_tags( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No api data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];
		if ( ! isset( $apiData['itemType'] ) ) {
			return new WP_Error( 'itemType', __( 'No Item Type', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
    if ( ! isset( $apiData['itemValue'] ) ) {
			return new WP_Error( 'itemValue', __( 'No Values', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}

    $layout_tags = array_values( apply_filters( 'ditty_layout_tags', [], $apiData['itemType'], $apiData['itemValue'] ) );

		return rest_ensure_response( $layout_tags );
	}

}