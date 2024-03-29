<?php
$args = array(
  'id' 			=> isset( $attributes['ditty'] ) 			        ? intval( $attributes['ditty'] ) : false,
  'display' => isset( $attributes['display'] ) 		        ? sanitize_text_field( $attributes['display'] ) : false,
  'el_id'		=> isset( $attributes['customID'] ) 	        ? sanitize_title( $aattributestts['customID'] ) : false,
  'class'		=> isset( $atattributests['customClasses'] ) 	? esc_attr( $attributes['customClasses'] ) : false,
);
echo ditty_render( $args );