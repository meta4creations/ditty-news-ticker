<?php


/**
 * Add the drag icon to the list heading
 *
 * @access  public
 * @since   1.0.0
 */	
function mtphr_dnt_list_heading_drag() {
	echo '<i class="dashicons dashicons-menu"></i>';
}


/**
 * Add the action buttons to the list heading
 *
 * @access  public
 * @since   1.0.0
 */
function mtphr_dnt_list_heading_buttons() {
	?>
	<div class="mtphr-dnt-list-buttons">
		<a class="mtphr-dnt-list-delete" href="#"><i class="dashicons dashicons-no"></i></a>
		<a class="mtphr-dnt-list-add" href="#"><i class="dashicons dashicons-plus"></i></a>
	</div>
	<?php
}


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