<?php

use ScssPhp\ScssPhp\Compiler;
use Padaliyajay\PHPAutoprefixer\Autoprefixer;

/**
 * Ditty Render Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Shortcodes
 * @copyright   Copyright (c) 2023, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1.29
*/

class Ditty_Shortcodes {

	/**
	 * Get things started
	 * @access  public
	 * @since   3.1.29
	 */
	public function __construct() {

    // Shortcodes
		add_shortcode( 'ditty', [$this, 'do_ditty_shortcode'] );
	}

  /**
	 * Display the Ditty via shortcode
	 *
	 * @since    3.0
	 * @access   public
	 * @var      html
	 */
	public function do_ditty_shortcode( $atts ) {
		if ( ! is_admin() ) {
      return ditty_render( $atts );
		}
	}
}