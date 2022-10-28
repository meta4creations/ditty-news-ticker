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


		// register_rest_route( "dittyEditor/v{$this->version}/ditty/(?P<id>[\d]+)", array(
    //   'methods' 	=> 'GET',
    //   'callback' 	=> array( $this, 'get_ditty' )
    // ) );
		// register_rest_route( "dittyEditor/v{$this->version}/save", array(
    //   'methods' 	=> 'POST',
    //   'callback' 	=> array( $this, 'save_ditty' )
    // ) );
	}

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

		// Update items
		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $i => $item ) {
				if ( is_object( $item ) ) {
					$item = ( array ) $item;
				}
				if ( is_array( $item ) && count( $item ) > 0 ) {
					foreach ( $item as $key => $value ) {
						$item[$key] = maybe_serialize( $value );
					}
				}
				Ditty()->db_items->update( $item['item_id'], $item, 'item_id' );
			}
		}

		return new WP_REST_Response( $apiData, 200 );
	}

	/**
	 * Get a Ditty
	 *
	 * @access public
	 * @since  1.0
	 */
	public function get_ditty( $request ) {
    $params = $request->get_params();
		//$id = $params['id'];
		$data = array(
			'id' => 'testing',
		);
		// if ( ! isset( $params['id'] ) || ! isset( $params['apiData'] ) ) {
		// 	return new WP_Error( 'no_id', __( 'No Ditty id or data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
		// }
		//$apiData = $params['apiData'];
		//$items = isset( $apiData['items'] ) ? $apiData['items'] : array();
		//$deletedItems = isset( $apiData['deletedItems'] ) ? $apiData['deletedItems'] : array();
		//$display = isset( $apiData['display'] ) ? $apiData['display'] : false;

		// Update items
		// if ( is_array( $items ) && count( $items ) > 0 ) {
		// 	foreach ( $items as $i => $item ) {
				
		// 	}
		// }


		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Save a Ditty
	 *
	 * @access public
	 * @since  1.0
	 */
	// public function save_ditty( $request ) {
  //   $params = $request->get_params();
	// 	// if ( ! isset( $params['id'] ) || ! isset( $params['apiData'] ) ) {
	// 	// 	return new WP_Error( 'no_id', __( 'No Ditty id or data', 'ditty-news-ticker' ), array( 'status' => 404 ) );
	// 	// }
	// 	$apiData = $params['apiData'];
	// 	//$items = isset( $apiData['items'] ) ? $apiData['items'] : array();
	// 	//$deletedItems = isset( $apiData['deletedItems'] ) ? $apiData['deletedItems'] : array();
	// 	//$display = isset( $apiData['display'] ) ? $apiData['display'] : false;

	// 	// Update items
	// 	// if ( is_array( $items ) && count( $items ) > 0 ) {
	// 	// 	foreach ( $items as $i => $item ) {
				
	// 	// 	}
	// 	// }


	// 	return new WP_REST_Response( $apiData, 200 );
	// }

}