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

		// Notice close
		$( '.ditty-dashboard-notice' ).on( 'click', '.notice-dismiss', function() {		
			var $notice = $( this ).parents( '.ditty-dashboard-notice' ),
					notice_id = $notice.data( 'notice_id' );
			
			var data = {
				action		: 'ditty_notice_close',
				notice_id	: notice_id,
				security	: dittyAdminVars.security
			};
			$.post( dittyAdminVars.ajaxurl, data );
			
		} );

	}() );
	
} );