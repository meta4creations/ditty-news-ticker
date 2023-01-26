jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";
		
		function toggle_content_fields( $form ) {
			var val = $form.find( 'input[name="content_display"]:checked' ).val();
			if ( 'full' === val  ) {
				$form.find( '.ditty-field--excerpt_length' ).hide();
				$form.find( '.ditty-field--excerpt_element' ).hide();
				$form.find( '.ditty-field--more' ).hide();
				$form.find( '.ditty-field--more_before' ).hide();
				$form.find( '.ditty-field--more_after' ).hide();
				$form.find( '.ditty-field--more_link' ).hide();
			} else {
				
				$form.find( '.ditty-field--excerpt_length' ).show();
				$form.find( '.ditty-field--excerpt_element' ).show();
				$form.find( '.ditty-field--more' ).show();
				$form.find( '.ditty-field--more_before' ).show();
				$form.find( '.ditty-field--more_after' ).show();
				$form.find( '.ditty-field--more_link' ).show();
			}
		}
		
		$( '#ditty-editor' ).on( 'ditty_item_editor_panel_init', '.ditty-editor__panel--item_editor', function( e, editorPanel ) {  
			if ( 'posts_feed' !== editorPanel.itemType && 'post' !== editorPanel.itemType ) {
				return false;
			}
			var $form = editorPanel.$form;
			toggle_content_fields( $form );
			$form.on( 'click', 'input[name="content_display"]', function() {
				toggle_content_fields( $form );
			} );
		} );

	}() );
	
} );