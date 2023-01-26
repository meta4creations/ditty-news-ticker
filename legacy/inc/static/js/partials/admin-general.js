jQuery( function($) {
	
	var $settings_select = $('#mtphr-dnt-settings-select'),
			$admin_bar = $('#wpadminbar');
			
			
	/* --------------------------------------------------------- */
	/* !Affix the menu bar - 2.0.1 */
	/* --------------------------------------------------------- */
	
	$settings_select.mtphr_dnt_affix({
	  offset: {
	    top: function () {
		    if( $(window).width() < 601 ) {
			    return (this.top = $settings_select.offset().top + 5);
		    } else {
			    return (this.top = $settings_select.offset().top - $admin_bar.height() + 5);
		    }
	    }
	  }
	});
	
	
	/* --------------------------------------------------------- */
	/* !Main settings selects - 2.0.0 */
	/* --------------------------------------------------------- */
	
	$('#mtphr-dnt-metabox-group-toggles').on( 'click', '.mtphr-dnt-metabox-group-toggle', function(e) {
		e.preventDefault();
		
		var value = $(this).attr('href');
		
		$('.mtphr-dnt-metabox-group-toggle').removeClass('active');
		$('.mtphr-dnt-metabox-group').removeClass('active');
		
		$(this).addClass('active');
		$(value).addClass('active');
		
		// Set the input data
		$('input[name="_mtphr_dnt_admin_tab"]').val( value );
	});

	
	/* --------------------------------------------------------- */
	/* !Initiate the CodeMirror fields - 1.4.0 */
	/* --------------------------------------------------------- */

	$('.mtphr-dnt-codemirror-css').each( function() {

		var $textarea = $(this).children('textarea');
		CodeMirror.fromTextArea($textarea[0], {
			'mode' : 'css',
			'lineNumbers' : true,
			'lineWrapping' : true,
			'viewportMargin' : Infinity
		});
		//myCodeMirror.setSize( false, false );
	});

	$('.mtphr-dnt-codemirror-js').each( function() {

		var $textarea = $(this).children('textarea');
		var myCodeMirror = CodeMirror.fromTextArea($textarea[0], {
			'mode' : 'htmlmixed',
			'lineNumbers' : true,
			'lineWrapping' : true
		});
		myCodeMirror.setSize( false, 140 );
	});	
	
	
	/* --------------------------------------------------------- */
	/* !Ensure javascript is working - 2.0.0 */
	/* --------------------------------------------------------- */
	
	if( $('input[name="_mtphr_dnt_admin_javascript"]').length ) {
		$('input[name="_mtphr_dnt_admin_javascript"]').val('ok');
	}



	/* --------------------------------------------------------- */
	/* !Metabox toggles - 2.0.0 */
	/* --------------------------------------------------------- */
	
	function mtphr_dnt_toggle_metaboxes( $button, kind ) {

		// Set the metaboxes
		$('#mtphr-dnt-'+kind+'-metaboxes > div').stop(true, true).hide();
		var metaboxes = $button.attr('metabox').split(' ');
		for( var i=0; i < metaboxes.length; i++ ) {
			$('#'+metaboxes[i]).show();
		}

		// Set the button classes
		$button.siblings('a').removeClass('button-primary');
		$button.addClass('button-primary');
		
		// Store the new value
		$button.siblings('input').val($button.attr('href').substring(1));
	}
	
	if( $('.mtphr-dnt-type-toggle.button-primary').length ) {
		mtphr_dnt_toggle_metaboxes( $('.mtphr-dnt-type-toggle.button-primary'), 'type' );
	}
	
	if( $('.mtphr-dnt-mode-toggle.button-primary').length ) {
		mtphr_dnt_toggle_metaboxes( $('.mtphr-dnt-mode-toggle.button-primary'), 'mode' );
	}
	
	$('#mtphr-dnt-type-select').on( 'click', '.mtphr-dnt-type-toggle', function(e) {
		
		e.preventDefault();
		if( !$(this).hasClass('button-primary') ) {
			mtphr_dnt_toggle_metaboxes( $(this), 'type' );
		}
	});
	
	$('#mtphr-dnt-mode-select').on( 'click', '.mtphr-dnt-mode-toggle', function(e) {
		
		e.preventDefault();
		if( !$(this).hasClass('button-primary') ) {
			mtphr_dnt_toggle_metaboxes( $(this), 'mode' );
		}
	});
	
	
	
	/* --------------------------------------------------------- */
	/* !Sort list - 1.4.4 */
	/* --------------------------------------------------------- */
	
	if( $('.mtphr-dnt-sort').length > 0 ) {
		
		$('.mtphr-dnt-sort').sortable( {
			handle: '.mtphr-dnt-sort-heading',
			items: '.mtphr-dnt-sort-item',
			axis: 'y',
			opacity: 0.7,
		  placeholder: {
        element: function(currentItem) {
	        var height = $(currentItem).innerHeight();
	        return $('<div class="mtphr-dnt-sort-placeholder" style="height:'+height+'px;"></div>')[0];
        },
        update: function() {
          return;
        }
    	},
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
		
		$('body').on( 'click', '.mtphr-dnt-sort-heading.optional', function(e) {
			e.preventDefault();
			
			var $container = $(this).parents('.mtphr-dnt-sort-item'),
					$content = $(this).next('.mtphr-dnt-sort-item-fields'),
					$input = $(this).children('input');
					
			if( $container.hasClass('active') ) {
				$container.removeClass('active');
				$input.val('off');
				if( $content.length ) {
					$content.stop(true,true).slideUp( 1000, 'easeOutQuint' );
				}
			} else {
				$container.addClass('active');
				$input.val('on');
				if( $content.length ) {
					$content.stop(true,true).slideDown( 1000, 'easeOutQuint' );
				}
			}
		});
	}
	
	
	/* --------------------------------------------------------- */
	/* !List - 2.0.0 */
	/* --------------------------------------------------------- */
	
	$('.mtphr-dnt-list').mtphr_dnt_list();

	
	/* --------------------------------------------------------- */
	/* !Single image upload - 2.0.0 */
	/* --------------------------------------------------------- */

	// Delete an image
	$('body').on( 'click', '.mtphr-dnt-single-image .mtphr-dnt-delete', function(e) {
		e.preventDefault();

		var $image = $(this).parent(),
				$button = $image.siblings('.mtphr-dnt-single-image-upload'),
				$input = $image.siblings('input');

		$input.val('');
		$image.remove();
		$button.show();
	});

	// Add an image
	$('body').on( 'click', '.mtphr-dnt-single-image-upload', function(e) {
	  e.preventDefault();

	  // Save the container
	  var $button = $(this),
	  		$container = $button.parent(),
	  		$input = $container.children('input');

	  // Create a custom uploader
	  var uploader;
	  if( uploader ) {
	    uploader.open();
	    return;
	  }

	  // Set the uploader attributes
	  uploader = wp.media({
	    title: ditty_news_ticker_vars.img_title,
	    button: { text: ditty_news_ticker_vars.img_button, size: 'small' },
	    multiple: false,
	    library : {
	    	type : 'image'
    	}
	  });

	  uploader.on( 'select', function() {

			var attachments = uploader.state().get('selection').toJSON();
			if( attachments.length > 0 ) {

				$input.val(attachments[0].id);

				// Create the display
				var data = {
					action: 'mtphr_dnt_single_image_ajax',
					attachment: attachments[0],
					security: ditty_news_ticker_vars.security
				};
				jQuery.post( ajaxurl, data, function( response ) {
					$button.hide();
					$container.append( response );
				});
			}
	  });

	  //Open the uploader dialog
	  uploader.open();

	  return false;
	});
	
	
	/* --------------------------------------------------------- */
	/* !Tool tips - 2.0.0 */
	/* --------------------------------------------------------- */

	// Setup protip
	$.protip( {
		defaults: {
			position: 'top',
			size: 'small',
			scheme: 'black',
			classes: 'ditty-protip',
		}
	} );
	
		
		
	/* --------------------------------------------------------- */
	/* !Mixed tick list - 2.0.0 */
	/* --------------------------------------------------------- */
	
	function mtphr_dnt_mixed_ticks_all( $field ) {
		
		var checked = $field.find('input').is(':checked');
		if( checked ) {
			$field.next().fadeOut();
		} else {
			$field.next().fadeIn();
		}
	}
	
	$('body').on( 'click', '.mtphr-dnt-list-field-mtphr_dnt_mixed_ticks_all input', function() {
		mtphr_dnt_mixed_ticks_all( $(this).parents('.mtphr-dnt-list-field-mtphr_dnt_mixed_ticks_all') );
	});
	
	$('.mtphr-dnt-list-field-mtphr_dnt_mixed_ticks_all').each( function() {
		mtphr_dnt_mixed_ticks_all( $(this) );
	});
	
	
	/* --------------------------------------------------------- */
	/* !First tick on init */
	/* --------------------------------------------------------- */
	
	function mtphr_dnt_init_tick( $field ) {
		if ( $field.is( ':checked' ) ) {
			$( '.mtphr-dnt-field-mtphr_dnt_scroll_init_delay' ).show();
		} else {
			$( '.mtphr-dnt-field-mtphr_dnt_scroll_init_delay' ).hide();
		}
	}
	
	$('body').on( 'click', 'input[name="_mtphr_dnt_scroll_init"]', function() {
		mtphr_dnt_init_tick( $(this) );
	});
	
	if ( $( 'input[name="_mtphr_dnt_scroll_init"]').length ) {
		mtphr_dnt_init_tick( $( 'input[name="_mtphr_dnt_scroll_init"]') );
	} 

});



/* --------------------------------------------------------- */
/* !List */
/* --------------------------------------------------------- */

( function($) {

	var methods = {

		init : function( options ) {

			return this.each( function(){

				// Create default options
				var settings = {
				};

				// Add any set options
				if (options) {
					$.extend(settings, options);
				}

				var $table = $(this);
				// Setup protip
				$.protip( {
					defaults: {
						position: 'top',
						size: 'small',
						scheme: 'black',
						classes: 'ditty-protip',
					}
				} );
				
				$table.sortable( {
					handle: '.mtphr-dnt-list-heading > .dashicons-menu',
					items: '.mtphr-dnt-list-item',
					axis: 'y',
					opacity: 0.7,
				  placeholder: {
		        element: function(currentItem) {
			        var height = $(currentItem).innerHeight();
			        return $('<div class="mtphr-dnt-sort-placeholder" style="height:'+height+'px;"></div>')[0];
		        },
		        update: function() {
		          return;
		        }
		    	},
				  helper: function(e, tr) {
				    var $originals = tr.children();
				    var $helper = tr.clone();
				    $helper.children().each(function(index) {
				      $(this).width($originals.eq(index).width());
				      $(this).height($originals.eq(index).height());
				    });
				    return $helper;
				  },
				  start: function( e, ui ) {
					  var $item = $( ui.item );
					  //$item.parents( '.mtphr-dnt-field-mtphr_dnt_ticks' ).find( '.mtphr-dnt-list-item-contents' ).hide();
					  if ( $item.find('.wp-editor-container').length ) {
						  var id = $item.find( '.wp-editor-area' ).attr('id');
						  tinyMCE.execCommand( 'mceRemoveEditor', true, id );
					  } 
					},
				  stop: function( e, ui ) {
					  var $item = $( ui.item );
					  //$item.parents( '.mtphr-dnt-field-mtphr_dnt_ticks' ).find( '.mtphr-dnt-list-item-contents' ).show();
					  if ( $item.find('.wp-editor-container').length ) {
						  var id = $item.find( '.wp-editor-area' ).attr('id');
						  tinyMCE.execCommand( 'mceAddEditor', true, id );
					  }  
				  },
				});

				function mtphr_dnt_list_handle_toggle() {
				
					if( $table.find('.mtphr-dnt-list-item').length > 1 ) {
						$table.find('.mtphr-dnt-list-handle').show();
						$table.find('.mtphr-dnt-list-delete').show();
					} else {
						$table.find('.mtphr-dnt-list-handle').hide();
						$table.find('.mtphr-dnt-list-delete').hide();
					}
				}
			
				function mtphr_dnt_list_set_order() {
					
					$table.find('.mtphr-dnt-list-item').each( function(index) {	
						$(this).find('textarea, input, select').each( function() {
						
							var name, key;
						
							if( $(this).hasClass('wp-editor-area') ) {
							
								var $parent = $(this).parents('.mtphr-dnt-field-type-wysiwyg');
								name = $parent.attr('data-name');
								key = $parent.attr('data-key');
								
							} else {
							
								name = $(this).attr('data-name');
								key = $(this).attr('data-key');
							}
							
							if( name && key ) {
								$(this).attr('name', name+'['+index+']['+key+']');
							}
						});
					});
					
					mtphr_dnt_list_handle_toggle();
				}
				
				function mtphr_dnt_list_add_item( $item, unique_class ) {
					
					// Save the container
				  var $dup = $item.clone();
				  		
				  // Reset the duplicate
				  $dup.find('textarea, input, select').each( function() {
				  	if( $(this).attr('type') === 'checkbox' || $(this).attr('type') === 'radio' ) {
					  	$(this).attr('checked', false);
				  	} else {
					  	$(this).val('');
				  	}
				  });
				  
				  // Setup new wysiwyg editors
				  $dup.find('textarea').each( function() {
				  	if( $(this).hasClass('wp-editor-area') ) {
					  	
					  	var	$parent = $(this).parents('.mtphr-dnt-field-type-wysiwyg'),
					  			name = $parent.attr('data-name');
					  			
					  	$parent.children('.wp-core-ui').remove();
					  			
					  	// Create the display
							var data = {
								action: 'mtphr_dnt_wysiwyg_ajax',
								name: name,
								security: ditty_news_ticker_vars.security
							};
							jQuery.post( ajaxurl, data, function( response ) {
								
								var $editor = $(response),
										id = $editor.find('textarea').attr('id');

								$parent.append( response );
								
								if( typeof(tinyMCE) === 'object' && typeof(tinyMCE.execCommand) === 'function' ) {
									tinyMCE.execCommand("mceAddEditor", false, id);
						    }
						    
						    // Set the order
								mtphr_dnt_list_set_order();	    
							});

				  	}
				  });

				  // Add the duplicate
				  $dup.hide();
				  $item.after( $dup );
				  $dup.fadeIn().addClass(unique_class);

				  // Setup protip
					$.protip( {
						defaults: {
							position: 'top',
							size: 'small',
							scheme: 'black',
							classes: 'ditty-protip',
						}
					} );
				  
				  // Set the order
				  mtphr_dnt_list_set_order();
				  
				  // Trigger an added event
				  $table.trigger('mtphr_dnt_list_item_added', [$dup, unique_class]);
				}

				// Delete list item
				$table.on( 'click', '.mtphr-dnt-list-delete', function(e) {
					e.preventDefault();
		
					// Fade out the item
					$(this).parents('.mtphr-dnt-list-item').fadeOut( function() {
						$(this).remove();
						mtphr_dnt_list_set_order();
					});
				});
				
				// Add new row
				$table.on( 'click', '.mtphr-dnt-list-add', function(e) {
				  e.preventDefault();
					mtphr_dnt_list_add_item( $(this).parents('.mtphr-dnt-list-item') );  
				});
				
				
				$table.on('mtphr_dnt_list_add_item', function( e, item, unique_class ) {
					mtphr_dnt_list_add_item( item, unique_class ); 
				});
				
				mtphr_dnt_list_set_order();
				
			});
		}
	};

	/**
	 * Setup the list
	 *
	 * @since 1.0.0
	 */
	$.fn.mtphr_dnt_list = function( method ) {

		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call(arguments, 1) );
		} else if ( typeof method === 'object' || !method ) {
			return methods.init.apply( this, arguments );
		} else {
			throw new Error( 'Method ' +  method + ' does not exist in mtphr_dnt_list' );
		}
	};

})( jQuery );