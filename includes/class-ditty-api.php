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
	 * @since   1.0
	 */
	public function __construct() {
		$this->version = '1';	
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}
	
	/**
	 * Add to the update queue
	 *
	 * @access public
	 * @since  1.0
	 */
	public function register_routes() {
		register_rest_route( 'dittyeditor/v' . $this->version, 'save', array(
      'methods' 	=> 'POST',
      'callback' 	=> array( $this, 'save_ditty' )
    ) );
	}

	/**
	 * Save updated Ditty values
	 *
	 * @access public
	 * @since  1.0
	 */
	public function save_ditty( $request ) {
		$params = $request->get_params();
		if ( ! isset( $params['apiData'] ) ) {
			return new WP_Error( 'no_id', __( 'No Ditty id or data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		}
		$apiData = $params['apiData'];
		$id = isset( $apiData['id'] ) ? $apiData['id'] : array();
		$items = isset( $apiData['items'] ) ? $apiData['items'] : array();
		$deletedItems = isset( $apiData['deletedItems'] ) ? $apiData['deletedItems'] : array();
		$display = isset( $apiData['display'] ) ? $apiData['display'] : false;
		$settings = isset( $apiData['settings'] ) ? $apiData['settings'] : false;

		$testing = array();

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
				$testing[] = $item;

				Ditty()->db_items->update( $item['item_id'], $item, 'item_id' );
			}
		}

		// Update display
		if ( $display ) {
			if ( ! update_post_meta( $id, '_ditty_display', $display ) ) {
				$display = 'error';
			}
		}

		// Update settings
		if ( $settings ) {
			if ( ! update_post_meta( $id, '_ditty_settings', $settings ) ) {
				$settings = 'error';
			}
		}

		return new WP_REST_Response( $settings, 200 );
	}

}