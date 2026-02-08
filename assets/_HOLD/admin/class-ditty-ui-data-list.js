/**
 * UI - Data List
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  listType				: 'toggle',
	  showAll 				: true,
	  activeFilter		: '*',
	  filter					: '.ditty-data-list__filter',
	  filterSelector 	: 'filter',
	  item 						: '.ditty-data-list__item',
	  itemSelector 		: 'filter'
  };

  var Ditty_UI_Data_List = function ( elmt, options ) {
	  
    this.elmt         		= elmt;
    this.settings     		= $.extend( {}, defaults, $.ditty_ui_data_list.defaults, options );
    this.$elmt        		= $( elmt );
    this.filters        	= [];

    this._init();
  };


  Ditty_UI_Data_List.prototype = {

    /**
		 * Initialize the data list
		 *
		 * @since		3.0
		 * @return	null
		*/
    _init: function () {

      var self = this;
      
			this.$elmt.on( 'click', this.settings.filter, { self: this }, this._filterClick );
			this.$elmt.find( this.settings.filter + '.active' ).each( function() {
				self._filterList( $( this ).data( self.settings.filterSelector ) );
			});
			
			if ( '*' !== this.settings.activeFilter ) {
				this._filterList( this.settings.activeFilter );
			}

			// Trigger the init
      setTimeout( function () {
        self.trigger( 'init' ); 
      }, 1 );
    },
    
    
    /**
		 * Select the filter and update the list
		 *
		 * @since    3.0
		 * @return   null
		*/
    _filterList: function( filter ) {
	    
	    var self = this,
	    		filters = this.filters;

	    if ( 'toggle' === this.settings.listType ) {
		    
		    if ( this.filters.includes( filter ) ) {
			    if ( this.settings.showAll ) {
				    this.filters = [];
				    this.$elmt.find( this.settings.filter ).removeClass( 'active' );
				    this.$elmt.find( this.settings.item ).show();
			    }
			  } else {
			    this.filters = [ filter ];
			    this.$elmt.find( this.settings.filter ).removeClass( 'active' );
					this.$elmt.find( this.settings.filter + '[data-' + this.settings.filterSelector + '="' + filter + '"]' ).addClass( 'active' );
					this.$elmt.find( this.settings.item ).hide();
					this.$elmt.find( this.settings.item + '[data-' + this.settings.itemSelector + '="' + filter + '"]' ).show();
				}
		  } else if ( 'filter' === this.settings.listType ) {
			  
			  this.$elmt.find( this.settings.item ).hide();
			  
			  if ( this.filters.includes( filter ) ) {
			    this.$elmt.find( this.settings.filter + '[data-' + this.settings.filterSelector + '="' + filter + '"]' ).removeClass( 'active' );
					for( var i = 0; i < this.filters.length; i++ ) { 
					   if ( self.filters[i] === filter ) {
					     self.filters.splice( i, 1 ); 
					   }
					}
				} else {	
					this.$elmt.find( this.settings.filter + '[data-' + this.settings.filterSelector + '="' + filter + '"]' ).addClass( 'active' );
			    this.filters.push( filter );
				}

				// Show the filtered items
				$.each( this.filters, function( index, value ) {
				  self.$elmt.find( this.settings.item + '[data-' + this.settings.itemSelector + '="' + value + '"]' ).show();
				});
				
				// Show all if no filters
				if( 0 === this.filters.length ) {
					self.$elmt.find( this.settings.item ).show();
				}
		  }

		  if ( filters !== this.filters ) {
			  self.trigger( 'update' ); 
		  } 
	  },
    
    
    /**
		 * Filter click listener
		 *
		 * @since    3.0
		 * @return   null
		*/
    _filterClick: function( e ) {
	    e.preventDefault();
	    
	    var self = e.data.self,
	    		$link = $( e.target );
	    		
	    if ( ! $link.is( 'a' ) ) {
		    $link = $link.parents( 'a' );
	    }

	    self._filterList( $link.data( self.settings.filterSelector ) );
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
	    var params = [ this.settings, this.filters ]; 
	    
	    if ( customParams ) {
		    params = customParams;
	    }

	    this.$elmt.trigger( 'ditty_ui_data_list_' + fn, params );
	
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
	      this.settings = $.extend( {}, defaults, $.ditty_ui_data_list.defaults, key );
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
			
			this.$elmt.off( 'click', this.settings.filter, { self: this }, this._filterClick );
			
	    // Trigger a reset notice
	    this.trigger( 'destroy' ); 
	    this.elmt._ditty_ui_data_list = null;
    }
  };

	
	/**
	 * Create the data list
	 *
	 * @since  	3.0
	 * @return 	null
	*/
  $.fn.ditty_ui_data_list = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_ui_data_list ) {
        	this._ditty_ui_data_list = new Ditty_UI_Data_List( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_ui_data_list;

        if ( ! instance ) {
          throw new Error( 'No Ditty_UI_Data_List applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_UI_Data_List.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_ui_data_list = {};
  $.ditty_ui_data_list.defaults = defaults;

} )( jQuery );
