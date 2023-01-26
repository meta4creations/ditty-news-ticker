/* global tinyMCEPreInit:true */
/* global tinymce:true */
/* global quicktags:true */
/* global QTags:true */
/* global wp:true */

jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";
    
    /**
		 * Transform textarea into wysiwyg editor.
		 */
		function transform( $field ) {

			$field.addClass( 'ditty-input--wysiwyg--init' );
			if ( $field.hasClass( 'ditty-input--clone--clone' ) ) {
				var ranId = Math.floor( ( Math.random() * 100000000 ) + 1 );
				$field.find( 'textarea' ).attr( 'id', 'ditty-input--' + ranId );
			}
			
			var $wrapper = $field.find( '.wp-editor-wrap' ),
					id = $field.find( 'textarea' ).attr( 'id' ),
					editor;
					
			// Ignore existing editor.
			if ( tinyMCEPreInit.mceInit[id] ) {
				editor = tinymce.get( id );
				editor.on( 'keyup change', function() {
					tinymce.triggerSave();
					$field.trigger( 'ditty_input_wysiwyg_update' );
				} );
				return;
			}
	
			var originalId = getOriginalId( $field ),
					settings = getEditorSettings( originalId );

			updateDom( $wrapper, id );

			// TinyMCE
			if ( window.tinymce ) {
				tinymce.execCommand( 'mceRemoveEditor', true, id );
				editor = new tinymce.Editor( id, settings.tinymce, tinymce.EditorManager );
				editor.render();
				editor.on( 'keyup change', function() {
					tinymce.triggerSave();
					$field.trigger( 'ditty_input_wysiwyg_update' );
				} );
			}
	
			// Quick tags
			if ( window.quicktags ) {
				settings.quicktags.id = id;
				quicktags( settings.quicktags );
				QTags._buttonsInit();
			}
		}
		
		function getOriginalId( $clone ) {
			var $original = $clone.siblings( '.ditty-input--wysiwyg.ditty-input--clone--orig' ),
					origingalID = $original.find( 'textarea' ).attr( 'id' );

			if ( /_\d+$/.test( origingalID ) ) {
				origingalID = origingalID.replace( /_\d+$/, '' );
			}
			if ( tinyMCEPreInit.mceInit.hasOwnProperty( origingalID ) || tinyMCEPreInit.qtInit.hasOwnProperty( origingalID ) ) {
				return origingalID;
			}
			return '';
		}

		function updateDom( $wrapper, id ) {
			// Wrapper div and media buttons
			$wrapper.attr( 'id', 'wp-' + id + '-wrap' )
			        .find( '.mce-container' ).remove().end()               // Remove rendered tinyMCE editor
			        .find( '.wp-editor-tools' ).attr( 'id', 'wp-' + id + '-editor-tools' )
			        .find( '.wp-media-buttons' ).attr( 'id', 'wp-' + id + '-media-buttons' )
			        .find( 'button' ).data( 'editor', id ).attr( 'data-editor', id );
	
			// Set default active mode.
			$wrapper.removeClass( 'html-active tmce-active' );
			$wrapper.addClass( window.tinymce ? 'tmce-active' : 'html-active' );
	
			// Editor tabs
			$wrapper.find( '.switch-tmce' )
			        .attr( 'id', id + 'tmce' )
			        .data( 'wp-editor-id', id ).attr( 'data-wp-editor-id', id ).end()
			        .find( '.switch-html' )
			        .attr( 'id', id + 'html' )
			        .data( 'wp-editor-id', id ).attr( 'data-wp-editor-id', id );
	
			// Quick tags
			$wrapper.find( '.wp-editor-container' ).attr( 'id', 'wp-' + id + '-editor-container' )
			        .find( '.quicktags-toolbar' ).attr( 'id', 'qt_' + id + '_toolbar' ).html( '' );
		}
		
		function getEditorSettings( id ) {		
			var settings = getDefaultEditorSettings();
			if ( id && tinyMCEPreInit.mceInit.hasOwnProperty( id ) ) {
				settings.tinymce = tinyMCEPreInit.mceInit[id];
			}
			if ( id && window.quicktags && tinyMCEPreInit.qtInit.hasOwnProperty( id ) ) {
				settings.quicktags = tinyMCEPreInit.qtInit[id];
			}
			return settings;
		}

		function getDefaultEditorSettings() {
			var settings = wp.editor.getDefaultSettings();	
			settings.tinymce.toolbar1 = 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv';
			settings.tinymce.toolbar2 = 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help';
			settings.quicktags.buttons = 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close';	
			return settings;
		}
		
		function pre_save( e ) {
			if ( window.tinymce && $( e.target ).find( '.ditty-input--wysiwyg--init .wp-editor-area' ).length ) {
				tinymce.triggerSave();
			}
		}
    $( document ).on( 'ditty_pre_save_fields', pre_save );
    
    function init( e ) {
			$( e.target ).find( '.ditty-input--wysiwyg:not(.ditty-input--wysiwyg--init)' ).each( function() {
				transform( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );