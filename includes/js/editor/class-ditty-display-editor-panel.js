/**
 * Ditty Display Editor Panel
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  editor: null
  };

  var Ditty_Display_Editor_Panel = function ( elmt, options ) {
	  
    this.elmt         				= elmt;
    this.settings     				= $.extend( {}, defaults, $.ditty_display_editor_panel.defaults, options );
    this.$elmt        				= $( elmt );
    this.$form								= this.$elmt.find( '.ditty-editor-options' );
    this.$back								= this.$elmt.find( '.ditty-editor-options__back' );
    this.$optionsTitle				= this.$elmt.find( '.ditty-editor-options__title' );
    this.$importExportField		= this.$elmt.find( '.ditty-editor__import-export__field' );
    this.$importExportUpdate	= this.$elmt.find( '.ditty-editor__import-export__update' );
    this.displayTitle					= null;
    this.displayId						= this.settings.editor.ditty.options( 'display' );
    this.displayType					= this.settings.editor.ditty.options( 'type' );
    this.$editorDisplay 			= this.settings.editor.$panels.find( '.ditty-editor__panel--displays' ).find( '.ditty-data-list__item.editing' );
    this.$editorDisplayTitle	= this.$editorDisplay.find( '.ditty-data-list__item__label' );
    this.displayOptions				= null;
    this.initData							= null;
    this.afterUpdateAction		= '';	

    this._init();
  };


  Ditty_Display_Editor_Panel.prototype = {

    /**
		 * Initialize the data list
		 *
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {
	    
      var self = this,
      		displayOptions;
      		
      // Save the initial data
	    this.initData = this.$form.serialize();
	    
	    // Initialize dynamic fields
      this.settings.editor.initFields( this.$elmt );

      // Store the current ditty options
	    displayOptions = this.settings.editor.ditty.$elmt['ditty_' + this.displayType]( 'options' );
	    this.displayOptions = $.extend( {}, displayOptions );

      this.displayTitle = this.$optionsTitle.val();
      
      // Add actions
      this.$importExportUpdate.on( 'click', { self: this }, this._importUpdate );   
			this.settings.editor.$elmt.on( 'ditty_editor_add_drafts', { self: this }, this._addDrafts );
      this.settings.editor.$elmt.on( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
	    this.$form.on( 'submit', { self: this }, this._submitForm );
	    this.$back.on( 'click', { self: this }, this._backClick );
			this.$elmt.on( 'change', 'input[type="text"], input[type="number"]', { self: this }, this._textfieldListeners );
			this.$form.on( 'click', 'input[type="radio"]', { self: this }, this._radioListeners );
			this.$form.on( 'click', 'input[type="checkbox"]', { self: this }, this._checkboxListeners );
			this.$form.on( 'change', 'select', { self: this }, this._selectListeners );
			this.$form.on( 'ditty_field_clone_update', { self: this }, this._cloneListeners );

			// Trigger the init
      setTimeout( function() {
        self.trigger( 'init', [self] ); 
      }, 1 );
    },
    
    /**
     * Return to the displays list
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _showDisplayList: function() {
			this.settings.editor.$panels.ditty_slider( 'options', 'transition', 'slideRight' );
			this.settings.editor.$panels.ditty_slider( 'showSlideById', 'displays' );
    },
    
    /**
		 * Check for updates
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _checkUpdates: function() { 
		  var currentData = this.$form.serialize();
		  if ( currentData !== this.initData ) {
			  this.settings.editor.addUpdate( 'displaySettings', this.displayId );
				this.settings.editor.delayedSubmitEnable(); // Enable the delayed submit since we have changes
		  }	else {
			  this.settings.editor.removeUpdate( 'displaySettings', this.displayId );
		  }
		},
		
		/**
     * Editor tabs listener
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _addDrafts: function( e ) {
		  var self = e.data.self,
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
			  self._showDisplayList();
		  } else {
			  self.afterUpdateAction = 'return';
			  self.$form.trigger( 'submit' );
		  }	
    },

	  /**
		 * Cancel click
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _submitForm: function( e ) {
		  e.preventDefault();
		  var self = e.data.self;
			
		  self.settings.editor.updateStart(); // Start the update overlay
			
			var data = {
				action				: 'ditty_editor_display_update',
				display_id		: self.displayId,
				draft_values	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
		  self.$form.ajaxSubmit( {
		    url				: dittyVars.ajaxurl,
				type			: 'post',
				dataType	: 'json',
				data			: data,
        success: function( response ) {
	        self.initData = self.$form.serialize();

					if ( response.draft_id && response.draft_label ) {
						self.displayTitle = response.draft_label;
						self.$editorDisplayTitle.text( response.draft_label );
						dittyDraftDisplayUpdate( self, response.draft_id, 'label', response.draft_label );
					}
					if ( response.draft_id && response.draft_settings ) {
						self.displayOptions = response.draft_settings;
						dittyDraftDisplayUpdate( self, response.draft_id, 'settings', response.draft_settings );
					}
	        if ( response.draft_settings_json && self.$importExportField.length ) {
		        self.$importExportField.val( response.draft_settings_json );
	        }
					
					self.settings.editor.updateStop(); // Stop the update overlay
					self.settings.editor.delayedSubmitDisable(); // Remove the delayed submit since we just submitted
					
					// Show the display list
					if ( 'return' === self.afterUpdateAction ) {
						self._showDisplayList();
					}
					// Update the ticker
					if ( 'save' === self.afterUpdateAction ) {
						self.settings.editor.saveDitty();
					}
					self.afterUpdateAction = '';
        }
	    } ); 
    },
    
    /**
		 * Update the imported values
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _importUpdate: function( e ) {
			e.preventDefault();
			
		  // var self 		= e.data.self,
		  // 		values 	= self.$importExportField.val();
    },
		
		/**
		 * Listen for clone changes
		 *
		 * @since    3.0
		 * @return   null
		*/
		_cloneListeners: function( e, fieldData, cloneName ) {
			var self = e.data.self,
					$target = $( e.target );

			self.settings.editor.ditty.options( cloneName, fieldData );
		},
    
    /**
		 * Listen for textfield changes
		 *
		 * @since    3.0
		 * @return   null
		*/
    _textfieldListeners: function( e ) {
	    var self = e.data.self,
					$target = $( e.target ),
					name = $target.attr( 'name' ),
					$fieldset = $target.parents( '.ditty-input--spacing__group, .ditty-input--radius__group' );	
					
	  	if ( $fieldset.length ) {
		  	var fieldsetName = '',
		  			fieldsetValue = {};
		  	$fieldset.find( 'input').each( function() {
			  	name = $( this ).attr( 'name' );
			  	fieldsetName = name.split( '[' );
			  	fieldsetName = fieldsetName[0];
			  	var matches = name.match(/\[(.*)\]/);
			  	fieldsetValue[matches[1]] = $( this ).val();
		  	} );
		  	self.settings.editor.ditty.options( fieldsetName, fieldsetValue );
	  	} else { 
				self.settings.editor.ditty.options( name, $target.val() );
	  	}
			self._checkUpdates();
	  },
	  
	  /**
		 * Listen for radio button changes
		 *
		 * @since    3.0
		 * @return   null
		*/
    _radioListeners: function( e ) { 
	  	var self = e.data.self,
					$target = $( e.target ),
					value = $target.val(),
					name = $target.attr( 'name' );

	  	self.settings.editor.ditty.options( name, value );
			self._checkUpdates();
	  },
	  
	  /**
		 * Listen for checkbox changes
		 *
		 * @since    3.0
		 * @return   null
		*/
    _checkboxListeners: function( e ) {
			var self = e.data.self,
					$target = $( e.target ),
					value = $target.is( ':checked' ) ? $( this ).val() : false,
					name = $target.attr( 'name' );
		  				
	  	self.settings.editor.ditty.options( name, value );
			self._checkUpdates();
	  },
	  
	  /**
		 * Listen for select changes
		 *
		 * @since    3.0
		 * @return   null
		*/
    _selectListeners: function( e ) {
			var self = e.data.self,
					$target = $( e.target ),
					value = $target.val(),
					name = $target.attr( 'name' ); 

	  	self.settings.editor.ditty.options( name, value );
			self._checkUpdates();
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

	    this.$elmt.trigger( 'ditty_display_editor_panel_' + fn, params );
	
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
	      this.settings = $.extend( {}, defaults, $.ditty_display_editor_panel.defaults, key );
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
			
			this.trigger( 'destroy', [this] ); 

	    // Remove actions
			this.$importExportUpdate.off( 'click', { this: this }, this._importUpdate );
			this.settings.editor.$elmt.off( 'ditty_editor_add_drafts', { self: this }, this._addDrafts );
	    this.settings.editor.$elmt.off( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
 		 	this.$form.off( 'submit', { this: this }, this._submitForm );
	    this.$back.off( 'click', { this: this }, this._cancel_click );  
	    this.$elmt.off( 'change', 'input[type="text"], input[type="number"]', { self: this }, this._textfieldListeners );
			this.$form.off( 'click', 'input[type="radio"]', { self: this }, this._radioListeners );
			this.$form.off( 'click', 'input[type="checkbox"]', { self: this }, this._checkboxListeners );
			this.$form.off( 'change', 'select', { self: this }, this._selectListeners );
			this.$form.off( 'ditty_field_clone_update', { self: this }, this._cloneListeners );
	    
	    this.elmt._ditty_display_editor_panel = null;	    
    }
  };

	
	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_display_editor_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_display_editor_panel ) {
        	this._ditty_display_editor_panel = new Ditty_Display_Editor_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_display_editor_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Display_Editor_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Display_Editor_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_display_editor_panel = {};
  $.ditty_display_editor_panel.defaults = defaults;

} )( jQuery );
