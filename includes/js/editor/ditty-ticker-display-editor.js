/* global dittyVars:true */

jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function modifyHeights( $minHeight, $maxHeight, directionValue ) {
			if ( 'down' === directionValue || 'up' === directionValue ) {
				$minHeight.show();
				$maxHeight.show();
			} else {
				$minHeight.hide();
				$maxHeight.hide();
			}
		}
		
		function setScrollDelay( $form ) {
			var val = $form.find( 'input[name="scrollInit"]:checked' ).val();
			if ( 'filled' === val ) {
				$form.find( '.ditty-field--scrollDelay' ).show();
			} else {
				$form.find( '.ditty-field--scrollDelay' ).hide();
			}
		}

		$( '#ditty-editor' ).on( 'ditty_display_editor_panel_init', '.ditty-editor__panel--displayEditor', function( e, editorPanel ) {  
			if ( 'ticker' === editorPanel.displayType ) {
				var $form = editorPanel.$form,
						$minHeight = $form.find( '.ditty-field--minHeight' ),
						$maxHeight = $form.find( '.ditty-field--maxHeight' );
				
				// Set heights
				modifyHeights( $minHeight, $maxHeight, $form.find( 'input[name="direction"]:checked' ).val() );
				$form.find( 'input[name="direction"]' ).on( 'change', function() {
					modifyHeights( $minHeight, $maxHeight, $( this ).val() );
				} );
				
				// Set scroll delay
				setScrollDelay( $form );
				$form.find( 'input[name="scrollInit"]' ).on( 'click', function() {
					setScrollDelay( $form );
				} );
			}
		} );   

	}() );
	
} );