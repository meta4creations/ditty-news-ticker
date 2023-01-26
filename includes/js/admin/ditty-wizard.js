
jQuery( function( $ ) {	
	// Setup strict mode
	(function() {

    "use strict";
		
		var dittyWizardValues = {};
		
		/**
		 * Ditty title
		 *
		 * @since    3.0.13
		*/
		$( '.ditty-wizard-setting--title input[name="ditty_title"]' ).on( 'keyup', function() {
			var $setting = $( this ).parents( '.ditty-wizard-setting' ),
					val = $( this ).val();
					
			if ( '' !== val ) {
				$setting.addClass( 'complete' );
				
				// Show the layout settings
				$( '.ditty-wizard-setting--item-type').css( 'display', 'flex' );
			}
			dittyWizardValues.title = val;
		} );
		
		/**
		 * Item Type
		 *
		 * @since    3.0.13
		*/
		$( '.ditty-wizard-setting--item-type .ditty-option-grid__item' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-wizard-setting' ),
					slug = $( this ).data( 'value' );

			if ( ! $( this ).hasClass( 'active' ) ) {

				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );

				// Reset & show item type settings
				$( '.ditty-wizard-setting--item-type-settings' ).removeClass( 'complete' );
				$( '.ditty-wizard-setting--item-type-settings .ditty-option-submit' ).addClass( 'ditty-button--primary' );
				$( '.ditty-wizard-setting--item-type-settings').css( 'display', 'flex' );
				
				// Display the correct item type settings group
				var $itemSettings = $( '.ditty-item-type-settings__group[data-id="' + slug + '"]' );
				$( '.ditty-item-type-settings__group' ).removeClass( 'active' ).hide();
				$itemSettings.addClass( 'active' ).show();
				$itemSettings.trigger( 'ditty_init_fields' );
				if ( ! $itemSettings.hasClass( 'init' ) ) {
					$itemSettings.trigger( 'ditty_wizard_init', [slug] );
					$itemSettings.addClass( 'init' );
				}
				
				// Reset the layout settings
				$( '.ditty-wizard-setting--layout' ).removeClass( 'complete' );
				$( '.ditty-wizard-setting--layout .ditty-option-grid__item' ).removeClass( 'active' );
				$( '.ditty-wizard-setting--layout').hide();
				
				// Display the correct layout variation settings group
				$( '.ditty-wizard-setting--layout__variation' ).removeClass( 'complete' ).removeClass( 'active' ).hide();
				$( '.ditty-wizard-setting--layout__variation.' + slug ).addClass( 'active' ).show();
				
				// Reset the display settings
				$( '.ditty-wizard-setting--display' ).removeClass( 'complete' );
				$( '.ditty-wizard-setting--display .ditty-option-grid__item' ).removeClass( 'active' );
				$( '.ditty-wizard-setting--display').hide();
				
				// Reset the submit settings
				$( '.ditty-wizard-setting--submit').hide();
				
				// Add to the initialize object
				dittyWizardValues.itemType = $( this ).data( 'value' );
				
				// Animate to the next setting
				if ( ! $setting.hasClass( 'complete' ) ) {
					$setting.addClass( 'complete' );
					$( [document.documentElement, document.body] ).stop().animate( {
						scrollTop: $( '.ditty-wizard-setting--item-type-settings' ).offset().top
					}, 700 );
				}
			}
		} );
		
		/**
		 * Item Type Settings Submit
		 *
		 * @since    3.0.13
		*/
		$( '.ditty-wizard-setting--item-type-settings .ditty-option-submit' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-wizard-setting' ),
					$settings_group = $setting.find( '.ditty-item-type-settings__group.active' ),
					values = {};
					
			$( this ).removeClass( 'ditty-button--primary' );

			$settings_group.find( ':input' ).each( function() {
				var type = $( this ).prop( 'type' );
		
				// checked radios/checkboxes
				if ( ( type === "checkbox" || type === "radio" ) ) { 
					if (  this.checked ) {
						values[$( this ).attr( 'name' )] = $( this ).val();
					}
				} else if ( type !== "button" && type !== "submit" ) {
					values[$( this ).attr( 'name' )] = $( this ).val();
				}
			} );
			
			// Show the layout settings
			$( '.ditty-wizard-setting--layout').css( 'display', 'flex' );
			
			// Add to the initialize object
			dittyWizardValues.itemTypeValues = values;
			
			// Animate to the next setting
			if ( ! $setting.hasClass( 'complete' ) ) {
				$setting.addClass( 'complete' );
				$( [document.documentElement, document.body] ).stop().animate( {
					scrollTop: $( '.ditty-wizard-setting--layout' ).offset().top
				}, 700 );
			}
		} );
		
		/**
		 * Variation Layout
		 *
		 * @since    3.0.13
		*/
		$( '.ditty-wizard-setting--layout .ditty-option-grid__item' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-wizard-setting' ),
					$variations = $( this ).parents( '.ditty-wizard-setting--layout__variation' );
		
			if ( ! $( this ).hasClass( 'active' ) ) {		
				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );
				$variations.addClass( 'complete' );
				
				// Check for all variations complete
				if ( $( '.ditty-wizard-setting--layout__variation.active' ).length === $( '.ditty-wizard-setting--layout__variation.complete' ).length ) {
					var layoutVariations = {};
					$( '.ditty-wizard-setting--layout__variation.complete' ).each( function() {
						layoutVariations[$( this ).data( 'id' )] = $( this ).find( '.ditty-option-grid__item.active' ).data( 'value' );
					} );
					
					// Show the display settings
					$( '.ditty-wizard-setting--display').css( 'display', 'flex' );
					
					dittyWizardValues.layoutVariations = layoutVariations;
					
					// Animate to the next setting
					if ( ! $setting.hasClass( 'complete' ) ) {
						$setting.addClass( 'complete' );
						$( [document.documentElement, document.body] ).stop().animate( {
							scrollTop: $( '.ditty-wizard-setting--display' ).offset().top
						}, 700 );
					}
				}
			}
		} );
		
		/**
		 * Display
		 *
		 * @since    3.0.13
		*/
		$( '.ditty-wizard-setting--display .ditty-option-grid__item' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-wizard-setting' ),
					id = $( this ).data( 'value' );
		
			if ( ! $( this ).hasClass( 'active' ) ) {		
				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );
				
				// Show the submit settings
				$( '.ditty-wizard-setting--submit').css( 'display', 'flex' );
				
				dittyWizardValues.display = id;
				
				// Animate to the next setting
				if ( ! $setting.hasClass( 'complete' ) ) {
					$setting.addClass( 'complete' );
					$( [document.documentElement, document.body] ).stop().animate( {
						scrollTop: $( '.ditty-wizard-setting--submit' ).offset().top
					}, 700 );
				}
			}
		} );
		
		/**
		 * Submit the wizard
		 *
		 * @since    3.0.13
		*/
		$( '#ditty-wizard-submit' ).on( 'click', function( e ) {
			e.preventDefault();
			
			var dittyId = $( this ).data( 'ditty_id' );
			
			$( '#ditty-wizard-overlay' ).addClass( 'active' );
			
			//$( this ).text( $( this ).data( 'submitting' ) );
			
			var data = {
				action				: 'ditty_submit_wizard',
				ditty_id			: dittyId,
				init_values 	: dittyWizardValues,
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				window.history.pushState( null, '', '/wp-admin/post.php?post=' + dittyId + '&action=edit' );
				location.reload();
			}, 'json' );

		} );
		
	}() );
	
} );