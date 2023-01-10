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
	public $cache_enabled = false;
	private $cache_transient = 'ditty_scripts_cache';
	private $cache_dir;
	private $cache_url;
	private $cache_time;
	private $cache;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0.9
	 */
	public function __construct() {	
		add_action( 'init', array( $this, 'delete_cache' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );	
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'admin_footer', array( $this, 'enqueue_global_scripts' ), 20 );
    add_action( 'wp_footer', array( $this, 'enqueue_global_scripts' ), 20 );
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
	 * Delete cache scripts
	 *
	 * @since 3.1
	 * @return void
	 */
	public function delete_cache() {
		if ( isset( $_GET['ditty_delete_cache'] ) ) {
			global $wp_filesystem;
			require_once ( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();

			$upload_dir = wp_upload_dir();
			$ditty_cache_dir = $upload_dir['basedir'].'/ditty/cache';
			$wp_filesystem->delete( $ditty_cache_dir , true );
			delete_transient( $this->cache_transient );
		}
	}

	/**
	 * Get the cache dir.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function get_cache_dir() {
		if ( empty( $this->cache_dir ) ) {
			$upload_dir = wp_upload_dir();
			$this->cache_dir = $upload_dir['basedir'].'/ditty/cache';
		}
		return $this->cache_dir;
	}

	/**
	 * Get the cache url.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function get_cache_url() {
		if ( empty( $this->cache_url ) ) {
			$upload_dir = wp_upload_dir();
			$this->cache_url = $upload_dir['baseurl'].'/ditty/cache';
		}
		return $this->cache_url;
	}

	/**
	 * Get the cache url.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function get_cache_time() {
		if ( empty( $this->cache_time ) ) {
			$this->cache_time = current_time( 'timestamp' );
		}
		return $this->cache_time;
	}

	/**
	 * Create the cache directory
	 *
	 * @since 3.1
	 * @return void
	 */
	private function create_cache_directory() {
		$upload_dir = wp_upload_dir();
		$ditty_upload_dir = $upload_dir['basedir'].'/ditty';
		$ditty_upload_url = $upload_dir['baseurl'].'/ditty';
		if( ! file_exists( $ditty_upload_dir ) ) {
			mkdir( $ditty_upload_dir );
		}
		if( ! file_exists( $ditty_upload_dir . '/index.php' ) ) {
			file_put_contents( $ditty_upload_dir . '/index.php', "<?php
// Silence is golden.");
		}

		$ditty_cache_dir = $ditty_upload_dir . '/cache';
		$ditty_cache_url = $ditty_upload_url . '/cache';
		if( ! file_exists( $ditty_cache_dir ) ) {
			mkdir( $ditty_cache_dir );
		}
		if( ! file_exists( $ditty_cache_dir . '/index.php' ) ) {
			file_put_contents( $ditty_cache_dir . '/index.php', "<?php
// Silence is golden.");
		}
	}

	/**
	 * Combine css files for the cache.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function combine_styles( $type, $required = [] ) {
		$styles = $this->get_styles();
		if ( isset( $styles[$type] ) && is_array( $styles[$type] ) && count( $styles[$type] ) > 0 ) {
			$cache_time = $this->get_cache_time();
			$cache_path = $this->get_cache_dir() . "/ditty-{$type}-cache-{$cache_time}.css";
			$cache_url = $this->get_cache_url() . "/ditty-{$type}-cache-{$cache_time}.css";
			$cache_required = $required;
			if ( ! file_exists( $cache_path ) ) {
				global $wp_filesystem;
				$combined_styles = '';
				foreach ( $styles[$type] as $slug => $style ) {
					if ( $wp_filesystem->exists( $style[2] ) ) {
						$combined_styles .= $wp_filesystem->get_contents( $style[2] );
						if ( is_array( $style[3] ) ) {
							$cache_required = array_merge( $cache_required, $style[3] );
						}
					}
				}
				$minified_styles = preg_replace( '/\s+/S', ' ', $combined_styles );
				file_put_contents( $cache_path, trim( $minified_styles ) );
			}
			return array(
				"{$type}_css_path" => $cache_path,
				"{$type}_css_url" => $cache_url,
				"{$type}_css_required" => $cache_required,
			);
		}
	}

	/**
	 * Combine script files for the cache.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function combine_scripts( $type, $required = [] ) {
		$scripts = $this->get_scripts();
		if ( isset( $scripts[$type] ) && is_array( $scripts[$type] ) && count( $scripts[$type] ) > 0 ) {
			$cache_time = $this->get_cache_time();
			$cache_path = $this->get_cache_dir() . "/ditty-{$type}-cache-{$cache_time}.js";
			$cache_url = $this->get_cache_url() . "/ditty-{$type}-cache-{$cache_time}.js";
			$cache_required = $required;
			if ( ! file_exists( $cache_path ) ) {
				global $wp_filesystem;
				$combined_scripts = '';
				foreach ( $scripts[$type] as $slug => $script ) {
					if ( $wp_filesystem->exists( $script[2] ) ) {
						$combined_scripts .= $wp_filesystem->get_contents( $script[2] );
						if ( is_array( $script[3] ) ) {
							$cache_required = array_merge( $cache_required, $script[3] );
						}
					}
				}
				$minified_scripts = \JShrink\Minifier::minify( $combined_scripts );
				file_put_contents( $cache_path, $minified_scripts );
			}
			return array(
				"{$type}_js_path" => $cache_path,
				"{$type}_js_url" => $cache_url,
				"{$type}_js_required" => $cache_required,
			);
		}
	}

	/**
	 * Get Ditty cache.
	 *
	 * @since 3.1
	 * @return void
	 */
	private function get_cache() {
		if ( empty( $this->cache ) ) {
			$cache = get_transient( $this->cache_transient );
			if ( ! $cache ) {
				global $wp_filesystem;
				require_once ( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
				$this->create_cache_directory();

				$cache = array(
					'time' => $this->get_cache_time(),
				);

				// Combine and cache css files
				if ( $display_css_cache = $this->combine_styles( 'display' ) ) {
					$cache = array_merge( $cache, $display_css_cache );
				}
				if ( $editor_css_cache = $this->combine_styles( 'editor' ) ) {
					$cache = array_merge( $cache, $editor_css_cache );
				}
				
				// Combine and cache js files
				if ( $display_js_cache = $this->combine_scripts( 'display', ['ditty'] ) ) {
					$cache = array_merge( $cache, $display_js_cache );
				}
				if ( $editor_js_cache = $this->combine_scripts( 'editor', ['ditty', 'wp-element', 'wp-components'] ) ) {
					$cache = array_merge( $cache, $editor_js_cache );
				}

				set_transient( $this->cache_transient, $cache );
			}
			$this->cache = $cache;
		}
		return $this->cache;
	}

	/**
	 * Load the scripts.
	 *
	 * @since    3.1
	 */
	private function load_external_styles( $type, $required = [], $load_type = 'register'  ) {
		$styles = $this->get_styles();
		$slugs = [];
		if ( isset( $styles[$type] ) && is_array( $styles[$type] ) && count( $styles[$type] ) > 0 ) {
			foreach ( $styles[$type] as $slug => $style ) {
				$slugs[] = $slug;
				$required_styles = $required;
				if ( is_array( $style[3] ) ) {
					$required_styles = array_merge( $required_styles, $style[3] );
				}
				$load = "wp_{$load_type}_style";
				$load(
					$slug,
					$style[1],
					$required_styles,
					$style[4],
					'all'
				);
			}
		}
		return $slugs;
	}

	/**
	 * Load the stylesheets.
	 *
	 * @since    3.1
	 */
	private function load_external_scripts( $type, $required = [], $load_type = 'register'  ) {
		$scripts = $this->get_scripts();
		$slugs = [];
		if ( isset( $scripts[$type] ) && is_array( $scripts[$type] ) && count( $scripts[$type] ) > 0 ) {
			foreach ( $scripts[$type] as $slug => $script ) {
				$slugs[] = $slug;
				$required_scripts = $required;
				if ( is_array( $script[3] ) ) {
					$required_scripts = array_merge( $required_scripts, $script[3] );
				}
				$load = "wp_{$load_type}_script";
				$load(
					$slug,
					$script[1],
					$required_scripts,
					$script[4],
					true
				);
			}
		}
		return $slugs;
	}

  /**
	 * Register the stylesheets.
	 *
	 * @since    3.1
	 */
	public function enqueue_styles() {
		
		// Enqueue display styles
		if ( $this->cache_enabled ) {
			$cache = $this->get_cache();
			wp_enqueue_style(
				'ditty-display-cache',
				$cache['display_css_url'],
				$cache['display_css_required'],
				null,
				'all'
			);
		} else {
			$this->load_external_styles( 'display', [], 'enqueue' );
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
	public function enqueue_scripts( $hook ) {
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
		if ( empty( $ditty_scripts_enqueued ) ) {
			wp_add_inline_script( 'ditty', 'const ditty={};', 'before' );
		}

		
		if ( $this->cache_enabled ) {
			$cache = $this->get_cache();
			wp_register_script(
				'ditty-display-cache',
				$cache['display_js_url'],
				$cache['display_js_required'],
				null,
				true
			);
			$display_slugs = ['ditty-display-cache'];
		} else {
			$display_slugs = $this->load_external_scripts( 'display', ['ditty'], 'register' );
		}

		if ( ditty_editing() ) {
			wp_dequeue_script( 'autosave' );

			if ( $this->cache_enabled ) {
				$cache = $this->get_cache();
				wp_enqueue_script(
					'ditty-editor-cache',
					$cache['editor_js_url'],
					$cache['editor_js_required'],
					null,
					true
				);
			} else {
				$this->load_external_scripts( 'editor', ['ditty', 'wp-element', 'wp-components'], 'enqueue' );
			}

			wp_enqueue_script( 'dittyEditor',
				DITTY_URL . 'build/dittyEditor.js',
				array_merge(['wp-element', 'wp-components', 'wp-hooks', 'lodash', 'ditty'], $display_slugs),
				ditty_version(),
				true
			);
			if ( empty( $ditty_scripts_enqueued ) ) {
				wp_add_inline_script( 'ditty', 'const dittyEditor={};', 'before' );
				wp_add_inline_script( 'dittyEditor', 'const dittyEditorVars = ' . json_encode( array(
					'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
					'security'			=> wp_create_nonce( 'ditty' ),
					'mode'					=> WP_DEBUG ? 'development' : 'production',
					'userId'				=> get_current_user_id(),
					'siteUrl'				=> site_url(),
					'displays'			=> Ditty()->editor->display_data(),
					'layouts'				=> Ditty()->editor->layout_data(),
					'itemTypes'			=> Ditty()->editor->item_type_data(),
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
	public function enqueue_global_scripts() {
		
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
		if ( $this->cache_enabled ) {
			$cache = $this->get_cache();
			wp_print_scripts( 'ditty-display-cache' );
			wp_print_scripts( 'ditty' );
		} else {
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

		// Add ditty scripts
		global $ditty_singles;
		if ( empty( $ditty_singles ) ) {
			$ditty_singles = array();
		}
		if ( is_array( $ditty_singles ) && count( $ditty_singles ) > 0 ) {
			?>
			<script id="ditty-singles">
				jQuery( function( $ ) {
				<?php
				foreach ( $ditty_singles as $ditty_atts ) {
					Ditty()->singles->init( $ditty_atts );
				}
				?>
				} );
			</script>
			<?php
		}	
	}

}