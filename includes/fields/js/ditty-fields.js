/* global jQuery:true */
/* //global dittyVars:true */
/* //global console:true */

// @codekit-append 'fields/clone.js
// @codekit-append 'fields/code.js
// @codekit-append 'fields/color.js
// @codekit-append 'fields/date.js
// @codekit-append 'fields/file.js
// @codekit-append 'fields/image.js
// @codekit-append 'fields/slider.js
// @codekit-append 'fields/wysiwyg.js
// @codekit-append 'fields/group.js
// @codekit-append 'fields/layout_element.js

jQuery(function($) {
	// Setup strict mode
	(function() {
		'use strict';

		$('body').on('click', '.ditty-help-icon', function(e) {
			e.preventDefault();

			var $icon = $(this),
				$label = $icon.parents('.ditty-field__label'),
				$help = $label.siblings('.ditty-field__help');

			if ($icon.hasClass('active')) {
				$icon.removeClass('active');
				$help.hide();
			} else {
				$icon.addClass('active');
				$help.show();
			}
		});
	})();
});
