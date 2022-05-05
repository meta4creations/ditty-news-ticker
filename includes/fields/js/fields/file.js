jQuery(function($) {
	// Setup strict mode
	(function() {
		'use strict';

		function setup($field) {
			$field.addClass('ditty-input--file--init');

			var $input = $field.find('input[type="text"]'),
				uploader;

			$field.on('click', '.ditty-input--file__upload', function(e) {
				e.preventDefault();
				if (undefined === uploader) {
					uploader = wp.media({
						title: $input.data('media_title'),
						button: { text: $input.data('media_button'), size: 'small' },
						multiple: $input.data('multiple'),
						library: {
							type: $input.data('file_types'),
						},
					});
				}

				uploader.on('open', function() {
					var selection = uploader.state().get('selection');
					var attachment = wp.media.attachment($input.val());
					if (attachment) {
						selection.add(attachment);
					}
					// let ids = [13, 14, 56];
					// ids.forEach(function(id) {
					// 	let attachment = wp.media.attachment(id);
					// 	selection.add(attachment ? [attachment] : []);
					// } );
				});

				uploader.on('select', function() {
					var attachments = uploader
							.state()
							.get('selection')
							.toJSON(),
						file_data = [];

					if (attachments.length > 0) {
						$(attachments).each(function() {
							file_data.push({
								id: $(this)[0].id,
								title: $(this)[0].title,
								caption: $(this)[0].caption,
								description: $(this)[0].description,
								link: $(this)[0].link,
								url: $(this)[0].url,
							});
						});
					}

					$input.val(file_data[0].url);

					$field.trigger('ditty_field_file_select', [$field, file_data]);
					$field.trigger('ditty_field_update');
				});

				uploader.open();
				return false;
			});
		}

		function init(e) {
			$(e.target)
				.find('.ditty-input--file:not(.ditty-input--file--init)')
				.each(function() {
					setup($(this));
				});
		}
		$(document).on('ditty_init_fields', init);
	})();
});
