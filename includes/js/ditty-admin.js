/* global jQuery:true */
/* global dittyAdminVars:true */
/* global console:true */

// @codekit-append 'admin/general.js';
// @codekit-append 'admin/class-ditty-settings.js';
// @codekit-append 'admin/class-ditty-extension.js';
// @codekit-append 'admin/ditty-extensions.js';
// @codekit-append 'admin/ditty-displays.js';
// @codekit-append 'admin/ditty-wizard.js';

jQuery(function($) {
	// Setup strict mode
	(function() {
		'use strict';

		$('#poststuff').trigger('ditty_init_fields');
		$('#ditty-settings').ditty_settings();
	})();
});
