/* global jQuery:true */
/* global dittyAdminVars:true */
/* global console:true */

// @codekit-append 'admin/class-ditty-settings.js';
// @codekit-append 'admin/class-ditty-extension.js';
// @codekit-append 'admin/ditty-extensions.js';
// @codekit-append 'admin/ditty-displays.js';

jQuery( document ).ready( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		$( '#poststuff' ).trigger( 'ditty_init_fields' );
		$( '#ditty-settings' ).ditty_settings();

	}() );
	
} );