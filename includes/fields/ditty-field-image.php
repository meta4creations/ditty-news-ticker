<?php

/**
 * Ditty Image Field Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Image
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Image extends Ditty_Field {	
	public $type = 'image';
  
  /**
   * Return the default atts
   *
   * @access  private
   * @since   3.0
   */
  public function defaults() {
    $atts = array(
      'types' => array(),
      'multiple' => false,
      'media_title' => __( 'Upload or select an image', 'ditty-news-ticker' ),
      'media_button' => __( 'Insert Image', 'ditty-news-ticker' ),
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
      'name' => $name,
      'type' => 'hidden',
      'class' => ( $this->args['input_class'] ) ? $this->args['input_class'] : false,
      'data-media_title' => $this->args['media_title'],
      'data-media_button' => $this->args['media_button'],
      'data-multiple' => $this->args['multiple'],
      'value' => $std,
    );
    $html .= '<input ' . ditty_attr_to_html( $atts ) . ' />';
    $html .= '<div class="ditty-input--image__preview">';
      if( '' != $std ) {
        $html .= wp_get_attachment_image( $std, 'medium' );
        $html .= '<a href="#" class="ditty-input--image__upload"></a>';
      } else {
        $html .= '<a href="#" class="ditty-input--image__upload"><i class="fas fa-plus"></i></a>';
      }
    $html .= '</div>';
    return $html;
  }
}
