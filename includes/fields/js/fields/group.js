jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setup( $field ) {
			$field.addClass( 'ditty-field-type--group--init' );
			
			var $input = $field.children( '.ditty-field__input__container' ).children( '.ditty-input--group' ),
					height = 0;
					
			if ( 'collapsed' === $field.attr( 'data-collapsible' ) ) {
				$input.hide();
			}

			$field.on( 'click', '.ditty-field__collapsible-toggle', function( e ) {
				e.preventDefault();
				if ( 'expanded' === $( this ).parents( '.ditty-field-type--group' ).attr( 'data-collapsible' ) ) {
					$( this ).parents( '.ditty-field-type--group' ).attr( 'data-collapsible', 'collapsed' );
					height = $input.outerHeight();
					$input.stop().animate( {
						marginTop: '-' + height + 'px'
					}, 1000, 'easeInOutQuint', function() {
							$input.hide();
					} );
				} else {
					$( this ).parents( '.ditty-field-type--group' ).attr( 'data-collapsible', 'expanded' );
					height = $input.outerHeight();
					$input.stop().css( 'marginTop', '-' + height + 'px' );
					$input.show();
					$input.stop().animate( {
						marginTop: 0
					}, 1000, 'easeInOutQuint', function() {
					} );
				}
			} );
		}

    function init( e ) {
	    $( e.target ).find( '.ditty-field-type--group[data-collapsible]:not(.ditty-field-type--group--init)' ).each( function() {
				setup( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );