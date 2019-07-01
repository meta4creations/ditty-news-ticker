<?php
	
function dnt_register_api_routes() {
  
  register_rest_route( 'dnt/v1', '/fields/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'dnt_api_get_fields',
  ) );
}
add_action( 'rest_api_init', 'dnt_register_api_routes' );


function dnt_api_get_fields( $request ) {
	
	$type = $request['type'];
	
	return 'testing';
}