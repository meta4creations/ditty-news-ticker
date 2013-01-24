/* Table of Contents

* Lists
* Code
* Metabox toggle

*/




jQuery(document).ready( function($) {


	

	/**
	 * Add list functionality.
	 *
	 * @since 1.0.0
	 */
	if( $('.mtphr-dnt-metaboxer-list').length > 0 ) {
		mtphr_dnt_metaboxer_lists();
	}
	function mtphr_dnt_metaboxer_lists() {
	
		// Loop through all lists to initialize
		$('.mtphr-dnt-metaboxer-list').each( function(index) {

			// Set the field order
			mtphr_dnt_metaboxer_lists_set_order( $(this) );
			
			// Add sorting to the items
			$(this).sortable( {
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
		});
		
		// Set the list item order
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
		
		// Add item click
		$('.mtphr-dnt-metaboxer-list-item-add').children('a').click( function(e) {
			e.preventDefault();
			
			// Create a new item with blank content
			var $parent = $(this).parents('.mtphr-dnt-metaboxer-list-item');
			var $new = $parent.clone(true).hide();
			$new.find('input,textarea,select').removeAttr('value').removeAttr('checked').removeAttr('selected');
			$parent.after($new);
			$new.fadeIn();
			
			// Set the field order
			mtphr_dnt_metaboxer_lists_set_order( $(this).parents('.mtphr-dnt-metaboxer-list') );
			
			// Show the handles
			$(this).parents('.mtphr-dnt-metaboxer-list').find('.mtphr-dnt-metaboxer-list-item-handle,.mtphr-dnt-metaboxer-list-item-delete').show();
			
			// Set the focus to the new input
			var inputs = $new.find('input,textarea,select');
			$(inputs[0]).focus();
		});
		
		// Delete item click
		$('.mtphr-dnt-metaboxer-list-item-delete').children('a').click( function(e) {
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
	}
	
	
	
	
	/**
	 * Add list functionality.
	 *
	 * @since 1.0.0
	 */
	if( $('.mtphr-dnt-metaboxer-sort').length > 0 ) {
		mtphr_dnt_metaboxer_sorts();
	}
	function mtphr_dnt_metaboxer_sorts() {
	
		// Loop through all sorts to initialize
		$('.mtphr-dnt-metaboxer-sort').each( function(index) {

			// Set the field order
			//mtphr_dnt_metaboxer_sorts_set_order( $(this) );
			
			// Add sorting to the items
			$(this).sortable( {
				handle: '.mtphr-dnt-metaboxer-sort-item-handle',
				items: '.mtphr-dnt-metaboxer-sort-item',
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
					//mtphr_dnt_metaboxer_sorts_set_order( $(this) );
				}
			});
		});
		
		// Set the list item order
		/*
function mtphr_dnt_metaboxer_sorts_set_order( $sort ) {
	
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
*/
	}
	
	
	
	
	/**
	 * Add code functionality.
	 *
	 * @since 1.0.0
	 */
	if( $('.mtphr-dnt-metaboxer-code').length > 0 ) {
		mtphr_dnt_metaboxer_codes();
	}
	function mtphr_dnt_metaboxer_codes() {
		
		// Select the code on button click
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
	}
	
	
	
	
	/**
	 * Add metabox toggle functionality.
	 *
	 * @since 1.0.0
	 */
	if( $('.mtphr-dnt-metaboxer-field-metabox_toggle').length > 0 ) {
		mtphr_dnt_metaboxer_metabox_toggles();
	}
	function mtphr_dnt_metaboxer_metabox_toggles() {
	
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
	}





});