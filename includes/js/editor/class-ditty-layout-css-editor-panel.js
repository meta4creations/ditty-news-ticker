/* global dittyLayoutCss:true */

/**
 * Ditty Layout CSS Editor Panel
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  editor 			: null,
	  prevPanel 	: ''
  };

  var Ditty_Layout_Css_Editor_Panel = function ( elmt, options ) {
	  
    this.elmt         								= elmt;
    this.settings     								= $.extend( {}, defaults, $.ditty_layout_css_editor_panel.defaults, options );
    this.$elmt        								= $( elmt );
    this.$form												= this.$elmt.find( '.ditty-editor-options' );
    this.$textarea										= this.$elmt.find( '.ditty-editor-options__code' );
    this.$back												= this.$elmt.find( '.ditty-editor-options__back' );
    this.$preview											= this.$elmt.find( '.ditty-editor-options__preview' );
    this.$editHtml										= this.$elmt.find( '.ditty-editor-options__edit-html' );
    this.$title												= this.$elmt.find( '.ditty-editor-options__title' );
		this.$body												= this.$elmt.find( '.ditty-editor-options__body' );
    this.$tags												= this.$elmt.find( '.ditty-editor-options__tags' );
    this.itemType											= this.$form.data( 'item_type' );
    this.layoutId											= this.$form.data( 'layout_id' );
    this.$editorItem 									= dittyVars.editor.currentItem;
    this.$editorLayout 								= dittyVars.editor.currentLayout;
    this.$editorLayoutTitle						= this.$editorLayout ? this.$editorLayout.find( '.ditty-data-list__item__label' ) : false;
		this.$editorLayoutVariation 			= dittyVars.editor.currentLayoutVariation;
		this.$editorLayoutVariationTitle	= this.$editorLayoutVariation.find( '.ditty-layout-variation__template span' );
    this.editorDittyId								= this.$editorItem.data( 'ditty_id' );
    this.editorItemId									= this.$editorItem.data( 'item_id' );
		this.editorItemType								= this.$editorItem.data( 'item_type' );
		this.editorItemValue							= this.$editorItem.data( 'item_value' );
    this.itemSelector									= '';
    this.codeEditor										= null;
    this.codeHasUpdates 							= false;
    this.initData											= null;
    this.afterUpdateAction						= '';	
        
    this._init();
  };


  Ditty_Layout_Css_Editor_Panel.prototype = {

    /**
		 * Initialize the data list
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {

      // Save the initial data
	    this.initData = this.$form.serialize();
	    
	    // Set the css item selector
	    this._setCssSelector();

      // Initialize dynamic fields
      this._initEditor();

      // Add actions
	    this.$back.on( 'click', { self: this }, this._backClick );
	    this.$preview.on( 'click', { self: this }, this._previewClick );
	    this.$editHtml.on( 'click', { self: this }, this._editHtml );
	    this.$form.on( 'submit', { self: this }, this._submitForm );
	    this.$form.on( 'click', '.ditty-editor-options__tag', { self: this }, this._insertTag );
	    this.$title.on( 'keyup', { self: this }, this._titleUpdate );
			this.$body.on( 'click', '.ditty-editor-options__body__error', { self: this }, this._removeErrorNotice );
			this.settings.editor.$elmt.on( 'ditty_editor_add_drafts', { self: this }, this._addDrafts );
	    this.settings.editor.$elmt.on( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
    },
    
    /**
     * Set the css item selector
		 * @since    3.0
		 * @return   null
		*/
	  _setCssSelector: function() {
			if ( dittyVars.isTickerPost ) {
				this.itemSelector = '#poststuff .ditty-layout--' + this.layoutId;
			} else {
				this.itemSelector = '.ditty-layout--' + this.layoutId;
			}
    },
    
    /**
     * Return to the previous panel
		 * @since    3.0
		 * @return   null
		*/
	  _showPrevPanel: function() {
			this.settings.editor.$panels.ditty_slider( 'options', 'transition', 'slideRight' );
			this.settings.editor.$panels.ditty_slider( 'showSlideById', this.settings.prevPanel );
    },
    
    /**
     * Editor updated listener
		 * @since    3.0
		 * @return   null
		*/
	  _titleUpdate: function( e ) {
		  var self = e.data.self;	
			self.settings.editor.addUpdate( 'layoutUpdate', self.layoutId );
    },
    
    /**
     * Preview button add updates class
		 * @since    3.0
		 * @return   null
		*/
	  _enablePreviewButton: function() {
		  this.$preview.addClass( 'ditty-has-updates' );
    },
    
    /**
     * Preview button remove updates class
		 * @since    3.0
		 * @return   null
		*/
	  _disablePreviewButton: function() {
		  this.$preview.removeClass( 'ditty-has-updates' );
		  this.$preview.children( 'i' ).attr( 'class', this.$preview.children( 'i' ).data( 'class' ) );
    },
    
    /**
     * Editor tabs listener
		 * @since    3.0
		 * @return   null
		*/
	  _addDrafts: function( e ) {
		  var self = e.data.self;
		  self.codeEditor.codemirror.save();
		  
		  var currentData = self.$form.serialize();
		  if ( currentData !== self.initData || self.codeHasUpdates ) {
			  self.$form.trigger( 'submit' );
		  }	
    },
		
		/**
		 * Editor updated listener
		 * @since    3.0
		 * @return   null
		*/
		_saveDrafts: function( e ) {
			var self = e.data.self;
			self.codeEditor.codemirror.save();
			
			var currentData = self.$form.serialize();
			if ( currentData !== self.initData || self.codeHasUpdates ) {
				self.afterUpdateAction = 'save';
				self.$form.trigger( 'submit' );
			}	
		},
        
    /**
     * Back click
		 * @since    3.0
		 * @return   null
		*/
	  _backClick: function( e ) {
		  e.preventDefault();
		  var self = e.data.self;

		  self.codeEditor.codemirror.save();  
		  var currentData = self.$form.serialize();
		  		
		  if ( currentData !== self.initData || self.codeHasUpdates ) {
			  self.afterUpdateAction = 'return';
			  self.$form.trigger( 'submit' );
		  } else {
			  self._showPrevPanel();
		  }	
    },
    
    /**
     * Preview any changes
		 * @since    3.0
		 * @return   null
		*/
	  _previewClick: function( e ) {
		  e.preventDefault();
		  var self 	= e.data.self;

		  self.codeEditor.codemirror.save(); // Update the textarea	  
		  var currentData = self.$form.serialize();
	
		  if ( currentData !== self.initData || self.codeHasUpdates ) {  
			  self.$preview.children( 'i' ).attr( 'class', dittyVars.updateIcon );
			  self.$form.trigger( 'submit' ); 
		  }
    },
    
    /**
		 * Edit the html of the layout
		 * @since    3.0
		 * @return   null
		*/
	  _editHtml: function( e ) { 
		  e.preventDefault();
		  var self 				= e.data.self,
					panelId		= 'layout_html_editor';
			
			self.settings.editor.updateStart(); // Start the update overlay		
			self.codeEditor.codemirror.save(); // Update the textarea	  
		  var currentData = self.$form.serialize();
		  if ( currentData !== self.initData || self.codeHasUpdates ) {  
			  self.$preview.children( 'i' ).attr( 'class', dittyVars.updateIcon );
			  self.$form.trigger( 'submit' ); 
		  }
			
			// Show the panel if it exists
		  if ( self.settings.editor.panelExists( panelId ) ) {
				self.settings.editor.$elmt.find( '.ditty-editor__panel--layout_html_editor input.ditty-editor-options__title' ).val( self.$title.val() );
			  self.settings.editor.panelOptions( 'transition', 'fade' );
				self.settings.editor.showPanel( panelId );
			  
			// Else, load the new panel and show
		  } else {	
				var data = {
					action				: 'ditty_editor_layout_fields',
					layout_id			: self.layoutId,
					layout_title  : self.$title.val(),
					ditty_id			: self.editorDittyId,
					item_id				: self.editorItemId,
					item_type			: self.editorItemType,
					item_value		: self.editorItemValue,
					edit_type			: 'html',
					draft_values 	: self.settings.editor.getDraftValues(),
					security			: dittyVars.security
				};
				$.post( dittyVars.ajaxurl, data, function( response ) {
					if ( response ) {
						var html = '<div class="ditty-editor__panel ditty-editor__panel--' + panelId + '">' + response.form + '</div>';
						self.settings.editor.panelOptions( 'transition', 'fade' );
						self.settings.editor.showPanel( panelId, html );
					}
				}, 'json' );
			}
		}, 
		
		/**
		 * Add an error notice
		 * @since    3.0
		 * @return   null
		*/
		_showErrorNotice: function() {
			var $error = $( '<div class="ditty-editor-options__body__error"><span>' + dittyVars.strings.layout_css_error + '</span></div>' );
			this.$body.append( $error );
			
		},
		
		/**
		 * Remove the error notice
		 * @since    3.0
		 * @return   null
		*/
		_removeErrorNotice: function( e ) {
			var self = e.data.self;
			self.$body.find( '.ditty-editor-options__body__error' ).remove();
		}, 
    
    /**
		 * Submit updates
		 * @since    3.0
		 * @return   null
		*/
	  _submitForm: function( e ) {
		  e.preventDefault();
		  
		  var self 			= e.data.self,
		  		layoutId 	= self.layoutId,
		  		itemType 	= self.itemType;
		  		
		  if ( self.$form.hasClass( 'ditty-editor-has-errors' ) ) {
			  return false;
		  }

		  self.codeEditor.codemirror.save(); // Update the textarea
		  self.settings.editor.updateStart(); // Start the update overlay
		  
			var data = {
				action				: 'ditty_editor_layout_update',
				layout_id 		: layoutId,
				item_type 		: itemType,
				edit_type			: 'css',
				draft_values 	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
		  self.$form.ajaxSubmit( {
		    url			: dittyVars.ajaxurl,
				type		: 'post',
				dataType: 'json',
				data		: data,
        success: function( response ) {
	        self.initData = self.$form.serialize();
	        self._disablePreviewButton();
	        if ( response.label ) {
						if ( self.$editorLayoutTitle ) {
							self.$editorLayoutTitle.html( response.label );
						}
						self.$editorLayoutVariationTitle.html( response.label );
					}	
					// Remove the template version numbers
					if ( self.$editorLayoutTitle ) {
						self.$editorLayoutTitle.find( '.ditty-layout-version' ).remove();
					}
					if ( self.$editorLayoutVariationTitle ) {
						self.$editorLayoutVariationTitle.find( '.ditty-layout-version' ).remove();
					}
					
					if ( response.code ) {
						dittyLayoutCss( response.code, self.layoutId, 'update' );
					} else {
						self._showErrorNotice();
					}
					if ( response.draft_id && response.draft_meta ) {
						dittyDraftLayoutUpdate( self, response.draft_id, null, response.draft_meta );
					}
	
					self.codeHasUpdates = false;
					self.settings.editor.updateStop(); // Stop the update overlay
					self.settings.editor.delayedSubmitDisable(); // Remove the delayed submit since we just submitted
					
					if ( 'return' === self.afterUpdateAction ) {
						self._showPrevPanel(); // Show the items list
					}
					if ( 'save' === self.afterUpdateAction ) {
						self.settings.editor.saveDitty(); // Update the ticker
					}
					self.afterUpdateAction = '';
        }
	    } ); 
    },
    
    /**
     * Initialize the editor
		 * @since    3.0
		 * @return   null
		*/
	  _initEditor: function() {
		  var self = this,
		  		codeEditorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {},
					mode = dittyVars.editor.ditty_layouts_sass ? 'sass' : 'css';	
			
	    codeEditorSettings.codemirror = _.extend(
	      {},
	      codeEditorSettings.codemirror,
	      {
		      mode				: mode,
	        indentUnit	: 2,
	        tabSize			: 2
	      }
	    );
	    this.codeEditor = wp.codeEditor.initialize( this.$textarea[0], codeEditorSettings );
	    this.codeEditor.codemirror.on( 'change', function() {
		    self.codeEditor.codemirror.save();
		    self.settings.editor.addUpdate( 'layoutCssUpdate', self.layoutId );
				self.codeHasUpdates = true;
				self._enablePreviewButton();
				self.settings.editor.delayedSubmitEnable(); // Enable the delayed submit since we have changes
			} );
    },

    /**
     * Insert a tag
		 * @since    3.0
		 * @return   null
		*/
	  _insertTag: function( e ) {
		  var self 		= e.data.self,
		  		$tag		= $( e.target ),
		  		text		= $tag.text(),
		  		cursor 	= self.codeEditor.codemirror.getCursor();
					
			self.codeEditor.codemirror.replaceRange( text, cursor );
			
			// Move the cursor position
			cursor.ch = cursor.ch + text.length;
			self.codeEditor.codemirror.setCursor( cursor );
			//self.codeEditor.codemirror.trigger( 'focus' );
    },

	  /**
		 * Return a specific setting
		 * @since    3.0
		 * @return   null
		*/
    _options: function ( key ) {
	    return this.settings[key];
    },
    
		/**
		 * Setup triggers
		 * @since  	3.0
		 * @return 	null
		*/
    trigger: function ( fn, customParams ) {
	    var params = [this.settings]; 
	    if ( customParams ) {
		    params = customParams;
	    }

	    this.$elmt.trigger( 'ditty_layout_css_editor_panel_' + fn, params );
	
	    if ( typeof this.settings[fn] === 'function' ) {
	      this.settings[fn].apply( this.$elmt, params );
	    }
    },
		
		/**
		 * Allow settings to be modified
		 * @since  	3.0
		 * @return 	null
		*/
    options: function ( key, value ) {
	    if ( typeof key === 'object' ) {
	      this.settings = $.extend( {}, defaults, $.ditty_layout_css_editor_panel.defaults, key );
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
		 * Destroy the class
		 * @since  	3.0
		 * @return 	null
		*/
    destroy: function () {
	    
	    // Remove actions
	    this.$back.off( 'click', { self: this }, this._backClick );
	    this.$preview.off( 'click', { self: this }, this._previewClick );
	    this.$editHtml.off( 'click', { self: this }, this._editHtml );
	    this.$form.off( 'submit', { self: this }, this._submitForm );
	    this.$form.off( 'click', '.ditty-editor-options__tag', { self: this }, this._insertTag );
	    this.$title.off( 'keyup', { self: this }, this._titleUpdate );
			this.$body.off( 'click', '.ditty-editor-options__body__error', { self: this }, this._removeErrorNotice );
			this.settings.editor.$elmt.off( 'ditty_editor_add_drafts', { self: this }, this._addDrafts );
	    this.settings.editor.$elmt.off( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
			this.codeEditor.codemirror.off( 'change' );
	    this.codeEditor.codemirror.toTextArea();
	    
	    this.elmt._ditty_layout_css_editor_panel = null;	    
    }
  };

	/**
	 * Create the data list
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_layout_css_editor_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_layout_css_editor_panel ) {
        	this._ditty_layout_css_editor_panel = new Ditty_Layout_Css_Editor_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_layout_css_editor_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Layout_Css_Editor_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Layout_Css_Editor_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_layout_css_editor_panel = {};
  $.ditty_layout_css_editor_panel.defaults = defaults;

} )( jQuery );
