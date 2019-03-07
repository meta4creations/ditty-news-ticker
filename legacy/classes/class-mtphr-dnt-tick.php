<?php
	
/* --------------------------------------------------------- */
/* !Create the base ticker class - 2.0.9 */
/* --------------------------------------------------------- */

class MTPHR_DNT_Tick {
	
	private $contents;
	private $id;
	private $classes;
	private $type;
	private $width;
	private $height;
	
	
	function __construct( $data ) {	
		$this->contents = ( is_array($data) && isset($data['tick']) ) ? $data['tick'] : $data;
 	}
 	
 	
 	private function tick_wrapper() {
	 	
 	}

}