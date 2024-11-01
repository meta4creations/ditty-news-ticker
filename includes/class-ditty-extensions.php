<?php

/**
 * Ditty Extensions Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Extensions
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

class Ditty_Extensions {
	
	/**
	 * Slug
	 *
	 * @since 3.0
	 */
	public $licenses;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'add_extension_updaters' ), 0 );
		add_action( 'admin_menu', array( $this, 'add_extensions_page' ), 3 );
		//add_action( 'network_admin_menu', array( $this, 'add_extensions_page' ) );
		
		// Ajax
		add_action( 'wp_ajax_ditty_extension_license_activate', array( $this, 'license_activate_ajax' ) );
		add_action( 'wp_ajax_ditty_extension_license_refresh', array( $this, 'license_refresh_ajax' ) );
		add_action( 'wp_ajax_ditty_extension_license_deactivate', array( $this, 'license_deactivate_ajax' ) );
		add_action( 'wp_ajax_ditty_extension_panel_update', array( $this, 'panel_update_ajax' ) );
		
		$this->licenses = $this->get_licenses();
	}
	
	/**
	 * Add the extensions page
	 * @access  public
	 * @since   3.0
	 */
	public function add_extensions_page() {
		// if ( is_multisite() ) {
		// 	if ( is_network_admin() ) {
		// 		add_submenu_page( 'settings.php', __( 'Ditty Extensions', 'ditty-news-ticker' ), __( 'Extensions', 'ditty-news-ticker' ), 'manage_ditty_settings', 'ditty_extensions', array( $this, 'extensions_page' ) );	
		// 	}
		// } else {
			add_submenu_page( 'edit.php?post_type=ditty', __( 'Ditty Extensions', 'ditty-news-ticker' ), __( 'Extensions', 'ditty-news-ticker' ), 'manage_ditty_settings', 'ditty_extensions', array( $this, 'extensions_page' ) );
		//}
	}
	
	/**
	 * Load all the extension updaters
	 * @access  public
	 * @since   3.0.12
	 */
	public function add_extension_updaters() {
		if ( wp_doing_ajax() ) {
			$action = isset( $_POST['action'] ) ? $_POST['action'] : false;
			if ( 'update-plugin' != $action ) {
				return false;
			}
		}
		$extension_licenses = ditty_extension_licenses();
		$ditty_licenses = $this->licenses;		
		if ( is_array( $extension_licenses ) && count( $extension_licenses ) > 0 ) {
			foreach( $extension_licenses as $slug => $license_settings ) {
				if ( ! isset( $license_settings['version'] ) || ! isset( $license_settings['item_id'] ) || ! isset( $license_settings['path'] ) ) {
					continue;	
				}
				$license_key = isset( $ditty_licenses[$slug] ) ? trim( $ditty_licenses[$slug]['key'] ) : '';
				$edd_updater = new Ditty_Plugin_Updater( 'https://www.metaphorcreations.com', $license_settings['path'],
					array(
						'version' => $license_settings['version'],		// current version number
						'license' => $license_key,										// license key (used get_option above to retrieve from DB)
						'item_id' => $license_settings['item_id'],	// ID of the product
						'author'  => isset( $license_settings['author'] ) ? $license_settings['author'] : __( 'Ditty', 'ditty-news-ticker' ),		// author of this plugin
						'beta'    => false,
					)
				);				
			}
		}
	}
	
	/**
	 * Return a message string
	 * @access  public
	 * @since   3.0
	 */
	private function status_message( $slug, $extension ) {
		$messages = array(
			'valid'								=> __( 'License is active.', 'ditty-news-ticker' ),
			'generic_error' 			=> __( 'An error occurred, please try again.', 'ditty-news-ticker' ),
			'remote_error' 				=> __( 'An error occurred, please try again.', 'ditty-news-ticker' ),
			'expired' 						=> __( 'Your license key expired on %s.', 'ditty-news-ticker' ),
			'disabled' 						=> __( 'Your license key has been disabled.', 'ditty-news-ticker' ),
			'revoked' 						=> __( 'Your license key has been disabled.', 'ditty-news-ticker' ),
			'missing' 						=> __( 'Invalid license.', 'ditty-news-ticker' ),
			'invalid' 						=> __( 'Your license is not active for this URL.', 'ditty-news-ticker' ),
			'site_inactive' 			=> __( 'Your license is not active for this URL.', 'ditty-news-ticker' ),
			'item_name_mismatch' 	=> sprintf( __( 'This appears to be an invalid license key for %s.', 'ditty-news-ticker' ), $extension ),
			'no_activations_left' => __( 'Your license key has reached its activation limit.', 'ditty-news-ticker' ),
			'deactivated'					=> __( 'License is deactivated.', 'ditty-news-ticker' ),
			'failed'							=> __( 'Update failed.', 'ditty-news-ticker' ),
		);
		$messages = apply_filters( 'ditty_extensions_messages', $messages, $extension );
		if ( isset( $messages[$slug] ) ) {
			$message = $messages[$slug];
			if ( 'expired' == $slug ) {
				$license_data = $this->get_license( $extension );
				$message = sprintf( $message, date_i18n( get_option( 'date_format' ), strtotime( $license_data['expires'], current_time( 'timestamp' ) ) ) );
			}
			if ( 'expired' == $slug || 'no_activations_left' == $slug ) {
				$link = 'https://www.metaphorcreations.com';
				$message .= '<br/><span class="ditty-extension__renewal-link">' . sprintf( __( 'Please <a href="%s" target="_blank">update or renew your license</a>.', 'ditty-news-ticker' ), $link ) . '</span>';
			}
			return $message;
		}		
		//return sprintf( __( '%s: No message available.', 'ditty-news-ticker' ), $slug );
	}

	/**
	 * Return the license data
	 * @access  public
	 * @since   3.0
	 */
	private function get_licenses() {
		$licenses = ( is_multisite() ) ? get_site_option( 'ditty_licenses', array() ) : get_option( 'ditty_licenses', array() );
		return $licenses;
	}
	
	/**
	 * Return an extension's license data
	 * @access  public
	 * @since   3.0
	 */
	public function get_license( $slug ) {
		if ( isset( $this->licenses[$slug] ) ) {
			return $this->licenses[$slug];
		}
	}
	
	/**
	 * Return an extension's license data
	 * @access  public
	 * @since   3.0
	 */
	public function has_valid_license( $slug ) {
		if ( $license_data = Ditty()->extensions->get_license( $slug ) ) {
			if ( 'valid' == $license_data['status'] ) {
				return true;
			}
		}
	}
	
	/**
	 * Update the license data
	 * @access  public
	 * @since   3.0
	 */
	private function update_licenses( $data ) {
		$licenses = ( is_multisite() ) ? update_site_option( 'ditty_licenses', $data ) : update_option( 'ditty_licenses', $data );
		$this->licenses = $licenses;
		return $licenses;
	}
	
	/**
	 * Update an extension's license
	 * @access  public
	 * @since   3.0
	 */
	private function update_license( $extension, $license_key='', $status='', $expires='', $error=false ) {
		$licenses = $this->licenses;
		if ( ! is_array( $licenses ) || empty( $licenses ) ) {
			$licenses = array();
		}
		if ( ! isset( $licenses[$extension] ) ) {
			$licenses[$extension] = array();
		}
		$licenses[$extension]['key'] = sanitize_text_field( $license_key );
		$licenses[$extension]['status'] = esc_attr( $status );
		$licenses[$extension]['expires'] = sanitize_text_field( $expires );
		$licenses[$extension]['error'] = esc_attr( $error );
		
		$updated_licenses = array();
		if ( is_array( $licenses ) && count( $licenses ) > 0 ) {
			foreach ( $licenses as $slug => $license ) {
				if ( '' === $slug ) {
					continue;
				}
				$updated_licenses[$slug] = $license;
			}
		}
		
		return $this->update_licenses( $updated_licenses );
	}
	
	/**
	 * Remove an extension's license
	 * @access  public
	 * @since   3.0
	 */
	private function remove_license( $extension ) {
		$licenses = $this->licenses;
		if ( ! is_array( $licenses ) || empty( $licenses ) ) {
			return false;
		}
		unset( $licenses[$extension] );
		return $this->update_licenses( $licenses );
	}
	
	/**
	 * Check if a license is active
	 * @access  public
	 * @since   3.0
	 */
	public function get_license_status( $extension ) {
		$licenses = $this->licenses;
		if ( ! is_array( $licenses ) || empty( $licenses ) ) {
			$licenses = array();
		}
		if ( ! isset( $licenses[$extension] ) ) {
			return false;
		}
		if ( isset( $licenses[$extension]['status'] ) ) {
			return $licenses[$extension]['status'];
		}
	}
	
	/**
	 * Render an extension
	 * @access  public
	 * @since   3.0
	 */
	private function render_extension( $extension, $data, $extension_licenses ) {
		$settings = isset( $data['settings'] ) ? $data['settings'] : array();
		$license_settings = isset( $extension_licenses[$extension] ) ? $extension_licenses[$extension] : false;
		$oauth_settings = false;
		$other_settings = array();
		if ( is_array( $settings ) && count( $settings ) > 0 ) {
			foreach ( $settings as $i => $setting ) {
				if ( 'oauth' === $setting['id'] ) {
					$oauth_settings = $setting;
				} else {
					$other_settings[] = $setting;
				}
			}
		}
		
		$init_panel = false;
		$license = '';
		$license_key = '';
		$status = 'dna';
		$error = '';
		$user_name = false;
		$user_avatar = false;
		$user_banner = false;
		
		if ( isset( $_GET['extension'] ) && isset( $_GET['panel'] ) && $_GET['extension'] == $extension ) {
			$init_panel = $_GET['panel'];
		}
		if ( $license_settings ) {
			
			// Add the license to the settings array
			$license_settings['id'] = 'license';
			$license_settings['label'] = __( 'License', 'ditty-news-ticker' );
			$settings = array( 'license' => $license_settings ) + $settings;
			
			if ( $license_data = $this->get_license( $extension ) ) {
				$license_key 	= isset( $license_data['key'] ) 		? substr( $license_data['key'], 0, -15 ) . '***************' : '';
				$status 			= isset( $license_data['status'] ) 	? $license_data['status'] : '';
				$error 				= isset( $license_data['error'] ) 	? $license_data['error'] : '';
			} else {
				$status		= 'none';
			}
		}
    
    $icon_style = '';
		$heading_style = '';
		$classes = 'ditty-extension ditty-extension--' . $extension;
		if ( $oauth_settings ) {
			$classes .= ' ditty-extension--oauth';
			if ( isset( $oauth_settings['has_custom_keys'] ) && '' != $oauth_settings['has_custom_keys'] ) {
				$classes .= ' ditty-extension--custom-keys';
			}
			if ( isset( $oauth_settings['user_name'] ) ) {
				$user_name = $oauth_settings['user_name'];
			}
			if ( isset( $oauth_settings['user_avatar'] ) ) {
				$user_avatar = $oauth_settings['user_avatar'];
			}
			if ( isset( $oauth_settings['user_banner'] ) ) {
				$user_banner = $oauth_settings['user_banner'];
				$heading_style .= 'background-image:url(' . esc_url_raw( $user_banner ) . ');';
			}
		}
		
    $extension_icon = ( strpos( $data['icon'], '<svg ' ) !== false ) ? $data['icon'] : '<i class="' . esc_attr( $data['icon'] ) . '"></i>';
    $extension_icon_color = isset( $data['icon_color'] ) ? $data['icon_color'] : false;
    $extension_icon_bg_color = isset( $data['icon_bg_color'] ) ? $data['icon_bg_color'] : false;
    $highlight_color = isset( $data['highlight_color'] ) ? $data['highlight_color'] : '#19BF7C'; 
    if ( $extension_icon_color ) {
      $icon_style .= 'color:' . esc_attr( $extension_icon_color ) . ';';
      $heading_style .= 'color:' . esc_attr( $extension_icon_color ) . ';';
    }
    if ( $extension_icon_bg_color ) {
      $icon_style .= 'background:' . esc_attr( $extension_icon_bg_color ) . ';';
      $heading_style .= 'background:' . esc_attr( $extension_icon_bg_color ) . ';';
    }

    $attr = array(
			'class' => $classes,
			'data-extension' => $extension,
			'data-license_status' => $status,
      'data-highlight_color' => $highlight_color,
		);
		if ( $oauth_settings ) {
			$attr['data-api'] = ( isset( $oauth_settings['authorized'] ) && '' != $oauth_settings['authorized'] ) ? 'authorized' : 'unauthorized';
			if ( isset( $oauth_settings['has_custom_keys'] ) && '' != $oauth_settings['has_custom_keys'] ) {
				$attr['data-api_keys'] = $oauth_settings['has_custom_keys'];
			}
		}
		?>
		<div <?php echo ditty_attr_to_html( $attr ); ?>>
			<div class="ditty-extension__contents">
			
				<div class="ditty-extension__header" style="<?php echo esc_attr( $heading_style ); ?>">
					<?php if ( ! $user_banner ) { ?>
						<div class="ditty-extension__header__icon">
              <?php echo ditty_kses_post( $extension_icon ); ?>
            </div>
					<?php } ?>
					<div class="ditty-extension__header__overlay"></div>
					<div class="ditty-extension__icon" style="<?php echo esc_attr( $icon_style ); ?>">
						<?php if ( $user_avatar ) { ?>
							<img src="<?php echo esc_url_raw( $user_avatar ); ?>" />
              <div class="ditty-extension__icon__small" style="<?php echo esc_attr( $icon_style ); ?>"><?php echo ditty_kses_post( $extension_icon ); ?></div>
						<?php } else { ?>
						  <?php echo ditty_kses_post( $extension_icon ); ?>
            <?php } ?>
					</div>
					<h3 class="ditty-extension__title"><?php echo sanitize_text_field( $data['name'] ); ?></h3>
				</div>
				<?php
				if ( isset( $data['preview'] ) ) {
				?>
					<div class="ditty-extension__preview">
						<a class="ditty-extension__preview__link ditty-button" href="<?php echo esc_url_raw( $data['url'] ); ?>" target="_blank"><?php _e( 'Preview Extension', 'ditty-news-ticker' ); ?></a>
					</div>
				<?php
				} else {
					
					if ( is_array( $settings ) && count( $settings ) > 0 ) {
						echo '<div class="ditty-extension__tabs">';
						foreach ( $settings as $i => $setting ) {
							echo '<a href="#" class="ditty-extension__tab" data-slide_id="' . esc_attr( $setting['id'] ) . '">' . sanitize_text_field( $setting['label'] ) . '</a>';
						}
						echo '</div>';
					}
					
					if ( is_array( $settings ) && count( $settings ) > 0 ) {
						echo '<div class="ditty-extension__panels" data-init_panel="' . esc_attr( $init_panel ) . '">';
						foreach ( $settings as $i => $setting ) {
							?>
							<div class="ditty-extension__panel ditty-extension__panel--<?php echo esc_attr( $setting['id'] ); ?>" data-slide_id="<?php echo esc_attr( $setting['id'] ); ?>" data-slide_cache="true">	
								<form class="ditty-extension__form">
								<?php
								if( 'license' == $setting['id'] ) {
									?>
									<div class="ditty-field">
										<?php
										if ( '' == $error ) {
											$license_message = $this->status_message( $status, $extension );
										}	else {
											$license_message = $this->status_message( $error, $extension );
										}
										$item_id = isset( $setting['item_id'] ) ? $setting['item_id'] : '';
										$item_name = isset( $setting['item_name'] ) ? $setting['item_name'] : '';
										?>
										<label class="ditty-field__label ditty-extension__license__message"><?php echo $license_message; ?></label>
										<div class="ditty-field__input ditty-extension__license__fields">
											<input class="ditty-extension__license__input regular-text" name="ditty_licenses[<?php echo $extension; ?>]" type="text" placeholder="<?php _e( 'Add your license key here', 'ditty-news-ticker' ); ?>" value="<?php echo esc_attr( $license_key ); ?>" />
											<a class="ditty-extension__license__submit ditty-button protip" href="#" data-extension="<?php echo esc_attr( $extension ); ?>" data-extension_id="<?php echo esc_attr( $item_id ); ?>" data-extension_name="<?php echo esc_attr( $data['name'] ); ?>" data-product_name="<?php echo esc_attr( $item_name ); ?>" data-pt-title="<?php _e( 'Activate License', 'ditty-news-ticker' ); ?>"><i class="fas fa-check" data-class="fas fa-check"></i></a>
											<a class="ditty-extension__license__refresh ditty-button protip" href="#" data-extension="<?php echo esc_attr( $extension ); ?>" data-extension_id="<?php echo esc_attr( $item_id ); ?>" data-extension_name="<?php echo esc_attr( $data['name'] ); ?>" data-product_name="<?php echo esc_attr( $item_name ); ?>" data-pt-title="<?php _e( 'Refresh License', 'ditty-news-ticker' ); ?>"><i class="fas fa-sync-alt" data-class="fas fa-sync-alt"></i></a>
											<a class="ditty-extension__license__deactivate ditty-button protip" href="#" data-extension="<?php echo esc_attr( $extension ); ?>" data-extension_id="<?php echo esc_attr( $item_id ); ?>" data-extension_name="<?php echo esc_attr( $data['name'] ); ?>" data-product_name="<?php echo esc_attr( $item_name ); ?>" data-pt-title="<?php _e( 'Deactive License', 'ditty-news-ticker' ); ?>"><i class="fas fa-times" data-class="fas fa-times"></i></a>	
										</div>
									</div>
									<?php
								} else {
									if ( isset( $setting['func'] ) && function_exists( $setting['func'] ) ) {
										call_user_func( $setting['func'] );
									} elseif( isset( $setting['fields'] ) ) {
										ditty_fields( $setting['fields'] );
										$update_button = isset( $setting['update_button'] ) ? $setting['update_button'] : 'default';
										if ( 'disabled' != $update_button ) {
											$field = array(
												'type'				=> 'button',
												'id'					=> 'submit',
												'label'				=> __( 'Update', 'ditty-news-ticker' ),
												'priority' 		=> 'primary',
												'full_width'	=> true,
												'icon_after' 	=> 'fas fa-check',
											);
											echo ditty_field( $field );
										}
									}
								}
								?>
								</form>
							</div>		
							<?php
						}
						echo '</div>';
					}
				}
				?>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Render the extensions page
	 * @access  public
	 * @since   3.0
	 */
	public function extensions_page() {	
		if ( wp_doing_ajax() ) {
			return false;
		}		
		
		$extension_licenses = ditty_extension_licenses();
		$extensions = ditty_extensions();
		if ( isset( $_GET['extension'] ) ) {
			$extensions = array( $_GET['extension'] => $extensions[$_GET['extension']]) + $extensions;
		} 
		?>
		<div id="ditty-page" class="wrap">
			
			<div id="ditty-page__header">
				<h2><?php _e( 'Ditty Extensions', 'ditty-news-ticker' ); ?></h2>
			</div>
			
			<div id="ditty-page__content">
				<div id="ditty-extensions">
					
					<div class="ditty-extensions-group ditty-extensions-group--active">
						<h3><?php _e( 'Active Extensions', 'ditty-news-ticker' ); ?></h3>
						<div class="ditty-extensions-grid">
							<?php
							if ( is_array( $extensions ) && count( $extensions ) > 0 ) {
								foreach ( $extensions as $extension => $data ) {
									if ( isset( $data['preview'] ) ) {
										continue;
									}
									$this->render_extension( $extension, $data, $extension_licenses );
								}
							}	
							?>
						</div>
					</div>
					
					<div class="ditty-extensions-group ditty-extensions-group--other">
						<h3><?php _e( 'Additional Ditty Extensions', 'ditty-news-ticker' ); ?></h3>
						<div class="ditty-extensions-grid">
							<?php
							if ( is_array( $extensions ) && count( $extensions ) > 0 ) {
								foreach ( $extensions as $extension => $data ) {
									if ( isset( $data['preview'] ) ) {
										$this->render_extension( $extension, $data, $extension_licenses );
									}	
								}
							}	
							?>
						</div>
					</div>
				</div>
				
			</div>
		<?php
	}
	
	/**
	 * Attempt to activate a license
	 *
	 * @access public
	 * @since  3.0.8
	 */
	public function license_activate( $extension, $license_key, $item_id ) {
		
		// data to send in our API request
		$api_params = array(
			'edd_action' 	=> 'activate_license',
			'license'    	=> $license_key,
			'item_id'			=> $item_id, // the name of our product in EDD
			'url'        	=> home_url(),
		);
		
		// Call the custom API.
		$response = wp_remote_post( 'https://www.metaphorcreations.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		$status = '';
		$error = false;
		$message = '';

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$error = 'remote_error';
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = $this->status_message( 'remote_error', $extension );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				$error = $license_data->error;
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							$this->status_message( $license_data->error, $extension ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					default :
						$message = $this->status_message( $license_data->error, $extension );
						break;
				}
				if ( '' == $message ) {
					$error = 'generic_error';
					$message = $this->status_message( 'generic_error', $extension );
				}
			}
			$status = $license_data->license; // will be either "valid" or "invalid"
			$expires = $license_data->expires;
			if ( 'valid' == $status ) {
				$message = $this->status_message( 'valid', $extension );
			}
			$this->update_license( $extension, $license_key, $status, $expires, $error );
		}

		$data = array(
			'status' 		=> $status,
			'error' 		=> $error,
			'message' 	=> $message,
			'response' 	=> $response,
		);
		return $data;
	}
	public function license_activate_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$license_key_ajax 		= isset( $_POST['license'] ) 				? sanitize_text_field( $_POST['license'] ) 				: false;
		$extension_ajax 			= isset( $_POST['extension'] ) 			? esc_attr( $_POST['extension'] ) 								: false;
		$extension_id_ajax 		= isset( $_POST['extension_id'] ) 	? intval( $_POST['extension_id'] ) 								: false;
			
		$error = '';
		if ( ! $license_key_ajax || '' == $license_key_ajax  ) {
			$error = __( 'Error: License key is missing.', 'ditty-news-ticker' );
		}
		if ( ! $extension_id_ajax ) {
			$error =  __( 'Error: Extension ID is missing.', 'ditty-news-ticker' );
		}
		if ( ! $extension_ajax ) {
			$error =  __( 'Error: Invalid extension.', 'ditty-news-ticker' );
		}
		if ( '' != $error ) {
			$data = array(
				'status' => 'error',
				'message' => $error,
			);
			wp_send_json( $data );
		}
		$data = $this->license_activate( $extension_ajax, $license_key_ajax, $extension_id_ajax );
		if ( 'valid' == $data['status'] ) {
			$data['license_key'] 	= substr( $license_key_ajax, 0, -15 ) . '***************';
		}
		wp_send_json( $data );
	}
	public function license_refresh_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$extension_ajax 			= isset( $_POST['extension'] ) 			? esc_attr( $_POST['extension'] ) 								: false;
		$extension_id_ajax 		= isset( $_POST['extension_id'] ) 	? intval( $_POST['extension_id'] ) 								: false;
		$license = $this->get_license( $extension_ajax );
		
		$error = '';
		if ( ! isset( $license['key'] ) || '' == $license['key'] ) {
			$error = __( 'Error: License key is missing.', 'ditty-news-ticker' );
		}
		if ( ! $extension_id_ajax ) {
			$error =  __( 'Error: Extension ID is missing.', 'ditty-news-ticker' );
		}
		if ( ! $extension_ajax ) {
			$error =  __( 'Error: Invalid extension.', 'ditty-news-ticker' );
		}
		if ( '' != $error ) {
			$data = array(
				'status' => 'error',
				'message' => $error,
			);
			wp_send_json( $data );
		}
		$data = $this->license_activate( $extension_ajax, $license['key'], $extension_id_ajax );
		if ( 'valid' == $data['status'] ) {
			$data['license_key'] 	= substr( $license['key'], 0, -15 ) . '***************';
		}
		wp_send_json( $data );
	}
	
	/**
	 * Remove a license
	 *
	 * @access public
	 * @since  3.0
	 */
	public function license_deactivate( $extension, $license_key, $item_id ) {
		
		// data to send in our API request
		$api_params = array(
			'edd_action' 	=> 'deactivate_license',
			'license'    	=> $license_key,
			'item_id'			=> $item_id, // the name of our product in EDD
			'url'        	=> home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post( 'https://www.metaphorcreations.com', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		$status = '';
		$error = false;
		$message = '';

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$error = 'remote_error';
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = $this->status_message( 'remote_error', $extension );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			$status = $license_data->license; // will be either "deactivated" or "failed"
			$message = $this->status_message( $status, $extension );
			$this->remove_license( $extension );
		}

		$data = array(
			'status' 			=> $status,
			'error' 			=> $error,
			'message' 		=> $message,
			'response' 		=> $response,
			'license_key'	=> 'DELETE',
		);
		return $data;
	}
	public function license_deactivate_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$extension_ajax 			= isset( $_POST['extension'] ) 			? esc_attr( $_POST['extension'] ) 								: false;
		$extension_id_ajax 		= isset( $_POST['extension_id'] ) 	? intval( $_POST['extension_id'] ) 								: false;
		$extension_name_ajax 	= isset( $_POST['extension_name'] ) ? sanitize_text_field( $_POST['extension_name'] )	: false;
		$license = $this->get_license( $extension_ajax );
		if ( ! $extension_id_ajax || ! $extension_ajax || ! isset( $license['key'] ) ) {
			wp_die();
		}
		$data = $this->license_deactivate( $extension_ajax, $license['key'], $extension_id_ajax );
		wp_send_json( $data );
	}
	
	/**
	 * Settings update
	 *
	 * @access public
	 * @since  3.0
	 */
	public function panel_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );

		$extension_ajax = isset( $_POST['extension'] ) ? esc_attr( $_POST['extension'] ) : false;	
		$panel_ajax = isset( $_POST['panel'] ) ? esc_attr( $_POST['panel'] ) : false;	
		if ( ! $extension_ajax || ! current_user_can( 'manage_ditty_settings' ) ) {
			wp_die();
		}

		$panel_data = array(
			'post_data' 		=> $_POST,
			'extension' 		=> $extension_ajax,
			'panel' 				=> $panel_ajax,
			'input_updates'	=> array(),
		);
		$panel_data = apply_filters( 'ditty_extension_panel_update', $panel_data );
		wp_send_json( $panel_data );
	}
	
}