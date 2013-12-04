jQuery( document ).ready( function($) {
		
		
	/* --------------------------------------------------------- */
	/* !Mixed tick list */
	/* --------------------------------------------------------- */
	
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
					console.log($(this).attr('key'));
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
		
		// Add videos
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
			jQuery.post( ajaxurl, data, function( response ) {

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

});