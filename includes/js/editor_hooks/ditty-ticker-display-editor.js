jQuery(function($) {
	// Setup strict mode
	(function() {
		'use strict';

		/**
		 * Set the min height
		 *
		 * @since    3.0.14
		 */
		function setMinHeight($form, ditty) {
			var val = $form.find('input[name="direction"]:checked').val(),
				$minHeight = $form.find('.ditty-field--minHeight'),
				$maxHeight = $form.find('.ditty-field--maxHeight'),
				$minHeightInput = $form.find('input[name="minHeight"]');

			if ('down' === val || 'up' === val) {
				$minHeight.show();
				$maxHeight.show();
				if ('' === $minHeightInput.val()) {
					var defaultValue = '300px';

					$minHeightInput.val(defaultValue);
					if (ditty) {
						ditty.options('minHeight', defaultValue);
					}
				}
			} else {
				$minHeight.hide();
				$maxHeight.hide();
			}
		}

		/**
		 * Set the scroll delay field visibility
		 *
		 * @since    3.0.13
		 */
		function setScrollDelay($form) {
			var val = $form.find('input[name="scrollInit"]:checked').val();
			if ('filled' === val) {
				$form.find('.ditty-field--scrollDelay').show();
			} else {
				$form.find('.ditty-field--scrollDelay').hide();
			}
		}

		/**
		 * Set the scroll delay field visibility
		 *
		 * @since    3.0.13
		 */
		function setItemWrap($form) {
			var val = $form.find('input[name="cloneItems"]:checked').val();
			if ('no' === val) {
				$form.find('.ditty-field--wrapItems').show();
			} else {
				$form.find('.ditty-field--wrapItems').hide();
			}
		}

		/**
		 * Set the title field visibility
		 *
		 * @since    3.0.13
		 */
		function setTitleStyles($form) {
			var val = $form.find('select[name="titleDisplay"]').val(),
				$displayField = $form.find('.ditty-field--titleDisplay');

			if ('none' === val) {
				$displayField.siblings().hide();
			} else {
				$displayField.siblings().show();
			}
		}

		/**
		 * Set the title field visibility
		 *
		 * @since    3.0.14
		 */
		function initTickerDisplay($form, ditty) {
			// Set minHeight
			setMinHeight($form, ditty);
			$form.find('input[name="direction"]').on('click', function() {
				setMinHeight($form, ditty);
			});

			// Set scroll delay
			setScrollDelay($form);
			$form.find('input[name="scrollInit"]').on('click', function() {
				setScrollDelay($form);
			});

			// Set item wrap
			setItemWrap($form);
			$form.find('input[name="cloneItems"]').on('click', function() {
				setItemWrap($form);
			});

			// Set the title styles
			setTitleStyles($form);
			$form.find('select[name="titleDisplay"]').on('change', function() {
				setTitleStyles($form);
			});
		}

		$('#ditty-editor').on('ditty_display_editor_panel_init', '.ditty-editor__panel--displayEditor', function(e, editorPanel) {
			if ('ticker' === editorPanel.displayType) {
				var $form = editorPanel.$form,
					dittyEditor = $form.parents('#ditty-editor__settings')[0],
					ditty = dittyEditor._ditty_editor.ditty;

				initTickerDisplay($form, ditty);
			}
		});

		$('.ditty-sandbox').on('ditty_sandbox_init', function(e, settings, ditty) {
			if ('ticker' === settings.displayType) {
				var $form = $(this).find('form');
				initTickerDisplay($form, ditty);
			}
		});

		if ($('.ditty-display-admin-settings--ticker').length) {
			initTickerDisplay($('.ditty-display-admin-settings--ticker'));
		}
	})();
});
