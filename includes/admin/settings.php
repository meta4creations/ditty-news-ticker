<?php
	
/**
 * Register settings pages
 *
 * @since    3.0
*/
function ditty_settings_pages() {
	add_submenu_page(
		'edit.php?post_type=ditty',		// The ID of the top-level menu page to which this submenu item belongs
		__( 'Settings', 'ditty-news-ticker' ),		// The value used to populate the browser's title bar when the menu page is active
		__( 'Settings', 'ditty-news-ticker' ),		// The label of this submenu item displayed in the menu
		'manage_ditty_settings',			// What roles are able to access this submenu item
		'ditty_settings',							// The ID used to represent this submenu item
		'ditty_settings_display'			// The callback function used to render the options for this submenu item
	);
}
add_action( 'admin_menu', 'ditty_settings_pages', 5 );


/**
 * Render the settings page
 *
 * @since    3.0
*/
function ditty_settings_display() {
	?>
	<div id="ditty-page" class="wrap">
		
		<div id="ditty-page__header">
			<h2><?php _e( 'Ditty Settings', 'ditty-news-ticker' ); ?></h2>
		</div>
		
		<div id="ditty-page__content">
			<div id="ditty-settings">
	
				<?php
				$init_panel = isset( $_GET['tab'] ) ? $_GET['tab'] : false;
				$settings = apply_filters( 'ditty_settings_tabs', array(
					'general' => array(
						'icon'			=> 'fas fa-cog',
						'label' 		=> __( 'General', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_general',
					),
					'global_ditty' => array(
						'icon'			=> 'fas fa-globe-americas',
						'label' 		=> __( 'Global Ditty', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_global_ditty',
					),
					'layout_defaults' => array(
						'icon'			=> 'fas fa-pencil-ruler',
						'label' 		=> __( 'Layout Defaults', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_variation_defaults',
					),
					'layout_templates' => array(
						'icon'			=> 'fas fa-pencil-ruler',
						'label' 		=> __( 'Layout Templates', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_layout_templates',
					),
					'display_templates' => array(
						'icon'			=> 'fas fa-tablet-alt',
						'label' 		=> __( 'Display Templates', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_display_templates',
					),
					'advanced' => array(
						'icon'			=> 'fas fa-pencil-alt',
						'label' 		=> __( 'Advanced', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_advanced',
					),	
				) );
				?>

				<div class="ditty-settings__tabs">
					<?php
					if ( is_array( $settings ) && count( $settings ) > 0 ) {
						foreach ( $settings as $slug => $setting ) {
							echo '<a href="#" class="ditty-settings__tab ditty-settings__tab--' . $slug . '" data-panel="' . $slug . '">';
								echo '<i class="' . $setting['icon'] . '"></i>';
								echo '<span>' . $setting['label'] . '</span>';
							echo '</a>';
						}
					}
					?>
				</div>
				
				<form class="ditty-settings__form">
					<div class="ditty-settings__header">
						<a href="#" class="ditty-button ditty-button--primary ditty-settings__save"><?php echo ditty_admin_strings( 'settings_save' ); ?></a>
					</div>
					<div class="ditty-notification-bar">
						<div class="ditty-notification ditty-notification--updated"><?php echo ditty_admin_strings( 'settings_updated' ); ?></div>
						<div class="ditty-notification ditty-notification--warning"><?php echo ditty_admin_strings( 'settings_changed' ); ?></div>
						<div class="ditty-notification ditty-notification--error"><?php echo ditty_admin_strings( 'settings_error' ); ?></div>
					</div>
					<div class="ditty-settings__panels" data-init_panel="<?php echo $init_panel; ?>">
						<?php
						if ( is_array( $settings ) && count( $settings ) > 0 ) {
							foreach ( $settings as $slug => $setting ) {
								?>
								<div class="ditty-settings__panel ditty-settings__panel--<?php echo $slug; ?>" data-slide_id="<?php echo $slug; ?>" data-slide_cache="true">	
									<?php
									if ( isset( $setting['fields'] ) && function_exists( $setting['fields'] ) ) {
										call_user_func( $setting['fields'] );
									}
									?>
								</div>
								<?php
							}
						}
						?>
					</div>
					<div class="ditty-settings__footer">
						<a href="#" class="ditty-button ditty-button--primary ditty-settings__save"><?php echo ditty_admin_strings( 'settings_save' ); ?></a>
					</div>
					<div class="ditty-updating-overlay"></div>
				</form>
			</div>
		</div>
	</div><!-- /.wrap -->
	<?php
}

/**
 * Setup the general settings fields
 *
 * @since    3.0  
*/
function ditty_settings_general() {	
	$fields = array(
		'heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> __( 'General Settings', 'ditty-news-ticker' ),
		),
		'live_refresh' => array(
			'type' 		=> 'number',
			'id' 			=> 'live_refresh',
			'name' 		=> __( 'Live Refresh Rate', 'ditty-news-ticker' ),
			'after' 	=> __( 'Minute(s)', 'ditty-news-ticker' ),
			'desc'		=> __( 'Set the live update refresh interval for your Ditty.', 'ditty-news-ticker' ),
			'std' 		=> ditty_settings( 'live_refresh' ),
		),
		// 'notification_email' => array(
		// 	'type' 				=> 'text',
		// 	'id' 					=> 'notification_email',
		// 	'name' 				=> __( 'Notification Email', 'ditty-news-ticker' ),
		// 	'placeholder'	=> __( 'Add a notification email', 'ditty-news-ticker' ),
		// 	'sanitize'		=> 'email',
		// 	'atts'				=> array(
		// 		'type' => 'email',
		// 	),
		// 	'std' 				=> ditty_settings( 'notification_email' ),
		// ),
		'ditty_layout_ui' => array(
			'type' 				=> 'radio',
			'id' 					=> 'ditty_layout_ui',
			'name' 				=> __( 'Layout Posts', 'ditty-news-ticker' ),
			'desc' 				=> __( 'Edit Layouts directly as post types.', 'ditty-news-ticker' ),
			'inline'			=> true,
			'options'			=> array(
				'disabled'	=> __( 'Disabled', 'ditty-news-ticker' ),
				'enabled'		=> __( 'Enabled', 'ditty-news-ticker' ),
			),
			'std' 				=> ditty_settings( 'ditty_layout_ui' ),
		),
		'ditty_display_ui' => array(
			'type' 				=> 'radio',
			'id' 					=> 'ditty_display_ui',
			'name' 				=> __( 'Ditty Display Posts', 'ditty-news-ticker' ),
			'desc' 				=> __( 'Edit Ditty Displays directly as post types.', 'ditty-news-ticker' ),
			'inline'			=> true,
			'options'			=> array(
				'disabled'	=> __( 'Disabled', 'ditty-news-ticker' ),
				'enabled'		=> __( 'Enabled', 'ditty-news-ticker' ),
			),
			'std' 				=> ditty_settings( 'ditty_display_ui' ),
		),
	);
	ditty_fields( $fields );
}

/**
 * Setup the layouts fields
 *
 * @since    3.0  
*/
function ditty_settings_variation_defaults() {	
	$fields = array(
		'heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> __( 'Variation Defaults', 'ditty-news-ticker' ),
		),
		'layout_variation_defaults' => array(
			'type' 		=> 'html',
			'id' 			=> 'layout_variation_defaults',
			'name' 		=> __( 'Layout Variation Defaults', 'ditty-news-ticker' ),
			'std' 		=> Ditty()->layouts->variation_defaults(),
		),
	);
	ditty_fields( $fields );
}

/**
 * Setup the layout templates fields
 *
 * @since    3.0  
*/
function ditty_settings_layout_templates() {	
	$fields = array(
		'heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> __( 'Layout Templates', 'ditty-news-ticker' ),
		),
		'layout_templates' => array(
			'type' 		=> 'html',
			'id' 			=> 'layout_templates',
			'name' 		=> __( 'Layout Templates', 'ditty-news-ticker' ),
			'std' 		=> Ditty()->layouts->layout_templates_list(),
		),
	);
	ditty_fields( $fields );
}

/**
 * Setup the display templates fields
 *
 * @since    3.0  
*/
function ditty_settings_display_templates() {	
	$fields = array(
		'heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> __( 'Display Defaults', 'ditty-news-ticker' ),
		),
		'display_templates' => array(
			'type' 		=> 'html',
			'id' 			=> 'display_templates',
			'name' 		=> __( 'Default Displays', 'ditty-news-ticker' ),
			'std' 		=> Ditty()->displays->display_templates_list(),
		),
	);
	ditty_fields( $fields );
}

/**
 * Setup the editor settings fields
 *
 * @since    3.0  
*/
function ditty_settings_advanced() {	
	$fields = array(
		'heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> __( 'Advanced Settings', 'ditty-news-ticker' ),
		),
		'ditty_wizard' => array(
			'type' 		=> 'radio',
			'id' 			=> 'ditty_wizard',
			'name' 		=> __( 'Ditty Wizard', 'ditty-news-ticker' ),
			'desc' 		=> __( "The Ditty Wizard helps you set up your new Dittys with a step by step guide.", 'ditty-news-ticker' ),
			'options'	=> array(
				'enabled' 	=> __( 'Enabled', 'ditty-news-ticker' ),
				'disabled' 	=> __( 'Disabled', 'ditty-news-ticker' ),
			),
			'inline'	=> true,
			'std' 		=> ditty_settings( 'ditty_wizard' ),
		),
		'disable_fontawesome' => array(
			'type' 				=> 'checkbox',
			'id' 					=> 'disable_fontawesome',
			'name' 				=> __( 'Font Awesome', 'ditty-news-ticker' ),
			'label' 			=> __( 'Disable Font Awesome from loading on the front-end', 'ditty-news-ticker' ),
			'desc' 				=> __( 'This will disable the rendering of certain icons used in default Layouts and Layout tags.', 'ditty-news-ticker' ),
			'std' 				=> ditty_settings( 'disable_fontawesome' ),
		),
		'ditty_news_ticker' => array(
			'type' 				=> 'checkbox',
			'id' 					=> 'ditty_news_ticker',
			'name' 				=> __( 'Ditty News Ticker', 'ditty-news-ticker' ),
			'label' 			=> __( 'Enable Ditty News Ticker (Legacy code)', 'ditty-news-ticker' ),
			'desc' 				=> __( 'This will enable loading of all legacy scripts and post types. Only enable this option if you have active Ditty News Ticker posts displaying on your site. You must refresh your browser after saving before changes take place.', 'ditty-news-ticker' ),
			'std' 				=> ditty_settings( 'ditty_news_ticker' ),
		),	
		// 'ditty_layouts_sass' => array(
		// 	'type' 				=> 'checkbox',
		// 	'id' 					=> 'ditty_layouts_sass',
		// 	'name' 				=> __( 'Ditty Layouts CSS Editor', 'ditty-news-ticker' ),
		// 	'label' 			=> __( 'Use SASS for the Layout CSS editor', 'ditty-news-ticker' ),
		// 	'desc' 				=> __( 'This is an advanced option. Error notifications may not work within the CSS editor.', 'ditty-news-ticker' ),
		// 	'std' 				=> ditty_settings( 'ditty_layouts_sass' ),
		// ),
	);
	ditty_fields( $fields );
}

/**
 * Setup the global Ditty fields
 *
 * @since    3.0  
*/
function ditty_settings_global_ditty() {
	$fields = array(
		'heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'heading',
			'name' 		=> __( 'Global Ditty', 'ditty-news-ticker' ),
			'desc'		=> __( 'Add Ditty dynamically anywhere on your site. You just need to specify an html selector and the position for the Ditty in relation to the selector. Then choose a Ditty and optionally set other customization options.', 'ditty-news-ticker' ),
		),
		'global_ditty' => array(
			'type' 						=> 'group',
			'id' 							=> 'global_ditty',
			'clone'						=> true,
			'clone_button'		=> __( 'Add More Global Tickers', 'ditty-news-ticker' ),
			'multiple_fields'	=> false,
			'fields' 					=> array(
				'selector' 		=> array(
					'type'				=> 'text',
	        'id' 					=> 'selector',
	        'name' 				=> __( 'HTML Selector', 'ditty-news-ticker' ),
	        'help'				=> __( 'Add a jQuery HTML element selector to add a Ditty to.', 'ditty-news-ticker' ),
	        'placeholder'	=> __( 'Example: #site-header', 'ditty-news-ticker' ),
        ),
        array(
	        'type'				=> 'select',
	        'id' 					=> 'position',
          'name' 				=> __( 'Position', 'ditty-news-ticker' ),
          'help'				=> __( 'Select the position of the Ditty in relation to the HTML selector.', 'ditty-news-ticker' ),
          'placeholder'	=> __( 'Select Position', 'ditty-news-ticker' ),
          'options' 		=> array(
	          'prepend'	=> __( 'Start of Element', 'ditty-news-ticker' ),
	          'append'	=> __( 'End of Element', 'ditty-news-ticker' ),
	          'before'	=> __( 'Before Element', 'ditty-news-ticker' ),
	          'after'		=> __( 'After Element', 'ditty-news-ticker' ),
          ),
        ),
        array(
	        'type'			=> 'select',
	        'id' 				=> 'ditty',
          'name' 			=> __( 'Ditty', 'ditty-news-ticker' ),
          'help'				=> __( 'Select a Ditty you want to display globally.', 'ditty-news-ticker' ),
          'placeholder'	=> __( 'Select a Ditty', 'ditty-news-ticker' ),
          'options' 		=> Ditty()->singles->select_field_options(),
        ),
        array(
	        'type'				=> 'select',
	        'id' 					=> 'display',
          'name' 				=> __( 'Display', 'ditty-news-ticker' ),
          'help'				=> __( 'Optional: Select a custom display to use with the Ditty.', 'ditty-news-ticker' ),
          'placeholder'	=> __( 'Use Default Display', 'ditty-news-ticker' ), 
          'options' 		=> Ditty()->displays->select_field_options(),
        ),
        array(
	        'type'	=> 'text',
	        'id' 		=> 'custom_id',
          'name' 	=> __( 'Custom ID', 'ditty-news-ticker' ),
          'help'	=> __( 'Optional: Add a custom ID to the Ditty', 'ditty-news-ticker' ),
        ),
        array(
	        'type'	=> 'text',
	        'id' 		=> 'custom_classes',
          'name' 	=> __( 'Custom Classes', 'ditty-news-ticker' ),
          'help'	=> __( 'Optional: Add custom classes to the Ditty', 'ditty-news-ticker' ),
        ),
			),
			'std' 	=> ditty_settings( 'global_ditty' ),
		),
	);
	ditty_fields( $fields );
}

/**
 * Save settings via ajax
 *
 * @since    3.0
 */
function ditty_settings_save_ajax() {
	check_ajax_referer( 'ditty', 'security' );
	if ( ! current_user_can( 'manage_ditty_settings' ) ) {
		return false;
	}
	$json_data = apply_filters( 'ditty_settings_save', $_POST, array() );
	wp_send_json( $json_data );
}
add_action( 'wp_ajax_ditty_settings_save', 'ditty_settings_save_ajax' );

/**
 * Save settings
 *
 * @since    3.0
 */
function ditty_settings_save( $data, $json_data ) {

	$input_updates = array();
	$sanitized_global_ditty = array();
	if ( isset( $data['global_ditty'] ) && is_array( $data['global_ditty'] ) && count( $data['global_ditty'] ) > 0 ) {
		foreach ( $data['global_ditty'] as $index => $global_ditty ) {
			$sanitized_classes = array();
			if ( isset( $global_ditty['custom_classes'] ) ) {
				$classes = array_map( 'trim', explode( ' ', $global_ditty['custom_classes'] ) );
				if ( is_array( $classes ) && count( $classes ) > 0 ) {
					foreach ( $classes as $i => $class ) {
						$sanitized_classes[] = sanitize_html_class( $class );
					}
				}
			}
			$sanitized_data = array(
				'selector' 				=> isset( $global_ditty['selector'] ) 			? wp_kses_post( $global_ditty['selector'] ) : false,
				'position' 				=> isset( $global_ditty['position'] ) 			? esc_attr( $global_ditty['position'] ) : false,
				'ditty' 					=> isset( $global_ditty['ditty'] ) 					? intval( $global_ditty['ditty'] ) : false,
				'display' 				=> isset( $global_ditty['display'] ) 				? esc_attr( $global_ditty['display'] ) : false,
				'custom_id' 			=> isset( $global_ditty['custom_id'] ) 			? sanitize_title( $global_ditty['custom_id'] ) : false,
				'custom_classes' 	=> isset( $global_ditty['custom_classes'] ) ? implode( ' ', $sanitized_classes ) : false,
			);
			$sanitized_global_ditty[] = $sanitized_data;
			
			// Add data to pass back to javascript
			$input_updates["global_ditty[{$index}][custom_id]"] = $sanitized_data['custom_id'];
			$input_updates["global_ditty[{$index}][custom_classes]"] = $sanitized_data['custom_classes'];
		}
	}
	
	$variation_types = ditty_layout_variation_types();
	$sanitized_variation_defaults = array();
	if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
		foreach ( $variation_types as $item_type => $item_type_variations ) {
			if ( ! isset( $sanitized_variation_defaults[$item_type] ) ) {
				$sanitized_variation_defaults[$item_type] = array();
			}
			if ( is_array( $item_type_variations ) && count( $item_type_variations ) > 0 ) {
				foreach ( $item_type_variations as $variation_id => $item_type_variation ) {
					if ( isset( $data["variation_default_{$item_type}_{$variation_id}"] ) ) {
						$sanitized_variation_defaults[$item_type][$variation_id] = intval( $data["variation_default_{$item_type}_{$variation_id}"] );
					}
				}
			}
		}
	}

	$settings = array(
		'live_refresh'				=> isset( $data['live_refresh'] ) 				? intval( $data['live_refresh'] ) : 10,
		'ditty_display_ui'		=> isset( $data['ditty_display_ui'] ) 		? esc_attr( $data['ditty_display_ui'] ) : 'disabled',
		'ditty_layout_ui'			=> isset( $data['ditty_layout_ui'] ) 			? esc_attr( $data['ditty_layout_ui'] ) : 'disabled',
		'ditty_layouts_sass' 	=> isset( $data['ditty_layouts_sass'] ) 	? esc_attr( $data['ditty_layouts_sass'] ) : false,
		'variation_defaults'	=> $sanitized_variation_defaults,
		'global_ditty'				=> $sanitized_global_ditty,
		'ditty_news_ticker' 	=> isset( $data['ditty_news_ticker'] ) 		? esc_attr( $data['ditty_news_ticker'] ) : false,
		'ditty_wizard' 				=> isset( $data['ditty_wizard'] ) 			? esc_attr( $data['ditty_wizard'] ) : false,
		'disable_fontawesome' => isset( $data['disable_fontawesome'] )	? esc_attr( $data['disable_fontawesome'] ) : false,
		'notification_email' 	=> ( isset( $data['notification_email'] ) && is_email( $data['notification_email'] ) ) ? $data['notification_email'] : false,
	);
	ditty_settings( $settings );
	
	if ( ! isset( $json_data['input_updates'] ) ) {
		$json_data['input_updates'] = array();
	}
	$json_data['input_updates'] = array_merge( $json_data['input_updates'], $input_updates );
	return $json_data;
}
add_action( 'ditty_settings_save', 'ditty_settings_save', 10, 2 );