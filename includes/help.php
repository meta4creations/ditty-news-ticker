<?php
/**
 * Add help tabs
 *
 * @package Ditty News Ticker
 */




add_action('admin_head-edit.php', 'mtphr_dnt_edit_help_tab');
/**
 * Edit page help tab
 *
 * @since 1.0.0
 */
function mtphr_dnt_edit_help_tab() {

	global $typenow;

	if ( $typenow == 'ditty_news_ticker' ) {
		
		// Get the current screen
		$screen = get_current_screen();
		
		// Add a general help tab
		mtphr_dnt_general_help_tab( $screen );
		
		// Add info to the help sidebar
		$screen->set_help_sidebar( mtphr_dnt_help_sidebar() );
	}
}




add_action('admin_head-post-new.php', 'mtphr_dnt_post_help_tab');
add_action('admin_head-post.php', 'mtphr_dnt_post_help_tab');
/**
 * Post page help tab
 *
 * @since 1.0.0
 */
function mtphr_dnt_post_help_tab() {

	global $typenow;

	if ( $typenow == 'ditty_news_ticker' ) {

		// Get the current screen
		$screen = get_current_screen();
		
		// Add a general help tab
		mtphr_dnt_general_help_tab( $screen );
		
		// Add a general help tab
		$screen->add_help_tab( array( 
			'id' => 'mtphr-dnt-scroll-mode-help',            			//unique id for the tab
			'title' => __('Scroll Mode', 'ditty-news-ticker'),   					//unique visible title for the tab
			'callback' => 'mtphr_dnt_scroll_mode_help_callback'		//optional function to callback
		));
		
		// Add a general help tab
		$screen->add_help_tab( array( 
			'id' => 'mtphr-dnt-rotate-mode-help',            			//unique id for the tab
			'title' => __('Rotate Mode', 'ditty-news-ticker'),   					//unique visible title for the tab
			'callback' => 'mtphr_dnt_rotate_mode_help_callback'		//optional function to callback
		));
		
		// Add a general help tab
		$screen->add_help_tab( array( 
			'id' => 'mtphr-dnt-list-mode-help',            				//unique id for the tab
			'title' => __('List Mode', 'ditty-news-ticker'),   						//unique visible title for the tab
			'callback' => 'mtphr_dnt_list_mode_help_callback'			//optional function to callback
		));
		
		// Add info to the help sidebar
		$screen->set_help_sidebar( mtphr_dnt_help_sidebar() );
	}
}




/**
 * Create the general help tab
 *
 * @since 1.0.0
 */
function mtphr_dnt_general_help_tab( $screen ) {

	// Add a general help tab
	$screen->add_help_tab( array( 
		'id' => 'mtphr-dnt-general-help',            				//unique id for the tab
		'title' => __('Ditty News Ticker', 'ditty-news-ticker'),   	//unique visible title for the tab
		'callback' => 'mtphr_dnt_general_help_callback' 		//optional function to callback
	));
}




/**
 * Help tab sidebar
 *
 * @since 1.0.0
 */
function mtphr_dnt_help_sidebar() {

	$sidebar = '<p><strong>'.__('For more information:', 'ditty-news-ticker').'</strong></p>';
	$sidebar .= '<p>'.__('Visit the <a href="http://www.metaphorcreations.com" target="_blank">documentation</a> on the Ditty News Ticker website', 'ditty-news-ticker').'</p>';
	$sidebar .= '<p><strong><a href="http://www.metaphorcreations.com" target="_blnak">'.__('View DNT extensions', 'ditty-news-ticker').'</strong></a>';
	
	return $sidebar;
}




/**
 * General help tab content
 *
 * @since 1.0.0
 */
function mtphr_dnt_general_help_callback() {
	echo '<p>'.__('Add general information about Ditty News Ticker.', 'ditty-news-ticker').'</p>';	
}

/**
 * Scroll mode help tab content
 *
 * @since 1.0.0
 */
function mtphr_dnt_scroll_mode_help_callback() {
	echo '<p>'.__('Add scroll mode info.', 'ditty-news-ticker').'</p>';	
}

/**
 * Rotate mode help tab content
 *
 * @since 1.0.0
 */
function mtphr_dnt_rotate_mode_help_callback() {
	echo '<p>'.__('Add rotate mode info.', 'ditty-news-ticker').'</p>';	
}

/**
 * List mode help tab content
 *
 * @since 1.0.0
 */
function mtphr_dnt_list_mode_help_callback() {
	echo '<p>'.__('Add list mode info.', 'ditty-news-ticker').'</p>';	
}








