<?php

require_once( MTPHR_DNT_DIR.'legacy/composer.php' );
require_once( MTPHR_DNT_DIR.'legacy/helpers.php' );
require_once( MTPHR_DNT_DIR.'legacy/post-types.php' );
require_once( MTPHR_DNT_DIR.'legacy/settings.php' );
require_once( MTPHR_DNT_DIR.'legacy/static.php' );
require_once( MTPHR_DNT_DIR.'legacy/widget.php' );

if( is_admin() ) {

	// Load admin specific code
	require_once( MTPHR_DNT_DIR.'legacy/admin/ajax.php' );
	require_once( MTPHR_DNT_DIR.'legacy/admin/meta-boxes.php' );
	require_once( MTPHR_DNT_DIR.'legacy/admin/edit-columns.php' );
	require_once( MTPHR_DNT_DIR.'legacy/admin/fields/helpers.php' );
	require_once( MTPHR_DNT_DIR.'legacy/admin/fields/fields.php' );
	require_once( MTPHR_DNT_DIR.'legacy/admin/filters.php' );
	require_once( MTPHR_DNT_DIR.'legacy/admin/functions.php' );
	require_once( MTPHR_DNT_DIR.'legacy/admin/upgrades.php' );
	
} else {
	
	// Load front-end specific code
	require_once( MTPHR_DNT_DIR.'legacy/filters.php' );
	require_once( MTPHR_DNT_DIR.'legacy/functions.php' );
	require_once( MTPHR_DNT_DIR.'legacy/shortcodes.php' );
	require_once( MTPHR_DNT_DIR.'legacy/classes/class-mtphr-dnt.php' );
	require_once( MTPHR_DNT_DIR.'legacy/classes/class-mtphr-dnt-tick.php' );
	require_once( MTPHR_DNT_DIR.'legacy/classes/class-mtphr-dnt-image.php' );
	require_once( MTPHR_DNT_DIR.'legacy/classes/helpers/class-mtphr-dnt-string-replacement.php' );
	require_once( MTPHR_DNT_DIR.'legacy/templates.php' );
}

require_once MTPHR_DNT_DIR.'legacy/classes/class-mtphr-dnt-roles.php';
require_once MTPHR_DNT_DIR.'legacy/install.php';