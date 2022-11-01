<?php
/**
 * Shortcodes
 *
 * @package Ditty News Ticker
 */
 

add_shortcode( 'ditty_news_ticker', 'ditty_news_ticker_display' );
/**
 * Add the news ticker.
 *
 * @since 1.0.0
 */
function ditty_news_ticker_display( $atts, $content = null ) {
	$defaults = array(
		'id' => '',
		'class' => ''
	);
	$args = shortcode_atts( $defaults, $atts );

	// Remove the id & class before passing the atts
	unset( $atts['id'] );
	unset( $atts['class'] );
	
	// Return the ticker
	return get_mtphr_dnt_ticker( $args['id'], $args['class'], $atts );
}


