<?php

/**
 * Ditty Settings Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Settins
 * @copyright   Copyright (c) 2023, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1
*/
class Ditty_Settings {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'reset_admin_permissions' ), 5 );
		add_action( 'admin_menu', array( $this, 'settings_pages' ), 5 );
	}

	/**
	 * Register settings pages
	 *
	 * @since    3.1
	*/
	public function settings_pages() {
		add_submenu_page(
			'edit.php?post_type=ditty',		// The ID of the top-level menu page to which this submenu item belongs
			__( 'Settings', 'ditty-news-ticker' ),		// The value used to populate the browser's title bar when the menu page is active
			__( 'Settings', 'ditty-news-ticker' ),		// The label of this submenu item displayed in the menu
			'manage_ditty_settings',			// What roles are able to access this submenu item
			'ditty_settings',							// The ID used to represent this submenu item
			array( $this, 'settings_page_display' )			// The callback function used to render the options for this submenu item
		);
	}

	/**
	 * Render the settings page
	 *
	 * @since    3.1
	*/
	public function settings_page_display() {
		?>
		<div id="ditty-settings__wrapper" class="ditty-adminPage"></div>
		<?php
	}
	
	/**
	 * Setup the fields
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function fields() {	
		$test = $this->user_roles_and_capabilities();

		$fields = [
			[
				'id' => 'general',
				'label' => __("General Settings", "ditty-news-ticker"),
				'name' => __("General Settings", "ditty-news-ticker"),
				'description' => __( 'Add a description here...', "ditty-news-ticker" ),
				'icon' => 'fa-cog',
				'fields' => [
					[
						'type' 		=> 'number',
						'id' 			=> 'live_refresh',
						'name' 		=> esc_html__( 'Live Refresh Rate', 'ditty-news-ticker' ),
						'min'			=> 1,
						'after' 	=> esc_html__( 'Minute(s)', 'ditty-news-ticker' ),
						'description'		=> esc_html__( 'Set the live update refresh interval for your Ditty.', 'ditty-news-ticker' ),
						'std' 		=> ditty_settings_defaults( 'live_refresh' ),
					],
					[
						'type' 				=> 'radio',
						'id' 					=> 'edit_links',
						'name' 				=> esc_html__( 'Edit Links', 'ditty-news-ticker' ),
						'description' => esc_html__( 'Display links to edit Ditty.', 'ditty-news-ticker' ),
						'inline'			=> true,
						'options'			=> array(
							'enabled'		=> esc_html__( 'Enabled', 'ditty-news-ticker' ),
							'disabled'	=> esc_html__( 'Disabled', 'ditty-news-ticker' ),
						),
						'std' 				=> ditty_settings_defaults( 'edit_links' ),
					],
          [
						'type' 				=> 'radio',
						'id' 					=> 'disable_googlefonts',
						'name' 				=> esc_html__( 'Google Fonts', 'ditty-news-ticker' ),
						'description' => esc_html__( 'This will enable or disable the ability to select and use Google fonts in the Ditty Display settings.', 'ditty-news-ticker' ),
						'inline'			=> true,
						'options'			=> array(
							'enabled'		=> esc_html__( 'Enabled', 'ditty-news-ticker' ),
							'disabled'	=> esc_html__( 'Disabled', 'ditty-news-ticker' ),
						),
						'std' 				=> ditty_settings_defaults( 'disable_fontawesome' ),
					],
					[
						'type' 				=> 'radio',
						'id' 					=> 'disable_fontawesome',
						'name' 				=> esc_html__( 'Font Awesome', 'ditty-news-ticker' ),
						'description' => esc_html__( 'This will enable or disable loading of Font Awesome on the front-end of the site. Font Awesome icons may used in various Layout tags.', 'ditty-news-ticker' ),
						'inline'			=> true,
						'options'			=> array(
							'enabled'		=> esc_html__( 'Enabled', 'ditty-news-ticker' ),
							'disabled'	=> esc_html__( 'Disabled', 'ditty-news-ticker' ),
						),
						'std' 				=> ditty_settings_defaults( 'disable_fontawesome' ),
					],
					[
						'type' 				=> 'radio',
						'id' 					=> 'ditty_news_ticker',
						'name' 				=> esc_html__( 'Ditty News Ticker', 'ditty-news-ticker' ),
						'description' => esc_html__( 'This will enable or disable loading of all legacy scripts and post types. Only enable this option if you have active Ditty News Ticker posts displaying on your site. You must refresh your browser after saving before changes take place.', 'ditty-news-ticker' ),
						'inline'			=> true,
						'options'			=> array(
							'enabled'		=> esc_html__( 'Enabled', 'ditty-news-ticker' ),
							'disabled'	=> esc_html__( 'Disabled', 'ditty-news-ticker' ),
						),
						'std' 				=> ditty_settings_defaults( 'ditty_news_ticker' ),
					],	
				]
			],
			[
				'id' => 'global_ditty',
				'label' => esc_html__( 'Global Ditty', 'ditty-news-ticker' ),
				'name' => esc_html__( 'Global Ditty Settings', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Add Ditty dynamically anywhere on your site. You just need to specify an html selector and the position for the Ditty in relation to the selector. Then choose a Ditty and optionally set other customization options.', 'ditty-news-ticker' ),
				'icon' => 'fa-globe-americas',
				'fields' => [
					[
						'type' 						=> 'group',
						'id' 							=> 'global_ditty',
						'clone'						=> true,
						'clone_button'		=> esc_html__( 'Add More Global Tickers', 'ditty-news-ticker' ),
						'multiple_fields'	=> false,
						'fields' 					=> array(
							[
								'type'				=> 'text',
								'id' 					=> 'selector',
								'name' 				=> esc_html__( 'HTML Selector', 'ditty-news-ticker' ),
								'help'				=> esc_html__( 'Add a jQuery HTML element selector to add a Ditty to.', 'ditty-news-ticker' ),
								'placeholder'	=> esc_html__( 'Example: #site-header', 'ditty-news-ticker' ),
							],
							[
								'type'				=> 'select',
								'id' 					=> 'position',
								'name' 				=> esc_html__( 'Position', 'ditty-news-ticker' ),
								'help'				=> esc_html__( 'Select the position of the Ditty in relation to the HTML selector.', 'ditty-news-ticker' ),
								'placeholder'	=> esc_html__( 'Select Position', 'ditty-news-ticker' ),
								'options' 		=> array(
									'prepend'	=> esc_html__( 'Start of Element', 'ditty-news-ticker' ),
									'append'	=> esc_html__( 'End of Element', 'ditty-news-ticker' ),
									'before'	=> esc_html__( 'Before Element', 'ditty-news-ticker' ),
									'after'		=> esc_html__( 'After Element', 'ditty-news-ticker' ),
								),
							],
							[
								'type'			=> 'select',
								'id' 				=> 'ditty',
								'name' 			=> esc_html__( 'Ditty', 'ditty-news-ticker' ),
								'help'				=> esc_html__( 'Select a Ditty you want to display globally.', 'ditty-news-ticker' ),
								'placeholder'	=> esc_html__( 'Select a Ditty', 'ditty-news-ticker' ),
								'options' 		=> Ditty()->singles->select_field_options(),
							],
							[
								'type'				=> 'select',
								'id' 					=> 'display',
								'name' 				=> esc_html__( 'Display', 'ditty-news-ticker' ),
								'help'				=> esc_html__( 'Optional: Select a custom display to use with the Ditty.', 'ditty-news-ticker' ),
								'placeholder'	=> esc_html__( 'Use Default Display', 'ditty-news-ticker' ), 
								'options' 		=> Ditty()->displays->select_field_options(),
							],
							[
								'type'	=> 'text',
								'id' 		=> 'custom_id',
								'name' 	=> esc_html__( 'Custom ID', 'ditty-news-ticker' ),
								'help'	=> esc_html__( 'Optional: Add a custom ID to the Ditty', 'ditty-news-ticker' ),
							],
							[
								'type'	=> 'text',
								'id' 		=> 'custom_classes',
								'name' 	=> esc_html__( 'Custom Classes', 'ditty-news-ticker' ),
								'help'	=> esc_html__( 'Optional: Add custom classes to the Ditty', 'ditty-news-ticker' ),
							],
						),
					],
				]
			],
			[
				'id' => 'layout_variation_defaults',
				'label' => esc_html__( 'Layout Defaults', 'ditty-news-ticker' ),
				'name' => esc_html__( 'Item Type Layout Defaults', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Set default layouts for your item types.', 'ditty-news-ticker' ),
				'icon' => 'fa-pencil-ruler',
				'fields' => [
					[
						'type'	=> 'group',
						'id' 		=> 'variation_defaults',
						//'multipleFields' => true,
						'fields' => $this->get_layout_default_fields(),
					],
				],
			],
			[
				'id' => 'permissions',
				'label' => esc_html__( 'Permissions', 'ditty-news-ticker' ),
				'name' => esc_html__( 'User Role Permissions', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Set user permissions for the roles on your site.', 'ditty-news-ticker' ),
				'icon' => 'fa-lock',
				'fields' => [
					[
						'type'	=> 'group',
						'id' 		=> 'permissions',
						'fields' => $this->user_roles_and_capabilities(),
					],
				],
			],

			// [
			// 	'id' => 'extensions',
			// 	'label' => esc_html__( 'Extensions', 'ditty-news-ticker' ),
			// 	'name' =>esc_html__( 'Extensions', 'ditty-news-ticker' ),
			// 	'icon' => 'fas fa-plus',
			// 	'pages' => $this->get_extensions()
			// ],
			// [
			// 	'id' => 'layoutDefaults',
			// 	'label' => esc_html__( 'Layout Defaults', 'ditty-news-ticker' ),
			// 	'name' =>esc_html__( 'Layout Variation Defaults', 'ditty-news-ticker' ),
			// 	'icon' => 'fas fa-pencil-ruler',
			// ],
		];	
		return $fields;
	}

	/**
	 * Return formatted extensions
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function get_extensions() {
		$extensions = ditty_extensions();
		$formatted_extensions = [];
		if ( is_array( $extensions ) && count( $extensions ) > 0 ) {
			foreach ( $extensions as $slug => $extension ) {
				if ( ! isset( $extension['id'] ) ) {
					$extension['id'] = $slug;
					$formatted_extensions[] = $extension;
				}
			}
		}
		return $formatted_extensions;
	}

	/**
	 * Render the layout default fields
	 *
	 * @since    3.1
	*/
	public function get_layout_default_fields() {
		$settings = get_ditty_settings( 'variation_defaults' );
		$item_types = ditty_item_types();
		$options = Ditty()->layouts->select_field_options();

		$fields = [];
		if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
			foreach ( $item_types as $item_type  ) {
				if ( ! $item_type_object = ditty_item_type_object( $item_type['type'] ) ) {
					continue;
				}
				$variation_types = $item_type_object->get_layout_variation_types();
				$variation_fields = [];
				if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
					foreach ( $variation_types as $variation_id => $variation_type ) {
						$variation_fields[] = [
							'type'	=> 'select',
							'id' 		=> $variation_id,
							'name' 	=> count( $variation_types ) > 1 ? $variation_type['label'] : false,
							'help'	=> count( $variation_types ) > 1 ? $variation_type['description'] : false,
							'options' => $options,
							'placeholder' => __( 'Choose a Layout', 'ditty-news-ticker' ),
							'std'			=> ( isset( $settings[$item_type['type']] ) && isset( $settings[$item_type['type']][$variation_id] ) ) ? $settings[$item_type['type']][$variation_id] : false,
						];
					}
				}

				$fields[] = [
					'type'	=> 'group',
					'id' 		=> $item_type['type'],
					'name' 	=> $item_type['label'],
					'icon' 	=> $item_type['icon'],
					'description'	=> sprintf( esc_html__( 'Set layout variations defaults for %s item types.', 'ditty-news-ticker' ), $item_type['label'] ),
					'collapsible' => true,
					'fields' => $variation_fields,
				];
			}
		}

		return $fields;
	}

	/**
	 * Reset admin permissions
	 *
	 * @access  public
	 * @since   3.1.9
	 */
	public function reset_admin_permissions() {
		if ( isset( $_GET['reset_ditty_admin_permissions'] ) && current_user_can( 'manage_options' ) ) {
			$ditty_capabilities = $this->get_capabilities();
			$role = get_role( 'administrator' );
			if ( is_array( $ditty_capabilities ) && count( $ditty_capabilities ) > 0 ) {
				foreach ( $ditty_capabilities as $ditty_capability ) {
					$role->add_cap( esc_attr( $ditty_capability ) );
				}
			}
			$redirect_url = remove_query_arg( 'reset_ditty_admin_permissions', admin_url('edit.php?post_type=ditty&page=ditty_settings&tab=permissions' ) );
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Return the urser roles a capabilites fieles
	 *
	 * @access  public
	 * @since   3.1.9
	 */
	private function user_roles_and_capabilities() {
		$wp_roles_instance = wp_roles();
		$all_roles = $wp_roles_instance->roles;
		$fields = [];

		$ditty_capabilities = $this->get_capabilities();
		$active_capabilities = $this->get_active_capabilities();

		foreach ($all_roles as $role_key => $role) {
			if ( 'administrator' == $role_key ) {
				continue;
			}
			$role_capabilities = $ditty_capabilities;
			$role_group = [
				'type'	=> 'group',
				'id' 		=> $role_key,
				'name' => sprintf( esc_html__( '%s Permissions', 'ditty-news-ticker' ), $role['name'] ),
				'description' => sprintf( esc_html__( 'Set Ditty permissions for the %s role.', 'ditty-news-ticker' ), $role['name'] ),
				'collapsible' => true,
				'defaultState' => 'collapsed',
				'fields' => [
					[
						'type' => 'checkboxes',
						'id' => 'capabilities',
						'inline' => false,
						'options' => $role_capabilities,
						'std' => $active_capabilities[$role_key],
					]
				],
			];
			$fields[] = $role_group;
		}

		return $fields;
	}

	/**
	 * Get Ditty capabilities
	 *
	 * @access  public
	 * @since   3.1.15
	 */
	private function get_capabilities() {
		$ditty_capabilities = array();
		$capability_types = array( 'ditty', 'ditty_layout', 'ditty_display' );
		foreach ( $capability_types as $capability_type ) {
			$caps = array(
				//"publish_{$capability_type}s",
				"edit_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"delete_{$capability_type}",
				"delete_{$capability_type}s",
				"delete_others_{$capability_type}s",
			);
			if ( is_array( $caps ) && count( $caps ) > 0 ) {
				foreach ( $caps as $cap ) {
					$ditty_capabilities[$cap] = $cap;
				}
			}
		}
		$ditty_capabilities['manage_ditty_settings'] = 'manage_ditty_settings';
		return $ditty_capabilities;
	}

	/**
	 * Get active capabilities of roles
	 *
	 * @access  public
	 * @since   3.1.9
	 */
	private function get_active_capabilities() {
		$wp_roles_instance = wp_roles();
		$all_roles = $wp_roles_instance->roles;
		$ditty_capabilities = $this->get_capabilities();
		$active_capabilities = [];
		foreach ($all_roles as $role_key => $role) {
			$capabilities = [];
			foreach ( $role['capabilities'] as $capability => $enabled ) {
				if ( $enabled && in_array( $capability, $ditty_capabilities ) ) {
					$capabilities[] = $capability;
				}
			}
			$active_capabilities[$role_key] = $capabilities;
		}
		return $active_capabilities;
	}

	/**
	 * Sanitize and possibly save the settings
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function sanitize( $values ) {
		$sanitized_global_ditty = array();
		if ( isset( $values['global_ditty'] ) && is_array( $values['global_ditty'] ) && count( $values['global_ditty'] ) > 0 ) {
			foreach ( $values['global_ditty'] as $index => $global_ditty ) {
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
					'position' 				=> isset( $global_ditty['position'] ) 			? sanitize_key( $global_ditty['position'] ) : false,
					'ditty' 					=> isset( $global_ditty['ditty'] ) 					? intval( $global_ditty['ditty'] ) : false,
					'display' 				=> isset( $global_ditty['display'] ) 				? intval( $global_ditty['display'] ) : false,
					'custom_id' 			=> isset( $global_ditty['custom_id'] ) 			? sanitize_title( $global_ditty['custom_id'] ) : false,
					'custom_classes' 	=> isset( $global_ditty['custom_classes'] ) ? implode( ' ', $sanitized_classes ) : false,
				);
				$sanitized_global_ditty[] = $sanitized_data;
			}
		}

		// Set capabilities
		$active_capabilities = $this->get_active_capabilities();
		if ( isset( $values['permissions'] ) && is_array( $values['permissions'] ) && count( $values['permissions'] ) > 0 ) {
			foreach ( $values['permissions'] as $role_key => $data ) {
				if ( 'administrator' == $role_key ) {
					continue;
				}
				$role = get_role( $role_key );
				$added_capabilities = array_diff( $data['capabilities'], $active_capabilities[$role_key] );
				$removed_capabilities = array_diff( $active_capabilities[$role_key], $data['capabilities'] );
				if ( is_array( $added_capabilities ) && count( $added_capabilities ) > 0 ) {
					foreach ( $added_capabilities as $added_capability ) {
						$role->add_cap( esc_attr( $added_capability ) );
					}
				}
				if ( is_array( $removed_capabilities ) && count( $removed_capabilities ) > 0 ) {
					foreach ( $removed_capabilities as $removed_capability ) {
						$role->remove_cap( esc_attr( $removed_capability ) );
					}
				}
			}
		}
		
		$sanitized_fields = array(
			'live_refresh'				=> isset( $values['live_refresh'] ) 				? intval( $values['live_refresh'] ) : 10,
			'edit_links'					=> isset( $values['edit_links'] ) 					? sanitize_key( $values['edit_links'] ) : 'enabled',
			'variation_defaults'	=> isset( $values['variation_defaults'] )		? ditty_sanitize_settings( $values['variation_defaults'] ) : [],
			'permissions'					=> isset( $values['permissions'] ) ? $values['permissions'] : [],
			'global_ditty'				=> $sanitized_global_ditty,
			'ditty_news_ticker' 	=> isset( $values['ditty_news_ticker'] ) 		? sanitize_key( $values['ditty_news_ticker'] ) : false,
			'disable_googlefonts' => isset( $values['disable_googlefonts'] )	? sanitize_key( $values['disable_googlefonts'] ) : false,
      'disable_fontawesome' => isset( $values['disable_fontawesome'] )	? sanitize_key( $values['disable_fontawesome'] ) : false,
			'notification_email' 	=> ( isset( $values['notification_email'] ) && is_email( $values['notification_email'] ) ) ? $values['notification_email'] : false,
		);

		return $sanitized_fields;
	}

	/**
	 * Save the settings after sanitizing
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function save( $values ) {
		$sanitized_fields = $this->sanitize( $values );
		return update_ditty_settings( $sanitized_fields );
	}
}