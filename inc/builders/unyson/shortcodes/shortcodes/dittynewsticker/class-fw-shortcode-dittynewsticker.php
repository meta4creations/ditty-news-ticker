<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Shortcode_DittyNewsTicker extends FW_Shortcode
{
	/**
	 * @internal
	 */
	public function _init() {
	}

	public function get_tickers() {
		
		$args = array(
			'posts_per_page' => -1,
			'offset' => 0,
			'category' => '',
			'orderby' => 'title',
			'order' => 'ASC',
			'include' => '',
			'exclude' => '',
			'meta_key' => '',
			'meta_value' => '',
			'post_type' => 'ditty_news_ticker',
			'post_mime_type' => '',
			'post_parent' => '',
			'post_status' => 'publish',
			'suppress_filters' => true
		);
		$tickers = get_posts( $args );
		
		$tickers_array = array( 'dnt-' => __('Select a Ticker', 'ditty-news-ticker') );
		if( is_array($tickers) && count($tickers) > 0 ) {
			foreach( $tickers as $i=>$ticker ) {
				$tickers_array['dnt-'.$ticker->ID] = $ticker->post_title;
			}
		} else {
			$tickers_array = array( '' => __('No tickers found', 'ditty-news-ticker') );
		}
	
		return $tickers_array;
	}
}