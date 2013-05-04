jQuery(document).ready( function($) {



/**
 * Add code functionality.
 *
 * @since 1.0.0
 */
$('.mtphr-dnt-metaboxer-code-select').click( function(e) {
	e.preventDefault();

	var $pre = $(this).parents('.mtphr-dnt-metaboxer-code').find('pre');
	var refNode = $pre[0];
	if ( jQuery.browser.msie ) {
		var range = document.body.createTextRange();
		range.moveToElementText( refNode );
		range.select();
	} else if ( jQuery.browser.mozilla || jQuery.browser.opera ) {
		var selection = window.getSelection();
		var range = document.createRange();
		range.selectNodeContents( refNode );
		selection.removeAllRanges();
		selection.addRange( range );
	} else if ( jQuery.browser.safari || jQuery.browser.chrome ) {
		var selection = window.getSelection();
		selection.setBaseAndExtent( refNode, 0, refNode, 1 );
	}
});



/**
 * Add file functionality.
 *
 * @since 1.0.0
 */
$('.mtphr-dnt-metaboxer-file').each( function(index) {

	// If there currently isn't a value, show the upload button
	if( $(this).find('.mtphr-dnt-metaboxer-file-value').val() == '' ) {
		$(this).find('.mtphr-dnt-metaboxer-file-upload').css('display','inline-block');
	}
});

// Custom media upload functionality
$('.mtphr-dnt-metaboxer-file-upload').click(function() {

	// Save the container
	var $container = $(this).parent('.mtphr-dnt-metaboxer-file-contents');

  var send_attachment_bkp = wp.media.editor.send.attachment;

  wp.media.editor.send.attachment = function( props, attachment ) {

  	// Set the field value
  	$container.find('.mtphr-dnt-metaboxer-file-value').val(attachment.id);

  	// Create the display
		var data = {
			action: 'mtphr_dnt_metaboxer_ajax_file_display',
			id: attachment.id,
			type: attachment.type,
			url: attachment.url,
			title: attachment.title,
			caption: attachment.caption,
			description: attachment.description,
			security: mtphr_dnt_metaboxer_vars.security
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post( ajaxurl, data, function( response ) {

			// Append the new data
			$container.append( response );

			// Hide the upload button
			$container.find('.mtphr-dnt-metaboxer-file-upload').hide();
		});

    wp.media.editor.send.attachment = send_attachment_bkp;
  }

  wp.media.editor.open();

  return false;
});

$('.mtphr-dnt-metaboxer-file-delete').live('click',function() {

	// Save the container
	var $container = $(this).parents('.mtphr-dnt-metaboxer-file-contents');

	// Remove the field value
  $container.find('.mtphr-dnt-metaboxer-file-value').val('');

	// Remove the current display
	$container.find('.mtphr-dnt-metaboxer-file-table').remove();

	// Disply the upload button
	$container.find('.mtphr-dnt-metaboxer-file-upload').css('display','inline-block');
});



/**
 * Add image functionality.
 *
 * @since 1.0.0
 */
$('.mtphr-dnt-metaboxer-image').each( function(index) {

	// If there currently isn't a value, show the upload button
	if( $(this).find('.mtphr-dnt-metaboxer-image-value').val() == '' ) {
		$(this).find('.mtphr-dnt-metaboxer-image-upload').css('display','inline-block');
	}
});

// Image upload functionality
$('.mtphr-dnt-metaboxer-image-upload').click(function() {

	// Save the container
	var $container = $(this).parent('.mtphr-dnt-metaboxer-image-contents');

  var send_attachment_bkp = wp.media.editor.send.attachment;

  wp.media.editor.send.attachment = function( props, attachment ) {

  	// Set the field value
  	$container.find('.mtphr-dnt-metaboxer-image-value').val(attachment.id);

  	// Create the display
		var data = {
			action: 'mtphr_dnt_metaboxer_ajax_image_display',
			id: attachment.id,
			type: attachment.type,
			url: attachment.url,
			title: attachment.title,
			caption: attachment.caption,
			description: attachment.description,
			security: mtphr_dnt_metaboxer_vars.security
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post( ajaxurl, data, function( response ) {

			// Append the new data
			$container.append( response );

			// Hide the upload button
			$container.find('.mtphr-dnt-metaboxer-image-upload').hide();
		});

    wp.media.editor.send.attachment = send_attachment_bkp;
  }

  wp.media.editor.open();

  return false;
});

$('.mtphr-dnt-metaboxer-image-delete').live('click',function() {

	// Save the container
	var $container = $(this).parents('.mtphr-dnt-metaboxer-image-contents');

	// Remove the field value
  $container.find('.mtphr-dnt-metaboxer-image-value').val('');

	// Remove the current display
	$container.find('.mtphr-dnt-metaboxer-image-table').remove();

	// Disply the upload button
	$container.find('.mtphr-dnt-metaboxer-image-upload').css('display','inline-block');
});




/**
 * Add image select functionality.
 *
 * @since 1.0
 */
$('.mtphr-dnt-metaboxer-image-select-link').click( function(e) {

	e.preventDefault();

	// Get the value
	var val = $(this).attr('href');

	// Save the value to the input
	$(this).siblings('input[type="hidden"]').val(val);

	// Set & remove selected
	$(this).siblings('.mtphr-dnt-metaboxer-image-select-link').removeClass('selected');
	$(this).addClass('selected');
});



/**
 * Add list functionality.
 *
 * @since 1.0.0
 */
$('.mtphr-dnt-metaboxer-list').each( function(index) {

	// Set the field order
	mtphr_dnt_metaboxer_lists_set_order( $(this) );

	// Add sorting to the items
	mtphr_dnt_metaboxer_lists_set_sortable( $(this) );
});

// List - add sorting to the items
function mtphr_dnt_metaboxer_lists_set_sortable( $list ) {

	$list.sortable( {
		handle: '.mtphr-dnt-metaboxer-list-item-handle',
		items: '.mtphr-dnt-metaboxer-list-item',
		axis: 'y',
		helper: function(e, tr) {
	    var $originals = tr.children();
	    var $helper = tr.clone();
	    $helper.children().each(function(index) {
	      // Set helper cell sizes to match the original sizes
	      $(this).width($originals.eq(index).width())
	    });
	    return $helper;
	  },
	  update: function( event, ui ) {

			// Set the field order
			mtphr_dnt_metaboxer_lists_set_order( $(this) );
		}
	});
}

// List - set the list item order
function mtphr_dnt_metaboxer_lists_set_order( $list ) {

	// Set the order of the items
	$list.find('.mtphr-dnt-metaboxer-list-item').each( function(i) {

		$(this).find('.mtphr-dnt-metaboxer-list-structure-item').each( function(e) {

			var base = $(this).attr('base');
			var field = $(this).attr('field');
			$(this).find('input,textarea,select').attr('name', base+'['+i+']['+field+']');
		});
	});

	// Hide the delete if only one element
	if( $list.find('.mtphr-dnt-metaboxer-list-item').length == 1 ) {

		$list.find('.mtphr-dnt-metaboxer-list-item-handle,.mtphr-dnt-metaboxer-list-item-delete').hide();
	}
}

// List - add item click
$('.mtphr-dnt-metaboxer-list-item-add').live( 'click', function(e) {
	e.preventDefault();

	// Create a new item with blank content
	var $parent = $(this).parents('.mtphr-dnt-metaboxer-list-item');
	var $new = $parent.clone(true).hide();

	if( $new.find('.wp-editor-wrap').length > 0 ) {
		var $container = $new.find('.wp-editor-wrap').parent();
		$new.find('.wp-editor-wrap').remove();
		$container.append('<div class="mtphr-dnt-metaboxer-wysiwyg-placeholder">You must <strong>save your page</strong> to use this editor.</div>');
	}

	$new.find('input,textarea,select').removeAttr('value').removeAttr('checked').removeAttr('selected');
	$parent.after($new);
	$new.fadeIn().css('display', 'table-row');

	// Set the field order
	mtphr_dnt_metaboxer_lists_set_order( $(this).parents('.mtphr-dnt-metaboxer-list') );

	// Show the handles
	$(this).parents('.mtphr-dnt-metaboxer-list').find('.mtphr-dnt-metaboxer-list-item-handle,.mtphr-dnt-metaboxer-list-item-delete').show();

	// Set the focus to the new input
	var inputs = $new.find('input,textarea,select');
	$(inputs[0]).focus();
});

// List - delete item click
$('.mtphr-dnt-metaboxer-list-item-delete').live( 'click', function(e) {
	e.preventDefault();

	// Fade out the item
	$(this).parents('.mtphr-dnt-metaboxer-list-item').fadeOut( function() {

		// Get the list
		var $list = $(this).parents('.mtphr-dnt-metaboxer-list');

		// Remove the item
		$(this).remove();

		// Set the field order
		mtphr_dnt_metaboxer_lists_set_order( $list );
	});
});

// List -  handle hover
$('.mtphr-dnt-metaboxer-list-item-handle').live( 'hover', function() {
	mtphr_dnt_metaboxer_lists_set_sortable( $(this).parents('.mtphr-dnt-metaboxer-list') );
});



/**
 * Add metabox toggle functionality.
 *
 * @since 1.0.0
 */
$('.mtphr-dnt-metaboxer-field-metabox_toggle').each( function(index) {

	// Create an array to store all the toggled metaboxes
	var metaboxes = Array();
	$(this).find('.mtphr-dnt-metaboxer-metabox-toggle').each( function(index) {

		// Get the metaboxes and merge into the main array
		var m = $(this).attr('metaboxes').split(',');
		$.merge( metaboxes, m );
	});
	var total_metaboxes = metaboxes.length;

	// Hide the toggled metaboxes
	mtphr_dnt_metaboxer_metabox_hide();

	// Display the current metaboxes
	if( $(this).find('.mtphr-dnt-metaboxer-metabox-toggle.button-primary').length > 0 ) {
		$init_button = $(this).find('.mtphr-dnt-metaboxer-metabox-toggle.button-primary');
	} else {
		$init_button = $(this).find('.mtphr-dnt-metaboxer-metabox-toggle:first');
		$init_button.addClass('button-primary');
	}
	mtphr_dnt_metaboxer_metabox_show( $init_button );

	// Hide the toggled metaboxes
	function mtphr_dnt_metaboxer_metabox_hide() {
		for( var i=0; i<total_metaboxes; i++ ) {
			$('#'+metaboxes[i]).hide();
			$('input[name="'+metaboxes[i]+'-hide"]').removeAttr('checked');
		}
	}

	// Show the selected metaboxes
	function mtphr_dnt_metaboxer_metabox_show( $button ) {

		// Get and display the selected metaboxes
		var m = $button.attr('metaboxes').split(',');
		var t = m.length;

		// Show all the toggled metaboxes
		for( var i=0; i<t; i++ ) {
			$('#'+m[i]).show();
			$('input[name="'+m[i]+'-hide"]').attr('checked', 'checked');
		}

		// Store the new value
		$button.siblings('input').val($button.attr('href'));
	}

	// Select the code on button click
	$(this).find('.mtphr-dnt-metaboxer-metabox-toggle').click( function(e) {
		e.preventDefault();

		// Hide all the toggled metaboxes
		mtphr_dnt_metaboxer_metabox_hide();

		// Show the selected metaboxes
		mtphr_dnt_metaboxer_metabox_show( $(this) );

		// Set the button classes
		$(this).siblings('.mtphr-dnt-metaboxer-metabox-toggle').removeClass('button-primary');
		$(this).addClass('button-primary');
	});
});



/**
 * Add sort functionality.
 *
 * @since 1.0.1
 */
$('.mtphr-dnt-metaboxer-sort').each( function(index) {

	// Add sorting to the items
	$(this).sortable( {
		handle: '.mtphr-dnt-metaboxer-sort-item-handle',
		items: '.mtphr-dnt-metaboxer-sort-item',
		axis: 'y',
	  update: function( event, ui ) {
		}
	});
});







});