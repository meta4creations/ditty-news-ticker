<?php

/**
 * Render the import/export page
 *
 * @since    3.0.17
*/
function ditty_export_display() {
	?>
	<div id="ditty-page" class="wrap">
		
		<header class="ditty-header">
			<div class="ditty-header__wrapper">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 258.8 99.21" fill="#FFFFFF"><path d="M0,49.5C0,32.3,8.6,20.4,24.6,20.4a19.93,19.93,0,0,1,6.6,1V3.1H45V62.3l1,10.3H34.2l-.9-5.2h-.5a15.21,15.21,0,0,1-13,6.8C3.8,74.2,0,61.5,0,49.5Zm31.2,7.4V31.7a13.7,13.7,0,0,0-6-1.3c-8.7,0-11.3,8.7-11.3,17.8,0,8.5,1.9,15.8,8.9,15.8C27.9,64,31.2,60.2,31.2,56.9Z"/><path d="M55.7,7.4A7.33,7.33,0,0,1,63.4,0c4.6,0,7.8,3.3,7.8,7.4s-3.2,7.4-7.8,7.4S55.7,11.7,55.7,7.4ZM70.5,21.9V72.6H56.4V21.9Z"/><path d="M95.8,3.1V21.9H112V3.1h14.1V21.9h13V32.8h-13V55.9c0,5.9,2.6,7.6,6.4,7.6a11.9,11.9,0,0,0,6.1-1.9l3.2,9c-3,2-8.2,3.5-13.3,3.5-15.2,0-16.5-8.7-16.5-17.8V32.8H95.8V55.9c0,5.9,2,7.6,5.7,7.6a11.64,11.64,0,0,0,5.7-1.6l2.1,9.4c-2.6,1.7-7.4,2.8-11.1,2.8-15.1,0-16.4-8.7-16.4-17.8V3.1Z"/><path d="M149.6,85.81c0-7.21,4.4-12.81,10.3-17.11-8.4-1.3-13-5.9-13-16V21.9h14V51.6c0,5.4.5,9.1,7,9.1,4,0,7.7-3.2,7.7-8.3V21.9h14V64.2a108.13,108.13,0,0,1-.9,13.9c-1.5,13.5-8.9,21.11-22.4,21.11C155.2,99.21,149.6,94,149.6,85.81Zm26.3-9.11V67.2c-7.4,3.5-14,8.5-14,16.11,0,3.9,2.2,5.79,6,5.79C173.8,89.1,175.9,84.4,175.9,76.7Z"/><path d="M198.7,66.8a7,7,0,0,1,7.4-7.2c5,0,7.7,2.8,7.7,7.1s-2.6,7.5-7.4,7.5C201.3,74.2,198.7,71.1,198.7,66.8Z"/><path d="M221.2,66.8a7,7,0,0,1,7.4-7.2c5,0,7.7,2.8,7.7,7.1s-2.6,7.5-7.4,7.5C223.8,74.2,221.2,71.1,221.2,66.8Z"/><path d="M243.7,66.8a7,7,0,0,1,7.4-7.2c5,0,7.7,2.8,7.7,7.1s-2.6,7.5-7.4,7.5C246.3,74.2,243.7,71.1,243.7,66.8Z"/></svg>
			</div>
		</header>
		
		<div id="ditty-page__header">
			<h2><?php esc_html_e( 'Ditty Import/Export', 'ditty-news-ticker' ); ?></h2>
		</div>
		
		<div id="ditty-page__content">
			<div id="ditty-settings">
				<?php
				$current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'export_ditty';
				$settings = apply_filters( 'ditty_settings_tabs', array(
					'export_ditty' => array(
						'icon'			=> 'fas fa-file-export',
						'label' 		=> __( 'Export Ditty', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_export_ditty',
					),
					'export_layouts' => array(
						'icon'			=> 'fas fa-file-export',
						'label' 		=> __( 'Export Layouts', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_export_layouts',
					),
					'export_displays' => array(
						'icon'			=> 'fas fa-file-export',
						'label' 		=> __( 'Export Displays', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_export_displays',
					),
					'import' => array(
						'icon'			=> 'fas fa-file-import',
						'label' 		=> __( 'Import Posts', 'ditty-news-ticker' ),
						'fields' 		=> 'ditty_settings_import',
					),
				) );
				?>

				<div class="ditty-settings__tabs">
					<?php
					if ( is_array( $settings ) && count( $settings ) > 0 ) {
						foreach ( $settings as $slug => $setting ) {
							$active = ( $slug == $current_tab ) ? ' active' : '';
							echo '<a href="' . add_query_arg( 'tab', $slug ) . '" class="ditty-settings__tab ditty-settings__tab--' . esc_attr( $slug ) . $active . '" data-panel="' . esc_attr( $slug ) . '">';
								echo '<i class="' . esc_attr( $setting['icon'] ) . '"></i>';
								echo '<span>' . esc_html( $setting['label'] ) . '</span>';
							echo '</a>';
						}
					}
					?>
				</div>
				
				<form class="ditty-settings__form" method="post" enctype="multipart/form-data">
					<div class="ditty-notification-bar">
						<div class="ditty-notification ditty-notification--updated"><?php echo esc_html( ditty_admin_strings( 'settings_updated' ) ); ?></div>
						<div class="ditty-notification ditty-notification--warning"><?php echo esc_html( ditty_admin_strings( 'settings_changed' ) ); ?></div>
						<div class="ditty-notification ditty-notification--error"><?php echo esc_html( ditty_admin_strings( 'settings_error' ) ); ?></div>
					</div>
					<div class="ditty-settings__panels">
						<?php
						if ( isset( $settings[$current_tab] ) ) {
							?>
							<div class="ditty-settings__panel ditty-settings__panel--<?php echo esc_attr( $slug ); ?>" style="display:block;">	
								<?php
								if ( isset( $settings[$current_tab]['fields'] ) && function_exists( $settings[$current_tab]['fields'] ) ) {
									call_user_func( $settings[$current_tab]['fields'] );
								}
								?>
							</div>
							<?php
						}	
						?>
					</div>
					<input type="hidden" name="ditty_export_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
					<div class="ditty-updating-overlay"></div>
				</form>
			</div>
		</div>
	</div><!-- /.wrap -->
	<?php
}

/**
 * Setup the import and export fields
 *
 * @since    3.0.17
*/
function ditty_settings_import() {
	$fields = array(
		'ditty_import_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'ditty_import_heading',
			'name' 		=> esc_html__( 'Ditty Import', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Ditty you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Ditty posts. You can optionally include the connected Layouts and Displays for each Ditty.", 'ditty-news-ticker' ),
		),
	);
	
	if ( isset( $_POST['ditty_import_button'] ) ) {
		$fields['ditty_import_options'] = array(
			'type'				=> 'html',
			'id' 					=> 'ditty_import_options',
			'std' 				=> ditty_import_options(),
		);
		$fields['ditty_new_import_button'] = array(
			'type'				=> 'html',
			'id' 					=> 'ditty_new_import_button',
			'std'					=> '<a href="' . admin_url( 'edit.php?post_type=ditty&page=ditty_export&tab=import' ) . '">' . esc_html__( 'New Import', 'ditty-news-ticker' ) . '</a>',
		);
	} else {
		$fields['ditty_import_posts'] = array(
			'name' 				=> esc_html__( 'Import', 'ditty-news-ticker' ),
			'type'				=> 'text',
			'id' 					=> 'ditty_import_posts',
			'input_class'	=> 'ditty-export-posts',
			'atts'				=> array(
				'type' => 'file',
			),
		);
		$fields['ditty_import_button'] = array(
			'type'				=> 'button',
			'id' 					=> 'ditty_import_button',
			'name' 				=> ' ',
			'label'				=> esc_html__( 'Import Posts', 'ditty-news-ticker' ) . ' <i class="fas fa-sync-alt fa-spin"></i>',
			'icon_after' 	=> 'fas fa-sync-alt fa-spin',
			'input_class'	=> 'ditty-import-button',
			'atts' 				=> array(
				'type' => 'submit',
			),
		);
	}
	ditty_fields( $fields );
}

/**
 * Export Ditty posts
 *
 * @since    3.0.17
*/
function ditty_settings_export_ditty() {
	$fields = array(
		'ditty_export_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'ditty_export_heading',
			'name' 		=> esc_html__( 'Ditty Export', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Ditty you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Ditty posts. You can optionally include the connected Layouts and Displays for each Ditty.", 'ditty-news-ticker' ),
		),
		'ditty_export_ditty_ids' => array(
			'name' 				=> esc_html__( 'Ditty Posts', 'ditty-news-ticker' ),
			'desc'				=> esc_html__( "Select the Ditty you would like to export. All connected Layouts and Displays will automatically be exported as well.", 'ditty-news-ticker' ),
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_export_ditty_ids',
			'options'			=> ditty_export_ditty_options(),
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_connections' => array(
			'name' 				=> esc_html__( 'Connected Posts', 'ditty-news-ticker' ),
			'desc'				=> esc_html__( "Do you want to export connected Layouts and Displays for your Ditty?", 'ditty-news-ticker' ),
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_export_connections',
			'inline'			=> true,
			'options'			=> array(
				'layouts' => esc_html__( 'Export connected Layouts', 'ditty-news-ticker' ),
				'displays' => esc_html__( 'Export connected Displays', 'ditty-news-ticker' ),
			),
			'std'					=> array( 
				'layouts' 	=> 'layouts',
				'displays'	=> 'displays',
			),
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_button' => array(
			'type'				=> 'button',
			'id' 					=> 'ditty_export_button',
			'name' 				=> ' ',
			'label'				=> esc_html__( 'Export Selected Posts', 'ditty-news-ticker' ) . ' <i class="fas fa-sync-alt fa-spin"></i>',
			'icon_after' 	=> 'fas fa-sync-alt fa-spin',
			'input_class'	=> 'ditty-export-button',
			'atts' 				=> array(
				'disabled'					=> 'disabled',
				'type'							=> 'submit',
			),
		),
	);
	ditty_fields( $fields );
}

/**
 * Export Ditty posts
 *
 * @since    3.0.17
*/
function ditty_settings_export_layouts() {
	$fields = array(
		'ditty_export_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'ditty_export_heading',
			'name' 		=> esc_html__( 'Ditty Export', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Ditty you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Ditty posts. You can optionally include the connected Layouts and Displays for each Ditty.", 'ditty-news-ticker' ),
		),
		'ditty_export_layout_ids' => array(
			'name' 				=> esc_html__( 'Layout Posts', 'ditty-news-ticker' ),
			'desc'				=> esc_html__( "Select the Layouts you would like to export.", 'ditty-news-ticker' ),
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_export_layout_ids',
			'options'			=> ditty_export_layout_options(),
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_button' => array(
			'type'				=> 'button',
			'id' 					=> 'ditty_export_button',
			'name' 				=> ' ',
			'label'				=> esc_html__( 'Export Selected Posts', 'ditty-news-ticker' ) . ' <i class="fas fa-sync-alt fa-spin"></i>',
			'icon_after' 	=> 'fas fa-sync-alt fa-spin',
			'input_class'	=> 'ditty-export-button',
			'atts' 				=> array(
				'disabled'					=> 'disabled',
				'type'							=> 'submit',
			),
		),
	);
	ditty_fields( $fields );
}

/**
 * Export Ditty posts
 *
 * @since    3.0.17
*/
function ditty_settings_export_displays() {
	$fields = array(
		'ditty_export_heading' => array(
			'type' 		=> 'heading',
			'id' 			=> 'ditty_export_heading',
			'name' 		=> esc_html__( 'Ditty Export', 'ditty-news-ticker' ),
			'desc'	=> esc_html__( "Select the Ditty you would like to export. When you click the download button below, Ditty will create a JSON file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the Ditty posts. You can optionally include the connected Layouts and Displays for each Ditty.", 'ditty-news-ticker' ),
		),
		'ditty_export_display_ids' => array(
			'name' 				=> esc_html__( 'Display Posts', 'ditty-news-ticker' ),
			'desc'				=> esc_html__( "Select the Displays you would like to export.", 'ditty-news-ticker' ),
			'type'				=> 'checkboxes',
			'id' 					=> 'ditty_export_display_ids',
			'options'			=> ditty_export_display_options(),
			'input_class'	=> 'ditty-export-posts',
		),
		'ditty_export_button' => array(
			'type'				=> 'button',
			'id' 					=> 'ditty_export_button',
			'name' 				=> ' ',
			'label'				=> esc_html__( 'Export Selected Posts', 'ditty-news-ticker' ) . ' <i class="fas fa-sync-alt fa-spin"></i>',
			'icon_after' 	=> 'fas fa-sync-alt fa-spin',
			'input_class'	=> 'ditty-export-button',
			'atts' 				=> array(
				'disabled'					=> 'disabled',
				'type'							=> 'submit',
			),
		),
	);
	ditty_fields( $fields );
}

/**
 * Create the Ditty export fields
 *
 * @since    3.0.17
 */
function ditty_export_ditty_options() {
	$args = array(
		'posts_per_page' => -1,
		'orderby' => 'post_date',
		'post_type' => 'ditty',
	);
	$posts = get_posts( $args );
	
	$options = array(
		'select_all' => esc_html__( 'Select all Ditty', 'ditty-news-ticker' ),
	);
	if ( is_array( $posts ) && count( $posts ) > 0 ) {
		foreach ( $posts as $i => $post ) {
			$options[$post->ID] = $post->post_title;
		}
	}
	return $options;
}

/**
 * Create the Layout export fields
 *
 * @since    3.0.17
 */
function ditty_export_layout_options() {
	$layouts = ditty_layout_posts();
	$options = array(
		'select_all' => esc_html__( 'Select all Layouts', 'ditty-news-ticker' ),
	);
	if ( is_array( $layouts ) && count( $layouts ) > 0 ) {
		foreach ( $layouts as $i => $layout ) {
			$version = get_post_meta( $layout->ID, '_ditty_layout_version', true );
			$version_string = '';
			if ( $version ) {
				$version_string = " <small class='ditty-layout-version'>(v{$version})</small>";
			}
			$options[$layout->ID] = $layout->post_title . $version_string;
		}
	}
	return $options;
}

/**
 * Create the Display export fields
 *
 * @since    3.0.17
 */
function ditty_export_display_options() {
	$displays = ditty_display_posts();
	$options = array(
		'select_all' => esc_html__( 'Select all Displays', 'ditty-news-ticker' ),
	);
	if ( is_array( $displays ) && count( $displays ) > 0 ) {
		foreach ( $displays as $i => $display ) {
			$version = get_post_meta( $display->ID, '_ditty_display_version', true );
			$version_string = '';
			if ( $version ) {
				$version_string = " <small class='ditty-display-version'>(v{$version})</small>";
			}
			$options[$display->ID] = $display->post_title . $version_string;
		}
	}
	return $options;
}

/**
 * Create the export file
 *
 * @since    3.0.17
 */
function ditty_create_export_file() {
	if ( ! isset( $_POST['ditty_export_button'] ) ) {
		return false;
	}
	// verify nonce
	if ( ! isset( $_POST['ditty_export_nonce'] ) || ! wp_verify_nonce( $_POST['ditty_export_nonce'], basename( __FILE__ ) ) ) {
		return false;
	}
	$export = array();
	$ditty_ids = isset( $_POST['ditty_export_ditty_ids'] ) ? $_POST['ditty_export_ditty_ids'] : array();
	$layout_ids = isset( $_POST['ditty_export_layout_ids'] ) ? $_POST['ditty_export_layout_ids'] : array();
	$display_ids = isset( $_POST['ditty_export_display_ids'] ) ? $_POST['ditty_export_display_ids'] : array();
	$connections = isset( $_POST['ditty_export_connections'] ) ? $_POST['ditty_export_connections'] : array();
	
	if ( ! empty( $ditty_ids ) ) {
		if ( $ditty_data = ditty_export_ditty_posts( $ditty_ids ) ) {
			if ( isset( $ditty_data['ditty'] ) ) {
				$export['ditty'] = $ditty_data['ditty'];
			}
			if ( isset( $connections['layouts'] ) && isset( $ditty_data['layout_ids'] ) ) {
				$layout_ids = array_merge( $layout_ids, $ditty_data['layout_ids'] );
				$layout_ids = array_unique( $layout_ids );
			}
			if ( isset( $connections['displays'] ) && isset( $ditty_data['display_ids'] ) ) {
				$display_ids = array_merge( $display_ids, $ditty_data['display_ids'] );
				$display_ids = array_unique( $display_ids );
			}
		}
	}
	
	if ( ! empty( $layout_ids ) ) {
		if ( $layout_data = ditty_export_ditty_layouts( $layout_ids ) ) {
			$export['layouts'] = $layout_data;
		}
	}
	
	if ( ! empty( $display_ids ) ) {
		if ( $display_data = ditty_export_ditty_displays( $display_ids ) ) {
			$export['displays'] = $display_data;
		}
	}

	if ( empty( $export ) ) {
		return false;
	}
	
	// $export_json = json_encode( $export );
	// $filename = 'ditty-export-' . date( 'Y-m-d' ) . '.json';
	// $filename = sanitize_file_name( $filename );
	// header( 'Content-Description: File Transfer' );
	// header( "Content-Disposition: attachment; filename=$filename" );
	// header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ), true );
	// echo $export_json;
	// die();
}
add_action( 'admin_init', 'ditty_create_export_file' );

/**
 * Export posts
 *
 * @since    3.0.17
 */
function ditty_export_ditty_posts( $post_ids ) {
	$export = array();
	$dittys = array();
	$displays = array();
	$layouts = array();
	
	if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
		foreach ( $post_ids as $i => $post_id ) {
			if ( 'select_all' == $post_id ) {
				continue;
			}
			$uniq_id = ditty_maybe_add_uniq_id( $post_id );
			$display = get_post_meta( $post_id, '_ditty_display', true );
			$display_uniq_id = ditty_maybe_add_uniq_id( $display );
			
			// Store the display for possible export
			$displays[$display] = $display;
			
			$items = array();
			$all_meta = Ditty()->db_items->get_items( $post_id );
			if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
				foreach ( $all_meta as $i => $meta ) {
					if ( is_object( $meta ) ) {
						$meta = ( array ) $meta;
					}
					
					// Clean and store all item custom 
					$custom_meta = ditty_item_get_all_meta( $meta['item_id'] );
					if ( is_array( $custom_meta ) && count( $custom_meta ) > 0 ) {
						$cleaned_meta = array();
						foreach ( $custom_meta as $data ) {
							if ( is_object( $data ) ) {
								$data = ( array ) $data;
							}
							$data['meta_value'] = maybe_unserialize( $data['meta_value'] );
							unset( $data['meta_id'] );
							unset( $data['item_id'] );
							$cleaned_meta[] = $data;
						}
						$meta['custom_meta'] = $cleaned_meta;
					}
					
					$item_value = maybe_unserialize( $meta['item_value'] );
					$meta['item_value'] = $item_value;
					
					$layout_value = maybe_unserialize( $meta['layout_value'] );
					$updated_layout_value = array();
					if ( is_array( $layout_value ) && count( $layout_value ) > 0 ) {
						foreach ( $layout_value as $variation => $layout_id ) {
							$layouts[$layout_id] = $layout_id;
							$updated_layout_value[$variation] = ditty_maybe_add_uniq_id( $layout_id );
						}
					}
					$meta['layout_value'] = $updated_layout_value;

					unset( $meta['item_id'] );
					unset( $meta['date_created'] );
					unset( $meta['date_modified'] );
					unset( $meta['ditty_id'] );
					$items[] = $meta;
				} 
			}
	
			$dittys[$uniq_id] = array(
				'label' 		=> get_the_title( $post_id ),
				'init'			=> get_post_meta( $post_id, '_ditty_init', true ),
				'settings'	=> get_post_meta( $post_id, '_ditty_settings', true ),
				'display'		=> $display_uniq_id,
				'items'			=> $items,
				'uniq_id'		=> $uniq_id,
			);
		}
	}
	if ( ! empty( $dittys ) ) {
		$export['ditty'] = $dittys;
	}
	if ( ! empty( $layouts ) ) {
		$export['layout_ids'] = array_values( $layouts );
	}
	if ( ! empty( $displays ) ) {
		$export['display_ids'] = array_values( $displays );
	}
	if ( ! empty( $export ) ) {
		return $export;
	}
}

/**
 * Export Layouts
 *
 * @since    3.0.17
 */
function ditty_export_ditty_layouts( $post_ids ) {
	$export = array();
	$layouts = array();

	if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
		foreach ( $post_ids as $i => $post_id ) {
			if ( 'select_all' == $post_id ) {
				continue;
			}
			$uniq_id = ditty_maybe_add_uniq_id( $post_id );
			$layouts[$uniq_id] = array(
				'label' 			=> get_the_title( $post_id ),
				'description'	=> get_post_meta( $post_id, '_ditty_layout_description', true ),
				'html'				=> stripslashes( get_post_meta( $post_id, '_ditty_layout_html', true ) ),
				'css'					=> get_post_meta( $post_id, '_ditty_layout_css', true ),
				'version' 		=> get_post_meta( $post_id, '_ditty_layout_version', true ),
				'uniq_id'			=> $uniq_id,
			);
		}
	}
	
	if ( ! empty( $layouts ) ) {
		return $layouts;
	}
}

/**
 * Export Displays
 *
 * @since    3.0.17
 */
function ditty_export_ditty_displays( $post_ids ) {
	$export = array();
	$displays = array();
	
	if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
		foreach ( $post_ids as $i => $post_id ) {
			if ( 'select_all' == $post_id ) {
				continue;
			}
			$uniq_id = ditty_maybe_add_uniq_id( $post_id );
			$displays[$uniq_id] = array(
				'label' 				=> get_the_title( $post_id ),
				'description'		=> get_post_meta( $post_id, '_ditty_display_description', true ),
				'display_type'	=> get_post_meta( $post_id, '_ditty_display_type', true ),
				'settings'			=> get_post_meta( $post_id, '_ditty_display_settings', true ),
				'version' 			=> get_post_meta( $post_id, '_ditty_display_version', true ),
				'uniq_id'				=> $uniq_id,
			);
		}
	}
	
	if ( ! empty( $displays ) ) {
		return $displays;
	}
}

/**
 * Create the export file
 *
 * @since    3.0.17
 */
function ditty_import_posts() {
	if ( ! isset( $_POST['ditty_import_button'] ) ) {
		return false;
	}
	// verify nonce
	if ( ! isset( $_POST['ditty_export_nonce'] ) || ! wp_verify_nonce( $_POST['ditty_export_nonce'], basename( __FILE__ ) ) ) {
		return false;
	}
	if ( ! isset( $_FILES['ditty_import_posts'] ) || ! $_FILES['ditty_import_posts']['tmp_name'] ) {
		return false;	
	}
	
	$transient_name = 'ditty_import';
	$transient_data = array();
	
	$json_data = file_get_contents( $_FILES['ditty_import_posts']['tmp_name'] );
	$data = json_decode( $json_data, 1 );
	$layouts = array();
	$displays = array();
	
	// Import Layouts
	if ( isset( $data['layouts'] ) && is_array( $data['layouts'] ) && count( $data['layouts'] ) > 0 ) {
		
		// Add to the transient data
		$transient_data['layouts'] = array();
		
		foreach ( $data['layouts'] as $uniq_id => $layout_data ) {
			$postarr = array(
				'post_type'		=> 'ditty_layout',
				'post_status'	=> 'publish',
				'post_title'	=> esc_html( $layout_data['label'] ),
			);
			$imported_layout_id = wp_insert_post( $postarr );
			$imported_data = array(
				'id' => $imported_layout_id,
			);
			
			if ( isset( $layout_data['description'] ) ) {
				update_post_meta( $imported_layout_id, '_ditty_layout_description', wp_kses_post( $layout_data['description'] ) );
			}
			if ( isset( $layout_data['html'] ) ) {
				$html = str_replace( '\\', '\\\\', $layout_data['html'] );
				update_post_meta( $imported_layout_id, '_ditty_layout_html', wp_kses_post( $html ) );
			}
			if ( isset( $layout_data['css'] ) ) {
				update_post_meta( $imported_layout_id, '_ditty_layout_css', wp_kses_post( $layout_data['css'] ) );
			}
			if ( isset( $layout_data['version'] ) ) {
				update_post_meta( $imported_layout_id, '_ditty_layout_version', wp_kses_post( $layout_data['version'] ) );
			}
			update_post_meta( $imported_layout_id, '_ditty_uniq_id', $uniq_id );
			$layouts[$uniq_id] = $imported_layout_id;
			
			$transient_data['layouts'][$imported_layout_id] = $imported_data;
		}
	}
	
	// Import Displays
	if ( isset( $data['displays'] ) && is_array( $data['displays'] ) && count( $data['displays'] ) > 0 ) {
		
		// Add to the transient data
		$transient_data['displays'] = array();
		
		foreach ( $data['displays'] as $uniq_id => $display_data ) {
			$postarr = array(
				'post_type'		=> 'ditty_display',
				'post_status'	=> 'publish',
				'post_title'	=> esc_html( $display_data['label'] ),
			);
			$imported_display_id = wp_insert_post( $postarr );
			$imported_data = array(
				'id' => $imported_display_id,
			);
			
			if ( isset( $display_data['description'] ) ) {
				update_post_meta( $imported_display_id, '_ditty_display_description', wp_kses_post( $display_data['description'] ) );
			}
			if ( isset( $display_data['display_type'] ) ) {
				update_post_meta( $imported_display_id, '_ditty_display_type', esc_html( $display_data['display_type'] ) );
			}
			if ( $display_object = ditty_display_type_object( $display_data['display_type'] ) ) {
				$fields = $display_object->fields();
				$sanitized_settings = ditty_sanitize_fields( $fields, $display_data['settings'], "ditty_display_type_{$display_data['display_type']}" );
				update_post_meta( $imported_display_id, '_ditty_display_settings', $sanitized_settings );
			}
			if ( isset( $display_data['version'] ) ) {
				update_post_meta( $imported_display_id, '_ditty_display_version', wp_kses_post( $display_data['version'] ) );
			}
			update_post_meta( $imported_display_id, '_ditty_uniq_id', $uniq_id );
			$displays[$uniq_id] = $imported_display_id;
			
			$transient_data['displays'][$imported_display_id] = $imported_data;
		}
	}
	
	// Import Ditty
	if ( isset( $data['ditty'] ) && is_array( $data['ditty'] ) && count( $data['ditty'] ) > 0 ) {
		
		// Add to the transient data
		$transient_data['ditty'] = array();
		
		foreach ( $data['ditty'] as $uniq_id => $ditty_data ) {
			$postarr = array(
				'post_type'		=> 'ditty',
				'post_status'	=> 'publish',
				'post_title'	=> esc_html( $ditty_data['label'] ),
			);
			$imported_ditty_id = wp_insert_post( $postarr );
			$imported_data = array(
				'id' => $imported_ditty_id,
			);
			
			
			$settings = isset( $ditty_data['settings'] ) ? $ditty_data['settings'] : array();
			$sanitized_settings = Ditty()->singles->sanitize_settings( $settings );
			update_post_meta( $imported_ditty_id, '_ditty_settings', $sanitized_settings );
			
			update_post_meta( $imported_ditty_id, '_ditty_init', 'yes' );
			
			if ( isset( $displays[$ditty_data['display']] ) ) {
				$imported_data['display'] = $displays[$ditty_data['display']];
				update_post_meta( $imported_ditty_id, '_ditty_display', intval( $displays[$ditty_data['display']] ) );
			}
			
			update_post_meta( $imported_ditty_id, '_ditty_uniq_id', $uniq_id );
			
			// Add items
			if ( is_array( $ditty_data['items'] ) && count( $ditty_data['items'] ) > 0 ) {
				foreach ( $ditty_data['items'] as $i => $item ) {
					
					// Gather the custom meta
					$custom_meta = false;
					if ( isset( $item['custom_meta'] ) ) {
						$custom_meta = $item['custom_meta'];
						unset( $item['custom_meta'] );
					}
					
					// Add the ditty id
					$item['ditty_id'] = $imported_ditty_id;

					// Add the item author
					$item['item_author'] = get_current_user_id();
					
					// Set the layouts
					$updated_layout_value = array();
					if ( is_array( $item['layout_value'] ) && count( $item['layout_value'] ) > 0 ) {
						foreach ( $item['layout_value'] as $variation => $layout_id ) {
							if ( isset( $layouts[$layout_id] ) ) {
								$updated_layout_value[$variation] = $layouts[$layout_id];
							}
						}
					}
					$item['layout_value'] = $updated_layout_value;
					
					// Sanitize and save item data
					$sanitized_item_data = Ditty()->singles->sanitize_item_data( $item );
					if ( $new_item_id = Ditty()->db_items->insert( apply_filters( 'ditty_item_db_data', $sanitized_item_data, $imported_ditty_id ), 'item' ) ) {
						
						// Add the custom meta
						if ( is_array( $custom_meta ) && count( $custom_meta ) > 0 ) {
							foreach ( $custom_meta as $i => $meta ) {
								ditty_item_add_meta( $new_item_id, esc_attr( $meta['meta_key'] ), $meta['meta_value']  );
							}
						}
					}	
				}
			}
			
			$transient_data['ditty'][$imported_ditty_id] = $imported_data;
		}
	}
	if ( ! empty( $transient_data ) ) {
		set_transient( $transient_name, $transient_data );
	}
}
add_action( 'admin_init', 'ditty_import_posts' );

/**
 * Import options for the user
 *
 * @since    3.0.17
 */
function ditty_import_options() {
	$transient_name = 'ditty_import';
	$transient_data = get_transient( $transient_name );
	if ( ! $transient_data ) {
		return false;
	}
	
	$html = '';
	
	if ( isset( $transient_data['ditty'] ) && is_array( $transient_data['ditty'] ) && count( $transient_data['ditty'] ) > 0 ) {
		$html .= '<div class="ditty-import__group">';
			$html .= '<h3>' . esc_html__( 'Imported Ditty', 'ditty-news-ticker' ) . '</h3>';
			$html .= '<ul class="ditty-import__list">';
			foreach ( $transient_data['ditty'] as $i => $ditty ) {
				$html .= '<li class="ditty-import__item">';
					$html .= '<p class="ditty-import__post-title"><span>' . get_the_title( $ditty['id'] ) . '</span> <a href="' . get_edit_post_link( $ditty['id'] ) . '">' . esc_html__( 'Edit', 'ditty-news-ticker' ) . '</a></p> ';
					// if ( isset( $ditty['display'] ) ) {
					// 	$html .= '<p class="ditty-import__display"><strong>' . esc_html__( 'Display', 'ditty-news-ticker' ) . ':</strong> ' . get_the_title( $ditty['display'] ) . ' <a href="' . get_edit_post_link( $ditty['display'] ) . '">' . esc_html__( 'Edit', 'ditty-news-ticker' ) . '</a></p> ';
					// }
				$html .= '</li>';
			}
			$html .= '</ul>';
		$html .= '</div>';
	}
	
	if ( isset( $transient_data['layouts'] ) && is_array( $transient_data['layouts'] ) && count( $transient_data['layouts'] ) > 0 ) {
		$html .= '<div class="ditty-import__group">';
			$html .= '<h3>' . esc_html__( 'Imported Layouts', 'ditty-news-ticker' ) . '</h3>';
			$html .= '<ul class="ditty-import__list">';
			foreach ( $transient_data['layouts'] as $i => $layout ) {
				$html .= '<li class="ditty-import__item">';
					$html .= '<p class="ditty-import__post-title"><span>' . get_the_title( $layout['id'] ) . '</span> <a href="' . get_edit_post_link( $layout['id'] ) . '">' . esc_html__( 'Edit', 'ditty-news-ticker' ) . '</a></p> ';
				$html .= '</li>';
			}
			$html .= '</ul>';
		$html .= '</div>';
	}
	
	if ( isset( $transient_data['displays'] ) && is_array( $transient_data['displays'] ) && count( $transient_data['displays'] ) > 0 ) {
		$html .= '<div class="ditty-import__group">';
			$html .= '<h3>' . esc_html__( 'Imported Displays', 'ditty-news-ticker' ) . '</h3>';
			$html .= '<ul class="ditty-import__list">';
			foreach ( $transient_data['displays'] as $i => $display ) {
				$html .= '<li class="ditty-import__item">';
					$html .= '<p class="ditty-import__post-title"><span>' . get_the_title( $display['id'] ) . '</span> <a href="' . get_edit_post_link( $display['id'] ) . '">' . esc_html__( 'Edit', 'ditty-news-ticker' ) . '</a></p> ';
				$html .= '</li>';
			}
			$html .= '</ul>';
		$html .= '</div>';
	}
	
	return $html;
}