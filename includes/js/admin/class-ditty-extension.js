/**
 * Ditty Extension class
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
  };

  var Ditty_Extension = function ( elmt, options ) {
	  
    this.elmt       = elmt;
    this.settings   = $.extend( {}, defaults, $.ditty_extension.defaults, options );
    this.$elmt      = $( elmt );
    this.$panels    = $( elmt ).find( '.ditty-extension__panels' );
    this.initPanel	= this.$panels.data( 'init_panel' );

    this._init();
  };

  Ditty_Extension.prototype = {
    
    _init: function () {
      
      // Add listeners
			this.$elmt.on( 'click', '.ditty-extension__tab', { self: this }, this._tabClick );
	    this.$elmt.on( 'click', 'button[name="submit"]', { self: this }, this._updatePanel );
	    this.$elmt.on( 'click', '.ditty-extension__license__submit', { self: this, action: 'ditty_extension_license_activate' }, this._licenseUpdate );
			this.$elmt.on( 'click', '.ditty-extension__license__refresh', { self: this, action: 'ditty_extension_license_refresh' }, this._licenseUpdate );
	    this.$elmt.on( 'click', '.ditty-extension__license__deactivate', { self: this, action: 'ditty_extension_license_deactivate' }, this._licenseUpdate );
			this.$panels.on( 'ditty_slider_before_slide_update', { self: this }, this._beforePanelUpdate );
			this._initSlider();
    },
    
    /**
		 * Initialize the slider
		 *
		 * @since    3.0
		 * @return   null
		*/
		_initSlider: function () {
			var args = {
				transition			: 'fade',
				transitionSpeed	: 0.75,
				heightSpeed			: 0.75,
				touchSwipe			: false,
				slidesEl				: '.ditty-extension__panel',
				slideId					: ( '' !== this.initPanel ) ? this.initPanel : false
			};
			this.$panels.ditty_slider( args );
		},
		
		/**
		 * Initialize the slider
		 *
		 * @since    3.0
		 * @return   null
		*/
		_beforePanelUpdate: function ( e, index, slide ) {
			var self = e.data.self;
			self.$elmt.find( '.ditty-extension__tab' ).removeClass( 'active' );
			self.$elmt.find( '.ditty-extension__tab[data-slide_id="' + slide.id + '"]' ).addClass( 'active' );
		},

		/**
		 * Tab click
		 *
		 * @since    3.0
		 * @return   null
		*/
		_tabClick: function ( e ) {
			e.preventDefault();
		  var self 					= e.data.self;
			var $tab 					= $( this ),
					slideId 			= $tab.data( 'slide_id' ),
					transition 		= 'slideLeft',
					$currentTab		= self.$elmt.find( '.ditty-extension__tab.active' );
					
			if ( $currentTab === $tab ) {
				return false;
			}

			if ( $tab.index() < $currentTab.index() ) {
				transition 	= 'slideRight';
			}

			//self.$elmt.find( '.ditty-extension__tab' ).removeClass( 'active' );
			//$tab.addClass( 'active' );
			
			self.$panels.ditty_slider( 'options', 'transition', transition );
		  self.$panels.ditty_slider( 'showSlideById', slideId );
		},
		
		/**
		 * Update inputs after save and sanitize
		 *
		 * @since    3.0.19
		 * @return   null
		*/
		_upateInputs: function( updates ) {
			$.each( updates, function( inputName, updatedValue ) {
				if ( $( 'input[name="' + inputName + '"]' ).length ) {
					$( 'input[name="' + inputName + '"]' ).val( updatedValue );
				}
			} );
		},
		
		/**
		 * Panel update listener
		 *
		 * @since    3.0.19
		 * @return   null
		*/
		_updatePanel: function ( e ) {
			e.preventDefault();
		  var self = e.data.self;

			var $button 	= $( this ),
	    		$panel		= $button.parents( '.ditty-extension__panel' ),
	    		$form			= $button.parents( '.ditty-extension__form' ),
	    		$icon			= $button.find( 'i' ),
					iconClass	= $icon.attr( 'class' ),
	    		extension = self.$elmt.data( 'extension' ),
	    		panel 		= $panel.data( 'slide_id' );
	
	    if ( self.$elmt.hasClass( 'updating' ) ) {
				return false;
			}

			self.$elmt.addClass( 'updating' );
		  $icon.attr( 'class', dittyAdminVars.updateIcon );
		  
		  $form.ajaxSubmit( {
		    url				: dittyAdminVars.ajaxurl,
				type			: 'post',
				dataType	: 'json',
				data			: {
	        action		: 'ditty_extension_panel_update',
	        extension : extension,
	        panel			: panel,
	        security	: dittyAdminVars.security
		    },
        success: function( data ) {
	        $icon.attr( 'class', iconClass );
					self.$elmt.removeClass( 'updating' );
					if ( data.input_updates ) {
						self._upateInputs( data.input_updates );
					}
					$( '#ditty-extensions' ).trigger( 'ditty_extension_panel_updated', [ data, self.$elmt, $panel ] ); 
        }
	    } );
		},
		
		/**
		 * Update a license
		 *
		 * @since    3.0
		 * @return   null
		*/
		_licenseUpdate: function ( e ) {
			e.preventDefault();
		  var self 		= e.data.self,
		  		action 	= e.data.action;
			
			var $button					= $( this ),
					$message				= self.$elmt.find( '.ditty-extension__license__message' ),
	    		$input 					= $button.siblings( '.ditty-extension__license__input' ),
	    		license 				= $input.val(),
	    		extension 			= $button.data( 'extension' ),
	    		extensionId 		= $button.data( 'extension_id' ),
	    		extensionName 	= $button.data( 'extension_name' ),
	    		$icon						= $button.children( 'i' );
	    		
	    if ( self.$elmt.hasClass( 'updating' ) ) {
				return false;
			}
	    		
	    self.$elmt.addClass( 'updating' );
			$icon.attr( 'class', dittyAdminVars.updateIcon );

	    // Load the new display data		
			var data = {
				action					: action,
				license					: license,
				extension				: extension,
				extension_id		: extensionId,	
				extension_name	: extensionName,
				security				: dittyAdminVars.security
			};	
	    $.post( dittyAdminVars.ajaxurl, data, function( response ) {
		    if ( response ) {
			    self.$elmt.attr( 'data-license_status', response.status );
			    $message.html( response.message );
		    }
				if ( response.license_key ) {
					if ( 'DELETE' === response.license_key ) {
						$input.val( '' );
					} else {
						$input.val( response.license_key );
					}
				}
		    $icon.attr( 'class', $icon.data( 'class' ) );
		    self.$elmt.removeClass( 'updating' );
			}, 'json' );
		},
		
    /**
		 * Return data for the object
		 *
		 * @since    3.0
		 * @return   null
		*/
    _getOption: function( key ) {
	    switch( key ) {
				default:
					return this.settings[key];
	    }
    },
    
    /**
		 * Set data for the object
		 *
		 * @since    3.0
		 * @return   null
		*/
    _setOption: function( key, value ) {  
			if ( undefined === value ) {
				return false; 
			}

	    this.settings[key] = value;
    },
		
		/**
		 * Get or set ditty options
		 *
		 * @since    3.0
		 * @return   null
		*/
    options: function ( key, value ) {
	    var self = this;
	    if ( typeof key === 'object' ) {   
		    $.each( key, function( k, v ) {
			    self._setOption( k, v );
				});  
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
		 * Destroy this object
		 *
		 * @since    3.0
		 * @return   null
		*/
    destroy: function () {
			// Remove listeners
			this.$elmt.off( 'click', '.ditty-extension__tab', { self: this }, this._tabClick );
			this.$elmt.off( 'click', 'button[name="submit"]', { self: this }, this._updatePanel );
			this.$elmt.off( 'click', '.ditty-extension__license__submit, .ditty-extension__license__refresh', { self: this, action: 'ditty_extension_license_activate' }, this._licenseUpdate );
			this.$elmt.off( 'click', '.ditty-extension__license__deactivate', { self: this, action: 'ditty_extension_license_deactivate' }, this._licenseUpdate );
			this.$panels.off( 'ditty_slider_after_slide_update', { self: this }, this._afterPanelUpdate );
			
	    this.$panels.ditty_slider( 'destroy' );
	    this.elmt._ditty_extension = null;
    }
  };

  $.fn.ditty_extension = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_extension ) {
        	this._ditty_extension = new Ditty_Extension( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_extension;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Extension applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Extension.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_extension = {};
  $.ditty_extension.defaults = defaults;

} )( jQuery );
