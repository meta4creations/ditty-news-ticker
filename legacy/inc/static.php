<?php

/* --------------------------------------------------------- */
/* !Load the front end scripts - 2.2 */
/* --------------------------------------------------------- */

function mtphr_dnt_scripts( $hook ) {
	
	$version = WP_DEBUG ? time() : DITTY_VERSION;
	
	if ( is_admin() ) {
		
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

			// Load protop
			wp_enqueue_style( 'protip', DITTY_URL . 'legacy/inc/static/libs/protip/protip.min.css', false, '1.4.21' );
			wp_enqueue_script( 'protip', DITTY_URL . 'legacy/inc/static/libs/protip/protip.min.js', array( 'jquery' ), '1.4.21', true );
	
			// Load the news ticker scripts
			wp_enqueue_script( 'ditty-news-ticker', DITTY_URL . 'legacy/inc/static/js/script-admin.min.js', array( 'jquery','jquery-ui-core','jquery-ui-sortable', 'jquery-effects-core', 'protip' ), $version, true );
			wp_localize_script( 'ditty-news-ticker', 'ditty_news_ticker_vars', array(
					'security' => wp_create_nonce( 'ditty-news-ticker' ),
					'img_title' => __( 'Upload or select an image', 'ditty-news-ticker' ),
					'img_button' => __( 'Use Image', 'ditty-news-ticker' ),
					'strings' => mtphr_dnt_strings()
				)
			);
		}
		
		// Load the icon font css
		wp_enqueue_style( 'ditty-news-ticker-font', DITTY_URL . 'legacy/inc/static/libs/fontastic/styles.css', false, $version );
	
		// Load the plugin css
		wp_enqueue_style( 'ditty-news-ticker', DITTY_URL . 'legacy/inc/static/css/style-admin.css', array('dashicons'), $version );
		
	} else {

		// Load the icon font css
		wp_enqueue_style( 'ditty-news-ticker-font', DITTY_URL . 'legacy/inc/static/libs/fontastic/styles.css', false, $version );
	
		// Register swiped events
		wp_enqueue_script( 'swiped-events', DITTY_URL . 'legacy/inc/static/js/swiped-events.min.js', false, '1.1.4', true );
	
		// Register the Ditty News Ticker scripts
		wp_enqueue_style( 'ditty-news-ticker', DITTY_URL . 'legacy/inc/static/css/style.css', false, $version );
		
		wp_enqueue_script( 'ditty-news-ticker', DITTY_URL . 'legacy/inc/static/js/ditty-news-ticker.js', array('jquery', 'imagesloaded', 'swiped-events', 'jquery-effects-core'), $version, true );
		wp_localize_script( 'ditty-news-ticker', 'mtphr_dnt_vars', array(
				'is_rtl' => is_rtl(),
			)
		);		
	}
}
add_action( 'wp_enqueue_scripts', 'mtphr_dnt_scripts' );
add_action( 'admin_enqueue_scripts', 'mtphr_dnt_scripts' );



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
/* !Initialize the ticker scripts - 3.0.12 */
/* --------------------------------------------------------- */

function mtphr_dnt_tickers_init_scripts() {

	global $mtphr_dnt_ticker_scripts;
	
	if( is_array($mtphr_dnt_ticker_scripts) && !empty($mtphr_dnt_ticker_scripts) ) {
		//wp_print_scripts('ditty-news-ticker');
		
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
			jQuery( function( $ ) {
			<?php foreach( $filtered_tickers as $ticker ) { ?>
				$( '<?php echo $ticker['ticker']; ?>' ).ditty_news_ticker({
					id : '<?php echo $ticker['id']; ?>',
					type : '<?php echo $ticker['type']; ?>',
					scroll_direction : '<?php echo $ticker['scroll_direction']; ?>',
					scroll_speed : <?php echo $ticker['scroll_speed']; ?>,
					scroll_pause : <?php echo $ticker['scroll_pause']; ?>,
					scroll_spacing : <?php echo $ticker['scroll_spacing']; ?>,
					scroll_init : <?php echo $ticker['scroll_init']; ?>,
					scroll_init_delay : <?php echo isset( $ticker['scroll_init_delay'] ) ? intval( $ticker['scroll_init_delay'] ) : 2; ?>,
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