<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

if( ($atts['ticker'] != '' || $atts['ticker'] != 'dnt-') && function_exists('ditty_news_ticker') ) {
	ditty_news_ticker( substr($atts['ticker'], 4) );
}