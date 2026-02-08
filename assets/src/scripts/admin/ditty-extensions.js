
jQuery( function( $ ) {	
	// Setup strict mode
	(function() {

    "use strict";
    
    // Setup protip
		$.protip( {
			defaults: {
				position: 'top',
				size: 'small',
				scheme: 'black',
				classes: 'ditty-protip'
			}
		} );

		/**
		 * Listen for an accordion toggle click
		 *
		 * @since    3.0
		 * @return   null
		*/
		// $( '#ditty-extensions' ).on( 'click', '.ditty-accordion__toggle', function( e ) {
		// 	e.preventDefault();
		// 	var $accordion = $( this ).parent(),
		// 			$content = $( this ).next();
		// 			
		// 	if ( $accordion.hasClass( 'active' ) ) {
		// 		$accordion.removeClass( 'active' );
		// 		$content.stop().slideUp( { duration: 750, easing: "easeInOutQuint" } );
		// 	} else {
		// 		$accordion.addClass( 'active' );
		// 		$content.stop().slideDown( { duration: 750, easing: "easeInOutQuint" }, function() {
		// 			$content.css( 'height', 'auto' );
		// 		} );
		// 	}
		// } );
		// 
    /**
		 * Initialize the extensions
		 *
		 * @since    3.0
		 * @return   null
		*/
		function ditty_extensions_init() {
			$( '#ditty-extensions' ).find( '.ditty-extension' ).each( function( index ) {	

				var $extension = $( this ),
						$panels = $extension.find( '.ditty-extension__panels' );
				
				if ( $panels.length ) {
					$extension.ditty_extension();
				}
				
				$( '#ditty-extensions' ).trigger( 'ditty_init_fields' );
				
				setTimeout( function() {
					$extension.addClass( 'ditty-extension--init' );
				}, index * 250 );
						
			} );
		}
		ditty_extensions_init();

	}() );
	
} );