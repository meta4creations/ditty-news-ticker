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
	}

	/**
	 * Check the permissions of the user
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

				if ( Ditty()->db_items->update( $item['item_id'], $item, 'item_id' ) ) {
					if ( ! isset( $updates['items'] ) ) {
						$updates['items'] = array();
					}
					$updates['items'][] = $item;
				} else {
					if ( ! isset( $errors['items'] ) ) {
						$errors['items'][] = $item;
					}
				}
			}
		}

		// Update display
		if ( $display ) {
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
			'aipData'	=> $apiData,
		);

		return rest_ensure_response( $data );
	}

}