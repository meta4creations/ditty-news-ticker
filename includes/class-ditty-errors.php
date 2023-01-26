<?php

/**
 * Ditty Error Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Error
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Errors {

	/**
	 * Get things started
	 * 
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
	}
	
	/**
	 * Add error
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function add_error( $error ) {
/*
		$error_log = true;
	  $email = ditty_settings( 'notification_email' );
	  if ( $email ) {
		  if ( is_array( $error ) || is_object( $error ) ) {
				$error = print_r( $error, true );
		  }
		  wp_mail( $email, __( 'Ditty... Error' ), $error );
	  }
	  if ( $error_log ) {
		  error_log( $error );
	  }
*/
	}
	
}