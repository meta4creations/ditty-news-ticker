jQuery( document ).ready( function($) {
	
/*
	function tick_edit_containers_close() {
		
		$('.dnt-tick-list__edit').each( function(index) {
			
		});
		
	}
*/
	
	$('body').on( 'click', '.dnt-tick-list__element--type', function(e) {
		
		e.preventDefault();
		
		var $edit_container = $(this).siblings('.dnt-tick-list__edit'),
				$edit_contents = $edit_container.children('.dnt-tick-list__edit__contents'),
				edit_content = ditty_news_ticker_vars.edit_types,
				content_h = $edit_contents.height();
	
		if( $(this).hasClass('active') ) {	
			$(this).removeClass('active');
			
			$edit_contents.stop().animate( {
				marginTop: '-'+content_h+'px'
			}, 1000, 'easeInOutQuint', function() {
				$edit_contents.empty();
			});
			
		} else {
			
			$(this).addClass('active');
			
			if( content_h === 0 ) {
				$edit_contents.hide();
				$edit_contents.html( edit_content );
				content_h = $edit_contents.height();
				$edit_contents.stop().css('marginTop', '-'+content_h+'px');
				$edit_contents.show();
			}

			$edit_contents.stop().animate( {
				marginTop: 0
			}, 1000, 'easeInOutQuint', function() {
				// Animation complete.
			});
		}
	});
	
	
	$('body').on( 'click', '.dnt-tick-list__element--template', function(e) {
		
		e.preventDefault();
		
		var $edit_container = $(this).siblings('.dnt-tick-list__edit'),
				$edit_contents = $edit_container.children('.dnt-tick-list__edit__contents'),
				edit_content = ditty_news_ticker_vars.edit_templates,
				content_h = $edit_contents.height();
	
		if( $(this).hasClass('active') ) {		
			$(this).removeClass('active');
			
			$edit_contents.stop().animate( {
				marginTop: '-'+content_h+'px'
			}, 1000, 'easeInOutQuint', function() {
				$edit_contents.empty();
			});
			
		} else {
			
			$(this).addClass('active');
			
			if( content_h === 0 ) {
				$edit_contents.hide();
				$edit_contents.html( edit_content );
				content_h = $edit_contents.height();
				$edit_contents.stop().css('marginTop', '-'+content_h+'px');
				$edit_contents.show();
			}

			$edit_contents.stop().animate( {
				marginTop: 0
			}, 1000, 'easeInOutQuint', function() {
				// Animation complete.
			});
		}
	});
	
	
	$('.dnt-tick-type-option').click( function(e){
		
		e.preventDefault();
		
		var $item = $(this).parents('.dnt-ticks__item'),
				type = $(this).data('type');
		
		var data = {
			action		: 'dnt_admin_initialize_tick_type',
			type			: type,
			security	: ditty_news_ticker_vars.security
		};
    
    $.post( ditty_news_ticker_vars.ajaxurl, data, function( response ) {
			
			if( response ) {
				$item.html( response );
			}

		} );

	});

});
