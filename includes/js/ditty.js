/* global jQuery:true */
/* global dittyVars:true */

// @codekit-append 'partials/helpers.js';

jQuery( document ).ready( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";
    
    var liveIds = {},
    		liveInterval = null;
    
    /**
		 * Listen for ditty live update start triggers
		 *
		 * @since    3.0
		 * @return   null
		*/
/*
    $( 'body' ).on( 'ditty_start_live_updates', function( event, dittyId ) {
	    liveIds[dittyId] = Math.floor( $.now()/1000 );
	    startLiveUpdates();
	  } );
*/
	  
	  /**
		 * Listen for ditty live update stop triggers
		 *
		 * @since    3.0
		 * @return   null
		*/
/*
	  $( 'body' ).on( 'ditty_stop_live_updates', function( event, dittyId ) {
		  var updated_liveIds = {};
		  $.each( liveIds, function( dittyId, timestamp ) {
			  if ( parseInt( dittyId ) !== parseInt( dittyId ) ) {
			  	updated_liveIds[dittyId] = timestamp;
			  }
			} );
			liveIds = updated_liveIds;
			if ( undefined === liveIds.length ) {
				stopLiveUpdates();
			}
	  } );
*/

    /**
		 * Live update a Ditty
		 *
		 * @since    3.0
		 * @return   null
		*/
    function liveUpdate( dittyId, items ) {
	    $( '.ditty[data-id="' + dittyId + '"]' ).each( function() {
		    var displayType = $( this ).data( 'type' );
				if ( 'development' === dittyVars.mode && window.console ) {
					console.log( 'LIVE UPDATE' );
				}
		    $( this )['ditty_' + displayType]( 'options', 'items', items );
		  } );
	  }
	  
	  /**
		 * Get current API IDs
		 *
		 * @since    3.0
		 * @return   null
		*/
    // function getApiIds() {
	  //   var apiIds = {};
	  //   $( '.ditty-item' ).each( function() {
		//     var apiId = $( this ).data( 'api_id' );
		//     if ( apiId ) {
		// 	    apiIds[apiId] = apiId;
		//     }	
		// 	} );
		// 	return apiIds;
	  // }
	  
	  /**
		 * Check for live updates
		 *
		 * @since    3.0
		 * @return   null
		*/
    function checkLiveUpdates() {
	    var data = {
				action		: 'ditty_live_updates',
				live_ids 	: liveIds,
				//api_ids		: getApiIds(),
				security	: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				if ( response.updated_items ) {
					$.each( response.updated_items, function( dittyId, items ) {
						liveUpdate( dittyId, items );
						liveIds[dittyId] = Math.floor( $.now() / 1000 );
					} );
				}	
			}, 'json' );
	  }
	  
    /**
		 * Stop listening for live updates
		 *
		 * @since    3.0
		 * @return   null
		*/
    // function stopLiveUpdates() {
	  //   if ( null !== liveInterval ) {
		//     cancelAnimationFrame( liveInterval );
		//     liveInterval = null;
		//   }
	  // }
	  
	  /**
		 * Start listening for live updates
		 *
		 * @since    3.0
		 * @return   null
		*/
    function startLiveUpdates() {
	    if ( null !== liveInterval ) {
		    return false;
		  }
		  cancelAnimationFrame( liveInterval );
			
			var updateInterval = dittyVars.updateInterval ? parseInt( dittyVars.updateInterval ) : 60,
					startTime = Date.now();
					
			function dittyLiveUpdatesLoop() {
				var currTime = Date.now(),
						passedTime = Math.floor( ( currTime - startTime ) / 1000 );

				if ( passedTime >= updateInterval ) {
					startTime = currTime;
					checkLiveUpdates();
				}
				liveInterval = requestAnimationFrame( dittyLiveUpdatesLoop );
			}	
			liveInterval = requestAnimationFrame( dittyLiveUpdatesLoop );
	  }
	  
	  /**
		 * Update extension API calls
		 *
		 * @since    3.0
		 * @return   null
		*/
    // function updateExtensionApis() {  
		// 	var data = {
		// 		action		: 'ditty_api_background_updates',
		// 		security	: dittyVars.security
		// 	};
		// 	$.post( dittyVars.ajaxurl, data, function() {
		// 	}, 'json' );
	  // }
    
    /**
		 * Setup the global Dittys
		 *
		 * @since    3.0
		 * @return   null
		*/
    function setupGlobalDitty() {
			$.each( dittyVars.globals, function( index, data ) {
				var selector = $( data.selector );
				if ( ! data.ditty || undefined === selector[0] ) {
					return;	
				}
				var $ditty = $( '<div class="ditty" data-id="' + data.ditty + '" data-ajax_load="1"></div>' );			
				if ( data.display && '' !== data.display ) {
					$ditty.attr( 'data-display', data.display );
				}
				if ( data.customId && '' !== data.customId ) {
					$ditty.attr( 'id', data.customId );
				}
				if ( data.customClasses && '' !== data.customClasses ) {
					$ditty.addClass( data.customClasses );
				}
				switch( data.position ) {
					case 'prepend':
						$( selector[0] ).prepend( $ditty );
						break;
					case 'before':
						$( selector[0] ).before( $ditty );
						break;
					case 'after':
						$( selector[0] ).after( $ditty );
						break;
					default:
						$( selector[0] ).append( $ditty );
						break;
				}
			} );
	  }

		/**
		 * Load all the dittys
		 *
		 * @since    3.0
		 * @return   null
		*/
		function dittyInit() {

			// Add the global Dittys
			setupGlobalDitty();

			$( '.ditty' ).each( function() {
				var $ditty 		= $( this ),
						ajax_load = $ditty.data( 'ajax_load' ) 		? $ditty.data( 'ajax_load' ) 		: false,
						editor 		= $ditty.data( 'show_editor' ) 	? $ditty.data( 'show_editor' ) 	: false,
						load_type = $ditty.data( 'load_type' ) 		? $ditty.data( 'load_type' ) 		: false;
					
				// Load the Dittys via ajax	
				if ( ajax_load ) {
					var data = {
						action						: 'ditty_init',
						id								: $ditty.data( 'id' ) ? $ditty.data( 'id' ) : false,
						display						: $ditty.data( 'display' ) ? $ditty.data( 'display' ) : '',
						display_settings	: $ditty.data( 'display_settings' ) ? $ditty.data( 'display_settings' ) : false,
						editor						: editor,
						load_type					: load_type,
						security					: dittyVars.security
					};
					$.post( dittyVars.ajaxurl, data, function( response ) {
						// Make sure the display type exists
						if ( ! response.display_type || ( 'function' !== typeof $ditty['ditty_' + response.display_type] ) ) {
							if ( window.console ) {
								console.log( 'Ditty Display type not loaded:', response.display_type );
							}
							return false;
						}
						
						// Load the ditty
						$ditty['ditty_' + response.display_type]( response.args );
						
						// Add to the liveIds
						if ( ! editor ) {
							liveIds[$ditty.data( 'id' )] = Math.floor( $.now()/1000 );
						}
					}, 'json' );
					
				} else {
					
					if ( ! editor ) {
						liveIds[$ditty.data( 'id' )] = Math.floor( $.now()/1000 );
					}
				}
			} );

			if ( $( '.ditty' ).length && dittyVars.updateInterval ) {
				startLiveUpdates();
			}
		}
		dittyInit();

	}() );
	
} );