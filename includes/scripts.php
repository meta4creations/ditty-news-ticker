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
 * @since 1.2.1
 */
function mtphr_dnt_admin_scripts( $hook ) {

	global $typenow;

	if ( $typenow == 'ditty_news_ticker' ) {

		// Load scipts for the media uploader
		if(function_exists( 'wp_enqueue_media' )){
	    wp_enqueue_media();
		} else {
	    wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
		}

		// Load the CodeMirror plugin
		wp_register_style( 'codemirror', MTPHR_DNT_URL.'/assets/css/codemirror.css', false, MTPHR_DNT_VERSION );
		wp_enqueue_style( 'codemirror' );
		wp_register_script( 'codemirror', MTPHR_DNT_URL.'/assets/js/codemirror.js', array('jquery'), MTPHR_DNT_VERSION, true );
		wp_enqueue_script( 'codemirror' );
		wp_register_script( 'codemirror-css', MTPHR_DNT_URL.'/assets/js/css.js', array('jquery'), MTPHR_DNT_VERSION, true );
		wp_enqueue_script( 'codemirror-css' );

		// Load the metaboxer scripts
		wp_register_style( 'ditty-metaboxer', MTPHR_DNT_URL.'/includes/metaboxer/metaboxer.css', array( 'colors', 'thickbox' ), MTPHR_DNT_VERSION );
		wp_enqueue_style( 'ditty-metaboxer' );
		wp_register_script( 'ditty-metaboxer', MTPHR_DNT_URL.'/includes/metaboxer/metaboxer.js', array( 'jquery','jquery-ui-core','jquery-ui-sortable' ), MTPHR_DNT_VERSION, true );
		wp_enqueue_script( 'ditty-metaboxer' );
		wp_localize_script( 'ditty-metaboxer', 'ditty_metaboxer_vars', array(
				'security' => wp_create_nonce( 'ditty-metaboxer' )
			)
		);
	}

	// Load the plugin css
	wp_register_style( 'mtphr-dnt-admin', MTPHR_DNT_URL.'/assets/css/style-admin.css', false, MTPHR_DNT_VERSION );
	wp_enqueue_style( 'mtphr-dnt-admin' );
}




add_action( 'wp_enqueue_scripts', 'mtphr_dnt_scripts' );
/**
 * Load the front end scripts
 *
 * @since 1.1.8
 */
function mtphr_dnt_scripts() {

	// Load the css
	wp_register_style( 'ditty-news-ticker', MTPHR_DNT_URL.'/assets/css/style.css', false, MTPHR_DNT_VERSION );
	wp_enqueue_style( 'ditty-news-ticker' );

	// Register touchSwipe
	wp_register_script( 'touchSwipe', MTPHR_DNT_URL.'/assets/js/jquery.touchSwipe.min.js', array('jquery'), MTPHR_DNT_VERSION, true );

	// Register the jQuery easing
	wp_register_script( 'jquery-easing', MTPHR_DNT_URL.'/assets/js/jquery.easing.1.3.js', array('jquery'), MTPHR_DNT_VERSION, true );

	// Register the DNT jQuery class
	wp_register_script( 'ditty-news-ticker', MTPHR_DNT_URL.'/assets/js/ditty-news-ticker.js', array('jquery'), MTPHR_DNT_VERSION, true );
}




add_action( 'wp_head', 'mtphr_dnt_custom_css' );
/**
 * Add custom css
 *
 * @since 1.1.5
 */
function mtphr_dnt_custom_css() {

	$settings = get_option( 'mtphr_dnt_general_settings' );
	if( $settings && isset($settings['css']) ) {
		echo '<style>'.sanitize_text_field( $settings['css'] ).'</style>';
	}
}




add_action( 'wp_footer', 'mtphr_dnt_tickers_init_scripts', 20 );
/**
 * Initialize the ticker scriptinos
 *
 * @since 1.1.8
 */
function mtphr_dnt_tickers_init_scripts() {

	global $mtphr_dnt_ticker_scripts;
	if( is_array($mtphr_dnt_ticker_scripts) && !empty($mtphr_dnt_ticker_scripts) ) {
		wp_print_scripts('touchSwipe');
		wp_print_scripts('jquery-easing');
		wp_print_scripts('ditty-news-ticker');
		?>
		<script>
			jQuery( window ).load( function() {
			<?php foreach( $mtphr_dnt_ticker_scripts as $ticker ) { ?>
				jQuery( '<?php echo $ticker['ticker']; ?>' ).ditty_news_ticker({
					id : '<?php echo $ticker['id']; ?>',
					type : '<?php echo $ticker['type']; ?>',
					scroll_direction : '<?php echo $ticker['scroll_direction']; ?>',
					scroll_speed : <?php echo $ticker['scroll_speed']; ?>,
					scroll_pause : <?php echo $ticker['scroll_pause']; ?>,
					scroll_spacing : <?php echo $ticker['scroll_spacing']; ?>,
					scroll_init : <?php echo $ticker['scroll_init']; ?>,
					rotate_type : '<?php echo $ticker['rotate_type']; ?>',
					auto_rotate : <?php echo $ticker['auto_rotate']; ?>,
					rotate_delay : <?php echo $ticker['rotate_delay']; ?>,
					rotate_pause : <?php echo $ticker['rotate_pause']; ?>,
					rotate_speed : <?php echo $ticker['rotate_speed']; ?>,
					rotate_ease : '<?php echo $ticker['rotate_ease']; ?>',
					nav_reverse : <?php echo $ticker['nav_reverse']; ?>,
					offset : <?php echo $ticker['offset']; ?>,
					after_load : function( $ticker ) {
						<?php echo apply_filters( 'mtphr_dnt_after_load_rotate' , '', $ticker['id'] ); ?>
					},
					before_change : function( $ticker ) {
						<?php echo apply_filters( 'mtphr_dnt_before_change_rotate' , '', $ticker['id'] ); ?>
					},
					after_change : function( $ticker ) {
						<?php echo apply_filters( 'mtphr_dnt_after_change_rotate' , '', $ticker['id'] ); ?>
					}
				});
			 <?php } ?>
			});
		</script>
		<?php
	}
}

