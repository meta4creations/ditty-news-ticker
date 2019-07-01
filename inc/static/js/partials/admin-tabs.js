jQuery( document ).ready( function($) {

	// Setup strict mode
	(function() {

    "use strict";
		
		/**
		 * Variation hover
		 * @since   3.0
		 */
		$('.dnt-tab__panel__variation').hover(
		  function () {
			  var $label = $(this).children('.dnt-tab__panel__variation__label');
			  $label.css('display', 'flex');
		    $label.stop().animate( {
		    	marginLeft: 0,
		    	opacity: 1
		    }, 500, 'easeOutQuint', function() {
		    	// Animation complete.
		    });
		  }, 
		  function () {
			  var $label = $(this).children('.dnt-tab__panel__variation__label');
		    $label.stop().animate( {
		    	marginLeft: '-100%',
		    	opacity: 0
		    }, 500, 'easeOutQuint', function() {
		    	$label.css('display', 'none');
		    });
		  }
		);
		
		
		/**
		 * Setup the variations
		 * @since   3.0
		 */
		function activate_variation( $link ) {
			
			if( !$link.hasClass('active') ) {
				
				// Reset the tabs and variations
				$('.dnt-tab__panel__variation').removeClass('active');
				$('.dnt-tab__panel__input--variation').hide();
				
				// Activate the current tab
				$link.addClass('active');
				
				// Activate the variation
				var $panel = $link.parents( '.dnt-tab__panel' );
				var $variation = $panel.find( '.dnt-tab__panel__input--variation[data-id="'+$link.data('variation')+'"]' );
				$variation.show();
				
				// Refresh codemirror
				//var cm = $variation.find('.dnt-layout-structure-textarea').next('.CodeMirror')[0].CodeMirror;
				//cm.refresh();
			}
		}
		$('.dnt-tab__panel__variation').click( function(e) {
			e.preventDefault();
			activate_variation( $(this) );
		});
		
		
		/**
		 * Setup the tabs
		 * @since   3.0
		 */
		function activate_tab( $tab ) {
			
			var $container = $tab.parents( '.dnt-tabs' );
			var $panel = $container.find( '.dnt-tab__panel[data-id="'+$tab.data('panel')+'"]' );
			
			if( $tab.hasClass('active') ) {
				
				// Deactivate the current tab
				if( 'toggle' === $container.data('tab-type') ) {
					$tab.removeClass('active');
					$panel.hide();	
				}
				
			} else {
				
				// Reset the tabs and panels
				$('.dnt-tab').removeClass('active');
				$('.dnt-tab__panel').hide();
				
				// Activate the current tab
				$tab.addClass('active');
				$panel.show();
				
				// Actiave variations, if any
				if( $panel.find('.dnt-tab__panel__variations').length ) {
					if( $panel.find('.dnt-tab__panel__variation.active').length ) {
						activate_variation( $panel.find('.dnt-tab__panel__variation.active') );
					} else {
						activate_variation( $panel.find('.dnt-tab__panel__variation:first-child') );
					}
				}
			}
		}
		$('.dnt-tab').click( function(e) {
			e.preventDefault();
			activate_tab( $(this) );	
		});
		if( $('.dnt-tab').length ) {
			//activate_tab( $('.dnt-tab:first-child').children('a') );
		}

	}());

});