<?php

/**
 * Ditty Field Html Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Field Html
 * @copyright   Copyright (c) 2020, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Field_Html extends Ditty_Field {	
	
	public $type = 'html';
	
	/**
	 * Return the input
	 *
	 * @since 3.0
	 * @return $html string
	 */
	public function input( $name, $std = false ) {
		return $std;
	}
	
}
