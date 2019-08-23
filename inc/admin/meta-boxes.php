<?php

/* --------------------------------------------------------- */
/* !Add the ticker display code - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_display_code') ) {
function mtphr_dnt_display_code() {

	global $post, $typenow;
	
	if( $typenow == 'ditty_news_ticker' ) {
		
		echo '<div id="mtphr-dnt-code-copy">';
			echo '<table>';
				echo '<tr>';
					echo '<td id="mtphr-dnt-shortcode-copy">';
						echo '<div class="wrapper">';
							echo '<h3>'.__('Shortcode', 'ditty-news-ticker').'</h3>';
							echo '<p>'.__('Copy and paste this shortcode into a page or post to display the ticker within the post content.', 'ditty-news-ticker').'</p>';
							echo '<pre><p>[ditty_news_ticker id="'.$post->ID.'"]</p></pre>';
							echo '<a href="#" class="button mtphr-dnt-code-select">'.__('Select Shortcode', 'ditty-news-ticker').'</a>';
						echo '</div>';
					echo '</td>';
						
					echo '<td id="mtphr-dnt-function-copy">';
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
}
add_action( 'edit_form_after_title', 'mtphr_dnt_display_code' );


/* --------------------------------------------------------- */
/* !Add the main ticker options - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_option_buttons') ) {
function mtphr_dnt_option_buttons() {

	global $post, $typenow;
	
	if( $typenow == 'ditty_news_ticker' ) {
		
		$tab = get_post_meta( $post->ID, '_mtphr_dnt_admin_tab', true );
		$tab = ( $tab != '' ) ? $tab : '#mtphr-dnt-type-select';
	
		$types = mtphr_dnt_types_array();
		$type = get_post_meta( $post->ID, '_mtphr_dnt_type', true );
		$type = ( $type != '' ) ? $type : 'default';
		
		$modes = mtphr_dnt_modes_array();
		$mode = get_post_meta( $post->ID, '_mtphr_dnt_mode', true );
		$mode = ( $mode != '' ) ? $mode : 'scroll';
		
		echo '<div id="mtphr-dnt-settings-select">';
			echo '<input type="hidden" name="mtphr_dnt_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
			echo '<input type="hidden" name="_mtphr_dnt_admin_tab" value="'.$tab.'" />';
			echo '<input type="hidden" name="_mtphr_dnt_admin_javascript" value="error" />';

			echo '<div id="mtphr-dnt-metabox-group-toggles" class="mtphr-dnt-clearfix">';
				$active = ( $tab == '#mtphr-dnt-type-select' ) ? ' active' : '';
				echo '<a class="mtphr-dnt-metabox-group-toggle'.$active.'" href="#mtphr-dnt-type-select"><i class="mtphr-dnt-icon-dittynewsticker"></i> '.__('<span>Ticker </span>Type', 'ditty-news-ticker').'</a>';
				$active = ( $tab == '#mtphr-dnt-mode-select' ) ? ' active' : '';
				echo '<a class="mtphr-dnt-metabox-group-toggle'.$active.'" href="#mtphr-dnt-mode-select"><i class="mtphr-dnt-icon-dittynewsticker"></i> '.__('<span>Ticker </span>Mode', 'ditty-news-ticker').'</a>';
				$active = ( $tab == '#mtphr-dnt-global-select' ) ? ' active' : '';
				echo '<a class="mtphr-dnt-metabox-group-toggle'.$active.'" href="#mtphr-dnt-global-select"><i class="mtphr-dnt-icon-dittynewsticker"></i> '.__('<span>Global </span>Settings', 'ditty-news-ticker').'</a>';
				echo '<button name="save" type="submit" class="button button-primary button-large" id="mtphr-dnt-publish"><i class="dashicons dashicons-yes"></i><span> '.__('Update', 'ditty-news-ticker').'</span></button>';
			echo '</div>';
			
			
			/* --------------------------------------------------------- */
			/* !Ticker Type - 2.0.2 */
			/* --------------------------------------------------------- */
			
			$active = ( $tab == '#mtphr-dnt-type-select' ) ? ' active' : '';
			echo '<div id="mtphr-dnt-type-select" class="mtphr-dnt-metabox-group'.$active.'">';
				
				echo '<div>';
					echo '<div class="mtphr-dnt-metabox-toggle">';
						echo '<input type="hidden" name="_mtphr_dnt_type" value="'.$type.'" />';
						foreach( $types as $i=>$t ) {
							
							$value = '';
							$button = $t['button'];
							$metabox_id = isset($t['metabox_id']) ? $t['metabox_id'] : '';
							if( is_array($metabox_id) ) {
								$metabox_id = trim(implode(' ', $t['metabox_id']));
							}
					
							// Create a button
							$selected = ( $type == $i ) ? ' button-primary' : '';
							$icon = isset($t['icon']) ? '<i class="'.$t['icon'].'"></i> ' : '';
							echo '<a href="#'.$i.'" metabox="'.$metabox_id.'" class="mtphr-dnt-type-toggle mtphr-dnt-button button'.$selected.'">'.$icon.$button.'</a>&nbsp;';
						}
						echo '<a href="http://www.dittynewsticker.com/" target="_blank" class="mtphr-dnt-button button mtphr-dnt-get-more"><i class="fontastic mtphr-dnt-icon-download"></i> '.__('More Extensions', 'ditty-news-ticker').'</a>';
					echo '</div>';
				echo '</div>';
				
				echo '<div id="mtphr-dnt-type-metaboxes">';
					do_action( 'mtphr_dnt_type_metaboxes' );
				echo '</div>';
				
			echo '</div>';
			
			
			/* --------------------------------------------------------- */
			/* !Ticker Mode - 2.0.2 */
			/* --------------------------------------------------------- */
				
			$active = ( $tab == '#mtphr-dnt-mode-select' ) ? ' active' : '';
			echo '<div id="mtphr-dnt-mode-select" class="mtphr-dnt-metabox-group'.$active.'">';
				
				echo '<div class="wrapper">';
					echo '<div class="mtphr-dnt-metabox-toggle">';
						echo '<input type="hidden" name="_mtphr_dnt_mode" value="'.$mode.'" />';
						foreach( $modes as $i=>$m ) {
							
							$value = '';
							$button = $m['button'];
							$metabox_id = isset($m['metabox_id']) ? $m['metabox_id'] : '';
							if( is_array($metabox_id) ) {
								$metabox_id = trim(implode(' ', $m['metabox_id']));
							}
					
							// Create a button
							$selected = ( $mode == $i ) ? ' button-primary' : '';
							$icon = isset($m['icon']) ? '<i class="'.$m['icon'].'"></i> ' : '';
							echo '<a href="#'.$i.'" metabox="'.$metabox_id.'" class="mtphr-dnt-mode-toggle mtphr-dnt-button button'.$selected.'">'.$icon.$button.'</a>&nbsp;';
						}
						echo '<a href="http://www.dittynewsticker.com/" target="_blank" class="mtphr-dnt-button button mtphr-dnt-get-more"><i class="fontastic mtphr-dnt-icon-download"></i> '.__('More Extensions', 'ditty-news-ticker').'</a>';
					echo '</div>';
				echo '</div>';
				
				echo '<div id="mtphr-dnt-mode-metaboxes">';
					do_action( 'mtphr_dnt_mode_metaboxes' );
				echo '</div>';
				
			echo '</div>';
			
			
			/* --------------------------------------------------------- */
			/* !Global Settings - 2.0.0 */
			/* --------------------------------------------------------- */
				
			$active = ( $tab == '#mtphr-dnt-global-select' ) ? ' active' : '';
			echo '<div id="mtphr-dnt-global-select" class="mtphr-dnt-metabox-group'.$active.'">';
				
				echo '<div id="mtphr-dnt-global-metaboxes">';
					do_action( 'mtphr_dnt_global_metaboxes' );
				echo '</div>';
				
			echo '</div>';

		echo '</div>';
	}
}
}
add_action( 'edit_form_after_title', 'mtphr_dnt_option_buttons' );




/* --------------------------------------------------------- */
/* !Add ticker type metaboxes - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_type_metaboxes') ) {
function mtphr_dnt_type_metaboxes() {
	
	// Default type metabox
	mtphr_dnt_metabox( 'mtphr-dnt-default-metabox', mtphr_dnt_default_fields() );
	
	// Mixed type metabox
	mtphr_dnt_metabox( 'mtphr-dnt-mixed-metabox', mtphr_dnt_mixed_fields() );	
}
}
add_action( 'mtphr_dnt_type_metaboxes', 'mtphr_dnt_type_metaboxes' );


/* --------------------------------------------------------- */
/* !Add ticker mode metaboxes - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_mode_metaboxes') ) {
function mtphr_dnt_mode_metaboxes() {
	
	// Scroll mode metabox
	mtphr_dnt_metabox( 'mtphr-dnt-scroll-metabox', mtphr_dnt_scroll_fields() );
	
	// Rotate mode metabox
	mtphr_dnt_metabox( 'mtphr-dnt-rotate-metabox', mtphr_dnt_rotate_fields() );
	
	// List mode metabox
	mtphr_dnt_metabox( 'mtphr-dnt-list-metabox', mtphr_dnt_list_fields() );
}
}
add_action( 'mtphr_dnt_mode_metaboxes', 'mtphr_dnt_mode_metaboxes' );


/* --------------------------------------------------------- */
/* !Add global metaboxes - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_global_metaboxes') ) {
function mtphr_dnt_global_metaboxes() {
	
	// Global mode metabox
	mtphr_dnt_metabox( 'mtphr-dnt-global-metabox', mtphr_dnt_global_fields() );
}
}
add_action( 'mtphr_dnt_global_metaboxes', 'mtphr_dnt_global_metaboxes' );




/* --------------------------------------------------------- */
/* !Return the default ticker values - 2.0.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_default_values') ) {
function mtphr_dnt_default_values() {
	
	global $post;
	
	$defaults = array(
		'ticks' => false,
		'line_breaks' => ''
	);
	
	$defaults = apply_filters( 'mtphr_dnt_default_defaults', $defaults );
	
	$values = array(
		'ticks' => get_post_meta( $post->ID, '_mtphr_dnt_ticks', true ),
		'line_breaks' => get_post_meta( $post->ID, '_mtphr_dnt_line_breaks', true )
	);
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	return wp_parse_args( $values, $defaults );	
}
}

/* --------------------------------------------------------- */
/* !Return the default ticker fields - 2.0.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_default_fields') ) {
function mtphr_dnt_default_fields() {
	
	$settings = mtphr_dnt_general_settings();
	$values = mtphr_dnt_default_values();

	$fields = array(
		
		/* !Ticks (& text, link, target, nofollow) - 2.0.0 */
		'ticks' => array(
			'heading' => __('Ticks', 'ditty-news-ticker'),
			'description' => __('Add an unlimited number of ticks to your ticker', 'ditty-news-ticker'),
			'help' => __('Use the \'+\' and \'x\' buttons on the right to add and delete ticks. Drag and drop the arrows on the left to re-order your ticks.', 'ditty-news-ticker'),
			'type' => 'list',
			'name' => '_mtphr_dnt_ticks',
			'value' => $values['ticks'],
			'fields' => array(
				
				/* !Tick text - 2.0.0 */
				'tick' => array(
					'heading' => __('Ticker text', 'ditty-news-ticker'),
					'help' => __('Add the content of your tick. HTML and inline styles are supported.', 'ditty-news-ticker'),
					'type' => (isset($settings['wysiwyg']) && ($settings['wysiwyg'] == '1' || $settings['wysiwyg'] == 'on') ) ? 'wysiwyg' : 'textarea',
					'placeholder' => __('Add your content here. HTML and inline styles are supported.', 'ditty-news-ticker'),
					'rows' => 2
				),
				
				/* !Tick link - 2.0.0 */
				'link' => array(
					'heading' => __('Link', 'ditty-news-ticker'),
					'help' => __('Wrap a link around your tick content. You can also add a link directly into your content.', 'ditty-news-ticker'),
					'type' => 'text',
					'placeholder' => __('Add a URL (optional)', 'ditty-news-ticker'),
				),
				
				/* !Tick link target - 2.0.0 */
				'target' => array(
					'heading' => __('Target', 'ditty-news-ticker'),
					'help' => __('Set a target for your link.', 'ditty-news-ticker'),
					'type' => 'select',
					'options' => array(
						'_self' => '_self',
						'_blank' => '_blank'
					)
				),
				
				/* !Tick link nofollow - 2.0.0 */
				'nofollow' => array(
					'heading' => __('No Follow', 'ditty-news-ticker'),
					'help' => __('Enabling this setting will add an attribute called \'nofollow\' to your link. This tells search engines to not follow this link.', 'ditty-news-ticker'),
					'type' => 'checkbox',
					'label' => __('Add "nofollow" to link', 'ditty-news-ticker')
				)
			)
		),
		
		/* !Force line breaks - 2.0.0 */
		'line_breaks' => array(
			'heading' => __('Line breaks', 'ditty-news-ticker'),
			'description' => __('Force line breaks on carriage returns (not used for wysiwyg editors)', 'ditty-news-ticker'),
			'help' => __('Enabling this setting will create new lines for all carrige returns contained in your tick text', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_line_breaks',
			'value' => $values['line_breaks'],
			'label' => __('Force line breaks', 'ditty-news-ticker')
		)
	);
	
	return apply_filters( 'mtphr_dnt_default_fields', $fields, $values );
}
}



/* --------------------------------------------------------- */
/* !Return the mixed ticker values - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_mixed_values') ) {
function mtphr_dnt_mixed_values() {
	
	global $post;
	
	$defaults = array(
		'ticks' => false
	);
	
	$defaults = apply_filters( 'mtphr_dnt_mixed_defaults', $defaults );
	
	$values = array(
		'ticks' => get_post_meta( $post->ID, '_mtphr_dnt_mixed_ticks', true )
	);
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	return wp_parse_args( $values, $defaults );	
}
}

/* --------------------------------------------------------- */
/* !Return the mixed ticker fields - 2.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_mixed_fields') ) {
function mtphr_dnt_mixed_fields() {
	
	$values = mtphr_dnt_mixed_values();

	$types = mtphr_dnt_types_labels();
	unset($types['mixed']);	
	
	$fields = array(
		
		/* !Ticks (& type, all, lffset) - 2.0.0 */
		'ticks' => array(
			'heading' => __('Tick selection', 'ditty-news-ticker'),
			'description' => __('Select the ticks you would like to display by choosing the tick type and the offset position of the selected feed', 'ditty-news-ticker'),
			'type' => 'list',
			'name' => '_mtphr_dnt_mixed_ticks',
			'value' => $values['ticks'],
			'fields' => array(
				
				/* !Tick type - 2.0.0 */
				'type' => array(
					'heading' => __('Ticker type', 'ditty-news-ticker'),
					'help' => __('Select the ticker type to use.', 'ditty-news-ticker'),
					'type' => 'select',
					'options' => $types
				),
				
				/* !All ticks - 2.0.0 */
				'all' => array(
					'heading' => __('All ticks', 'ditty-news-ticker'),
					'help' => __('All ticks from the specified type will be show when this is enabled.', 'ditty-news-ticker'),
					'type' => 'checkbox',
					'label' => __('Display all ticks', 'ditty-news-ticker')
				),
				
				/* !Tick offset - 2.0.0 */
				'offset' => array(
					'heading' => __('Offset', 'ditty-news-ticker'),
					'help' => __('Choose the specific tick you would like to use from the specified type. \'0\' will display the first tick, \'1\' will display the second tick, and so on.', 'ditty-news-ticker'),
					'type' => 'number',
				)
			)
		)
	);
	
	return apply_filters( 'mtphr_dnt_mixed_fields', $fields, $values );
}
}


/* --------------------------------------------------------- */
/* !Return the scroll mode values - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_scroll_values') ) {
function mtphr_dnt_scroll_values() {
	
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
	
	$defaults = apply_filters( 'mtphr_dnt_scroll_defaults', $defaults );
	
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
	
	return wp_parse_args( $values, $defaults );
}
}

/* --------------------------------------------------------- */
/* !Return the scroll mode fields - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_scroll_fields') ) {
function mtphr_dnt_scroll_fields() {

	$values = mtphr_dnt_scroll_values();
	
	$fields = array(
		
		/* !Scroll direction (& init) - 2.0.0 */
		'direction' => array(
			'heading' => __('Scroll direction', 'ditty-news-ticker'),
			'description' => __('Set the scroll direction of the ticker', 'ditty-news-ticker'),
			'help' => __('Set the direction you want the ticker to scroll. By default, the ticker starts off-screen, but you can enable the \'Show first tick on init\' setting to force the content to start on-screen.', 'ditty-news-ticker'),
			'type' => 'radio_buttons',
			'name' => '_mtphr_dnt_scroll_direction',
			'value' => $values['direction'],
			'options' => array(
				'left' => __('Left', 'ditty-news-ticker'),
				'right' => __('Right', 'ditty-news-ticker'),
				'up' => __('Up', 'ditty-news-ticker'),
				'down' => __('Down', 'ditty-news-ticker')
			),
			'append' => array(
				
				/* !Scroll init - 2.0.0 */
				'init' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_scroll_init',
					'label' => __('Show first tick on init', 'ditty-news-ticker'),
					'value' => $values['init'],
				)
			)
		),
		
		/* !Tick width (& height) - 2.0.0 */
		'dimensions' => array(
			'heading' => __('Tick dimensions', 'ditty-news-ticker'),
			'description' => __('Override the auto dimensions with specific values', 'ditty-news-ticker'),
			'help' => __('Set a specific width and height for the ticks. When using a vertically scrolling ticker the height will define the overall height of the ticker.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_scroll_width',
			'value' => $values['width'],
			'before' => __('Width', 'ditty-news-ticker').':',
			'after' => __('pixels', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Tick height - 2.0.0 */
				'height' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_scroll_height',
					'value' => $values['height'],
					'before' => __('Height', 'ditty-news-ticker').':',
					'after' => __('pixels', 'ditty-news-ticker')
				),
			)
		),
		
		/* !Tick padding (& margin) - 2.0.0 */
		'padding' => array(
			'heading' => __('Scroller padding', 'ditty-news-ticker'),
			'description' => __('Set the vertical spacing of the scrolling data', 'ditty-news-ticker'),
			'help' => __('Add custom vertical padding and margins to each of your ticks.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_scroll_padding',
			'value' => $values['padding'],
			'before' => __('Vertical padding', 'ditty-news-ticker').':',
			'after' => __('pixels', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Ticker margin - 2.0.0 */
				'margin' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_scroll_margin',
					'value' => $values['margin'],
					'before' => __('Vertical margin', 'ditty-news-ticker').':',
					'after' => __('pixels', 'ditty-news-ticker'),
				)
			)
		),
		
		/* !Scroll speed (& pause) - 2.0.0 */
		'speed' => array(
			'heading' => __('Scroll speed', 'ditty-news-ticker'),
			'description' => __('Set the speed of the scrolling data', 'ditty-news-ticker'),
			'help' => __('Set the speed of the ticker. You may need to try different speeds to get optimum results when using different fonts. Enable the checkbox to pause the ticker when a user\'s have their mouse over the ticker.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_scroll_speed',
			'value' => $values['speed'],
			'append' => array(
				
				/* !Scroll pause - 2.0.0 */
				'pause' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_scroll_pause',
					'value' => $values['pause'],
					'label' => __('Pause on mouse over', 'ditty-news-ticker'),
				)
			)
		),
		
		/* !Tick spacing - 2.0.0 */
		'spacing' => array(
			'heading' => __('Tick spacing', 'ditty-news-ticker'),
			'description' => __('Set the spacing between scrolling data', 'ditty-news-ticker'),
			'help' => __('Set the amount of space that should be rendered between the ticks within your ticker.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_scroll_tick_spacing',
			'value' => $values['spacing'],
			'after' => __('pixels', 'ditty-news-ticker')
		)
	);
	
	return apply_filters( 'mtphr_dnt_scroll_fields', $fields, $values );
}
}


/* --------------------------------------------------------- */
/* !Return the rotate mode values - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_rotate_values') ) {
function mtphr_dnt_rotate_values() {
	
	global $post;
	
	$type = get_post_meta( $post->ID, '_mtphr_dnt_rotate_type', true );
	
	$defaults = array(
		'type' => 'fade',
		'reverse' => '',
		'height' => 0,
		'padding' => 0,
		'margin' => 0,
		'auto' => ($type == '' ) ? '1' : '',
		'delay' => 7,
		'pause' => '',
		'speed' => 10,
		'ease' => 'easeInOutQuint',
		'directional_nav' => ($type == '' ) ? '1' : '',
		'directional_nav_hide' => '',
		'control_nav' => ($type == '' ) ? '1' : '',
		'control_nav_type' => 'button',
		'disable_touchswipe' => ''
	);
	
	$defaults = apply_filters( 'mtphr_dnt_rotate_defaults', $defaults, $type );
	
	$values = array(
		'type' => $type,
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
		'control_nav_type' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_control_nav_type', true ),
		'disable_touchswipe' => get_post_meta( $post->ID, '_mtphr_dnt_rotate_disable_touchswipe', true )
	);
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	return wp_parse_args( $values, $defaults );
}
}

/* --------------------------------------------------------- */
/* !Return the rotate mode fields - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_rotate_fields') ) {
function mtphr_dnt_rotate_fields() {

	$values = mtphr_dnt_rotate_values();
	
	$fields = array(
		
		/* !Rotate type - 2.0.18 */
		'direction' => array(
			'heading' => __('Rotation type', 'ditty-news-ticker'),
			'description' => __('Set the type of rotation for the ticker', 'ditty-news-ticker'),
			'help' => __('Select the rotation type. Enable \'Dynamic Slide Direction\' to reverse the slide direction when previous items are selected.', 'ditty-news-ticker'),
			'type' => 'radio_buttons',
			'name' => '_mtphr_dnt_rotate_type',
			'value' => $values['type'],
			'options' => array(
				'fade' => __('Fade', 'ditty-news-ticker'),
				'slide_left' => __('Slide left', 'ditty-news-ticker'),
				'slide_right' => __('Slide right', 'ditty-news-ticker'),
				'slide_up' => __('Slide up', 'ditty-news-ticker'),
				'slide_down' => __('Slide down', 'ditty-news-ticker')
			),
			'append' => array(
				
				/* !Dynamic slide direction - 2.0.0 */
				'init' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_rotate_directional_nav_reverse',
					'label' => __('Dynamic slide direction', 'ditty-news-ticker'),
					'value' => $values['reverse'],
				)
			)
		),
		
		/* !Tick height - 2.0.0 */
		'dimensions' => array(
			'heading' => __('Tick dimensions', 'ditty-news-ticker'),
			'description' => __('Override the auto dimensions with specific values', 'ditty-news-ticker'),
			'help' => __('Set a specific height for the ticks.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_rotate_height',
			'value' => $values['height'],
			'before' => __('Height', 'ditty-news-ticker').':',
			'after' => __('pixels', 'ditty-news-ticker')
		),
		
		/* !Tick padding (& margin) - 2.0.0 */
		'padding' => array(
			'heading' => __('Rotator padding', 'ditty-news-ticker'),
			'description' => __('Set the vertical spacing of the rotating data', 'ditty-news-ticker'),
			'help' => __('Add custom vertical padding and margins to each of your ticks.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_rotate_padding',
			'value' => $values['padding'],
			'before' => __('Vertical padding', 'ditty-news-ticker').':',
			'after' => __('pixels', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Ticker margin - 2.0.0 */
				'margin' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_rotate_margin',
					'value' => $values['margin'],
					'before' => __('Vertical margin', 'ditty-news-ticker').':',
					'after' => __('pixels', 'ditty-news-ticker'),
				)
			)
		),
		
		/* !Auto rotate (& pause) - 2.0.0 */
		'auto_rotate' => array(
			'heading' => __('Auto rotate', 'ditty-news-ticker'),
			'description' => __('Set the delay between rotations', 'ditty-news-ticker'),
			'help' => __('Enable auto rotation of your ticks with and set the amount of time each tick should display. Optionally, force the rotator to pause when the user hovers over the ticker.', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_auto_rotate',
			'value' => $values['auto'],
			'label' => __('Enable', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Delay - 2.0.0 */
				'delay' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_rotate_delay',
					'value' => $values['delay'],
					'after' => __('Seconds delay', 'ditty-news-ticker')
				),
				
				/* !Pause - 2.0.0 */
				'pause' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_rotate_pause',
					'value' => $values['pause'],
					'label' => __('Pause on mouse over', 'ditty-news-ticker')
				)
			)
		),
		
		/* !Rotate speed (& ease) - 2.0.0 */
		'speed' => array(
			'heading' => __('Rotate speed', 'ditty-news-ticker'),
			'description' => __('Set the speed & easing of the rotation', 'ditty-news-ticker'),
			'help' => __('Set the speed of the rotation based on tenths of a second. Also, choose the type of easing you want to use when the ticks rotate.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_rotate_speed',
			'value' => $values['speed'],
			'after' => __('Tenths of a second', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Ease - 2.0.0 */
				'ease' => array(
					'type' => 'select',
					'name' => '_mtphr_dnt_rotate_ease',
					'value' => $values['ease'],
					'option_keys' => false,
					'options' => array(
						'linear','swing','jswing','easeInQuad','easeInCubic','easeInQuart','easeInQuint','easeInSine','easeInExpo','easeInCirc','easeInElastic','easeInBack','easeInBounce','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc','easeOutElastic','easeOutBack','easeOutBounce','easeInOutQuad','easeInOutCubic','easeInOutQuart','easeInOutQuint','easeInOutSine','easeInOutExpo','easeInOutCirc','easeInOutElastic','easeInOutBack','easeInOutBounce'
					)
				)
			)
		),
		
		/* !Directional navigation - 2.0.0 */
		'directional_nav' => array(
			'heading' => __('Directional navigation', 'ditty-news-ticker'),
			'description' => __('Set the directional navigation options', 'ditty-news-ticker'),
			'help' => __('Enable the directional navigation. Optionally, set the navigation to auto-hide when the user is not hovering over the ticker.', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_rotate_directional_nav',
			'value' => $values['directional_nav'],
			'label' => __('Enable', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Hide - 2.0.0 */
				'hide' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_rotate_directional_nav_hide',
					'value' => $values['directional_nav_hide'],
					'label' => __('Autohide navigation', 'ditty-news-ticker')
				)
			)
		),
		
		/* !Control navigation - 2.0.0 */
		'control_nav' => array(
			'heading' => __('Control navigation', 'ditty-news-ticker'),
			'description' => __('Set the control navigation options', 'ditty-news-ticker'),
			'help' => __('Enable the control navigation and choose the type of display.', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_rotate_control_nav',
			'value' => $values['control_nav'],
			'label' => __('Enable', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Type - 2.0.0 */
				'nav_type' => array(
					'type' => 'radio_buttons',
					'name' => '_mtphr_dnt_rotate_control_nav_type',
					'value' => $values['control_nav_type'],
					'options' => array(
						'button' => __('Buttons', 'ditty-news-ticker'),
						'number' => __('Numbers', 'ditty-news-ticker')	
					)
				)
			)
		),
		
		/* !Disable touchswipe - 2.0.0 */
		'touchswipe' => array(
			'heading' => __('Disable Touchswipe', 'ditty-news-ticker'),
			'description' => __('Disable touchswipe navigation on touch devices', 'ditty-news-ticker'),
			'help' => __('Disable touchswipe navigation on touch devices', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_rotate_disable_touchswipe',
			'value' => $values['disable_touchswipe'],
			'label' => __('Disable', 'ditty-news-ticker')
		),
		
	);
	
	return apply_filters( 'mtphr_dnt_rotate_fields', $fields, $values );	
}
}


/* --------------------------------------------------------- */
/* !Return the list mode values - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_list_values') ) {
function mtphr_dnt_list_values() {
	
	global $post;
	
	$defaults = array(
		'padding' => 0,
		'margin' => 0,
		'spacing' => 10,
		'paging' => '',
		'count' => 10,
		'prev_next' => '',
		'prev_text' => __('« Previous', 'ditty-news-ticker'),
		'next_text' => __('Next »', 'ditty-news-ticker'),
	);
	
	$defaults = apply_filters( 'mtphr_dnt_list_defaults', $defaults );
	
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
	
	return wp_parse_args( $values, $defaults );
}
}

/* --------------------------------------------------------- */
/* !Return the list mode fields - 2.0.2 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_list_fields') ) {
function mtphr_dnt_list_fields() {
	
	$values = mtphr_dnt_list_values();
	
	$fields = array(
		
		/* !Tick padding (& margin) - 2.0.0 */
		'padding' => array(
			'heading' => __('List padding', 'ditty-news-ticker'),
			'description' => __('Set the vertical spacing of the list container', 'ditty-news-ticker'),
			'help' => __('Add custom vertical padding and margins to each of your ticks.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_list_padding',
			'value' => $values['padding'],
			'before' => __('Vertical padding', 'ditty-news-ticker').':',
			'after' => __('pixels', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Ticker margin - 2.0.0 */
				'margin' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_list_margin',
					'value' => $values['margin'],
					'before' => __('Vertical margin', 'ditty-news-ticker').':',
					'after' => __('pixels', 'ditty-news-ticker'),
				)
			)
		),
		
		/* !Tick spacing - 2.0.0 */
		'spacing' => array(
			'heading' => __('Tick spacing', 'ditty-news-ticker'),
			'description' => __('Set the spacing between ticks', 'ditty-news-ticker'),
			'help' => __('Set the amount of space that should be added between the ticks.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_list_tick_spacing',
			'value' => $values['spacing'],
			'after' => __('pixels', 'ditty-news-ticker')
		),
		
		/* !Paging - 2.0.0 */
		'paging' => array(
			'heading' => __('List paging', 'ditty-news-ticker'),
			'description' => __('Break the list up into pages', 'ditty-news-ticker'),
			'help' => __('Break your list up into pages with navigation. Set the number of ticks to show per page and customize the previous and next links.', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_list_tick_paging',
			'value' => $values['paging'],
			'label' => __('Enable', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Scroll pause - 2.0.0 */
				'count' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_list_tick_count',
					'value' => $values['count'],
					'after' => __('Ticks per page', 'ditty-news-ticker')
				),
				
				/* !Previous & next buttons - 2.0.0 */
				'prev_next' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_list_tick_prev_next',
					'value' => $values['prev_next'],
					'label' => __('Enable previous & next links', 'ditty-news-ticker')
				),
				
				/* !Previous text - 2.0.0 */
				'prev_text' => array(
					'type' => 'text',
					'name' => '_mtphr_dnt_list_tick_prev_text',
					'value' => $values['prev_text'],
					'placeholder' => __('Previous text', 'ditty-news-ticker')
				),
				
				/* !Next text - 2.0.0 */
				'next_next' => array(
					'type' => 'text',
					'name' => '_mtphr_dnt_list_tick_next_text',
					'value' => $values['next_text'],
					'placeholder' => __('Next text', 'ditty-news-ticker')
				)
			)
		)
		
	);
	
	return apply_filters( 'mtphr_dnt_list_fields', $fields, $values );
}
}


/* --------------------------------------------------------- */
/* !Return the global values - 2.1.24 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_global_values') ) {
function mtphr_dnt_global_values() {
	
	global $post;
	
	$defaults = array(
		'ajax' => '',
		'title' => '',
		'inline_title' => '',
		'hide' => '',
		'shuffle' => '',
		'reverse' => '',
		'width' => 0,
		'offset' => 20,
		'trim_ticks' => '',
		'pause_button' => '',
		'grid' => '',
		'grid_empty_rows' => '',
		'grid_equal_width' => '',
		'grid_cols' => 2,
		'grid_rows' => 2,
		'grid_padding' => 5,
		'grid_remove_padding' => '',
	);
	
	$defaults = apply_filters( 'mtphr_dnt_global_defaults', $defaults );
	
	$values = array(
		'ajax' => get_post_meta( $post->ID, '_mtphr_dnt_ajax', true ),
		'title' => get_post_meta( $post->ID, '_mtphr_dnt_title', true ),
		'inline_title' => get_post_meta( $post->ID, '_mtphr_dnt_inline_title', true ),
		'hide' => get_post_meta( $post->ID, '_mtphr_dnt_hide', true ),
		'shuffle' => get_post_meta( $post->ID, '_mtphr_dnt_shuffle', true ),
		'reverse' => get_post_meta( $post->ID, '_mtphr_dnt_reverse', true ),
		'width' => get_post_meta( $post->ID, '_mtphr_dnt_ticker_width', true ),
		'offset' => get_post_meta( $post->ID, '_mtphr_dnt_offset', true ),	
		'trim_ticks' => get_post_meta( $post->ID, '_mtphr_dnt_trim_ticks', true ),
		'pause_button' => get_post_meta( $post->ID, '_mtphr_dnt_pause_button', true ),
		'grid' => get_post_meta( $post->ID, '_mtphr_dnt_grid', true ),
		'grid_empty_rows' => get_post_meta( $post->ID, '_mtphr_dnt_grid_empty_rows', true ),
		'grid_equal_width' => get_post_meta( $post->ID, '_mtphr_dnt_grid_equal_width', true ),
		'grid_cols' => get_post_meta( $post->ID, '_mtphr_dnt_grid_cols', true ),
		'grid_rows' => get_post_meta( $post->ID, '_mtphr_dnt_grid_rows', true ),
		'grid_padding' => get_post_meta( $post->ID, '_mtphr_dnt_grid_padding', true ),
		'grid_remove_padding' => get_post_meta( $post->ID, '_mtphr_dnt_grid_remove_padding', true ),
	);
	
	if( !isset($_GET['post']) ) {
		$values['hide'] = 'on';
	}
	
	foreach( $values as $i=>$value ) {
		if( $value == '' ) {
			unset($values[$i]);
		}
	}
	
	return wp_parse_args( $values, $defaults );
}
}

/* --------------------------------------------------------- */
/* !Return the global fields - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_global_fields') ) {
function mtphr_dnt_global_fields() {

	$values = mtphr_dnt_global_values();

	$fields = array(
		
		/* !Title display (& inline display) - 2.0.0 */
		'title' => array(
			'heading' => __('Ticker title', 'ditty-news-ticker'),
			'description' => __('Set the display of the title', 'ditty-news-ticker'),
			'help' => __('Enable the display and set the position of the ticker title.', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_title',
			'value' => $values['title'],
			'label' => __('Display title', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Ticker margin - 2.0.0 */
				'inline' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_inline_title',
					'value' => $values['inline_title'],
					'label' => __('Inline title', 'ditty-news-ticker')				
				)
			)
		),
		
		/* !Shuffle - 2.0.4 */
		'shuffle' => array(
			'heading' => __('Ticker options', 'ditty-news-ticker'),
			'description' => __('General ticker options', 'ditty-news-ticker'),
			'type' => 'container',
			'append' => array(
				
				/* !Hide ticker - 2.0.4 */
				'hide' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_hide',
					'value' => $values['hide'],
					'label' => __('Hide ticker if no ticks exist', 'ditty-news-ticker')
				),
				
				/* !Shuffle ticks - 2.0.4 */
				'shuffle' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_shuffle',
					'value' => $values['shuffle'],
					'label' => __('Randomly shuffle the ticks', 'ditty-news-ticker')
				),
				
				/* !Reverse ticks - 2.0.4 */
				'reverse' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_reverse',
					'value' => $values['reverse'],
					'label' => __('Reverse the order of the ticks', 'ditty-news-ticker')
				),
				
				/* !Trim ticks - 2.0.2 */
				'trim_ticks' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_trim_ticks',
					'value' => $values['trim_ticks'],
					'label' => __('Remove margin and padding from all tick contents', 'ditty-news-ticker')
				),
				
				/* !Pause button - 2.0.4 */
				'pause_button' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_pause_button',
					'value' => $values['pause_button'],
					'label' => __('Add a play/pause button to scrolling and auto-rotating tickers', 'ditty-news-ticker')
				),
			)
		),
		
		/* !Ticker width - 2.0.0 */
		'width' => array(
			'heading' => __('Ticker width', 'ditty-news-ticker'),
			'description' => __('Set a static width for the ticker', 'ditty-news-ticker'),
			'help' => __('Leave blank or set to \'0\' if you want the ticker width to be responsive.', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_ticker_width',
			'value' => $values['width'],
			'after' => __('pixels', 'ditty-news-ticker')
		),
		
		/* !Ticker offset - 2.0.0 */
		'offset' => array(
			'heading' => __('Offset ticks', 'ditty-news-ticker'),
			'description' => __('Set the amount of pixels ticks should start and end off the screen', 'ditty-news-ticker'),
			'type' => 'number',
			'name' => '_mtphr_dnt_offset',
			'value' => $values['offset'],
			'after' => __('pixels', 'ditty-news-ticker')
		),
		
		/* !Grid display - 2.0.0 */
		'grid' => array(
			'heading' => __('Grid Display', 'ditty-news-ticker'),
			'description' => __('Enable a grid display of your tickers and adjust the grid settings', 'ditty-news-ticker'),
			'help' => __('You should only use this option if you need to display multiple ticks at the same time. You should not use this functionality if you are using a normal scrolling ticker.', 'ditty-news-ticker'),
			'type' => 'checkbox',
			'name' => '_mtphr_dnt_grid',
			'value' => $values['grid'],
			'label' => __('Display ticks in a grid', 'ditty-news-ticker'),
			'append' => array(
				
				/* !Empty rows - 2.0.0 */
				'empty_rows' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_grid_empty_rows',
					'value' => $values['grid_empty_rows'],
					'label' => __('Render empty rows', 'ditty-news-ticker')				
				),
				
				/* !Empty rows - 2.0.0 */
				'equal_width' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_grid_equal_width',
					'value' => $values['grid_equal_width'],
					'label' => __('Force equal column width', 'ditty-news-ticker')				
				),
				
				/* !Columns - 2.0.0 */
				'cols' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_grid_cols',
					'value' => $values['grid_cols'],
					'before' => __('Columns', 'ditty-news-ticker')				
				),
				
				/* !Rows - 2.0.0 */
				'rows' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_grid_rows',
					'value' => $values['grid_rows'],
					'before' => __('Rows', 'ditty-news-ticker')				
				),
				
				/* !Cell padding - 2.0.0 */
				'padding' => array(
					'type' => 'number',
					'name' => '_mtphr_dnt_grid_padding',
					'value' => $values['grid_padding'],
					'before' => __('Cell padding', 'ditty-news-ticker')				
				),
				
				/* !Remove padding - 2.0.0 */
				'remove_padding' => array(
					'type' => 'checkbox',
					'name' => '_mtphr_dnt_grid_remove_padding',
					'value' => $values['grid_remove_padding'],
					'label' => __('Remove padding on table edges', 'ditty-news-ticker')				
				)
			)
		),
		
	);
	
	return apply_filters( 'mtphr_dnt_global_fields', $fields, $values );
}
}




/* --------------------------------------------------------- */
/* !Save the custom meta - 2.1.10 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_metabox_save') ) {
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
	
	// Check javascript errors
	$admin_javascript = isset($_POST['_mtphr_dnt_admin_javascript']) ? $_POST['_mtphr_dnt_admin_javascript'] : 'error';
	//update_post_meta( $post_id, '_mtphr_dnt_admin_javascript', $admin_javascript );
	
	// Update the type & mode
	if( isset($_POST['_mtphr_dnt_type']) ) {
	
		$tab = isset($_POST['_mtphr_dnt_admin_tab']) ? sanitize_text_field($_POST['_mtphr_dnt_admin_tab']) : '';
		$type = isset($_POST['_mtphr_dnt_type']) ? sanitize_text_field($_POST['_mtphr_dnt_type']) : 'default';
		$mode = isset($_POST['_mtphr_dnt_mode']) ? sanitize_text_field($_POST['_mtphr_dnt_mode']) : 'scroll';
		
		update_post_meta( $post_id, '_mtphr_dnt_admin_tab', $tab );
		update_post_meta( $post_id, '_mtphr_dnt_type', $type );
		update_post_meta( $post_id, '_mtphr_dnt_mode', $mode );
	}
	
	// Save the default ticks
	$sanitized_ticks = array();
	if( isset($_POST['_mtphr_dnt_ticks']) ) {
		
		$force_breaks = ( isset($_POST['_mtphr_dnt_line_breaks']) && $_POST['_mtphr_dnt_line_breaks'] != '' ) ? 1 : '';
		update_post_meta( $post_id, '_mtphr_dnt_line_breaks', $force_breaks );
		
		$allowed_tags = wp_kses_allowed_html( 'post' );
		$allowed_tags['div']['data-href'] = true;
		$allowed_tags['div']['data-width'] = true;

		if( count($_POST['_mtphr_dnt_ticks']) > 0 ) {
			//echo '<pre>';print_r($_POST['_mtphr_dnt_ticks']);echo '</pre>';
			foreach( $_POST['_mtphr_dnt_ticks'] as $tick ) {
				
				$sanitized_tick = apply_filters( 'mtphr_dnt_sanitized_tick', array(
					'tick' => isset($tick['tick']) ? wp_kses($tick['tick'], $allowed_tags) : '',
					'link' => isset($tick['link']) ? esc_url($tick['link']) : '',
					'target' => isset($tick['target']) ? $tick['target'] : '',
					'nofollow' => isset( $tick['nofollow'] ) ? $tick['nofollow'] : ''
				), $tick, '_mtphr_dnt_ticks');
								
				if( $sanitized_tick ) {
					$sanitized_ticks[] = $sanitized_tick;
				}
			}
		}
	}
	if( $admin_javascript == 'ok' ) {
		update_post_meta( $post_id, '_mtphr_dnt_ticks', $sanitized_ticks );
	}
	
	// Save the mixed ticks
	$sanitized_ticks = array();
	if( isset($_POST['_mtphr_dnt_mixed_ticks']) ) {
		if( count($_POST['_mtphr_dnt_mixed_ticks']) > 0 ) {
			foreach( $_POST['_mtphr_dnt_mixed_ticks'] as $tick ) {
				
				$sanitized_tick = apply_filters( 'mtphr_dnt_sanitized_tick', array(
					'type' => isset($tick['type']) ? $tick['type'] : '',
					'offset' => isset($tick['offset']) ? intval($tick['offset']) : 0,
					'all' => (isset($tick['all']) && $tick['all'] == 'on') ? $tick['all'] : ''
				), $tick, '_mtphr_dnt_mixed_ticks');
				
				if( $sanitized_tick ) {
					$sanitized_ticks[] = $sanitized_tick;
				}
			}
		}
	}
	if( $admin_javascript == 'ok' ) {
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
		$disable_touchswipe = isset($_POST['_mtphr_dnt_rotate_disable_touchswipe']) ? $_POST['_mtphr_dnt_rotate_disable_touchswipe'] : '';
		
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
		update_post_meta( $post_id, '_mtphr_dnt_rotate_disable_touchswipe', $disable_touchswipe );
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
	
		//$ajax = isset($_POST['_mtphr_dnt_ajax']) ? $_POST['_mtphr_dnt_ajax'] : '';
		$title = isset($_POST['_mtphr_dnt_title']) ? $_POST['_mtphr_dnt_title'] : '';
		$inline_title = isset($_POST['_mtphr_dnt_inline_title']) ? $_POST['_mtphr_dnt_inline_title'] : '';
		$hide = isset($_POST['_mtphr_dnt_hide']) ? $_POST['_mtphr_dnt_hide'] : '';
		$shuffle = isset($_POST['_mtphr_dnt_shuffle']) ? $_POST['_mtphr_dnt_shuffle'] : '';
		$reverse = isset($_POST['_mtphr_dnt_reverse']) ? $_POST['_mtphr_dnt_reverse'] : '';
		$width = isset($_POST['_mtphr_dnt_ticker_width']) ? intval($_POST['_mtphr_dnt_ticker_width']) : 0;
		$offset = isset($_POST['_mtphr_dnt_offset']) ? intval($_POST['_mtphr_dnt_offset']) : 20;
		$trim = isset($_POST['_mtphr_dnt_trim_ticks']) ? $_POST['_mtphr_dnt_trim_ticks'] : '';
		$pause_button = isset($_POST['_mtphr_dnt_pause_button']) ? $_POST['_mtphr_dnt_pause_button'] : '';
		$grid = isset($_POST['_mtphr_dnt_grid']) ? $_POST['_mtphr_dnt_grid'] : '';
		$grid_empty_rows = isset($_POST['_mtphr_dnt_grid_empty_rows']) ? $_POST['_mtphr_dnt_grid_empty_rows'] : '';
		$grid_equal_width = isset($_POST['_mtphr_dnt_grid_equal_width']) ? $_POST['_mtphr_dnt_grid_equal_width'] : '';
		$grid_cols = isset($_POST['_mtphr_dnt_grid_cols']) ? intval($_POST['_mtphr_dnt_grid_cols']) : 2;
		$grid_rows = isset($_POST['_mtphr_dnt_grid_rows']) ? intval($_POST['_mtphr_dnt_grid_rows']) : 2;
		$grid_padding = isset($_POST['_mtphr_dnt_grid_padding']) ? intval($_POST['_mtphr_dnt_grid_padding']) : 5;
		$grid_remove_padding = isset($_POST['_mtphr_dnt_grid_remove_padding']) ? $_POST['_mtphr_dnt_grid_remove_padding'] : '';
		
		//update_post_meta( $post_id, '_mtphr_dnt_ajax', $ajax );
		update_post_meta( $post_id, '_mtphr_dnt_title', $title );
		update_post_meta( $post_id, '_mtphr_dnt_inline_title', $inline_title );
		update_post_meta( $post_id, '_mtphr_dnt_hide', $hide );
		update_post_meta( $post_id, '_mtphr_dnt_shuffle', $shuffle );
		update_post_meta( $post_id, '_mtphr_dnt_reverse', $reverse );
		update_post_meta( $post_id, '_mtphr_dnt_ticker_width', $width );
		update_post_meta( $post_id, '_mtphr_dnt_offset', $offset );
		update_post_meta( $post_id, '_mtphr_dnt_trim_ticks', $trim );
		update_post_meta( $post_id, '_mtphr_dnt_pause_button', $pause_button );
		update_post_meta( $post_id, '_mtphr_dnt_grid', $grid );
		update_post_meta( $post_id, '_mtphr_dnt_grid_empty_rows', $grid_empty_rows );
		update_post_meta( $post_id, '_mtphr_dnt_grid_equal_width', $grid_equal_width );
		update_post_meta( $post_id, '_mtphr_dnt_grid_cols', $grid_cols );
		update_post_meta( $post_id, '_mtphr_dnt_grid_rows', $grid_rows );
		update_post_meta( $post_id, '_mtphr_dnt_grid_padding', $grid_padding );
		update_post_meta( $post_id, '_mtphr_dnt_grid_remove_padding', $grid_remove_padding );
	}
}
}
add_action( 'save_post', 'mtphr_dnt_metabox_save' );

