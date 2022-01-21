/* global _:true */

jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setup( $field ) {
			$field.addClass( 'ditty-input--code--init' );
			
			var $textarea = $field.find( 'textarea' ),
					codeEditor = null,
					codeEditorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
					
	    codeEditorSettings.codemirror = _.extend(
	      {},
	      codeEditorSettings.codemirror,
	      {
		      mode				: $textarea.data( 'mode' ) ? $textarea.data( 'mode' ) : null,
	        indentUnit	: 2,
	        tabSize			: 2
	      }
	    );
	    codeEditor = wp.codeEditor.initialize( $textarea[0], codeEditorSettings );
		}

    function init( e ) {
			$( e.target ).find( '.ditty-input--code:not(.ditty-input--code--init)' ).each( function() {
				setup( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );