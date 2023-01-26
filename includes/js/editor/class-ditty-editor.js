/* global tinymce:true */

/**
 * Ditty Editor
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  screen	: 'admin',
	  ditty 	: null,
		panel		: ''
  };

  var Ditty_Editor = function( elmt, options ) {

    this.elmt         	= elmt;
    this.settings     	= $.extend( {}, defaults, $.ditty_editor.defaults, options );
    this.$elmt        	= $( elmt );
    this.ditty 					= this.settings.ditty;   
    this.dittyId				= this.settings.ditty.options( 'id' );
    this.dittyType 			= this.settings.ditty.options( 'type' );
    this.displayId			= 0;
    this.panelsLoaded		= 0;
    this.unsavedUpdates	= {};
		this.draftValues		= {};

    this.$overlay				= null;
    this.$contents			= null;
    this.$header				= null;
    this.$update				= null;
    this.$updateCount		= null;
		this.$updateLabel		= null;
    this.$tabs					= null;
    this.$panels				= null;
    this.currentTab			= 0;
    this.currentPanel		= this.settings.panel;
		this.delayedSubmit	= false;
    this.tabs						= [];
    this.panels					= [];
    this.activeItems		= [];

    this._init();
  };


  Ditty_Editor.prototype = {

    /**
		 * Initialize the editor
		 *
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {
      var self = this,
      		$contents,
      		$header,
      		$updateCount,
					$updateLabel,
      		$update,
      		$tabs,
      		$panels;
      
      // Create the editor contents
      $contents = $( '<div class="ditty-editor__contents"></div>' );
      this.$contents = $contents;
      
      // Create the editor header
      $header = $( '<div class="ditty-editor__header"></div>' );
      this.$header = $header;
      
      // Create the editor update button count
      $updateCount = $( '<span class="ditty-editor__update__count"></span>' );
      this.$updateCount = $updateCount;
			
			// Create the editor update button label
			$updateLabel = $( '<span class="ditty-editor__update__label">Save Ditty</span>' );
			this.$updateLabel = $updateLabel;
      
      // Create the editor update button
      $update = $( '<button type="submit" class="ditty-editor__update ditty-button ditty-button--primary"></button>' );
      this.$update = $update;
      
      // Create the editor tabs
      $tabs = $( '<div class="ditty-editor__tabs"></div>' );
      this.$tabs = $tabs;
      
      // Create the editor panels
      $panels = $( '<div class="ditty-editor__panels"></div>' );
      this.$panels = $panels;
      
      // Add the new elements
      $update.prepend( $updateCount, $updateLabel );
      $header.append( $update );
      $contents.append( $header, $tabs, $panels );
      this.$elmt.append( $contents );
      
      this.$overlay = $( '<div class="ditty-updating-overlay ditty-admin-item__overlay"><div class="ditty-updating-overlay__inner"><i class="fas fa-sync-alt fa-spin"></i></div></div>' );
      this.$elmt.append( this.$overlay );
      
      // Setup action listeners
      $( document ).on( 'postbox-moved', { self: this }, this._postboxMoved );
			$( document ).on( 'postboxes-columnchange', { self: this }, this._postboxMoved );
			$( window ).on( 'beforeunload', { self: this }, this._beforeunload );
			this.ditty.$elmt.on( 'ditty_active_items_update', { self: this }, this._dittyItemsUpdated );
			this.ditty.$elmt.on( 'ditty_disabled_items_update', { self: this }, this._disabledItemsUpdate );
			this.$tabs.on( 'click', '.ditty-editor__tab', { self: this }, this._showPanel );
			this.$update.on( 'click', { self: this }, this._saveClick );
			this.$panels.on( 'ditty_slider_init', { self: this }, this._editorLoaded );
			this.$panels.on( 'ditty_slider_before_slide_update', { self: this }, this._beforeSlideUpdate );
			this.$panels.on( 'ditty_slider_after_slide_update', { self: this }, this._afterSlideUpdate );
			this.$panels.on( 'ditty_slider_slide_removed', { self: this }, this._slideRemoved );

			// Load the editor contents
      this._loadContents();
      
      // Stop live updates
      this.trigger( 'stop_live_updates' );
			
			// Trigger the init
      setTimeout( function() {
        self.trigger( 'init' ); 
      }, 1 );
    },


    /**
		 * Select the filter and update the list
		 *
		 * @since    3.0
		 * @return   null
		*/
    _loadContents: function() {

	    var self = this;
	    
	  	var data = {
				action		: 'ditty_editor_load_contents',
				ditty_id	: this.dittyId,
				security	: dittyVars.security
			};

			$.post( dittyVars.ajaxurl, data, function( response ) {
				self._initTabs( response.tabs );
				self._initPanels( response.panels );
				self._initSlider();
			}, 'json' );

	  },

	  /**
		 * Initialize the editor
		 *
		 * @since    3.0
		 * @return   null
		*/
		_editorLoaded: function ( e ) {
			var self = e.data.self;
			$( 'body' ).trigger( 'ditty_editor_loaded', [ self ] );
		},
		
		/**
		 * Get the editor update count
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _setUpdateCount: function() {
			// var total = 0;
			// $.each( this.unsavedUpdates, function( type, data ) {
			// 	total += data.length;
			// } ); 
			// 
			// if ( total > 0 ) {
			// 	this.$updateCount.text( total );
			// } else {
			// 	this.$updateCount.text( '' );
			// }
		},

	  /**
		 * Initialize the slider
		 *
		 * @since    3.0
		 * @return   null
		*/
		_initSlider: function () {
			var slideId = this.tabs[0].id;
			if ( this.currentPanel && '' !== this.currentPanel ) {
				slideId = this.currentPanel;
			}

			// Create a new slider and bind actions
      this.$panels.ditty_slider( {
	      transition			: 'fade',
	      transitionSpeed	: 0.75,
	      heightSpeed			: 0.75,
	      touchSwipe			: false,
	      slides					: this.panels,
	      slideId					: slideId
	    } );
		},
		
		/**
		 * Before slide update
		 *
		 * @since    3.0
		 * @return   null
		*/
		_beforeSlideUpdate: function ( e, index, slide, prevIndex, prevSlide ) {	
			var self = e.data.self;
			self.updateStart();
			self._updateTab( slide.id );
			$( 'body' ).trigger( 'ditty_editor_before_panel_update', [ slide.id, slide.$elmt, prevSlide.id, prevSlide.$elmt, self ] );
		},
		
		/**
		 * After slide update
		 *
		 * @since    3.0
		 * @return   null
		*/
		_afterSlideUpdate: function ( e, index, slide, prevIndex, prevSlide ) {	
			var self = e.data.self;
			self.updateStop();
			$( 'body' ).trigger( 'ditty_editor_after_panel_update', [ slide.id, slide.$elmt, prevSlide.id, prevSlide.$elmt, self ] );
		},
		
		/**
		 * Slide removed notice
		 *
		 * @since    3.0
		 * @return   null
		*/
		_slideRemoved: function ( e, slide ) {	
			var self = e.data.self;
			$( 'body' ).trigger( 'ditty_editor_panel_removed', [ slide.id, slide.$elmt, self ] );
		},

		/**
		 * Show a specific panel
		 *
		 * @since    3.0
		 * @return   null
		*/
		_showPanel: function ( e ) {
			e.preventDefault();
			var self 			= e.data.self,
					$tab 			= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					panelId 	= $tab.data( 'panel' ),
					index			= parseInt( $tab.data( 'index' ) );
		  
		  if ( panelId === self.currentTab ) {
			  return false;
			}
			
			var transition = index > self.currentTab ? 'slideLeft' : 'slideRight';
		  self.$panels.ditty_slider( 'options', 'transition', transition );
		  self.$panels.ditty_slider( 'showSlideById', panelId );
		  self.currentTab = index;
		  self.$elmt.trigger( 'ditty_editor_add_drafts' );
		},

		/**
		 * Update the active tab
		 *
		 * @since    3.0
		 * @return   null
		*/
		_updateTab: function ( id ) {
			var $tab = $( '.ditty-editor__tab[data-panel="' + id + '"]' );
			if ( undefined !== $tab[0] ) {
				$( '.ditty-editor__tab' ).removeClass( 'active' );
				$tab.addClass( 'active' );
				this.currentTab = parseInt( $tab.data( 'index' ) );
			}
		},
	  
	  /**
		 * Initialize the editor tabs
		 *
		 * @since    3.0
		 * @return   null
		*/
    _initTabs: function ( tabs ) {
	    var self 	= this,
	    		index = 0;
	    $.each( tabs, function( key, value ) {
		    var $tab = $( '<a href="#" class="ditty-editor__tab ditty-editor__tab--' + key + '" data-panel="' + key + '" data-index="' + index + '"><i class="' + value.icon + '"></i><span>' + value.label + '</span></a>' );
		    self.$tabs.append( $tab );
		    self.tabs.push( {
			    id: key,
			    tab: $tab
		    } );
		    index++;
			} ); 
	  },
	  
	  /**
		 * Initialize the editor panels
		 *
		 * @since    3.0
		 * @return   null
		*/
    _initPanels: function ( panels ) {
	    var self = this;
	    $.each( panels, function( key, value ) {
		    var html = '<div class="ditty-editor__panel ditty-editor__panel--' + key + '">' + value + '</div>';		
		    self.panels.push( {
			    id		: key,
			    html	: html,
			    cache	: true
		    } );
			} ); 
	  },
		
		/**
		 * Initialize dynamic fields
		 *
		 * @since    3.0
		 * @return   null
		*/
    _initFields: function ( $fields ) {
	    $fields.find( '.ditty-data-list' ).ditty_ui_data_list();
			$fields.trigger( 'ditty_init_fields' );
			$.protip({
				defaults: {
					position: 'top',
					size: 'small',
					scheme: 'black',
					classes: 'ditty-protip'
				}
			});
	  },

	  /**
		 * Save the Ditty via ajax
		 *
		 * @since    3.0
		 * @return   null
		*/
	  saveDitty: function( args ) {
		  var self 					= this,
		  		dittyId 		= self.dittyId;
		  		
		  self.$updateLabel.text( 'Updating...' );
		  self.updateStart(); // Stop the update overlay

		  var data = {
        action				: 'ditty_editor_save',
        ditty_id 			: dittyId,
				draft_values	: self.draftValues,
				return_items 	: 1,
        security			: dittyVars.security
	    };
			data = $.extend( {}, data, args );

			$.post( dittyVars.ajaxurl, data, function( response ) {
				$( 'body' ).trigger( 'ditty_editor_save_ditty_response', [response] );

				// Update the items
				if ( response.display_items ) {
					self.ditty.options( 'items', response.display_items );
				}
				self.draftValues		= {};
				self.unsavedUpdates	= {};
				self._setUpdateCount();
				self.$elmt.removeClass( 'ditty-editor--updates-exist' );
				
				self.$updateLabel.text( 'Ditty Saved!' );
				setTimeout(function() {
					self.$updateLabel.text( 'Save Ditty' );
				}, 2000 );
				self.updateStop(); // Stop the update overlay
				
				if ( response.new_ditty_url ) {
					window.history.pushState( null, '', '/wp-admin/post.php?post=' + dittyId + '&action=edit' );
				}
			} );
    },
		_saveClick: function( e ) {
			e.preventDefault(); 
			var self = e.data.self;
			self.$elmt.trigger( 'ditty_editor_save_drafts' );
			if ( ! self.delayedSubmit ) {
				self.saveDitty();
			}
		},
	    
	  /**
		 * Close
		 *
		 * @since    3.0
		 * @return   null
		*/
/*
	  _close: function() {
	    //this.trigger( 'close' );
    },
*/
	  	  
	  /**
		 * Cancel click
		 *
		 * @since    3.0
		 * @return   null
		*/
/*
	  _cancel_click: function( e ) {
		  e.preventDefault();
		  var self = e.data.self;
	    self._close();
    },
*/
 
    /**
		 * Listen for updated items
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _dittyItemsUpdated: function( e, ditty, items ) {
			var self = e.data.self;
			self.activeItems = items;
			self.trigger( 'active_items_update' );
		},
		
		/**
     * Listen for updated disabled items
		 *
		 * @since    3.0
		 * @return   null
		*/
	  _disabledItemsUpdate: function( e, items ) {
		  var self = e.data.self;
		  $.each( items, function( id, status ) {
			  if ( 'disabled' === status ) {
				  self.$elmt.find( '#ditty-editor-item--' + id ).addClass( 'ditty-editor-item--disabled' );
			  } else {
				  self.$elmt.find( '#ditty-editor-item--' + id ).removeClass( 'ditty-editor-item--disabled' );
			  }
			} );   
    },
    
		/**
		 * Postbox moved listener
		 *
		 * @since    3.0
		 * @return   null
		*/
		_postboxMoved: function ( e, item ) {
			if ( ! window.tinymce ) {
				return false;
			}

			$( item ).find( '.wp-editor-area' ).each( function() {
				var id = $( this ).attr( 'id' );
				tinymce.execCommand( 'mceRemoveEditor', true, id );
				tinymce.execCommand( 'mceAddEditor', true, id );
			} );
		},
		
		/**
		 * Warn users before leaving
		 *
		 * @since    3.0
		 * @return   null
		*/
		_beforeunload: function ( e  ) {
			var self = e.data.self;
			if ( Object.keys( self.unsavedUpdates ).length > 0 ) {
				return true;
			} else {
				return undefined;
			}
		},
      
    /**
		 * Window resize listener
		 *
		 * @since    3.0
		 * @return   null
		*/
    _windowResize: function( e ) {
	    e.preventDefault();
		  //var self = e.data.self;
		  //self._set_editor_padding();
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
		 * Check if an update exists
		 *
		 * @since    3.0
		 * @return   null
		*/
    panelExists: function ( id ) {
	    var panels = this.$panels.ditty_slider( 'options', 'slides' ),
	    		exists = false;
	    
	    $.each( panels, function( index, panel ) {
		    if ( String( panel.id ) === String( id ) ) {
			    exists = true;
			    return;
		    }
		  } );
	    return exists;
	  },
	  
	  /**
		 * Check if an update exists
		 *
		 * @since    3.0
		 * @return   null
		*/
    showPanel: function ( id, html ) {
	    if ( html ) {
		    this.$panels.ditty_slider( 'addSlideById', id, html );
	    }
	    this.$panels.ditty_slider( 'showSlideById', id, true );
	  },
    
    /**
		 * Check if an update exists
		 *
		 * @since    3.0
		 * @return   null
		*/
    updateExists: function ( type, id ) {
	    if ( undefined === this.unsavedUpdates[type] ) {
		    this.unsavedUpdates[type] = [];
	    }
	    if ( -1 !== $.inArray( id, this.unsavedUpdates[type] ) ) {
		    return true;
	    }
	  },
    
    /**
		 * Let the user know there are unsaved updates
		 *
		 * @since    3.0
		 * @return   null
		*/
    addUpdate: function ( type, id ) {
	    var updated = false;
	    if ( undefined === this.unsavedUpdates[type] ) {
		    this.unsavedUpdates[type] = [];
	    }
	    if ( -1 === $.inArray( id, this.unsavedUpdates[type] ) ) {
		    updated = true;
		    this.unsavedUpdates[type].push( id );
	    }
	    this._setUpdateCount();
	    this.$elmt.addClass( 'ditty-editor--updates-exist' );
			//this.$updateLabel.text( 'Unsaved Updates' );

	    return updated;
	  },
	  
	   /**
		 * Remove an update notification
		 *
		 * @since    3.0
		 * @return   null
		*/
    removeUpdate: function ( type, id ) {
	    if ( undefined === this.unsavedUpdates[type] ) {
		    return false;
	    }
	    if ( -1 === $.inArray( id, this.unsavedUpdates[type] ) ) {
		    return false;
	    }
	    var removed = false,
	    		updatedType = [];
					
	    $.each( this.unsavedUpdates[type], function( index, type_id ) { 
		    if ( String( id ) === String( type_id ) ) {
			    removed = true;
			  } else {
			    updatedType.push( type_id ); 
		    }
		  } );
		  this.unsavedUpdates[type] = updatedType;
	    this._setUpdateCount();
	    if ( '' === this.$updateCount.text() ) {
		    this.$elmt.removeClass( 'ditty-editor--updates-exist' );
	    }
	    return removed; 
	  },
        
    /**
		 * Show the update overlay
		 *
		 * @since    3.0
		 * @return   null
		*/
    updateStart: function () {
	    this.$overlay.fadeIn();
	  },
	  
	  /**
		 * Hide the update overlay
		 *
		 * @since    3.0
		 * @return   null
		*/
    updateStop: function () {
	    this.$overlay.fadeOut();
	  },
    
    /**
		 * Initialize dynamic fields
		 *
		 * @since    3.0
		 * @return   null
		*/
    initFields: function ( $fields ) {
	    this._initFields( $fields );
	  },
		
		/**
		 * Add draft values
		 *
		 * @since    3.0
		 * @return   null
		*/
		updateDraftValues: function ( key, value ) {
			this.$elmt.addClass( 'ditty-editor--updates-exist' );
			if ( key ) {
				this.draftValues[key] = value;
				if ( 'development' === dittyVars.mode && window.console ) {
					console.log( 'draftValues:', this.draftValues );
				}
				return this.draftValues[key];
			} else {
				this.draftValues = value;
				if ( 'development' === dittyVars.mode && window.console ) {
					console.log( 'draftValues:', this.draftValues );
				}
				return this.draftValues;
			}
			
		},
		
		/**
		 * Get draft values
		 *
		 * @since    3.0
		 * @return   null
		*/
		getDraftValues: function ( key ) {
			if ( key ) {
				if ( this.draftValues[key] ) {
					return this.draftValues[key];
				} else {
					return false;
				}
			} else {
				return this.draftValues;
			}
		},
		
		/**
		 * Enable/disable delayed submit
		 *
		 * @since    3.0
		 * @return   null
		*/
		delayedSubmitEnable: function () {
			this.delayedSubmit = true;
		},
		delayedSubmitDisable: function () {
			this.delayedSubmit = false;
		},
		
		/**
		 * Setup triggers
		 *
		 * @since  	3.0
		 * @return 	null
		*/
    trigger: function ( fn, customParams ) { 
	    var params = [];
	    
	    switch( fn ) {
		    case 'active_items_update':
		    	params = [this.ditty, this.activeItems];
		    	break;
		    case 'stop_live_updates':
		    	params = [this.dittyId];
		    	break;
		    default:
		    	params = [this.settings];
		    	break;
	    }
	    
	    if ( customParams ) {
		    params = customParams;
	    }

	    this.$elmt.trigger( 'ditty_editor_' + fn, params );
	    if ( typeof this.settings[fn] === 'function' ) {
	      this.settings[fn].apply( this.$elmt, params );
	    }
	    $( 'body' ).trigger( 'ditty_' + fn, params );
    },	
		
		/**
		 * Allow settings to be modified
		 *
		 * @since  	3.0
		 * @return 	null
		*/
/*
    options: function ( key, value ) {

	    if ( typeof key === 'object' ) {
	      this.settings = $.extend( {}, defaults, $.ditty_editor.defaults, key );
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
*/	

		/**
		 * Return option data for the object
		 *
		 * @since    3.0
		 * @return   value
		*/
    _getPanelOption: function( key ) {
	    switch( key ) {
		    case 'elmnt':
		    	return this.$panels;
				default:
					return this.settings[key];
	    }
    },
    
    /**
		 * Set options data for the object
		 *
		 * @since    3.0
		 * @return   null
		*/
    _setPanelOption: function( key, value ) { 
			if ( undefined === value ) {
				return false; 
			}
			this.$panels.ditty_slider( 'options', key, value );
	    this.trigger( 'update' );
    },
    
    /**
		 * Hook to get or set editor options
		 *
		 * @since    3.0
		 * @return   null
		*/
    panelOptions: function ( key, value ) {
	    var self = this;
	    if ( typeof key === 'object' ) {   
		    $.each( key, function( k, v ) {
			    self._setPanelOption( k, v );
				} );  
	    } else if ( typeof key === 'string' ) {
        if ( value === undefined ) {
	        return self._getPanelOption( key );
        }
        self._setPanelOption( key, value );
	    } else {
        return self.$panels.ditty_slider( 'options' );
	    }
    },

		/**
		 * Return option data for the object
		 *
		 * @since    3.0
		 * @return   value
		*/
    _getOption: function( key ) {
	    switch( key ) {
		    case 'elmnt':
		    	return this;
				default:
					return this.settings[key];
	    }
    },
    
    /**
		 * Set options data for the object
		 *
		 * @since    3.0
		 * @return   null
		*/
    _setOption: function( key, value ) { 
			if ( undefined === value ) {
				return false; 
			}
	    this.settings[key] = value;
	    this.trigger( 'update' );
    },
    
    /**
		 * Hook to get or set editor options
		 *
		 * @since    3.0
		 * @return   null
		*/
    options: function ( key, value ) {
	    var self = this;
	    if ( typeof key === 'object' ) {   
		    $.each( key, function( k, v ) {
			    self._setOption( k, v );
				} );  
	    } else if ( typeof key === 'string' ) {
        if ( value === undefined ) {
	        return self._getOption( key );
        }
        self._setOption( key, value );
	    } else {
        return self.settings;
	    }
    },
		
		/**
		 * Destroy the editor
		 *
		 * @since  	3.0
		 * @return 	null
		*/
    destroy: function () {
	    if ( this.$panels.ditty_slider ) {
	      this.$panels.ditty_slider( 'destroy' );
      }
      
      $( document ).off( 'postbox-moved', { self: this }, this._postboxMoved );
			$( document ).off( 'postboxes-columnchange', { self: this }, this._postboxMoved );
			$( window ).off( 'beforeunload', { self: this }, this._beforeunload );
			this.ditty.$elmt.off( 'ditty_active_items_update', { self: this }, this._dittyItemsUpdated );
			this.ditty.$elmt.off( 'ditty_disabled_items_update', { self: this }, this._disabledItemsUpdate );
			this.$tabs.off( 'click', 'ditty-editor__tab', { self: this }, this._showPanel );
			this.$update.off( 'click', { self: this }, this._saveClick );
			this.$panels.off( 'ditty_slider_init', { self: this }, this._editorLoaded );
			this.$panels.off( 'ditty_slider_before_slide_update', { self: this }, this._beforeSlideUpdate );
			this.$panels.off( 'ditty_slider_after_slide_update', { self: this }, this._afterSlideUpdate );
			this.$panels.off( 'ditty_slider_slide_removed', { self: this }, this._slideRemoved );

      this.trigger( 'destroy' );
      this.elmt._ditty_editor = null;
    }
  };
	
	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_editor = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_editor ) {
        	this._ditty_editor = new Ditty_Editor( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_editor;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Editor applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Editor.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_editor = {};
  $.ditty_editor.defaults = defaults;

} )( jQuery );
