<?php

/**
 * MTPHR_DNT Roles and Capabilities
 *
 * @package     MTPHR_DNT
 * @subpackage  Classes/MTPHR_DNT Roles
 * @copyright   Copyright (c) 2017, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.1.11
*/
class MTPHR_DNT_Roles {
	
	/**
	 * Get things going
	 *
	 * @since 2.1.11
	 */
	public function __construct() {
	}
	

	/**
	 * Add new News Ticker specific capabilities
	 *
	 * @access public
	 * @since  2.1.11
	 * @global WP_Roles $wp_roles
	 * @return void
	 */
	public function add_caps() {
		global $wp_roles;

		if( class_exists('WP_Roles') ) {
			if( !isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if( is_object($wp_roles) ) {
			
			/** Site Administrator Capabilities */
			$wp_roles->add_cap( 'administrator', 'manage_ditty_news_ticker_settings' );

			// Add the main post type capabilities
			$capabilities = $this->get_core_caps();
			foreach( $capabilities as $cap_group ) {
				foreach( $cap_group as $cap ) {
					$wp_roles->add_cap( 'administrator', $cap );
				}
			}
		}
	}


	/**
	 * Gets the core post type capabilities
	 *
	 * @access public
	 * @since  2.1.11
	 * @return array $capabilities Core post type capabilities
	 */
	public function get_core_caps() {
		$capabilities = array();

		$capability_types = array( 'ditty_news_ticker' );

		foreach ( $capability_types as $capability_type ) {
			$capabilities[ $capability_type ] = array(
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

		return $capabilities;
	}


	/**
	 * Remove core post type capabilities (called on uninstall)
	 *
	 * @access public
	 * @since 2.1.11
	 * @return void
	 */
	public function remove_caps() {

		global $wp_roles;

		if( class_exists('WP_Roles') ) {
			if( !isset($wp_roles) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if( is_object($wp_roles) ) {
			
			/** Administrator Capabilities */
			$wp_roles->remove_cap( 'administrator', 'icc_manage_ticket_settings' );

			/** Remove the Main Post Type Capabilities */
			$capabilities = $this->get_core_caps();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'administrator', $cap );
				}
			}
		}
	}

}
