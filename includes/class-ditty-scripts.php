<?php

/**
 * Ditty Scripts Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty WPML
 * @copyright   Copyright (c) 2022, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1
*/

class Ditty_Scripts {

  public $styles = [];
	public $scripts = [];

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0.9
	 */
	public function __construct() {	
    add_action( 'admin_enqueue_scripts', array( $this, 'dev_enqueue_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'dev_enqueue_styles' ) );	
    add_action( 'admin_enqueue_scripts', array( $this, 'dev_enqueue_scripts' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'dev_enqueue_scripts' ) );
    add_action( 'admin_footer', array( $this, 'dev_enqueue_global_scripts' ), 20 );
    add_action( 'wp_footer', array( $this, 'dev_enqueue_global_scripts' ), 20 );
	}
	
	/**
	 * Register Ditty styles.
	 *
	 * @since 3.1
	 * @return void
	 */
	public function register_style( $type, $args ) {
		if ( ! isset( $this->styles[$type]) ) {
			$this->styles[$type] = [];
		}	
		$this->styles[$type][$args[0]] = $args;
	}

	/**
	 * Get Ditty styles.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function get_styles() {
		return $this->styles;
	}

	/**
	 * Register Ditty scripts.
	 *
	 * @since 3.1
	 * @return void
	 */
	public function register_script( $type, $args ) {
		if ( ! isset( $this->scripts[$type]) ) {
			$this->scripts[$type] = [];
		}	
		$this->scripts[$type][$args[0]] = $args;
	}

	/**
	 * Get Ditty scripts.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function get_scripts() {
		return $this->scripts;
	}

  /**
	 * Register the stylesheets.
	 *
	 * @since    3.1
	 */
	public function dev_enqueue_styles() {
		$styles = $this->get_styles();

		// Enqueue display styles
		if ( isset( $styles['display'] ) && is_array( $styles['display'] ) && count( $styles['display'] ) > 0 ) {
			foreach ( $styles['display'] as $slug => $style ) {
				$required_styles = [];
				if ( is_array( $style[2] ) ) {
					$required_styles = array_merge( $required_styles, $style[2] );
				}
				wp_enqueue_style(
					$slug,
					$style[1],
					$required_styles,
					$style[3],
					'all'
				);
			}
		}

		// Enqueue editor styles
		wp_enqueue_style(
			'ditty-editor',
			DITTY_URL . 'build/dittyEditor.css',
			['wp-components'],
			ditty_version(),
			'all'
		);
		
		$disable_fontawesome = ditty_settings( 'disable_fontawesome' );
		if ( ! is_admin() && ! $disable_fontawesome ) {
			wp_enqueue_style( 'ditty-fontawesome', DITTY_URL . 'includes/libs/fontawesome-6.2.0/css/all.css', false, '6.2.0', false );
		}
		
		if ( is_admin() ) {
			wp_enqueue_style( 'ditty-fontawesome', DITTY_URL . 'includes/libs/fontawesome-6.2.0/css/all.css', false, '6.2.0', false );
		}
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    3.1
	 */
	public function dev_enqueue_scripts( $hook ) {
		global $ditty_scripts_enqueued;
		$scripts = $this->get_scripts();

		$min = WP_DEBUG ? '' : '.min';
		wp_register_script( 'hammer', DITTY_URL . 'includes/libs/hammer.min.js', array( 'jquery' ), '2.0.8.1', true );
		wp_register_script( 'ditty-slider', DITTY_URL . 'includes/js/class-ditty-slider' . $min . '.js', array( 'jquery', 'hammer' ), ditty_version(), true );
		wp_register_script( 'ditty-helpers', DITTY_URL . 'includes/js/partials/helpers.js', [], ditty_version(), true );

		// Register Ditty and display scripts
		wp_register_script( 'ditty',
			DITTY_URL . 'build/ditty.js',
			['wp-hooks', 'jquery-effects-core', 'jquery'],
			ditty_version(),
			true
		);
		if ( isset( $scripts['display'] ) && is_array( $scripts['display'] ) && count( $scripts['display'] ) > 0 ) {
			foreach ( $scripts['display'] as $slug => $script ) {
				$required_scripts = ['ditty'];
				if ( is_array( $script[2] ) ) {
					$required_scripts = array_merge( $required_scripts, $script[2] );
				}
				wp_register_script(
					$slug,
					$script[1],
					$required_scripts,
					$script[3],
					true
				);
			}
		}

		if ( ditty_editing() ) {
			wp_dequeue_script( 'autosave' );

			// Load all display scripts
			$display_slugs = [];
			if ( isset( $scripts['display'] ) && is_array( $scripts['display'] ) && count( $scripts['display'] ) > 0 ) {
				foreach ( $scripts['display'] as $slug => $script ) {
					$display_slugs[] = $slug;
					wp_enqueue_script( $slug );
				}
			}

			// Load all editor scripts		
			if ( isset( $scripts['editor'] ) && is_array( $scripts['editor'] ) && count( $scripts['editor'] ) > 0 ) {
				foreach ( $scripts['editor'] as $slug => $script ) {
					$required_scripts = ['ditty', 'wp-element', 'wp-components'];
					if ( is_array( $script[2] ) ) {
						$required_scripts = array_merge( $required_scripts, $script[2] );
					}
					wp_enqueue_script(
						$slug,
						$script[1],
						$required_scripts,
						$script[3],
						true
					);
				}
			}
			wp_enqueue_script( 'dittyEditor',
				DITTY_URL . 'build/dittyEditor.js',
				array_merge(['wp-element', 'wp-components', 'wp-hooks', 'lodash', 'ditty'], $display_slugs),
				ditty_version(),
				true
			);
			if ( empty( $ditty_scripts_enqueued ) ) {
				wp_add_inline_script( 'dittyEditor', 'const dittyEditorVars = ' . json_encode( array(
					'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
					'security'			=> wp_create_nonce( 'ditty' ),
					'mode'					=> WP_DEBUG ? 'development' : 'production',
					'userId'				=> get_current_user_id(),
					'siteUrl'				=> site_url(),
					'displays'			=> Ditty()->editor->display_data(),
					'layouts'				=> Ditty()->editor->layout_data(),
					'itemTypes'			=> array_values( ditty_item_types() ),
					'displayTypes'	=> Ditty()->editor->display_type_data(),
				) ), 'before' );
			}
		}	

		$ditty_scripts_enqueued = 'enqueued';
	}

	/**
	 * Enqueue global scripts for any Ditty's displayed
	 *
	 * @since    31
	 */
	public function dev_enqueue_global_scripts() {
		
		// Add item scripts
		global $ditty_item_scripts;
		if ( empty( $ditty_item_scripts ) ) {
			$ditty_item_scripts = array();
		}
		
		// Add display scripts
		global $ditty_display_scripts;
		if ( empty( $ditty_display_scripts ) ) {
			$ditty_display_scripts = array();
		}
		if ( is_array( $ditty_item_scripts ) && count( $ditty_item_scripts ) > 0 ) {
			foreach ( $ditty_item_scripts as $i => $ditty_item_script ) {
				wp_print_scripts( "ditty-{$ditty_item_script}" );
			}
		}
		if ( is_array( $ditty_display_scripts ) && count( $ditty_display_scripts ) > 0 ) {	
			$add_ditty = false;
			foreach ( $ditty_display_scripts as $i => $display_type ) {
				if ( empty( $display_type ) ) {
					continue;
				}
				wp_print_scripts( "ditty-display-{$display_type}" );
				$add_ditty = true;
			}
			if ( $add_ditty ) {
				wp_print_scripts( 'ditty' );
			}
		}
	}

}