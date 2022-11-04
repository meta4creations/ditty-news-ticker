<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      3.0
 * @package    Ditty
 * @subpackage Ditty/includes
 * @author     Metaphor Creations <joe@metaphorcreations.com>
 */
class Ditty {
	
	/**
	 * The one true Ditty
	 *
	 * @since    3.0
	 * @var      Ditty
	 */
	private static $instance;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    3.0
	 * @access   protected
	 * @var      Ditty_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    3.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    3.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * Ditty singles object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Singles
	 */
	public $singles;
	
	/**
	 * Ditty items object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Items
	 */
	public $items;
	
	/**
	 * Ditty layouts object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Displays
	 */
	public $layouts;
	
	/**
	 * Ditty displays object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Displays
	 */
	public $displays;

	/**
	 * Ditty editor object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Editor
	 */
	public $editor;
	
	/**
	 * Ditty database items object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_DB_Items
	 */
	public $db_items;
	
	/**
	 * Ditty database item meta object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_DB_Item_Meta
	 */
	public $db_item_meta;
	
	/**
	 * Ditty extensions object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Extensions
	 */
	public $extensions;
	
	/**
	 * Ditty WPML object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_WPML
	 */
	public $wpml;
	
	/**
	 * Ditty error object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Errors
	 */
	public $errors;
	public $api;

	
	
	/**
	 * Main Ditty Instance.
	 *
	 * Insures that only one instance of Ditty exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 3.0
	 * @static
	 * @staticvar array $instance
	 * @uses Ditty::setup_constants() Setup the constants needed.
	 * @uses Ditty::includes() Include the required files.
	 * @uses Ditty::load_textdomain() load the language files.
	 * @see DITTY()
	 * @return object|Ditty The one true Ditty
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Ditty ) ) {
			self::$instance = new Ditty;

			self::$instance->plugin_name 	= 'ditty-news-ticker';
			self::$instance->version 			= WP_DEBUG ? time() : DITTY_VERSION;
			
			self::$instance->includes();
			self::$instance->set_locale();
			self::$instance->define_global_hooks();
			self::$instance->run();
			
			self::$instance->api					= new Ditty_API();	
			self::$instance->db_items			= new Ditty_DB_Items();
			self::$instance->db_item_meta	= new Ditty_DB_Item_Meta();
			self::$instance->displays			= new Ditty_Displays();
			self::$instance->editor				= new Ditty_Editor();
			self::$instance->errors				= new Ditty_Errors();
			self::$instance->extensions		= new Ditty_Extensions();
			self::$instance->layouts			= new Ditty_Layouts();
			self::$instance->singles			= new Ditty_Singles();
			self::$instance->items				= new Ditty_Items();
			self::$instance->wpml					= new Ditty_WPML();	
		}

		return self::$instance;
	}
	
	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 3.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ditty-news-ticker' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 3.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'ditty-news-ticker' ), '1.0' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ditty_Loader. Orchestrates the hooks of the plugin.
	 * - Ditty_i18n. Defines internationalization functionality.
	 * - Ditty_Admin. Defines all hooks for the admin area.
	 * - Ditty_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    3.0.13
	 * @access   private
	 */
	private function includes() {
		
		//require_once DITTY_DIR . 'eddsl/eddsl.php';
		if ( ! class_exists( 'ChromePhp' ) && defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			require_once DITTY_DIR . 'includes/libs/ChromePhp.php';
		}
		require_once DITTY_DIR . 'vendor/autoload.php';	

		// Add custom fields
		require_once DITTY_DIR . 'includes/fields/ditty-field.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-button.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-checkbox.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-checkboxes.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-code.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-color.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-date.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-divider.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-file.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-group.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-heading.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-html.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-image.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-layout_element.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-number.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-radio.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-text.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-textarea.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-radius.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-select.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-slider.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-spacing.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-wysiwyg.php';
		require_once DITTY_DIR . 'includes/fields/helpers.php';
		
		// Add general files
		require_once DITTY_DIR . 'includes/helpers.php';
		require_once DITTY_DIR . 'includes/hooks.php';
		require_once DITTY_DIR . 'includes/layout-tags.php';
		//require_once DITTY_DIR . 'includes/layout-tag-fields.php';
		require_once DITTY_DIR . 'includes/layout-tag-helpers.php';
		require_once DITTY_DIR . 'includes/layout-tag-hooks.php';
		require_once DITTY_DIR . 'includes/layout-tag-hooks-default.php';
		require_once DITTY_DIR . 'includes/layout-tag-hooks-posts.php';
		require_once DITTY_DIR . 'includes/layout-templates.php';
		require_once DITTY_DIR . 'includes/post-types.php';
		require_once DITTY_DIR . 'includes/upgrades.php';
		require_once DITTY_DIR . 'includes/widget.php';
		require_once DITTY_DIR . 'includes/wizard.php';
		
		// Builders
		require_once DITTY_DIR . 'includes/builders/fusion/builder.php';
		
		// Add database files
		require_once DITTY_DIR . 'includes/class-ditty-db.php';
		require_once DITTY_DIR . 'includes/class-ditty-db-items.php';
		require_once DITTY_DIR . 'includes/class-ditty-db-item-meta.php';

		// Add api files
		require_once DITTY_DIR . 'includes/class-ditty-api.php';

		// Add 3rd party files
		//require_once DITTY_DIR . 'includes/libs/scssphp/scss.inc.php';

		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once DITTY_DIR . 'includes/class-ditty-loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once DITTY_DIR . 'includes/class-ditty-i18n.php';
		
		// The class responsible for setting custom roles and capabalities.
		require_once DITTY_DIR . 'includes/class-ditty-roles.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once DITTY_DIR . 'includes/class-ditty-display.php';
		require_once DITTY_DIR . 'includes/class-ditty-display-item.php';
		require_once DITTY_DIR . 'includes/class-ditty-displays.php';
		require_once DITTY_DIR . 'includes/class-ditty-display-type.php';
		require_once DITTY_DIR . 'includes/class-ditty-display-type-ticker.php';
		require_once DITTY_DIR . 'includes/class-ditty-display-type-list.php';
		require_once DITTY_DIR . 'includes/class-ditty-editor.php';
		require_once DITTY_DIR . 'includes/class-ditty-errors.php';
		require_once DITTY_DIR . 'includes/class-ditty-extensions.php';
		require_once DITTY_DIR . 'includes/class-ditty-layout.php';
		require_once DITTY_DIR . 'includes/class-ditty-layouts.php';
		require_once DITTY_DIR . 'includes/class-ditty-item.php';
		require_once DITTY_DIR . 'includes/class-ditty-items.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-default.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-wp-editor.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-posts-lite.php';
		require_once DITTY_DIR . 'includes/class-ditty-singles.php';
		require_once DITTY_DIR . 'includes/class-ditty-wpml.php';
		
		require_once DITTY_DIR . 'src/blocks/ditty/index.php';
		
		if ( is_admin() ) {
			if ( ! class_exists( 'Ditty_Plugin_Updater' ) ) {
				require_once DITTY_DIR . 'includes/admin/Ditty_Plugin_Updater.php';
			}
			require_once DITTY_DIR . 'includes/admin/columns.php';
			require_once DITTY_DIR . 'includes/admin/export.php';
			require_once DITTY_DIR . 'includes/admin/notices.php';
			require_once DITTY_DIR . 'includes/admin/settings.php';
			//require_once DITTY_DIR . 'includes/admin/info.php';
		}
		
		// Possibly add Ditty News Ticker
		if ( ditty_news_ticker_enabled() ) {
			require_once DITTY_DIR . 'legacy/legacy.php';
		} else {
			require_once DITTY_DIR . 'includes/legacy-helpers.php';
		}

		$this->loader = new Ditty_Loader();
		
		do_action( 'ditty_loaded' );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ditty_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    3.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Ditty_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks that have global functionality
	 * of the plugin.
	 *
	 * @since    3.0
	 * @access   private
	 */
	private function define_global_hooks() {
		if ( is_ditty_dev() ) {
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'dev_enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $this, 'dev_enqueue_styles' );	
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'dev_enqueue_scripts' );
			$this->loader->add_action( 'wp_enqueue_scripts', $this, 'dev_enqueue_scripts' );
			$this->loader->add_action( 'admin_footer', $this, 'dev_enqueue_global_scripts', 20 );
			$this->loader->add_action( 'wp_footer', $this, 'dev_enqueue_global_scripts', 20 );
		} else {
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_styles' );	
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
			$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts' );
			$this->loader->add_action( 'admin_footer', $this, 'enqueue_global_scripts', 20 );
			$this->loader->add_action( 'wp_footer', $this, 'enqueue_global_scripts', 20 );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    3.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     3.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     3.0
	 * @return    Ditty_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     3.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Write to the log
	 *
	 * @since     3.0
	 * @return    null
	 */
	public function write_log( $log )  {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
	
	/**
	 * Register the stylesheets.
	 *
	 * @since    3.0.12
	 */
	public function enqueue_styles() {	
		wp_enqueue_style( 'ditty', DITTY_URL . 'includes/css/ditty.css', array(), $this->version, 'all' );
		wp_register_style( 'ditty-editor', DITTY_URL . 'includes/css/ditty-editor.css', array(), $this->version, 'all' );
		wp_register_style( 'ditty-admin', DITTY_URL . 'includes/css/ditty-admin.css', array(), $this->version, 'all' );
		
		wp_register_style( 'protip', DITTY_URL . 'includes/libs/protip/protip.min.css', false, '1.4.21', false );	
		wp_register_style( 'ion-rangeslider', DITTY_URL . 'includes/libs/ion.rangeSlider/css/ion.rangeSlider.min.css', false, '2.3.1', false );
		wp_register_style( 'jquery-minicolors', DITTY_URL . 'includes/libs/jquery-minicolors/jquery.minicolors.css', false, '2.3.5', false );
		
		$disable_fontawesome = ditty_settings( 'disable_fontawesome' );
		if ( ! is_admin() && ! $disable_fontawesome ) {
			wp_enqueue_style( 'ditty-fontawesome', DITTY_URL . '/includes/libs/fontawesome-6.2.0/css/all.css', false, '6.2.0', false );
		}
		
		if ( is_admin() ) {
			wp_enqueue_style( 'wp-codemirror' );
			wp_enqueue_style( 'protip' );	
			wp_enqueue_style( 'ion-rangeslider' );
			wp_enqueue_style( 'jquery-minicolors' );
			wp_enqueue_style( 'ditty-editor' );
			wp_enqueue_style( 'ditty-admin' );
			wp_enqueue_style( 'ditty-fontawesome', DITTY_URL . 'includes/libs/fontawesome-6.2.0/css/all.css', false, '6.2.0', false );
		}
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    3.0.14
	 */
	public function enqueue_scripts( $hook ) {
		global $ditty_scripts_enqueued;
		$min = WP_DEBUG ? '' : '.min';
		
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
		wp_register_script( 'ditty-editor-hooks', DITTY_URL . 'includes/js/ditty-editor-hooks.min.js', array( 'jquery' ), $this->version, true );
		
		wp_register_script( 'ditty', DITTY_URL . 'includes/js/ditty.min.js', array( 'jquery', 'jquery-effects-core', ), $this->version, true );
		if ( empty( $ditty_scripts_enqueued ) ) {
			wp_add_inline_script( 'ditty', 'const dittyVars = ' . json_encode( array(
				'ajaxurl'					=> admin_url( 'admin-ajax.php' ),
				'security'				=> wp_create_nonce( 'ditty' ),
				'mode'						=> WP_DEBUG ? 'development' : 'production',
				'strings' 				=> ditty_strings(),
				'adminStrings' 		=> is_admin() ? ditty_admin_strings() : false,
				'globals'					=> ditty_get_globals(),
				'updateIcon'			=> 'fas fa-sync-alt fa-spin',
				'updateInterval'	=> ( MINUTE_IN_SECONDS * ditty_settings( 'live_refresh' ) ),
				'editor'					=> array(
					'ditty_layouts_sass' => ditty_settings( 'ditty_layouts_sass' ),
				),
			) ), 'before' );
		}
		
		wp_register_script( 'ditty-slider', DITTY_URL . 'includes/js/class-ditty-slider' . $min . '.js', array( 'jquery', 'hammer' ), $this->version, true );
		wp_register_script( 'ditty-display-ticker', DITTY_URL . 'includes/js/class-ditty-display-ticker' . $min . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'ditty-display-list', DITTY_URL . 'includes/js/class-ditty-display-list' . $min . '.js', array( 'jquery', 'ditty-slider' ), $this->version, true );

		if ( is_admin() ) {
			
			// Make sure to enqueue the scripts in the admin
			wp_enqueue_script( 'ditty-display-ticker' );
			wp_enqueue_script( 'ditty-display-list' );
			
			wp_enqueue_script( 'ion-rangeslider' );
			wp_enqueue_script( 'jquery-minicolors' );
			wp_enqueue_script( 'protip' );
			wp_enqueue_script( 'ditty-fields' );
			wp_register_script( 'ditty-editor', DITTY_URL . 'includes/js/ditty-editor.min.js', array(
				'jquery',
				'protip',
				'iris',
				'jquery-form',
				'jquery-ui-core',
				'jquery-effects-core',
				'wp-codemirror',
				'ditty-slider',
				'ditty-fields',
			), $this->version, true );
			wp_enqueue_script( 'ditty-editor-hooks' );
			wp_register_script( 'ditty-admin', DITTY_URL . 'includes/js/ditty-admin.min.js', array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-sortable',
				'jquery-effects-core',
				'wp-i18n',
			), $this->version, true );
			
			
			if ( empty( $ditty_scripts_enqueued ) ) {
				wp_add_inline_script( 'ditty-admin', 'const dittyAdminVars = ' . json_encode( array(
					'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
					'security'			=> wp_create_nonce( 'ditty' ),
					'mode'					=> WP_DEBUG ? 'development' : 'production',
					'adminStrings' 	=> is_admin() ? ditty_admin_strings() : false,
					'updateIcon'		=> 'fas fa-sync-alt fa-spin',
				) ), 'before' );
			}

			if ( current_user_can( 'edit_dittys' ) || current_user_can( 'edit_ditty_layouts' ) ) {
				wp_enqueue_editor();
				wp_enqueue_code_editor(
					array(
						'type' => 'text/html'
					)
				);
				wp_enqueue_script( 'ditty-editor' );	
			}
			if ( current_user_can( 'manage_ditty_settings' ) ) {
				wp_enqueue_script( 'ditty-admin' );
			}
			
			// Disable autosave for Ditty posts
			if ( 'ditty' == get_post_type() ) {
				wp_enqueue_script( 'ditty' );
				wp_dequeue_script( 'autosave' );
			}	
		}
		
		// Ensure global scripts are being added
		if ( ! is_admin() ) {
			$global_ditty = ditty_settings( 'global_ditty' );
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
	 * Enqueue global scripts for any Ditty's displayed
	 *
	 * @since    3.0.28
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
	
	
	/**
	 * Register the stylesheets.
	 *
	 * @since    3.1
	 */
	public function dev_enqueue_styles() {	
		wp_enqueue_style( 'ditty', DITTY_URL . 'includes/css/ditty.css', array(), $this->version, 'all' );

		wp_enqueue_style(
			'ditty-editor', DITTY_URL . 'build/dittyEditor.css',
			['wp-components'],
			$this->version,
			'all'
		);
		// wp_enqueue_style(
		// 	'ditty', DITTY_URL . 'build/ditty.css',
		// 	[],
		// 	$this->version,
		// 	'all'
		// );
		// wp_enqueue_style(
		// 	'ditty-display-ticker',
		// 	DITTY_URL . 'build/displays/dittyDisplayTicker.css',
		// 	['ditty'],
		// 	$this->version,
		// 	'all'
		// );

		$disable_fontawesome = ditty_settings( 'disable_fontawesome' );
		if ( ! is_admin() && ! $disable_fontawesome ) {
			wp_enqueue_style( 'ditty-fontawesome', DITTY_URL . 'includes/libs/fontawesome-6.2.0/css/all.css', false, '6.2.0', false );
		}
		
		if ( is_admin() ) {
			//wp_enqueue_style( 'ditty-admin', DITTY_URL . 'includes/css/ditty-admin.css', array(), $this->version, 'all' );
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
		$min = WP_DEBUG ? '' : '.min';
		wp_register_script( 'hammer', DITTY_URL . 'includes/libs/hammer.min.js', array( 'jquery' ), '2.0.8.1', true );
		wp_register_script( 'ditty-slider', DITTY_URL . 'includes/js/class-ditty-slider' . $min . '.js', array( 'jquery', 'hammer' ), $this->version, true );
		wp_register_script( 'ditty-helpers', DITTY_URL . 'includes/js/partials/helpers.js', [], $this->version, true );
		wp_register_script( 'ditty-display-ticker', DITTY_URL . 'includes/js/class-ditty-display-ticker' . $min . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'ditty-display-list', DITTY_URL . 'includes/js/class-ditty-display-list' . $min . '.js', array( 'jquery', 'ditty-slider' ), $this->version, true );

		wp_register_script( 'ditty',
			DITTY_URL . 'build/ditty.js',
			['wp-hooks', 'jquery-effects-core', 'jquery'],
			$this->version,
			true
		);
		// wp_register_script(
		// 	'ditty-display-ticker',
		// 	DITTY_URL . 'build/displays/dittyDisplayTicker.js',
		// 	['ditty'],
		// 	$this->version,
		// 	true
		// );
		// wp_register_script(
		// 	'ditty-display-list',
		// 	DITTY_URL . 'build/displays/dittyDisplayList.js',
		// 	['ditty'],
		// 	$this->version,
		// 	true
		// );
		// wp_register_script(
		// 	'ditty-display-grid',
		// 	DITTY_URL . 'build/displays/dittyDisplayGrid.js',
		// 	['ditty'],
		// 	$this->version,
		// 	true
		// );
		wp_register_script( 'ditty-editor',
			DITTY_URL . 'build/dittyEditor.js',
			['wp-element', 'wp-components', 'wp-hooks', 'lodash', 'ditty'],
			$this->version,
			true
		);
		wp_enqueue_script(
			'ditty-scripts',
			DITTY_URL . 'build/dittyScripts.js',
			['ditty', 'wp-element', 'wp-components'],
			$this->version,
			true
		);
		if ( empty( $ditty_scripts_enqueued ) ) {
			wp_add_inline_script( 'ditty-editor', 'const dittyEditorVars = ' . json_encode( array(
				'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
				'security'			=> wp_create_nonce( 'ditty' ),
				'mode'					=> WP_DEBUG ? 'development' : 'production',
				'userId'				=> get_current_user_id(),
				'siteUrl'				=> site_url(),
				'displays'			=> Ditty()->editor->display_data(),
				'layouts'				=> Ditty()->editor->layout_data(),
				'itemTypes'			=> array_values( ditty_item_types() ),
				'displayTypes'	=> array_values( ditty_display_types() ),
			) ), 'before' );
		}
	
		if ( is_admin() ) {

			// Disable autosave for Ditty posts
			if ( ditty_editing() ) {
				wp_dequeue_script( 'autosave' );	
				wp_enqueue_script( 'ditty' );
				wp_enqueue_script( 'ditty-helpers' );
				wp_enqueue_script( 'ditty-display-ticker' );
				wp_enqueue_script( 'ditty-display-list' );
				//wp_enqueue_script( 'ditty-display-grid' );
				wp_enqueue_script( 'ditty-editor' );
				wp_enqueue_script( 'ditty-item-type' );
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
