jQuery( function( $ ) {
	
	// Setup strict mode
	(function() {

    "use strict";
    
		function setup( $field ) {

			// Setup protip
			$.protip( {
				defaults: {
					position: 'top',
					size: 'small',
					scheme: 'black',
					classes: 'ditty-protip'
				}
			} );
			
			var $inputContainer = $field.children( '.ditty-field__input__container' );
			
			$field.addClass( 'ditty-field--clone-enabled--init' );
			$field.data( 'input_count', $inputContainer.children( '.ditty-field__input' ).length );

			$inputContainer.children( '.ditty-field__input' ).each( function() {
				setupButtons( $field, $( this ) );
			} );		
			
			$field.find( '.ditty-field__actions__clone' ).on( 'click', function( e ) {
				e.preventDefault();
				var $input = $inputContainer.children( '.ditty-field__input' ).last();
				addInput( $field, $input );
			} );
			
			$inputContainer.sortable( {
				handle: '.ditty-field__input__action--arrange',
				items: '> .ditty-field__input',
				axis: 'y',
				start: function( event, ui ) {
					var $item = $( ui.item );
					$item.addClass( 'ditty-field__input--moving' );
				},
				stop: function( event, ui ) {
					var $item = $( ui.item );
					$item.removeClass( 'ditty-field__input--moving' );
				},
				update: function() {
					updateField( $field );
				}
			} );
		}
		
		/**
		 * Update the field class and input names
		 *
		 * @since    3.0
		 * @return   null
		*/
		function updateField( $field ) {
			var $inputContainer 	= $field.children( '.ditty-field__input__container' ),
					cloneName 				= $field.data( 'clone_name' ),
					cloneMax 					= $field.data( 'clone_max' ),
					fieldData					= [];
			
			$inputContainer.children( '.ditty-field__input' ).each( function( index ) {
				var $input = $( this );
		    $input.find( ':input' ).each( function() {
			    var baseId = $( this ).parents( '.ditty-field__input' ).data( 'baseid' ),
							fieldName;
			    
					if ( baseId ) {
						fieldName = cloneName + '[' + index + '][' + baseId + ']';
				    //$( this ).attr( 'name', cloneName + '[' + index + '][' + baseId + ']' );
			    } else {
						fieldName = cloneName + '[' + index + ']';
				    //$( this ).attr( 'name', cloneName + '[' + index + ']' );
			    }
					$( this ).attr( 'name', fieldName );
					fieldData.push( {
						name	: fieldName,
						value	: $(this).val()
					} );
		    } );
	    } );
	    $field.data( 'input_count', $inputContainer.children( '.ditty-field__input' ).length );
	    
	    if ( cloneMax > 0 && $inputContainer.children( '.ditty-field__input' ).length >= cloneMax ) {
				$field.addClass( 'ditty-field--clone-enabled--max' );
			} else {
				$field.removeClass( 'ditty-field--clone-enabled--max' );
			}
			
			$field.trigger( 'ditty_field_update' );
			$field.trigger( 'ditty_field_clone_update', [fieldData, cloneName] );
    }
    
    /**
		 * Add a new clone input
		 *
		 * @since    3.0
		 * @return   null
		*/
    function addInput( $field, $input, cloneField ) { 	    		
			cloneField = cloneField ? cloneField : $field.data( 'clone_field' );
	    
	    var $clone = $( cloneField );
	    
	    $input.after( $clone );
	    
			updateField( $field );
			setupButtons( $field, $clone );

			$field.trigger( 'ditty_init_fields' );
    }
    
    /**
		 * Setup clone buttons
		 *
		 * @since    3.0
		 * @return   null
		*/
    function setupButtons( $field, $input ) {     
	    var $remove 		= $input.find( '.ditty-field__input__action--remove' ),
	    		$add 				= $input.find( '.ditty-field__input__action--add' ),
	    		$clone 			= $input.find( '.ditty-field__input__action--clone' );

			$remove.on( 'click', function( e ) {
				e.preventDefault();
				$( this ).protipHide();
				if ( 1 === $input.siblings().length ) {
					addInput( $field, $input );
				}
				$input.remove();
				updateField( $field );
				//$( 'body' ).trigger( 'ditty_enable_settings_update' );
			} );
			
			$add.on( 'click', function( e ) {
				e.preventDefault();
				addInput( $field, $input );
				//$( 'body' ).trigger( 'ditty_enable_settings_update' );
			} );
			
			$clone.on( 'click', function( e ) {
				e.preventDefault();
				addInput( $field, $input, $input.clone() );
				//$( 'body' ).trigger( 'ditty_enable_settings_update' );
			} );
    }

    function init( e ) {
			$( e.target ).find( '.ditty-field--clone-enabled:not(.ditty-field--clone-enabled--init)' ).each( function() {
				setup( $( this ) );
			} );
		}
    $( document ).on( 'ditty_init_fields', init );

	}() );
	
} );