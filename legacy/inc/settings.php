<?php

/* --------------------------------------------------------- */
/* !Create the settings page - 2.1.11 */
/* --------------------------------------------------------- */

function mtphr_dnt_settings_menu() {

	add_submenu_page(
		'edit.php?post_type=ditty',		// The ID of the top-level menu page to which this submenu item belongs
		__( 'Settings', 'ditty-news-ticker' ) . ' ' . __( '(Legacy)', 'ditty-news-ticker' ),
		__( 'Settings', 'ditty-news-ticker' ) . ' <small><em>' . __( '(Legacy)', 'ditty-news-ticker' ) . '</em></small>',
		'manage_ditty_news_ticker_settings',			// What roles are able to access this submenu item
		'mtphr_dnt_settings',											// The ID used to represent this submenu item
		'mtphr_dnt_settings_display'							// The callback function used to render the options for this submenu item
	);
}
add_action( 'admin_menu', 'mtphr_dnt_settings_menu', 15 );


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
			if ( isset( $tabs[$active_tab] ) ) {
				settings_fields( $tabs[$active_tab] );
				do_settings_sections( $tabs[$active_tab] );
			}
			echo apply_filters( 'mtphr_dnt_settings_submit_button', get_submit_button() );
			?>
		</form>

	</div><!-- /.wrap -->
	<?php
}



/* --------------------------------------------------------- */
/* !Get the settings - 2.2.10 */
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
		'private_posts' => '',
		'css' => ''
	);
	return $defaults;
}
}



/* --------------------------------------------------------- */
/* !Setup the settings - 2.2.10 */
/* --------------------------------------------------------- */

function mtphr_dnt_initialize_settings() {

	$settings = mtphr_dnt_general_settings();


	add_settings_section( 'mtphr_dnt_general_settings_section', __( 'Ditty News Ticker settings', 'ditty-news-ticker' ), false, 'mtphr_dnt_general_settings' );


	/* Visual Editor */
	$title = mtphr_dnt_settings_label( __( 'Visual Editor', 'ditty-news-ticker' ), __('Use the visual editor to create tick contents', 'ditty-news-ticker') );
	add_settings_field( 'mtphr_dnt_general_settings_wysiwyg', $title, 'mtphr_dnt_general_settings_wysiwyg', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_section', array('settings' => $settings) );
	
	/* Quick Edit Links */
	$title = mtphr_dnt_settings_label( __( 'Quick Edit Links', 'ditty-news-ticker' ), __('Add quick edit links on the front-end of the site for editors and admins', 'ditty-news-ticker') );
	add_settings_field( 'mtphr_dnt_general_settings_edit_links', $title, 'mtphr_dnt_general_settings_edit_links', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_section', array('settings' => $settings) );	
	
	/* Private Posts */
	$title = mtphr_dnt_settings_label( __( 'Private Ticker Posts', 'ditty-news-ticker' ), __('Make all ticker posts private', 'ditty-news-ticker') );
	add_settings_field( 'mtphr_dnt_general_settings_private_posts', $title, 'mtphr_dnt_general_settings_private_posts', 'mtphr_dnt_general_settings', 'mtphr_dnt_general_settings_section', array('settings' => $settings) );	
	
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
/* !Private Posts - 2.2.10 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_general_settings_private_posts') ) {
function mtphr_dnt_general_settings_private_posts( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_dnt_general_settings_private_posts">';
		echo '<label><input type="checkbox" name="mtphr_dnt_general_settings[private_posts]" value="1" '.checked('1', $settings['private_posts'], false).' /> '.__('Private ticker posts', 'ditty-news-ticker').'</label>';
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
/* !Sanitize the setting fields - 3.0.27 */
/* --------------------------------------------------------- */

function mtphr_dnt_general_settings_sanitize( $fields ) {
	if ( ! is_array( $fields ) ) {
		$fields = array();
	}
	$fields['css'] = isset( $fields['css'] ) ? wp_kses_post($fields['css']) : '';
	
	// Clear the permalinks
	$settings = mtphr_dnt_general_settings();
	$private_posts = isset( $fields['private_posts'] ) ? esc_attr($fields['private_posts']) : '';
	if ( $settings['private_posts'] != $private_posts ) {
		flush_rewrite_rules( false );
	}

	return $fields;
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
