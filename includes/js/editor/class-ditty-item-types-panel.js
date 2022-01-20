/**
 * Ditty Editor
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  editor: null
  };

  var Ditty_Item_Types_Panel = function ( elmt, options ) {
	  
    this.elmt         	= elmt;
    this.settings     	= $.extend( {}, defaults, $.ditty_item_types_panel.defaults, options );
    this.$elmt        	= $( elmt );
    this.$back					= this.$elmt.find( '.ditty-editor-options__back' );   
    this.$editorItem 		= this.settings.editor.$panels.find( '.ditty-editor__panel--items' ).find( '.ditty-data-list__item.editing' );
    this.editorDittyId	= this.$editorItem.data( 'ditty_id' );
    this.editorItemId		= this.$editorItem.data( 'item_id' );
    this.currentType		= null;
   
    this._init();
  };


  Ditty_Item_Types_Panel.prototype = {

    /**
		 * Initialize the data list
		 *
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {
	    this.$elmt.addClass( 'init' );

	    // Initialize dynamic fields
      this.settings.editor.initFields( this.$elmt );
			
			// Add actions
			this.$back.on( 'click', { self: this }, this._backClick );
	    this.$elmt.on( 'click', '.ditty-editor-item-type', { self: this }, this._typeClick );
	    
	    this.panelVisible();
    },
    
    /**
     * Return to the item list
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _showItemsList: function() {
			this.settings.editor.panelOptions( 'transition', 'slideLeft' );
			this.settings.editor.showPanel( 'items' );
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
		  self._showItemsList();
    },

    /**
		 * Select a new type
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _typeClick: function( e ) {
		  e.preventDefault();
		  
		  var self			= e.data.self,
		  		$item 		= $( e.target ).is( '.ditty-editor-item-type' ) ? $( e.target ) : $( e.target ).parents( '.ditty-editor-item-type' ),
		  		itemType 	= $item.data( 'item_type' );

		  if ( itemType === self.currentType ) {
			  self._showItemsList();  // Show the display list
			} else {
		
			  self.settings.editor.updateStart(); // Start the update overlay
				//dittyDraftItemUpdateData( self, self.editorItemId, 'item_type', itemType );

				var data = {
					action				: 'ditty_editor_item_type_update',
					item_id				: self.editorItemId,
					item_type			: itemType,
					draft_values 	: self.settings.editor.getDraftValues(),
					security			: dittyVars.security
				};
				$.post( dittyVars.ajaxurl, data, function( response ) {
					if ( response.display_items ) {
						self.settings.editor.ditty.updateItems( response.display_items, self.editorItemId );
					}
					if ( response.editor_item ) {
						var $editorItem = $( response.editor_item );
						self.$editorItem.after( $editorItem );
						self.$editorItem.remove();
						self.$editorItem = $editorItem;
					}
					if ( response.draft_id && response.draft_data ) {
						dittyDraftItemUpdateData( self, response.draft_id, null, response.draft_data );
					}
					self._showItemsList(); // Show the display list
					self.settings.editor.updateStop(); // Stop the update overlay
				}, 'json' );
			}
    },
    
    /**
		 * Do actions when panel is visible
		 *
		 * @since  	3.0.12
		 * @return 	null
		*/
    panelVisible: function () {
	    this.$editorItem 		= this.settings.editor.$panels.find( '.ditty-editor__panel--items' ).find( '.ditty-data-list__item.editing' );
	    this.editorDittyId	= this.$editorItem.data( 'ditty_id' );
	    this.editorItemId		= this.$editorItem.data( 'item_id' );
	    this.currentType		= this.$editorItem.data( 'item_type' );
			this.$elmt.find( '.ditty-editor-item-type' ).removeClass( 'active' );
	    this.$elmt.find( '.ditty-editor-item-type[data-item_type="' + this.currentType + '"]' ).addClass( 'active' );
    },
    
    /**
		 * Do actions when panel is hidden
		 *
		 * @since  	3.0
		 * @return 	null
		*/
    panelHidden: function () {
	    var $editorItemIcon = this.$editorItem.find( '.ditty-data-list__item__icon' ).children( 'i' );
	    $editorItemIcon.attr( 'class', $editorItemIcon.data( 'class' ) ); 
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

	    this.$elmt.trigger( 'ditty_item_types_panel_' + fn, params );
	
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
	      this.settings = $.extend( {}, defaults, $.ditty_item_types_panel.defaults, key );
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
	    this.$elmt.off( 'click', '.ditty-editor-item-type', { self: this }, this._typeClick );
	    
	    this.trigger( 'destroy' );
	    this.elmt._ditty_item_types_panel = null;	    
    }
  };

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_item_types_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_item_types_panel ) {
        	this._ditty_item_types_panel = new Ditty_Item_Types_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_item_types_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Item_Types_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Item_Types_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_item_types_panel = {};
  $.ditty_item_types_panel.defaults = defaults;

} )( jQuery );
