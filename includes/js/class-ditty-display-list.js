/* global jQuery:true */
/* global dittyEditorInit:true */
/* global dittyLayoutCss:true */
/* global dittyUpdateItems:true */
/* //global console:true */

/**
 * Ditty Slider class
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
	  	id										: 0,
	  	title									: '',
			titleDisplay					: 'none',
	  	display								: 0,
			status								: '',
	  	spacing								: 30,
	  	paging								: 0, // 0, 1
	  	perPage								: 0,
	  	transition						:	'fade', // fade, slideLeft, slideREight, slideDown, slideUp
	  	transitionEase				:	'easeInOutQuint',
	  	transitionSpeed				:	1.5, // 1 - 10
	  	autoplay							: 0, // 0, 1
	  	autoplayPause					:	0, // 0, 1
	  	autoplaySpeed					:	8, // 1 - 60
	  	height								:	0,
	  	heightEase						:	'easeInOutQuint',
	  	heightSpeed						:	1.5, // 1 - 10	
	  	arrows								: 'none',
	  	arrowsIconColor				: '',
			arrowsBgColor					: '',
			arrowsPosition				: 'center',
	  	arrowsPadding					: {},
			arrowsStatic					: 0,
	  	navPrev								: '<i class="fas fa-angle-left"></i>',
	  	navNext								: '<i class="fas fa-angle-right"></i>',
	  	bullets								: 'none',
			bulletsColor					: '',
			bulletsColorActive		: '',
	  	bulletsPosition				: 'bottomCenter',
			bulletsSpacing				: 2,
	  	bulletsPadding				: {},
	  	bullet								: '',
	  	maxWidth							: '',
			bgColor								: '',
	  	padding								: {},
			margin								: {},
			borderColor						: {},
			borderStyle						: {},
			borderWidth						: {},
	  	borderRadius					: {},
	  	contentsBgColor				: '',
	  	contentsPadding				: {},
			contentsBorderColor		: {},
			contentsBorderStyle		: {},
			contentsBorderWidth		: {},
	  	contentsBorderRadius	: {},
	  	pageBgColor						: '',
	  	pagePadding						: {},
			pageBorderColor				: {},
			pageBorderStyle				: {},
			pageBorderWidth				: {},
	  	pageBorderRadius			: {},
	  	itemTextColor					: '',
			itemBgColor						: '',
	  	itemBorderColor				: {},
			itemBorderStyle				: {},
			itemBorderWidth				: {},
			itemBorderRadius			: {},
			itemPadding						: {},
	  	page									: 0,
	  	shuffle								: 0,
	  	showEditor						: 0,
      // init									: function () {},
      items									: [
	      // {
	      //  id:					null,
	      //  uniq_id:		null,
	      //  parent_id:	null,
	      //  html:				null,
	      //  status:			null,
	      // ...
      ]
  };

  var Ditty_List = function ( elmt, options ) {
	  
	  this.displayType  = 'list';
    this.elmt         = elmt;
    this.$elmt        = $( elmt );
    this.settings     = $.extend( {}, defaults, $.ditty_list.defaults, options );
    this.total        = this.settings.items.length;
    this.totalPages   = 1;
    this.page					= this.settings.page;
    this.pages				= []; 
    this.enabledItems	= [];
    this.visibleItems = [];
    this.editItem			= null;
		
    if ( 1 === parseInt( this.settings.shuffle ) ) {
      this.shuffle();
    }

    this._init();
  };

  Ditty_List.prototype = {
    
    _init: function () {
	    
	    // Remove the pre class
	    this.$elmt.removeClass( 'ditty--pre' );

      // Add classes and data attributes
      this.$elmt.addClass( 'ditty ditty-list' );
      this.$elmt.attr( 'data-id', this.settings.id );
      this.$elmt.attr( 'data-type', this.displayType );
      this.$elmt.attr( 'data-display', this.settings.display );
      
      // Calculate the number of pages
      this._calculatePages();
      
      // Initialize the slider
      this._initSlider();

			// Show the editor or start live updates
			if ( this.settings.showEditor ) {
	      dittyEditorInit( this );
      } else {
				this.trigger( 'start_live_updates' );
      }
    },
    
    /**
		 * Initialize the slider
		 *
		 * @since    3.0
		 * @return   null
		*/
		_initSlider: function () {
			
			// Modify any settings to fit a slider
			var sliderSettings = [];
			$.each( this.settings, function( key, value ) {
				var sliderKey = key.replace( 'page', 'slide' );
				sliderSettings[sliderKey] = value;
			} );
			sliderSettings.slides = this.pages;
			
			// Create a new slider and bind actions
      this.$elmt.ditty_slider( sliderSettings );
      this.$elmt.on( 'ditty_slider_init', { self: this }, this._triggerInit );
      this.$elmt.on( 'ditty_slider_update', { self: this }, this._triggerUpdate );
      this.$elmt.on( 'ditty_slider_after_slide_update', { self: this }, this._triggerShowSlide );
		},
		
		/**
		 * Destroy the slider
		 *
		 * @since    3.0
		 * @return   null
		*/
		_destroySlider: function () {
      if ( this.$elmt.ditty_slider ) {
	      this.$elmt.off( 'ditty_slider_init', { self: this }, this._triggerInit );
	      this.$elmt.off( 'ditty_slider_update', { self: this }, this._triggerUpdate );
	      this.$elmt.off( 'ditty_slider_after_slide_update', { self: this }, this._triggerShowSlide );
	      this.$elmt.ditty_slider( 'destroy' );
      }
		},
    
    /**
		 * Update the slider
		 *
		 * @since    3.0
		 * @return   null
		*/
		_updateSlider: function ( index ) {
			var newIndex = index ? index : this.$elmt.ditty_slider( 'options', 'slide');
			this.$elmt.ditty_slider( 'options', 'slides', this.settings.pages );
	    this.$elmt.ditty_slider( 'options', 'slide', -1 );
		  this.$elmt.ditty_slider( 'showSlide', newIndex );
		},

		/**
		 * Style individual items
		 *
		 * @since    3.0
		 * @return   null
		*/
		_styleItem: function( $item ) {
	    $item.children( '.ditty-item__elements' ).css( {
				color: this.settings.itemTextColor,
				backgroundColor: this.settings.itemBgColor,
				borderColor: this.settings.itemBorderColor,
				borderStyle: this.settings.itemBorderStyle
			} );
			$item.children( '.ditty-item__elements' ).css( this.settings.itemPadding );
			$item.children( '.ditty-item__elements' ).css( this.settings.itemBorderRadius );
			$item.children( '.ditty-item__elements' ).css( this.settings.itemBorderWidth );
			$item.css( {
				paddingBottom: this.settings.spacing + 'px'
			} );
		},
		
		/**
		 * Create a page of items
		 *
		 * @since    3.0
		 * @return   null
		*/
    _createPage: function( index ) {   
	  	var self = this,
	  			$page = $( '<div class="ditty-list__page ditty-list__page--' + index + '"></div>' ),
	  			items = this._getItemsByPageIndex( index );
			
			$.each( items, function( index, value ) {
				var $item = $( value.html );
				self._styleItem( $item );

				// Add the layout css to the DOM
				if ( value.css ) {
					dittyLayoutCss( value.css, value.layout_id );
				}
				$page.append( $item );
			} );
			
			// Remove the spacing from the last item
			$page.children().last().css( { paddingBottom: 0 } );
			
			var page = {
				id		: 'page' + parseInt( index + 1 ),
				html	: $page,
				items : items
			};
			
			return page;	
    },
		
		/**
		 * Calculate and create pages
		 *
		 * @since    3.0
		 * @return   null
		*/
		_calculatePages: function () {
			var self = this,
					items = [];
			$.each( this.settings.items, function( index, item ) {
				if ( self._isItemEnabled( index ) ) {
					items.push( item );
				}
			} );
			this.enabledItems = items;
			this.total = items.length;
				
	    if ( parseInt( this.settings.paging ) && parseInt( this.settings.perPage ) > 0 ) {
		    this.totalPages = Math.ceil( parseInt( this.total ) / parseInt( this.settings.perPage ) );
	    } else {
		    this.totalPages = 1;
	    }
	    
	    this.pages = [];
	    for( var i = 0; i < this.totalPages; i++ ) {
		    this.pages.push( this._createPage( i ) );
	    }
    },
    
    /**
		 * Get a page index by item index
		 *
		 * @since    3.0
		 * @return   null
		*/
    _getPageByItemIndex: function ( index ) {
	    var pageIndex = Math.ceil( ( parseInt( index ) + 1 ) / this.settings.perPage ) - 1; 
	    return pageIndex; 
    },
    
    /**
		 * Get items by page index
		 *
		 * @since    3.0
		 * @return   null
		*/
    _getItemsByPageIndex: function ( index ) {
	    var items = this.enabledItems;
			
			// Pull the items for the current index
	  	if ( parseInt( this.totalPages ) > 1 ) {
		  	var start = parseInt( this.settings.perPage ) * index,
		  			end = start + parseInt( this.settings.perPage );			
		  	items = this.enabledItems.slice( start, end );
	  	}
	    return items;
    },
		
		/**
		 * Shuffle items items TODO
		 *
		 * @since    3.0
		 * @return   null
		*/
    shuffle: function () {
	    var temp,
	        rand;
					
	    for ( var i = this.total - 1; i > 0; i-- ) {
	      rand = Math.floor( Math.random() * ( i + 1 ) );
	      temp = this.settings.items[i];
	
	      this.settings.items[i] = this.settings.items[rand];
	      this.settings.items[rand] = temp;
	    }
    },
    
    /**
		 * Check if a item is enabled
		 *
		 * @since    3.0
		 * @return   null
		*/
		_isItemEnabled: function( index ) {
			if ( undefined === this.settings.items[parseInt( index )] ) {
				return false;
			}
			if ( undefined === this.settings.items[parseInt( index )].is_disabled ) {
				return true;
			} else {
				if ( this.settings.items[parseInt( index )].is_disabled.length > 0 ) {
					return false;
				} else {
					return true;
				}
			}
		},
		
		/**
		 * Get the disabled status of all items
		 *
		 * @since    3.0
		 * @return   null
		*/ 
    _disabledItemsStatus: function () {
	    var self = this,
	    		statuses = {};
 	    $.each( this.settings.items, function( i, item ) {
		    if ( self._isItemEnabled( i ) ) {
			    statuses[item.id] = 'enabled';
		    } else {
			    statuses[item.id] = 'disabled';
		    }
			} );
			return statuses;
    },
    
    /**
		 * Add a disabled type to a item
		 *
		 * @since    3.0
		 * @return   null
		*/ 
    addItemDisabled: function ( id, slug  ) {
	    var self = this;
 	    $.each( this.settings.items, function( i, item ) {
		    if ( String( item.id ) === String( id ) ) {
					if ( ! $.isArray( self.settings.items[i].is_disabled ) ) {
						self.settings.items[i].is_disabled = [];
					}
			    self.settings.items[i].is_disabled.push( slug );
		    }
			} );
			this.updateItems( this.settings.items );
			this.trigger( 'disabled_items_update' );
    },
    
    /**
		 * Remove a disabled type from a item
		 *
		 * @since    3.0
		 * @return   null
		*/ 
    removeItemDisabled: function ( id, slug  ) {
	    var self = this;
 	    $.each( this.settings.items, function( i, item ) {
		    if ( String( item.id ) === String( id ) ) {
					if ( $.isArray( self.settings.items[i].is_disabled ) && self.settings.items[i].is_disabled.length ) {
			    	self.settings.items[i].is_disabled = $.grep( self.settings.items[i].is_disabled, function( value ) {
							return value !== slug;
						} );
					}
		    }
			} );
			this.updateItems( this.settings.items );
			this.trigger( 'disabled_items_update' );
    },
    
    /**
		 * Show a specific item by index or id
		 *
		 * @since    3.0
		 * @return   null
		*/
    showItem: function ( id ) { 
	    var itemIndexes = [];		
	    $.each( this.settings.items, function( i, item ) {
		    if ( String( item.id ) === String( id ) ) {
			    itemIndexes.push( i );
		    }
			} );   
	    if ( 0 !== itemIndexes.length ) {
		    var page = this._getPageByItemIndex( parseInt( itemIndexes[0] ) );
		    this.$elmt.ditty_slider( 'showSlide', page );
	    }
	  },
    
    /**
		 * Add a new item
		 *
		 * @since    3.0
		 * @return   null
		*/ 
    addItem: function ( item, index, type ) {
	    var newItems = this.settings.items.slice(),
					indexExists = true; 
	    
			if ( index >= this.total || index < 0 ) {
		    indexExists = false;
		  }

	    // Replace a item
	    if ( 'replace' === type && indexExists ) {
		    newItems.splice( index, 1, item );
	    
	    // Add a item
	    } else {  
		    if ( null === index || '' === index ) {
			    newItems.splice( parseInt( this.item ) + 1, 0, item );
		    } else {
			    if ( index >= this.total ) {
				    newItems.push( item );
				  } else if ( index < 0 ) {
					  newItems.splice( 0, 0, item );
				  } else {
					  newItems.splice( index, 0, item );
				  }
		    }
	    }
			this.updateItems( newItems );
    },
    
    /**
		 * Delete a item by index
		 *
		 * @since    3.0
		 * @return   null
		*/ 
    deleteItem: function ( id ) {
	    var updatedItems = [];	
			$.each( this.settings.items, function( index, item ) { 
		    if ( String( item.id ) !== String( id ) ) {
			    updatedItems.push( item );
			  }
		  } );  
		  this.updateItems( updatedItems );
    },

	  /**
		 * Update the current items
		 *
		 * @since    3.0
		 * @return   null
		*/
		updateItems: function ( newItems, itemId, type, forceSwapAll ) {
	    if ( undefined === newItems ) {
		    return false;
	    }

			var self = this,
					currentIndex = this.$elmt.ditty_slider( 'options', 'slide' ),
					forceSwaps = [];
			
			// Update a single item id		
			if ( itemId ) {
				var tempCurrentItems = this.settings.items.slice(),
						tempNewItems = [],
						tempSwapped = false;
						
				$.each( tempCurrentItems, function( index, item ) {
					if ( String( item.id ) === String( itemId ) ) {
						
						// Add after the id
						if ( 'after' === type ) {
							tempNewItems.push( item );
							$.each( newItems, function( index, newItem ) {
								tempNewItems.push( newItem );
							} );
							tempSwapped = true;
							
						// Add before the id
						} else if ( 'before' === type ) {
							$.each( newItems, function( index, newItem ) {
								tempNewItems.push( newItem );
							} );
							tempNewItems.push( item );
							tempSwapped = true;
						
						// Else swap the ID
						} else {
							if ( ! tempSwapped ) {
								$.each( newItems, function( index, newItem ) {
									tempNewItems.push( newItem );
									forceSwaps.push( String( newItem.uniq_id ) );
								} );
								tempSwapped = true;
							}
						}
					} else {
						tempNewItems.push( item );
					} 
				} );
				if ( ! tempSwapped ) {
					$.each( this.settings.items, function( index, item ) {
						tempNewItems.push( item );
					} );
					tempSwapped = true;
				}
				newItems = tempNewItems;
			}
			this.settings.items = newItems;
			this.total = newItems.length;	
			this._calculatePages();
			this.$elmt.ditty_slider( 'options', 'slides', this.pages );
			
			var $currentPage = this.$elmt.ditty_slider( 'options', 'currentSlide' ),
					currentItems = $currentPage.children( '.ditty-item' ),
					newIndex = this.$elmt.ditty_slider( 'options', 'slide' ),
					itemSwaps = [];
					
			if ( currentIndex !== newIndex ) {
				return false;
			}
			
			var newPageItems,
					newPageItemsCount = 0,
					$lastCurrentItem = null,
					lastCurrentItemPadding = -1,
					currentItemsUpdated = [];
			
			if ( this.pages[newIndex] ) {
				newPageItems = this.pages[newIndex].items;
				newPageItemsCount = newPageItems.length;
				
				// Add new page items
				$.each( newPageItems, function( index, newItem ) {
					var $newItem = $( newItem.html );
					
					// Add the css and style the items
					if ( newItem.css ) {
						dittyLayoutCss( newItem.css, newItem.layout_id );
					}
					self._styleItem( $newItem );
					if ( index === newPageItemsCount - 1 ) {
						$newItem.css( { paddingBottom: 0 } );
					}
					
					// Swap existing items
					if ( currentItems[index] ) {
						var $currentItem = $( currentItems[index] );
						$lastCurrentItem = $currentItem;
						
						currentItemsUpdated.push( index );
						if ( forceSwapAll || ( String( $currentItem.data( 'item_uniq_id' ) ) !== String( newItem.uniq_id ) ) || forceSwaps.includes( String( newItem.uniq_id ) )   ) {	
							itemSwaps.push( {
								currentItem: $currentItem,
								newItem: $newItem
							} );
						}
						
					// Add new items
					} else {
						var $tempItem = $( '<div class="ditty-temp-item"></div>' );
						$currentPage.append( $tempItem );
						itemSwaps.push( {
							currentItem: $tempItem,
							newItem: $newItem
						} );
						
						lastCurrentItemPadding = parseInt( self.settings.spacing );
					}
				} );
			}
			
			// Remove old page items
			$.each( currentItems, function( index ) {
				if ( ! currentItemsUpdated.includes( index ) ) {
					var $currentItem = $( currentItems[index] ),
							$tempItem = $( '<div class="ditty-temp-item"></div>' );
					itemSwaps.push( {
						currentItem: $currentItem,
						newItem: $tempItem
					} );
					
					lastCurrentItemPadding = 0;
				}
			} );
			
			// Add padding to the last current item
			if ( null !== $lastCurrentItem && lastCurrentItemPadding >= 0 ) {
				$lastCurrentItem.css( {
					paddingBottom: lastCurrentItemPadding + 'px'
				} );
			}
			
			dittyUpdateItems( itemSwaps );
			this.trigger( 'update' );
	  },

		/**
		 * Return the currently visible items
		 *
		 * @since    3.0
		 * @return   null
		*/
    getActiveItems: function () {
	    return this._getItemsByPageIndex( this.page );
    },
    
    /**
		 * Trigger the init
		 *
		 * @since    3.0
		 * @return   null
		*/
    _triggerInit: function ( e ) {
	    var self = e.data.self;
      self.trigger( 'init' );
    },
    
    /**
		 * Trigger an update
		 *
		 * @since    3.0
		 * @return   null
		*/
    _triggerUpdate: function ( e ) {
	    var self = e.data.self;
      self.trigger( 'update' );
    },
    
    /**
		 * Trigger a slide update
		 *
		 * @since    3.0
		 * @return   null
		*/
    _triggerShowSlide: function ( e, slide ) {
	    var self = e.data.self;
	    self.page = slide;
      self.trigger( 'active_items_update' );
    },

    /**
		 * Trigger events
		 *
		 * @since    3.0
		 * @return   null
		*/
    trigger: function ( fn ) {
	    var params = [];
	    
	    switch( fn ) {
		    case 'active_items_update':
		    	params = [this, this.getActiveItems()];
		    	break;
		    case 'disabled_items_update':
		    	params = [this._disabledItemsStatus()];
		    	break;
		    case 'start_live_updates':
		    	params = [this.settings.id];
		    	break;
		    default:
		    	params = [
		    		this.settings,
		    		this.$elmt
		    	];
		    	break;
	    }

	    this.$elmt.trigger( 'ditty_' + fn, params );   
	    if ( typeof this.settings[fn] === 'function' ) {
	      this.settings[fn].apply( this.$elmt, params );
	    } 
	    //params.unshift( this );   
	    $( 'body' ).trigger( 'ditty_' + fn, params );
    },
    
    /**
		 * Return data for the object
		 *
		 * @since    3.0
		 * @return   null
		*/
    _getOption: function( key ) {
	    switch( key ) {
		    case 'ditty':
		    	return this;
		    case 'type':
		    	return this.displayType;
		    case 'display':
		    	return this.settings.display;
				case 'items':
		    	return this.settings.items;
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
			
			var sliderKey = key,
					sliderValue = value,
					updateSlider = true;
	    
	    var self = this;

	    switch( key ) {
		    case 'items':
					updateSlider = false;
					this.updateItems( value );
					break;
		    case 'perPage':
		    case 'paging':
					updateSlider = false;
					this.settings[key] = value;
		    	this.updateItems( this.settings.items );
		    	break;
		    case 'spacing':
				this.settings[key] = value;
					this.$elmt.find( '.ditty-item' ).each( function() {
						$( this ).css( { paddingBottom: self.settings.spacing + 'px' } );
					});
					this.$elmt.find( '.ditty-list__page' ).each( function() {
						$( this ).children( '.ditty-item:last-child' ).css( { paddingBottom: 0 } );
					});
					break;
		    case 'itemTextColor':
				case 'itemBgColor':
				case 'itemBorderColor':
				case 'itemBorderStyle':
				case 'itemBorderWidth':
		    case 'itemBorderRadius':
				case 'itemPadding':
					this.settings[key] = value;
		    	this.$elmt.find( '.ditty-item' ).each( function() {
			    	self._styleItem( $( this ) );
		    	} );
		    	break;
				default:
					this.settings[key] = value;
					break;
	    }
			
	    // Convert page to slide
			if ( updateSlider ) {
		    sliderKey = sliderKey.replace( 'page', 'slide' );
		    this.$elmt.ditty_slider( 'options', sliderKey, sliderValue );
			}
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
	    this._destroySlider();
	    this.$elmt.removeClass( 'ditty ditty-list' );
	    this.$elmt.removeAttr( 'data-id' );
      this.$elmt.removeAttr( 'data-type' );
      this.$elmt.removeAttr( 'data-display' );
      this.$elmt.removeAttr( 'style' );
      this.$elmt.empty();
	    this.elmt._ditty_list = null;
    }
  };

  $.fn.ditty_list = function( options ) {
    var args = arguments,
        error = false,
        returns;

    if ( options === undefined || typeof options === 'object' ) {
      return this.each( function () {
        if ( ! this._ditty_list ) {
        	this._ditty_list = new Ditty_List( this, options );
        }
      });
    } else if ( typeof options === 'string' ) {
      this.each( function () {
        var instance = this._ditty_list;

        if ( ! instance ) {
          throw new Error( 'No Ditty_List applied to this element.' );
        }
        if ( typeof instance[options] === 'function' && options[0] !== '_' ) {
          returns = instance[options].apply( instance, [].slice.call( args, 1 ) );
        } else {
          error = true;
        }
      } );

      if ( error ) {
        throw new Error( 'No method "' + options + '" in Ditty_List.' );
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_list = {};
  $.ditty_list.defaults = defaults;

} )( jQuery );
