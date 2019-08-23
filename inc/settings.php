<?php

/* --------------------------------------------------------- */
/* !Create the settings page - 2.1.11 */
/* --------------------------------------------------------- */

function mtphr_dnt_settings_menu() {

	add_submenu_page(
		'edit.php?post_type=ditty_news_ticker',		// The ID of the top-level menu page to which this submenu item belongs
		__( 'Settings', 'ditty-news-ticker' ),		// The value used to populate the browser's title bar when the menu page is active
		__( 'Settings', 'ditty-news-ticker' ),		// The label of this submenu item displayed in the menu
		'manage_ditty_news_ticker_settings',			// What roles are able to access this submenu item
		'mtphr_dnt_settings',											// The ID used to represent this submenu item
		'mtphr_dnt_settings_display'							// The callback function used to render the options for this submenu item
	);
}
add_action( 'admin_menu', 'mtphr_dnt_settings_menu', 9 );


/* --------------------------------------------------------- */
/* !General options section callback 1.0.6 */
/* --------------------------------------------------------- */

function mtphr_dnt_general_settings_callback() {
	?>
	<div style="margin-bottom: 20px;">
		<h4 style="margin-top:0;"><?php _e( 'The global settings to your news tickers.', 'ditty-news-ticker' ); ?></h4>
	</div>
	<?php
}


/* --------------------------------------------------------- */
/* !Render the settings page with tabs - 2.1.23 */
/* --------------------------------------------------------- */

function mtphr_dnt_settings_display( $active_tab = null ) {
	?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">

		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Ditty News Ticker Settings', 'ditty-news-ticker' ); ?></h2>
		<?php settings_errors(); ?>

		<?php
		$tabs = mtphr_dnt_settings_tabs();
		$active_tab = isset( $_GET['tab'] ) ? esc_html($_GET['tab']) : 'general';
		?>

		<ul style="margin-bottom:20px;" class="subsubsub">
			<?php
			$num_tabs = count($tabs);
			$count = 0;
			foreach( $tabs as $key=>$tab ) {
				$count++;
				$current = ($key==$active_tab) ? 'class="current"' : '';
				$sep = ($count!=$num_tabs) ? ' |' : '';
				echo '<li><a href="?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab='.$key.'" '.$current.'>'.ucfirst($key).'</a>'.$sep.'</li>';
			}
			?>
		</ul>

		<br class="clear" />

		<form method="post" action="options.php">
			<?php
			settings_fields( $tabs[$active_tab] );
			do_settings_sections( $tabs[$active_tab] );		
			echo apply_filters( 'mtphr_dnt_settings_submit_button', get_submit_button() );
			?>
		</form>

	</div><!-- /.wrap -->
	<?php
}



/* --------------------------------------------------------- */
/* !Get the settings - 1.4.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_general_settings') ) {
function mtphr_dnt_general_settings() {
	$settings = get_option( 'mtphr_dnt_general_settings', array() );
	return wp_parse_args( $settings, mtphr_dnt_general_settings_defaults() );
}
}
if( !function_exists('mtphr_dnt_general_settings_defaults') ) {
function mtphr_dnt_general_settings_defaults() {
	$defaults = array(
		'wysiwyg' => '',
		'edit_links' => '',
		'css' => ''
	);
	return $defaults;
}
}



/* --------------------------------------------------------- */
/* !Setup the settings - 1.4.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_initialize_settings() {

	$settings = mtphr_dnt_general_settings();
	
	
	/* --------------------------------------------------------- */
	/* !Add the setting sections - 1.4.0 */
	/* --------------------------------------------------------- */

	add_settings_section( 'mtphr_dnt_general_settings_section', __( 'Ditty News Ticker settings', 'ditty-news-ticker' ), false, 'mtphr_dnt_general_settings' );
	
	
	/* --------------------------------------------------------- */
	/* !Add the settings - 1.4.0 */
	/* --------------------------------------------------------- */

	/* Visual Editor */
	$title = mtphr_dnt_settings_label( __( 'Visual Editor', 'ditty-news-ticker' ), __('Use the visual editor to create tick contents', 'ditty-news-ticker') );
	add_settings_field( 'mtphr_dnt_general_settings_wysiwyg', $title, 'mtphr_dnt_general_settings_wysiwyg', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_section', array('settings' => $settings) );
	
	/* Quick Edit Links */
	$title = mtphr_dnt_settings_label( __( 'Quick Edit Links', 'ditty-news-ticker' ), __('Add quick edit links on the front-end of the site for editors and admins', 'ditty-news-ticker') );
	add_settings_field( 'mtphr_dnt_general_settings_edit_links', $title, 'mtphr_dnt_general_settings_edit_links', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_section', array('settings' => $settings) );	
	
	/* Custom CSS */
	$title = mtphr_dnt_settings_label( __( 'Custom CSS', 'ditty-news-ticker' ), __('Add custom css to style your ticker without modifying any external files', 'ditty-news-ticker') );
	add_settings_field( 'mtphr_dnt_general_settings_css', $title, 'mtphr_dnt_general_settings_css', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_section', array('settings' => $settings) );

	
	/* --------------------------------------------------------- */
	/* !Register the settings - 1.4.0 */
	/* --------------------------------------------------------- */

	if( false == get_option('mtphr_dnt_general_settings') ) {
		add_option( 'mtphr_dnt_general_settings' );
	}
	register_setting( 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_sanitize' );
}
add_action( 'admin_init', 'mtphr_dnt_initialize_settings' );



/* --------------------------------------------------------- */
/* !WYSIWYG - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_general_settings_wysiwyg') ) {
function mtphr_dnt_general_settings_wysiwyg( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_dnt_general_settings_wysiwyg">';
		echo '<label><input type="checkbox" name="mtphr_dnt_general_settings[wysiwyg]" value="1" '.checked('1', $settings['wysiwyg'], false).' /> '.__('Use the visual editor for ticks', 'ditty-news-ticker').'</label>';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Edit Links - 1.4.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_general_settings_edit_links') ) {
function mtphr_dnt_general_settings_edit_links( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_dnt_general_settings_edit_links">';
		echo '<label><input type="checkbox" name="mtphr_dnt_general_settings[edit_links]" value="1" '.checked('1', $settings['edit_links'], false).' /> '.__('Add quick edit links', 'ditty-news-ticker').'</label>';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !CSS - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_general_settings_css') ) {
function mtphr_dnt_general_settings_css( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_dnt_general_settings_css">';
		echo '<div class="mtphr-dnt-codemirror mtphr-dnt-codemirror-css">';
			echo '<textarea name="mtphr_dnt_general_settings[css]" cols="60" rows="4">'.$settings['css'].'</textarea>';
		echo '</div>';
	echo '</div>';
}
}



/* --------------------------------------------------------- */
/* !Sanitize the setting fields - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_general_settings_sanitize') ) {
function mtphr_dnt_general_settings_sanitize( $fields ) {

	$fields['css'] = isset( $fields['css'] ) ? wp_kses_post($fields['css']) : '';

	return $fields;
}
}



/* --------------------------------------------------------- */
/* !Create a settings label - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_settings_label') ) {
function mtphr_dnt_settings_label( $title, $description = '' ) {

	$label = '<div class="mtphr-dnt-label-alt">';
		$label .= '<label>'.$title.'</label>';
		if( $description != '' ) {
			$label .= '<small>'.$description.'</small>';
		}
	$label .= '</div>';

	return $label;
}
}
