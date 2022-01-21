jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setup( $field ) {
			$field.addClass( 'ditty-input--slider--init' );
			$field.find( 'input[type="text"]' ).ionRangeSlider();
		}

    function init( e ) {
			$( e.target ).find( '.ditty-input--slider:not(.ditty-input--slider--init)' ).each( function() {
				setup( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );