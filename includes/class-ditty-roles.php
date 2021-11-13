<?php

/**
 * Register custom roles and capabilities
 *
 * @link       https://www.metaphorcreations.com
 * @since      3.0
 *
 * @package    Ditty
 * @subpackage Ditty/includes
 */

/**
 * Register custom roles and capabilities
 *
 * Setup custom defined roles and capabilities for
 * post types and settings.
 *
 * @since      3.0
 * @package    Ditty
 * @subpackage Ditty/includes
 * @author     Metaphor Creations <joe@metaphorcreations.com>
 */
class Ditty_Roles {

	/**
	 * Add capabilities
	 *
	 * @since  3.0
	 * @global WP_Roles $wp_roles
	 * @return void
	 */
	public function add_caps() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {

			$wp_roles->add_cap( 'administrator', 'manage_ditty_settings' );

			// Add the main post type capabilities
			$capabilities = $this->get_core_caps();
			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'administrator', $cap );
					$wp_roles->add_cap( 'author', $cap );
					$wp_roles->add_cap( 'editor', $cap );
				}
			}
		}
	}

	/**
	 * Gets the core post type capabilities
	 *
	 * @since  3.0
	 * @return array $capabilities Core post type capabilities
	 */
	public function get_core_caps() {
		
		$capabilities = array();

		$capability_types = array( 'ditty', 'ditty_layout', 'ditty_display' );

		foreach ( $capability_types as $capability_type ) {
			$capabilities[$capability_type] = array(
				// Post type
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",
			);
		}
		
		$capabilities['ditty_item'] = array(
			'edit_ditty_items',
			'publish_ditty_items',
			'delete_ditty_items',
		);

		return $capabilities;
	}

	
	/**
	 * Remove core post type capabilities (called on uninstall)
	 *
	 * @since 3.0
	 * @return void
	 */
	public function remove_caps() {

		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {
			
			/** Administrator Capabilities */
			$wp_roles->remove_cap( 'administrator', 'manage_ditty_settings' );

			/** Remove the Main Post Type Capabilities */
			$capabilities = $this->get_core_caps();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'administrator', $cap );
					$wp_roles->remove_cap( 'author', $cap );
					$wp_roles->remove_cap( 'editor', $cap );
				}
			}
		}
	}

}
