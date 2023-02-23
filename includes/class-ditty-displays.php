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
		
		// WP metabox hooks
		add_action( 'add_meta_boxes', array( $this, 'metaboxes' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ) );
		add_action( 'wp_ajax_ditty_admin_display_update', array( $this, 'admin_update_ajax' ) );
		
		// General hooks
		add_filter( 'post_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );
		add_action( 'ditty_editor_update', array( $this, 'update_drafts' ), 10, 2 );
		
		add_action( 'wp_ajax_ditty_install_display', array( $this, 'install_display' ) );
	}
	
	/**
	 * Install default displays
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function install_default( $display_type, $display_template = false, $display_version = false ) {	
		$args = array(
			'display_type'	=> $display_type,
			'template' 			=> $display_template,
			'version'				=> $display_version,
			'fields'				=> 'ids',
		);
		if ( $display_ids = ditty_display_posts( $args ) ) {
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
				$sanitized_settings = ditty_sanitize_fields( $fields, $templates[$display_template]['settings'], "ditty_display_type_{$display_type}" );
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
	public function display_templates_list() {
		$html = '';
		$display_types = ditty_display_types();
		$default_displays = ditty_default_displays();
		if ( is_array( $default_displays ) && count( $default_displays ) > 0 ) {
			$html .= '<ul id="ditty-display-templates">';
			foreach ( $default_displays as $display_type => $display_data ) {
				$html .= '<li class="ditty-templates-list__type">';
					$html .= '<div class="ditty-templates-list__type__heading">';
						$html .= '<h3>' . $display_data['label'] . '</h3>';
					$html .= '</div>';
					if ( is_array( $display_data['templates'] ) && count( $display_data['templates'] ) > 0 ) {
						$html .= '<ul id="ditty-templates-list__templates">';
						foreach ( $display_data['templates'] as $template => $template_data ) {
							$args = array(
								'display_type'	=> $display_type,
								'template' 			=> $template,
								'fields'				=> 'ids',
								'return'				=> 'versions',
							);
							$display_versions = ditty_display_posts( $args );
							$html .= '<li class="ditty-templates-list__template">';
								$html .= '<div class="ditty-templates-list__template__heading">';
									$html .= '<h4 class="ditty-templates-list__template__label">';
										$html .= $template_data['label'] . " <small class='ditty-layout-version'>(v{$template_data['version']})</small>";
									$html .= '</h4>';
									$html .= '<p class="ditty-templates-list__template__description">' . $template_data['description'] . '</p>';
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
	 * Add the post ID to the list row actions
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function modify_list_row_actions( $actions, $post ) {
		if ( $post->post_type == 'ditty_display' ) {
			//$id_string = sprintf( __( 'ID: %d', 'ditty-news-ticker' ), $post->ID );
			$id_array = array(
				'id' => sprintf( __( 'ID: %d', 'ditty-news-ticker' ), $post->ID ),
			);
			$actions = array_merge( $id_array, $actions );
		}
		return $actions;
	}
	
	/**
	 * Add metaboxes
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function metaboxes() {
		add_meta_box( 'ditty-display-info', __( 'Display Info', 'ditty-news-ticker' ), array( $this, 'metabox_display_info' ), 'ditty_display', 'side', 'high' );
		add_meta_box( 'ditty-display-settings', __( 'Display Settings', 'ditty-news-ticker' ), array( $this, 'metabox_display_settings' ), 'ditty_display', 'normal' );
	}
	
	/**
	 * Save custom meta
	 * 
	 * @since  3.0.26
	 * @return void
	 */
	public function metabox_save( $post_id ) {
		global $post;
		
		// verify nonce
		if ( ! isset( $_POST['ditty_display_nonce'] ) || ! wp_verify_nonce( $_POST['ditty_display_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}
	
		// check autosave
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) return $post_id;
		
		// don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) return $post_id;
	
		// check permissions
		if ( isset( $_POST['post_type'] ) && 'ditty_display' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_ditty_display', $post_id ) ) {
				return $post_id;
			}
		} elseif ( ! current_user_can( 'edit_ditty_display', $post_id ) ) {
			return $post_id;
		}
		
		if ( isset( $_POST['_ditty_display_type'] ) ) {
			$display_type = sanitize_text_field( $_POST['_ditty_display_type'] );
			update_post_meta( $post_id, '_ditty_display_type', $display_type );
		}

		if ( isset( $_POST['_ditty_display_description'] ) ) {
			$display_description = sanitize_text_field( $_POST['_ditty_display_description'] );
			update_post_meta( $post_id, '_ditty_display_description', $display_description );
		}
		
		// Possibly add a uniq_id
		ditty_maybe_add_uniq_id( $post_id );
		
		// Remove the version number of edited displays
		delete_post_meta( $post_id, '_ditty_display_template' );
		delete_post_meta( $post_id, '_ditty_display_version' );
	}
	
	/**
	 * Update the display via ajax
	 *
	 * @since    3.0
	 */
	public function admin_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$display_id_ajax		= isset( $_POST['display_id'] )		? $_POST['display_id']		: false;
		if ( ! current_user_can( 'edit_ditty_displays' ) || ! $display_id_ajax ) {
			wp_die();
		}
		$settings = $_POST;
		unset( $settings['action'] );
		unset( $settings['display_id'] );
		unset( $settings['security'] );

		$json_data = array();
		$display_type = get_post_meta( $display_id_ajax, '_ditty_display_type', true );
		if ( $display_type_object = ditty_display_type_object( $display_type ) ) {
			$fields = $display_type_object->fields();
			$sanitized_display_settings = ditty_sanitize_fields( $fields, $settings, "ditty_display_type_{$display_type}" );
			update_post_meta( $display_id_ajax, '_ditty_display_settings', $sanitized_display_settings );
			$json_data['sanitize_settings'] = $sanitized_display_settings;
		} else {
			$json_data['error'] = __( 'Display type does not exist', 'ditty-news-ticker' );
		}
		wp_send_json( $json_data );
	}
	
	/**
	 * Add the Display info metabox
	 * 
	 * @since  3.0.26
	 * @return void
	 */
	public function metabox_display_info() {
		global $post;
		$display_types = ditty_display_types();
		$display_types_array = array(
			'' => esc_html__( 'Select a Display Type', 'ditty-news-ticker' ),
		);
		if ( is_array( $display_types ) && count( $display_types ) > 0 ) {
			foreach ( $display_types as $i => $display_type ) {
				$display_types_array[$i] = $display_type['label'];
			}
		}
		
		$display_type = get_post_meta( $post->ID, '_ditty_display_type', true );
		$display_description = get_post_meta( $post->ID, '_ditty_display_description', true );
		$display_name = ( $display_type && isset( $display_types[$display_type] ) ) ? $display_types[$display_type]['label'] : false;

		$fields = array();
		if ( $display_name ) {
			$fields['type'] = array(
				'type' 	=> 'text',
				'id'		=> '_ditty_display_type',
				'name' 	=> __( 'Display Type', 'ditty-news-ticker' ),
				'std' 	=> $display_name,
				'atts'	=> array(
					'disabled' => 'disabled',
				),
			);
		} else {
			$fields['type'] = array(
				'type' 	=> 'select',
				'id'		=> '_ditty_display_type',
				'name' 	=> __( 'Display Type', 'ditty-news-ticker' ),
				'options'	=> $display_types_array,
			);
		}
		$fields['description'] = array(
			'type' 	=> 'textarea',
			'id'		=> '_ditty_display_description',
			'name' 	=> __( 'Description', 'ditty-news-ticker' ),
			'std' 	=> $display_description,
		);
		ditty_fields( $fields );
		echo '<input type="hidden" name="ditty_display_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';
	}
	
	/**
	 * Add the Layout html metabox
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function metabox_display_settings() {
		global $post;
		$display_type = get_post_meta( $post->ID, '_ditty_display_type', true );
		if ( $display_type ) {
			if ( $display_type_object = ditty_display_type_object( $display_type ) ) {
				$display_settings = get_post_meta( $post->ID, '_ditty_display_settings', true );
				if ( ! is_array( $display_settings ) ) {
					$display_settings = array();
				}
				$display_settings = shortcode_atts( $display_type_object->default_settings(), $display_settings );
				$setting_fields = $display_type_object->fields( $display_settings );
				echo "<div class='ditty-display-admin-settings ditty-display-admin-settings--{$display_type}'>";
					ditty_fields( $setting_fields );
				echo '</div>';
			} else {
				echo sprintf( __( '% display type does not exist.', 'ditty-news-ticier' ), $display_type );
			}
		} else {
			echo '<p style="padding:8px 12px;margin:0;">' . esc_html__( 'Select a Display Type in the Display Info metabox and save the post to view settings.', 'ditty-news-ticker' ) . '</p>';
		}
	}
	
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
	 * Add display title styles
	 */
	private function title_styles( $settings, $display, $type ) {
		$settings	= $this->title_settings( $settings );
		$styles = '';
		$styles .= '.ditty[data-display="' . $display . '"] .ditty__title {';
			$styles .= ( '' != $settings['titleBgColor'] ) ? "background-color:{$settings['titleBgColor']};" : '';
			$styles .= ( '' != $settings['titleMargin']['marginTop'] ) ? "margin-top:{$settings['titleMargin']['marginTop']};" : '';
			$styles .= ( '' != $settings['titleMargin']['marginRight'] ) ? "margin-right:{$settings['titleMargin']['marginRight']};" : '';
			$styles .= ( '' != $settings['titleMargin']['marginBottom'] ) ? "margin-bottom:{$settings['titleMargin']['marginBottom']};" : '';
			$styles .= ( '' != $settings['titleMargin']['marginLeft'] ) ? "margin-left:{$settings['titleMargin']['marginLeft']};" : '';
			$styles .= ( '' != $settings['titlePadding']['paddingTop'] ) ? "padding-top:{$settings['titlePadding']['paddingTop']};" : '';
			$styles .= ( '' != $settings['titlePadding']['paddingRight'] ) ? "padding-right:{$settings['titlePadding']['paddingRight']};" : '';
			$styles .= ( '' != $settings['titlePadding']['paddingBottom'] ) ? "padding-bottom:{$settings['titlePadding']['paddingBottom']};" : '';
			$styles .= ( '' != $settings['titlePadding']['paddingLeft'] ) ? "padding-left:{$settings['titlePadding']['paddingLeft']};" : '';
			if ( 'none' != $settings['titleBorderStyle'] ) {
				$styles .= "border-style:{$settings['titleBorderStyle']};";
				$styles .= ( '' != $settings['titleBorderColor'] ) ? "border-color:{$settings['titleBorderColor']};" : '';
				$styles .= ( '' != $settings['titleBorderWidth']['borderTopWidth'] ) ? "border-top-width:{$settings['titleBorderWidth']['borderTopWidth']};" : '';
				$styles .= ( '' != $settings['titleBorderWidth']['borderRightWidth'] ) ? "border-right-width:{$settings['titleBorderWidth']['borderRightWidth']};" : '';
				$styles .= ( '' != $settings['titleBorderWidth']['borderBottomWidth'] ) ? "border-bottom-width:{$settings['titleBorderWidth']['borderBottomWidth']};" : '';
				$styles .= ( '' != $settings['titleBorderWidth']['borderLeftWidth'] ) ? "border-left-width:{$settings['titleBorderWidth']['borderLeftWidth']};" : '';
			}
			$styles .= ( '' != $settings['titleBorderRadius']['borderTopLeftRadius'] ) ? "border-top-left-radius:{$settings['titleBorderRadius']['borderTopLeftRadius']};" : '';
			$styles .= ( '' != $settings['titleBorderRadius']['borderTopRightRadius'] ) ? "border-top-right-radius:{$settings['titleBorderRadius']['borderTopRightRadius']};" : '';
			$styles .= ( '' != $settings['titleBorderRadius']['borderBottomLeftRadius'] ) ? "border-bottom-left-radius:{$settings['titleBorderRadius']['borderBottomLeftRadius']};" : '';
			$styles .= ( '' != $settings['titleBorderRadius']['borderBottomRightRadius'] ) ? "border-bottom-right-radius:{$settings['titleBorderRadius']['borderBottomRightRadius']};" : '';
		$styles .= '}';
		$styles .= '.ditty[data-display="' . $display . '"] .ditty__title__element {';
			$styles .= ( '' != $settings['titleColor'] ) ? "color:{$settings['titleColor']};" : '';
			$styles .= ( '' != $settings['titleFontSize'] ) ? "font-size:{$settings['titleFontSize']};" : '';
			$styles .= ( '' != $settings['titleLineHeight'] ) ? "line-height:{$settings['titleLineHeight']};" : '';
		$styles .= '}';
	
		$styles .= '.ditty[data-display="' . $display . '"] .ditty__title__element {';
			$styles .= ( '' != $settings['titleColor'] ) ? "color:{$settings['titleColor']};" : '';
			$styles .= ( '' != $settings['titleFontSize'] ) ? "font-size:{$settings['titleFontSize']};" : '';
			$styles .= ( '' != $settings['titleLineHeight'] ) ? "line-height:{$settings['titleLineHeight']};" : '';
		$styles .= '}';
	
		return apply_filters( 'ditty_display_title_styles', $styles, $settings, $display, $type );
	}
	
	/**
	 * Add display container styles
	 */
	private function container_styles( $settings, $display, $type ) {
		$styles = '';
		$styles .= '.ditty[data-display="' . $display . '"] {';
			$styles .= ( '' != $settings['maxWidth'] ) ? "max-width:{$settings['maxWidth']};" : '';
			$styles .= ( '' != $settings['bgColor'] ) ? "background-color:{$settings['bgColor']};" : '';
			$styles .= ( '' != $settings['padding']['paddingTop'] ) ? "padding-top:{$settings['padding']['paddingTop']};" : '';
			$styles .= ( '' != $settings['padding']['paddingRight'] ) ? "padding-right:{$settings['padding']['paddingRight']};" : '';
			$styles .= ( '' != $settings['padding']['paddingBottom'] ) ? "padding-bottom:{$settings['padding']['paddingBottom']};" : '';
			$styles .= ( '' != $settings['padding']['paddingLeft'] ) ? "padding-left:{$settings['padding']['paddingLeft']};" : '';
			$styles .= ( '' != $settings['margin']['marginTop'] ) ? "margin-top:{$settings['margin']['marginTop']};" : '';
			$styles .= ( '' != $settings['margin']['marginRight'] ) ? "margin-right:{$settings['margin']['marginRight']};" : '';
			$styles .= ( '' != $settings['margin']['marginBottom'] ) ? "margin-bottom:{$settings['margin']['marginBottom']};" : '';
			$styles .= ( '' != $settings['margin']['marginLeft'] ) ? "margin-left:{$settings['margin']['marginLeft']};" : '';
			if ( 'none' != $settings['borderStyle'] ) {
				$styles .= "border-style:{$settings['borderStyle']};";
				$styles .= ( '' != $settings['borderColor'] ) ? "border-color:{$settings['borderColor']};" : '';
				$styles .= ( '' != $settings['borderWidth']['borderTopWidth'] ) ? "border-top-width:{$settings['borderWidth']['borderTopWidth']};" : '';
				$styles .= ( '' != $settings['borderWidth']['borderRightWidth'] ) ? "border-right-width:{$settings['borderWidth']['borderRightWidth']};" : '';
				$styles .= ( '' != $settings['borderWidth']['borderBottomWidth'] ) ? "border-bottom-width:{$settings['borderWidth']['borderBottomWidth']};" : '';
				$styles .= ( '' != $settings['borderWidth']['borderLeftWidth'] ) ? "border-left-width:{$settings['borderWidth']['borderLeftWidth']};" : '';
			}
			$styles .= ( '' != $settings['borderRadius']['borderTopLeftRadius'] ) ? "border-top-left-radius:{$settings['borderRadius']['borderTopLeftRadius']};" : '';
			$styles .= ( '' != $settings['borderRadius']['borderTopRightRadius'] ) ? "border-top-right-radius:{$settings['borderRadius']['borderTopRightRadius']};" : '';
			$styles .= ( '' != $settings['borderRadius']['borderBottomLeftRadius'] ) ? "border-bottom-left-radius:{$settings['borderRadius']['borderBottomLeftRadius']};" : '';
			$styles .= ( '' != $settings['borderRadius']['borderBottomRightRadius'] ) ? "border-bottom-right-radius:{$settings['borderRadius']['borderBottomRightRadius']};" : '';
		$styles .= '}';
		return apply_filters( 'ditty_display_container_styles', $styles, $settings, $display, $type );
	}
	
	/**
	 * Add display content styles
	 */
	private function content_styles( $settings, $display, $type ) {
		$styles = '';
		$styles .= '.ditty[data-display="' . $display . '"] .ditty__contents {';
			$styles .= ( '' != $settings['contentsBgColor'] ) ? "background-color:{$settings['contentsBgColor']};" : '';
			$styles .= ( '' != $settings['contentsPadding']['paddingTop'] ) ? "padding-top:{$settings['contentsPadding']['paddingTop']};" : '';
			$styles .= ( '' != $settings['contentsPadding']['paddingRight'] ) ? "padding-right:{$settings['contentsPadding']['paddingRight']};" : '';
			$styles .= ( '' != $settings['contentsPadding']['paddingBottom'] ) ? "padding-bottom:{$settings['contentsPadding']['paddingBottom']};" : '';
			$styles .= ( '' != $settings['contentsPadding']['paddingLeft'] ) ? "padding-left:{$settings['contentsPadding']['paddingLeft']};" : '';
			if ( 'none' != $settings['contentsBorderStyle'] ) {
				$styles .= "border-style:{$settings['contentsBorderStyle']};";
				$styles .= ( '' != $settings['contentsBorderColor'] ) ? "border-color:{$settings['contentsBorderColor']};" : '';
				$styles .= ( '' != $settings['contentsBorderWidth']['borderTopWidth'] ) ? "border-top-width:{$settings['contentsBorderWidth']['borderTopWidth']};" : '';
				$styles .= ( '' != $settings['contentsBorderWidth']['borderRightWidth'] ) ? "border-right-width:{$settings['contentsBorderWidth']['borderRightWidth']};" : '';
				$styles .= ( '' != $settings['contentsBorderWidth']['borderBottomWidth'] ) ? "border-bottom-width:{$settings['contentsBorderWidth']['borderBottomWidth']};" : '';
				$styles .= ( '' != $settings['contentsBorderWidth']['borderLeftWidth'] ) ? "border-left-width:{$settings['contentsBorderWidth']['borderLeftWidth']};" : '';
			}
			$styles .= ( '' != $settings['contentsBorderRadius']['borderTopLeftRadius'] ) ? "border-top-left-radius:{$settings['contentsBorderRadius']['borderTopLeftRadius']};" : '';
			$styles .= ( '' != $settings['contentsBorderRadius']['borderTopRightRadius'] ) ? "border-top-right-radius:{$settings['contentsBorderRadius']['borderTopRightRadius']};" : '';
			$styles .= ( '' != $settings['contentsBorderRadius']['borderBottomLeftRadius'] ) ? "border-bottom-left-radius:{$settings['contentsBorderRadius']['borderBottomLeftRadius']};" : '';
			$styles .= ( '' != $settings['contentsBorderRadius']['borderBottomRightRadius'] ) ? "border-bottom-right-radius:{$settings['contentsBorderRadius']['borderBottomRightRadius']};" : '';
		$styles .= '}';
		return apply_filters( 'ditty_display_content_styles', $styles, $settings, $display, $type );
	}
	
	/**
	 * Add display item styles
	 */
	private function item_styles( $settings, $display, $type ) {
		$styles = '';
		$styles .= '.ditty[data-display="' . $display . '"] .ditty-item__elements {';
			$styles .= ( '' != $settings['itemTextColor'] ) ? "color:{$settings['itemTextColor']};" : '';
			$styles .= ( '' != $settings['itemBgColor'] ) ? "background-color:{$settings['itemBgColor']};" : '';
			$styles .= ( '' != $settings['itemPadding']['paddingTop'] ) ? "padding-top:{$settings['itemPadding']['paddingTop']};" : '';
			$styles .= ( '' != $settings['itemPadding']['paddingRight'] ) ? "padding-right:{$settings['itemPadding']['paddingRight']};" : '';
			$styles .= ( '' != $settings['itemPadding']['paddingBottom'] ) ? "padding-bottom:{$settings['itemPadding']['paddingBottom']};" : '';
			$styles .= ( '' != $settings['itemPadding']['paddingLeft'] ) ? "padding-left:{$settings['itemPadding']['paddingLeft']};" : '';
			if ( 'none' != $settings['itemBorderStyle'] ) {
				$styles .= "border-style:{$settings['itemBorderStyle']};";
				$styles .= ( '' != $settings['itemBorderColor'] ) ? "border-color:{$settings['itemBorderColor']};" : '';
				$styles .= ( '' != $settings['itemBorderWidth']['borderTopWidth'] ) ? "border-top-width:{$settings['itemBorderWidth']['borderTopWidth']};" : '';
				$styles .= ( '' != $settings['itemBorderWidth']['borderRightWidth'] ) ? "border-right-width:{$settings['itemBorderWidth']['borderRightWidth']};" : '';
				$styles .= ( '' != $settings['itemBorderWidth']['borderBottomWidth'] ) ? "border-bottom-width:{$settings['itemBorderWidth']['borderBottomWidth']};" : '';
				$styles .= ( '' != $settings['itemBorderWidth']['borderLeftWidth'] ) ? "border-left-width:{$settings['itemBorderWidth']['borderLeftWidth']};" : '';
			}
			$styles .= ( '' != $settings['itemBorderRadius']['borderTopLeftRadius'] ) ? "border-top-left-radius:{$settings['itemBorderRadius']['borderTopLeftRadius']};" : '';
			$styles .= ( '' != $settings['itemBorderRadius']['borderTopRightRadius'] ) ? "border-top-right-radius:{$settings['itemBorderRadius']['borderTopRightRadius']};" : '';
			$styles .= ( '' != $settings['itemBorderRadius']['borderBottomLeftRadius'] ) ? "border-bottom-left-radius:{$settings['itemBorderRadius']['borderBottomLeftRadius']};" : '';
			$styles .= ( '' != $settings['itemBorderRadius']['borderBottomRightRadius'] ) ? "border-bottom-right-radius:{$settings['itemBorderRadius']['borderBottomRightRadius']};" : '';
		$styles .= '}';
	
		return apply_filters( 'ditty_display_item_styles', $styles, $settings, $display, $type );
	}
}