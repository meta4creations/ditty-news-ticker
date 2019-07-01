<?php

/**
 * Load external scripts
 * @since 3.0
 */
function _action_dnt_scripts() {
	
	if( is_admin() ) {
		
		global $typenow;
	
		if( $typenow == 'ditty_news_ticker' || $typenow == 'ditty_layout' ) {
			
			// Load codemirror
			wp_enqueue_style( 'codemirror', plugins_url('ditty-news-ticker/inc/static/packages/codemirror-5.40.0/lib/codemirror.css'), array(), '5.40.0' );
			wp_enqueue_style( 'codemirror-blackboard', plugins_url('ditty-news-ticker/inc/static/packages/codemirror-5.40.0/theme/blackboard.css'), array(), '5.40.0' );
			wp_enqueue_script( 'codemirror', plugins_url('ditty-news-ticker/inc/static/packages/codemirror-5.40.0/lib/codemirror.js'), array(), '5.40.0', true );	
			wp_enqueue_script( 'codemirror-css', plugins_url('ditty-news-ticker/inc/static/packages/codemirror-5.40.0/mode/css/css.js'), array(), '5.40.0', true );
			//wp_enqueue_script( 'codemirror-php', plugins_url('ditty-news-ticker/inc/static/packages/codemirror-5.40.0/mode/php/php.js'), array(), '5.40.0', true );
			wp_enqueue_script( 'codemirror-htmlmixed', plugins_url('ditty-news-ticker/inc/static/packages/codemirror-5.40.0/mode/htmlmixed/htmlmixed.js'), array(), '5.40.0', true );	
			
			// Register jQuery easing
			wp_register_script( 'jquery-easing', plugins_url('ditty-news-ticker/inc/static/js/jquery.easing.js'), array('jquery'), '1.4.1', true );
	
			// Load the news ticker scripts
			wp_enqueue_script( 'ditty-news-ticker', plugins_url('ditty-news-ticker/inc/static/js/script-admin.min.js'), array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-sortable',
				'jquery-easing',
			), filemtime(DNT_DIR.'inc/static/js/script-admin.min.js'), true );
			wp_localize_script( 'ditty-news-ticker', 'ditty_news_ticker_vars', array(
					'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
					'security' 				=> wp_create_nonce( 'ditty-news-ticker' ),
					'edit_types' 			=> DNT()->tickers->edit_types(),
					'edit_templates' 	=> DNT()->tickers->edit_templates(),
				)
			);
			
			
			wp_enqueue_script( 'ditty-news-ticker-react', plugins_url('ditty-news-ticker/inc/static/js/script-admin-react.min.js'), array(
				'wp-element'
			), filemtime(DNT_DIR.'inc/static/js/script-admin-react.min.js'), true );
			wp_localize_script( 'ditty-news-ticker-react', 'ditty_news_ticker_vars', array(
					'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
					'security' 		=> wp_create_nonce( 'ditty-news-ticker' ),
					'dnt_types' 	=> dnt_types(),
					'dnt_fields' 	=> dnt_fields(),
					'dnt_ticks' 	=> dnt_ticks(),
					'dnt_strings' => dnt_strings()
				)
			);
			
		}	
		
		// Load fontawesome
		wp_enqueue_style( 'fontawesome-pro', plugins_url('ditty-news-ticker/inc/static/packages/fontawesome-pro-5.7.2-web/css/all.min.css'), false, '5.7.2' );

		// Load the plugin css
		wp_enqueue_style( 'ditty-news-ticker', plugins_url('ditty-news-ticker/inc/static/css/style-admin.css'), false, filemtime(DNT_DIR.'inc/static/css/style-admin.css') );
		
	} else {
		
	}
}
add_action( 'wp_enqueue_scripts', '_action_dnt_scripts' );
add_action( 'admin_enqueue_scripts', '_action_dnt_scripts' );