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
 * @since 1.0.6
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
		'',																					// Title to be displayed on the administration page
		'mtphr_dnt_general_settings_callback',			// Callback used to render the description of the section
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
}




/**
 * Render the theme options page
 *
 * @since 1.0.6
 */
function mtphr_dnt_settings_display( $active_tab = null ) {
	?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Ditty News Ticker Settings', 'ditty-news-ticker' ); ?></h2>
		<?php settings_errors(); ?>
		
		<?php
		$tabs = mtphr_dnt_settings_tabs();
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		?>
		
		<ul style="margin-bottom:20px;" class="subsubsub">
			<?php
			$num_tabs = count($tabs);
			$count = 0;
			foreach( $tabs as $key=>$tab ) {
				$count++;
				$current = ($key==$active_tab) ? 'class="current"' : '';
				$sep = ($count!=$num_tabs) ? ' |' : '';
				echo '<li><a href="?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab='.$key.'" '.$current.'>'.ucfirst($key).'</a>'.$sep.'</li>';
			}
			?>
		</ul>
		
		<br class="clear" />

		<form method="post" action="options.php">
			<?php
			settings_fields( $tabs[$active_tab] );
			do_settings_sections( $tabs[$active_tab] );
			submit_button();
			?>
		</form>
		
	</div><!-- /.wrap -->
	<?php
}




/**
 * General options section callback
 *
 * @since 1.0.6
 */
function mtphr_dnt_general_settings_callback() {
	?>
	<div style="margin-bottom: 20px;">
		<h4 style="margin-top:0;"><?php _e( 'The global settings to your news tickers.', 'ditty-news-ticker' ); ?></h4>
	</div>
	<?php
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
