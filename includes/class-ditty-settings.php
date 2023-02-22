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
				'name' => __("General", "ditty-news-ticker"),
				'desc' => __( 'Add a description here...', "ditty-news-ticker" ),
				'icon' => 'fas fa-cog',
				'fields' => [
					'content' => array(
						'type'	=> 'textarea',
						'id'		=> 'content',
						'name'	=> __( 'Content', 'ditty-news-ticker' ),
						'help'	=> __( 'Add the content of your item. HTML and inline styles are supported.', 'ditty-news-ticker' ),
						'std'		=> isset( $values['content'] ) ? $values['content'] : false,
					),
					'link_url' => array(
						'type'			=> 'text',
						'id'				=> 'link_url',
						'name'			=> __( 'Link', 'ditty-news-ticker' ),
						'help'			=> __( 'Add a custom link to your content. You can also add a link directly into your content.', 'ditty-news-ticker' ),
						'atts'			=> array(
							'type'	=> 'url',
						),
						'std'		=> isset( $values['link_url'] ) ? $values['link_url'] : false,
					),
					'link_title' => array(
						'type'			=> 'text',
						'id'				=> 'link_title',
						'name'			=> __( 'Title', 'ditty-news-ticker' ),
						'help'			=> __( 'Add a title to the custom lnk.', 'ditty-news-ticker' ),
						'std'			=> isset( $values['link_title'] ) ? $values['link_title'] : false,
					),
					'link_target' => array(
						'type'			=> 'select',
						'id'				=> 'link_target',
						'name'			=> __( 'Target', 'ditty-news-ticker' ),
						'help'			=> __( 'Set a target for your link.', 'ditty-news-ticker' ),
						'options'		=> array(
							'_self'		=> '_self',
							'_blank'	=> '_blank'
						),
						'std'		=> isset( $values['link_target'] ) ? $values['link_target'] : false,
					),
					'link_nofollow' => array(
						'type'			=> 'checkbox',
						'id'				=> 'link_nofollow',
						'name'			=> __( 'No Follow', 'ditty-news-ticker' ),
						'label'			=> __( 'Add "nofollow" to link', 'ditty-news-ticker' ),
						'help'			=> __( 'Enabling this setting will add an attribute called \'nofollow\' to your link. This tells search engines to not follow this link.', 'ditty-news-ticker' ),
						'std'		=> isset( $values['link_nofollow'] ) ? $values['link_nofollow'] : false,
					),
				]
			]
		];	
		return $fields;
	}

	/**
	 * Get the Ditty settings
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function settings( $key=false, $value='' ) {
		global $ditty_settings;
		if ( empty( $ditty_settings ) ) {
			$ditty_settings = get_option( 'ditty_settings', array() );
		}
		if ( $key ) {
			if ( is_array( $key ) ) {
				foreach ( $key as $k => $v ) {
					$ditty_settings[$k] = $v;
				}
				update_option( 'ditty_settings', $ditty_settings );
			} else {
				if ( $value ) {
					$ditty_settings[$key] = $value;
					update_option( 'ditty_settings', $ditty_settings );
				}
			}
		}
		$ditty_settings = wp_parse_args( $ditty_settings, $this->default_settings() );
		if ( $key && ! is_array( $key ) ) {
			if ( isset( $ditty_settings[$key] ) ) {
				return $ditty_settings[$key];
			}
		} else {
			return $ditty_settings;
		}
	}

	/**
	 * Get the default settings
	 *
	 * @access  public
	 * @since   3.1
	 */
	public function default_settings() {		
		$defaults = array(
			'live_refresh'					=> 10,
			'default_display'				=> false,
			'ditty_display_ui'			=> 'enabled',
			'ditty_layout_ui'				=> 'enabled',
			'ditty_layouts_sass'		=> false,
			'variation_defaults'		=> array(),
			'global_ditty'					=> array(),
			'ditty_news_ticker' 		=> '',
			'disable_fontawesome' 	=> '',
			'notification_email' 		=> '',
			'edit_links'						=> 'enabled',
		);
		return apply_filters( 'ditty_settings_defaults', $defaults );
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
			$this->settings( $sanitized_fields );
		}
		
		return $sanitized_fields;
	}
}