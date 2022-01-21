jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setup( $field ) {
			var format = $field.find( 'input[type="text"]' ).data( 'dateformat' );
			$field.find( 'input[type="text"]' ).datepicker( {
				dateFormat: format
			} );
		}

    function init( e ) {
			$( e.target ).find( '.ditty-input--date' ).each( function() {
				setup( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );