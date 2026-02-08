<?php

$el_id = isset( $attributes['customID'] ) ? $attributes['customID'] : ( isset( $attributes['anchor'] ) ? $attributes['anchor'] : false );
$class = isset( $attributes['customClasses'] ) ? $attributes['customClasses'] : ( isset( $attributes['className'] ) ? $attributes['className'] : false );
$display_settings = isset( $attributes['displaySettings'] ) ? json_decode( $attributes['displaySettings'] ) : false;

$args = array(
  'id' 			=> isset( $attributes['ditty'] ) 			        ? intval( $attributes['ditty'] ) : false,
  'display' => isset( $attributes['display'] ) 		        ? sanitize_text_field( $attributes['display'] ) : false,
  'layout'  => isset( $attributes['layout'] ) 		        ? sanitize_text_field( $attributes['layout'] ) : false,
  'display_settings'  => $display_settings ? json_encode( $display_settings ) : false,
  'el_id'		=> $el_id ? sanitize_title( $el_id ) : false,
  'class'		=> $class ? esc_attr( $class ) : false,
);

echo ditty_render( $args );