/**
 * Ditty Settings
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
  };

  var Ditty_Settings = function ( elmt, options ) {
	  
    this.elmt							= elmt;
    this.settings					= $.extend( {}, defaults, $.ditty_settings.defaults, options );
    this.$elmt						= $( elmt );
    this.$form						= this.$elmt.find( '.ditty-settings__form' );
		this.$panels					= this.$elmt.find( '.ditty-settings__panels' );
		this.saveBtns					= this.$elmt.find( '.ditty-settings__save' );
		this.tabs							= this.$elmt.find( '.ditty-settings__tab' );
		this.$notice_update 	= this.$elmt.find( '.ditty-notification--updated' );
		this.$notice_error 		= this.$elmt.find( '.ditty-notification--error' );
		this.$notice_warning 	= this.$elmt.find( '.ditty-notification--warning' );
		this.initData					= null;
		this.url							= window.location.href;
    this._init();
  };


  Ditty_Settings.prototype = {

    /**
		 * Initialize the data list
		 *
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {

			// Initialize the slider
			this._initSlider();
      
      // Add listeners
			$( 'body' ).on( 'ditty_field_clone_update', { self: this }, this._enableSettingsUpdates );
			this.saveBtns.on( 'click', { self: this }, this._submitForm );
			this.$panels.on( 'ditty_slider_init', { self: this }, this._sliderInit );
			this.$panels.on( 'ditty_slider_before_slide_update', { self: this }, this._beforeSlideUpdate );
			this.$form.on( 'keyup change', 'input[type="text"], input[type="number"], textarea, select', { self: this }, this._checkUpdates );
			this.$form.on( 'click', 'input[type="radio"], input[type="checkbox"]', { self: this }, this._checkUpdates );
			this.$form.on( 'ditty_input_wysiwyg_update', '.ditty-input--wysiwyg', { self: this }, this._checkUpdates );
			this.$form.on( 'click', '.ditty-default-layout-install', { self: this }, this._installLayout );
			this.$form.on( 'click', '.ditty-default-display-install', { self: this }, this._installDisplay );
			
			this.$form.on( 'click', '.ditty-export-posts input[type="checkbox"]', { self: this }, this._exportCheckboxClick );
			
			if ( this.$elmt.hasClass( 'dynamic-tabs' ) ) {
				this.tabs.on( 'click', { self: this }, this._tabClick );
			}
			if ( this.$elmt.hasClass( 'dynamic-tabs' ) && this.url.indexOf( "#" ) > 0 ) {
				var activePanel = this.url.substring( this.url.indexOf( "#" ) + 1 );
				this._activatePanel( activePanel );
			}
    },
		
		/**
		 * Initialize the slider
		 *
		 * @since    3.0.13
		 * @return   null
		*/
		_initSlider: function () {	
			if ( this.$elmt.hasClass( 'dynamic-tabs' ) ) {
				var initPanel = this.$panels.data( 'init_panel' );
				this.$panels.ditty_slider( {
					transition						: 'fade',
					transitionEase				:	'linear',
					transitionSpeed				: 0,
					heightSpeed						: 0,
					initTransition				:	'fade',
					initTransitionEase		:	'linear',
					initTransitionSpeed		:	0,
					initHeightEase				:	'linear',
					initHeightSpeed				:	0,
					touchSwipe						: false,
					slidesEl							: '.ditty-settings__panel',
					slideId								: ( '' !== initPanel ) ? initPanel : false
				} );
			}
		},
		
		/**
		 * Slider init
		 *
		 * @since    3.0
		 * @return   null
		*/
		_sliderInit: function( e ) {
			var self = e.data.self;
			self.initData = self.$form.serialize();
		},
		
		/**
		 * Before slide update
		 *
		 * @since    3.0
		 * @return   null
		*/
		_beforeSlideUpdate: function( e, index, slide ) {
			var self = e.data.self;
			self._initFields( slide.$elmt );
			self.$elmt.find( '.ditty-settings__tab' ).removeClass( 'active' );
			self.$elmt.find( '.ditty-settings__tab[data-panel="' + slide.id + '"]' ).addClass( 'active' );
		},
		
		/**
		 * Preview button add updates class
		 *
		 * @since    3.0
		 * @return   null
		*/
		_enableSettingsUpdates: function( e ) {
			var self = ( e ) ? e.data.self : this;
			self.saveBtns.addClass( 'has-updates' );
			//self.notice_warning.slideDown();
		},
		
		/**
		 * Preview button remove updates class
		 *
		 * @since    3.0
		 * @return   null
		*/
		_disableSettingsUpdates: function() {
			this.saveBtns.removeClass( 'has-updates' );
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
				self._enableSettingsUpdates();
			}	else {
				self._disableSettingsUpdates();
			}
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
			$.protip( {
				defaults: {
					position: 'top',
					size: 'small',
					scheme: 'black',
					classes: 'ditty-protip'
				}
			} );
		},
		
		/**
		 * Show or hide post types
		 *
		 * @since    3.0
		 * @return   null
		*/
		_togglePostTypes: function () {
			if ( $( 'input[name="ditty_layout_ui"]' ).length ) {
				var layoutUiVal = $( 'input[name="ditty_layout_ui"]:checked' ).val(),
						$layoutMenu = $( '#adminmenu .wp-submenu > li > a[href="edit.php?post_type=ditty_layout"]' );
				if ( 'disabled' === layoutUiVal ) {
					$layoutMenu.hide();
				} else {
					$layoutMenu.css( 'display', 'block' );
				}
			}
			if ( $( 'input[name="ditty_display_ui"]' ).length ) {
				var displayUiVal = $( 'input[name="ditty_display_ui"]:checked' ).val(),
						$displayMenu = $( '#adminmenu .wp-submenu > li > a[href="edit.php?post_type=ditty_display"]' );
				if ( 'disabled' === displayUiVal ) {
					$displayMenu.hide();
				} else {
					$displayMenu.css( 'display', 'block' );
				}
			}
		},
		
		/**
		 * Activate a panel
		 *
		 * @since    3.0.13
		 * @return   null
		*/
		_activatePanel: function( panelId ) {
			var $tab = this.$elmt.find( '.ditty-settings__tab[data-panel="' + panelId + '"]' );
			if ( ! $tab.length ) {
				return false;
			}

			this.$elmt.find( '.ditty-settings__tab' ).removeClass( 'active' );
			$tab.addClass( 'active' );
			
			this.$panels.ditty_slider( 'showSlideById', panelId );
		},

		/**
		 * Listen for a tab click
		 *
		 * @since    3.0
		 * @return   null
		*/
		_tabClick: function( e ) {
			e.preventDefault();
			var self				= e.data.self,
					$tab				= $( e.target ).is( 'a' ) ? $( e.target ) : $( e.target ).parent( 'a' ),
					slideId 		= $tab.data( 'panel' ),
					$currentTab	= self.$elmt.find( '.ditty-settings__tab.active' );
					
			if ( $currentTab === $tab ) {
				return false;
			}
			
			var newUrl;			
			if ( slideId ) {
				var hash = '#' + slideId;
				newUrl = self.url.split("#")[0] + hash;
			} else {
				newUrl = self.url.split("#")[0];
			} 
			history.replaceState( null, null, newUrl );
			
			self._activatePanel( slideId );
		},
		
		/**
		 * Update inputs after save and sanitize
		 *
		 * @since    3.0
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
		 * Submit updates
		 *
		 * @since    3.0
		 * @return   null
		*/
		_submitForm: function( e ) {
			e.preventDefault();
			var self = e.data.self;

			if ( self.$elmt.hasClass( 'updating' ) ) {
				return false;
			}
			
			self.$elmt.trigger( 'ditty_pre_save_fields' );
			self.$elmt.addClass( 'updating' );
			self.saveBtns.text( dittyAdminVars.adminStrings.settingsSaving );
			
			self.$form.ajaxSubmit( {
				url				: dittyAdminVars.ajaxurl,
				type			: 'post',
				dataType	: 'json',
				data			: {
					action		: 'ditty_settings_save',
					security	: dittyAdminVars.security
				},
				success: function( data ) {
					if ( data.input_updates ) {
						self._upateInputs( data.input_updates );
					}
					self.initData = self.$form.serialize();
					self.saveBtns.text( dittyAdminVars.adminStrings.settings_updated );
					setTimeout( function() {
						self.saveBtns.text( dittyAdminVars.adminStrings.settings_save );
					}, 2000 );
					self.$elmt.removeClass( 'updating' );
					self._disableSettingsUpdates();

					//self.$notice_update.slideDown();
					
					// Check the post types display
					self._togglePostTypes();
				}
			} );
		},
		
		/**
		 * Install a layout
		 *
		 * @since    3.0
		 * @return   null
		*/
		_installLayout: function( e ) { 
			e.preventDefault();
			var self 			= e.data.self,
					$button 	= $( e.target ).is( 'button' ) ? $( e.target ) : $( e.target ).parents( 'button' ),
					$icon			= $button.find( 'i' ),
					iconClass	= $icon.attr( 'class' );
					
			if ( $button.hasClass( 'updating' ) ) {
				return false;
			}
			$button.addClass( 'updating' );
			$icon.attr( 'class', dittyAdminVars.updateIcon );
			
			var data = {
				action					: 'ditty_install_layout',
				layout_template : $button.data( 'layout_template' ),
				layout_version 	: $button.data( 'layout_version' ),
				security				: dittyAdminVars.security
			};
			$.post( dittyAdminVars.ajaxurl, data, function( response ) {
				$icon.attr( 'class', iconClass );
				self.$elmt.removeClass( 'updating' );	
				if ( response ) {
					$button.replaceWith( response.button );
				}
			} );
		},
		
		/**
		 * Install a display
		 *
		 * @since    3.0
		 * @return   null
		*/
		_installDisplay: function( e ) { 
			e.preventDefault();
			var self 			= e.data.self,
					$button 	= $( e.target ).is( 'button' ) ? $( e.target ) : $( e.target ).parents( 'button' ),
					$icon			= $button.find( 'i' ),
					iconClass	= $icon.attr( 'class' );
					
			if ( $button.hasClass( 'updating' ) ) {
				return false;
			}
			$button.addClass( 'updating' );
			$icon.attr( 'class', dittyAdminVars.updateIcon );
			
			var data = {
				action						: 'ditty_install_display',
				display_type			: $button.data( 'display_type' ),
				display_template 	: $button.data( 'display_template' ),
				display_version 	: $button.data( 'display_version' ),
				security					: dittyAdminVars.security
			};
			$.post( dittyAdminVars.ajaxurl, data, function( response ) {
				$icon.attr( 'class', iconClass );
				self.$elmt.removeClass( 'updating' );	
				if ( response ) {
					$button.replaceWith( response.button );
				}
			} );
		},
		
		/**
		 * Listen for export checkbox click
		 *
		 * @since    3.0.17
		 * @return   null
		*/
		_exportCheckboxClick: function( e ) { 
			var $checkbox 	= $( e.target ),
					$group 			= $checkbox.parents( '.ditty-input--checkboxes__group' ),
					$container 	= $checkbox.parents( '.ditty-field__input' ),
					$button			= $( '.ditty-export-button' ),
					checkboxes	= $group.find( 'input[type="checkbox"]' ),
					isChecked 	= $checkbox.is( ':checked' ),
					value 			= $checkbox.attr( 'value' );
					
			if ( 'select_all' === value ) {
				checkboxes.each( function() {
					if ( $( this )[0] !== $checkbox[0] ) {
						$( this ).prop( 'checked', isChecked );
					}
				} );
			}
			
			// Check if any checkboxes are selected
			var enableButton = false;
			checkboxes.each( function() {
				if ( $( this ).is( ':checked' ) ) {
					enableButton = true;
				}
			} );
			
			if ( enableButton ) {
				$button.attr( 'disabled', false );
			} else {
				$button.attr( 'disabled', 'disabled' );
			}	
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

	    this.$elmt.trigger( 'ditty_settings_' + fn, params );
	
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
	      this.settings = $.extend( {}, defaults, $.ditty_settings.defaults, key );
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
			$( 'body' ).off( 'ditty_field_clone_update', { self: this }, this._enableSettingsUpdates );
			this.saveBtns.off( 'click', { self: this }, this._submitForm );
			this.tabs.off( 'click', { self: this }, this._tabClick );
			this.$panels.off( 'ditty_slider_init', { self: this }, this._sliderInit );
			this.$panels.off( 'ditty_slider_before_slide_update', { self: this }, this._beforeSlideUpdate );
			this.$form.off( 'keyup change', 'input[type="text"], input[type="number"], textarea, select', { self: this }, this._checkUpdates );
			this.$form.off( 'click', 'input[type="radio"], input[type="checkbox"]', { self: this }, this._checkUpdates );
			this.$form.off( 'ditty_input_wysiwyg_update', '.ditty-input--wysiwyg', { self: this }, this._checkUpdates );
			this.$form.off( 'click', '.ditty-default-layout-install', { self: this }, this._installLayout );
			this.$form.off( 'click', '.ditty-default-display-install', { self: this }, this._installDisplay );
			
			this.$form.off( 'click', '.ditty-export-posts input[type="checkbox"]', { self: this }, this._exportCheckboxClick );

			this.$panels.ditty_slider( 'destroy' );
	    this.elmt._ditty_settings = null;	    
    }
  };

	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_settings = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_settings ) {
        	this._ditty_settings = new Ditty_Settings( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_settings;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Settings applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Settings.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_settings = {};
  $.ditty_settings.defaults = defaults;

} )( jQuery );
