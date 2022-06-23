jQuery(function($) {
	// Setup strict mode
	(function() {
		'use strict';

		var isDisplayPost = $('input[name="post_type"]').length && 'ditty_display' == $('input[name="post_type"]').val(),
			ajaxSubmitComplete = false;

		$('form#post').on('submit', function(e) {
			if (isDisplayPost && !ajaxSubmitComplete) {
				if (!$('#auto_draft').length) {
					e.preventDefault();

					var $button = $('#publishing-action').children('input[type="submit"]'),
						$spinner = $('#publishing-action').children('.spinner');

					$button.attr('disabled', 'disabled');
					$spinner.css('visibility', 'visible');

					var data = {
						action: 'ditty_admin_display_update',
						display_id: $('input[name="post_ID"]').val(),
						security: dittyAdminVars.security,
					};
					$('#ditty-display-settings').ajaxSubmit({
						url: dittyAdminVars.ajaxurl,
						type: 'post',
						dataType: 'json',
						data: data,
						success: function(response) {
							ajaxSubmitComplete = true;
							$('form#post').submit();
						},
					});
				}
			}
		});
	})();
});
