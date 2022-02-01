/* global dittyVars:true */

jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setMinHeight( $form ) {
			var val = $form.find( 'input[name="direction"]:checked' ).val(),
					$minHeight = $form.find( '.ditty-field--minHeight' ),
					$maxHeight = $form.find( '.ditty-field--maxHeight' ),
					$minHeightInput = $form.find( 'input[name="minHeight"]' );
					
			if ( 'down' === val || 'up' === val ) {
				$minHeight.show();
				$maxHeight.show();
				if ( '' === $minHeightInput.val() ) {
					var dittyEditor = $form.parents( '#ditty-editor__settings' )[0],
							defaultValue = '300px';
							
					$minHeightInput.val( defaultValue );
					dittyEditor._ditty_editor.ditty.options( 'minHeight', defaultValue );
				}
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
				var $form = editorPanel.$form;
				
				// Set minHeight
				setMinHeight( $form );
				$form.find( 'input[name="direction"]' ).on( 'click', function() {
					setMinHeight( $form );
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