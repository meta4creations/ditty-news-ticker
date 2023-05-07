/* global jQuery:true */
/* global dittyAdminVars:true */
/* global console:true */

// @codekit-append 'partials/class-ditty-ui-data-list.js';
// @codekit-append 'admin/class-ditty-extension.js';
// @codekit-append 'admin/ditty-extensions.js';

jQuery(function ($) {
  // Setup strict mode
  (function () {
    "use strict";

    $("#poststuff").trigger("ditty_init_fields");
    $("#ditty-settings").ditty_settings();
  })();
});
