jQuery(function($) {
	// Setup strict mode
	(function() {
		'use strict';

		function modifyItemsPerPage($perPage, pagingValue) {
			if (0 === parseInt(pagingValue)) {
				$perPage.hide();
			} else {
				$perPage.show();
			}
		}

		function modifyArrows(arrowSiblings, arrowsValue) {
			if ('none' === arrowsValue) {
				arrowSiblings.hide();
			} else {
				arrowSiblings.show();
			}
		}

		function modifyBullets(bulletSiblings, bulletsValue) {
			if ('none' === bulletsValue) {
				bulletSiblings.hide();
			} else {
				bulletSiblings.show();
			}
		}

		function initListDisplay($fields) {
			var $perPage = $fields.find('.ditty-field--perPage'),
				arrowSiblings = $fields.find('.ditty-field--arrows').siblings(),
				bulletSiblings = $fields.find('.ditty-field--bullets').siblings();

			// Set paging
			modifyItemsPerPage($perPage, $fields.find('input[name="paging"]:checked').val());
			$fields.find('input[name="paging"]').on('change', function() {
				modifyItemsPerPage($perPage, $(this).val());
			});

			// Set arrows
			modifyArrows(arrowSiblings, $fields.find('select[name="arrows"]').val());
			$fields.find('select[name="arrows"]').on('change', function() {
				modifyArrows(arrowSiblings, $(this).val());
			});

			// Set bullets
			modifyBullets(bulletSiblings, $fields.find('select[name="bullets"]').val());
			$fields.find('select[name="bullets"]').on('change', function() {
				modifyBullets(bulletSiblings, $(this).val());
			});
		}

		$('#ditty-editor').on('ditty_display_editor_panel_init', '.ditty-editor__panel--displayEditor', function(e, editorPanel) {
			if ('list' === editorPanel.displayType) {
				initListDisplay(editorPanel.$form);
			}
		});

		if ($('.ditty-display-admin-settings--list').length) {
			initListDisplay($('.ditty-display-admin-settings--list'));
		}
	})();
});
