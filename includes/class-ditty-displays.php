<?php

/**
 * Ditty Displays Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Displays
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Displays {
	
	private $new_displays;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
		
		// General hooks
		add_action( 'ditty_editor_update', array( $this, 'update_drafts' ), 10, 2 );
		add_filter( 'ditty_post_meta_update', array( $this, 'modify_ditty_draft_meta'), 10, 3 );

		// Editior elements
		add_action( 'ditty_editor_tabs', array( $this, 'editor_tab' ), 10, 2 );
		add_action( 'ditty_editor_panels', array( $this, 'editor_panel' ), 10, 2 );
		
		// Display elements
		add_action( 'ditty_editor_display_elements', array( $this, 'editor_display_icon' ), 5 );
		add_action( 'ditty_editor_display_elements', array( $this, 'editor_display_label' ), 10 );
		add_action( 'ditty_editor_display_elements', array( $this, 'editor_display_edit' ), 15 );
		add_action( 'ditty_editor_display_elements', array( $this, 'editor_display_clone' ), 20 );
		add_action( 'ditty_editor_display_elements', array( $this, 'editor_display_delete' ), 25 );

		// Ajax
		add_action( 'wp_ajax_ditty_editor_select_display', array( $this, 'editor_select_display_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_select_display', array( $this, 'editor_select_display_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_display_fields', array( $this, 'editor_fields_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_display_fields', array( $this, 'editor_fields_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_display_clone', array( $this, 'editor_clone_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_display_clone', array( $this, 'editor_clone_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_display_update', array( $this, 'editor_update_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_display_update', array( $this, 'editor_update_ajax' ) );
		
		add_action( 'wp_ajax_ditty_install_display', array( $this, 'install_display' ) );
	}
	
	/**
	 * Install default displays
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function install_default( $display_type, $display_template = false, $display_version = false ) {	
		if ( $display_ids = ditty_displays_with_type( $display_type, $display_template, $display_version ) ) {
			return reset( $display_ids );
		}

		$display_object = ditty_display_type_object( $display_type );
		$templates = $display_object->templates();
		if ( ! isset( $templates[$display_template] ) ) {
			return false;
		}
		$postarr = array(
			'post_type'		=> 'ditty_display',
			'post_status'	=> 'publish',
			'post_title'	=> $templates[$display_template]['label'],
		);
		if ( $new_display_id = wp_insert_post( $postarr ) ) {
			update_post_meta( $new_display_id, '_ditty_display_type', esc_attr( $display_type ) );
			update_post_meta( $new_display_id, '_ditty_display_template', esc_attr( $display_template ) );
			if ( isset( $templates[$display_template]['description'] ) ) {
				update_post_meta( $new_display_id, '_ditty_display_description', wp_kses_post( $templates[$display_template]['description'] ) );
			}
			if ( isset( $templates[$display_template]['version'] ) ) {
				update_post_meta( $new_display_id, '_ditty_display_version', wp_kses_post( $templates[$display_template]['version'] ) );
			}
			if ( isset( $templates[$display_template]['settings'] ) ) {
				$fields = $display_object->fields();
				$sanitized_settings = ditty_sanitize_fields( $fields, $templates[$display_template]['settings'] );
				update_post_meta( $new_display_id, '_ditty_display_settings', $sanitized_settings );
			}
		}
		return $new_display_id;
	}
	
	/**
	 * Install a display via ajax
	 *
	 * @access public
	 * @since  3.0
	 */
	public function install_display() {
		check_ajax_referer( 'ditty', 'security' );
		$display_type_ajax 		= isset( $_POST['display_type'] ) 			? $_POST['display_type'] 		: false;
		$display_template_ajax	= isset( $_POST['display_template'] )	? $_POST['display_template']	: false;
		$display_version_ajax	= isset( $_POST['display_version'] )		? $_POST['display_version']	: false;
		
		if ( ! current_user_can( 'publish_ditty_displays' ) || ! $display_type_ajax || ! $display_template_ajax ) {
			wp_die();
		}
		$display_id = $this->install_default( $display_type_ajax, $display_template_ajax, $display_version_ajax );
		
		$args = array(
			'type'				=> 'button',
			'label'				=> __( 'Installed', 'ditty-ticker' ),
			'link'				=> '#',
			'size' 				=> 'small',
			'input_class'	=> 'ditty-default-display-view',
			'field_only'	=> true,
			'atts'				=> array(
				'disabled' => 'disabled',
			),
		);
		$button = ditty_field( $args );
		
		$data = array(
			'display_id' => $display_id,
			'button'	=> $button,
		);	
		wp_send_json( $data );
	}
	
	/**
	 * List the default displays
	 *
	 * @access public
	 * @since  3.0
	 * @param  html
	 */
	public function list_default_displays() {
		$html = '';
		$display_types = ditty_display_types();
		$default_displays = ditty_default_displays();
		if ( is_array( $default_displays ) && count( $default_displays ) > 0 ) {
			$html .= '<ul id="ditty-default-displays">';
			foreach ( $default_displays as $display_type => $display_data ) {
				$html .= '<li class="ditty-defaults-list__type">';
					$html .= '<h3>' . $display_data['label'] . '</h3>';
					if ( is_array( $display_data['templates'] ) && count( $display_data['templates'] ) > 0 ) {
						$html .= '<ul id="ditty-defaults-list__templates">';
						foreach ( $display_data['templates'] as $template => $template_data ) {
							$display_versions = ditty_displays_with_type( $display_type, $template, false, 'versions' );

							$html .= '<li class="ditty-defaults-list__template">';
								$html .= '<div class="ditty-defaults-list__template__heading">';
									$html .= '<h4 class="ditty-defaults-list__template__label">';
										$html .= $template_data['label'] . " <small class='ditty-layout-version'>(v{$template_data['version']})</small>";
									$html .= '</h4>';
									$html .= '<p class="ditty-defaults-list__template__description">' . $template_data['description'] . '</p>';
								$html .= '</div>';
								
								$args = array(
									'type'				=> 'button',
									'label'				=> __( 'Installed', 'ditty-ticker' ),
									'link'				=> '#',
									'size' 				=> 'small',
									'input_class'	=> 'ditty-default-display-view',
									'field_only'	=> true,
								);
								if ( $display_versions ) {	
									if ( in_array( $template_data['version'], $display_versions ) ) {
										$args['label'] = __( 'Installed', 'ditty-ticker' );
										$args['atts'] = array(
											'disabled' => 'disabled',
										);
									} else {
										$args['label'] = sprintf( __( 'Install Version %s', 'ditty-ticker' ), $template_data['version'] );
										$args['input_class'] = 'ditty-default-display-install';
										$args['icon_after'] = 'fas fa-download';
										$args['atts'] = array(
											'data-display_type' => $display_type,
											'data-display_template' => $template,
											'data-display_version' => $template_data['version'],
										);
									}
								} else {
									$args['label'] = __( 'Install Template', 'ditty-ticker' );
									$args['input_class'] = 'ditty-default-display-install';
									$args['icon_after'] = 'fas fa-download';
									$args['atts'] = array(
										'data-display_type' 		=> $display_type,
										'data-display_template' => $template,
										'data-display_version' 	=> $template_data['version'],
									);
								}
								$html .= ditty_field( $args );
							$html .= '</li>';
						}
						$html .= '</ul>';
					}
				$html .= '</li>';
			}
			$html .= '</ul>';
		}
		
		return $html;
	}

	/**
	 * Add the editor item icon
	 *
	 * @since    3.0
	 */
	public function editor_display_icon( $display ) {
		echo '<span class="ditty-data-list__item__icon"><i class="' . $display->get_icon() . '" data-class="' . $display->get_icon() . '"></i></span>';
	}
	
	/**
	 * Add the editor item label
	 *
	 * @since    3.0
	 */
	public function editor_display_label( $display ) {
		$version = $display->get_version();
		$version_string = '';
		if ( $version ) {
			$version_string = " <small class='ditty-display-version'>(v{$version})</small>";
		}
		?>
		<span class="ditty-data-list__item__label"><?php echo $display->get_label(); ?><?php echo $version_string; ?></span>
		<?php
	}
	
	/**
	 * Add the editor edit html button
	 *
	 * @since    3.0
	 */
	public function editor_display_edit( $display ) {
		if ( current_user_can( 'edit_ditty_displays' ) ) {
			echo '<a href="#" class="ditty-data-list__item__edit protip" data-pt-title="' . __( 'Edit Display', 'ditty-news-ticker' ) . '"><i class="fas fa-edit" data-class="fas fa-edit"></i></a>';
		}
	}
	
	/**
	 * Add the editor clone button
	 *
	 * @since    3.0
	 */
	public function editor_display_clone( $display ) {
		if ( current_user_can( 'publish_ditty_displays' ) ) {
			echo '<a href="#" class="ditty-data-list__item__clone protip" data-pt-title="' . __( 'Clone', 'ditty-news-ticker' ) . '"><i class="fas fa-clone" data-class="fas fa-clone"></i></a>';
		}
	}
	
	/**
	 * Add the editor delete button
	 *
	 * @since    3.0
	 */
	public function editor_display_delete( $display ) {
		if ( current_user_can( 'delete_ditty_displays' ) ) {
			echo '<a href="#" class="ditty-data-list__item__delete protip" data-pt-title="' . __( 'Delete', 'ditty-news-ticker' ) . '"><i class="fas fa-trash-alt" data-class="fas fa-trash-alt"></i></a>';
		}
	}

	/**
	 * Return an array of custom displays
	 *
	 * @access  private
	 * @since   3.0
	 * @param   array    $displays.
	 */
	// private function get_custom_displays() {
	// 	$display_types = ditty_display_types();	
	// 	$args = array(
	// 		'posts_per_page' 	=> -1,
	// 		'orderby' 				=> 'post_title',
	// 		'order' 					=> 'ASC',
	// 		'post_type' 			=> 'ditty_display',
	// 		'fields' 					=> 'ids',
	// 		'meta_query' 			=> array(
	//       array(
	//         'key'     => '_ditty_display_type',
	//         'value'   => array_keys( $display_types ),
	//         'compare' => 'IN',
	//       ),
	//     ),
	// 	);
	// 	return get_posts( $args );
	// }
	
	/**
	 * Return an array of displays by type
	 *
	 * @access  public
	 * @since   3.0
	 * @param   array    $displays.
	 */
	public function get_displays_data() {	
		$displays = array();
		$display_types = ditty_display_types();
		ksort( $display_types );
		if ( is_array( $display_types ) && count( $display_types ) > 0 ) {
			foreach ( $display_types as $type_slug => $display_type ) {
				if ( ! $display_object = ditty_display_type_object( $type_slug ) ) {
					continue;
				}
				$args = array(
					'posts_per_page' => -1,
					'orderby' 		=> 'post_title',
					'order' 			=> 'ASC',
					'post_type' 	=> 'ditty_display',
					'meta_key'		=> '_ditty_display_type',
					'meta_value' 	=> $type_slug,
				);
				$posts = get_posts( $args );
				if ( is_array( $posts ) && count( $posts ) > 0 ) {
					foreach ( $posts as $i => $post ) {
						$version = get_post_meta( $post->ID, '_ditty_display_version', true );
						$version_string = '';
						if ( $version ) {
							$version_string = " <small class='ditty-display-version'>(v{$version})</small>";
						}
						$displays[] = array(
							'type_id' 			=> $type_slug,
							'type_label' 		=> $display_type['label'],
							'display_id' 		=> $post->ID,
							'display_label' => $post->post_title.$version_string,
						);
					}
				}
			}
		}	
		return $displays;
	}

	/**
	 * Return an array of displays for select fields
	 *
	 * @access  public
	 * @since   3.0
	 * @param   array    $options.
	 */
	public function select_field_options() {	
		$displays = $this->get_displays_data();
		$options = array();
		
		if ( is_array( $displays ) && count( $displays ) > 0 ) {
			foreach ( $displays as $i => $display ) {
				$options[$display['display_id']] = $display['type_label'] . ': ' . $display['display_label'];
			}
		}
	
		return $options;
	}

	/**
	 * Return an array of all display values
	 *
	 * @access  public
	 * @since   3.0
	 * @param   array    $values.
	 */
	public function get_values( $display_atts ) {
		
		if ( ! is_array( $display_atts ) ) {
			$display_atts = $this->get_attributes( $display_atts );
		}
		
		if ( ! $display_object = ditty_display_type_object( $display_atts['type'] ) ) {
			return false;
		}
		
		// If this is a custom post id
		if ( 'custom' == $display_atts['group'] ) {
			
			$defaults = $display_object->default_settings();			
			$settings = get_post_meta( $display_atts['id'], "_ditty_{$display_atts['type']}_settings", true );
			$settings = wp_parse_args( $settings, $defaults['settings'] );
			$display 	= get_post_meta( $display_atts['id'], "_ditty_{$display_atts['type']}_display", true );
			$display 	= wp_parse_args( $display, $defaults['display'] );
		
		// If this is a template
		} else {

			$template = $display_object->get_template( $display_atts['id'] );
			$settings = $template['settings'];
			$display 	= $template['display'];
		}
		
		return apply_filters( 'ditty_display_values', ( $settings + $display ), $display_atts['id'], $display_atts['type'] );
	}

	/**
	 * Return a displays values via ajax
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_values_ajax() {
		
		// Check the nonce
		check_ajax_referer( 'ditty', 'security' );
		
		$display_id = isset( $_POST['display_id'] ) ? $_POST['display_id'] : false;
		if ( $display = new Ditty_Display( $display_id )  ) {
			$data = array(
				'values' => $display->get_values( 'merged' ),
			);
		} else {
			$data = array(
				'error' => __( 'Display does not exist', 'ditty-news-ticker' ),
			);
		}

		wp_send_json( $data );
	}

	/**
	 * Add to the editor tabs
	 *
	 * @access  public
	 * @since   3.0
	 * @param   $html
	 */
	public function editor_tab( $tabs, $ditty_id ) {
		if ( ! current_user_can( 'edit_ditty_displays' ) ) {
			return false;
		}
		$tabs['displays'] = array(
			'icon' 		=> 'fas fa-tablet-alt',
			'label'		=> __( 'Display', 'ditty-news-ticker' ),
		);
		return $tabs;
	}
	
	/**
	 * Return the editor displays panel
	 *
	 * @access  public
	 * @since   3.0
	 * @param   $html
	 */
	public function editor_panel( $panels, $ditty_id ) {
		if ( ! current_user_can( 'edit_ditty_displays' ) ) {
			return false;
		}
		$display_id = get_post_meta( $ditty_id, '_ditty_display', true );
		if ( ! $display_id ) {
			$display_id = ditty_default_display( $ditty_id );
		}
		$display_types = ditty_display_types();
		ob_start();
		?>
		<div class="ditty-editor-options ditty-metabox">
			<div class="ditty-editor-options__contents">
				<div class="ditty-data-list">
					<div class="ditty-data-list__filters">
						<?php	
						if ( is_array( $display_types ) && count( $display_types ) > 0 ) {
							foreach ( $display_types as $type => $display ) {
								if ( ! $display_object = ditty_display_type_object( $type ) ) {
									continue;
								}
								echo '<a class="ditty-display-panel__filter ditty-data-list__filter" data-filter="' . $type . '" href="#"><i class="' . $display_object->get_icon() . '"></i>' . $display_object->get_label() . '</a>';
							}
						}
						?>
					</div>
					<div class="ditty-data-list__items" data-active="<?php echo $display_id; ?>">
						<?php
						// Add the custom displays
						if ( $displays_data = $this->get_displays_data() ) {
							foreach ( $displays_data as $i => $data ) {
								$display = new Ditty_Display( $data['display_id'] );
								echo $display->render_editor_list_item( 'return' );
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		// Return the output
		$html = ob_get_clean();
		
		$panels['displays'] = $html;
		return $panels;
	}
	
	/**
	 * Load a display
	 *
	 * @access public
	 * @since  3.0
	 */
	public function editor_select_display_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$display_id_ajax 		= isset( $_POST['display_id'] ) ? $_POST['display_id'] 	: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] ) 		? $_POST['draft_values'] 			: false;
		if ( ! current_user_can( 'edit_ditty_items' ) || ! $display_id_ajax ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		$display = new Ditty_Display( $display_id_ajax );
		$settings = $display->get_settings();
		wp_send_json( $settings );
	}

	/**
	 * Return a displays values to edit
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_fields_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$display_id_ajax 		= isset( $_POST['display_id'] ) 	? $_POST['display_id']		: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 	: false;
		if ( ! current_user_can( 'edit_ditty_displays' ) || ! $display_id_ajax ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		$display = new Ditty_Display( $display_id_ajax );
		?>
		<form class="ditty-editor-options ditty-metabox">
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__header">
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--start">
						<a href="#" class="ditty-editor-options__back"><i class="fas fa-chevron-left" data-class="fas fa-chevron-left"></i></a>
					</div>
					<input class="ditty-editor-options__title" type="text" name="title" placeholder="<?php _e( 'Title Goes Here...', 'ditty-news-ticker' ); ?>" value="<?php echo $display->get_label(); ?>" />
				</div>
				<div class="ditty-editor-options__body">
					<div class="ditty-editor-options__fields">
						<?php
						$display->object_settings();
						?>
					</div>
				</div>
			</div>
		</form>
		<?php
		wp_die();
	}

	/**
	 * Update the display via ajax
	 *
	 * @since    3.0
	 */
	public function editor_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$display_id_ajax		= isset( $_POST['display_id'] )		? $_POST['display_id']		: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] )	? $_POST['draft_values']	: false;
		if ( ! current_user_can( 'edit_ditty_displays' ) || ! $display_id_ajax ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		
		$display = new Ditty_Display( $display_id_ajax );
		$settings = $_POST;
		
		// Possibly update the label
		$draft_label = false;
		if ( isset( $settings['title'] ) ) {
			if ( $display->get_label() != $settings['title'] ) {
				$draft_label = $display->set_label( $settings['title'] );
			}
			unset( $settings['title'] );
		}
		unset( $settings['action'] );
		unset( $settings['display_id'] );
		unset( $settings['draft_values'] );
		unset( $settings['security'] );

		// Update the settings
		$draft_settings = $display->update_settings( $settings );
		
		$data = array(
			'draft_id' 							=> $display->get_display_id(),
			'draft_label'						=> $draft_label ? $draft_label : null,
			'draft_settings'				=> $draft_settings,
			'draft_settings_json'		=> json_encode( $draft_settings ),
		);
		if ( $draft_label ) {
			$data['draft_label'] = $draft_label;
		}

		wp_send_json( $data );
	}

	/**
	 * Clone a display
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_clone_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$display_id_ajax		= isset( $_POST['display_id'] )		? $_POST['display_id']		: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 	: false;
		if ( ! current_user_can( 'publish_ditty_displays' ) || ! $display_id_ajax ) {
			wp_die();
		}	
		ditty_set_draft_values( $draft_values_ajax );
		
		// Clone the source display
		$editor_display = new Ditty_Display( $display_id_ajax );
		$draft_id = uniqid( 'new-' );
		$draft_label = sprintf( __( '%s Clone', 'ditty-news-ticker' ), $editor_display->get_label() );
		$editor_display->set_display_id( $draft_id );
		$editor_display->set_label( $draft_label );
		$editor_display->set_version( '' );
		$draft_data = array(
			'label' 				=> $editor_display->get_label(),
			'description' 	=> $editor_display->get_description(),
			'display_type' 	=> $editor_display->get_display_type(),
			'settings' 			=> $editor_display->get_settings(),
		);
		$data = array(
			'editor_display' 		=> $editor_display->render_editor_list_item( 'return' ),
			'draft_id' 					=> $draft_id,
			'draft_data'				=> $draft_data,
		);
		wp_send_json( $data );		
	}

	/**
	 * Return the temporary new display IDs
	 *
	 * @access public
	 * @since  3.0
	 * @param  array $new_displays
	 */
	private function get_new_displays() {
		if ( empty( $this->new_displays ) ) {
			$this->new_displays = array();
		}
		return $this->new_displays;
	}
	
	/**
	 * Update the temporary new display IDs
	 *
	 * @access public
	 * @since  3.0
	 * @param  array $new_displays
	 */
	private function update_new_displays( $new_id, $post_id ) {
		$new_displays = $this->get_new_displays();
		$new_displays[$new_id] = $post_id;
		$this->new_displays = $new_displays;
	}
	
	/**
	 * Modify a Ditty's draft meta
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function modify_ditty_draft_meta( $meta_value, $meta_key, $ditty_id ) {
		if ( $meta_key == '_ditty_display' ) {
			if ( false === strpos( $meta_value, 'new-' ) ) {
				return $meta_value;
			}
			$new_displays = $this->get_new_displays();
			if ( isset( $new_displays[$meta_value] ) ) {
				return $new_displays[$meta_value];
			}	
		}
		return $meta_value;
	}
	
	/**
	 * Save display draft values on Ditty update
	 *
	 * @access public
	 * @since  3.0
	 */
	public function update_drafts( $ditty_id, $draft_values ) {
		if ( ! current_user_can( 'edit_ditty_displays' ) ) {
			return false;
		}
		
		$add_to_live_update = false;
		if ( isset( $draft_values['displays'] ) && is_array( $draft_values['displays'] ) && count( $draft_values['displays'] ) > 0 ) {
			foreach ( $draft_values['displays'] as $display_id => $display_data ) {
				$display_type = false;

				// Delete a display
				if ( 'DELETE' == $display_data ) {
					wp_trash_post( $display_id );
					continue;
				
				} elseif( is_array( $display_data ) ) {
					
					// Add or update a display
					if ( false !== strpos( $display_id, 'new-' ) ) {
						
						$postarr = array(
							'post_type'		=> 'ditty_display',
							'post_status'	=> 'publish',
							'post_title'	=> $display_data['label'],
						);
						$updated_display_id = wp_insert_post( $postarr );
						$this->update_new_displays( $display_id, $updated_display_id );
						$display_id = $updated_display_id;
						
					} else {
						if ( isset( $display_data['label'] ) ) {
							$postarr = array(
								'ID'					=> $display_id,
								'post_title'	=> $display_data['label'],
							);
							wp_update_post( $postarr );
						}
					}
	
					// Update a display description
					if ( isset( $display_data['description'] ) ) {
						update_post_meta( $display_id, '_ditty_display_description', wp_kses_post( $display_data['description'] ) );
					}
					
					// Update a display type
					if ( isset( $display_data['display_type'] ) ) {
						$display_type = esc_attr( $display_data['display_type'] );
						update_post_meta( $display_id, '_ditty_display_type', $display_type );
					}
	
					// Update a display settings
					if ( isset( $display_data['settings'] ) ) {
						if ( ! $display_type ) {
							$display_type = get_post_meta( $display_id, '_ditty_display_type', true );
						}
						$display_object = ditty_display_type_object( $display_type );
						$fields = $display_object->fields();
						$sanitized_settings = ditty_sanitize_fields( $fields, $display_data['settings'] );
						update_post_meta( $display_id, '_ditty_display_settings', $sanitized_settings );
					}
					
					// Remove the version number of edited displays
					delete_post_meta( $display_id, '_ditty_display_version' );
				}
			}
		}
	}
}