<?php
/**
 * Load CSS & jQuery Scripts
 *
 * @package Ditty News Ticker
 */




add_action( 'admin_enqueue_scripts', 'mtphr_dnt_admin_scripts' );
/**
 * Load the metaboxer scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_admin_scripts( $hook ) {

	global $typenow;

	if ( $typenow == 'ditty_news_ticker' ) {
		
		// Load the metaboxer style sheet
		wp_register_style( 'ditty-metaboxer', MTPHR_DNT_URL.'/includes/metaboxer/metaboxer.css', array( 'colors', 'thickbox', 'farbtastic' ), MTPHR_DNT_VERSION );
		wp_enqueue_style( 'ditty-metaboxer' );
	
		// Load the metaboxer jQuery
		wp_register_script( 'ditty-metaboxer', MTPHR_DNT_URL.'/includes/metaboxer/metaboxer.js', array('jquery','media-upload','thickbox','jquery-ui-core','jquery-ui-sortable','jquery-ui-datepicker', 'jquery-ui-slider', 'farbtastic'), MTPHR_DNT_VERSION, true );
		wp_enqueue_script( 'ditty-metaboxer' );
	}
	
	// Load the plugin css
	wp_register_style( 'mtphr-dnt-admin', MTPHR_DNT_URL.'/assets/css/style-admin.css', false, MTPHR_DNT_VERSION );
	wp_enqueue_style( 'mtphr-dnt-admin' );
}




add_action( 'wp_enqueue_scripts', 'mtphr_dnt_scripts' );
/**
 * Load the front end scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_scripts() {
	
	// Load the css
	wp_register_style( 'ditty-news-ticker', MTPHR_DNT_URL.'/assets/css/style.css', false, MTPHR_DNT_VERSION );
	wp_enqueue_style( 'ditty-news-ticker' );
	
	// Load the jQuery easing
	wp_register_script( 'jquery-easing', MTPHR_DNT_URL.'/assets/js/jquery.easing.1.3.js', array('jquery'), MTPHR_DNT_VERSION, true );
	wp_enqueue_script( 'jquery-easing' );
	
	// Load the jQuery
	wp_register_script( 'ditty-news-ticker', MTPHR_DNT_URL.'/assets/js/ditty-news-ticker.js', array('jquery'), MTPHR_DNT_VERSION, true );
	wp_enqueue_script( 'ditty-news-ticker' );
}




add_action( 'mtphr_dnt_after', 'mtphr_dnt_ticker_scripts', 10, 2 );
/**
 * Add the class scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_ticker_scripts( $id, $meta_data ) {

	extract( $meta_data );
	
	// Get the ticker classe
	$ticker = '#mtphr-dnt-'.$id;
	
	// Add a unique id class, if there is one
	if( isset($_mtphr_dnt_unique_id) ) {
		if( $_mtphr_dnt_unique_id != '' ) {
			$ticker = '#mtphr-dnt-'.$id.'-'.sanitize_html_class( $_mtphr_dnt_unique_id );
		}
	}

	if( $_mtphr_dnt_mode == 'scroll' ) {
		$pause = 0;
		if( isset($_mtphr_dnt_scroll_pause) ) {
			$pause = $_mtphr_dnt_scroll_pause ? 1 : 0;
		}
		ob_start(); ?>
		<script>
		jQuery( document ).ready( function($) {
			$( '<?php echo $ticker; ?>' ).ditty_news_ticker({
				type : '<?php echo $_mtphr_dnt_mode; ?>',
				scroll_direction : '<?php echo $_mtphr_dnt_scroll_direction; ?>',
				scroll_speed : <?php echo intval($_mtphr_dnt_scroll_speed); ?>,
				scroll_pause : <?php echo $pause; ?>,
				scroll_spacing : <?php echo intval($_mtphr_dnt_scroll_tick_spacing); ?>
			});
		});
		</script>
		<?php // Echo the compressed scripts
		echo mtphr_dnt_compress_script( ob_get_clean() );
		
	} elseif( $_mtphr_dnt_mode == 'rotate' ) {
		$rotate = 0; $pause = 0; $nav_autohide = 0; $nav_reverse = 0;
		if( isset($_mtphr_dnt_auto_rotate) ) {
			$rotate = $_mtphr_dnt_auto_rotate ? 1 : 0;
		}
		if( isset($_mtphr_dnt_rotate_pause) ) {
			$pause = $_mtphr_dnt_rotate_pause ? 1 : 0;
		}
		if( isset($_mtphr_dnt_rotate_directional_nav_reverse) ) {
			$nav_reverse = $_mtphr_dnt_rotate_directional_nav_reverse ? 1 : 0;
		}
		ob_start(); ?>
		<script>
		jQuery( document ).ready( function($) {
			$( '<?php echo $ticker; ?>' ).ditty_news_ticker({
				type : '<?php echo $_mtphr_dnt_mode; ?>',
				rotate_type : '<?php echo $_mtphr_dnt_rotate_type; ?>',
				auto_rotate : <?php echo $rotate; ?>,
				rotate_delay : <?php echo intval($_mtphr_dnt_rotate_delay); ?>,
				rotate_pause : <?php echo $pause; ?>,
				rotate_speed : <?php echo intval($_mtphr_dnt_rotate_speed); ?>,
				rotate_ease : '<?php echo $_mtphr_dnt_rotate_ease; ?>',
				nav_reverse : <?php echo $nav_reverse; ?>
			});
		});
		</script>
		<?php // Echo the compressed scripts
		echo mtphr_dnt_compress_script( ob_get_clean() );
		
		// Add the rotation in/out scripts to the footer
		if( function_exists('mtphr_dnt_rotate_'.$_mtphr_dnt_rotate_type.'_scripts') ) {
			add_action( 'wp_footer', 'mtphr_dnt_rotate_'.$_mtphr_dnt_rotate_type.'_scripts' );
		}
		
		if( $nav_reverse) {
			
			switch( $_mtphr_dnt_rotate_type ) {
				
				case 'slide_left':
					if( function_exists('mtphr_dnt_rotate_slide_right_scripts') ) {
						add_action( 'wp_footer', 'mtphr_dnt_rotate_slide_right_scripts' );
					}
					break;
					
				case 'slide_right':
					if( function_exists('mtphr_dnt_rotate_slide_left_scripts') ) {
						add_action( 'wp_footer', 'mtphr_dnt_rotate_slide_left_scripts' );
					}
					break;
					
				case 'slide_up':
					if( function_exists('mtphr_dnt_rotate_slide_down_scripts') ) {
						add_action( 'wp_footer', 'mtphr_dnt_rotate_slide_down_scripts' );
					}
					break;
					
				case 'slide_down':
					if( function_exists('mtphr_dnt_rotate_slide_up_scripts') ) {
						add_action( 'wp_footer', 'mtphr_dnt_rotate_slide_up_scripts' );
					}
					break;
			}
		}
	}
}




add_action( 'wp_head', 'mtphr_dnt_custom_css' );
/**
 * Add custom css
 *
 * @since 1.0.0
 */
function mtphr_dnt_custom_css() {
	$settings = get_option( 'mtphr_dnt_general_settings' );
	if( $settings ) {
	
		if( isset($settings['css']) ) {

			$styles = '<style>'.sanitize_text_field( $settings['css'] ).'</style>';
			echo mtphr_dnt_compress_script( $styles );
		}
	} 
}




/**
 * Add the rotate fade scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_rotate_fade_scripts() {
	ob_start(); ?>
	<script>
	
	// Initialize the ticks and ticker
	function mtphr_dnt_rotater_fade_init( $ticker, ticks, rotate_speed, ease ) {
		
		// Get the first tick
		$tick = ticks[0];
		
		// Find the width of the tick
		var w = $tick.parents('.mtphr-dnt-rotate').width();
		var h = $tick.height();
		
		// Set the height of the ticker
		$ticker.css( 'height', h+'px' );

		// Set the initial position of the width & make sure it's visible
		$tick.show();
  }

	// Show the new tick
	function mtphr_dnt_rotater_fade_in( $ticker, $tick, $prev, rotate_speed, ease ) {
    $tick.fadeIn( rotate_speed );
    
    var h = $tick.height();

		// Resize the ticker
		$ticker.stop().animate( {
			height: h+'px'
		}, rotate_speed, ease, function() {
		});
  }
  
  // Hide the old tick
  function mtphr_dnt_rotater_fade_out( $ticker, $tick, $next, rotate_speed, ease ) {
    $tick.fadeOut( rotate_speed );
  }
	</script>
	<?php // Echo the compressed scripts
	echo mtphr_dnt_compress_script( ob_get_clean() );
}

/**
 * Add the rotate slide left scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_rotate_slide_left_scripts() {
	ob_start(); ?>
	<script>
	
	// Initialize the ticks and ticker
	function mtphr_dnt_rotater_slide_left_init( $ticker, ticks, rotate_speed, ease ) {
		
		// Get the first tick
		$tick = ticks[0];
		
		// Find the dimensions of the tick
		var w = $tick.parents('.mtphr-dnt-rotate').width();
		var h = $tick.height();
		
		// Set the height of the ticker
		$ticker.css( 'height', h+'px' );

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'left', 0 );
		$tick.show();
		
		// If there are any images, reset height after loading
		if( $tick.find('img').length > 0 ) {
			
			$tick.find('img').each( function(index) {
				
				jQuery(this).load( function() {
					
					// Find the height of the tick
					var h = $tick.height();
			
					// Set the height of the ticker
					$ticker.css( 'height', h+'px' );
				});	
			});	
		}
  }
  
	// Show the new tick
	function mtphr_dnt_rotater_slide_left_in( $ticker, $tick, $prev, rotate_speed, ease ) {
		
		// Find the dimensions of the tick
		var w = $tick.parents('.mtphr-dnt-rotate').width();
		var h = $tick.height();

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'left', w+'px' );
		$tick.show();
		
		// Resize the ticker
		$ticker.stop().animate( {
			height: h+'px'
		}, rotate_speed, ease, function() {
		});
		
		// Slide the tick in
		$tick.stop().animate( {
			left: '0'
		}, rotate_speed, ease, function() {
		});
  }
  
  // Hide the old tick
  function mtphr_dnt_rotater_slide_left_out( $ticker, $tick, $next, rotate_speed, ease ) {
    
    // Find the dimensions of the tick
		var w = $tick.parents('.mtphr-dnt-rotate').width();
		var h = $tick.height();
		
		// Slide the tick in
		$tick.stop().animate( {
			left: '-'+w+'px'
		}, rotate_speed, ease, function() {
			// Hide the tick
			$tick.hide();
		});
  }
	</script>
	<?php // Echo the compressed scripts
	echo mtphr_dnt_compress_script( ob_get_clean() );
}

/**
 * Add the rotate slide right scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_rotate_slide_right_scripts() {
	ob_start(); ?>
	<script>
	
	// Initialize the ticks and ticker
	function mtphr_dnt_rotater_slide_right_init( $ticker, ticks, rotate_speed, ease ) {
		
		// Get the first tick
		$tick = ticks[0];
		
		// Find the dimensions of the tick
		var w = $tick.parents('.mtphr-dnt-rotate').width();
		var h = $tick.height();
		
		// Set the height of the ticker
		$ticker.css( 'height', h+'px' );

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'left', 0 );
		$tick.show();
		
		// If there are any images, reset height after loading
		if( $tick.find('img').length > 0 ) {
			
			$tick.find('img').each( function(index) {
				
				jQuery(this).load( function() {
					
					// Find the height of the tick
					var h = $tick.height();
			
					// Set the height of the ticker
					$ticker.css( 'height', h+'px' );
				});	
			});	
		}
  }
  
	// Show the new tick
	function mtphr_dnt_rotater_slide_right_in( $ticker, $tick, $prev, rotate_speed, ease ) {
		
		// Find the dimensions of the tick
		var w = $tick.parents('.mtphr-dnt-rotate').width();
		var h = $tick.height();

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'left', '-'+w+'px' );
		$tick.show();
		
		// Resize the ticker
		$ticker.stop().animate( {
			height: h+'px'
		}, rotate_speed, ease, function() {
		});
		
		// Slide the tick in
		$tick.stop().animate( {
			left: '0'
		}, rotate_speed, ease, function() {
		});
  }
  
  // Hide the old tick
  function mtphr_dnt_rotater_slide_right_out( $ticker, $tick, $next, rotate_speed, ease ) {
    
    // Find the dimensions of the tick
		var w = $tick.parents('.mtphr-dnt-rotate').width();
		var h = $tick.height();
		
		// Slide the tick in
		$tick.stop().animate( {
			left: w+'px'
		}, rotate_speed, ease, function() {
			// Hide the tick
			$tick.hide();
		});
  }
	</script>
	<?php // Echo the compressed scripts
	echo mtphr_dnt_compress_script( ob_get_clean() );
}

/**
 * Add the rotate slide down scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_rotate_slide_down_scripts() {
	ob_start(); ?>
	<script>
	
	// Initialize the ticks and ticker
	function mtphr_dnt_rotater_slide_down_init( $ticker, ticks, rotate_speed, ease ) {
		
		// Get the first tick
		$tick = ticks[0];
		
		// Find the height of the tick
		var h = $tick.height();
		
		// Set the height of the ticker
		$ticker.css( 'height', h+'px' );

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'top', 0 );
		$tick.show();
		
		// If there are any images, reset height after loading
		if( $tick.find('img').length > 0 ) {
			
			$tick.find('img').each( function(index) {
				
				jQuery(this).load( function() {
					
					// Find the height of the tick
					var h = $tick.height();
			
					// Set the height of the ticker
					$ticker.css( 'height', h+'px' );
				});	
			});	
		}
  }
  
	// Show the new tick
	function mtphr_dnt_rotater_slide_down_in( $ticker, $tick, $prev, rotate_speed, ease ) {
		
		// Find the height of the tick
		var h = $tick.height();

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'top', '-'+h+'px' );
		$tick.show();
		
		// Resize the ticker
		$ticker.stop().animate( {
			height: h+'px'
		}, rotate_speed, ease, function() {
		});
		
		// Slide the tick in
		$tick.stop().animate( {
			top: '0'
		}, rotate_speed, ease, function() {
		});
  }
  
  // Hide the old tick
  function mtphr_dnt_rotater_slide_down_out( $ticker, $tick, $next, rotate_speed, ease ) {
    
    // Find the height of the next tick
		var h = $next.height();
		
		// Slide the tick in
		$tick.stop().animate( {
			top: h+'px'
		}, rotate_speed, ease, function() {
			// Hide the tick
			$tick.hide();
		});
  }
	</script>
	<?php // Echo the compressed scripts
	echo mtphr_dnt_compress_script( ob_get_clean() );
}

/**
 * Add the rotate slide up scripts
 *
 * @since 1.0.0
 */
function mtphr_dnt_rotate_slide_up_scripts() {
	ob_start(); ?>
	<script>
	
	// Initialize the ticks and ticker
	function mtphr_dnt_rotater_slide_up_init( $ticker, ticks, rotate_speed, ease ) {
		
		// Get the first tick
		$tick = ticks[0];
		
		// Find the height of the tick
		var h = $tick.height();

		// Set the height of the ticker
		$ticker.css( 'height', h+'px' );

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'top', 0 );
		$tick.show();
		
		// If there are any images, reset height after loading
		if( $tick.find('img').length > 0 ) {
			
			$tick.find('img').each( function(index) {
				
				jQuery(this).load( function() {
					
					// Find the height of the tick
					var h = $tick.height();
			
					// Set the height of the ticker
					$ticker.css( 'height', h+'px' );
				});	
			});	
		}
  }
  
	// Show the new tick
	function mtphr_dnt_rotater_slide_up_in( $ticker, $tick, $prev, rotate_speed, ease ) {
		
		// Find the height of the tick
		var h = $tick.height();

		// Set the initial position of the width & make sure it's visible
		$tick.css( 'top', h+'px' );
		$tick.show();
		
		// Resize the ticker
		$ticker.stop().animate( {
			height: h+'px'
		}, rotate_speed, ease, function() {
		});
		
		// Slide the tick in
		$tick.stop().animate( {
			top: '0'
		}, rotate_speed, ease, function() {
		});
  }
  
  // Hide the old tick
  function mtphr_dnt_rotater_slide_up_out( $ticker, $tick, $next, rotate_speed, ease ) {
    
    // Find the height of the next tick
		var h = $tick.height();
		
		// Slide the tick in
		$tick.stop().animate( {
			top: '-'+h+'px'
		}, rotate_speed, ease, function() {
			// Hide the tick
			$tick.hide();
		});
  }
	</script>
	<?php // Echo the compressed scripts
	echo mtphr_dnt_compress_script( ob_get_clean() );
}



