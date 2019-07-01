<?php

/**
 * Ditty News Ticker API Class
 *
 * @package     Ditty News Ticker
 * @subpackage  Classes/Ditty News Ticker API
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * DNT_API Class
 *
 * Renders API returns as a JSON/XML array
 *
 * @since  3.0
 */
class DNT_API {
	
	/**
	 * Latest API Version
	 */
	const VERSION = 1;
	
	/**
	 * Pretty Print?
	 *
	 * @var bool
	 * @access private
	 * @since 3.0
	 */
	private $pretty_print = false;

	/**
	 * Is this a valid request?
	 *
	 * @var bool
	 * @access private
	 * @since 3.0
	 */
	private $is_valid_request = false;
	
	/**
	 * Response data to return
	 *
	 * @var array
	 * @access private
	 * @since 3.0
	 */
	private $data = array();
	
	/**
	 * Version of the API queried
	 *
	 * @var string
	 * @since 3.0
	 */
	private $queried_version;

	/**
	 * All versions of the API
	 *
	 * @var string
	 * @since 3.0
	 */
	protected $versions = array();

	/**
	 * Queried endpoint
	 *
	 * @var string
	 * @since 3.0
	 */
	private $endpoint;

	/**
	 * Endpoints routes
	 *
	 * @var object
	 * @since 3.0
	 */
	private $routes;

	/**
	 * Get things started
	 * @access  public
	 * @since   1.0
	 */
	public function __construct() {
		
		$this->versions = array(
			'v1' => 'DNT_API_V1',
		);

		foreach( $this->get_versions() as $version => $class ) {
			require_once DNT_DIR . 'inc/classes/class-dnt-api-' . $version . '.php';
		}

		add_action( 'init',                     array( $this, 'add_endpoint'     ) );
		add_action( 'wp',                       array( $this, 'process_query'    ), -1 );
		add_filter( 'query_vars',               array( $this, 'query_vars'       ) );
		
		// Determine if JSON_PRETTY_PRINT is available
		$this->pretty_print = defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : null;
	}
	
	/**
	 * Registers a new rewrite endpoint for accessing the API
	 *
	 * @author Daniel J Griffiths
	 * @param array $rewrite_rules WordPress Rewrite Rules
	 * @since 3.0
	 */
	public function add_endpoint( $rewrite_rules ) {
		add_rewrite_endpoint( 'dnt-api', EP_ALL );
	}
	
	/**
	 * Registers query vars for API access
	 *
	 * @since 3.0
	 * @author Daniel J Griffiths
	 * @param array $vars Query vars
	 * @return string[] $vars New query vars
	 */
	public function query_vars( $vars ) {

		$vars[] = 'fields';

		return $vars;
	}
	
	/**
	 * Retrieve the API versions
	 *
	 * @since 3.0
	 * @return array
	 */
	public function get_versions() {
		return $this->versions;
	}

	/**
	 * Retrieve the API version that was queried
	 *
	 * @since 3.0
	 * @return string
	 */
	public function get_queried_version() {
		return $this->queried_version;
	}
	
	/**
	 * Retrieves the default version of the API to use
	 *
	 * @access private
	 * @since 3.0
	 * @return string
	 */
	public function get_default_version() {

		$version = get_option( 'dnt_default_api_version' );

		if( defined( 'DNT_API_VERSION' ) ) {
			$version = DNT_API_VERSION;
		} elseif( ! $version ) {
			$version = 'v1';
		}

		return $version;
	}
	
	/**
	 * Sets the version of the API that was queried.
	 *
	 * Falls back to the default version if no version is specified
	 *
	 * @access private
	 * @since 3.0
	 */
	private function set_queried_version() {

		global $wp_query;

		$version = $wp_query->query_vars['dnt-api'];

		if( strpos( $version, '/' ) ) {

			$version = explode( '/', $version );
			$version = strtolower( $version[0] );

			$wp_query->query_vars['dnt-api'] = str_replace( $version . '/', '', $wp_query->query_vars['dnt-api'] );

			if( array_key_exists( $version, $this->versions ) ) {

				$this->queried_version = $version;

			} else {

				$this->is_valid_request = false;
				$this->invalid_version();
			}

		} else {

			$this->queried_version = $this->get_default_version();

		}
	}
	
	/**
	 * Listens for the API and then processes the API requests
	 *
	 * @global $wp_query
	 * @since 3.0
	 * @return void
	 */
	public function process_query() {

		global $wp_query;

		// Start logging how long the request takes for logging
		$before = microtime( true );

		// Check for edd-api var. Get out if not present
		if ( empty( $wp_query->query_vars['dnt-api'] ) ) {
			return;
		}

		// Determine which version was queried
		$this->set_queried_version();

		// Determine the kind of query
		$this->set_query_mode();

		if( ! defined( 'DNT_DOING_API' ) ) {
			define( 'DNT_DOING_API', true );
		}

		$data = array();
		$this->routes = new $this->versions[ $this->get_queried_version() ];

		switch( $this->endpoint ) :

			case 'fields' :

				$data = $this->routes->get_fields( array(
					'type'	=> isset( $wp_query->query_vars['type'] )	? $wp_query->query_vars['type']	: null,
				) );

				break;

		endswitch;

		// Allow extensions to setup their own return data
		$this->data = apply_filters( 'dnt_api_output_data', $data, $this->endpoint, $this );

		$after                       = microtime( true );
		$request_time                = ( $after - $before );
		$this->data['request_speed'] = $request_time;

		// Send out data to the output function
		$this->output();
	}
	
	/**
	 * Process Get Fields API Request
	 *
	 * @author Daniel J Griffiths
	 * @since 3.0
	 *
	 * @global object $wpdb Used to query the database using the WordPress
	 *
	 * @param array $args Arguments provided by API Request
	 *
	 * @return array
	 */
	public function get_fields( $args = array() ) {
		$defaults = array(
			'type'      => null,
		);

		$args = wp_parse_args( $args, $defaults );

		return apply_filters( 'edd_api_stats', array( 'testing' => 'testing', $this ) );
	}
	
	/**
	 * Retrieve the output format
	 *
	 * Determines whether results should be displayed in XML or JSON
	 *
	 * @since 3.0
	 *
	 * @return mixed|void
	 */
	public function get_output_format() {
		global $wp_query;

		$format = isset( $wp_query->query_vars['format'] ) ? $wp_query->query_vars['format'] : 'json';

		return apply_filters( 'dnt_api_output_format', $format );
	}
	
	/**
	 * Retrieve the output data
	 *
	 * @since 3.0
	 * @return array
	 */
	public function get_output() {
		return $this->data;
	}
	
	/**
	 * Output Query in either JSON/XML. The query data is outputted as JSON
	 * by default
	 *
	 * @author Daniel J Griffiths
	 * @since 3.0
	 * @global $wp_query
	 *
	 * @param int $status_code
	 */
	public function output( $status_code = 200 ) {
		global $wp_query;

		$format = $this->get_output_format();

		status_header( $status_code );

		do_action( 'dnt_api_output_before', $this->data, $this, $format );

		switch ( $format ) :

			case 'xml' :

/*
				require_once EDD_PLUGIN_DIR . 'includes/libraries/class-ArrayToXML.php';
				$arraytoxml = new ArrayToXML();
				$xml        = $arraytoxml->buildXML( $this->data, 'edd' );

				echo $xml;
*/

				break;

			case 'json' :

				header( 'Content-Type: application/json' );
				if ( ! empty( $this->pretty_print ) )
					echo json_encode( $this->data, $this->pretty_print );
				else
					echo json_encode( $this->data );

				break;


			default :

				// Allow other formats to be added via extensions
				do_action( 'dnt_api_output_' . $format, $this->data, $this );

				break;

		endswitch;

		do_action( 'dnt_api_output_after', $this->data, $this, $format );

		edd_die();
	}
	
	public function get_version() {
		return self::VERSION;
	}
	
}