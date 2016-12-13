<?php

/* --------------------------------------------------------- */
/* !Add VC Shortcode - 2.1.4 */
/* --------------------------------------------------------- */

function mtphr_dnt_add_to_vc() {	
	if( defined('WPB_VC_VERSION') && version_compare(WPB_VC_VERSION, '5.0.0', '>=' ) ) {
		vc_map( array(
			 'name' => __( 'Ditty News Ticker', 'ditty-news-ticker' ),
			 'base' => 'ditty_news_ticker',
			 'icon' => 'mtphr-dnt-icon-dittynewsticker',
			 'category' => 'Content',
			 'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => __( 'Ditty News Ticker', 'ditty-news-ticker' ),
						'param_name' => 'id',
						'description' => __('Select the ticker you want to display', 'ditty-news-ticker'),
						'value' => mtphr_dnt_get_tickers( true ),
						'admin_label' => true
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Class', 'ditty-news-ticker' ),
						'param_name' => 'class',
						'description' => __('Add a custom class name to the ticker', 'ditty-news-ticker'),
					),
		   )
		));
	}
}
add_action( 'vc_before_init', 'mtphr_dnt_add_to_vc' );