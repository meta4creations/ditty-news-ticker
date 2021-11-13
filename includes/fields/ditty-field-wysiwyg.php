<?php

/**
 * Ditty Field Ditty_Field_Wysiwyg Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Ditty_Field_Wysiwyg
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Wysiwyg extends Ditty_Field {	
	
	public $type = 'wysiwyg';
	
	/**
	 * Return the input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input( $name, $std = '' ) {
		
		if ( ! is_string( $std ) ) {
			$std = '';
		}
		
		wp_enqueue_editor();
		$settings = array(
			'textarea_name' => $name,
			'textarea_rows' => 10,
			'media_buttons' => false,
			'teeny'					=> true,
		);
		ob_start();
		wp_editor( stripslashes( $std ), uniqid( 'ditty-input--' ), $settings );
		return ob_get_clean();
	}
	
}
