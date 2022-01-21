jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setup( $field ) {
			$field.addClass( 'ditty-input--image--init' );
			
			var $input = $field.find( 'input[type="hidden"]' ),
					$preview = $field.find( '.ditty-input--image__preview' ),
					$preview_img = $preview.children( 'img' ),
					uploader;
			
			$field.on( 'click', '.ditty-input--image__upload', function( e ) {
				e.preventDefault();

				if ( undefined === uploader ) {
					uploader = wp.media({
						title: $input.data( 'media_title' ),
						button: { text: $input.data( 'media_button' ), size: 'small' },
						multiple: $input.data( 'multiple' ),
						library : {
							type : 'image'
						}
					} );
				}

				uploader.on( 'open', function() {
					var selection = uploader.state().get( 'selection' );
					var attachment = wp.media.attachment( $input.val() );
					if ( attachment ) {
						selection.add( attachment );
					}
					// let ids = [13, 14, 56];
					// ids.forEach(function(id) {
					// 	let attachment = wp.media.attachment(id);
					// 	selection.add(attachment ? [attachment] : []);
					// } );
				} );
				
				uploader.on( 'select', function() {
					var attachments = uploader.state().get( 'selection' ).toJSON(),
							image_data = [];
							
					if ( attachments.length > 0 ) {
						$(attachments).each( function() {	
							image_data.push( {
								id 					: $(this)[0].id,
								title				: $(this)[0].title,
								caption			: $(this)[0].caption,
								description	: $(this)[0].description,
								link				: $(this)[0].link,
								url					: $(this)[0].sizes.medium ? $(this)[0].sizes.medium.url : $(this)[0].sizes.full.url
							} );
						} );
					}
					
					$input.val( image_data[0].id );
					if ( $preview_img.length ) {
						$preview_img.remove();
					}
					$preview_img = $( '<img src="' + image_data[0].url + '" alt="" />' );
					$preview.prepend( $preview_img );
					$preview.find( 'i' ).remove();
					
					$field.trigger( 'ditty_field_image_select', [$field, image_data] );
					$field.trigger( 'ditty_field_update' );
				} );
				
				uploader.open();
				return false;
				
			} );
			
		}

    function init( e ) {
			$( e.target ).find( '.ditty-input--image:not(.ditty-input--image--init)' ).each( function() {
				setup( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );