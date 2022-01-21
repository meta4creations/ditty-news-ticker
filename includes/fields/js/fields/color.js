jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setup( $field ) {
			$field.addClass( 'ditty-input--color--init' );
			$field.find( 'input[type="text"]' ).minicolors( {
				format: 'rgb',
				opacity: true
			} );
		}

    function init( e ) {
	    $( e.target ).find( '.ditty-input--color:not(.ditty-input--color--init)' ).each( function() {
				setup( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );