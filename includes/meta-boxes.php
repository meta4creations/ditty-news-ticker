<?php
/**
 * Create the meta boxes
 *
 * @package Ditty News Ticker
 */


//add_action( 'admin_init', 'mtphr_dnt_metabox_types', 9 );
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
						'default' => '<a href="http://dittynewsticker.com" target="_blank"><strong>'.__('View all types', 'ditty-news-ticker').'</strong></a>'
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
 * @since 1.2.2
 */
function mtphr_dnt_metabox_type_default() {

	$tick_type = 'textarea';
	$settings = get_option( 'mtphr_dnt_general_settings' );
	if( $settings && isset($settings['wysiwyg']) ) {
		$tick_type = 'wysiwyg';
	}

	// Create an array to store the default item structure
	$tick_structure = array(
		'tick' => array(
			'header' => __('Ticker text', 'ditty-news-ticker'),
			'width' => '60%',
			'type' => $tick_type,
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
		),
		'nofollow' => array(
			'header' => __('NF', 'ditty-news-ticker'),
			'type' => 'checkbox'
		)
	);

	// Create an array to store the fields
	$default_fields = array();

	// Add the items field
	$default_fields['line_breaks'] = array(
		'id' => '_mtphr_dnt_line_breaks',
		'type' => 'checkbox',
		'label' => __('Force line breaks on carriage returns', 'ditty-news-ticker'),
	);

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




//add_action( 'admin_init', 'mtphr_dnt_metabox_modes', 11 );
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
		'default' => 'scroll'
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
 * @since 1.1.8
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
		'display' => 'inline',
		'append' => array(
			'_mtphr_dnt_scroll_init' => array(
				'type' => 'checkbox',
				'label' => __('Show first tick on init', 'ditty-news-ticker'),
			)
		)
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
		'name' => __('Rotator padding', 'ditty-news-ticker'),
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
		'name' => __('Directional navigation', 'ditty-news-ticker'),
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
		'name' => __('Control navigation', 'ditty-news-ticker'),
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
				'default' => 'number'
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
 * @since 1.1.8
 */
function mtphr_dnt_global_settings() {

	// Create an array to store the fields
	$global_fields = array();

	// Add the title field
	$global_fields['title'] = array(
		'id' => '_mtphr_dnt_title',
		'type' => 'checkbox',
		'label' => __('Display title', 'ditty-news-ticker'),
		'append' => array(
			'_mtphr_dnt_inline_title' => array(
				'type' => 'checkbox',
				'label' => __('Inline title', 'ditty-news-ticker')
			)
		)
	);

	// Add the randomize field
	$global_fields['shuffle'] = array(
		'id' => '_mtphr_dnt_shuffle',
		'type' => 'checkbox',
		'label' => __('Randomly shuffle the ticks', 'ditty-news-ticker')
	);

	// Add the title field
	$global_fields['ticker_width'] = array(
		'id' => '_mtphr_dnt_ticker_width',
		'before' => __('Ticker width <em>(optional)</em>', 'ditty-news-ticker'),
		'type' => 'number',
		'after' => 'px<br/>'.'<small><em>'.__('Override the auto width a with specific value.', 'ditty-news-ticker').'</em></small>'
	);

	// Add the offset field
	$global_fields['offset'] = array(
		'id' => '_mtphr_dnt_offset',
		'type' => 'number',
		'default' => 20,
		'before' => __('Offset ticks', 'ditty-news-ticker'),
		'after' => __('px from the edge', 'ditty-news-ticker').'<br/>'.'<small><em>'.__('The amount of pixels ticks should start and end off the screen.', 'ditty-news-ticker').'</em></small>'
	);

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
		'name' => __('Direct function', 'ditty-news-ticker'),
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



/* --------------------------------------------------------- */
/* !Add the main ticker options - 1.3.4 */
/* --------------------------------------------------------- */

function mtphr_dnt_option_buttons() {

	global $post, $typenow;
	
	if( $typenow == 'ditty_news_ticker' ) {
	
		$types = mtphr_dnt_types_array();
		$type = get_post_meta( $post->ID, '_mtphr_dnt_type', true );
		$type = ( $type != '' ) ? $type : 'default';
		
		$modes = mtphr_dnt_modes_array();
		$mode = get_post_meta( $post->ID, '_mtphr_dnt_mode', true );
		$type = ( $type != '' ) ? $type : 'scroll';
		
		echo '<table id="ditty-news-ticker-settings-select">';
			echo '<tr>';
				echo '<td id="ditty-news-ticker-type-select">';
					echo '<div class="wrapper">';
						echo '<h2>'.__('Ticker Type', 'ditty-news-ticker').' <a href="http://dittynewsticker.com" target="_blank"><small>'.__('View all types', 'ditty-news-ticker').'</small></a></h2>';
						echo '<p>'.__('Select the type of ticker you\'d like to use', 'ditty-news-ticker').'</p>';
						echo '<div class="mtphr-dnt-metabox-toggle">';
							echo '<input type="hidden" name="_mtphr_dnt_type" value="'.$type.'" />';
							foreach( $types as $i=>$t ) {
								
								$value = '';
								$button = $t['button'];
								$metaboxes = $t['metaboxes'];
								$metabox_list = join( ',', $metaboxes );
						
								// Create a button
								$selected = ( $type == $i ) ? ' button-primary' : '';
								echo '<a href="'.$i.'" metaboxes="'.$metabox_list.'" class="mtphr-dnt-metaboxer-metabox-toggle button'.$selected.'">'.$button.'</a>&nbsp;';
							}
						echo '</div>';
					echo '</div>';
				echo '</td>';
					
				echo '<td id="ditty-news-ticker-mode-select">';
					echo '<div class="wrapper">';
						echo '<h2>'.__('Ticker Mode', 'ditty-news-ticker').'</h2>';
						echo '<p>'.__('Select the mode of the ticker', 'ditty-news-ticker').'</p>';
						echo '<div class="mtphr-dnt-metabox-toggle">';
							echo '<input type="hidden" name="_mtphr_dnt_mode" value="'.$mode.'" />';
							foreach( $modes as $i=>$m ) {
								
								$value = '';
								$button = $m['button'];
								$metaboxes = $m['metaboxes'];
								$metabox_list = join( ',', $metaboxes );
						
								// Create a button
								$selected = ( $mode == $i ) ? ' button-primary' : '';
								echo '<a href="'.$i.'" metaboxes="'.$metabox_list.'" class="mtphr-dnt-metaboxer-metabox-toggle button'.$selected.'">'.$button.'</a>&nbsp;';
							}
						echo '</div>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	}
}
add_action( 'edit_form_after_title', 'mtphr_dnt_option_buttons' );





/* --------------------------------------------------------- */
/* !Add the mixed type metabox - 1.3.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_mixed_metabox() {

	add_meta_box( 'mtphr_dnt_mixed_metabox', __('Mixed Ticker Items', 'ditty-news-ticker'), 'mtphr_dnt_mixed_render_metabox', 'ditty_news_ticker', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_mixed_metabox' );



/* --------------------------------------------------------- */
/* !Render the mixed type metabox - 1.3.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_mixed_render_metabox() {

	global $post;

	$ticks = get_post_meta( $post->ID, '_mtphr_dnt_mixed_ticks', true );
	$types = mtphr_dnt_types_array();
	unset($types['mixed']);
	
	//echo '<pre>';print_r($ticks);echo '</pre>';

	echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	echo '<a href="#" id="mtphr-dnt-mixed-add-tick" class="button-primary">'.__('Add Tick', 'ditty-news-ticker').'</a><span class="spinner mtphr-dnt-add-spinner"></span>';
	
	echo '<table class="mtphr-dnt-table">';
	
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Tick selection', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Select the ticks you would like to display by choosing the tick type and the offset position of the selected feed', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<table class="mtphr-dnt-list mtphr-dnt-mixed-list" style="width:auto;">';
					if( is_array($ticks) && count($ticks) > 0 ) {
						foreach( $ticks as $i=>$tick ) {
							mtphr_dnt_render_mixed_tick( $types, $tick, $i );
						}
					} else {
						mtphr_dnt_render_mixed_tick( $types );
					}
				echo '</table>';
			echo '</td>';
		echo '</tr>';
		
	echo '</table>';
}

function mtphr_dnt_render_mixed_tick( $types, $tick=false, $i=false ) {
	
	$tick_type = ( isset($tick) && isset($tick['type']) ) ? $tick['type'] : '';
	$tick_offset = ( isset($tick) && isset($tick['offset']) ) ? $tick['offset'] : 0;
	
	echo '<tr class="mtphr-dnt-list-item">';
		echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
	  echo '<td class="mtphr-dnt-mixed-type">';
	  	echo '<label style="margin-right:10px;">'.__('Type:', 'ditty-news-ticker').' ';
				echo '<select name="_mtphr_dnt_mixed_ticks[type]" key="type">';
					echo '<option value="">-- '.__('Select Tick Type', 'ditty-news-ticker').' --</option>';
					foreach( $types as $i=>$type ) {
						echo '<option value="'.$i.'" '.selected($i, $tick_type, false).'>'.$type['button'].'</option>';
					}
				echo '</select>';
			echo '</label>';
		echo '</td>';
		echo '<td>';
			echo '<label>'.__('Offset:', 'ditty-news-ticker').' ';
				echo '<input type="number" name="_mtphr_dnt_mixed_ticks[offset]" key="offset" value="'.$tick_offset.'" />';
			echo '</label>';
		echo '</td>';
		echo '<td class="mtphr-dnt-list-delete"><a href="#"></a></td>';
	echo '</tr>';
}



/* --------------------------------------------------------- */
/* !Save the custom meta - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_dnt_metabox_save( $post_id ) {

	global $post;

	// verify nonce
	if (!isset($_POST['mtphr_dnt_nonce']) || !wp_verify_nonce($_POST['mtphr_dnt_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) return $post_id;

	// don't save if only a revision
	if ( isset($post->post_type) && $post->post_type == 'revision' ) return $post_id;

	// check permissions
	if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	// Update the type & mode
	if( isset($_POST['_mtphr_dnt_type']) ) {
	
		$type =  isset($_POST['_mtphr_dnt_type']) ? sanitize_text_field($_POST['_mtphr_dnt_type']) : 'default';
		$mode =  isset($_POST['_mtphr_dnt_mode']) ? sanitize_text_field($_POST['_mtphr_dnt_mode']) : 'scroll';
		
		update_post_meta( $post_id, '_mtphr_dnt_type', $type );
		update_post_meta( $post_id, '_mtphr_dnt_mode', $mode );
	}
	
	// Delete & save the mixed ticks
	delete_post_meta( $post_id, '_mtphr_dnt_mixed_ticks' );
	if( isset($_POST['_mtphr_dnt_mixed_ticks']) && count($_POST['_mtphr_dnt_mixed_ticks']) > 0 ) {
	
		$sanitized_ticks = array();
		foreach( $_POST['_mtphr_dnt_mixed_ticks'] as $tick ) {
			$sanitized_ticks[] = array(
				'type' => $tick['type'],
				'offset' => intval($tick['offset'])
			);
		}
		update_post_meta( $post_id, '_mtphr_dnt_mixed_ticks', $sanitized_ticks );
	}
}
add_action( 'save_post', 'mtphr_dnt_metabox_save' );









