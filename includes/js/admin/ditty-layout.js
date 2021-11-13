
// jQuery( document ).ready( function( $ ) {	
// 	
// 	// Setup strict mode
// 	(function() {
// 
//     "use strict";
// 		
// 		var currentType = $( 'select[name="_ditty_layout_type"]' ).val();
//     
//     $( 'select[name="_ditty_layout_type"]' ).on( 'change', function() {
// 			if ( '' !== currentType ) {
// 				window.confirm( 'Are you sure you want to do this? Changing the Layout Type may break items associated with it.' );
// 			}
// 		} );
// 		
// 		$( 'select[name="_ditty_layout_code"]' ).on( 'change', function( e ) {
// 			e.preventDefault();
// 			if ( window.confirm( 'Are you sure you want to do this? Selecting a Layout will overwrite the code for this post.' ) ) {
// 				$( this ).val( '' );
// 			} else {
// 				$( this ).val( '' );
// 			}
// 		} );
// 
// 	}() );
// 	
// } );