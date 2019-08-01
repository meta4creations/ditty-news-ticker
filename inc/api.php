<?php
	
function dnt_register_api_routes() {
  
  register_rest_route( 'dnt/v1', '/fields/', array(
    'methods' => 'GET',
    'callback' => 'dnt_api_get_fields',
  ) );
}
add_action( 'rest_api_init', 'dnt_register_api_routes' );


function dnt_api_get_fields( $request ) {
	
	$parameters = $request->get_params();
  $type = $parameters['type'];
  
  $dnt_types = dnt_types();
  if ( isset( $dnt_types[$type] ) && isset( $dnt_types[$type]['class_name'] ) && class_exists( $dnt_types[$type]['class_name'] ) ) {
	  $type_class = new $dnt_types[$type]['class_name'];
	  return array(
		  'fields' => $type_class->fields(),
	  );
  } else {
	  return new WP_Error( 'no_fields', __( 'No fields available', 'ditty-news-ticker' ), array( 'status' => 404 ) );
  }
}