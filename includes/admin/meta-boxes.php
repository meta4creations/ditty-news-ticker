<?php

/* --------------------------------------------------------- */
/* !Add the ticker display code - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_display_code() {

	global $post, $typenow;
	
	if( $typenow == 'ditty_news_ticker' ) {
		
		echo '<div id="ditty-news-ticker-code-copy">';
			echo '<table>';
				echo '<tr>';
					echo '<td id="ditty-news-ticker-shortcode-copy">';
						echo '<div class="wrapper">';
							echo '<h3>'.__('Shortcode', 'ditty-news-ticker').'</h3>';
							echo '<p>'.__('Copy and paste this shortcode into a page or post to display the ticker within the post content.', 'ditty-news-ticker').'</p>';
							echo '<pre><p>[ditty_news_ticker id="'.$post->ID.'"]</p></pre>';
							echo '<a href="#" class="button mtphr-dnt-code-select">'.__('Select Shortcode', 'ditty-news-ticker').'</a>';
						echo '</div>';
					echo '</td>';
						
					echo '<td id="ditty-news-ticker-function-copy">';
						echo '<div class="wrapper">';
							echo '<h3>'.__('Direct Function', 'ditty-news-ticker').'</h3>';
							echo '<p>'.__('Copy and paste this code directly into one of your theme files to display the ticker any where you want on your site.', 'ditty-news-ticker').'</p>';
	
							echo '<pre><p>&lt;?php if(function_exists(\'ditty_news_ticker\')){ditty_news_ticker('.$post->ID.');} ?&gt;</p></pre>';
							echo '<a href="#" class="button mtphr-dnt-code-select">'.__('Select Function', 'ditty-news-ticker').'</a>';
						echo '</div>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		echo '</div>';
	}
}
add_action( 'edit_form_after_title', 'mtphr_dnt_display_code' );


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
		
		echo '<div id="ditty-news-ticker-settings-select">';
			echo '<table>';
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
		echo '</div>';
	}
}
add_action( 'edit_form_after_title', 'mtphr_dnt_option_buttons' );




/* --------------------------------------------------------- */
/* !Add the default type metabox - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_default_metabox() {

	add_meta_box( 'mtphr_dnt_default_metabox', __('Default Ticker Items', 'ditty-news-ticker'), 'mtphr_dnt_default_render_metabox', 'ditty_news_ticker', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_default_metabox', 1 );


/* --------------------------------------------------------- */
/* !Add the mixed type metabox - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_mixed_metabox() {

	add_meta_box( 'mtphr_dnt_mixed_metabox', __('Mixed Ticker Items', 'ditty-news-ticker'), 'mtphr_dnt_mixed_render_metabox', 'ditty_news_ticker', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_mixed_metabox', 1 );


/* --------------------------------------------------------- */
/* !Add the scroll settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_scroll_settings_metabox() {

	add_meta_box( 'mtphr_dnt_scroll_settings_metabox', __('Scroll Settings', 'ditty-news-ticker'), 'mtphr_dnt_scroll_settings_render_metabox', 'ditty_news_ticker', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_scroll_settings_metabox' );


/* --------------------------------------------------------- */
/* !Add the rotate settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_rotate_settings_metabox() {

	add_meta_box( 'mtphr_dnt_rotate_settings_metabox', __('Rotate Settings', 'ditty-news-ticker'), 'mtphr_dnt_rotate_settings_render_metabox', 'ditty_news_ticker', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_rotate_settings_metabox' );


/* --------------------------------------------------------- */
/* !Add the list settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_list_settings_metabox() {

	add_meta_box( 'mtphr_dnt_list_settings_metabox', __('List Settings', 'ditty-news-ticker'), 'mtphr_dnt_list_settings_render_metabox', 'ditty_news_ticker', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_list_settings_metabox' );


/* --------------------------------------------------------- */
/* !Add the global settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_global_settings_metabox() {

	add_meta_box( 'mtphr_dnt_global_settings_metabox', __('Global Settings', 'ditty-news-ticker'), 'mtphr_dnt_global_settings_render_metabox', 'ditty_news_ticker', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_global_settings_metabox' );






/* --------------------------------------------------------- */
/* !Render the default type metabox - 1.4.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_default_render_metabox') ) {
function mtphr_dnt_default_render_metabox() {

	global $post;
	
	$settings = mtphr_dnt_general_settings();

	$line_breaks = get_post_meta( $post->ID, '_mtphr_dnt_line_breaks', true );
	$ticks = get_post_meta( $post->ID, '_mtphr_dnt_ticks', true );

	echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	echo '<table class="mtphr-dnt-table">';
	
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Line breaks', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Force line breaks on carriage returns', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_line_breaks" value="1" '.checked('1', $line_breaks, false).' />'.__('Force line breaks', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Ticks', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Add an unlimited number of ticks to your ticker', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<table class="mtphr-dnt-list mtphr-dnt-default-list mtphr-dnt-advanced-list">';
					echo '<tr>';
						echo '<th class="mtphr-dnt-list-handle"></th>';
						
						$label = __('Ticker text', 'ditty-news-ticker');
						if( $settings['wysiwyg'] == '1' ) {
							$label .= '<div class="mtphr-dnt-notice">'.__('<strong>Notice:</strong> When using the WYSIWYG editor you must save your ticker after adding or re-arranging ticks.', 'ditty-news-ticker').'</div>';
						}
						echo '<th>'.$label.'</th>';
						echo '<th>'.__('Link', 'ditty-news-ticker').'</th>';
						echo '<th class="mtphr-dnt-default-target">'.__('Target', 'ditty-news-ticker').'</th>';
						echo '<th class="mtphr-dnt-default-nofollow">'.__('NF', 'ditty-news-ticker').'</th>';
						echo '<th class="mtphr-dnt-list-delete"></th>';
						echo '<th class="mtphr-dnt-list-add"></th>';
					echo '</tr>';
					if( is_array($ticks) && count($ticks) > 0 ) {
						foreach( $ticks as $i=>$tick ) {
							mtphr_dnt_render_default_tick( $settings, $tick );
						}
					} else {
						mtphr_dnt_render_default_tick( $settings );
					}
				echo '</table>';
			echo '</td>';
		echo '</tr>';
		
	echo '</table>';
}
}

if( !function_exists('mtphr_dnt_render_default_tick') ) {
function mtphr_dnt_render_default_tick( $settings, $tick=false ) {
	
	$text = ( isset($tick) && isset($tick['tick']) ) ? $tick['tick'] : '';
	$link = ( isset($tick) && isset($tick['link']) ) ? $tick['link'] : '';
	$target = ( isset($tick) && isset($tick['target']) ) ? $tick['target'] : '';
	$nofollow = ( isset($tick) && isset($tick['nofollow']) ) ? $tick['nofollow'] : '';
	
	echo '<tr class="mtphr-dnt-list-item">';
		echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
		echo '<td class="mtphr-dnt-default-tick mtphr-dnt-wysiwyg-container" data-name="_mtphr_dnt_ticks" data-key="tick">';
			if( $settings['wysiwyg'] == '1' ) {
				$editor_settings = array();
				$editor_settings['media_buttons'] = true;
				$editor_settings['textarea_rows'] = 6;
				$editor_settings['editor_class'] = 'mtphr-dnt-wysiwyg';
				$editor_settings['textarea_name'] = '_mtphr_dnt_ticks[tick]';
				wp_editor( $text, 'mtphrdnttick'.uniqid(), $editor_settings );
			} else {
				echo '<textarea name="_mtphr_dnt_ticks[tick]" data-name="_mtphr_dnt_ticks" data-key="tick">'.$text.'</textarea>';
			}
		echo '</td>';
		echo '<td class="mtphr-dnt-default-link">';
			echo '<input type="text" name="_mtphr_dnt_ticks[link]" data-name="_mtphr_dnt_ticks" data-key="link" value="'.$link.'" />';
		echo '</td>';
	  echo '<td class="mtphr-dnt-default-target">';
			echo '<select name="_mtphr_dnt_ticks[target]" data-name="_mtphr_dnt_ticks" data-key="target">';
				echo '<option value="_self" '.selected('_self', $target, false).'>_self</option>';
				echo '<option value="_blank" '.selected('_blank', $target, false).'>_blank</option>';
			echo '</select>';
		echo '</td>';
		echo '<td class="mtphr-dnt-default-nofollow">';
			echo '<input type="checkbox" name="_mtphr_dnt_ticks[nofollow]" data-name="_mtphr_dnt_ticks" data-key="nofollow" value="1" '.checked('1', $nofollow, false).' />';
		echo '</td>';
		echo '<td class="mtphr-dnt-list-delete"><a href="#"></a></td>';
		echo '<td class="mtphr-dnt-list-add"><a href="#"></a></td>';
	echo '</tr>';
}
}



/* --------------------------------------------------------- */
/* !Render the mixed type metabox - 1.4.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_mixed_render_metabox') ) {
function mtphr_dnt_mixed_render_metabox() {

	global $post;

	$ticks = get_post_meta( $post->ID, '_mtphr_dnt_mixed_ticks', true );
	$types = mtphr_dnt_types_array();
	unset($types['mixed']);

	echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	echo '<table class="mtphr-dnt-table">';
	
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Tick selection', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Select the ticks you would like to display by choosing the tick type and the offset position of the selected feed', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<table class="mtphr-dnt-list mtphr-dnt-mixed-list mtphr-dnt-advanced-list" style="width:auto;">';
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
}

if( !function_exists('mtphr_dnt_render_mixed_tick') ) {
function mtphr_dnt_render_mixed_tick( $types, $tick=false, $i=false ) {
	
	$tick_type = ( isset($tick) && isset($tick['type']) ) ? $tick['type'] : '';
	$tick_offset = ( isset($tick) && isset($tick['offset']) ) ? $tick['offset'] : 0;
	
	echo '<tr class="mtphr-dnt-list-item">';
		echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
	  echo '<td class="mtphr-dnt-mixed-type">';
	  	echo '<label style="margin-right:10px;">'.__('Type:', 'ditty-news-ticker').' ';
				echo '<select name="_mtphr_dnt_mixed_ticks[type]" data-name="_mtphr_dnt_mixed_ticks" data-key="type">';
					echo '<option value="">-- '.__('Select Tick Type', 'ditty-news-ticker').' --</option>';
					foreach( $types as $i=>$type ) {
						echo '<option value="'.$i.'" '.selected($i, $tick_type, false).'>'.$type['button'].'</option>';
					}
				echo '</select>';
			echo '</label>';
		echo '</td>';
		echo '<td>';
			echo '<label>'.__('Offset:', 'ditty-news-ticker').' ';
				echo '<input type="number" name="_mtphr_dnt_mixed_ticks[offset]" data-name="_mtphr_dnt_mixed_ticks" data-key="offset" value="'.$tick_offset.'" />';
			echo '</label>';
		echo '</td>';
		echo '<td class="mtphr-dnt-list-delete"><a href="#"></a></td>';
		echo '<td class="mtphr-dnt-list-add"><a href="#"></a></td>';
	echo '</tr>';
}
}


/* --------------------------------------------------------- */
/* !Render the scroll settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_scroll_settings_render_metabox') ) {
function mtphr_dnt_scroll_settings_render_metabox() {

	global $post;
	
	$defaults = array(
		'direction' => 'left',
		'init' => '',
		'width' => 0,
		'height' => 0,
		'padding' => 0,
		'margin' => 0,
		'speed' => 10,
		'pause' => '',
		'spacing' => 40
	);
	
	$values = array(
		'direction' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_direction', true ),
		'init' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_init', true ),
		'width' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_width', true ),
		'height' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_height', true ),
		'padding' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_padding', true ),
		'margin' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_margin', true ),
		'speed' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_speed', true ),
		'pause' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_pause', true ),
		'spacing' => get_post_meta( $post->ID, '_mtphr_dnt_scroll_tick_spacing', true )
	);
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	$values = wp_parse_args( $values, $defaults );

	echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	echo '<table class="mtphr-dnt-table">';
	
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Scroll direction', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the scroll direction of the ticker', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="radio" name="_mtphr_dnt_scroll_direction" value="left" '.checked('left', $values['direction'], false).' /> '.__('Left', 'ditty-news-ticker').'</label>';
				echo '<label><input type="radio" name="_mtphr_dnt_scroll_direction" value="right" '.checked('right', $values['direction'], false).' /> '.__('Right', 'ditty-news-ticker').'</label>';
				echo '<label><input type="radio" name="_mtphr_dnt_scroll_direction" value="up" '.checked('up', $values['direction'], false).' /> '.__('Up', 'ditty-news-ticker').'</label>';
				echo '<label style="margin-right:20px;"><input type="radio" name="_mtphr_dnt_scroll_direction" value="down" '.checked('down', $values['direction'], false).' /> '.__('Down', 'ditty-news-ticker').'</label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_scroll_init" value="1" '.checked('1', $values['init'], false).' /> '.__('Show first tick on init', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Tick dimensions', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Override the auto dimensions with specific values', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label>'.__('Width', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_scroll_width" value="'.$values['width'].'" /></label>';
				echo '<label>'.__('Height', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_scroll_height" value="'.$values['height'].'" /></label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Scroller padding', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the vertical spacing of the scrolling data', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label>'.__('Vertical padding', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_scroll_padding" value="'.$values['padding'].'" /></label>';
				echo '<label>'.__('Vertical margin', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_scroll_margin" value="'.$values['margin'].'" /></label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Scroll speed', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the speed of the scrolling data', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="number" name="_mtphr_dnt_scroll_speed" value="'.$values['speed'].'" /></label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_scroll_pause" value="1" '.checked('1', $values['pause'], false).' /> '.__('Pause on mouse over', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Tick spacing', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the spacing between scrolling data', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="number" name="_mtphr_dnt_scroll_tick_spacing" value="'.$values['spacing'].'" /> '.__('Pixels', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
	echo '</table>';
}
}


/* --------------------------------------------------------- */
/* !Render the rotate settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_rotate_settings_render_metabox') ) {
function mtphr_dnt_rotate_settings_render_metabox() {

	global $post;
	
	$defaults = array(
		'type' => 'fade',
		'reverse' => '',
		'height' => 0,
		'padding' => 0,
		'margin' => 0,
		'auto' => '',
		'delay' => 7,
		'pause' => '',
		'speed' => 3,
		'ease' => 'linear',
		'directional_nav' => '',
		'directional_nav_hide' => '',
		'control_nav' => '',
		'control_nav_type' => 'number'
	);
	
	$values = array(
		'type' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_type', true ),
		'reverse' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_directional_nav_reverse', true ),
		'height' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_height', true ),
		'padding' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_padding', true ),
		'margin' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_margin', true ),
		'auto' => get_post_meta( $post->ID, '_mtphr_dnt_auto_rotate', true ),
		'delay' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_delay', true ),
		'pause' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_pause', true ),
		'speed' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_speed', true ),
		'ease' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_ease', true ),
		'directional_nav' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_directional_nav', true ),
		'directional_nav_hide' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_directional_nav_hide', true ),
		'control_nav' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_control_nav', true ),
		'control_nav_type' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_control_nav_type', true )
	);
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	$values = wp_parse_args( $values, $defaults );

	echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	echo '<table class="mtphr-dnt-table">';
	
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Rotation type', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the type of rotation for the ticker', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="radio" name="_mtphr_dnt_rotate_type" value="fade" '.checked('fade', $values['type'], false).' /> '.__('Fade', 'ditty-news-ticker').'</label>';
				echo '<label><input type="radio" name="_mtphr_dnt_rotate_type" value="slide_left" '.checked('slide_left', $values['type'], false).' /> '.__('Slide left', 'ditty-news-ticker').'</label>';
				echo '<label><input type="radio" name="_mtphr_dnt_rotate_type" value="slide_right" '.checked('slide_right', $values['type'], false).' /> '.__('Slide right', 'ditty-news-ticker').'</label>';
				echo '<label><input type="radio" name="_mtphr_dnt_rotate_type" value="slide_up" '.checked('slide_up', $values['type'], false).' /> '.__('Slide up', 'ditty-news-ticker').'</label>';
				echo '<label style="margin-right:20px;"><input type="radio" name="_mtphr_dnt_rotate_type" value="slide_down" '.checked('slide_down', $values['type'], false).' /> '.__('Slide down', 'ditty-news-ticker').'</label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_rotate_directional_nav_reverse" value="1" '.checked('1', $values['reverse'], false).' /> '.__('Dynamic slide direction', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Tick dimensions', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Override the auto dimensions with specific values', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label>'.__('Height', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_rotate_height" value="'.$values['height'].'" /></label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Rotator padding', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the vertical spacing of the rotating data', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label>'.__('Vertical padding', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_rotate_padding" value="'.$values['padding'].'" /></label>';
				echo '<label>'.__('Vertical margin', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_rotate_margin" value="'.$values['margin'].'" /></label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Auto rotate', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the delay between rotations', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_auto_rotate" value="1" '.checked('1', $values['auto'], false).' /> '.__('Enable', 'ditty-news-ticker').'</label>';
				echo '<label style="margin-right:20px;"><input type="number" name="_mtphr_dnt_rotate_delay" value="'.$values['delay'].'" /> '.__('Seconds delay', 'ditty-news-ticker').'</label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_rotate_pause" value="1" '.checked('1', $values['pause'], false).' /> '.__('Pause on mouse over', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Rotate speed', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the speed & easing of the rotation', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="number" name="_mtphr_dnt_rotate_speed" value="'.$values['speed'].'" /> '.__('Tenths of a second', 'ditty-news-ticker').'</label>';
				echo '<label><select name="_mtphr_dnt_rotate_ease">';
					$eases = array('linear','swing','jswing','easeInQuad','easeInCubic','easeInQuart','easeInQuint','easeInSine','easeInExpo','easeInCirc','easeInElastic','easeInBack','easeInBounce','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc','easeOutElastic','easeOutBack','easeOutBounce','easeInOutQuad','easeInOutCubic','easeInOutQuart','easeInOutQuint','easeInOutSine','easeInOutExpo','easeInOutCirc','easeInOutElastic','easeInOutBack','easeInOutBounce');
					foreach( $eases as $ease ) {
						echo '<option value="'.$ease.'" '.selected($ease, $values['ease'], false).'>'.$ease.'</option>';	
					}
				echo '</select></label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Directional navigation', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the directional navigation options', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_rotate_directional_nav" value="1" '.checked('1', $values['directional_nav'], false).' /> '.__('Enable', 'ditty-news-ticker').'</label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_rotate_directional_nav_hide" value="1" '.checked('1', $values['directional_nav_hide'], false).' /> '.__('Autohide navigation', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Control navigation', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the control navigation options', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_rotate_control_nav" value="1" '.checked('1', $values['control_nav'], false).' /> '.__('Enable', 'ditty-news-ticker').'</label>';
				echo '<label><input type="radio" name="_mtphr_dnt_rotate_control_nav_type" value="number" '.checked('number', $values['control_nav_type'], false).' /> '.__('Numbers', 'ditty-news-ticker').'</label>';
				echo '<label><input type="radio" name="_mtphr_dnt_rotate_control_nav_type" value="button" '.checked('button', $values['control_nav_type'], false).' /> '.__('Buttons', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
	echo '</table>';
}
}


/* --------------------------------------------------------- */
/* !Render the list settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_list_settings_render_metabox') ) {
function mtphr_dnt_list_settings_render_metabox() {

	global $post;
	
	$defaults = array(
		'padding' => 0,
		'margin' => 0,
		'spacing' => 10,
		'paging' => '',
		'count' => 0,
		'prev_next' => '',
		'prev_text' => __('« Previous', 'ditty-news-ticker'),
		'next_text' => __('Next »', 'ditty-news-ticker'),
	);
	
	$values = array(
		'padding' => get_post_meta( $post->ID, '_mtphr_dnt_list_padding', true ),
		'margin' => get_post_meta( $post->ID, '_mtphr_dnt_list_margin', true ),
		'spacing' => get_post_meta( $post->ID, '_mtphr_dnt_list_tick_spacing', true ),
		'paging' => get_post_meta( $post->ID, '_mtphr_dnt_list_tick_paging', true ),
		'count' => get_post_meta( $post->ID, '_mtphr_dnt_list_tick_count', true ),
		'prev_next' => get_post_meta( $post->ID, '_mtphr_dnt_list_tick_prev_next', true ),
		'prev_text' => get_post_meta( $post->ID, '_mtphr_dnt_list_tick_prev_text', true ),
		'next_text' => get_post_meta( $post->ID, '_mtphr_dnt_list_tick_next_text', true ),
	);
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	$values = wp_parse_args( $values, $defaults );

	echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	echo '<table class="mtphr-dnt-table">';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('List padding', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the vertical spacing of the list container', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label>'.__('Vertical padding', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_list_padding" value="'.$values['padding'].'" /></label>';
				echo '<label>'.__('Vertical margin', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_list_margin" value="'.$values['margin'].'" /></label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('Tick spacing', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Set the spacing between ticks', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="number" name="_mtphr_dnt_list_tick_spacing" value="'.$values['spacing'].'" /> '.__('Pixels', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-label">';
				echo '<label>'.__('List paging', 'ditty-news-ticker').'</label>';
				echo '<small>'.__('Break the list up into pages', 'ditty-news-ticker').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_list_tick_paging" value="1" '.checked('1', $values['paging'], false).' /> '.__('Enable', 'ditty-news-ticker').'</label>';
				echo '<label><input type="number" name="_mtphr_dnt_list_tick_count" value="'.$values['count'].'" /> '.__('Ticks per page', 'ditty-news-ticker').'</label>';
				echo '<br/>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_list_tick_prev_next" value="1" '.checked('1', $values['prev_next'], false).' /> '.__('Enable previous & next links', 'ditty-news-ticker').'</label>';
				echo '<br/>';
				echo '<label style="margin-right:10px;"><input type="text" name="_mtphr_dnt_list_tick_prev_text" value="'.$values['prev_text'].'" size="20" placeholder="'.__('Previous text', 'ditty-news-ticker').'" /></label>';
				echo '<label><input type="text" name="_mtphr_dnt_list_tick_next_text" value="'.$values['next_text'].'" size="20" placeholder="'.__('Next text', 'ditty-news-ticker').'" /></label>';
			echo '</td>';
		echo '</tr>';
		
	echo '</table>';
}
}


/* --------------------------------------------------------- */
/* !Render the global settings metabox - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_global_settings_render_metabox') ) {
function mtphr_dnt_global_settings_render_metabox() {

	global $post;
	
	$defaults = array(
		'title' => '',
		'inline_title' => '',
		'shuffle' => '',
		'width' => 0,
		'offset' => 20,
		'grid' => '',
		'grid_empty_rows' => '',
		'grid_equal_width' => '',
		'grid_cols' => 2,
		'grid_rows' => 2,
		'grid_padding' => 5,
	);
	
	$values = array(
		'title' => get_post_meta( $post->ID, '_mtphr_dnt_title', true ),
		'inline_title' => get_post_meta( $post->ID, '_mtphr_dnt_inline_title', true ),
		'shuffle' => get_post_meta( $post->ID, '_mtphr_dnt_shuffle', true ),
		'width' => get_post_meta( $post->ID, '_mtphr_dnt_ticker_width', true ),
		'offset' => get_post_meta( $post->ID, '_mtphr_dnt_offset', true ),	
		'grid' => get_post_meta( $post->ID, '_mtphr_dnt_grid', true ),
		'grid_empty_rows' => get_post_meta( $post->ID, '_mtphr_dnt_grid_empty_rows', true ),
		'grid_equal_width' => get_post_meta( $post->ID, '_mtphr_dnt_grid_equal_width', true ),
		'grid_cols' => get_post_meta( $post->ID, '_mtphr_dnt_grid_cols', true ),
		'grid_rows' => get_post_meta( $post->ID, '_mtphr_dnt_grid_rows', true ),
		'grid_padding' => get_post_meta( $post->ID, '_mtphr_dnt_grid_padding', true ),
	);
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	$values = wp_parse_args( $values, $defaults );

	echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	echo '<table class="mtphr-dnt-table">';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-no-label">';
				echo '<label><input type="checkbox" name="_mtphr_dnt_title" value="1" '.checked('1', $values['title'], false).' /> '.__('Display title', 'ditty-news-ticker').'</label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_inline_title" value="1" '.checked('1', $values['inline_title'], false).' /> '.__('Inline title', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-no-label">';
				echo '<label><input type="checkbox" name="_mtphr_dnt_shuffle" value="1" '.checked('1', $values['shuffle'], false).' /> '.__('Randomly shuffle the ticks', 'ditty-news-ticker').'</label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-no-label">';
				echo '<label>'.__('Ticker width <em>(optional)</em>', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_ticker_width" value="'.$values['width'].'" /></label>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-no-label">';
				echo '<label>'.__('Offset ticks', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_offset" value="'.$values['offset'].'" /> '.__('px from the edge', 'ditty-news-ticker').'</label>';
				echo '<br/><small><em>'.__('The amount of pixels ticks should start and end off the screen.', 'ditty-news-ticker').'</em></small>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td class="mtphr-dnt-no-label">';
				echo '<p class="mtphr-dnt-side-label">'.__('Grid Display', 'ditty-news-ticker').'</p>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_grid" value="1" '.checked('1', $values['grid'], false).' /> '.__('Display ticks in a grid', 'ditty-news-ticker').'</label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_grid_empty_rows" value="1" '.checked('1', $values['grid_empty_rows'], false).' /> '.__('Render empty rows', 'ditty-news-ticker').'</label>';
				echo '<label><input type="checkbox" name="_mtphr_dnt_grid_equal_width" value="1" '.checked('1', $values['grid_equal_width'], false).' /> '.__('Force equal column width', 'ditty-news-ticker').'</label>';
				echo '<br/>';
				echo '<label>'.__('Columns', 'ditty-news-ticker').' <input type="number" style="width:50px;" name="_mtphr_dnt_grid_cols" value="'.$values['grid_cols'].'" /></label>';
				echo '<label>'.__('Rows', 'ditty-news-ticker').' <input type="number" style="width:50px;" name="_mtphr_dnt_grid_rows" value="'.$values['grid_rows'].'" /></label>';
				echo '<br/>';
				echo '<label>'.__('Cell padding', 'ditty-news-ticker').' <input type="number" name="_mtphr_dnt_grid_padding" value="'.$values['grid_padding'].'" /></label>';
			echo '</td>';
		echo '</tr>';
		
	echo '</table>';
}
}






/* --------------------------------------------------------- */
/* !Save the custom meta - 1.4.1 */
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
	
		$type = isset($_POST['_mtphr_dnt_type']) ? sanitize_text_field($_POST['_mtphr_dnt_type']) : 'default';
		$mode = isset($_POST['_mtphr_dnt_mode']) ? sanitize_text_field($_POST['_mtphr_dnt_mode']) : 'scroll';
		
		update_post_meta( $post_id, '_mtphr_dnt_type', $type );
		update_post_meta( $post_id, '_mtphr_dnt_mode', $mode );
	}
	
	// Save the default ticks
	if( isset($_POST['_mtphr_dnt_ticks']) ) {
		
		$force_breaks = ( isset($_POST['_mtphr_dnt_line_breaks']) && $_POST['_mtphr_dnt_line_breaks'] != '' ) ? 1 : '';
		update_post_meta( $post_id, '_mtphr_dnt_line_breaks', $force_breaks );

		$sanitized_ticks = array();
		if( count($_POST['_mtphr_dnt_ticks']) > 0 ) {
			foreach( $_POST['_mtphr_dnt_ticks'] as $tick ) {
				$sanitized_ticks[] = array(
					'tick' => convert_chars(wptexturize($tick['tick'])),
					'link' => esc_url($tick['link']),
					'target' => $tick['target'],
					'nofollow' => isset( $tick['nofollow'] ) ? $tick['nofollow'] : ''
				);
			}
		}
		update_post_meta( $post_id, '_mtphr_dnt_ticks', $sanitized_ticks );
	}
	
	// Save the mixed ticks
	if( isset($_POST['_mtphr_dnt_mixed_ticks']) ) {
		$sanitized_ticks = array();
		if( count($_POST['_mtphr_dnt_mixed_ticks']) > 0 ) {
			foreach( $_POST['_mtphr_dnt_mixed_ticks'] as $tick ) {
				$sanitized_ticks[] = array(
					'type' => $tick['type'],
					'offset' => intval($tick['offset'])
				);
			}
		}
		update_post_meta( $post_id, '_mtphr_dnt_mixed_ticks', $sanitized_ticks );
	}
	
	// Save the scroll settings
	if( isset($_POST['_mtphr_dnt_scroll_direction']) ) {
	
		$direction = isset($_POST['_mtphr_dnt_scroll_direction']) ? $_POST['_mtphr_dnt_scroll_direction'] : 'left';
		$init = isset($_POST['_mtphr_dnt_scroll_init']) ? $_POST['_mtphr_dnt_scroll_init'] : '';
		$width = isset($_POST['_mtphr_dnt_scroll_width']) ? intval($_POST['_mtphr_dnt_scroll_width']) : 0;
		$height = isset($_POST['_mtphr_dnt_scroll_height']) ? intval($_POST['_mtphr_dnt_scroll_height']) : 0;
		$padding = isset($_POST['_mtphr_dnt_scroll_padding']) ? intval($_POST['_mtphr_dnt_scroll_padding']) : 0;
		$margin = isset($_POST['_mtphr_dnt_scroll_margin']) ? intval($_POST['_mtphr_dnt_scroll_margin']) : 0;
		$speed = isset($_POST['_mtphr_dnt_scroll_speed']) ? intval($_POST['_mtphr_dnt_scroll_speed']) : 10;
		$pause = isset($_POST['_mtphr_dnt_scroll_pause']) ? $_POST['_mtphr_dnt_scroll_pause'] : '';
		$spacing = isset($_POST['_mtphr_dnt_scroll_tick_spacing']) ? intval($_POST['_mtphr_dnt_scroll_tick_spacing']) : 40;
		
		update_post_meta( $post_id, '_mtphr_dnt_scroll_direction', $direction );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_init', $init );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_width', $width );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_height', $height );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_padding', $padding );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_margin', $margin );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_speed', $speed );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_pause', $pause );
		update_post_meta( $post_id, '_mtphr_dnt_scroll_tick_spacing', $spacing );
	}
	
	// Save the rotate settings
	if( isset($_POST['_mtphr_dnt_rotate_type']) ) {
	
		$type = isset($_POST['_mtphr_dnt_rotate_type']) ? $_POST['_mtphr_dnt_rotate_type'] : 'fade';
		$reverse = isset($_POST['_mtphr_dnt_rotate_directional_nav_reverse']) ? $_POST['_mtphr_dnt_rotate_directional_nav_reverse'] : '';
		$height = isset($_POST['_mtphr_dnt_rotate_height']) ? intval($_POST['_mtphr_dnt_rotate_height']) : 0;
		$padding = isset($_POST['_mtphr_dnt_rotate_padding']) ? intval($_POST['_mtphr_dnt_rotate_padding']) : 0;
		$margin = isset($_POST['_mtphr_dnt_rotate_margin']) ? intval($_POST['_mtphr_dnt_rotate_margin']) : 0;
		$auto = isset($_POST['_mtphr_dnt_auto_rotate']) ? $_POST['_mtphr_dnt_auto_rotate'] : '';
		$delay = isset($_POST['_mtphr_dnt_rotate_delay']) ? intval($_POST['_mtphr_dnt_rotate_delay']) : 7;
		$pause = isset($_POST['_mtphr_dnt_rotate_pause']) ? $_POST['_mtphr_dnt_rotate_pause'] : '';
		$speed = isset($_POST['_mtphr_dnt_rotate_speed']) ? intval($_POST['_mtphr_dnt_rotate_speed']) : 3;
		$ease = isset($_POST['_mtphr_dnt_rotate_ease']) ? $_POST['_mtphr_dnt_rotate_ease'] : 'linear';
		$directional_nav = isset($_POST['_mtphr_dnt_rotate_directional_nav']) ? $_POST['_mtphr_dnt_rotate_directional_nav'] : '';
		$directional_nav_hide = isset($_POST['_mtphr_dnt_rotate_directional_nav_hide']) ? $_POST['_mtphr_dnt_rotate_directional_nav_hide'] : '';
		$control_nav = isset($_POST['_mtphr_dnt_rotate_control_nav']) ? $_POST['_mtphr_dnt_rotate_control_nav'] : '';
		$control_nav_type = isset($_POST['_mtphr_dnt_rotate_control_nav_type']) ? $_POST['_mtphr_dnt_rotate_control_nav_type'] : 'number';
		
		update_post_meta( $post_id, '_mtphr_dnt_rotate_type', $type );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_directional_nav_reverse', $reverse );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_height', $height );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_padding', $padding );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_margin', $margin );
		update_post_meta( $post_id, '_mtphr_dnt_auto_rotate', $auto );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_delay', $delay );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_pause', $pause );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_speed', $speed );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_ease', $ease );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_directional_nav', $directional_nav );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_directional_nav_hide', $directional_nav_hide );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_control_nav', $control_nav );
		update_post_meta( $post_id, '_mtphr_dnt_rotate_control_nav_type', $control_nav_type );
	}
	
	// Save the list settings
	if( isset($_POST['_mtphr_dnt_list_padding']) ) {
	
		$padding = isset($_POST['_mtphr_dnt_list_padding']) ? intval($_POST['_mtphr_dnt_list_padding']) : 0;
		$margin = isset($_POST['_mtphr_dnt_list_margin']) ? intval($_POST['_mtphr_dnt_list_margin']) : 0;
		$spacing = isset($_POST['_mtphr_dnt_list_tick_spacing']) ? intval($_POST['_mtphr_dnt_list_tick_spacing']) : 10;
		$paging = isset($_POST['_mtphr_dnt_list_tick_paging']) ? $_POST['_mtphr_dnt_list_tick_paging'] : '';
		$count = isset($_POST['_mtphr_dnt_list_tick_count']) ? intval($_POST['_mtphr_dnt_list_tick_count']) : 0;
		$prev_next = isset($_POST['_mtphr_dnt_list_tick_prev_next']) ? $_POST['_mtphr_dnt_list_tick_prev_next'] : '';
		$prev_text = ( isset($_POST['_mtphr_dnt_list_tick_prev_text']) && $_POST['_mtphr_dnt_list_tick_prev_text'] != '' ) ? sanitize_text_field($_POST['_mtphr_dnt_list_tick_prev_text']) : __('« Previous', 'ditty-news-ticker');
		$next_text = ( isset($_POST['_mtphr_dnt_list_tick_next_text']) && $_POST['_mtphr_dnt_list_tick_next_text'] != '' ) ? sanitize_text_field($_POST['_mtphr_dnt_list_tick_next_text']) : __('Next »', 'ditty-news-ticker');
		
		update_post_meta( $post_id, '_mtphr_dnt_list_padding', $padding );
		update_post_meta( $post_id, '_mtphr_dnt_list_margin', $margin );
		update_post_meta( $post_id, '_mtphr_dnt_list_tick_spacing', $spacing );
		update_post_meta( $post_id, '_mtphr_dnt_list_tick_paging', $paging );
		update_post_meta( $post_id, '_mtphr_dnt_list_tick_count', $count );
		update_post_meta( $post_id, '_mtphr_dnt_list_tick_prev_next', $prev_next );
		update_post_meta( $post_id, '_mtphr_dnt_list_tick_prev_text', $prev_text );
		update_post_meta( $post_id, '_mtphr_dnt_list_tick_next_text', $next_text );
	}
	
	// Save the global settings
	if( isset($_POST['_mtphr_dnt_ticker_width']) ) {
	
		$title = isset($_POST['_mtphr_dnt_title']) ? $_POST['_mtphr_dnt_title'] : '';
		$inline_title = isset($_POST['_mtphr_dnt_inline_title']) ? $_POST['_mtphr_dnt_inline_title'] : '';
		$shuffle = isset($_POST['_mtphr_dnt_shuffle']) ? $_POST['_mtphr_dnt_shuffle'] : '';
		$width = isset($_POST['_mtphr_dnt_ticker_width']) ? intval($_POST['_mtphr_dnt_ticker_width']) : 0;
		$offset = isset($_POST['_mtphr_dnt_offset']) ? intval($_POST['_mtphr_dnt_offset']) : 20;	
		$grid = isset($_POST['_mtphr_dnt_grid']) ? $_POST['_mtphr_dnt_grid'] : '';
		$grid_empty_rows = isset($_POST['_mtphr_dnt_grid_empty_rows']) ? $_POST['_mtphr_dnt_grid_empty_rows'] : '';
		$grid_equal_width = isset($_POST['_mtphr_dnt_grid_equal_width']) ? $_POST['_mtphr_dnt_grid_equal_width'] : '';
		$grid_cols = isset($_POST['_mtphr_dnt_grid_cols']) ? intval($_POST['_mtphr_dnt_grid_cols']) : 2;
		$grid_rows = isset($_POST['_mtphr_dnt_grid_rows']) ? intval($_POST['_mtphr_dnt_grid_rows']) : 2;
		$grid_padding = isset($_POST['_mtphr_dnt_grid_padding']) ? intval($_POST['_mtphr_dnt_grid_padding']) : 5;
		
		update_post_meta( $post_id, '_mtphr_dnt_title', $title );
		update_post_meta( $post_id, '_mtphr_dnt_inline_title', $inline_title );
		update_post_meta( $post_id, '_mtphr_dnt_shuffle', $shuffle );
		update_post_meta( $post_id, '_mtphr_dnt_ticker_width', $width );
		update_post_meta( $post_id, '_mtphr_dnt_offset', $offset );
		update_post_meta( $post_id, '_mtphr_dnt_grid', $grid );
		update_post_meta( $post_id, '_mtphr_dnt_grid_empty_rows', $grid_empty_rows );
		update_post_meta( $post_id, '_mtphr_dnt_grid_equal_width', $grid_equal_width );
		update_post_meta( $post_id, '_mtphr_dnt_grid_cols', $grid_cols );
		update_post_meta( $post_id, '_mtphr_dnt_grid_rows', $grid_rows );
		update_post_meta( $post_id, '_mtphr_dnt_grid_padding', $grid_padding );
	}
}
add_action( 'save_post', 'mtphr_dnt_metabox_save' );









