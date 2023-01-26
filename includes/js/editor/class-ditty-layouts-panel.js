/**
 * Ditty Layouts Panel
 *
 * @since		3.0
 * @return	null
*/
( function ( $ ) {
  'use strict';

  var defaults = {
	  editor: null
  };

  var Ditty_Layouts_Panel = function ( elmt, options ) {
    this.elmt         			= elmt;
    this.settings     			= $.extend( {}, defaults, $.ditty_layouts_panel.defaults, options );
    this.$elmt        			= $( elmt );
    this.$back							= this.$elmt.find( '.ditty-editor-options__back' );
    this.$list							= $( elmt ).find( '.ditty-data-list__items' );
		this.$editorItem 				= dittyVars.editor.currentItem;
    this.editorDittyId			= this.$editorItem.data( 'ditty_id' );
    this.editorItemId				= this.$editorItem.data( 'item_id' );
		this.editorItemtype			= this.$editorItem.data( 'item_type' );
		this.editorItemValue		= this.$editorItem.data( 'item_value' );
    this.$editorVariation 	= dittyVars.editor.currentLayoutVariation;
    this.editorVariationId	= this.$editorVariation.data( 'layout_variation_id' );
    this.editorLayoutId			= this.$editorVariation.data( 'layout_id' );
    this._init();
  };


  Ditty_Layouts_Panel.prototype = {

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
	    this.$elmt.on( 'click', '.ditty-data-list__item', { self: this }, this._selectLayout );
	    this.$elmt.on( 'click', '.ditty-data-list__item__edit_html', { self: this, editType: 'html' }, this._editLayout );
	    this.$elmt.on( 'click', '.ditty-data-list__item__edit_css', { self: this, editType: 'css' }, this._editLayout );
	    this.$elmt.on( 'click', '.ditty-data-list__item__clone', { self: this }, this._cloneLayout );
	    this.$elmt.on( 'click', '.ditty-data-list__item__delete', { self: this }, this._deleteLayout );
			this.$elmt.on( 'click', '.ditty-data-list__item__save', { self: this }, this._saveLayout );
			this.$list.on( 'click', '.ditty-editor-layout > a', { self: this }, this._actionClick );
			$( 'body' ).on( 'ditty_editor_save_ditty_response', { self: this }, this._dittyEditorSaveResponse );
	    
	    // Activate the current layout
	    this._activateLayout( this.$list.find( '#ditty-editor-layout--' + this.editorLayoutId ) );
    },

    /**
		 * Activate a layout
		 *
		 * @since		3.0
		 * @return	null
		*/
    _activateLayout: function ( $layout ) {
	    this.$list.find( '.ditty-editor-layout' ).removeClass( 'active' );
	    $layout.addClass( 'active' );
    },
    
    /**
     * Return to the item list
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _showVariationsList: function() {
			this.settings.editor.$panels.ditty_slider( 'options', 'transition', 'slideRight' );
			this.settings.editor.$panels.ditty_slider( 'showSlideById', 'layout_variations' );
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
		  self._showVariationsList();
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
					var $editorItem = $( '#ditty-editor-layout--' + draftId );
					if ( $editorItem.length ) {
						$editorItem.attr( 'id', 'ditty-editor-layout--' + newId );
						$editorItem.attr( 'data-layout_id', newId ).data( 'layout_id', newId );
					}
				} );
			}
		},
		
		/**
		 * Update new layout ids on save
		 *
		 * @since    3.0
		 * @return   null
		*/
		// dittyUpdatedDraftLayouts: function( variationType, layoutId ) {
		// 	var self = this;
		// 	
		// 	$.each( $( '.ditty-editor-item' ), function() {
		// 		var itemID = $( this ).data( 'item_id' ),
		// 				itemType = $( this ).data( 'item_type' ),
		// 				layoutValue = $( this ).data( 'layout_value' );
		// 		$.each( layoutValue, function( type, id ) {
		// 			if ( String( itemType ) === String( self.editorItemtype ) && String( type ) === String( variationType ) ) {
		// 				layoutValue[type] = String( layoutId );
		// 				dittyDraftItemUpdateData( self, itemID, 'layout_value', layoutValue );
		// 			}
		// 		} );
		// 	} );
		// },

    /**
		 * Load a new layout
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _selectLayout: function( e ) { 
		  e.preventDefault();
		  var self = e.data.self;
		  if ( $( e.target ).parent().is( 'a' ) ) {
				return false;
			}
			
			var $layout 			= $( e.target ).is( '.ditty-data-list__item' ) ? $( e.target ) : $( e.target ).parents( '.ditty-data-list__item' ),
					layoutId 			= $layout.data( 'layout_id' ),
					layoutVersion	= $layout.data( 'layout_version' ),
					layoutValue 	= self.$editorItem.data( 'layout_value' );

			if ( $layout.hasClass( 'active' ) ) {
				return false;
			}
			$.each( layoutValue, function( type ) {
				if ( self.editorVariationId === type ) {
					layoutValue[type] = String( layoutId );
				}
			} );

			// Highlight the active layout
			self.settings.editor.updateStart(); // Start the update overlay
			//self.dittyUpdatedDraftLayouts( self.editorVariationId, layoutId );
			//dittyDraftItemUpdateData( self, self.editorItemId, 'layout_id', layoutId );
			dittyDraftItemUpdateData( self, self.editorItemId, 'layout_value', layoutValue );
			self._activateLayout( $layout );

			// Use ajax to load the new layout
			var data = {
				action				: 'ditty_editor_select_layout',
				layout_id			: layoutId,
				item_id				: self.editorItemId,
				ditty_id			: self.editorDittyId,
				draft_values 	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				self.settings.editor.updateStop(); // Stop the update overlay
				if ( response.display_items ) {
					//self.settings.editor.ditty.updateItems( response.display_items, false, false, true );
					self.settings.editor.ditty.updateItems( response.display_items, self.editorItemId );
				}
				if ( response.editor_item ) {
					var $newEditorItem = $( response.editor_item );
					self.$editorItem.replaceWith( $newEditorItem );
				}

				// Update the current Ditty
				self.$editorItem.attr( 'data-layout_value', layoutValue ).data( 'layout_value', layoutValue );
				self.$editorVariation.attr( 'data-layout_id', layoutId ).data( 'layout_id', layoutId );
				self.$editorVariation.find( '.ditty-layout-variation__template > span' ).text( response.layout_label );
				if ( layoutVersion ) {
					self.$editorVariation.find( '.ditty-layout-variation__template > small' ).text( '(' + layoutVersion + ')' );
				} else {
					self.$editorVariation.find( '.ditty-layout-variation__template > small' ).text( '' );
				}
			}, 'json' );	
    },

		/**
		 * Clone a layout
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _cloneLayout: function( e ) { 
		  e.preventDefault();
		  var self 				= e.data.self,
		  		$button 		= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 			= $button.parents( '.ditty-data-list__item' ),
					layoutId 		= $item.data( 'layout_id' );
			
			self.settings.editor.updateStart(); // Start the update overlay
			
			// Use ajax to clone the layout
			var data = {
				action				: 'ditty_editor_layout_clone',
				layout_id			: layoutId,
				draft_values	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response.editor_layout ) {
					var $new = $( response.editor_layout );
			    $new.hide();
			    $item.after( $new );
			    $new.slideDown();
		    }
				if ( response.draft_id && response.draft_meta ) {
					dittyDraftLayoutUpdate( self, response.draft_id, null, response.draft_meta );
				}
		    self.settings.editor.updateStop(); // Stop the update overlay
			}, 'json' );
		},
		
		/**
		 * Save a layout preview
		 *
		 * @since    3.0
		 * @return   null
		*/
		_saveLayout: function( e ) { 
			e.preventDefault();
			var self 				= e.data.self,
					$button 		= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 			= $button.parents( '.ditty-data-list__item' ),
					layoutId 		= $item.data( 'layout_id' );
			
			//self.settings.editor.updateStart(); // Start the update overlay
			
			// Use ajax to clone the layout
			// var data = {
			// 	action				: 'ditty_editor_layout_save_preview',
			// 	layout_id			: layoutId,
			// 	item_id				: self.editorItemId,
			// 	draft_values	: self.settings.editor.getDraftValues(),
			// 	security			: dittyVars.security
			// };
			// $.post( dittyVars.ajaxurl, data, function( response ) {
			// 	console.log( response );
			// 	self.settings.editor.updateStop(); // Stop the update overlay
			// }, 'json' );
		},

		/**
		 * Delete a layout
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _deleteLayout: function( e ) { 
		  e.preventDefault();

		  var self 			= e.data.self,
		  		$button 	= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 		= $button.parents( '.ditty-data-list__item' ),
					$nextItem	= null,
					layoutId 	= $item.data( 'layout_id' );

			// Find the layout to load if this one is active
			if ( $item.hasClass( 'active' ) ) {
				if ( $item.prev().length ) {
					$nextItem = $item.prev();
				} else if ( $item.next().length ) {
					$nextItem = $item.next();
				}
			}
			
			// Remove the layout
			$item.slideUp( function() {
				$( this ).remove();
			} );
			dittyDraftLayoutDelete( self, layoutId );
			
			// Possibly select another layout
			if ( null !== $nextItem ) { 
				$nextItem.trigger( 'click' );
			}
		},
  
    /**
		 * Edit the html or css of a layout
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _editLayout: function( e ) { 
		  e.preventDefault();
		  var self 				= e.data.self,
		  		editType 		= e.data.editType,
		  		$button 		= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$layout 		= $button.parents( '.ditty-data-list__item' ),
					layoutId 		= $layout.data( 'layout_id' );
					
			self.settings.editor.updateStart(); // Start the update overlay
			self.$list.find( '.ditty-data-list__item' ).removeClass( 'editing' );
			$layout.addClass( 'editing' );
			
			// Load the new display fields		
			var data = {
				action				: 'ditty_editor_layout_fields',
				layout_id			: layoutId,
				item_id				: self.editorItemId,
				item_type			: self.editorItemtype,
				item_value		: self.editorItemValue,
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
		 * Trigger an actions when a button is clicked
		 *
		 * @since    3.0
		 * @return   null
		*/
		_actionClick: function( e ) { 
			e.preventDefault();
			var self 				= e.data.self,
					$button 		= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$layout 		= $button.parents( '.ditty-data-list__item' ),
					layoutId 		= $layout.data( 'layout_id' );
					
			dittyVars.editor.currentLayout = $layout; // Set the current layout

			$( 'body' ).trigger( 'ditty_editor_layout_action_click', [$button, $layout, layoutId, self.editorDittyId, self.settings.editor] );
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

	    this.$elmt.trigger( 'ditty_layouts_panel_' + fn, params );
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
	      this.settings = $.extend( {}, defaults, $.ditty_layouts_panel.defaults, key );
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
	    
	    // Remove the init class
	    this.$elmt.removeClass( 'init' );

			// Remove actions
			this.$back.off( 'click', { self: this }, this._backClick );
	    this.$elmt.off( 'click', '.ditty-data-list__item', { self: this }, this._selectLayout );
			this.$elmt.off( 'click', '.ditty-data-list__item__edit_html', { self: this, editType: 'html' }, this._editLayout );
			this.$elmt.off( 'click', '.ditty-data-list__item__edit_css', { self: this, editType: 'css' }, this._editLayout );
			this.$elmt.off( 'click', '.ditty-data-list__item__clone', { self: this }, this._cloneLayout );
			this.$elmt.off( 'click', '.ditty-data-list__item__delete', { self: this }, this._deleteLayout );
			this.$elmt.off( 'click', '.ditty-data-list__item__save', { self: this }, this._saveLayout );
			this.$list.off( 'click', '.ditty-editor-layout > a', { self: this }, this._actionClick );
			$( 'body' ).off( 'ditty_editor_save_ditty_response', { self: this }, this._dittyEditorSaveResponse );

	    this.elmt._ditty_layouts_panel = null;
    }
  };

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_layouts_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_layouts_panel ) {
        	this._ditty_layouts_panel = new Ditty_Layouts_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_layouts_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Layouts_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Layouts_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_layouts_panel = {};
  $.ditty_layouts_panel.defaults = defaults;

} )( jQuery );
