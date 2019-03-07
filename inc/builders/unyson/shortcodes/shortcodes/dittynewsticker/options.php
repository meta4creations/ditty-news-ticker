<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$dittynewsticker_shortcode = fw_ext('shortcodes')->get_shortcode('dittynewsticker');

$options = array(
	'ticker' => array(
    'type'  => 'select',
    'label' => __('Ditty News Ticker', 'ditty-news-ticker'),
    'desc'  => __('Select the ticker you want to display', 'ditty-news-ticker'),
    'choices' => $dittynewsticker_shortcode->get_tickers()
	)
	
);