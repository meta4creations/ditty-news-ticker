/**
 * Ditty Editor Items Panel
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  editor: null
  };

  var Ditty_Items_Panel = function ( elmt, options ) {
    this.elmt         = elmt;
    this.settings     = $.extend( {}, defaults, $.ditty_items_panel.defaults, options );
    this.$elmt        = $( elmt );
    this.$add       	= $( elmt ).find( '.ditty-editor-options__add' );
    this.$contents    = $( elmt ).find( '.ditty-editor__panel__contents' );
    this.$list				= $( elmt ).find( '.ditty-data-list' );
    this.$listItems		= $( elmt ).find( '.ditty-data-list__items' );
		this.isEmptyTicker	= false;

    this._init();
  };


  Ditty_Items_Panel.prototype = {

    /**
		 * Initialize the panel
		 *
		 * @since		3.0.12
		 * @return	null
		*/
    _init: function () {
	    this.$elmt.addClass( 'init' );

			// Add actions
			this.settings.editor.$elmt.on( 'ditty_editor_active_items_update', { self: this }, this._dittyActiveItemsUpdated );
	    this.$add.on( 'click', { self: this }, this._add_item );
			this.$elmt.on( 'click', '.ditty-data-list__item', { self: this }, this._showItem );
			this.$elmt.on( 'click', '.ditty-data-list__item__icon', { self: this }, this._editType );
			this.$elmt.on( 'click', '.ditty-data-list__item__edit', { self: this }, this._editItem );
			this.$elmt.on( 'click', '.ditty-data-list__item__layout', { self: this }, this._editLayoutVariations );
			this.$elmt.on( 'click', '.ditty-data-list__item__clone', { self: this }, this._cloneItem );
			this.$elmt.on( 'click', '.ditty-data-list__item__delete', { self: this }, this._deleteItem );
			this.$list.on( 'click', '.ditty-editor-item > a', { self: this }, this._actionClick );
			$( 'body' ).on( 'ditty_editor_save_ditty_response', { self: this }, this._dittyEditorSaveResponse );

			// Make sure there is at least one item
			if ( 1 > this.$elmt.find( '.ditty-data-list__item' ).length ) {
				this.isEmptyTicker = true;
				this.$add.trigger( 'click' );
			}
			
			// Initialize list sorting
			this._initializeSorting();
			
			// Highlight the current items
			this._highlightListItems( this.settings.editor.activeItems );
    },
		
		/**
		 * Update new layout ids on save
		 *
		 * @since    3.0
		 * @return   null
		*/
		dittyUpdateSavedDraftLayouts: function( draftId, newID ) {
			$.each( $( '.ditty-editor-item' ), function() {
				var layoutValue = $( this ).data( 'layout_value' );
				$.each( layoutValue, function( type, id ) {
					if ( String( id ) === String( draftId ) ) {
						layoutValue[type] = String( newID );
					}
				} );
				$( this ).attr( 'data-layout_value', layoutValue ).data( 'layout_value', layoutValue );
			} );
		},

		/**
		 * Update new item ids on save
		 *
		 * @since    3.0
		 * @return   null
		*/
		_dittyEditorSaveResponse: function( e, response ) {
			var self = e.data.self;
			if ( response.ditty_new_item_ids ) {
				$.each( response.ditty_new_item_ids, function( draftId, newId ) {
					var $editorItem = $( '#ditty-editor-item--' + draftId );
					if ( $editorItem.length ) {
						$editorItem.attr( 'id', 'ditty-editor-item--' + newId );
						$editorItem.attr( 'data-item_id', newId ).data( 'item_id', newId );
					}
				} );
			}
			if ( response.ditty_new_layout_ids ) {
				$.each( response.ditty_new_layout_ids, function( draftId, newID ) {
					self.dittyUpdateSavedDraftLayouts( draftId, newID );
				} );
			}
		},

		/**
		 * Highlight a list item
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _highlightListItem: function( item ) { 
		  var itemId = item.id + '';
	    itemId = itemId.split( '_' );
			this.$elmt.find( '#ditty-editor-item--' + itemId[0] ).addClass( 'active' );
		},

		/**
		 * Loop through the active items and highlight
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _highlightListItems: function( items ) {
		  this.$elmt.find( '.ditty-data-list__item' ).removeClass( 'active' );
		  var self = this; 
		  if ( Array.isArray( items ) ) {
			  $.each( items, function( i, item ) {
				  self._highlightListItem( item );
				});
		  } else {
			  self._highlightListItem( items );
		  }
		},

		/**
		 * Update the highlighted items when active
		 *
		 * @since    3.0
		 * @return   null
		*/
		_dittyActiveItemsUpdated: function( e, ditty, items ) {
	    var self = e.data.self;
			self._highlightListItems( items );
    },
    
    /**
		 * Initialize item sorting
		 *
		 * @since    3.0
		 * @return   null
		*/
    _initializeSorting: function() {
	    var self = this;   
	    this.$listItems.sortable( {
				handle: '.ditty-data-list__item__move',
				items: '.ditty-data-list__item',
				axis: 'y',
				start: function( event, ui ) {
					var $item = $( ui.item );
					$item.addClass( 'ditty-data-list__item--moving' );
				},
				stop: function( event, ui ) {
					var $item = $( ui.item );
					$item.removeClass( 'ditty-data-list__item--moving' );
					self.settings.editor.addUpdate( 'item_order', self.settings.editor.dittyId ); // Add to the update queue
				},
				update: function() {
					self._updateItemIndexes( 'updateDitty' );
				}
			} );
    },
    
    /**
		 * Show a specific item
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _showItem: function( e ) { 
		  e.preventDefault();
		  var self = e.data.self;
		  if ( ! $( e.target ).is( 'a' ) && ! $( e.target ).parent().is( 'a' ) ) {
				var $item 	= $( e.target ).is( '.ditty-data-list__item' ) ? $( e.target ) : $( e.target ).parents( '.ditty-data-list__item' ),
						itemId 	= $item.data( 'item_id' );
	
				// Update the display element
				self.settings.editor.ditty.showItem( itemId );
			}
    },
		
		/**
		 * Trigger an actions when a button is clicked
		 *
		 * @since    3.0
		 * @return   null
		*/
		_actionClick: function( e ) { 
			e.preventDefault();
			var self 			= e.data.self,
					$button 	= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 		= $button.parents( '.ditty-data-list__item' ),
					dittyId 	= $item.data( 'ditty_id' ),
					itemId 		= $item.data( 'item_id' );
					
			dittyVars.editor.currentItem = $item; // Set the current item

			$( 'body' ).trigger( 'ditty_editor_item_action_click', [$button, $item, itemId, dittyId, self.settings.editor] );
		},

    /**
		 * Edit a item type
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _editType: function( e ) { 
		  e.preventDefault();
		  var self = e.data.self;
		  
		  var $button = $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 	= $button.parents( '.ditty-data-list__item' );

			$item.trigger( 'click' );
			$item.addClass( 'editing' );
			self.settings.editor.updateStart(); // Start the update overlay
			self.settings.editor.panelOptions( 'transition', 'slideRight' );
			self.settings.editor.showPanel( 'item_types' );
		},
		
		/**
		 * Edit a item
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _editItem: function( e ) { 
		  e.preventDefault();
		  var self = e.data.self;
		  
		  var $button = $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 	= $button.parents( '.ditty-data-list__item' ),
					itemId 	= $item.data( 'item_id' );

			$item.trigger( 'click' );
			$item.addClass( 'editing' );
			self.settings.editor.updateStart(); // Start the update overlay
			
			// var data = {
			// 	hook		: 'ditty_editorItem_fields',
			// 	itemId	: itemId,
			// };
			// ditty_editor_ajax( data, self );
			
			// Load the item fields		
			var data = {
				action				: 'ditty_editor_item_fields',
				item_id				: itemId,
				draft_values 	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response ) {
					var html = '<div class="ditty-editor__panel ditty-editor__panel--item_editor">' + response + '</div>';
					self.settings.editor.panelOptions( 'transition', 'slideLeft' );
					self.settings.editor.showPanel( 'item_editor', html );
				}
			} );
		},
		
		// _ditty_editorItem_fields: function( e, data ) {
		// 	var self = e.data.self;
		// 	if ( data.html ) {
		// 		var html = '<div class="ditty-editor__panel ditty-editor__panel--item_editor">' + data.html + '</div>';
		// 		self.settings.editor.panelOptions( 'transition', 'slideLeft' );
		// 		self.settings.editor.showPanel( 'item_editor', html );
		// 	}
		// },
		
		/**
		 * Edit a item layout variation
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _editLayoutVariations: function( e ) { 
		  e.preventDefault();
		  var self = e.data.self;
		  
		  var $button 			= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 				= $button.parents( '.ditty-data-list__item' ),
					itemType 			= $item.data( 'item_type' ),
					layoutValue 	= $item.data( 'layout_value' ),
					itemLabel 		= $item.find( '.ditty-data-list__item__label' ).html();

			$item.trigger( 'click' );
			$item.addClass( 'editing' );
			self.settings.editor.updateStart(); // Start the update overlay

			// Load the item fields		
			var data = {
				action				: 'ditty_editor_layout_variations',
				ditty_id			: self.settings.editor.dittyId,
				item_type			: itemType,
				item_label		: itemLabel,
				layout_value	: layoutValue,
				draft_values 	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response ) {
					var html = '<div class="ditty-editor__panel ditty-editor__panel--layout-variations">' + response + '</div>';
					self.settings.editor.panelOptions( 'transition', 'slideLeft' );
					self.settings.editor.showPanel( 'layout_variations', html );
				}
			} );
		},

		/**
		 * Add a new item
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _add_item: function( e ) { 
		  e.preventDefault();
		  var self 				= e.data.self,
					dittyId 	= self.settings.editor.dittyId;

			//self.settings.editor.updateStart(); // Start the update overlay
			
			// Load the new display fields		
			var data = {
				action				: 'ditty_editor_item_add',
				ditty_id			: dittyId,
				draft_values 	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response.display_items ) {
					if ( self.isEmptyTicker ) {
						var placeholderItems = self.settings.editor.ditty.options( 'items' );
						$.each( placeholderItems, function( index, data ) {
							self.settings.editor.ditty.deleteItem( data.id );
						} );
						self.isEmptyTicker = false;
					}
			    $.each( response.display_items, function( key, value ) {
				    self.settings.editor.ditty.addItem( value, 0 );
					} ); 		
		    }
				if ( response.editor_item ) {
					var $new = $( response.editor_item );
					$new.hide();
					self.$listItems.prepend( $new );
					$new.slideDown();
					self._updateItemIndexes();
					//self.settings.editor.addUpdate( 'item_add', value.id ); // Add to the update queue
				}
				if ( response.draft_id && response.draft_data ) {
					dittyDraftItemUpdateData( self, response.draft_id, null, response.draft_data );
				}
				if ( response.draft_id && response.draft_meta ) {
					dittyDraftItemUpdateMeta( self, response.draft_id, null, response.draft_meta );
				}
				//.settings.editor.updateStop(); // Stop the update overlay
			}, 'json' );
		},
		
		/**
		 * Clone a item
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _cloneItem: function( e ) { 
		  e.preventDefault();
		  var self 		= e.data.self,
					$button = $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 	= $button.parents( '.ditty-data-list__item' ),
					itemId = $item.data( 'item_id' );

			self.settings.editor.updateStart(); // Start the update overlay
			
			// Load the new display fields		
			var data = {
				action				: 'ditty_editor_item_clone',
				item_id				: itemId,
				draft_values	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response.display_items && response.display_items.length ) {
					self.settings.editor.ditty.updateItems( response.display_items, itemId, 'after' );
				}
				if ( response.editor_item ) {
					var $new = $( response.editor_item );
			    $new.hide();
			    $item.after( $new );
			    $new.slideDown();
			    self._updateItemIndexes();
		    }
				if ( response.draft_id && response.draft_data ) {
					dittyDraftItemUpdateData( self, response.draft_id, null, response.draft_data );
				}
				if ( response.draft_id && response.draft_meta ) {
					dittyDraftItemUpdateMeta( self, response.draft_id, null, response.draft_meta );
				}
				self.settings.editor.updateStop(); // Stop the update overlay	
			}, 'json' );
		},

		/**
		 * Delete a item
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _deleteItem: function( e ) { 
		  e.preventDefault();
		  var self 			= e.data.self,
					$button 	= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 		= $button.parents( '.ditty-data-list__item' ),
					itemId 		= $item.data( 'item_id' );

			if ( 1 >= self.$elmt.find( '.ditty-data-list__item' ).length ) {
				self.$add.trigger( 'click' );
			}
			
			// Remove the item from the editor and ditty
			$item.slideUp( function() {
				$( this ).remove();
			} );
			self.settings.editor.ditty.deleteItem( itemId );
			dittyDraftItemDelete( self, itemId ); // Remove the draft data
		},
		
		/**
		 * Update the item order
		 *
		 * @since		3.0
		 * @return	null
		*/
    _updateItemIndexes: function( action ) {
		  var self = this,
	    		itemIds = [];

	    this.$elmt.find( '.ditty-data-list__item' ).each( function( index ) {
				var itemId = $( this ).data( 'item_id' );
				itemIds.push( itemId );
				dittyDraftItemUpdateData( self, itemId, 'item_index', index );
	    } );
	    
			if ( 'updateDitty' === action ) {
		    // Update the ditty with the new order
		    var reorderedItems = dittyItemsReorder( self.settings.editor.ditty.options( 'items' ), itemIds );
				self.settings.editor.ditty.options( 'items', reorderedItems );
			}
	  },

    /**
		 * Do actions when panel is visible
		 *
		 * @since  	3.0
		 * @return 	null
		*/
    panelVisible: function () {
			this.$elmt.find( '.ditty-data-list__item' ).removeClass( 'editing' );
    },

	  /**
		 * Return a specific setting
		 *
		 * @since    3.0
		 * @return   null
		*/
    _options: function ( key ) {
	    return this.settings[key];
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

	    this.$elmt.trigger( 'ditty_items_panel_' + fn, params );
	
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
	      this.settings = $.extend( {}, defaults, $.ditty_items_panel.defaults, key );
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
			this.settings.editor.$elmt.off( 'ditty_editor_active_items_update', { self: this }, this._dittyActiveItemsUpdated );
	    this.$add.off( 'click', { self: this }, this._add_item );
	  	this.$elmt.off( 'click', '.ditty-data-list__item', { self: this }, this._showItem );
			this.$elmt.off( 'click', '.ditty-data-list__item__icon', { self: this }, this._editType );
			this.$elmt.off( 'click', '.ditty-data-list__item__edit', { self: this }, this._editItem );
			this.$elmt.off( 'click', '.ditty-data-list__item__layout', { self: this }, this._editLayoutVariations );
			this.$elmt.off( 'click', '.ditty-data-list__item__clone', { self: this }, this._cloneItem );
			this.$elmt.off( 'click', '.ditty-data-list__item__delete', { self: this }, this._deleteItem );
			this.$list.off( 'click', '.ditty-editor-item > a', { self: this }, this._actionClick );
			$( 'body' ).off( 'ditty_editor_save_ditty_response', { self: this }, this._dittyEditorSaveResponse );
	    
	    this.trigger( 'destroy' );
	    this.elmt._ditty_items_panel = null;
    }
  };

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_items_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_items_panel ) {
        	this._ditty_items_panel = new Ditty_Items_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_items_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Items_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Items_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_items_panel = {};
  $.ditty_items_panel.defaults = defaults;

} )( jQuery );
