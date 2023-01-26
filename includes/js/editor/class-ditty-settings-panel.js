/**
 * Ditty Editor Settings Panel
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  editor: null
  };

  var Ditty_Settings_Panel = function ( elmt, options ) {
    this.elmt         					= elmt;
    this.settings     					= $.extend( {}, defaults, $.ditty_settings_panel.defaults, options );
    this.$elmt        					= $( elmt );
		this.$form									= this.$elmt.find( '.ditty-editor-options' );
		this.$title									= this.$elmt.find( 'input[name="title]' );
		this.$previewBg							= this.$elmt.find( 'input[name="previewBg"]' );
		this.$previewPaddingTop			= this.$elmt.find( 'input[name="previewPadding[paddingTop]"]' );
		this.$previewPaddingBottom	= this.$elmt.find( 'input[name="previewPadding[paddingBottom]"]' );
		this.$previewPaddingLeft		= this.$elmt.find( 'input[name="previewPadding[paddingLeft]"]' );
		this.$previewPaddingRight		= this.$elmt.find( 'input[name="previewPadding[paddingRight]"]' );
		this.$postTitle							= $( '.ditty-post__title' );
		this.$editorPreview					= $( '#ditty-editor__preview' );
		this.dittyId								= this.$form.data( 'ditty_id' );
		this.initData								= null;
		this.afterUpdateAction			= '';	

    this._init();
  };


  Ditty_Settings_Panel.prototype = {

    /**
		 * Initialize the panel
		 *
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {
			
			// Save the initial data
			this.initData = this.$form.serialize();
			
			// Initialize dynamic fields
			this.settings.editor.initFields( this.$elmt );
			
			// Add actions
			this.settings.editor.$elmt.on( 'ditty_editor_add_drafts', { self: this }, this._addDrafts );
			this.settings.editor.$elmt.on( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
			this.$form.on( 'submit', { self: this }, this._submitForm );
			this.$form.on( 'keyup change', 'input[type="text"], input[type="number"], textarea, select', { self: this }, this._checkUpdates );
			this.$form.on( 'click', 'input[type="radio"], input[type="checkbox"]', { self: this }, this._checkUpdates );
			this.$form.on( 'ditty_input_wysiwyg_update', '.ditty-input--wysiwyg', { self: this }, this._checkUpdates );
			this.$form.on( 'keyup change', 'input[name="title"]', { self: this }, this._titleChange );
			this.$form.on( 'keyup change', '.ditty-field--preview_settings *', { self: this }, this._previewBgChange );
			
	    this.$elmt.addClass( 'init' );
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
				self.settings.editor.addUpdate( 'settings', self.itemId );
				self.settings.editor.delayedSubmitEnable(); // Enable the delayed submit since we have changes
			}	else {
				self.settings.editor.removeUpdate( 'settings', self.itemId );
			}
		},
		
		/**
		 * Listen for title changes
		 *
		 * @since    3.0
		 * @return   null
		*/
		_titleChange: function( e ) {
			var self = e ? e.data.self : this;
			var title = $( e.target ).val();
			self.$postTitle.text( title );
		},
		
		/**
		 * Listen for the preview background change
		 *
		 * @since    3.0
		 * @return   null
		*/
		_previewBgChange: function( e ) {
			var self = e ? e.data.self : this;
			var preview_css = {
				backgroundColor	: self.$previewBg.val(),
				paddingTop			: self.$previewPaddingTop.val(),
				paddingBottom		: self.$previewPaddingBottom.val(),
				paddingLeft			: self.$previewPaddingLeft.val(),
				paddingRight		: self.$previewPaddingRight.val()
			};
			self.$editorPreview.css( preview_css );
		},
		
		/**
		 * Editor updated listener
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
		 * Submit updates
		 *
		 * @since    3.0
		 * @return   null
		*/
		_submitForm: function( e ) {
			e.preventDefault(); 
			var self 		= e.data.self,
					dittyId = self.dittyId;
			
			self.settings.editor.updateStart(); // Start the update overlay
			
			var data = {
				action				: 'ditty_editor_settings_update',
				ditty_id 			: dittyId,
				draft_values 	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			self.$form.ajaxSubmit( {
				url				: dittyVars.ajaxurl,
				type			: 'post',
				dataType	: 'json',
				data			: data,
				success		: function( response ) { 
					self.initData = self.$form.serialize();
					dittyDraftUpdate( self, 'settings', false, response );	
					self.settings.editor.delayedSubmitDisable(); // Remove the delayed submit since we just submitted
					if ( 'save' === self.afterUpdateAction ) { // Update the ticker
						self.settings.editor.saveDitty( { return_items: 0 } );
					}
					self.afterUpdateAction = '';
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

	    this.$elmt.trigger( 'ditty_settings_panel_' + fn, params );
	
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
	      this.settings = $.extend( {}, defaults, $.ditty_settings_panel.defaults, key );
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
			this.settings.editor.$elmt.off( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
			this.settings.editor.$elmt.off( 'ditty_editor_add_drafts', { self: this }, this._addDrafts );
			this.$form.off( 'submit', { self: this }, this._submitForm );
			this.$form.off( 'keyup change', 'input[type="text"], input[type="number"], textarea, select', { self: this }, this._checkUpdates );
			this.$form.off( 'keyup change', 'input[name="previewBg"]', { self: this }, this._previewBg_cahnge );
			this.$form.off( 'keyup change', 'input[name="title"]', { self: this }, this._titleChange );
			this.$form.off( 'click', 'input[type="radio"], input[type="checkbox"]', { self: this }, this._checkUpdates );
			this.$form.off( 'ditty_input_wysiwyg_update', '.ditty-input--wysiwyg', { self: this }, this._checkUpdates );
	    
	    this.trigger( 'destroy' );
	    this.elmt._ditty_settings_panel = null;
    }
  };

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_settings_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_settings_panel ) {
        	this._ditty_settings_panel = new Ditty_Settings_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_settings_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Settings_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Settings_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_settings_panel = {};
  $.ditty_settings_panel.defaults = defaults;

} )( jQuery );
