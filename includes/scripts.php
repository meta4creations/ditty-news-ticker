<?php

/* --------------------------------------------------------- */
/* !Load the front end scripts - 2.1.2 */
/* --------------------------------------------------------- */

function mtphr_dnt_scripts() {

	// Load the icon font css
	wp_register_style( 'ditty-news-ticker-font', plugins_url('ditty-news-ticker/assets/fontastic/styles.css'), false, filemtime(MTPHR_DNT_DIR.'assets/fontastic/styles.css') );
	wp_enqueue_style( 'ditty-news-ticker-font' );

	// Register touchSwipe
	wp_register_script( 'touchSwipe', plugins_url('ditty-news-ticker/assets/js/jquery.touchSwipe.min.js'), array('jquery'), filemtime(MTPHR_DNT_DIR.'assets/js/jquery.touchSwipe.min.js'), true );

	// Register the jQuery easing
	wp_register_script( 'jquery-easing', plugins_url('ditty-news-ticker/assets/js/jquery.easing.1.3.js'), array('jquery'), '1.3', true );

	// Register the Ditty News Ticker scripts
	wp_register_style( 'ditty-news-ticker', plugins_url('ditty-news-ticker/assets/css/style.css'), false, filemtime(MTPHR_DNT_DIR.'assets/css/style.css') );
	wp_enqueue_style( 'ditty-news-ticker' );
	wp_register_script( 'imagesLoaded', plugins_url('ditty-news-ticker/assets/js/imagesloaded.pkgd.min.js'), array('jquery'), '4.1.0', true );
	wp_register_script( 'ditty-news-ticker', plugins_url('ditty-news-ticker/assets/js/ditty-news-ticker.js'), array('jquery', 'imagesLoaded', 'touchSwipe', 'jquery-easing'), filemtime(MTPHR_DNT_DIR.'assets/js/ditty-news-ticker.js'), true );
	wp_localize_script( 'ditty-news-ticker', 'mtphr_dnt_vars', array(
			'is_rtl' => is_rtl(),
		)
	);
	
}
add_action( 'wp_enqueue_scripts', 'mtphr_dnt_scripts' );



/* --------------------------------------------------------- */
/* !Add custom css - 1.1.5 */
/* --------------------------------------------------------- */

function mtphr_dnt_custom_css() {

	$settings = get_option( 'mtphr_dnt_general_settings' );
	if( $settings && isset($settings['css']) ) {
		echo '<style>'.sanitize_text_field( $settings['css'] ).'</style>';
	}
}
add_action( 'wp_head', 'mtphr_dnt_custom_css' );



/* --------------------------------------------------------- */
/* !Initialize the ticker scripts - 1.5.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_tickers_init_scripts() {

	global $mtphr_dnt_ticker_scripts;
	
	if( is_array($mtphr_dnt_ticker_scripts) && !empty($mtphr_dnt_ticker_scripts) ) {
		wp_print_scripts('ditty-news-ticker');
		
		$filtered_tickers = array();
		$id_array = array();
		foreach ( $mtphr_dnt_ticker_scripts as $ticker) {
	    if (!array_key_exists($ticker['id'], $id_array)) {
        $id_array[$ticker['id']] = $ticker['id'];
        $filtered_tickers[] = $ticker;
	    }
		}
		?>
		<script>
			jQuery( document ).ready( function($) {
			<?php foreach( $filtered_tickers as $ticker ) { ?>
				$( '<?php echo $ticker['ticker']; ?>' ).ditty_news_ticker({
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
					disable_touchswipe : <?php echo $ticker['disable_touchswipe']; ?>,
					offset : <?php echo $ticker['offset']; ?>,
					after_load : function( $ticker ) {
						<?php echo apply_filters( 'mtphr_dnt_after_load_rotate', '', $ticker['id'] ); ?>
					},
					before_change : function( $ticker ) {
						<?php echo apply_filters( 'mtphr_dnt_before_change_rotate', '', $ticker['id'] ); ?>
					},
					after_change : function( $ticker ) {
						<?php echo apply_filters( 'mtphr_dnt_after_change_rotate', '', $ticker['id'] ); ?>
					}
				});
			 <?php } ?>
			});
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'mtphr_dnt_tickers_init_scripts', 20 );