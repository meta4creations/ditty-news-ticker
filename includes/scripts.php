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
		wp_register_style( 'ditty-metaboxer', MTPHR_DNT_URL.'/includes/metaboxer/metaboxer.css', array( 'colors', 'thickbox' ), MTPHR_DNT_VERSION );
		wp_enqueue_style( 'ditty-metaboxer' );
		
		// Load scipts for the media uploader
		if(function_exists( 'wp_enqueue_media' )){
	    wp_enqueue_media();
		} else {
	    wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
		}
	
		// Load the metaboxer jQuery
		wp_register_script( 'ditty-metaboxer', MTPHR_DNT_URL.'/includes/metaboxer/metaboxer.js', array( 'jquery','jquery-ui-core','jquery-ui-sortable' ), MTPHR_DNT_VERSION, true );
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
 * @since 1.1.2
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
		jQuery( window ).load( function() {
			jQuery( '<?php echo $ticker; ?>' ).ditty_news_ticker({
				id : '<?php echo $id; ?>',
				type : '<?php echo $_mtphr_dnt_mode; ?>',
				scroll_direction : '<?php echo $_mtphr_dnt_scroll_direction; ?>',
				scroll_speed : <?php echo intval($_mtphr_dnt_scroll_speed); ?>,
				scroll_pause : <?php echo $pause; ?>,
				scroll_spacing : <?php echo intval($_mtphr_dnt_scroll_tick_spacing); ?>
			});
		});
		</script>
		<?php // Echo the compressed scripts
		echo ob_get_clean();
		
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
		jQuery( window ).load( function() {
			jQuery( '<?php echo $ticker; ?>' ).ditty_news_ticker({
				id : '<?php echo $id; ?>',
				type : '<?php echo $_mtphr_dnt_mode; ?>',
				rotate_type : '<?php echo $_mtphr_dnt_rotate_type; ?>',
				auto_rotate : <?php echo $rotate; ?>,
				rotate_delay : <?php echo intval($_mtphr_dnt_rotate_delay); ?>,
				rotate_pause : <?php echo $pause; ?>,
				rotate_speed : <?php echo intval($_mtphr_dnt_rotate_speed); ?>,
				rotate_ease : '<?php echo $_mtphr_dnt_rotate_ease; ?>',
				nav_reverse : <?php echo $nav_reverse; ?>,
				after_load : function( $ticker ) {
					<?php echo apply_filters( 'mtphr_dnt_after_load_rotate' , '', $id ); ?>
				},
				before_change : function( $ticker ) {
					<?php echo apply_filters( 'mtphr_dnt_before_change_rotate' , '', $id ); ?>
				},
				after_change : function( $ticker ) {
					<?php echo apply_filters( 'mtphr_dnt_after_change_rotate' , '', $id ); ?>
				}
			});
		});
		</script>
		<?php // Echo the compressed scripts
		echo ob_get_clean();
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

