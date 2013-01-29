/**
 * Ditty News Ticker
 * Date: 1/15/2013
 *
 * @author Metaphor Creations
 * @version 1.0.0
 *
 **/

( function($) {
	
	var methods = {
	
		init : function( options ) {

			return this.each( function(){

				// Create default options
				var settings = {
					type									: 'scroll',
					scroll_direction			: 'left',
					scroll_speed					: 10,
					scroll_pause					: 0,
					scroll_spacing				: 40,
					scroll_units					: 1,
					rotate_type						: 'fade',
					auto_rotate						: 0,
					rotate_delay					: 10,
					rotate_pause					: 0,
					rotate_speed					: 10,
					rotate_ease						: 'easeOutExpo',
					nav_reverse						: 0,
					before_change					: function(){},
					after_change					: function(){},
					after_load						: function(){}
				};
				
				// Useful variables. Play carefully.
        var vars = {
	        tick_count			: 0,
	        current_tick		: 0,
	        reverse					: 0,
	        running					: 0
        };
				
				// Add any set options
				if (options) { 
					$.extend(settings, options);
				}

				// Create variables
				var $ticker = $(this).find('.mtphr-dnt-tick-container'),
					$nav_prev = $ticker.find('.mtphr-dnt-nav-prev'),
					$nav_next = $ticker.find('.mtphr-dnt-nav-next'),
					$nav_controls = $ticker.siblings('.mtphr-dnt-control-links'),
					ticker_width = $ticker.width(),
					ticker_height = 0,
					ticks = [],
					ticker_scroll,
					ticker_delay,
					rotate_adjustment = settings.rotate_type,
					after_change_timeout,
					ticker_pause = false,
					touch_down_x,
					touch_down_y,
					touch_link = '',
					touch_target = '';

				// Add the vars
				$ticker.data('ditty:vars', vars);
				
				// Save the tick count & total
				vars.tick_count = $ticker.find('.mtphr-dnt-tick').length;

				// Start the first tick
				if( vars.tick_count > 0 ) {

					// Setup a ticker scroll
					if( settings.type == 'scroll' ) {
						mtphr_dnt_scroll_setup();
						
					// Setup a ticker rotator
					} else if( settings.type == 'rotate' ) {
						mtphr_dnt_rotator_setup();
					}	
		    }
		    
		    
		    
		    /**
		     * Setup the ticker scroll
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_setup() {
		    	
		    	// Loop through the tick items
					$ticker.find('.mtphr-dnt-tick').each( function(index) {
						
						// Find the greatest tick height
						if( $(this).height() > ticker_height ) {
							ticker_height = $(this).height();
						}
						
						if( settings.scroll_direction == 'up' || settings.scroll_direction == 'down' ) {
							$(this).css('height', 'auto');
						}
					});
					
					// Set the ticker height
					$ticker.css('height',ticker_height+'px');
		    	
		    	// Loop through the tick items
					$ticker.find('.mtphr-dnt-tick').each( function(index) {
	
						// Set the tick position
						var position;
						
						switch( settings.scroll_direction ) {
							case 'left':
								position = ticker_width;
								$(this).css('left',position+'px');
								break;
								
							case 'right':
								position = parseInt('-'+$(this).width());
								$(this).css('left',position+'px');
								break;
								
							case 'up':
								position = parseInt(ticker_height);
								$(this).css('top',position+'px');
								break;
								
							case 'down':
								position = parseInt('-'+$(this).height());;
								$(this).css('top',position+'px');
								break;
						}
						
						// Make sure the ticker is visible
						$(this).show();
						
						// Add the tick data
						var tick = [{'headline':$(this), 'width':$(this).width(), 'height':$(this).height(), 'position':position, 'reset':position, 'visible':false}];
	
						// Add the tick to the array
						ticks.push(tick);
					});

					// Set the first tick visibility
					ticks[vars.current_tick][0].visible = true;
					
					// Start the scroll loop
					mtphr_dnt_scroll_loop();
					
					// Clear the loop on mouse hover
					$ticker.hover(
					  function () {
					  	if( settings.scroll_pause ) {
					    	clearInterval( ticker_scroll );
					    }
					  }, 
					  function () {
					  	if( settings.scroll_pause ) {
					    	mtphr_dnt_scroll_loop();
					    }
					  }
					);
		    }
		    
		    /**
		     * Create the ticker scroll loop
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_loop() {
			    
			    // Start the ticker timer
					ticker_scroll = setInterval( function() {

						for( var i=0; i<vars.tick_count; i++ ) {
	
							if( ticks[i][0].visible == true ) {
								
								var pos = 0;
								
								switch( settings.scroll_direction ) {
									case 'left':
										pos = mtphr_dnt_scroll_left(i);
										ticks[i][0].headline.css('left',pos+'px');
										break;
										
									case 'right':
										pos = mtphr_dnt_scroll_right(i);
										ticks[i][0].headline.css('left',pos+'px');
										break;
										
									case 'up':
										pos = mtphr_dnt_scroll_up(i);
										ticks[i][0].headline.css('top',pos+'px');
										break;
										
									case 'down':
										pos = mtphr_dnt_scroll_down(i);
										ticks[i][0].headline.css('top',pos+'px');
										break;
								}
								
								ticks[i][0].position = pos;
							}
						}
			    }, parseFloat(100/settings.scroll_speed));	
		    }

		    /**
		     * Scroll the ticker left
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_left( i ) {
			    
			    // Find the new position
					var pos = ticks[i][0].position - settings.scroll_units;
					
					// Reset the tick if off the screen
					if( pos < -ticks[i][0].width ) {
						pos = mtphr_dnt_scroll_check_current(i);
					} else if( pos < ticker_width-ticks[i][0].width-settings.scroll_spacing ) {
						mtphr_dnt_scroll_check_next(i);
					}
					
					return pos;
		    }
		    
		    /**
		     * Scroll the ticker right
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_right( i ) {
			    
			    // Find the new position
					var pos = ticks[i][0].position + settings.scroll_units;

					// Reset the tick if off the screen
					if( pos > ticker_width ) {
						pos = mtphr_dnt_scroll_check_current(i);
					} else if( pos > settings.scroll_spacing ) {	
						mtphr_dnt_scroll_check_next(i);
					}
					
					return pos;
		    }
		    
		    /**
		     * Scroll the ticker up
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_up( i ) {
			    
			    // Find the new position
					var pos = ticks[i][0].position - settings.scroll_units;

					// Reset the tick if off the screen
					if( pos < -ticks[i][0].height ) {
						pos = mtphr_dnt_scroll_check_current(i);
					} else if( pos < ticker_height-ticks[i][0].height-settings.scroll_spacing ) {	
						mtphr_dnt_scroll_check_next(i);
					}
					
					return pos;
		    }
		    
		    /**
		     * Scroll the ticker down
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_down( i ) {
			    
			    // Find the new position
					var pos = ticks[i][0].position + settings.scroll_units;

					// Reset the tick if off the screen
					if( pos > ticker_height ) {
						pos = mtphr_dnt_scroll_check_current(i);
					} else if( pos > settings.scroll_spacing ) {	
						mtphr_dnt_scroll_check_next(i);
					}
					
					return pos;
		    }
 
		    /**
		     * Check the current tick position
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_check_current( i ) {
						
					if( vars.tick_count > 1 ) {
						ticks[i][0].visible = false;
					}
					
					return ticks[i][0].reset;
		    }
		    
		    /**
		     * Check the next tick visibility
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_check_next( i ) {
						
					if( i==(vars.tick_count-1) ) {
						ticks[0][0].visible = true;
					} else {
						ticks[(i+1)][0].visible = true;
					}
		    }
		    
		    /**
		     * Resize the scroll ticks
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_scroll_resize_ticks() {

			    for( var i=0; i<vars.tick_count; i++ ) {
				    
				    // Set the tick position
						var position;
						
						var $tick = ticks[i][0].headline;
						
						switch( settings.scroll_direction ) {
							case 'left':
								position = ticker_width;
								if( ticks[i][0].visible == false ) {
									$tick.css('left',position+'px');
								}
								break;
								
							case 'right':
								position = parseInt('-'+$tick.width());
								if( ticks[i][0].visible == false ) {
									$tick.css('left',position+'px');
								}
								break;
								
							case 'up':
								position = parseInt(ticker_height);
								if( ticks[i][0].visible == false ) {
									$tick.css('top',position+'px');
								}
								break;
								
							case 'down':
								position = parseInt('-'+$tick.height());
								if( ticks[i][0].visible == false ) {
									$tick.css('top',position+'px');
								}
								break;
						}
						
						// Adjust the tick data
						ticks[i][0].width = $tick.width();
						ticks[i][0].height = $tick.height();
						if( ticks[i][0].visible == false ) {
							ticks[i][0].position = position;
						}
						ticks[i][0].reset = position;
			    }
		    }
		    
		    
		    
		    
		    /**
		     * Setup the ticker rotator
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_rotator_setup() {

		    	// Loop through the tick items
					$ticker.find('.mtphr-dnt-tick').each( function(index) {
	
						// Add the tick to the array
						ticks.push($(this));
						
					});

					// Resize the ticks
					mtphr_dnt_rotator_resize_ticks();
					
					// Find the rotation type and create the dynamic rotation init function
					var rotate_init_name = 'mtphr_dnt_rotator_'+settings.rotate_type+'_init';
					var mtphr_dnt_rotator_type_init = eval('('+rotate_init_name+')');
					mtphr_dnt_rotator_type_init( $ticker, ticks, parseInt(settings.rotate_speed*100), settings.rotate_ease );
					mtphr_dnt_rotator_update_links( 0 );
					
					// Start the rotator rotate
					if( settings.auto_rotate ) {
						mtphr_dnt_rotator_delay();
					}
					
					// Clear the loop on mouse hover
					$ticker.hover(
					  function () {
					  	if( settings.auto_rotate && settings.rotate_pause ) {
					    	clearInterval( ticker_delay );
					    }
					  }, 
					  function () {
					  	if( settings.auto_rotate && settings.rotate_pause ) {
					    	mtphr_dnt_rotator_delay();
					    }
					  }
					);
		    }
		    
		    /**
		     * Create the ticker rotator loop
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_rotator_delay() {

			    // Start the ticker timer
					ticker_delay = setInterval( function() {

						// Find the new tick
			    	var new_tick = parseInt(vars.current_tick + 1);
						if( new_tick == vars.tick_count ) {
							new_tick = 0;
						}
						
						mtphr_dnt_rotator_update( new_tick );

			    }, parseInt(settings.rotate_delay*1000));	
		    }
		    
		    /**
		     * Create the rotator update call
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_rotator_update( new_tick ) {
		    	
		    	// Clear the interval
		    	if( settings.auto_rotate ) {
			    	clearInterval( ticker_delay );
			    }
		    
		    	// Trigger the before change callback
          settings.before_change.call( this, $ticker );
          
          // Set the running variable
          vars.running = 1;
 
			    // Rotate the current tick out
					mtphr_dnt_rotator_out( new_tick );
					
					// Rotate the new tick in
					mtphr_dnt_rotator_in( new_tick );
					
					// Set the current tick
					vars.current_tick = new_tick;

					// Trigger the after change callback
					after_change_timeout = setTimeout( function() {
					
						settings.after_change.call( this, $ticker );
						
						// Reset the rotator type & variables
						rotate_adjustment = settings.rotate_type;
						vars.reverse = 0;
						vars.running = 0;
						
						// Restart the interval
						if( settings.auto_rotate ) {
				    	mtphr_dnt_rotator_delay();
				    }
						
					}, parseInt(settings.rotate_speed*100) );
		    }
		    
		    /**
		     * Update the control links
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_rotator_update_links( new_tick ) {
			    
			    if( $nav_controls ) {
          	$nav_controls.children('a').removeClass('active');
          	$nav_controls.children('a[href="'+new_tick+'"]').addClass('active');
          }
		    }
		    
		    /**
		     * Create the rotator in function calls
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_rotator_in( new_tick ) {
		    	
		    	// Update the links
		    	mtphr_dnt_rotator_update_links( new_tick );
			    
			    // Find the rotation type and create the dynamic rotation in function
					var rotate_in_name = 'mtphr_dnt_rotator_'+rotate_adjustment+'_in';
					var mtphr_dnt_rotator_type_in = eval('('+rotate_in_name+')');
					mtphr_dnt_rotator_type_in( $ticker, $(ticks[new_tick]), $(ticks[vars.current_tick]), parseInt(settings.rotate_speed*100), settings.rotate_ease );
		    }
		    
		    /**
		     * Create the rotator out function calls
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_rotator_out( new_tick ) {
			    
			    // Find the rotation type and create the dynamic rotation out function
					var rotate_out_name = 'mtphr_dnt_rotator_'+rotate_adjustment+'_out';
					var mtphr_dnt_rotator_type_out = eval('('+rotate_out_name+')');
					mtphr_dnt_rotator_type_out( $ticker, $(ticks[vars.current_tick]), $(ticks[new_tick]), parseInt(settings.rotate_speed*100), settings.rotate_ease );
		    }

		    /**
		     * Resize the rotator ticks
		     *
		     * @since 1.0.0
		     */
		    function mtphr_dnt_rotator_resize_ticks() {

			    for( var i=0; i<vars.tick_count; i++ ) {
				    
				    // Set the width of the tick
				    $(ticks[i]).width( ticker_width+'px' );
			    }
		    }

		    


		    /**
		     * Navigation clicks
		     *
		     * @since 1.0.0
		     */
		    if( $nav_prev && settings.type == 'rotate' ) {
		    
		    	$nav_prev.bind('click', function( e ) {
		    		e.preventDefault();
		    		
		    		if(vars.running) return false;
			    	
			    	// Find the new tick
			    	var new_tick = parseInt(vars.current_tick-1);
						if( new_tick < 0 ) {
							new_tick = vars.tick_count-1;
						}
						if( settings.nav_reverse ) {
							if( settings.rotate_type == 'slide_left' ) {
								rotate_adjustment = 'slide_right';
							} else if( settings.rotate_type == 'slide_right' ) {
								rotate_adjustment = 'slide_left';
							} else if( settings.rotate_type == 'slide_down' ) {
								rotate_adjustment = 'slide_up';
							} else if( settings.rotate_type == 'slide_up' ) {
								rotate_adjustment = 'slide_down';
							}
							vars.reverse = 1;
						}
						mtphr_dnt_rotator_update( new_tick );	
		    	});
		    	
		    	$nav_next.bind('click', function(e) {
		    		e.preventDefault();

		    		if(vars.running) return false;
			    	
			    	// Find the new tick
			    	var new_tick = parseInt(vars.current_tick + 1);
						if( new_tick == vars.tick_count ) {
							new_tick = 0;
						}
						mtphr_dnt_rotator_update( new_tick );	
		    	});
		    }
		    
		    
		    
		    
		    /**
		     * Nav controls
		     *
		     * @since 1.0.2
		     */
		    if( $nav_controls && settings.type == 'rotate' ) {

			    $nav_controls.children('a').bind('click', function( e ) {
		    		e.preventDefault();
		    		
		    		// Find the new tick
			    	var new_tick = parseInt( $(this).attr('href') );
			    	
		    		if(vars.running) return false;
		    		if(new_tick == vars.current_tick) return false;
		    		
			    	var reverse = ( new_tick < vars.current_tick ) ? 1 : 0;
		    		
		    		if( settings.nav_reverse && reverse ) {
							if( settings.rotate_type == 'slide_left' ) {
								rotate_adjustment = 'slide_right';
							} else if( settings.rotate_type == 'slide_right' ) {
								rotate_adjustment = 'slide_left';
							} else if( settings.rotate_type == 'slide_down' ) {
								rotate_adjustment = 'slide_up';
							} else if( settings.rotate_type == 'slide_up' ) {
								rotate_adjustment = 'slide_down';
							}
							vars.reverse = 1;
						}
						mtphr_dnt_rotator_update( new_tick );	
		    	});
		    }
		    
		    
		    
		    
		    /**
		     * Mobile touch support
		     *
		     * @since 1.0.0
		     */
		    if( settings.type == 'rotate' ) {
		    
			    /*
			    $ticker.bind( 'touchstart', function(e) {

						e.preventDefault();
						
						// Save the target
						touch_link = $(e.target).attr('href');
						touch_target = $(e.target).attr('target');;
						
						var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
				    touch_down_x = touch.pageX;
				    touch_down_y = touch.pageY;
					});
					
					$ticker.bind( 'touchend', function(e) {
					
						if(vars.running) return false;
						
						var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
				    touch_up_x = touch.pageX;
				    touch_up_y = touch.pageY;
				
						if( Math.abs(touch_down_x-touch_up_x) > 100 ) {
							if ( touch_down_x-touch_up_x > 0 ) {

					    	// Find the new tick
					    	var new_tick = parseInt(vars.current_tick + 1);
								if( new_tick == vars.tick_count ) {
									new_tick = 0;
								}
								mtphr_dnt_rotator_update( new_tick );
								
							} else {
								
								// Find the new tick
					    	var new_tick = parseInt(vars.current_tick-1);
								if( new_tick < 0 ) {
									new_tick = vars.tick_count-1;
								}
								if( settings.nav_reverse ) {
									if( settings.rotate_type == 'slide_left' ) {
										rotate_adjustment = 'slide_right';
									} else if( settings.rotate_type == 'slide_right' ) {
										rotate_adjustment = 'slide_left';
									} else if( settings.rotate_type == 'slide_down' ) {
										rotate_adjustment = 'slide_up';
									} else if( settings.rotate_type == 'slide_up' ) {
										rotate_adjustment = 'slide_down';
									}
									vars.reverse = 1;
								}
								mtphr_dnt_rotator_update( new_tick );
							}
						} else {
						
							if( touch_link != '' ) {
								if( touch_target == '_blank' ) {
									window.open( touch_link );
								} else {
									window.location( touch_link );
								}
							}
							
							touch_link = '';
							touch_target = '';
						}
					});
					*/
				}
		    
		    
		    
		    
		    /**
		     * Resize listener
		     * Reset the ticker width
		     *
		     * @since 1.0.0
		     */
		    $(window).resize( function() {
			    ticker_width = $ticker.width();
			    
			    if( settings.type == 'scroll' ) {
				    mtphr_dnt_scroll_resize_ticks();
			    } else if( settings.type == 'rotate' ) {
				    mtphr_dnt_rotator_resize_ticks();
			    }
		    });


		    
		    
		    // Trigger the afterLoad callback
        settings.after_load.call(this, $ticker);

			});
		}
	};
	




	/**
	 * Setup the class
	 *
	 * @since 1.0.0
	 */
	$.fn.ditty_news_ticker = function( method ) {
		
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call(arguments, 1) );
		} else if ( typeof method === 'object' || !method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist in ditty_news_ticker' );
		}
	};
		
})( jQuery );