jQuery( document ).ready( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";

		function setup( $field ) {
			$field.addClass( 'ditty-input--image--init' );
			
			var uploader;
			
			$field.on( 'click', '.ditty-input--image__upload', function( e ) {
				e.preventDefault();

				if ( undefined === uploader ) {
					uploader = wp.media({
						title: 'TEST TITLE',
						button: { text: 'Button Title', size: 'small' },
						multiple: true,
						library : {
							type : 'image'
						}
					} );
				}
				
				uploader.on( 'select', function() {
		
					var attachments = uploader.state().get( 'selection' ).toJSON();
					if ( attachments.length > 0 ) {
						$(attachments).each( function( index ) {
							
							// var id = $(this)[0].id,
							// 		title = $(this)[0].title,
							// 		description = $(this)[0].description,
							// 		link = $(this)[0].link,
							// 		url = $(this)[0].sizes.thumbnail ? $(this)[0].sizes.thumbnail.url : $(this)[0].sizes.full.url;
									
							console.log( $( this ) );
							
							// if( index == 0 ) {
							// 	$list_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_image input').val(id);
							// 	$list_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_title input').val(title);
							// 	$list_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_description textarea').val(description);
							// 	$list_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_link input').val(link);
							// 	$list_item.find('.mtphr-dnt-data-image-preview').html('<img src="'+url+'" /><a href="#" class="mtphr-dnt-data-image-upload"><i class="dashicons dashicons-no"></i></a>');
							// } else {
							// 	$list.trigger('mtphr_dnt_list_add_item', [$list_item, 'new-data-image']);
							// 	
							// 	var $dup_item = $('.new-data-image').first();
							// 	$dup_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_image input').val(id);
							// 	$dup_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_title input').val(title);
							// 	$dup_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_description textarea').val(description);
							// 	$dup_item.find('.mtphr-dnt-field-mtphr_dnt_image_ticks_link input').val(link);
							// 	$dup_item.find('.mtphr-dnt-data-image-preview').html('<img src="'+url+'" /><a href="#" class="mtphr-dnt-data-image-upload"><i class="dashicons dashicons-no"></i></a>');
							// 	$dup_item.removeClass('new-data-image');
							// }
						} );
					}
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