<?php

/**
 * Ditty File Field Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field File
 * @copyright   Copyright (c) 2022, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0.12
*/
class Ditty_Field_File extends Ditty_Field {	
	public $type = 'file';
  
  /**
   * Return the default atts
   *
   * @access  private
   * @since   3.0
   */
  public function defaults() {
    $atts = array(
      'file_types'  => array(),
      'multiple'      => false,
      'media_title'   => __( 'Upload or select a file', 'ditty-news-ticker' ),
      'media_button'  => __( 'Insert File', 'ditty-news-ticker' ),
    );
    return wp_parse_args( $atts, $this->common );
  }
  
  /**
   * Return the input
   *
   * @since 3.0
   * @return $html string
   */
  public function input( $name, $std = false ) {
    $html = '';
    
    $atts = array(
      'name'              => $name,
      'type'              => 'text',
      'class'             => ( $this->args['input_class'] ) ? $this->args['input_class'] : false,
      'data-media_title'  => $this->args['media_title'],
      'data-media_button' => $this->args['media_button'],
      'data-file_types'   => json_encode( $this->args['file_types'] ),
      'data-multiple'     => $this->args['multiple'],
      'value'             => $std,
    );
    //$html .= '<div class="ditty-input--image__preview">';
      $html .= '<input ' . ditty_attr_to_html( $atts ) . ' />';
      $html .= '<a href="#" class="ditty-input--file__upload ditty-button ditty-button--small">' . __( 'Upload File', 'ditty-news-ticker' ) . '</a>';
    //$html .= '</div>';
    return $html;
  }
}
