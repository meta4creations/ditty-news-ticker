<?php

/* --------------------------------------------------------- */
/* !Load the admin scrips - 1.4.6 */
/* --------------------------------------------------------- */

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
		wp_register_style( 'codemirror', MTPHR_DNT_URL.'/assets/css/admin/codemirror.css', false, MTPHR_DNT_VERSION );
		wp_enqueue_style( 'codemirror' );
		wp_register_script( 'codemirror', MTPHR_DNT_URL.'/assets/js/admin/codemirror.js', array('jquery'), MTPHR_DNT_VERSION, true );
		wp_enqueue_script( 'codemirror' );
		wp_register_script( 'codemirror-css', MTPHR_DNT_URL.'/assets/js/admin/codemirror/css.js', array('jquery'), MTPHR_DNT_VERSION, true );
		wp_enqueue_script( 'codemirror-css' );
		
		// Load the news ticker scripts
		wp_register_script( 'ditty-news-ticker', MTPHR_DNT_URL.'/assets/js/admin/script.js', array( 'jquery','jquery-ui-core','jquery-ui-sortable' ), MTPHR_DNT_VERSION, true );
		wp_enqueue_script( 'ditty-news-ticker' );
		wp_localize_script( 'ditty-news-ticker', 'ditty_news_ticker_vars', array(
				'security' => wp_create_nonce( 'ditty-news-ticker' )
			)
		);
	}
	
	// Load the icon font css
	wp_register_style( 'ditty-news-ticker-font', MTPHR_DNT_URL.'/assets/fontastic/styles.css', false, MTPHR_DNT_VERSION );
	wp_enqueue_style( 'ditty-news-ticker-font' );

	// Load the plugin css
	wp_register_style( 'ditty-news-ticker', MTPHR_DNT_URL.'/assets/css/admin/style.css', false, MTPHR_DNT_VERSION );
	wp_enqueue_style( 'ditty-news-ticker' );
}
add_action( 'admin_enqueue_scripts', 'mtphr_dnt_admin_scripts' );

