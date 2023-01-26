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

  var Ditty_Item_Editor_Panel = function ( elmt, options ) {
	  
    this.elmt         			= elmt;
    this.settings     			= $.extend( {}, defaults, $.ditty_item_editor_panel.defaults, options );
    this.$elmt        			= $( elmt );
    this.$form							= this.$elmt.find( '.ditty-editor-options' );
		this.$back							= this.$elmt.find( '.ditty-editor-options__back' );
		this.$preview						= this.$elmt.find( '.ditty-editor-options__preview' );
    this.$title							= this.$elmt.find( '.ditty-editor-options__title' );
    this.itemId							= this.$form.data( 'item_id' );
    this.itemType						= this.$form.data( 'item_type' );
    this.dittyId						= this.$form.data( 'ditty_id' );
    this.$editorItem 				= this.settings.editor.$panels.find( '.ditty-editor__panel--items' ).find( '.ditty-data-list__item.editing' );
    this.$editorItemTitle		= this.$editorItem.find( '.ditty-data-list__item__label' );
    this.initData						= null;
    this.afterUpdateAction	= '';	

    this._init();
  };


  Ditty_Item_Editor_Panel.prototype = {

    /**
		 * Initialize the data list
		 *
		 * @since		3.0.12
		 * @return	null
		*/
    _init: function () {
			
			var self = this;

      // Save the initial data
	    this.initData = this.$form.serialize();

      // Initialize dynamic fields
      this.settings.editor.initFields( this.$elmt );
      
      // Add actions
      this.settings.editor.$elmt.on( 'ditty_editor_add_drafts', { self: this }, this._addDrafts );
			this.settings.editor.$elmt.on( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
      this.$form.on( 'submit', { self: this }, this._submitForm );
	    this.$back.on( 'click', { self: this }, this._backClick );
	    this.$preview.on( 'click', { self: this }, this._previewClick );
			this.$form.on( 'keyup change', 'input[type="text"], input[type="number"], textarea, select', { self: this }, this._checkUpdates );
			this.$form.on( 'click', 'input[type="radio"], input[type="checkbox"]', { self: this }, this._checkUpdates );
			this.$form.on( 'ditty_input_wysiwyg_update', '.ditty-input--wysiwyg', { self: this }, this._checkUpdates );
			this.$form.on( 'ditty_field_update', '.ditty-field__input', { self: this }, this._checkUpdates );
			
			// Trigger the init
			setTimeout( function() {
				self.trigger( 'init', [self] ); 
			}, 1 );
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
     * Preview button add updates class
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _enablePreviewButton: function() {
		  this.$preview.addClass( 'ditty-has-updates' );
    },
    
    /**
     * Preview button remove updates class
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _disablePreviewButton: function() {
		  this.$preview.removeClass( 'ditty-has-updates' );
		  this.$preview.children( 'i' ).attr( 'class', this.$preview.children( 'i' ).data( 'class' ) );
    },
    
    /**
		 * Check for updates
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _checkUpdates: function( e ) {
			var self = e ? e.data.self : this;
		  var currentData = self.$form.serialize();
		  if ( currentData !== self.initData ) {
			  self._enablePreviewButton();
			  self.settings.editor.addUpdate( 'item_settings', self.itemId );
				self.settings.editor.delayedSubmitEnable(); // Enable the delayed submit since we have changes
		  }	else {
			  self._disablePreviewButton();
			  self.settings.editor.removeUpdate( 'item_settings', self.itemId );
		  }
		},
    
    /**
     * Editor tabs listener
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _addDrafts: function( e ) {
		  var self 				= e.data.self,
		  		currentData = self.$form.serialize();

		  if ( currentData !== self.initData ) {
			  self.$form.trigger( 'submit' );
		  }	
    },
		
		/**
		 * Editor updated listener
		 *
		 * @since    3.0
		 * @return   null
		*/
		_saveDrafts: function( e ) {
			var self = e.data.self,
					currentData = self.$form.serialize();

			if ( currentData !== self.initData ) {
				self.afterUpdateAction = 'save';
				self.$form.trigger( 'submit' );
			}	
		},

    /**
     * Back click
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _backClick: function( e ) {
		  e.preventDefault();
		  var self = e.data.self,
		  		currentData = self.$form.serialize();
		  		
		  if ( currentData === self.initData ) {
			  self._showItemList();
		  } else {
			  self.afterUpdateAction = 'return';
			  self.$form.trigger( 'submit' );
		  }	
    },
    
    /**
     * Preview any changes
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _previewClick: function( e ) {
		  e.preventDefault();
		  var self = e.data.self,
		  		currentData = self.$form.serialize();
	
		  if ( currentData !== self.initData ) {  
			  self.$preview.children( 'i' ).attr( 'class', dittyVars.updateIcon );
			  self.$form.trigger( 'submit' ); 
		  }
    },

    /**
		 * Submit updates
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _submitForm: function( e ) {
		  e.preventDefault(); 
		  var self 				= e.data.self,
		  		itemId 		= self.itemId;
			
			self.settings.editor.updateStart(); // Start the update overlay
			
		  var data = {
        action				: 'ditty_editor_item_update',
        item_id 			: itemId,
				draft_values 	: self.settings.editor.getDraftValues(),
        security			: dittyVars.security
	    };
		  self.$form.ajaxSubmit( {
		    url				: dittyVars.ajaxurl,
				type			: 'post',
				dataType	: 'json',
				data			: data,
				error			: function() {
				},
        success		: function( response ) { 
					if ( response.value_updates ) {
						self._updateValues( response.value_updates );
					}
	        self.initData = self.$form.serialize();
	        self._disablePreviewButton();

					if ( response.display_items ) {
						self.settings.editor.ditty.updateItems( response.display_items, itemId );
					}
					if ( response.editor_item ) {
						var $item = $( response.editor_item ),
								itemTitle = $item.children( '.ditty-data-list__item__label' ).html();

						// Set the new titles
						self.$title.html( itemTitle );
						self.$editorItemTitle.html( itemTitle );
					}
					if ( response.draft_id && response.draft_data ) {
						dittyDraftItemUpdateData( self, response.draft_id, null, response.draft_data );
					}
					if ( response.draft_id && response.draft_meta ) {
						dittyDraftItemUpdateMeta( self, response.draft_id, null, response.draft_meta );
					}

					self.settings.editor.updateStop(); // Stop the update overlay
					self.settings.editor.delayedSubmitDisable(); // Remove the delayed submit since we just submitted

					// Show the items list
					if ( 'return' === self.afterUpdateAction ) {
						self._showItemList();
					}
					// Update the Ditty
					if ( 'save' === self.afterUpdateAction ) {
						self.settings.editor.saveDitty( { return_items: 0 } );
					}
					self.afterUpdateAction = '';
        }
	    } ); 
    },
		
		/**
		 * Update field values
		 *
		 * @since    3.0
		 * @return   null
		*/
		_updateValues: function ( data ) {
			var self = this;
			$.each( data, function( key, value ) {
				var $element = self.$form.find( '[name="' + key + '"]' );
				if ( $element.length ) {
					$element.val( value );
				}
			} );
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
	    this.$elmt.trigger( 'ditty_item_editor_panel_' + fn, params );
	
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
	      this.settings = $.extend( {}, defaults, $.ditty_item_editor_panel.defaults, key );
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
	    
			// Remove listeners
	    this.settings.editor.$elmt.off( 'ditty_editor_add_drafts', { self: this }, this._saveDrafts );
			this.settings.editor.$elmt.off( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
      this.$form.off( 'submit', { self: this }, this._submitForm );
	    this.$back.off( 'click', { self: this }, this._backClick );	 
	    this.$preview.off( 'click', { self: this }, this._previewClick ); 
			this.$form.off( 'keyup change', 'input[type="text"], input[type="number"], textarea, select', { self: this }, this._checkUpdates );
			this.$form.off( 'click', 'input[type="radio"], input[type="checkbox"]', { self: this }, this._checkUpdates );
			this.$form.off( 'ditty_input_wysiwyg_update', '.ditty-input--wysiwyg', { self: this }, this._checkUpdates );
			this.$form.off( 'ditty_field_update', '.ditty-field__input', { self: this }, this._checkUpdates );
			
	    this.elmt._ditty_item_editor_panel = null;	    
    }
  };

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_item_editor_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_item_editor_panel ) {
        	this._ditty_item_editor_panel = new Ditty_Item_Editor_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_item_editor_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Item_Editor_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Item_Editor_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_item_editor_panel = {};
  $.ditty_item_editor_panel.defaults = defaults;

} )( jQuery );
