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
	
	/**
	 * Setup the fields
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function fields() {	
		$fields = [
			[
				'id' => 'general',
				'label' => __("General", "ditty-news-ticker"),
				'name' => __("General Settings", "ditty-news-ticker"),
				'desc' => __( 'Add a description here...', "ditty-news-ticker" ),
				'icon' => 'fas fa-cog',
				'fields' => [
					'live_refresh' => array(
						'type' 		=> 'number',
						'id' 			=> 'live_refresh',
						'name' 		=> esc_html__( 'Live Refresh Rate', 'ditty-news-ticker' ),
						'after' 	=> esc_html__( 'Minute(s)', 'ditty-news-ticker' ),
						'desc'		=> esc_html__( 'Set the live update refresh interval for your Ditty.', 'ditty-news-ticker' ),
						'std' 		=> ditty_settings_defaults( 'live_refresh' ),
					),
					'edit_links' => array(
						'type' 				=> 'radio',
						'id' 					=> 'edit_links',
						'name' 				=> esc_html__( 'Edit Links', 'ditty-news-ticker' ),
						'desc' 				=> esc_html__( 'Display links to edit Ditty', 'ditty-news-ticker' ),
						'inline'			=> true,
						'options'			=> array(
							'disabled'	=> esc_html__( 'Disabled', 'ditty-news-ticker' ),
							'enabled'		=> esc_html__( 'Enabled', 'ditty-news-ticker' ),
						),
						'std' 				=> ditty_settings_defaults( 'edit_links' ),
					),
				]
			],
			[
				'id' => 'global_ditty',
				'label' => esc_html__( 'Global Ditty', 'ditty-news-ticker' ),
				'name' => esc_html__( 'Global Ditty Settings', 'ditty-news-ticker' ),
				'desc' => esc_html__( 'Add Ditty dynamically anywhere on your site. You just need to specify an html selector and the position for the Ditty in relation to the selector. Then choose a Ditty and optionally set other customization options.', 'ditty-news-ticker' ),
				'icon' => 'fas fa-globe-americas',
				'fields' => [
					'global_ditty' => array(
						'type' 						=> 'group',
						'id' 							=> 'global_ditty',
						'clone'						=> true,
						'clone_button'		=> esc_html__( 'Add More Global Tickers', 'ditty-news-ticker' ),
						'multiple_fields'	=> false,
						'fields' 					=> array(
							'selector' 		=> array(
								'type'				=> 'text',
								'id' 					=> 'selector',
								'name' 				=> esc_html__( 'HTML Selector', 'ditty-news-ticker' ),
								'help'				=> esc_html__( 'Add a jQuery HTML element selector to add a Ditty to.', 'ditty-news-ticker' ),
								'placeholder'	=> esc_html__( 'Example: #site-header', 'ditty-news-ticker' ),
							),
							array(
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
							),
							array(
								'type'			=> 'select',
								'id' 				=> 'ditty',
								'name' 			=> esc_html__( 'Ditty', 'ditty-news-ticker' ),
								'help'				=> esc_html__( 'Select a Ditty you want to display globally.', 'ditty-news-ticker' ),
								'placeholder'	=> esc_html__( 'Select a Ditty', 'ditty-news-ticker' ),
								'options' 		=> Ditty()->singles->select_field_options(),
							),
							array(
								'type'				=> 'select',
								'id' 					=> 'display',
								'name' 				=> esc_html__( 'Display', 'ditty-news-ticker' ),
								'help'				=> esc_html__( 'Optional: Select a custom display to use with the Ditty.', 'ditty-news-ticker' ),
								'placeholder'	=> esc_html__( 'Use Default Display', 'ditty-news-ticker' ), 
								'options' 		=> Ditty()->displays->select_field_options(),
							),
							array(
								'type'	=> 'text',
								'id' 		=> 'custom_id',
								'name' 	=> esc_html__( 'Custom ID', 'ditty-news-ticker' ),
								'help'	=> esc_html__( 'Optional: Add a custom ID to the Ditty', 'ditty-news-ticker' ),
							),
							array(
								'type'	=> 'text',
								'id' 		=> 'custom_classes',
								'name' 	=> esc_html__( 'Custom Classes', 'ditty-news-ticker' ),
								'help'	=> esc_html__( 'Optional: Add custom classes to the Ditty', 'ditty-news-ticker' ),
							),
						),
					),
				]
			],
		];	
		return $fields;
	}

	/**
	 * Sanitize and possibly save the settings
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function sanitize_settings( $values, $save_settings = false ) {
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
		
		$variation_types = ditty_layout_variation_types();
		$sanitized_variation_defaults = array();
		if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
			foreach ( $variation_types as $item_type => $item_type_variations ) {
				if ( ! isset( $sanitized_variation_defaults[$item_type] ) ) {
					$sanitized_variation_defaults[$item_type] = array();
				}
				if ( is_array( $item_type_variations ) && count( $item_type_variations ) > 0 ) {
					foreach ( $item_type_variations as $variation_id => $item_type_variation ) {
						if ( isset( $values["variation_default_{$item_type}_{$variation_id}"] ) ) {
							$sanitized_variation_defaults[$item_type][$variation_id] = intval( $values["variation_default_{$item_type}_{$variation_id}"] );
						}
					}
				}
			}
		}

		$sanitized_fields = array(
			'live_refresh'				=> isset( $values['live_refresh'] ) 				? intval( $values['live_refresh'] ) : 10,
			'edit_links'					=> isset( $values['edit_links'] ) 					? sanitize_key( $values['edit_links'] ) : 'enabled',
			'ditty_display_ui'		=> isset( $values['ditty_display_ui'] ) 		? sanitize_key( $values['ditty_display_ui'] ) : 'enabled',
			'ditty_layout_ui'			=> isset( $values['ditty_layout_ui'] ) 			? sanitize_key( $values['ditty_layout_ui'] ) : 'enabled',
			'ditty_layouts_sass' 	=> isset( $values['ditty_layouts_sass'] ) 	? sanitize_key( $values['ditty_layouts_sass'] ) : false,
			'variation_defaults'	=> $sanitized_variation_defaults,
			'global_ditty'				=> $sanitized_global_ditty,
			'ditty_news_ticker' 	=> isset( $values['ditty_news_ticker'] ) 		? sanitize_key( $values['ditty_news_ticker'] ) : false,
			'disable_fontawesome' => isset( $values['disable_fontawesome'] )	? sanitize_key( $values['disable_fontawesome'] ) : false,
			'notification_email' 	=> ( isset( $values['notification_email'] ) && is_email( $values['notification_email'] ) ) ? $values['notification_email'] : false,
		);

		if ( $save_settings ) {
			ditty_settings( $sanitized_fields );
		}
		
		return $sanitized_fields;
	}
}