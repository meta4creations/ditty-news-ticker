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
	
	public $api;
	public $db_items;
	public $db_item_meta;
	public $displays;
	public $editor;
	public $errors;
	public $extensions;
	public $layouts;
	public $settings;
	public $scripts;
  public $shortcodes;
	public $singles;
  //public $render;
  public $translations;

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
			self::$instance->version 			= ( defined( 'DITTY_DEVELOPMENT' ) && DITTY_DEVELOPMENT ) ? time() : DITTY_VERSION;
			
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
			self::$instance->scripts			= new Ditty_Scripts();
			self::$instance->settings			= new Ditty_Settings();
      self::$instance->shortcodes		= new Ditty_Shortcodes();
			self::$instance->singles			= new Ditty_Singles();
      //self::$instance->render				= new Ditty_Render();	
      self::$instance->translations	= new Ditty_Translations();	
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

		require_once DITTY_DIR . 'includes/class-ditty-scripts.php';

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
		require_once DITTY_DIR . 'includes/blocks.php';
    require_once DITTY_DIR . 'includes/deprecated.php';
		require_once DITTY_DIR . 'includes/helpers.php';
		require_once DITTY_DIR . 'includes/hooks.php';
		require_once DITTY_DIR . 'includes/layout-tag-helpers.php';
		require_once DITTY_DIR . 'includes/layout-tag-hooks.php';
		require_once DITTY_DIR . 'includes/layout-tag-hooks-default.php';
		require_once DITTY_DIR . 'includes/layout-tag-hooks-posts.php';
		require_once DITTY_DIR . 'includes/layout-templates.php';
		require_once DITTY_DIR . 'includes/layout-tags.php';
		require_once DITTY_DIR . 'includes/post-types.php';
		require_once DITTY_DIR . 'includes/upgrades.php';
		require_once DITTY_DIR . 'includes/widget.php';

    // Translator files
		require_once DITTY_DIR . 'includes/translators/wpml.php';
		
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
		require_once DITTY_DIR . 'includes/class-ditty-displays.php';
		require_once DITTY_DIR . 'includes/class-ditty-display-type.php';
		require_once DITTY_DIR . 'includes/class-ditty-display-type-ticker.php';
		require_once DITTY_DIR . 'includes/class-ditty-display-type-list.php';
		require_once DITTY_DIR . 'includes/class-ditty-editor.php';
		require_once DITTY_DIR . 'includes/class-ditty-errors.php';
		require_once DITTY_DIR . 'includes/class-ditty-extensions.php';

		require_once DITTY_DIR . 'includes/class-ditty-display-item.php';
		require_once DITTY_DIR . 'includes/class-ditty-layouts.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-default.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-wp-editor.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-html.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-posts-lite.php';
		require_once DITTY_DIR . 'includes/class-ditty-settings.php';
    require_once DITTY_DIR . 'includes/class-ditty-shortcodes.php';
		require_once DITTY_DIR . 'includes/class-ditty-singles.php';
    require_once DITTY_DIR . 'includes/class-ditty-translations.php';

    require_once DITTY_DIR . 'includes/class-ditty-render.php';

		if ( is_admin() ) {
			if ( ! class_exists( 'Ditty_Plugin_Updater' ) ) {
				require_once DITTY_DIR . 'includes/admin/Ditty_Plugin_Updater.php';
			}
			require_once DITTY_DIR . 'includes/admin/columns.php';
			require_once DITTY_DIR . 'includes/admin/export.php';
      require_once DITTY_DIR . 'includes/admin/marketing.php';
			require_once DITTY_DIR . 'includes/admin/notices.php';
		}
		
		// Possibly add Ditty News Ticker
		if ( ditty_news_ticker_enabled() ) {
			require_once DITTY_DIR . 'legacy/legacy.php';
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
		$this->loader->add_action( 'init', $this, 'register_ditty_styles' );
		$this->loader->add_action( 'init', $this, 'register_ditty_scripts' );
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
	 * Register Ditty scripts.
	 *
	 * @since 3.1
	 * @return void
	 */
	public function register_ditty_styles() {
		if ( ! function_exists( 'ditty_register_style' ) ) {
			return;
		}
		ditty_register_style( 'display', [
				'ditty-displays',
				DITTY_URL . 'build/dittyDisplays.css',
				DITTY_DIR . 'build/dittyDisplays.css',
				[],
				$this->version
			]
		);
	}

	/**
	 * Register Ditty scripts.
	 *
	 * @since 3.1
	 * @return void
	 */
	public function register_ditty_scripts() {
		if ( ! function_exists( 'ditty_register_script' ) ) {
			return;
		}
		$min = WP_DEBUG ? '' : '.min';
		ditty_register_script( 'display', [
				'ditty-display-ticker',
				DITTY_URL . 'build/dittyDisplayTicker.js',
				DITTY_DIR . 'build/dittyDisplayTicker.js',
				[ 'jquery', 'ditty-helpers' ],
				$this->version
			]
		);
		ditty_register_script( 'display', [
				'ditty-display-list',
				DITTY_URL . 'build/dittyDisplayList.js',
				DITTY_DIR . 'build/dittyDisplayList.js',
				array( 'jquery', 'ditty-slider', 'ditty-helpers' ),
				$this->version
			]
		);
		ditty_register_script( 'editor', [
				'dittyScripts',
				DITTY_URL . 'build/dittyScripts.js',
				DITTY_DIR . 'build/dittyScripts.js',
				[],
				$this->version,
			]
		);
	}

}
