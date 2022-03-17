/* global jQuery:true */

jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

		"use strict";
		
		$( '#menu-posts-ditty .wp-submenu li' ).each( function() {
			var $item = $( this ),
					$link = $item.children( 'a' );
					
			if ( $link.length ) {
				var href = $link[0].href,
						parts = href.split( '=' ),
						lastPart = parts[parts.length-1];
				
				$item.addClass( lastPart );
			}
		} );

	}() );
	
} );