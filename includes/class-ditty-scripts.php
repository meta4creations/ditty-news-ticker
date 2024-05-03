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
	private $version;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0.9
	 */
	public function __construct() {	
		$this->version	= ( defined( 'DITTY_DEVELOPMENT' ) && DITTY_DEVELOPMENT ) ? time() : DITTY_VERSION;
		
		add_action( 'init', array( $this, 'delete_cache' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );	
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    add_action( 'enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets'] );
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
// 		if( ! file_exists( $ditty_upload_dir . '/index.php' ) ) {
// 			file_put_contents( $ditty_upload_dir . '/index.php', "<?php
// // Silence is golden.");
// 		}

		$ditty_cache_dir = $ditty_upload_dir . '/cache';
		$ditty_cache_url = $ditty_upload_url . '/cache';
		if( ! file_exists( $ditty_cache_dir ) ) {
			mkdir( $ditty_cache_dir );
		}
// 		if( ! file_exists( $ditty_cache_dir . '/index.php' ) ) {
// 			file_put_contents( $ditty_cache_dir . '/index.php', "<?php
// // Silence is golden.");
// 		}
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
	public function enqueue_styles( $hook ) {

		//wp_enqueue_style( 'ditty-init', DITTY_URL . 'build/ditty.css', false, $this->version, false );	
		
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

		wp_register_style( 'protip', DITTY_URL . 'includes/libs/protip/protip.min.css', false, '1.4.21', false );	
		
		wp_register_style(
			'ditty-editor-init',
			DITTY_URL . 'build/dittyEditorInit.css',
			[],
			$this->version,
			'all'
		);

		if ( is_admin() ) {
			wp_enqueue_style(
				'ditty-admin',
				DITTY_URL . 'build/dittyAdmin.css',
				[],
				$this->version,
				'all'
			);
		}
		if ( 'ditty_page_ditty_extensions' == $hook || 'ditty_page_ditty_export' == $hook ) {
			wp_enqueue_style(
				'ditty-admin-old',
				DITTY_URL . 'build/dittyAdminOld.css',
				['protip'],
				$this->version,
				'all'
			);
		}
		if ( 'ditty_page_ditty_settings' == $hook ) {
			wp_enqueue_style(
				'ditty-settings',
				DITTY_URL . 'build/dittySettings.css',
				['ditty-editor-init'],
				$this->version,
				'all'
			);
		}
		
		// Enqueue editor styles
		if ( is_admin() && ( ditty_editing() || ditty_display_editing() || ditty_layout_editing() ) ) {
			wp_enqueue_style(
				'ditty-editor',
				DITTY_URL . 'build/dittyEditor.css',
				['ditty-editor-init', 'wp-components', 'wp-codemirror'],
				$this->version,
				'all'
			);
		}
		
		if ( ! is_admin() && ditty_fontawesome_enabled() ) {
			wp_enqueue_style( 'ditty-fontawesome', DITTY_URL . 'includes/libs/fontawesome-6.4.0/css/all.css', false, '6.4.0', false );
		}
		
		if ( is_admin() ) {
			wp_enqueue_style( 'ditty-fontawesome', DITTY_URL . 'includes/libs/fontawesome-6.4.0/css/all.css', false, '6.4.0', false );
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
		wp_register_script( 'ditty-slider', DITTY_URL . 'build/dittySlider.js', array( 'jquery', 'hammer' ), $this->version, true );
		wp_register_script( 'ditty-helpers', DITTY_URL . 'includes/js/partials/helpers.js', [], $this->version, true );
		wp_register_script( 'ditty-sass', DITTY_URL . 'includes/libs/sass/sass.js', [], $this->version );
		//wp_enqueue_script( 'ditty-sass', 'https://cdn.jsdelivr.net/npm/sass.js/dist/sass.min.js', [], $this->version );


		// Register the ditty init file
		// wp_enqueue_script( 'ditty-init',
		// 	DITTY_URL . 'build/ditty.js',
		// 	['wp-element'],
		// 	$this->version,
		// 	true
		// );
		// if ( empty( $ditty_scripts_enqueued ) ) {
		// 	wp_add_inline_script( 'ditty-init', 'const ditty=' . json_encode( apply_filters( 'ditty', array(
		// 		'dittyDevelopment'	=> defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false
		// 	) ) ) . ';', 'before' );
		// }


		// Register Ditty and display scripts
		wp_register_script( 'ditty', DITTY_URL . 'build/ditty.js', array( 'jquery', 'jquery-effects-core', ), $this->version, true );
		if ( empty( $ditty_scripts_enqueued ) ) {
			wp_add_inline_script( 'ditty', 'const dittyVars = ' . json_encode( apply_filters( 'dittyVars', array(
				'ajaxurl'					=> admin_url( 'admin-ajax.php' ),
				'security'				=> wp_create_nonce( 'ditty' ),
				'mode'						=> WP_DEBUG ? 'development' : 'production',
				'strings' 				=> ditty_strings(),
				'adminStrings' 		=> is_admin() ? ditty_admin_strings() : false,
				'globals'					=> ditty_get_globals(),
				'updateIcon'			=> 'fas fa-sync-alt fa-spin',
				'updateInterval'	=> ( MINUTE_IN_SECONDS * get_ditty_settings( 'live_refresh' ) ),
				'dittyDevelopment'	=> defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false,
			) ) ), 'before' ) . ';';
		}
		// wp_register_script( 'ditty',
		// 	DITTY_URL . 'build/ditty.js',
		// 	['wp-hooks', 'jquery-effects-core', 'jquery'],
		// 	$this->version,
		// 	true
		// );
		// if ( empty( $ditty_scripts_enqueued ) ) {
		// 	wp_add_inline_script( 'ditty', 'const ditty={};', 'before' );
		// 	wp_add_inline_script( 'ditty', 'const dittyVars = ' . json_encode( array(
		// 		'ajaxurl'					=> admin_url( 'admin-ajax.php' ),
		// 		'security'				=> wp_create_nonce( 'ditty' ),
		// 		'mode'						=> WP_DEBUG ? 'development' : 'production',
		// 		'globals'					=> ditty_get_globals(),
		// 		'updateInterval'	=> ( MINUTE_IN_SECONDS * get_ditty_settings( 'live_refresh' ) ),
		// 	) ), 'before' );
		// }
		
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
		
		// Register the editor init file
		$asset_file = include( DITTY_DIR . 'build/dittyEditorInit.asset.php' );
		wp_register_script( 'ditty-editor-init',
			DITTY_URL . 'build/dittyEditorInit.js',
			array_merge( $asset_file['dependencies'], ['wp-components'] ),
			$asset_file['version'],
			true
		);
		if ( empty( $ditty_scripts_enqueued ) ) {
      if ( $ditty_id = ditty_editing() ) {
        if ( 'ditty-new' == $ditty_id ) {
					$title = __( 'New Ditty', 'ditty-news-ticker' );
					$default_type = ditty_default_display_type();
					$display_type_object = ditty_display_type_object( $default_type );
					$display = false;
				} else {
					$ditty = get_post( $ditty_id );	
					$title = html_entity_decode( $ditty->post_title );
					$display = get_post_meta(  $ditty_id, '_ditty_display', true );
				}
				$item_data = Ditty()->editor->item_data(  $ditty_id );
        wp_add_inline_script( 'ditty-editor-init', 'const dittyEditorVars=' . json_encode( apply_filters( 'dittyEditorVars', array(
          'nonce'			          => wp_create_nonce( 'wp_rest' ),
          'mode'								=> WP_DEBUG ? 'development' : 'production',
          'userId'							=> get_current_user_id(),
          'adminUrl'						=> admin_url(),
          'restUrl'							=> get_rest_url(),
          'id'									=> $ditty_id,
          'title' 							=> $title,
          'status'							=> ( 'ditty-new' == $ditty_id ) ? 'publish' : get_post_status( $ditty_id ),
          'settings' 						=> ( 'ditty-new' == $ditty_id ) ? ditty_single_settings_defaults() : get_post_meta( $ditty_id, '_ditty_settings', true ),
          'items'								=> $item_data && isset( $item_data['items'] ) ? $item_data['items'] : false,
          'displayItems'				=> $item_data && isset( $item_data['display_items'] ) ? $item_data['display_items'] : false,
          'displayObject' 			=> is_array( $display ) ? ditty_sanitize_settings( $display ) : false,
          'display' 						=> ! is_array( $display ) ? $display : false,
          'displays'						=> Ditty()->editor->display_data(),
          'layouts'							=> Ditty()->editor->layout_data(),
          'itemTypes'						=> Ditty()->editor->item_type_data(),
          'displayTypes'				=> Ditty()->editor->display_type_data(),
          'variationDefaults' 	=> ditty_get_variation_defaults(),
          'defaultDisplayType' 	=> ditty_default_display_type(),
          'defaultItemType'			=> ditty_default_item_type(),
          'apiItemTypes'        => ditty_api_item_types(),
          'apiDisplayTypes'     => ditty_api_display_types(),
          'translationPlugin'    => Ditty()->translations->get_translation_plugin(),
          'translationLanguage'  => Ditty()->translations->get_translation_language(),
          'sassWorkerUrl'				  => DITTY_URL . 'includes/libs/sass/sass.worker.js',
          'dittyDevelopment'		  => defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false
        ), $hook ) ), 'before' ) . ';';
      }
      
			wp_add_inline_script( 'ditty-editor-init', 'const dittyEditor=' . json_encode( apply_filters( 'dittyEditor', array(
				'dittyDevelopment'	=> defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false
			) ) ) . ';', 'before' );
		}

		if ( $ditty_id = ditty_editing() ) {
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
				$this->load_external_scripts( 'editor', ['ditty-editor-init', 'wp-element', 'wp-components'], 'enqueue' );
			}

			wp_enqueue_script( 'ditty-editor',
				DITTY_URL . 'build/dittyEditor.js',
				array_merge(['ditty-editor-init', 'wp-element', 'wp-components', 'wp-editor', 'wp-block-editor', 'wp-hooks', 'wp-tinymce', 'wp-sanitize', 'lodash', 'wp-codemirror', 'ditty', 'ditty-sass'], $display_slugs),
				$this->version,
				true
			);
      wp_enqueue_media();
		}

		if ( $display_id = ditty_display_editing() ) {
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
				$this->load_external_scripts( 'editor', ['ditty-editor-init', 'wp-element', 'wp-components'], 'enqueue' );
			}

			wp_enqueue_script( 'ditty-display-editor',
				DITTY_URL . 'build/dittyDisplayEditor.js',
				array_merge(['ditty-editor-init', 'wp-element', 'wp-components', 'wp-hooks', 'wp-sanitize', 'lodash', 'wp-codemirror', 'ditty'], $display_slugs),
				$this->version,
				true
			);
      wp_enqueue_media();

			if ( empty( $ditty_scripts_enqueued ) ) {
				if ( 'ditty_display-new' == $display_id ) {
					$title = __( 'New Display', 'ditty-news-ticker' );
				} else {
					$display = get_post( $display_id );	
					$title = $display->post_title;
				}
				wp_add_inline_script( 'ditty-display-editor', 'const dittyEditorVars = ' . json_encode( apply_filters( 'dittyEditorVars', array(
					'nonce'			          => wp_create_nonce( 'wp_rest' ),
					'mode'								=> WP_DEBUG ? 'development' : 'production',
					'userId'							=> get_current_user_id(),
					'restUrl'							=> get_rest_url(),
					'id'									=> $display_id,
					'title' 							=> $title,
					'description' 				=> get_post_meta( $display_id, '_ditty_display_description', true ),
					'status'							=> ( 'ditty_display-new' == $display_id ) ? 'publish' : get_post_status( $display_id ),
					'type' 								=> 'ditty_display-new' == $display_id ? false : get_post_meta( $display_id, '_ditty_display_type', true ),
					'settings' 						=> 'ditty_display-new' == $display_id ? false : get_post_meta( $display_id, '_ditty_display_settings', true ),
					'editorSettings'			=> 'ditty_display-new' == $display_id ? false : get_post_meta( $display_id, '_ditty_editor_settings', true ),
					'displayTypes'				=> Ditty()->editor->display_type_data(),
          'apiDisplayTypes'     => ditty_api_display_types(),
					'defaultDisplayType' 	=> ditty_default_display_type(),
					'dittyDevelopment' 		=> defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false
				), $hook ) ), 'before' ) . ';';
			}
		}

		if ( $layout_id = ditty_layout_editing() ) {
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
				//$this->load_external_scripts( 'editor', ['ditty', 'wp-element', 'wp-components'], 'enqueue' );
				$this->load_external_scripts( 'editor', ['ditty-editor-init', 'wp-element', 'wp-components'], 'enqueue' );
			}

			wp_enqueue_script( 'ditty-layout-editor',
				DITTY_URL . 'build/dittyLayoutEditor.js',
				array_merge(['ditty-editor-init', 'wp-element', 'wp-components', 'wp-hooks', 'wp-sanitize', 'lodash', 'wp-codemirror', 'ditty', 'ditty-sass'], $display_slugs),
				$this->version,
				true
			);
      wp_enqueue_media();
      
			if ( empty( $ditty_scripts_enqueued ) ) {
				if ( 'ditty_layout-new' == $layout_id ) {
					$title = __( 'New Layout', 'ditty-news-ticker' );
				} else {
					$layout = get_post( $layout_id );	
					$title = $layout->post_title;
				}
				wp_add_inline_script( 'ditty-layout-editor', 'const dittyEditorVars = ' . json_encode( apply_filters( 'dittyEditorVars', array(
					'nonce'			      => wp_create_nonce( 'wp_rest' ),
					'mode'						=> WP_DEBUG ? 'development' : 'production',
					'userId'					=> get_current_user_id(),
					'restUrl'					=> get_rest_url(),
					'sassWorkerUrl'		=> DITTY_URL . 'includes/libs/sass/sass.worker.js',
					'id'							=> $layout_id,
					'title' 					=> $title,
					'description' 		=> get_post_meta( $layout_id, '_ditty_layout_description', true ),
					'status'					=> ( 'ditty_layout-new' == $layout_id ) ? 'publish' : get_post_status( $layout_id ),
					'html' 						=> 'ditty_layout-new' == $layout_id ? false : get_post_meta( $layout_id, '_ditty_layout_html', true ),
					'css' 						=> 'ditty_layout-new' == $layout_id ? false : get_post_meta( $layout_id, '_ditty_layout_css', true ),
					'editorItem'			=> 'ditty_layout-new' == $layout_id ? false : get_post_meta( $layout_id, '_ditty_editor_item', true ),
					'editorSettings'	=> 'ditty_layout-new' == $layout_id ? false : get_post_meta( $layout_id, '_ditty_editor_settings', true ),
					'itemTypes'				=> Ditty()->editor->item_type_data(),
          'apiItemTypes'    => ditty_api_item_types(),
					'dittyDevelopment'	=> defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false
				), $hook ) ), 'before' ) . ';';
			}
		}
		
		if ( is_admin() ) {
			wp_register_script( 'hammer', DITTY_URL . 'includes/libs/hammer.min.js', array( 'jquery' ), '2.0.8.1', true );
			wp_register_script( 'protip', DITTY_URL . 'includes/libs/protip/protip.min.js', array( 'jquery' ), '1.4.21', true );
			wp_register_script( 'ion-rangeslider', DITTY_URL . 'includes/libs/ion.rangeSlider/js/ion.rangeSlider.min.js', array( 'jquery' ), '2.3.1', true );
			wp_register_script( 'jquery-minicolors', DITTY_URL . 'includes/libs/jquery-minicolors/jquery.minicolors.min.js', array( 'jquery' ), '2.3.5', true );
			wp_register_script( 'ditty-fields', DITTY_URL . 'includes/fields/js/ditty-fields.min.js', array(
				'jquery',
				'protip',
				'jquery-effects-core',
				'wp-codemirror',
				'ion-rangeslider',
				'jquery-minicolors',
			), $this->version, true );
			wp_register_script( 'protip', DITTY_URL . 'includes/libs/protip/protip.min.js', array( 'jquery' ), '1.4.21', true );
			wp_register_script( 'ditty-fields', DITTY_URL . 'includes/fields/js/ditty-fields.min.js', array(
				'jquery',
				'protip',
				'jquery-effects-core',
				'wp-codemirror',
				'ion-rangeslider',
				'jquery-minicolors',
			), $this->version, true );

			if ( ( 'ditty_page_ditty_export' == $hook || 'ditty_page_ditty_extensions' == $hook ) && current_user_can( 'manage_ditty_settings' ) ) {
				wp_enqueue_script( 'ditty-admin', DITTY_URL . 'build/dittyAdmin.js', array(
					'jquery',
					'jquery-ui-core',
					'jquery-ui-sortable',
					'jquery-effects-core',
					'jquery-form',
					'protip',
					'wp-i18n',
					'ditty-fields',
					'ditty-slider',
				), $this->version, true );
				if ( empty( $ditty_scripts_enqueued ) ) {
					wp_add_inline_script( 'ditty-admin', 'const dittyAdminVars = ' . json_encode( apply_filters( 'dittyAdminVars', array(
						'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
						'security'			=> wp_create_nonce( 'ditty' ),
						'nonce'			    => wp_create_nonce( 'wp_rest' ),
						'mode'					=> WP_DEBUG ? 'development' : 'production',
						'adminStrings' 	=> is_admin() ? ditty_admin_strings() : false,
						'updateIcon'		=> 'fas fa-sync-alt fa-spin',
						'dittyDevelopment'	=> defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false,
					) ) ), 'before' ) . ';';
				}
			}

			if ( 'ditty_page_ditty_settings' == $hook && current_user_can( 'manage_ditty_settings' ) ) {
				wp_enqueue_script( 'ditty-settings',
					DITTY_URL . 'build/dittySettings.js',
					['wp-element', 'wp-components', 'wp-hooks', 'wp-sanitize', 'lodash', 'ditty-editor-init'],
					$this->version,
					true
				);
				wp_add_inline_script( 'ditty-settings', 'const dittySettingsVars = ' . json_encode( apply_filters( 'dittySettingsVars', array(
					'ajaxurl'						=> admin_url( 'admin-ajax.php' ),
          'nonce'             => wp_create_nonce('wp_rest'),
					'userId'						=> get_current_user_id(),
					'restUrl'						=> get_rest_url(),
					'fields'						=> Ditty()->settings->fields(),
					'settings'					=> get_ditty_settings(),
					'defaultSettings'		=> ditty_settings_defaults(),
					'dittyDevelopment'	=> defined( 'DITTY_DEVELOPMENT' ) ? DITTY_DEVELOPMENT : false,
				), $hook ) ), 'before' ) . ';';
			}
		}

		// Ensure global scripts are being added
		if ( ! is_admin() ) {
			$global_ditty = get_ditty_settings( 'global_ditty' );
			if ( is_array( $global_ditty ) && count( $global_ditty ) > 0 ) {
				foreach ( $global_ditty as $i => $global_ditty ) {
					if ( 'publish' === get_post_status( $global_ditty['ditty'] ) ) {
						ditty_add_scripts( $global_ditty['ditty'], $global_ditty['display'] );
					}
				}
			}
		}

		$ditty_scripts_enqueued = 'enqueued';
	}

  /**
   * Enqueue block editor assets
   */
  public function enqueue_block_editor_assets() {
    wp_enqueue_script('wp-block-editor');
    wp_enqueue_script('wp-edit-post');
    wp_enqueue_script('wp-components');
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