<?php

/**
 * Make sure Ditty News Ticker is enabled if the old shortcode is used
 *
 * @since    3.0  
*/
function ditty_legacy_display() {
	ditty_settings( 'ditty_news_ticker', '1' );
}
add_shortcode( 'ditty_news_ticker', 'ditty_legacy_display' );

/**
 * Make sure Ditty News Ticker is enabled if the old function is used
 *
 * @since    3.0  
*/
if ( ! function_exists( 'ditty_news_ticker' ) ) {
	function ditty_news_ticker() {
		ditty_settings( 'ditty_news_ticker', '1' );
	}
}
