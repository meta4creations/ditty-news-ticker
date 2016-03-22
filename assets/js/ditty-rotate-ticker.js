/**
 * Ditty Rotate Ticker
 * Date: 04/21/16
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
					id										: '',
					ticks									: '',
					autoplay							: false,
					speed									: 750,
					delay									: 3000,
					animation							: 'horizontal',
					animateHeight					: true
				};

				// Add any set options
				if (options) {
					$.extend(settings, options);
				}
				
				// Useful variables. Play carefully.
        var vars = {
        	id							: settings.id,
        	current_tick		: 0,
        	total_ticks			: 0,
        };

				// Create variables
				var $ticker = $(this),
						$wrapper = $ticker.find('.mtphr-dnt-wrapper'),
						$contents = $ticker.find('.mtphr-dnt-tick-contents');

				// Add the vars
				$ticker.data('ditty:vars', vars);
				
				vars.total_ticks = settings.ticks.length;
				
				
				/* --------------------------------------------------------- */
				/* !Initialize rotate - 1.0.0 */
				/* --------------------------------------------------------- */
				
				$wrapper.unslider({
					arrows: {
						//  Unslider default behaviour
						prev: '<a class="unslider-arrow prev">Previous slide</a>',
						next: '<a class="unslider-arrow next">Next slide</a>',
					
						//  Example: generate buttons to start/stop the slider autoplaying
						stop: '<a class="unslider-pause" />',
						start: '<a class="unslider-play">Play</a>'
					},
					animation: settings.animation,
					selectors: {
						container: '.mtphr-dnt-tick-contents',
						slides: '.mtphr-dnt-tick'
					},
					animateHeight: settings.animateHeight
				});
				
				
				
				
				
				

			
			/* --------------------------------------------------------- */
			/* !Close each */
			/* --------------------------------------------------------- */

			});
		}
	};





	/**
	 * Setup the class
	 *
	 * @since 1.0.0
	 */
	$.fn.ditty_rotate_ticker = function( method ) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call(arguments, 1) );
		} else if ( typeof method === 'object' || !method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist in ditty_rotate_ticker' );
		}
	};

})( jQuery );