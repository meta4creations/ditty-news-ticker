/**
 * Ditty Layout Variations Panel
 *
 * @since		3.0
 * @return	null
*/
( function ( $ ) {
	'use strict';

	var defaults = {
		editor: null
	};

	var Ditty_Layout_Variations_Panel = function ( elmt, options ) {
		this.elmt         			= elmt;
		this.settings     			= $.extend( {}, defaults, $.ditty_layout_variations_panel.defaults, options );
		this.$elmt        			= $( elmt );
		this.$back							= this.$elmt.find( '.ditty-editor-options__back' );
		this.$list							= $( elmt ).find( '.ditty-data-list__items' );
		this.$editorItem 				= dittyVars.editor.currentItem;
		this.editorItemLabel		= this.$editorItem.find( '.ditty-data-list__item__label' ).text();
		this.editorDittyId			= this.$editorItem.data( 'ditty_id' );
		this.editorItemId				= this.$editorItem.data( 'item_id' );
		this.editorItemType			= this.$editorItem.data( 'item_type' );
		this.editorItemValue		= this.$editorItem.data( 'item_value' );

		this._init();
	};


	Ditty_Layout_Variations_Panel.prototype = {

		/**
		 * Initialize the panel
		 *
		 * @since		3.0
		 * @return	null
		*/
		_init: function () {
			this.$elmt.addClass( 'init' );
			
			// Add actions
			this.$back.on( 'click', { self: this }, this._backClick );
			this.$elmt.on( 'click', '.ditty-layout-variation__change', { self: this }, this._changeTemplate );
			this.$elmt.on( 'click', '.ditty-layout-variation__edit_html', { self: this, editType: 'html' }, this._editLayout );
			this.$elmt.on( 'click', '.ditty-layout-variation__edit_css', { self: this, editType: 'css' }, this._editLayout );
			$( 'body' ).on( 'ditty_editor_save_ditty_response', { self: this }, this._dittyEditorSaveResponse );
		},
		
		/**
		 * Return to the item list
		 *
		 * @since    3.0
		 * @return   null
		*/
		_showItemList: function() {
			this.settings.editor.$panels.ditty_slider( 'options', 'transition', 'slideRight' );
			this.settings.editor.$panels.ditty_slider( 'showSlideById', 'items' );
		},
		
		/**
		 * Cancel click
		 *
		 * @since    3.0
		 * @return   null
		*/
		_backClick: function( e ) {
			e.preventDefault();
			var self = e.data.self;
			self._showItemList();
		},
		
		/**
		 * Update new item ids on save
		 *
		 * @since    3.0
		 * @return   null
		*/
		_dittyEditorSaveResponse: function( e, response ) {
			var self = e.data.self;
			if ( response.ditty_new_layout_ids ) {
				$.each( response.ditty_new_layout_ids, function( draftId, newId ) {
					var $variationItem = $( '.ditty-layout-variation[data-layout_id="' + draftId + '"]' );
					if ( $variationItem.length ) {
						$variationItem.attr( 'data-layout_id', newId ).data( 'layout_id', newId );
					}
				} );
			}
		},

		/**
		 * Load a new layout
		 *
		 * @since    3.0
		 * @return   null
		*/
		_changeTemplate: function( e ) { 
			e.preventDefault();
			var self = e.data.self;

			var $button 					= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$layoutVariation 	= $button.parents( '.ditty-data-list__item' ),
					layoutId					= $layoutVariation.data( 'layout_id' ),
					variationId 			= $layoutVariation.data( 'layout_variation_id' ),
					variationLabel 		= $layoutVariation.data( 'layout_variation_label' );

			dittyVars.editor.currentLayoutVariation = $layoutVariation; // Set the current layout variation
					
			self.settings.editor.updateStart(); // Start the update overlay
			$layoutVariation.addClass( 'editing' );

			// Load the item fields		
			var data = {
				action					: 'ditty_editor_layouts',
				ditty_id				: self.editorDittyId,
				item_type				: self.editorItemType,
				variation_id		: variationId,
				variation_label	: variationLabel,
				layout_id				: layoutId,
				draft_values		: self.settings.editor.getDraftValues(),
				security				: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response ) {
					var html = '<div class="ditty-editor__panel ditty-editor__panel--layouts">' + response + '</div>';		
					self.settings.editor.panelOptions( 'transition', 'slideLeft' );
					self.settings.editor.showPanel( 'layouts', html );
				}
			} );	
		},
	
		/**
		 * Edit the html or css of a layout
		 *
		 * @since    3.0.12
		 * @return   null
		*/
		_editLayout: function( e ) { 
			e.preventDefault();
			var self 							= e.data.self,
					editType 					= e.data.editType,
					$button 					= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$layoutVariation 	= $button.parents( '.ditty-data-list__item' ),
					layoutId 					= $layoutVariation.data( 'layout_id' );
					
			dittyVars.editor.currentLayoutVariation = $layoutVariation; // Set the current layout variation
			
			self.settings.editor.updateStart(); // Start the update overlay
			self.$list.find( '.ditty-data-list__item' ).removeClass( 'editing' );
			$layoutVariation.addClass( 'editing' );

			var itemIds = [],
					items = self.settings.editor.ditty.options( 'items' );
			
			$.each( items, function( index, item ) {
				itemIds.push( item.uniqId );
			} ); 

			// Load the new display fields		
			var data = {
				action				: 'ditty_editor_layout_fields',
				layout_id			: layoutId,
				item_type			: self.editorItemType,
				item_value		: self.editorItemValue,
				ditty_id			: self.editorDittyId,
				item_id				: self.editorItemId,
				item_ids			: itemIds,
				edit_type			: editType,
				draft_values	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response ) {
					var slideId = 'layout_' + editType + '_editor',
							html = '<div class="ditty-editor__panel ditty-editor__panel--' + slideId + '">' + response.form + '</div>';
					
					self.settings.editor.panelOptions( 'transition', 'slideLeft' );
					self.settings.editor.showPanel( slideId, html );
				}
			}, 'json' );
		}, 

		/**
		 * Do actions when panel is visible
		 *
		 * @since  	3.0
		 * @return 	null
		*/
		panelVisible: function () {
			this.$list.find( '.ditty-layout-variation' ).removeClass( 'editing' );
		},

		/**
		 * Setup triggers
		 *
		 * @since  	3.0
		 * @return 	null
		*/
		trigger: function ( fn, customParams ) {
			var params = [this.settings];  
			if ( customParams ) {
				params = customParams;
			}

			this.$elmt.trigger( 'ditty_layout_variations_panel_' + fn, params );
			if ( typeof this.settings[fn] === 'function' ) {
				this.settings[fn].apply( this.$elmt, params );
			}
		},	
		
		/**
		 * Allow settings to be modified
		 *
		 * @since  	3.0
		 * @return 	null
		*/
		options: function ( key, value ) {
			if ( typeof key === 'object' ) {
				this.settings = $.extend( {}, defaults, $.ditty_layout_variations_panel.defaults, key );
			} else if ( typeof key === 'string' ) {
				if ( value === undefined ) {
					return this.settings[key];
				}
				this.settings[key] = value;
			} else {
				return this.settings;
			}

			this.trigger( 'options_update' );
		},
		
		/**
		 * Destroy the editor
		 *
		 * @since  	3.0
		 * @return 	null
		*/
		destroy: function () {
			this.$elmt.removeClass( 'init' );
			this.$back.off( 'click', { self: this }, this._backClick );
			this.$elmt.off( 'click', '.ditty-layout-variation__change', { self: this }, this._changeTemplate );
			this.$elmt.off( 'click', '.ditty-layout-variation__edit_html', { self: this, editType: 'html' }, this._editLayout );
			this.$elmt.off( 'click', '.ditty-layout-variation__edit_css', { self: this, editType: 'css' }, this._editLayout );
			$( 'body' ).off( 'ditty_editor_save_ditty_response', { self: this }, this._dittyEditorSaveResponse );
			
			this.elmt._ditty_layout_variations_panel = null;
		}
	};

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
	$.fn.ditty_layout_variations_panel = function( options ) {
		var args = arguments,
				error = false,
				returns;

		if ( options === undefined || typeof options === 'object' ) {
			return this.each( function () {
				if ( ! this._ditty_layout_variations_panel ) {
					this._ditty_layout_variations_panel = new Ditty_Layout_Variations_Panel( this, options );
				}
			});
		} else if ( typeof options === 'string' ) {
			this.each( function () {
				var instance = this._ditty_layout_variations_panel;

				if ( ! instance ) {
					throw new Error( 'No Ditty_Layout_Variations_Panel applied to this element.' );
				}
				if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
					returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
				} else {
					error = true;
				}
			} );

			if ( error ) {
				throw new Error( 'No method "' + options + '" in Ditty_Layout_Variations_Panel.' );
			}

			return returns !== undefined ? returns : this;
		}
	};

	$.ditty_layout_variations_panel = {};
	$.ditty_layout_variations_panel.defaults = defaults;

} )( jQuery );