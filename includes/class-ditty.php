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
	 * Ditty posts object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Posts
	 */
	public $posts;
	
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
	 * Ditty error object.
	 *
	 * @since    3.0
	 * @access   public
	 * @var      object    Ditty_Errors
	 */
	public $errors;
	
	
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
			
			self::$instance->db_items			= new Ditty_DB_Items();
			self::$instance->db_item_meta	= new Ditty_DB_Item_Meta();
			self::$instance->displays			= new Ditty_Displays();
			self::$instance->editor				= new Ditty_Editor();
			self::$instance->errors				= new Ditty_Errors();
			self::$instance->extensions		= new Ditty_Extensions();
			self::$instance->layouts			= new Ditty_Layouts();
			self::$instance->posts				= new Ditty_Posts();
			self::$instance->items				= new Ditty_Items();	
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
	 * @since    3.0
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
		require_once DITTY_DIR . 'includes/fields/ditty-field-group.php';
		require_once DITTY_DIR . 'includes/fields/ditty-field-html.php';
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
		require_once DITTY_DIR . 'includes/layout-tag-helpers.php';
		require_once DITTY_DIR . 'includes/layout-tag-hooks.php';
		require_once DITTY_DIR . 'includes/post-types.php';
		require_once DITTY_DIR . 'includes/upgrades.php';
		
		// Add database files
		require_once DITTY_DIR . 'includes/class-ditty-db.php';
		require_once DITTY_DIR . 'includes/class-ditty-db-items.php';
		require_once DITTY_DIR . 'includes/class-ditty-db-item-meta.php';

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
		require_once DITTY_DIR . 'includes/class-ditty-layout-type.php';
		require_once DITTY_DIR . 'includes/class-ditty-layout-type-default.php';
		require_once DITTY_DIR . 'includes/class-ditty-layout-type-image.php';
		require_once DITTY_DIR . 'includes/class-ditty-item.php';
		require_once DITTY_DIR . 'includes/class-ditty-items.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-default.php';
		require_once DITTY_DIR . 'includes/class-ditty-item-type-wp-editor.php';
		require_once DITTY_DIR . 'includes/class-ditty-posts.php';
		
		require_once DITTY_DIR . 'blocks/ditty-block/index.php';
		
		if ( is_admin() ) {
			if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				require_once DITTY_DIR . 'includes/admin/EDD_SL_Plugin_Updater.php';
			}
			require_once DITTY_DIR . 'includes/admin/columns.php';
			require_once DITTY_DIR . 'includes/admin/settings.php';
			require_once DITTY_DIR . 'includes/admin/upgrade_details.php';
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
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_styles' );	
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $this, 'enqueue_block_editor_assets' );
		$this->loader->add_action( 'enqueue_block_assets', $this, 'enqueue_block_assets' );
		$this->loader->add_action( 'admin_footer', $this, 'enqueue_global_scripts', 20 );
		$this->loader->add_action( 'wp_footer', $this, 'enqueue_global_scripts', 20 );
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
	 * @since    3.0
	 */
	public function enqueue_styles() {	
		wp_enqueue_style( 'ditty', DITTY_URL . 'includes/css/ditty.css', array(), $this->version, 'all' );

		if ( current_user_can( 'edit_dittys' ) || current_user_can( 'edit_ditty_layouts' ) ) {
			wp_enqueue_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.15.3/css/all.css', false, '5.15.3', false );
			wp_enqueue_style( 'wp-codemirror' );
			wp_enqueue_style( 'protip', DITTY_URL . 'includes/libs/protip/protip.min.css', false, '1.4.21', false );	
			wp_enqueue_style( 'ion-rangeslider', DITTY_URL . 'includes/libs/ion.rangeSlider/css/ion.rangeSlider.min.css', false, '2.3.1', false );
			wp_enqueue_style( 'jquery-minicolors', DITTY_URL . 'includes/libs/jquery-minicolors/jquery.minicolors.css', false, '2.3.5', false );
			wp_enqueue_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
		}
		if ( is_admin() ) {
			wp_enqueue_style( 'ditty-admin', DITTY_URL . 'includes/css/ditty-admin.css', array(), $this->version, 'all' );
		} else {
			
			// Add scripts for the global Dittys
			$global_ditty = ditty_settings( 'global_ditty' );
			if ( is_array( $global_ditty ) && count( $global_ditty ) > 0 ) {
				foreach ( $global_ditty as $i => $global_ditty ) {
					if ( 'publish' === get_post_status( $global_ditty['ditty'] ) ) {
						ditty_add_scripts( $global_ditty['ditty'], $global_ditty['display'] );
					}
				}
			}
		}
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    3.0
	 */
	public function enqueue_scripts( $hook ) {
		$min = WP_DEBUG ? '' : '.min';
		
		wp_register_script( 'hammer', DITTY_URL . 'includes/libs/hammer.min.js', array( 'jquery' ), '2.0.8', true );
		wp_register_script( 'protip', DITTY_URL . 'includes/libs/protip/protip.min.js', array( 'jquery' ), '1.4.21', true );
		wp_register_script( 'ditty', DITTY_URL . 'includes/js/ditty.min.js', array( 'jquery', 'jquery-effects-core', ), $this->version, true );
		wp_add_inline_script( 'ditty', 'const dittyVars = ' . json_encode( array(
			'ajaxurl'					=> admin_url( 'admin-ajax.php' ),
			'security'				=> wp_create_nonce( 'ditty' ),
			'strings' 				=> ditty_strings(),
			'adminStrings' 		=> is_admin() ? ditty_admin_strings() : false,
			'globals'					=> ditty_get_globals(),
			'updateIcon'			=> 'fas fa-sync-alt fa-spin',
			'updateInterval'	=> ( MINUTE_IN_SECONDS * ditty_settings( 'live_refresh' ) ),
			'editor'					=> array(
				'ditty_layouts_sass' => ditty_settings( 'ditty_layouts_sass' ),
			),
		) ), 'before' );
		
		wp_register_script( 'ditty-slider', DITTY_URL . 'includes/js/class-ditty-slider' . $min . '.js', array( 'jquery', 'hammer' ), $this->version, true );
		wp_register_script( 'ditty-display-ticker', DITTY_URL . 'includes/js/class-ditty-display-ticker' . $min . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'ditty-display-list', DITTY_URL . 'includes/js/class-ditty-display-list' . $min . '.js', array( 'jquery', 'ditty-slider' ), $this->version, true );
		
		wp_enqueue_script( 'ion-rangeslider', DITTY_URL . 'includes/libs/ion.rangeSlider/js/ion.rangeSlider.min.js', array( 'jquery' ), '2.3.1', true );
		wp_enqueue_script( 'jquery-minicolors', DITTY_URL . 'includes/libs/jquery-minicolors/jquery.minicolors.min.js', array( 'jquery' ), '2.3.5', true );
		wp_enqueue_script( 'ditty-fields', DITTY_URL . 'includes/fields/js/ditty-fields.min.js', array(
			'jquery',
			'protip',
			'jquery-effects-core',
			'wp-codemirror',
			'ion-rangeslider',
			'jquery-minicolors',
		), $this->version, true );
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
		wp_register_script( 'ditty-admin', DITTY_URL . 'includes/js/ditty-admin.min.js', array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-sortable',
			'wp-i18n',
		), $this->version, true );
		wp_add_inline_script( 'ditty-admin', 'const dittyAdminVars = ' . json_encode( array(
			'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
			'security'			=> wp_create_nonce( 'ditty' ),
			'adminStrings' 	=> is_admin() ? ditty_admin_strings() : false,
			'updateIcon'		=> 'fas fa-sync-alt fa-spin',
		) ), 'before' );
		
		// wp_localize_script( 'ditty-admin', 'dittyAdminVars', array(
		// 		'ajaxurl'					=> admin_url( 'admin-ajax.php' ),
		// 		'security'				=> wp_create_nonce( 'ditty' ),
		// 		'adminStrings' 	=> is_admin() ? ditty_admin_strings() : false,
		// 		'update_icon'			=> 'fas fa-sync-alt fa-spin',
		// 	)
		// );
		
		
		if ( is_admin() ) {
			
			// Make sure to enqueue the scripts in the admin
			wp_enqueue_script( 'ditty-display-ticker' );
			wp_enqueue_script( 'ditty-display-list' );

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
	}
	
	/**
	 * Enqueue block editor only JavaScript and CSS
	 *
	 * @since    3.0
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_style(
			'ditty-blocks-editor',
			DITTY_URL . 'includes/css/blocks.editor.css',
			[ ],
			$this->version,
		);

		wp_enqueue_script(
			'ditty-blocks-editor',
			DITTY_URL . 'includes/js/editor.blocks.js',
			[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor' ],
			$this->version,
			true,
		);
		wp_add_inline_script( 'ditty-blocks-editor', 'const dittyBlocksEditorVars = ' . json_encode( array(
			'displays' => Ditty()->displays->get_displays_data(),
		) ), 'before' );
	}
	
	/**
	 * Enqueue front end and editor JavaScript and CSS assets
	 *
	 * @since    3.0
	 */
	public function enqueue_block_assets() {
		wp_enqueue_style(
			'ditty-blocks',
			DITTY_URL . 'includes/css/blocks.style.css',
			[],
			$this->version
		);
		
		wp_enqueue_script(
			'ditty-blocks',
			DITTY_URL . 'includes/js/frontend.blocks.js',
			[],
			$this->version,
			true,
		);
	}
	
	/**
	 * Enqueue global scripts for any Ditty's displayed
	 *
	 * @since    3.0
	 */
	public function enqueue_global_scripts() {
		global $ditty_item_scripts;
		if ( empty( $ditty_item_scripts ) ) {
			$ditty_item_scripts = array();
		}
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
			wp_print_scripts( 'ditty' );
			foreach ( $ditty_display_scripts as $i => $ditty_display_script ) {
				wp_print_scripts( "ditty-display-{$ditty_display_script}" );
			}
		}
	}

}
