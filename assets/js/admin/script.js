jQuery( document ).ready( function($) {

	
	/* --------------------------------------------------------- */
	/* !Initiate the CodeMirror fields - 1.4.0 */
	/* --------------------------------------------------------- */

	$('.mtphr-dnt-codemirror-css').each( function(i) {

		var $textarea = $(this).children('textarea');
		var myCodeMirror = CodeMirror.fromTextArea($textarea[0], {
			'mode' : 'css',
			'lineNumbers' : true,
			'lineWrapping' : true
		});
		myCodeMirror.setSize( false, 140 );
	});

	$('.mtphr-dnt-codemirror-js').each( function(i) {

		var $textarea = $(this).children('textarea');
		var myCodeMirror = CodeMirror.fromTextArea($textarea[0], {
			'mode' : 'htmlmixed',
			'lineNumbers' : true,
			'lineWrapping' : true
		});
		myCodeMirror.setSize( false, 140 );
	});
	
	
	
	/* --------------------------------------------------------- */
	/* !Code select - 1.4.0 */
	/* --------------------------------------------------------- */

	$('.mtphr-dnt-code-select').click( function(e) {
		e.preventDefault();
	
		var $pre = $(this).siblings('pre');
		var refNode = $pre[0];
		if ( $.browser.msie ) {
			var range = document.body.createTextRange();
			range.moveToElementText( refNode );
			range.select();
		} else if ( $.browser.mozilla || $.browser.opera ) {
			var selection = window.getSelection();
			var range = document.createRange();
			range.selectNodeContents( refNode );
			selection.removeAllRanges();
			selection.addRange( range );
		} else if ( $.browser.safari || $.browser.chrome ) {
			var selection = window.getSelection();
			selection.setBaseAndExtent( refNode, 0, refNode, 1 );
		}
	});



	/* --------------------------------------------------------- */
	/* !Metabox toggle - 1.3.3 */
	/* --------------------------------------------------------- */

	$('.mtphr-dnt-metabox-toggle').each( function(index) {
	
		// Create an array to store all the toggled metaboxes
		var metaboxes = Array();
		$(this).children('a').each( function(index) {
	
			// Get the metaboxes and merge into the main array
			var m = $(this).attr('metaboxes').split(',');
			$.merge( metaboxes, m );
		});
		var total_metaboxes = metaboxes.length;
	
		// Hide the toggled metaboxes
		mtphr_dnt_metabox_hide();
	
		// Display the current metaboxes
		if( $(this).children('a.button-primary').length > 0 ) {
			$init_button = $(this).children('a.button-primary');
		} else {
			$init_button = $(this).children('a:first');
			$init_button.addClass('button-primary');
		}
		mtphr_dnt_metabox_show( $init_button );
	
		// Hide the toggled metaboxes
		function mtphr_dnt_metabox_hide() {
			for( var i=0; i<total_metaboxes; i++ ) {
				$('#'+metaboxes[i]).hide();
				$('input[name="'+metaboxes[i]+'-hide"]').removeAttr('checked');
			}
		}
	
		// Show the selected metaboxes
		function mtphr_dnt_metabox_show( $button ) {
	
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
		$(this).children('a').click( function(e) {
			e.preventDefault();
	
			// Hide all the toggled metaboxes
			mtphr_dnt_metabox_hide();
	
			// Show the selected metaboxes
			mtphr_dnt_metabox_show( $(this) );
	
			// Set the button classes
			$(this).siblings('a').removeClass('button-primary');
			$(this).addClass('button-primary');
		});
	});
	
	
	/* --------------------------------------------------------- */
	/* !Sort list - 1.4.4 */
	/* --------------------------------------------------------- */
	
	if( $('.mtphr-dnt-sort-list').length > 0 ) {
		
		$('.mtphr-dnt-sort-list').sortable( {
			handle: '.mtphr-dnt-list-handle',
			items: '.mtphr-dnt-list-item',
			axis: 'y',
		  helper: function(e, tr) {
		    var $originals = tr.children();
		    var $helper = tr.clone();
		    $helper.children().each(function(index) {
		      $(this).width($originals.eq(index).width());
		      $(this).height($originals.eq(index).height());
		    });
		    return $helper;
		  },
		});
	}
	
	
	/* --------------------------------------------------------- */
	/* !Advanced list - 1.4.5 */
	/* --------------------------------------------------------- */
	
	if( $('.mtphr-dnt-advanced-list').length > 0 ) {

		function mtphr_dnt_advanced_handle_toggle( $table ) {
		
			if( $table.find('.mtphr-dnt-list-item').length > 1 ) {
				$table.find('.mtphr-dnt-list-handle').show();
				$table.find('.mtphr-dnt-list-delete').show();
			} else {
				$table.find('.mtphr-dnt-list-handle').hide();
				$table.find('.mtphr-dnt-list-delete').hide();
			}
		}
	
		function mtphr_dnt_advanced_set_order( $table ) {
			
			$table.find('.mtphr-dnt-list-item').each( function(index) {	
				$(this).find('textarea, input, select').each( function() {
				
					if( $(this).hasClass('mtphr-dnt-wysiwyg') ) {
					
						var $parent = $(this).parents('.mtphr-dnt-wysiwyg-container'),
								name = $parent.attr('data-name'),
								key = $parent.attr('data-key');
						
					} else {
					
						var name = $(this).attr('data-name'),
								key = $(this).attr('data-key');
					}
					
					$(this).attr('name', name+'['+index+']['+key+']');
				});
			});
			
			mtphr_dnt_advanced_handle_toggle( $table );
		}
		
		$('.mtphr-dnt-advanced-list').sortable( {
			handle: '.mtphr-dnt-list-handle',
			items: '.mtphr-dnt-list-item',
			axis: 'y',
		  helper: function(e, tr) {
		    var $originals = tr.children();
		    var $helper = tr.clone();
		    $helper.children().each(function(index) {
		      $(this).width($originals.eq(index).width());
		      $(this).height($originals.eq(index).height());
		    });
		    return $helper;
		  }
		});
		
		// Delete list item
		$('.mtphr-dnt-advanced-list').find('.mtphr-dnt-list-delete').live( 'click', function(e) {
			e.preventDefault();
			
			var $table = $(this).parents('.mtphr-dnt-advanced-list');

			// Fade out the item
			$(this).parents('.mtphr-dnt-list-item').fadeOut( function() {
				$(this).remove();
				mtphr_dnt_advanced_set_order( $table );
			});
		});
		
		// Add new row
		$('.mtphr-dnt-advanced-list').find('.mtphr-dnt-list-add').live( 'click', function(e) {
		  e.preventDefault();

		  // Save the container
		  var $table = $(this).parents('.mtphr-dnt-advanced-list'),
		  		$container = $(this).parents('.mtphr-dnt-list-item'),
		  		$dup = $container.clone();
		  		
		  // Reset the duplicate
		  $dup.find('textarea, input, select').each( function() {
			  $(this).val('');
		  });
		  
		  // Add the duplicate
		  $dup.hide();
		  $container.after( $dup );
		  $dup.fadeIn();
		  
		  // Set the order
		  mtphr_dnt_advanced_set_order( $table );
		});	
		
		$('.mtphr-dnt-advanced-list').each( function(index) {
			mtphr_dnt_advanced_set_order( $(this) );
		});
	}
	
		
		
	/* --------------------------------------------------------- */
	/* !Mixed tick list - 1.3.3 */
	/* --------------------------------------------------------- */
	
	/*
if( $('.mtphr-dnt-mixed-list').length > 0 ) {
	
		function mtphr_dnt_mixed_handle_toggle( $table ) {
			if( $table.find('.mtphr-dnt-list-item').length > 1 ) {
				$table.find('.mtphr-dnt-list-handle').show();
			} else {
				$table.find('.mtphr-dnt-list-handle').hide();
			}
		}
	
		function mtphr_dnt_mixed_set_order( $table ) {
			
			$table.find('.mtphr-dnt-list-item').each( function(index) {	
				$(this).find('input, select').each( function() {
					$(this).attr('name', '_mtphr_dnt_mixed_ticks['+index+']['+$(this).attr('key')+']');
				});
			});
			
			mtphr_dnt_mixed_handle_toggle( $table );
		}

		$('.mtphr-dnt-mixed-list').sortable( {
			handle: '.mtphr-dnt-list-handle',
			items: '.mtphr-dnt-list-item',
			axis: 'y',
		  helper: function(e, tr) {
		    var $originals = tr.children();
		    var $helper = tr.clone();
		    $helper.children().each(function(index) {
		      $(this).width($originals.eq(index).width());
		      $(this).height($originals.eq(index).height());
		    });
		    return $helper;
		  },
		});
		
		// Delete list item
		$('.mtphr-dnt-mixed-list').find('.mtphr-dnt-list-delete').live( 'click', function(e) {
			e.preventDefault();

			// Fade out the item
			$(this).parents('.mtphr-dnt-list-item').fadeOut( function() {
				$(this).remove();
			});
		});
		
		// Add new row
		$('#mtphr-dnt-mixed-add-tick').click( function(e) {
		  e.preventDefault();

		  // Save the container
		  var $container = $(this).siblings('.mtphr-dnt-table').find('.mtphr-dnt-mixed-list'),
		  		$spinner = $(this).next();
		  		
		  $spinner.css('display', 'inline-block');

		  var data = {
				action: 'mtphr_dnt_mixed_list_ajax',
				security: ditty_news_ticker_vars.security
			};
			$.post( ajaxurl, data, function( response ) {

				// Add the audio and adjust the toggles
				$container.append( response );
				mtphr_dnt_mixed_set_order( $container );
				$spinner.fadeOut();
			});
		});	
		
		$('.mtphr-dnt-mixed-list').each( function(index) {
			mtphr_dnt_mixed_set_order( $(this) );
		});
	}
*/

});