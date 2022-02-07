/* global jQuery:true */

/**
 * Ditty Display Sandbox
 *
 * @since		3.0.14
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  displayType: null,
		dittyUniqId: null
  };

  var Ditty_Display_Sandbox = function ( elmt, options ) {
	  
    this.elmt     	= elmt;
    this.settings   = $.extend( {}, defaults, $.ditty_display_sandbox.defaults, options );
    this.$elmt      = $( elmt );
    this.$form			= this.$elmt.find( '.ditty-editor-options' );
		this.$ditty     = null;
		this.ditty			= null;
		
    this._init();
  };


  Ditty_Display_Sandbox.prototype = {

    /**
		 * Initialize the data list
		 *
		 * @since		3.0.14
		 * @return	null
		*/
    _init: function () {

			var self = this;
			
			this.$ditty = $( '.ditty[data-uniqid="' + this.settings.dittyUniqId + '"]' );
			
			if ( undefined === this.$ditty[0] || null === this.$ditty ) {
				this.$elmt.hide();
				return false;
			}

	    // Initialize dynamic fields
      this.$form.trigger( 'ditty_init_fields' );
			$.protip( {
				defaults: {
					position: 'top',
					size: 'small',
					scheme: 'black',
					classes: 'ditty-protip'
				}
			} );
			
			// Listen for ditty init
			this.$ditty.on( 'ditty_init', { self: this }, this._initDitty );
			
      // Add actions
			this.$elmt.on( 'change', 'input[type="text"], input[type="number"]', { self: this }, this._textfieldListeners );
			this.$form.on( 'click', 'input[type="radio"]', { self: this }, this._radioListeners );
			this.$form.on( 'click', 'input[type="checkbox"]', { self: this }, this._checkboxListeners );
			this.$form.on( 'change', 'select', { self: this }, this._selectListeners );
    },
		
		/**
		 * Update the Ditty
		 *
		 * @since    3.0.14
		 * @return   null
		*/
		_initDitty: function( e ) {
			var self = e.data.self;
			self.ditty = self.$ditty[0]['_ditty_' + self.settings.displayType];
			if ( undefined === self.ditty || null === self.ditty ) {
				return false;
			}
			self.$elmt.removeClass( 'ditty-display-sandbox--disabled' );
			self.trigger( 'init' ); 
		},
		
		/**
		 * Update the Ditty
		 *
		 * @since    3.0.14
		 * @return   null
		*/
		_updateDitty: function( name, value ) {
			if ( undefined === this.ditty || null === this.ditty ) {
				return false;
			}
			this.ditty.options( name, value );
		},

    /**
		 * Listen for textfield changes
		 *
		 * @since    3.0.14
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
				self._updateDitty( fieldsetName, fieldsetValue );
	  	} else { 
				self._updateDitty( name, $target.val() );
	  	}
	  },
	  
	  /**
		 * Listen for radio button changes
		 *
		 * @since    3.0.14
		 * @return   null
		*/
    _radioListeners: function( e ) { 
	  	var self = e.data.self,
					$target = $( e.target ),
					value = $target.val(),
					name = $target.attr( 'name' );

	  	self._updateDitty( name, value );
	  },
	  
	  /**
		 * Listen for checkbox changes
		 *
		 * @since    3.0.14
		 * @return   null
		*/
    _checkboxListeners: function( e ) {
			var self = e.data.self,
					$target = $( e.target ),
					value = $target.is( ':checked' ) ? $( this ).val() : false,
					name = $target.attr( 'name' );
		  				
	  	self._updateDitty( name, value );
	  },
	  
	  /**
		 * Listen for select changes
		 *
		 * @since    3.0.14
		 * @return   null
		*/
    _selectListeners: function( e ) {
			var self = e.data.self,
					$target = $( e.target ),
					value = $target.val(),
					name = $target.attr( 'name' ); 

	  	self._updateDitty( name, value );
	  },
		
		/**
		 * Setup triggers
		 *
		 * @since  	3.0.14
		 * @return 	null
		*/
		trigger: function ( fn ) { 
			var params = [this.settings, this.ditty];
			this.$elmt.trigger( 'ditty_display_sandbox_' + fn, params );
			if ( typeof this.settings[fn] === 'function' ) {
				this.settings[fn].apply( this.$elmt, params );
			}
			this.$elmt.trigger( 'ditty_' + fn, params );
		},	
		
		/**
		 * Destroy the editor
		 *
		 * @since  	3.0.14
		 * @return 	null
		*/
    destroy: function () {
			
	    // Remove actions
	    this.$elmt.off( 'change', 'input[type="text"], input[type="number"]', { self: this }, this._textfieldListeners );
			this.$form.off( 'click', 'input[type="radio"]', { self: this }, this._radioListeners );
			this.$form.off( 'click', 'input[type="checkbox"]', { self: this }, this._checkboxListeners );
			this.$form.off( 'change', 'select', { self: this }, this._selectListeners );
	    
	    this.elmt._ditty_display_sandbox = null;	    
    }
  };

	
	/**
	 * Create the data list
	 *
	 * @since  	3.0.14
	 * @return 	null
	*/
  $.fn.ditty_display_sandbox = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_display_sandbox ) {
        	this._ditty_display_sandbox = new Ditty_Display_Sandbox( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_display_sandbox;

        if ( ! instance ) {
          throw new Error( 'No Ditty_Display_Sandbox applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_Display_Sandbox.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_display_sandbox = {};
  $.ditty_display_sandbox.defaults = defaults;

} )( jQuery );
