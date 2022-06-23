/* global dittyVars:true */
/* global jQuery:true */
// @codekit-append 'partials/class-ditty-ui-data-list.js';
// @codekit-append 'editor/helpers.js';
// @codekit-append 'editor/class-ditty-editor.js';
// @codekit-append 'editor/class-ditty-settings-panel.js';
// @codekit-append 'editor/class-ditty-items-panel.js';
// @codekit-append 'editor/class-ditty-item-types-panel.js';
// @codekit-append 'editor/class-ditty-item-editor-panel.js';
// @codekit-append 'editor/class-ditty-displays-panel.js';
// @codekit-append 'editor/class-ditty-display-editor-panel.js';
// @codekit-append 'editor/class-ditty-layout-variations-panel.js';
// @codekit-append 'editor/class-ditty-layouts-panel.js';
// @codekit-append 'editor/class-ditty-layout-html-editor-panel.js';
// @codekit-append 'editor/class-ditty-layout-css-editor-panel.js';
// @codekit-append 'editor_hooks/ditty-list-display-editor.js';
// @codekit-append 'editor_hooks/ditty-ticker-display-editor.js';
// @codekit-append 'editor_hooks/ditty-item-edit-editor.js';

jQuery(function($) {
	// Setup strict mode
	(function() {
		'use strict';

		/**
		 * Close down editor display panels appropriately
		 *
		 * @since    3.0
		 * @return   null
		 */
		$('body').on('ditty_editor_before_panel_update', function(event, slideId, $slide, prevSlideId, $prevSlide, editor) {
			// Disable any delayed Ditty updates
			editor.delayedSubmitDisable();

			// Setup protip
			$.protip({
				defaults: {
					position: 'top',
					size: 'small',
					scheme: 'black',
					classes: 'ditty-protip',
				},
			});

			switch (slideId) {
				case 'settings':
					if (!$slide.hasClass('init')) {
						$slide.ditty_settings_panel({ editor: editor });
					}
					break;
				case 'items':
					if ($slide.hasClass('init')) {
						$slide.ditty_items_panel('panelVisible');
					} else {
						$slide.ditty_items_panel({ editor: editor });
					}
					break;
				case 'displays':
					$slide.find('.ditty-data-list__item').removeClass('editing');
					if ($slide.hasClass('init')) {
						$slide.ditty_displays_panel('panelVisible');
					} else {
						$slide.ditty_displays_panel({
							editor: editor,
						});
					}
					break;
				case 'item_types':
					$slide.find('.ditty-data-list__item').removeClass('editing');
					if ($slide.hasClass('init')) {
						$slide.ditty_item_types_panel('panelVisible');
					} else {
						$slide.ditty_item_types_panel({
							editor: editor,
						});
					}
					break;
				case 'item_editor':
					$slide.ditty_item_editor_panel({ editor: editor });
					break;
				case 'display_editor':
					$slide.ditty_display_editor_panel({ editor: editor });
					break;
				case 'layouts':
					if (!('layoutHtmlEditor' === prevSlideId || 'layoutCssEditor' === prevSlideId)) {
						$slide.ditty_layouts_panel({ editor: editor });
					}
					break;
				case 'layout_variations':
					if ($slide.hasClass('init')) {
						$slide.ditty_layout_variations_panel('panelVisible');
					} else {
						$slide.ditty_layout_variations_panel({ editor: editor });
					}
					break;
				case 'layout_html_editor':
					$slide.ditty_layout_html_editor_panel({
						editor: editor,
						prevPanel: prevSlideId,
					});
					break;
				case 'layout_css_editor':
					$slide.ditty_layout_css_editor_panel({
						editor: editor,
						prevPanel: prevSlideId,
					});
					break;
				default:
					break;
			}
		});

		/**
		 * Setup the editor display panel when ready
		 *
		 * @since    3.0
		 * @return   null
		 */
		$('body').on('ditty_editor_panel_removed', function(event, slideId, $slide) {
			switch (slideId) {
				case 'item_editor':
					if ($slide.ditty_item_editor_panel) {
						$slide.ditty_item_editor_panel('destroy');
					}
					break;
				case 'display_editor':
					if ($slide.ditty_display_editor_panel) {
						$slide.ditty_display_editor_panel('destroy');
					}
					break;
				case 'layouts':
					if ($slide.ditty_layouts_panel) {
						$slide.ditty_layouts_panel('destroy');
					}
					break;
				case 'layout_variations':
					if ($slide.ditty_layout_variations_panel) {
						$slide.ditty_layout_variations_panel('destroy');
					}
					break;
				case 'layout_html_editor':
					if ($slide.ditty_layout_html_editor_panel) {
						$slide.ditty_layout_html_editor_panel('destroy');
					}
					break;
				case 'layout_css_editor':
					if ($slide.ditty_layout_css_editor_panel) {
						$slide.ditty_layout_css_editor_panel('destroy');
					}
					break;
				default:
					break;
			}
		});
	})();
});

/**
 * Initialize an editor
 *
 * @since    3.0.12
 * @return   null
 */
function dittyEditorInit(ditty) {
	if (!ditty) {
		return false;
	}
	jQuery('#ditty-editor__settings').ditty_editor({
		ditty: ditty,
	});
}
dittyEditorInit();
