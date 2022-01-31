
jQuery( function( $ ) {	
	// Setup strict mode
	(function() {

    "use strict";
		
		var dittyInitialize = {};
		
		/**
		 * Ditty title
		 *
		 * @since    3.1
		*/
		$( '.ditty-initialize-setting--title input[name="ditty_title"]' ).on( 'keyup', function() {
			var $setting = $( this ).parents( '.ditty-initialize-setting' ),
					val = $( this ).val();
					
			if ( '' !== val ) {
				$setting.addClass( 'complete' );
				
				// Show the layout settings
				$( '.ditty-initialize-setting--item-type').css( 'display', 'flex' );
			}
			dittyInitialize.title = val;
		} );
		
		/**
		 * Item Type
		 *
		 * @since    3.1
		*/
		$( '.ditty-initialize-setting--item-type .ditty-option-grid__item' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-initialize-setting' ),
					slug = $( this ).data( 'value' );

			if ( ! $( this ).hasClass( 'active' ) ) {

				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );

				// Reset & show item type settings
				$( '.ditty-initialize-setting--item-type-settings' ).removeClass( 'complete' );
				$( '.ditty-initialize-setting--item-type-settings .ditty-option-submit' ).addClass( 'ditty-button--primary' );
				$( '.ditty-initialize-setting--item-type-settings').css( 'display', 'flex' );
				
				// Display the correct item type settings group
				$( '.ditty-item-type-settings__group' ).removeClass( 'active' ).hide();
				$( '.ditty-item-type-settings__group[data-id="' + slug + '"]' ).addClass( 'active' ).show();
				$( '.ditty-item-type-settings__group[data-id="' + slug + '"]' ).trigger( 'ditty_init_fields' );
				
				// Reset the layout settings
				$( '.ditty-initialize-setting--layout' ).removeClass( 'complete' );
				$( '.ditty-initialize-setting--layout .ditty-option-grid__item' ).removeClass( 'active' );
				$( '.ditty-initialize-setting--layout').hide();
				
				// Display the correct layout variation settings group
				$( '.ditty-initialize-setting--layout__variation' ).removeClass( 'complete' ).removeClass( 'active' ).hide();
				$( '.ditty-initialize-setting--layout__variation.' + slug ).addClass( 'active' ).show();
				
				// Reset the display settings
				$( '.ditty-initialize-setting--display' ).removeClass( 'complete' );
				$( '.ditty-initialize-setting--display .ditty-option-grid__item' ).removeClass( 'active' );
				$( '.ditty-initialize-setting--display').hide();
				
				// Reset the submit settings
				$( '.ditty-initialize-setting--submit').hide();
				
				// Add to the initialize object
				dittyInitialize.itemType = $( this ).data( 'value' );
				
				// Animate to the next setting
				if ( ! $setting.hasClass( 'complete' ) ) {
					$setting.addClass( 'complete' );
					$( [document.documentElement, document.body] ).stop().animate( {
						scrollTop: $( '.ditty-initialize-setting--item-type-settings' ).offset().top
					}, 700 );
				}
			}
		} );
		
		/**
		 * Item Type Settings Submit
		 *
		 * @since    3.1
		*/
		$( '.ditty-initialize-setting--item-type-settings .ditty-option-submit' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-initialize-setting' ),
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
			$( '.ditty-initialize-setting--layout').css( 'display', 'flex' );
			
			// Add to the initialize object
			dittyInitialize.itemTypeValues = values;
			
			// Animate to the next setting
			if ( ! $setting.hasClass( 'complete' ) ) {
				$setting.addClass( 'complete' );
				$( [document.documentElement, document.body] ).stop().animate( {
					scrollTop: $( '.ditty-initialize-setting--layout' ).offset().top
				}, 700 );
			}
		} );
		
		/**
		 * Variation Layout
		 *
		 * @since    3.1
		*/
		$( '.ditty-initialize-setting--layout .ditty-option-grid__item' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-initialize-setting' ),
					$variations = $( this ).parents( '.ditty-initialize-setting--layout__variation' );
		
			if ( ! $( this ).hasClass( 'active' ) ) {		
				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );
				$variations.addClass( 'complete' );
				
				// Check for all variations complete
				if ( $( '.ditty-initialize-setting--layout__variation.active' ).length === $( '.ditty-initialize-setting--layout__variation.complete' ).length ) {
					var layoutVariations = {};
					$( '.ditty-initialize-setting--layout__variation.complete' ).each( function() {
						layoutVariations[$( this ).data( 'id' )] = $( this ).find( '.ditty-option-grid__item.active' ).data( 'value' );
					} );
					
					// Show the display settings
					$( '.ditty-initialize-setting--display').css( 'display', 'flex' );
					
					dittyInitialize.layoutVariations = layoutVariations;
					
					// Animate to the next setting
					if ( ! $setting.hasClass( 'complete' ) ) {
						$setting.addClass( 'complete' );
						$( [document.documentElement, document.body] ).stop().animate( {
							scrollTop: $( '.ditty-initialize-setting--display' ).offset().top
						}, 700 );
					}
				}
			}
		} );
		
		/**
		 * Display
		 *
		 * @since    3.1
		*/
		$( '.ditty-initialize-setting--display .ditty-option-grid__item' ).on( 'click', function( e ) {
			e.preventDefault();
			var $setting = $( this ).parents( '.ditty-initialize-setting' ),
					id = $( this ).data( 'value' );
		
			if ( ! $( this ).hasClass( 'active' ) ) {		
				$( this ).siblings().removeClass( 'active' );
				$( this ).addClass( 'active' );
				
				// Show the submit settings
				$( '.ditty-initialize-setting--submit').css( 'display', 'flex' );
				
				dittyInitialize.display = id;
				
				// Animate to the next setting
				if ( ! $setting.hasClass( 'complete' ) ) {
					$setting.addClass( 'complete' );
					$( [document.documentElement, document.body] ).stop().animate( {
						scrollTop: $( '.ditty-initialize-setting--submit' ).offset().top
					}, 700 );
				}
			}
		} );
		
		/**
		 * Submit the wizard
		 *
		 * @since    3.1
		*/
		$( '#ditty-wizard-submit' ).on( 'click', function( e ) {
			e.preventDefault();
			
			var dittyId = $( this ).data( 'ditty_id' );
			
			$( '#ditty-initialize-overlay' ).addClass( 'active' );
			
			//$( this ).text( $( this ).data( 'submitting' ) );
			
			var data = {
				action				: 'ditty_submit_wizard',
				ditty_id			: dittyId,
				init_values 	: dittyInitialize,
				security			: dittyVars.security
			};
			$.post( dittyVars.ajaxurl, data, function( response ) {
				window.history.pushState( null, '', '/wp-admin/post.php?post=' + dittyId + '&action=edit' );
				location.reload();
			}, 'json' );

		} );
		
	}() );
	
} );