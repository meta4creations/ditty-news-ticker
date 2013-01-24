<?php
/**
 * The global settings
 *
 * @package Ditty News Ticker
 */
 
 


add_action( 'admin_menu', 'mtphr_dnt_settings_menu', 9 );
/**
 * Create the settings page
 *
 * @since 1.0.0
 */
function mtphr_dnt_settings_menu() {

	add_submenu_page(
		'edit.php?post_type=ditty_news_ticker',		// The ID of the top-level menu page to which this submenu item belongs
		__( 'Settings', 'ditty-news-ticker' ),			// The value used to populate the browser's title bar when the menu page is active
		__( 'Settings', 'ditty-news-ticker' ),			// The label of this submenu item displayed in the menu
		'administrator',													// What roles are able to access this submenu item
		'mtphr_dnt_settings',											// The ID used to represent this submenu item
		'mtphr_dnt_settings_display'							// The callback function used to render the options for this submenu item
	);
}




add_action( 'admin_init', 'mtphr_dnt_initialize_settings' );
/**
 * Setup the custom options for the settings page
 *
 * @since 1.0.0
 */
function mtphr_dnt_initialize_settings() {

	/**
	 * General options sections
	 */
	$settings = array();
	
	$settings['css'] = array(
		'title' => __( 'Custom CSS', 'ditty-news-ticker' ),
		'type' => 'textarea',
		'rows' => 20,
		'description' => __( 'Custom CSS will be added to the head of each page that includes a Ditty News Ticker.', 'ditty-news-ticker' )
	);
	if( false == get_option('mtphr_dnt_general_settings') ) {	
		add_option( 'mtphr_dnt_general_settings' );
	}
	
	/* Register the general options */
	add_settings_section(
		'mtphr_dnt_general_settings_section',				// ID used to identify this section and with which to register options
		__( '&nbsp;', 'ditty-news-ticker' ),			// Title to be displayed on the administration page
		false,			// Callback used to render the description of the section
		'mtphr_dnt_general_settings'								// Page on which to add this section of options
	);

	if( is_array($settings) ) {
		foreach( $settings as $id => $setting ) {
			$setting['option'] = 'mtphr_dnt_general_settings';
			$setting['option_id'] = $id;
			$setting['id'] = 'mtphr_dnt_general_settings['.$id.']';
			add_settings_field( $setting['id'], $setting['title'], 'mtphr_dnt_settings_callback', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_section', $setting);
		}
	}
	
	// Register the fields with WordPress
	register_setting( 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings' );
	
	/**
	 * License options sections
	 */
	$license_settings = array();
	
	/*
$license_settings['test'] = array(
		'title' => __( 'Test', 'ditty-news-ticker' ),
		'type' => 'text',
		'default' => 'sidebar-right'
	);
*/
	
	if( false == get_option('mtphr_dnt_license_settings') ) {	
		add_option( 'mtphr_dnt_license_settings' );
	}
	
	// Register the style options
	add_settings_section(
		'mtphr_dnt_license_settings_section',					// ID used to identify this section and with which to register options
		__( '&nbsp;', 'ditty-news-ticker' ),		// Title to be displayed on the administration page
		false,		// Callback used to render the description of the section
		'mtphr_dnt_license_settings'							// Page on which to add this section of options
	);
	
	$license_settings = apply_filters( 'mtphr_dnt_license_settings', $license_settings );
	
	if( is_array($license_settings) ) {
		foreach( $license_settings as $id => $setting ) {
			$setting['option'] = 'mtphr_dnt_license_settings';
			$setting['option_id'] = $id;
			$setting['id'] = 'mtphr_dnt_license_settings['.$id.']';
			add_settings_field( $setting['id'], $setting['title'], 'mtphr_dnt_settings_callback', 'mtphr_dnt_license_settings', 'mtphr_dnt_license_settings_section', $setting);
		}
	}
	
	// Register the fields with WordPress
	register_setting( 'mtphr_dnt_license_settings', 'mtphr_dnt_license_settings' );
	
}




/**
 * Render the theme options page
 *
 * @since 1.0.0
 */
function mtphr_dnt_settings_display( $active_tab = null ) {
	?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Ditty News Ticker Settings', 'ditty-news-ticker' ); ?></h2>
		<?php settings_errors(); ?>
		
		<?php 
		if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else if( $active_tab == 'license_settings' ) {
			$active_tab = 'license_settings';
		} else {
			$active_tab = 'general_settings';
		}
		?>

		<h2 class="nav-tab-wrapper">
			<a href="?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'ditty-news-ticker' ); ?></a>
			<!-- <a href="?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=license_settings" class="nav-tab <?php echo $active_tab == 'license_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Extension Licenses', 'ditty-news-ticker' ); ?></a> -->
		</h2>

		<form method="post" action="options.php">
			<?php
				if( $active_tab == 'license_settings' ) {
				
					settings_fields( 'mtphr_dnt_license_settings' );
					do_settings_sections( 'mtphr_dnt_license_settings' );
				
				} else {
					
					settings_fields( 'mtphr_dnt_general_settings' );
					do_settings_sections( 'mtphr_dnt_general_settings' );
				}
	
				submit_button();
			?>
		</form>
		
	</div><!-- /.wrap -->
	<?php
}




/**
 * General options section callback
 *
 * @since 1.0.0
 */
function mtphr_dnt_general_settings_callback() {
	echo '<p>'.__( 'Add global settings to your news tickers.', 'ditty-news-ticker' ).'</p>';
	echo '<p>'.__( 'Use the Custom CSS textarea to set global or individual styles to each of your tickers.', 'ditty-news-ticker' ).'</p>';
}

/**
 * License options section callback
 *
 * @since 1.0.0
 */
function mtphr_dnt_license_settings_callback() {
	echo '<p>'.__( 'Add the licenses for each of your extensions.', 'ditty-news-ticker' ).'</p>';
}




/**
 * The custom field callback.
 *
 * @since 1.0.0
 */ 
function mtphr_dnt_settings_callback( $args ) {
	
	// First, we read the options collection
	if( isset($args['option']) ) {
		$options = get_option( $args['option'] );
		$value = isset( $options[$args['option_id']] ) ? $options[$args['option_id']] : '';
	} else {
		$value = get_option( $args['id'] );
	}	
	if( $value == '' && isset($args['default']) ) {
		$value = $args['default'];
	}
	if( isset($args['type']) ) {
	
		echo '<div class="mtphr-dnt-metaboxer-field mtphr-dnt-metaboxer-'.$args['type'].'">';
		
		// Call the function to display the field
		if ( function_exists('mtphr_dnt_metaboxer_'.$args['type']) ) {
			call_user_func( 'mtphr_dnt_metaboxer_'.$args['type'], $args, $value );
		}
		
		echo '<div>';
	}
	
	// Add a descriptions
	if( isset($args['description']) ) {
		echo '<span class="description"><small>'.$args['description'].'</small></span>';
	}
}
