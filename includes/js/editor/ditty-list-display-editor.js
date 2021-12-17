/* global dittyVars:true */

jQuery( document ).ready( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";
		
		function modifyItemsPerPage( $perPage, pagingValue ) {
			if ( 0 === parseInt( pagingValue ) ) {
				$perPage.hide();
			} else {
				$perPage.show();
			}
		}
		
		function modifyArrows( arrowSiblings, arrowsValue ) {
			if ( 'none' === arrowsValue ) {
				arrowSiblings.hide();
			} else {
				arrowSiblings.show();
			}
		}
		
		function modifyBullets( bulletSiblings, bulletsValue ) {
			if ( 'none' === bulletsValue ) {
				bulletSiblings.hide();
			} else {
				bulletSiblings.show();
			}
		}

		$( '#ditty-editor' ).on( 'ditty_display_editor_panel_init', '.ditty-editor__panel--displayEditor', function( e, editorPanel ) {  
			if ( 'list' === editorPanel.displayType ) {
				var $form = editorPanel.$form,
						$perPage = $form.find( '.ditty-field--perPage' ),
						arrowSiblings = $form.find( '.ditty-field--arrows' ).siblings(),
						bulletSiblings = $form.find( '.ditty-field--bullets' ).siblings();
				
				// Set paging
				modifyItemsPerPage( $perPage, $form.find( 'input[name="paging"]:checked' ).val() );
				$form.find( 'input[name="paging"]' ).on( 'change', function() {
					modifyItemsPerPage( $perPage, $( this ).val() );
				} );
				
				// Set arrows
				modifyArrows( arrowSiblings, $form.find( 'select[name="arrows"]' ).val() );
				$form.find( 'select[name="arrows"]' ).on( 'change', function() {
					modifyArrows( arrowSiblings, $( this ).val() );
				} );
				
				// Set bullets
				modifyBullets( bulletSiblings, $form.find( 'select[name="bullets"]' ).val() );
				$form.find( 'select[name="bullets"]' ).on( 'change', function() {
					modifyBullets( bulletSiblings, $( this ).val() );
				} );
			}
		} );   

	}() );
	
} );