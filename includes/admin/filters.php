<?php

function mtphr_dnt_plugin_upgrade(){

	$active_version = get_option( 'mtphr_dnt_active_version', '0' );

	// Outdated
	if( version_compare($active_version, MTPHR_DNT_VERSION, '<') ) {

		/*
		// Run specific upgrade routines
		if( version_compare( $active_version, '1.4.6', '<' ) ) {
					mtphr_dnt_upgrade_to_version_1_4_6();
				}
		*/	
		
		update_option( 'mtphr_dnt_active_version', MTPHR_DNT_VERSION );
	}
}
//add_action( 'admin_init', 'mtphr_dnt_plugin_upgrade' );