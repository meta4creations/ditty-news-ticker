<?php
	
/* --------------------------------------------------------- */
/* !Add the plugin updater - 2.1.17 */
/* --------------------------------------------------------- */

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}


/**
 * Get an option value
 * @since 2.2.6
 */
function mtphr_dnt_get_option( $option, $default=false ) {
	if ( is_multisite() ) {
		return get_site_option( $option, $default );
	} else {
		return get_option( $option, $default );
	}
}


/**
 * Update an option value
 * @since 2.2.6
 */
function mtphr_dnt_update_option( $option, $value ) {
	if ( is_multisite() ) {
		return update_site_option( $option, $value );
	} else {
		return update_option( $option, $value );
	}
}


/**
 * Return strings
 * @since 2.1.17
 */
function mtphr_dnt_strings() {	
	
	$strings = array(
		'successful_activation' => __('Your license is activated!', 'ditty-news-ticker'),
		'unsuccessful_activation' => __('Sorry, this license is not valid.', 'ditty-news-ticker'),
		'successful_deactivation' => __('Your license has been deactivated.', 'ditty-news-ticker'),
		'unsuccessful_deactivation' => __('Sorry, something went wrong with the deactivation.', 'ditty-news-ticker'),
		'deactivate_license' => __('Deactivate License', 'ditty-news-ticker'),
		'activate_license' => __('Activate License', 'ditty-news-ticker'),
		'refresh_license' => __('Refresh License', 'ditty-news-ticker')
	);
	
	return apply_filters( 'mtphr_dnt_license_strings', $strings );
}


/**
 * Return a single string
 * @since 2.1.17
 */
function mtphr_dnt_string( $slug ) {	

	$strings = mtphr_dnt_strings();
	if( isset($strings[$slug]) ) {
		return $strings[$slug];
	} else {
		return sprintf(__('% string does not exist', 'ditty-news-ticker'), $slug);
	}
}


/**
 * Return extension license data
 * @since 2.1.17
 */
function mtphr_dnt_all_license_data() {	
	
	$data = array(
/*
		'ditty-test-ticker' => array(
			'version' => '2.3',
			'item_name' => 'Ditty Test Ticker',
			'path' => 'ditty-test-ticker/ditty-test-ticker.php',
			'author' => 'Metaphor Creations'
		)
*/
	);
	
	return apply_filters( 'mtphr_dnt_license_data', $data );
}


/**
 * Initialize the extension updaters
 * @since 2.2.5
 */
function mtphr_dnt_plugin_updater() {
	
	// Get the various license data
	$all_license_data = mtphr_dnt_all_license_data();

	// retrieve our license key from the DB
	$mtphr_edd_licenses = mtphr_dnt_get_option( 'mtphr_edd_licenses', array() );
	
	if( is_array($all_license_data) && count($all_license_data) > 0 ) {
		foreach( $all_license_data as $slug=>$data ) {
			
			$license_key = isset($mtphr_edd_licenses[$slug]) ? trim($mtphr_edd_licenses[$slug]) : '';
		
			// setup the updater
			$edd_updater = new EDD_SL_Plugin_Updater( MTPHR_DNT_STORE_URL, $data['path'], array(
					'version' 	=> $data['version'], 																							// current version number
					'license' 	=> $license_key, 																									// license key (used get_option above to retrieve from DB)
					'item_name' => $data['item_name'], 																						// name of this plugin
					'author' 	=> isset($data['author']) ? $data['author'] : 'Metaphor Creations'  // author of this plugin
				)
			);
			
		}
	}
}
add_action( 'admin_init', 'mtphr_dnt_plugin_updater', 0 );


/**
 * Create the settings page
 * @since 2.2.5
 */
function mtphr_dnt_edd_settings_menu() {
	
	global $mtphr_edd_license_page;
	
	$mtphr_edd_licenses = mtphr_dnt_get_option( 'mtphr_edd_licenses', array() );
	$mtphr_edd_license_data = mtphr_dnt_get_option( 'mtphr_edd_license_data', array() );
	
	if( empty($mtphr_edd_license_page) ) {
		
		$all_license_data = mtphr_dnt_all_license_data();
		if( is_array($all_license_data) && count($all_license_data) > 0 ) {

			$mtphr_edd_license_page = 'ditty-news-ticker';
			
			if ( is_multisite() ) {
				
				if ( is_network_admin() ) {

					add_submenu_page(
						'settings.php',
						__('Metaphor Licenses', 'ditty-news-ticker'),
						__('Metaphor Licenses', 'ditty-news-ticker'),
						'manage_options',
						'mtphr_licenses',
						'mtphr_dnt_licenses_display'
					);	
				}
				
			} else {
			
				add_options_page(
					__('Metaphor Licenses', 'ditty-news-ticker'),
					__('Metaphor Licenses', 'ditty-news-ticker'),
					'manage_options',
					'mtphr_licenses',
					'mtphr_dnt_licenses_display'
				);
			}
		}
	}
}
add_action( 'admin_menu', 'mtphr_dnt_edd_settings_menu', 9 );
add_action( 'network_admin_menu', 'mtphr_dnt_edd_settings_menu', 9 );


/**
 * Render the settings page
 * @since 2.1.17
 */
function mtphr_dnt_licenses_display( $active_tab = null ) {
	?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">

		<h2><?php _e('Metaphor Creations Licenses', 'ditty-news-ticker'); ?></h2>
		<?php //settings_errors(); ?>

		<br class="clear" />

		<form method="post" action="options.php">
			<?php
			settings_fields( 'mtphr_edd_licenses' );
			do_settings_sections( 'mtphr_edd_licenses' );		
			?>
		</form>

	</div><!-- /.wrap -->
	<?php
}


/**
 * Setup the settings
 * @since 2.2.5
 */
function mtphr_dnt_edd_initialize_settings() {
	
	global $mtphr_edd_license_page;
	
	// Get the various license data
	$all_license_data = mtphr_dnt_all_license_data();
		
	// Add the setting sections
	if( $mtphr_edd_license_page == 'ditty-news-ticker' ) {
		add_settings_section( 'mtphr_edd_licenses_section', false, false, 'mtphr_edd_licenses' );
	}
	
	// Add the settings
	if( is_array($all_license_data) && count($all_license_data) > 0 ) {
		foreach( $all_license_data as $slug=>$data ) {
			add_settings_field( $slug, $data['item_name'], 'mtphr_dnt_license', 'mtphr_edd_licenses', 'mtphr_edd_licenses_section', array('slug'=>$slug) );
		}
	}
	
	// Register the settings
	if( $mtphr_edd_license_page == 'ditty-news-ticker' ) {
		if( false == mtphr_dnt_get_option('mtphr_edd_licenses') ) {
			add_option( 'mtphr_edd_licenses' );
		}
		register_setting( 'mtphr_edd_licenses', 'mtphr_edd_licenses', 'mtphr_edd_licenses_sanitize' );
	}
}
add_action( 'admin_init', 'mtphr_dnt_edd_initialize_settings' );


/**
 * License field render
 * @since 2.2.5
 */
if( !function_exists('mtphr_dnt_license') ) {
function mtphr_dnt_license( $args ) {

	$slug = $args['slug'];
	$mtphr_edd_licenses = mtphr_dnt_get_option( 'mtphr_edd_licenses', array() );
	$mtphr_edd_license_data = mtphr_dnt_get_option( 'mtphr_edd_license_data', array() );
		
	$license = '';
	$data = array();
	$status = 'none';
	$expires = false;
	if( isset($mtphr_edd_licenses[$slug]) ) {
		$license = isset( $mtphr_edd_licenses[$slug] ) ? $mtphr_edd_licenses[$slug] : '';
		$data = isset( $mtphr_edd_license_data[$slug] ) ? $mtphr_edd_license_data[$slug] : array();
		$status = isset( $data->license ) ? $data->license : 'none';
		$expires = isset( $data->expires ) ? date( 'F j, Y', strtotime($data->expires) ) : false;
		$expires = ( isset($data->expires) && $data->expires == 'lifetime' ) ? $data->expires : $expires;
		$activations_left = ( isset($data->activations_left) && $data->activations_left <= 0 ) ? 'none' : '';
	}
	$customer_dashboard = 'https://www.metaphorcreations.com/customer-dashboard/';
	?>
	<div class="mtphr-dnt-license-group mtphr-dnt-license-<?php echo $status; ?>">
		
		<?php wp_nonce_field( 'mtphr_dnt_license_nonce', 'mtphr_dnt_license_nonce' ); ?>
		<input class="mtphr_dnt_license_key regular-text" name="mtphr_edd_licenses[<?php echo $slug; ?>]" type="text" class="regular-text" placeholder="<?php _e('Add your license key here', 'ditty-news-ticker'); ?>" value="<?php esc_attr_e( $license ); ?>" />
		<input type="submit" class="button-secondary ditty-news-ticker-license-deactivate" data-slug="<?php echo $slug; ?>" value="<?php echo mtphr_dnt_string('deactivate_license'); ?>"/>
		
		<?php $activate_class = ($status == 'valid') ? ' button' : ' button-primary'; ?>
		<?php $activate_string = ($status == 'valid') ? 'refresh_license' : 'activate_license'; ?>
		<input type="submit" class="ditty-news-ticker-license-activate<?php echo $activate_class; ?>" data-slug="<?php echo $slug; ?>" value="<?php echo mtphr_dnt_string($activate_string); ?>"/>
		<span class="spinner mtphr-dnt-license-spinner"></span>
		
		<div class="mtphr_dnt_license_description">
			
			<?php if( $status !== false && $status == 'valid' ) { ?>	
				<?php if( $expires == 'lifetime' ) { ?>
					<p><em><?php _e('Your license is activated!', 'ditty-news-ticker'); ?></em></p>
				<?php } else { ?>
					<p><em><?php printf( __('Your license key expires on %s', 'ditty-news-ticker'), $expires); ?></em></p>
				<?php } ?>
			<?php } elseif( $status !== false && $status == 'invalid' ) { ?>
				
				<?php
				$error = isset( $data->error ) ? $data->error : false;
				switch( $error ) {
					case 'no_activations_left':
						echo '<p><em>'.__('Sorry, it looks like all of your licenses have already been activated.', 'ditty-news-ticker').'</em></p>';
						echo '<p><em>'.sprintf(__('View your license activations <a href="%s" target="_blank">here</a>', 'ditty-news-ticker'), $customer_dashboard).'</em></p>';
						break;
						
					default:
						if( $license != '' ) {
							echo '<p><em>'.sprintf(__('Sorry, this license is not valid. View your licenses <a href="%s" target="_blank">here</a>', 'ditty-news-ticker'), $customer_dashboard).'</em></p>';
						}
						break;
				}
			} elseif( $status !== false ) {
				switch( $status ) {
					
					case 'expired':
						echo '<p><em>'.sprintf(__('Sorry, your license has expired. Update your license <a href="%s" target="_blank">here</a>', 'ditty-news-ticker'), $customer_dashboard).'</em></p>';
						break;
						
					case 'disabled':
						echo '<p><em>'.__('Sorry, your license has been disabled.', 'ditty-news-ticker').'</em></p>';
						break;
						
					case 'site_inactive':
						if( $activations_left == 'none' ) {
							echo '<p><em>'.__('Sorry, it looks like all of your licenses have already been activated.', 'ditty-news-ticker').'</em></p>';
							echo '<p><em>'.sprintf(__('View your license activations <a href="%s" target="_blank">here</a>', 'ditty-news-ticker'), $customer_dashboard).'</em></p>';
						}
						break;
						
					default:
						break;
				}
				
			} ?>
			
		</div>
	</div>
	<?php
}
}


/**
 * Sanitize the setting fields
 * @since 2.2.5
 */
if( !function_exists('mtphr_edd_licenses_sanitize') ) {
function mtphr_edd_licenses_sanitize( $new ) {
	
	$mtphr_edd_licenses = mtphr_dnt_get_option( 'mtphr_edd_licenses', array() );
	$mtphr_edd_license_data = mtphr_dnt_get_option( 'mtphr_edd_license_data', array() );
	
	if( is_array($new) && count($new) > 0 ) {
		foreach( $new as $product=>$license ) {
			
			// If there is a new license, reset the data
			if( isset($mtphr_edd_licenses[$product]) && $mtphr_edd_licenses[$product] !== $license ) {
				unset( $mtphr_edd_license_data[$product] );
			}	
		}
	}
	
	return $new;
}
}


/**
 * Check licenses daily
 * @since 2.2.5
 */
function mtphr_dnt_license_check() {
	
	global $wp_version;

	$mtphr_edd_licenses = mtphr_dnt_get_option( 'mtphr_edd_licenses', array() );
	$mtphr_edd_license_data = mtphr_dnt_get_option( 'mtphr_edd_license_data', array() );
	
	$all_license_data = mtphr_dnt_all_license_data();
	if( is_array($all_license_data) && count($all_license_data) > 0 ) {
		foreach( $all_license_data as $slug=>$data ) {
			
			$license_key = isset($mtphr_edd_licenses[$slug]) ? trim($mtphr_edd_licenses[$slug]) : '';
		
			$api_params = array(
				'edd_action' 	=> 'check_license',
				'license' 		=> $license_key,
				'item_name' 	=> urlencode( $data['item_name'] ),
				'url'       	=> home_url()
			);
		
			// Call the custom API.
			$response = wp_remote_post( MTPHR_DNT_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		
			if ( is_wp_error( $response ) )
				return false;
		
			// decode & store the license data
			$mtphr_edd_license_data[$slug] = json_decode( wp_remote_retrieve_body( $response ) );
		}
	}
	
	mtphr_dnt_update_option( 'mtphr_edd_license_data', $mtphr_edd_license_data );
	
}
add_action( 'mtphr_dnt_license_check_action', 'mtphr_dnt_license_check' );


/**
 * Enable daily license check
 * @since 2.1.17
 */
function mtphr_dnt_enable_daily_license_check() {
	if(	!wp_next_scheduled('mtphr_dnt_license_check_action') ) {
		wp_schedule_event( time(), 'daily', 'mtphr_dnt_license_check_action' );
	}
}
register_activation_hook('ditty-news-ticker/ditty-news-ticker.php', 'mtphr_dnt_enable_daily_license_check');


/**
 * Disable daily license check
 * @since 2.1.17
 */
function mtphr_dnt_disable_daily_license_check() {
	wp_clear_scheduled_hook( 'mtphr_dnt_license_check_action' );
}
register_deactivation_hook('ditty-news-ticker/ditty-news-ticker.php', 'mtphr_dnt_disable_daily_license_check');


/**
 * Add a license check notice
 * @since 2.2.5
 */
function mtphr_dnt_license_bug() {
	
	if ( is_multisite() ) {
		if ( ! is_super_admin( get_current_user_id() ) ) {
			return;
		}
	}
	
	// Get the various license data
	$all_license_data = mtphr_dnt_all_license_data();
	
	// Check if the notice has been disabled
	$mtphr_edd_license_notices = mtphr_dnt_get_option( 'mtphr_edd_license_notices', array() );
	
	$display_notice = false;
	$slugs = array();
	
	ob_start();

		if ( is_multisite() ) {
			$url = network_admin_url( 'settings.php?page=mtphr_licenses' );
		} else {
			$url = admin_url( 'options-general.php?page=mtphr_licenses' );
		}
		
		if( is_array($all_license_data) && count($all_license_data) > 0 ) {
			foreach( $all_license_data as $slug=>$data ) {
				
				if( !isset($mtphr_edd_license_notices[$slug]) ) {
					
					if( !$display_notice ) {
						echo '<p style="margin-bottom:0;">'.sprintf(__('Don\'t forget to <a href="%1s">activate your license</a> for your Ditty Extensions!', 'ditty-news-ticker'), $url).'</p>';
						echo '<ul style="list-style:square;list-style-position:inside;margin:0;">';
					}					
					
					$display_notice = true;
					$slugs[] = $slug;
					
					echo '<li>'.$data['item_name'].'</li>';
				}
			}
			
			if( $display_notice ) {
				echo '</ul>';
			}
		}

  $notices = ob_get_clean();

	if( $display_notice ) {
		echo '<div id="ditty-news-ticker-license-notice" class="notice notice-warning is-dismissible" data-slugs="'.implode(',', $slugs).'">';
			echo $notices;
		echo '</div>';
	}

}
add_action('admin_notices', 'mtphr_dnt_license_bug' );


/**
 * Dismiss the bug via ajax
 * @since 2.2.5
 */
function mtphr_dnt_license_bug_dismiss() {

	// Check the nonce
	check_ajax_referer( 'ditty-news-ticker', 'security' );
	
	$slugs = isset($_POST['slugs']) ? explode(',', $_POST['slugs']) : array();
	
	$mtphr_edd_license_notices = mtphr_dnt_get_option( 'mtphr_edd_license_notices', array() );
	
	if( is_array($slugs) && count($slugs) > 0 ) {
		foreach( $slugs as $slug ) {
			$mtphr_edd_license_notices[$slug] = '1';
		}
	}

	mtphr_dnt_update_option( 'mtphr_edd_license_notices', $mtphr_edd_license_notices );

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_dnt_license_bug_dismiss', 'mtphr_dnt_license_bug_dismiss' );


/**
 * Deactivate a license via ajax
 * @since 2.2.5
 */
function mtphr_dnt_license_deactivate_ajax() {

	// Check the nonce
	check_ajax_referer( 'ditty-news-ticker', 'security' );
	
	$slug = isset($_POST['slug']) ? $_POST['slug'] : false;
	$all_license_data = mtphr_dnt_all_license_data();
	
	$action = 'success';
	$message = '';
	$ajax_response = array();
	
	if( !isset($all_license_data[$slug]) ) {
		$action = 'fail';
		$message = __('This license is not active.', 'ditty-news-ticker');
	}
	
	if( !$slug ) {
		$action = 'fail';
		$message = __('Something went wrong, please try again.', 'ditty-news-ticker');
	}

	if( $slug && $all_license_data[$slug] ) {
		
		$mtphr_edd_licenses = mtphr_dnt_get_option( 'mtphr_edd_licenses', array() );
		$mtphr_edd_license_notices = mtphr_dnt_get_option( 'mtphr_edd_license_notices', array() );
		if( !is_array($mtphr_edd_licenses) ) {
			$mtphr_edd_licenses = array();
		}
		if( !is_array($mtphr_edd_license_notices) ) {
			$mtphr_edd_license_notices = array();
		}
		
		$license = isset( $mtphr_edd_licenses[$slug] ) ? trim( $mtphr_edd_licenses[$slug] ) : '';
	
		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( $all_license_data[$slug]['item_name'] ), // the name of our product in EDD
			'url'       => home_url()
		);
	
		// Call the custom API.
		$response = wp_remote_post( MTPHR_DNT_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	
		// make sure the response came back okay
		if( is_wp_error($response) ) {
			$action = 'fail';
			$message = __('Something went wrong, please try again.', 'ditty-news-ticker');
			$ajax_response = $response; 
		
		} else {
		
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body($response) );
			$ajax_response = $license_data; 
			
			$status = isset( $license_data->license ) ? $license_data->license : 'none';
		
			// $license_data->license will be either "deactivated" or "failed"
			if( $status == 'deactivated' ) {
				$mtphr_edd_license_data = mtphr_dnt_get_option( 'mtphr_edd_license_data', array() );
				if( isset($mtphr_edd_license_data[$slug]) ) {
					unset($mtphr_edd_license_data[$slug]);
					mtphr_dnt_update_option( 'mtphr_edd_license_data', $mtphr_edd_license_data );
				}
				if( isset($mtphr_edd_license_notices[$slug]) ) {
					unset($mtphr_edd_license_notices[$slug]);
					mtphr_dnt_update_option( 'mtphr_edd_license_notices', $mtphr_edd_license_notices );
				}
			}
			
			if( $status == 'deactivated' ) {
				$message = mtphr_dnt_string('successful_deactivation');
			} else {
				$message = mtphr_dnt_string('unsuccessful_deactivation');
			}
			
		}
	}

	$return = array(
		'status' => $status,
		'message' => $message,
		'ajax_response' => $ajax_response
	);

	wp_send_json( $return );
}
add_action( 'wp_ajax_mtphr_dnt_license_deactivate_ajax', 'mtphr_dnt_license_deactivate_ajax' );


/**
 * Aactivate a license via ajax
 * @since 2.2.5
 */
function mtphr_dnt_license_activate_ajax() {

	// Check the nonce
	check_ajax_referer( 'ditty-news-ticker', 'security' );
	
	$slug = isset($_POST['slug']) ? $_POST['slug'] : false;
	$license = isset($_POST['license']) ? trim(sanitize_text_field($_POST['license'])) : false;
	$status = 'fail';
	$message = '';
	$ajax_response = array();
	
	if( !$license ) {
		$message = __('Please add a valid license.', 'ditty-news-ticker');
	}
	
	if( !$slug ) {
		$message = __('Something went wrong, please try again.', 'ditty-news-ticker');
	}
	
	if( $slug ) {
		
		$all_license_data = mtphr_dnt_all_license_data();
		$mtphr_edd_licenses = mtphr_dnt_get_option( 'mtphr_edd_licenses', array() );
		$mtphr_edd_license_notices = mtphr_dnt_get_option( 'mtphr_edd_license_notices', array() );
		if( !is_array($mtphr_edd_licenses) ) {
			$mtphr_edd_licenses = array();
		}
		if( !is_array($mtphr_edd_license_notices) ) {
			$mtphr_edd_license_notices = array();
		}
	
		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( $all_license_data[$slug]['item_name'] ), // the name of our product in EDD
			'url'       => home_url()
		);
	
		// Call the custom API.
		$response = wp_remote_post( MTPHR_DNT_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	
		// make sure the response came back okay
		if( is_wp_error($response) ) {
			$action = 'fail';
			$message = __('Something went wrong, please try again.', 'ditty-news-ticker');
			$ajax_response = $response; 
		
		} else {
			
			// Decode the response
			$license_data = json_decode( wp_remote_retrieve_body($response) );
			$ajax_response = $license_data; 
			
			$status = isset( $license_data->license ) ? $license_data->license : 'none';
			
			// Store the license data
			$mtphr_edd_license_data = mtphr_dnt_get_option( 'mtphr_edd_license_data', array() );
			$mtphr_edd_license_data[$slug] = $license_data;
			mtphr_dnt_update_option( 'mtphr_edd_license_data', $mtphr_edd_license_data );

			// Store the license
			$mtphr_edd_licenses[$slug] = $license;
			mtphr_dnt_update_option( 'mtphr_edd_licenses', $mtphr_edd_licenses );
			
			// Update the license notice
			$mtphr_edd_license_notices[$slug] = $slug;
			mtphr_dnt_update_option( 'mtphr_edd_license_notices', $mtphr_edd_license_notices );

			if( $status == 'valid' ) {
				$message = mtphr_dnt_string('successful_activation');
			} else {
				$message = mtphr_dnt_string('unsuccessful_activation');
			}
		
		}
	}
	
	$return = array(
		'status' => $status,
		'message' => $message,
		'ajax_response' => $ajax_response
	);

	wp_send_json( $return );
}
add_action( 'wp_ajax_mtphr_dnt_license_activate_ajax', 'mtphr_dnt_license_activate_ajax' );


/**
 * Admin footer scripts
 * @since 2.1.18
 */
function mtphr_dnt_license_bug_ajax() {
	?>
	<script>
		jQuery(document).ready(function($){
			
			$('#ditty-news-ticker-license-notice').click( function(e){
				if( $(e.target).is('.notice-dismiss') ) {
					
					var data = {
						action:'mtphr_dnt_license_bug_dismiss',
						slugs:$(this).data('slugs'),
						security:'<?php echo wp_create_nonce( 'ditty-news-ticker' ); ?>'
					};
					
					jQuery.post(ajaxurl,data,function(response){});
					
				}	
			});
			
			$('.ditty-news-ticker-license-deactivate').click( function(e){
				
				e.preventDefault();
				var $container = $(this).parent(),
						$spinner = $(this).siblings('.spinner'),
						$description = $(this).siblings('.mtphr_dnt_license_description'),
						$activate_button = $(this).siblings('.ditty-news-ticker-license-activate');
				
				$spinner.css('visibility', 'visible');
				
				var data = {
					action: 'mtphr_dnt_license_deactivate_ajax',
					slug: $(this).data('slug'),
					security: '<?php echo wp_create_nonce( 'ditty-news-ticker' ); ?>'
				};
				
				jQuery.post(ajaxurl,data,function(response){
					
					$container.attr('class', 'mtphr-dnt-license-group mtphr-dnt-license-'+response.status);
					if( response.status === 'deactivated' ) {
						$activate_button.addClass('button-primary');
						$activate_button.val('<?php echo mtphr_dnt_string('activate_license'); ?>');
					}	
					$description.html('<p><em>'+response.message+'</em></p>');
					$spinner.css('visibility', 'hidden');
					
				}, 'json');		
			});
			
			$('.ditty-news-ticker-license-activate').click( function(e){
				
				e.preventDefault();
				
				var $button = $(this),
						$container = $(this).parent(),
						$spinner = $(this).siblings('.spinner'),
						$input = $(this).siblings('.mtphr_dnt_license_key'),
						$description = $(this).siblings('.mtphr_dnt_license_description');
				
				$spinner.css('visibility', 'visible');
				
				var data = {
					action: 'mtphr_dnt_license_activate_ajax',
					license: $input.val(),
					slug: $button.attr('data-slug'),
					security: '<?php echo wp_create_nonce( 'ditty-news-ticker' ); ?>'
				};
				
				jQuery.post(ajaxurl,data,function(response){
					
					$container.attr('class', 'mtphr-dnt-license-group mtphr-dnt-license-'+response.status);
					if( response.status === 'valid' ) {
						$button.removeClass('button-primary').addClass('button');
						$button.val('<?php echo mtphr_dnt_string('refresh_license'); ?>');
					}	
					$description.html('<p><em>'+response.message+'</em></p>');
					$spinner.css('visibility', 'hidden');
					
				}, 'json');		
			});
			
			
			
		});
	</script>
	<?php
}
add_action( 'admin_footer', 'mtphr_dnt_license_bug_ajax' );
