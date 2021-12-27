/**
 * Ditty Displays Panel
 *
 * @since		3.0
 * @return	null
*/
( function ( $ ) {
  'use strict';

  var defaults = {
	  editor: null
  };

  var Ditty_Displays_Panel = function ( elmt, options ) {
    this.elmt         				= elmt;
    this.settings     				= $.extend( {}, defaults, $.ditty_displays_panel.defaults, options );
    this.$elmt        				= $( elmt );
    this.$list								= $( elmt ).find( '.ditty-data-list__items' );
    this.$contents       			= $( elmt ).find( '.ditty-editor__panel__contents' );
    this.initDisplay					= null;

    this._init();
  };


  Ditty_Displays_Panel.prototype = {

    /**
		 * Initialize the panel
		 *
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {
	    this.$elmt.addClass( 'init' );

	    // Setup the display list
	    this.$elmt.ditty_ui_data_list( {
		    filter				: '.ditty-display-panel__filter',
		    item					: '.ditty-editor-display',
		    itemSelector	: 'display_type'
	    } );

	    // Activate the current layout
	    this.initDisplay = this.$list.data( 'active' );
	    this._activateDisplay( this.$list.find( '#ditty-editor-display--' + this.initDisplay ) );
	    
	    // Add listeners
			this.$elmt.on( 'click', '.ditty-data-list__item', { self: this }, this._selectDisplay );
			this.$elmt.on( 'click', '.ditty-data-list__item__edit', { self: this }, this._editDisplay );
			this.$elmt.on( 'click', '.ditty-data-list__item__clone', { self: this }, this._cloneDisplay );
			this.$elmt.on( 'click', '.ditty-data-list__item__delete', { self: this }, this._deleteDisplay );
	    this.settings.editor.$elmt.on( 'ditty_editor_saveDrafts', { self: this }, this._saveDrafts );
    },
    
    /**
     * Editor updated listener
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _saveDrafts: function( e ) {
		  var self = e.data.self;
		  self.initDisplay = self.$list.find( '.ditty-editor-display.active' ).data( 'display_id' );
    },
    
    /**
		 * Activate a display
		 *
		 * @since		3.0
		 * @return	null
		*/
    _activateDisplay: function ( $display ) {
	    this.$list.find( '.ditty-editor-display' ).removeClass( 'active' );
	    $display.addClass( 'active' );
    },

    /**
		 * Load a new display
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _initDitty: function( displayType, displayId, values ) { 
			
			var $elmnt = this.settings.editor.ditty.$elmt;

			values.display 	= displayId;
			values.id 			= this.settings.editor.ditty.options( 'id' );
			values.items 		= this.settings.editor.ditty.options( 'items' );
			values.height 	= this.settings.editor.ditty.options( 'height' );
			
			this.settings.editor.ditty.destroy();
			
			// Setup the new ticker and overwrite ditty
			$elmnt['ditty_' + displayType]( values );
			this.settings.editor.ditty = $elmnt['ditty_' + displayType]( 'options', 'ditty' );
		},
    
    /**
		 * Load a new display
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _selectDisplay: function( e ) { 
		  e.preventDefault();
		  var self = e.data.self;
		  if ( $( e.target ).parent().is( 'a' ) ) {
				return false;
			}
			
			var $display 			= $( e.target ).is( '.ditty-data-list__item' ) ? $( e.target ) : $( e.target ).parents( '.ditty-data-list__item' ),
					displayId 		= $display.data( 'display_id' ),
					displayType 	= $display.data( 'display_type' );
					
			if ( $display.hasClass( 'active' ) ) {
				return false;
			}
			
			self.settings.editor.updateStart(); // Start the update overlay
			dittyDraftUpdate( self, 'post_meta', '_ditty_display', displayId );
			self._activateDisplay( $display );

			// Load the new display data		
			var data = {
				action				: 'ditty_editor_select_display',
				display_id		: displayId,
				draft_values	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( ! response ) {
					return false;
				}
				self._initDitty( displayType, displayId, response ); // Initialize a new ditty
				self.settings.editor.updateStop(); // Stop the update overlay
			}, 'json' );		
    },

		/**
		 * Clone a display
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _cloneDisplay: function( e ) { 
		  e.preventDefault();
		  var self 			= e.data.self,
					$button 	= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$display 	= $button.parents( '.ditty-data-list__item' ),
					displayId	= $display.data( 'display_id' );

			self.settings.editor.updateStart(); // Start the update overlay
			
			// Load the new display fields		
			var data = {
				action				: 'ditty_editor_display_clone',
				display_id		: displayId,
				draft_values	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};

			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response.editor_display ) {
					var $clone = $( response.editor_display );
			    $clone.hide();
			    $display.after( $clone );
			    $clone.slideDown();
		    }
				if ( response.draft_id && response.draft_data ) {
					dittyDraftDisplayUpdate( self, response.draft_id, null, response.draft_data );
				}
				self.settings.editor.updateStop(); // Stop the update overlay    
			}, 'json' );
		},

		/**
		 * Delete a display
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _deleteDisplay: function( e ) { 
		  e.preventDefault();

		  var self 				= e.data.self,
		  		$button 		= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$item 			= $button.parents( '.ditty-data-list__item' ),
					$nextItem 	= null,
					displayId 	= $item.data( 'display_id' );

			// Find the display to load if this one is active
			if ( $item.hasClass( 'active' ) ) {
				if ( $item.prev().length ) {
					$nextItem = $item.prev();
				} else if ( $item.next().length ) {
					$nextItem = $item.next();
				}
			}
			
			$item.slideUp( function() {
				$( this ).remove();
			} );
			dittyDraftDisplayDelete( self, displayId );
			
			// Possibly select another display
			if ( null !== $nextItem ) {
				$nextItem.trigger( 'click' );
			}
		},
  
    /**
		 * Edit a display
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _editDisplay: function( e ) { 
		  e.preventDefault();
		  var self = e.data.self;
		  self.$displayEdit = $( this ).parents( '.ditty-data-list__item' );
		  
		  var $button 	= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					$display 	= $button.parents( '.ditty-data-list__item' ),
					displayId	= $display.data( 'display_id' );

			self.settings.editor.updateStart(); // Start the update overlay
			$display.addClass( 'editing' );

			// Load the new display fields		
			var data = {
				action				: 'ditty_editor_display_fields',
				display_id		: displayId,
				draft_values	: self.settings.editor.getDraftValues(),
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response ) {
					var html = '<div class="ditty-editor__panel ditty-editor__panel--displayEditor">' + response + '</div>';
					self.settings.editor.panelOptions( 'transition', 'slideLeft' );
					self.settings.editor.showPanel( 'display_editor', html );
				}
			} );
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

	    this.$elmt.trigger( 'ditty_displays_panel_' + fn, params );
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
	      this.settings = $.extend( {}, defaults, $.ditty_displays_panel.defaults, key );
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
		 * Destroy the panel
		 *
		 * @since  	3.0
		 * @return 	null
		*/
    destroy: function () {
	    
	    // Remove the init class
	    this.$elmt.removeClass( 'init' );
	    
	    // Destroy the display list
	    this.$elmt.ditty_ui_data_list( 'destroy' );
			
			// Remove listeners
			this.$elmt.off( 'click', '.ditty-data-list__item', { self: this }, this._selectDisplay );
			this.$elmt.off( 'click', '.ditty-data-list__item__edit', { self: this }, this._editDisplay );
			this.$elmt.off( 'click', '.ditty-data-list__item__clone', { self: this }, this._cloneDisplay );
			this.$elmt.off( 'click', '.ditty-data-list__item__delete', { self: this }, this._deleteDisplay );
			this.settings.editor.$elmt.off( 'ditty_editor_save_drafts', { self: this }, this._saveDrafts );
	    
	    this.trigger( 'destroy' );
	    this.elmt._ditty_displays_panel = null;
    }
  };

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_displays_panel = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_displays_panel ) {
        	this._ditty_displays_panel = new Ditty_Displays_Panel( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_displays_panel;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Displays_Panel applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Displays_Panel.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_displays_panel = {};
  $.ditty_displays_panel.defaults = defaults;

} )( jQuery );
