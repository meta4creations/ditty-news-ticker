<?php
/**
 * Create the meta boxes
 *
 * @package Ditty News Ticker
 */
 



add_action( 'admin_init', 'mtphr_dnt_metabox_types', 9 ); 
/**
 * Create the types metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metabox_types() {

	/* Create the types metabox. */
	$dnt_types = array(
		'id' => 'mtphr_dnt_types',
		'title' => __('Ticker Type', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'id' => '_mtphr_dnt_type',
				'type' => 'metabox_toggle',
				'options' => mtphr_dnt_types_array(),
				'default' => 'default',
				'append' => array(
					'_mtphr_dnt_type_link' => array(
						'type' => 'html',
						'default' => '<a href="http://dittynewsticker.com/extensions/" target="_blank"><strong>'.__('View all types', 'ditty-news-ticker').'</strong></a>'
					)
				)
			)
		)
	);
	new MTPHR_DNT_MetaBoxer( $dnt_types );
}
 
 


add_action( 'admin_init', 'mtphr_dnt_metabox_type_default' ); 
/**
 * Create the default type metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metabox_type_default() {
	
	// Create an array to store the default item structure
	$tick_structure = array(
		'tick' => array(
			'header' => __('Ticker text', 'ditty-news-ticker'),
			'width' => '60%',
			'type' => 'textarea',
			'rows' => 2
		),
		'link' => array(
			'header' => __('Link', 'ditty-news-ticker'),
			'type' => 'text'
		),
		'target' => array(
			'header' => __('Target', 'ditty-news-ticker'),
			'type' => 'select',
			'options' => array( '_self', '_blank' )
		)
	);
	
	// Create an array to store the fields
	$default_fields = array();
	
	// Add the items field
	$default_fields['ticks'] = array(
		'id' => '_mtphr_dnt_ticks',
		'type' => 'list',
		'structure' => apply_filters('mtphr_dnt_default_tick_structure', $tick_structure)
	);
	
	// Create the metabox
	$dnt_default_data = array(
		'id' => 'mtphr_dnt_type_default',
		'title' => __('Default Ticker Items', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_dnt_type_fields_default', $default_fields)
	);
	new MTPHR_DNT_MetaBoxer( $dnt_default_data );
}




add_action( 'admin_init', 'mtphr_dnt_metabox_modes', 11 ); 
/**
 * Create the modes metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_metabox_modes() {

	// Create an array to store the fields
	$modes_fields = array();

	// Add the modes fields
	$modes_fields['modes'] = array(
		'id' => '_mtphr_dnt_mode',
		'type' => 'metabox_toggle',
		'options' => mtphr_dnt_modes_array(),
		'default' => 'scroll',
		'append' => array(
			'_mtphr_dnt_mode_link' => array(
				'type' => 'html',
				'default' => '<a href="http://dittynewsticker.com/extensions/" target="_blank"><strong>'.__('View all modes', 'ditty-news-ticker').'</strong></a>'
			)
		)
	);
	
	/* Create the modes metabox. */
	$dnt_modes = array(
		'id' => 'mtphr_dnt_modes',
		'title' => __('Ticker Mode', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => $modes_fields
	);
	new MTPHR_DNT_MetaBoxer( $dnt_modes );
}




add_action( 'admin_init', 'mtphr_dnt_mode_metabox_scroll', 12 ); 
/**
 * Create the scroll mode metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_mode_metabox_scroll() {

	// Create an array to store the fields
	$scroll_fields = array();
	
	// Add the dimensions field
	$scroll_fields['direction'] = array(
		'id' => '_mtphr_dnt_scroll_direction',
		'type' => 'radio',
		'name' => __('Scroll direction', 'ditty-news-ticker'),
		'options' => array(
			'left' => __('Left', 'ditty-news-ticker'),
			'right' => __('Right', 'ditty-news-ticker'),
			'up' => __('Up', 'ditty-news-ticker'),
			'down' => __('Down', 'ditty-news-ticker')
		),
		'default' => 'left',
		'description' => __('Set the scroll direction of the ticker.', 'ditty-news-ticker'),
		'display' => 'inline'
	);

	// Add the dimensions field
	$scroll_fields['dimensions'] = array(
		'id' => '_mtphr_dnt_scroll_width',
		'type' => 'number',
		'name' => __('Tick dimensions', 'ditty-news-ticker'),
		'default' => 0,
		'before' => __('Width', 'ditty-news-ticker'),
		'description' => __('Override the auto dimensions with specific values.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_scroll_height' => array(
				'type' => 'number',
				'default' => 0,
				'before' => __('Height', 'ditty-news-ticker'),
			)
		)
	);
	
	// Add the spacing field
	$scroll_fields['scroller_padding'] = array(
		'id' => '_mtphr_dnt_scroll_padding',
		'type' => 'number',
		'name' => __('Scroller padding', 'ditty-news-ticker'),
		'default' => 0,
		'before' => __('Vertical padding', 'ditty-news-ticker'),
		'description' => __('Set the vertical spacing of the scrolling data.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_scroll_margin' => array(
				'type' => 'number',
				'default' => 0,
				'before' => __('Vertical margin', 'ditty-news-ticker')
			)
		)
	);
	
	// Add the slide speed field
	$scroll_fields['scroll_speed'] = array(
		'id' => '_mtphr_dnt_scroll_speed',
		'type' => 'number',
		'name' => __('Scroll speed', 'ditty-news-ticker'),
		'default' => 10,
		'description' => __('Set the speed of the scrolling data.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_scroll_pause' => array(
				'type' => 'checkbox',
				'label' => __('Pause on mouse over', 'ditty-news-ticker'),
			)
		)
	);
	
	// Add the slide spacing field
	$scroll_fields['tick_spacing'] = array(
		'id' => '_mtphr_dnt_scroll_tick_spacing',
		'type' => 'number',
		'name' => __('Tick spacing', 'ditty-news-ticker'),
		'default' => 40,
		'after' => __('Pixels', 'ditty-news-ticker'),
		'description' => __('Set the spacing between scrolling data.', 'ditty-news-ticker')
	);
	
	// Create the metabox
	$dnt_scroll_settings = array(
		'id' => 'mtphr_dnt_mode_scroll',
		'title' => __('Scroll Settings', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_dnt_mode_fields_scroll', $scroll_fields)
	);
	new MTPHR_DNT_MetaBoxer( $dnt_scroll_settings );
}




add_action( 'admin_init', 'mtphr_dnt_mode_metabox_rotate', 12 ); 
/**
 * Create the rotate metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_mode_metabox_rotate() {

	// Create an array to store the fields
	$rotate_fields = array();
	
	// Add the dimensions field
	$rotate_fields['type'] = array(
		'id' => '_mtphr_dnt_rotate_type',
		'type' => 'radio',
		'name' => __('Rotation type', 'ditty-news-ticker'),
		'options' => array(
			'fade' => __('Fade', 'ditty-news-ticker'),
			'slide_left' => __('Slide left', 'ditty-news-ticker'),
			'slide_right' => __('Slide right', 'ditty-news-ticker'),
			'slide_up' => __('Slide up', 'ditty-news-ticker'),
			'slide_down' => __('Slide down', 'ditty-news-ticker')
		),
		'default' => 'fade',
		'description' => __('Set the type of rotation for the ticker.', 'ditty-news-ticker'),
		'display' => 'inline',
		'append' => array(
			'_mtphr_dnt_rotate_directional_nav_reverse' => array(
				'type' => 'checkbox',
				'label' => __('Dynamic slide direction', 'ditty-news-ticker')
			)
		)
	);

	// Add the dimensions field
	$rotate_fields['dimensions'] = array(
		'id' => '_mtphr_dnt_rotate_height',
		'type' => 'number',
		'name' => __('Tick dimensions', 'ditty-news-ticker'),
		'default' => 0,
		'before' => __('Height', 'ditty-news-ticker'),
		'description' => __('Override the auto dimensions with specific values.', 'ditty-news-ticker')
	);
	
	// Add the spacing field
	$rotate_fields['rotate_padding'] = array(
		'id' => '_mtphr_dnt_rotate_padding',
		'type' => 'number',
		'name' => __('Rotater padding', 'ditty-news-ticker'),
		'default' => 0,
		'before' => __('Vertical padding', 'ditty-news-ticker'),
		'description' => __('Set the vertical spacing of the rotating data.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_rotate_margin' => array(
				'type' => 'number',
				'default' => 0,
				'before' => __('Vertical margin', 'ditty-news-ticker')
			)
		)
	);
	
	// Add the rotate delay field
	$rotate_fields['rotate_delay'] = array(
		'id' => '_mtphr_dnt_auto_rotate',
		'type' => 'checkbox',
		'name' => __('Auto rotate', 'ditty-news-ticker'),
		'label' => __('Enable', 'ditty-news-ticker'),
		'description' => __('Set the delay between rotations.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_rotate_delay' => array(
				'type' => 'number',
				'default' => 7,
				'after' => __('Seconds delay', 'ditty-news-ticker')
			),
			'_mtphr_dnt_rotate_pause' => array(
				'type' => 'checkbox',
				'label' => __('Pause on mouse over', 'ditty-news-ticker')
			)
		)
	);
	
	// Add the rotate speed field
	$rotate_fields['rotate_speed'] = array(
		'id' => '_mtphr_dnt_rotate_speed',
		'type' => 'number',
		'name' => __('Rotate speed', 'ditty-news-ticker'),
		'default' => 3,
		'after' => __('Tenths of a second', 'ditty-news-ticker'),
		'description' => __('Set the speed & easing of the rotation.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_rotate_ease' => array(
				'type' => 'select',
				'options' => array('linear','swing','jswing','easeInQuad','easeInCubic','easeInQuart','easeInQuint','easeInSine','easeInExpo','easeInCirc','easeInElastic','easeInBack','easeInBounce','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc','easeOutElastic','easeOutBack','easeOutBounce','easeInOutQuad','easeInOutCubic','easeInOutQuart','easeInOutQuint','easeInOutSine','easeInOutExpo','easeInOutCirc','easeInOutElastic','easeInOutBack','easeInOutBounce')
			)
		)
	);
	
	// Add the rotate navigation field
	$rotate_fields['rotate_directional_nav'] = array(
		'id' => '_mtphr_dnt_rotate_directional_nav',
		'type' => 'checkbox',
		'name' => __('Directional Navigation', 'ditty-news-ticker'),
		'label' => __('Enable', 'ditty-news-ticker'),
		'description' => __('Set the directional navigation options.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_rotate_directional_nav_hide' => array(
				'type' => 'checkbox',
				'label' => __('Autohide navigation', 'ditty-news-ticker')
			)
		)
	);
	
	// Add the rotate navigation field
	$rotate_fields['rotate_control_nav'] = array(
		'id' => '_mtphr_dnt_rotate_control_nav',
		'type' => 'checkbox',
		'name' => __('Control Navigation', 'ditty-news-ticker'),
		'label' => __('Enable', 'ditty-news-ticker'),
		'description' => __('Set the control navigation options.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_rotate_control_nav_type' => array(
				'type' => 'radio',
				'options' => array(
					'number' => __('Numbers', 'ditty-news-ticker'),
					'button' => __('Buttons', 'ditty-news-ticker')
				),
				'display' => 'inline',
				'default' => 'numbers'
			)
		)
	);
	
	// Create the metabox
	$dnt_rotate_settings = array(
		'id' => 'mtphr_dnt_mode_rotate',
		'title' => __('Rotate Settings', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_dnt_mode_fields_rotate', $rotate_fields)
	);
	new MTPHR_DNT_MetaBoxer( $dnt_rotate_settings );
}




add_action( 'admin_init', 'mtphr_dnt_mode_metabox_list', 12 ); 
/**
 * Create the list metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_mode_metabox_list() {

	// Create an array to store the fields
	$list_fields = array();
	
	// Add the spacing field
	$list_fields['list_padding'] = array(
		'id' => '_mtphr_dnt_list_padding',
		'type' => 'number',
		'name' => __('List padding', 'ditty-news-ticker'),
		'default' => 0,
		'before' => __('Vertical padding', 'ditty-news-ticker'),
		'description' => __('Set the vertical spacing of the list container.', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_list_margin' => array(
				'type' => 'number',
				'default' => 0,
				'before' => __('Vertical margin', 'ditty-news-ticker')
			)
		)
	);
	
	// Add the list spacing field
	$list_fields['tick_spacing'] = array(
		'id' => '_mtphr_dnt_list_tick_spacing',
		'type' => 'number',
		'name' => __('Tick spacing', 'ditty-news-ticker'),
		'default' => 10,
		'after' => __('Pixels', 'ditty-news-ticker'),
		'description' => __('Set the spacing between ticks.', 'ditty-news-ticker')
	);
	
	// Create the metabox
	$dnt_list_settings = array(
		'id' => 'mtphr_dnt_mode_list',
		'title' => __('List Settings', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_dnt_mode_fields_list', $list_fields)
	);
	new MTPHR_DNT_MetaBoxer( $dnt_list_settings );
}




add_action( 'admin_init', 'mtphr_dnt_global_settings', 13 ); 
/**
 * Create the display metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_global_settings() {

	// Create an array to store the fields
	$global_fields = array();
	
	// Add the title field
	$global_fields['title'] = array(
		'id' => '_mtphr_dnt_title',
		'type' => 'checkbox',
		'label' => __('Display Title', 'ditty-news-ticker'),
	);
	
	// Add the title field
	$global_fields['inline_title'] = array(
		'id' => '_mtphr_dnt_inline_title',
		'type' => 'checkbox',
		'label' => __('Inline Title', 'ditty-news-ticker')
	);
	
	// Add the title field
	/*
	$global_fields['styled'] = array(
		'id' => '_mtphr_dnt_styled',
		'type' => 'checkbox',
		'label' => __('Enable Default CSS Styles', 'ditty-news-ticker'),
	);
	*/
		
	// Create the metabox
	$dnt_global = array(
		'id' => 'mtphr_dnt_global_settings',
		'title' => __('Global Settings', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'side',
		'priority' => 'default',
		'fields' => apply_filters('mtphr_dnt_display_fields', $global_fields)
	);
	new MTPHR_DNT_MetaBoxer( $dnt_global );
}




add_action( 'admin_init', 'mtphr_dnt_display_metabox', 13 ); 
/**
 * Create the display metabox.
 *
 * @since 1.0.0
 */
function mtphr_dnt_display_metabox() {

	// Create an array to store the fields
	$display_fields = array();
	
	// Add the shortcode field
	$display_fields['shortcode'] = array(
		'id' => '_mtphr_dnt_shortcode',
		'type' => 'code',
		'name' => __('Shortcode', 'ditty-news-ticker'),
		'button' => __('Select Shortcode', 'ditty-news-ticker'),
		'description' => __('Use this shortcode to insert the ticker into a post/page.', 'ditty-news-ticker'),
	);
	
	// Add the function field
	$display_fields['function'] = array(
		'id' => '_mtphr_dnt_function',
		'type' => 'code',
		'name' => __('Direct Function', 'ditty-news-ticker'),
		'button' => __('Select Function', 'ditty-news-ticker'),
		'description' => __('Place this code directly into your theme to display the ticker.', 'ditty-news-ticker'),
	);
	
	// Create the metabox
	$dnt_display = array(
		'id' => 'mtphr_dnt_display',
		'title' => __('Ticker Display', 'ditty-news-ticker'),
		'page' => array( 'ditty_news_ticker' ),
		'context' => 'side',
		'priority' => 'default',
		'fields' => apply_filters('mtphr_dnt_display_fields', $display_fields)
	);
	new MTPHR_DNT_MetaBoxer( $dnt_display );
}








