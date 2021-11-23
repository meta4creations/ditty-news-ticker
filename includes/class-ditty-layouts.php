<?php
/**
 * Ditty Layouts
 *
 * @package     Ditty News Layouts
 * @subpackage  Classes/Ditty Layouts
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

use ScssPhp\ScssPhp\Compiler;
use Padaliyajay\PHPAutoprefixer\Autoprefixer;

class Ditty_Layouts {
	
	private $new_layouts;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
		
		// WP metabox hooks
		add_action( 'add_meta_boxes', array( $this, 'metaboxes' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ) );
		
		// General hooks
		add_filter( 'post_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );
		add_action( 'ditty_editor_update', array( $this, 'update_drafts' ), 10, 2 );
		add_filter( 'ditty_item_db_data', array( $this, 'modify_ditty_item_db_data'), 10, 2 );
		
		// Layout elements
		add_action( 'ditty_editor_layout_elements', array( $this, 'editor_layout_icon' ), 5 );
		add_action( 'ditty_editor_layout_elements', array( $this, 'editor_layout_label' ), 10 );
		add_action( 'ditty_editor_layout_elements', array( $this, 'editor_layout_edit_html' ), 15 );
		add_action( 'ditty_editor_layout_elements', array( $this, 'editor_layout_edit_css' ), 20 );
		add_action( 'ditty_editor_layout_elements', array( $this, 'editor_layout_clone' ), 25 );
		add_action( 'ditty_editor_layout_elements', array( $this, 'editor_layout_delete' ), 30 );
		
		// Ajax
		add_action( 'wp_ajax_ditty_editor_layout_variations', array( $this, 'editor_layout_variations_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_layout_variations', array( $this, 'editor_layout_variations_ajax' ) );	
		add_action( 'wp_ajax_ditty_editor_layouts', array( $this, 'editor_layouts_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_layouts', array( $this, 'editor_layouts_ajax' ) );	
		add_action( 'wp_ajax_ditty_editor_select_layout', array( $this, 'editor_select_layout_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_select_layout', array( $this, 'editor_select_layout_ajax' ) );
	
		add_action( 'wp_ajax_ditty_editor_layout_clone', array( $this, 'editor_clone_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_layout_clone', array( $this, 'editor_clone_ajax' ) );
		
		add_action( 'wp_ajax_ditty_editor_layout_fields', array( $this, 'editor_fields_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_layout_fields', array( $this, 'editor_fields_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_layout_update', array( $this, 'editor_update_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_layout_update', array( $this, 'editor_update_ajax' ) );
		
		add_action( 'wp_ajax_ditty_install_layout', array( $this, 'install_layout' ) );
	}
	
	/**
	 * Install default layouts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function install_default( $layout_type, $layout_template = false, $layout_version = false ) {
		$args = array(
			'template' 	=> $layout_template,
			'version'		=> $layout_version,
		);
		if ( $layouts = ditty_layouts_with_type( $layout_type, $args ) ) {
			return end( $layouts );
		}

		$layout_object = ditty_layout_type_object( $layout_type );
		$templates = $layout_object->templates();
		if ( ! isset( $templates[$layout_template] ) ) {
			return false;
		}
		$postarr = array(
			'post_type'		=> 'ditty_layout',
			'post_status'	=> 'publish',
			'post_title'	=> $templates[$layout_template]['label'],
		);
		if ( $new_layout_id = wp_insert_post( $postarr ) ) {
			update_post_meta( $new_layout_id, '_ditty_layout_type', esc_attr( $layout_type ) );
			update_post_meta( $new_layout_id, '_ditty_layout_template', esc_attr( $layout_template ) );
			if ( isset( $templates[$layout_template]['description'] ) ) {
				update_post_meta( $new_layout_id, '_ditty_layout_description', wp_kses_post( $templates[$layout_template]['description'] ) );
			}
			if ( isset( $templates[$layout_template]['html'] ) ) {
				update_post_meta( $new_layout_id, '_ditty_layout_html', wp_kses_post( $templates[$layout_template]['html'] ) );
			}
			if ( isset( $templates[$layout_template]['css'] ) ) {
				update_post_meta( $new_layout_id, '_ditty_layout_css', wp_kses_post( $templates[$layout_template]['css'] ) );
			}
			if ( isset( $templates[$layout_template]['variations'] ) ) {
					update_post_meta( $new_layout_id, '_ditty_layout_variations', wp_kses_post( $templates[$layout_template]['variations'] ) );
				}
			if ( isset( $templates[$layout_template]['version'] ) ) {
				update_post_meta( $new_layout_id, '_ditty_layout_version', wp_kses_post( $templates[$layout_template]['version'] ) );
			}
		}
		return $new_layout_id;
	}
	
	/**
	 * Install a layout via ajax
	 *
	 * @access public
	 * @since  3.0
	 */
	public function install_layout() {
		check_ajax_referer( 'ditty', 'security' );
		$layout_type_ajax 		= isset( $_POST['layout_type'] ) 			? $_POST['layout_type'] 		: false;
		$layout_template_ajax	= isset( $_POST['layout_template'] )	? $_POST['layout_template']	: false;
		$layout_version_ajax	= isset( $_POST['layout_version'] )		? $_POST['layout_version']	: false;
		
		if ( ! current_user_can( 'publish_ditty_layouts' ) || ! $layout_type_ajax || ! $layout_template_ajax ) {
			wp_die();
		}
		$layout_id = $this->install_default( $layout_type_ajax, $layout_template_ajax, $layout_version_ajax );
		
		$args = array(
			'type'				=> 'button',
			'label'				=> __( 'Installed', 'ditty-ticker' ),
			'link'				=> '#',
			'size' 				=> 'small',
			'input_class'	=> 'ditty-default-layout-view',
			'field_only'	=> true,
			'atts'				=> array(
				'disabled' => 'disabled',
			),
		);
		$button = ditty_field( $args );
		
		$data = array(
			'layout_id' => $layout_id,
			'button'	=> $button,
		);	
		wp_send_json( $data );
	}

	/**
	 * List the default layouts
	 *
	 * @access public
	 * @since  3.0
	 * @param  html
	 */
	public function layout_templates_list() {
		$html = '';
		$layout_types = ditty_layout_types();
		$default_layouts = ditty_default_layouts();
		if ( is_array( $default_layouts ) && count( $default_layouts ) > 0 ) {
			$html .= '<ul id="ditty-layout-templates">';
			foreach ( $default_layouts as $layout_type => $layout_data ) {
				$html .= '<li class="ditty-templates-list__type">';
					$html .= '<div class="ditty-templates-list__type__heading">';
						$html .= '<h3>' . $layout_data['label'] . '</h3>';
						// $field = array(
						// 	'type' 		=> 'select',
						// 	'id' 			=> 'layout_variation_defaults',
						// 	'name' 		=> __( 'Layout Templates', 'ditty-news-ticker' ),
						// 	'std' 		=> Ditty()->layouts->layout_templates_list(),
						// );
						// $html .= ditty_field( $field );
					$html .= '</div>';
					if ( is_array( $layout_data['templates'] ) && count( $layout_data['templates'] ) > 0 ) {
						$html .= '<ul id="ditty-templates-list__templates">';
						foreach ( $layout_data['templates'] as $template => $template_data ) {
							$args = array(
								'template' 	=> $template,
								'return'		=> 'versions',
							);
							$layout_versions = ditty_layouts_with_type( $layout_type, $args );
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
									'input_class'	=> 'ditty-default-layout-view',
									'field_only'	=> true,
								);
								if ( $layout_versions ) {	
									if ( in_array( $template_data['version'], $layout_versions ) ) {
										$args['label'] = __( 'Installed', 'ditty-ticker' );
										$args['atts'] = array(
											'disabled' => 'disabled',
										);
									} else {
										$args['label'] = sprintf( __( 'Install Version %s', 'ditty-ticker' ), $template_data['version'] );
										$args['input_class'] = 'ditty-default-layout-install';
										$args['icon_after'] = 'fas fa-download';
										$args['atts'] = array(
											'data-layout_type' => $layout_type,
											'data-layout_template' => $template,
											'data-layout_version' => $template_data['version'],
										);
									}
								} else {
									$args['label'] = __( 'Install Template', 'ditty-ticker' );
									$args['input_class'] = 'ditty-default-layout-install';
									$args['icon_after'] = 'fas fa-download';
									$args['atts'] = array(
										'data-layout_type' => $layout_type,
										'data-layout_template' => $template,
										'data-layout_version' => $template_data['version'],
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
		if ( $post->post_type == 'ditty_layout' ) {
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
		add_meta_box( 'ditty-layout-info', __( 'Layout Info', 'ditty-news-ticker' ), array( $this, 'metabox_layout_info' ), 'ditty_layout', 'side', 'high' );
		add_meta_box( 'ditty-layout-html', __( 'Layout HTML', 'ditty-news-ticker' ), array( $this, 'metabox_layout_html' ), 'ditty_layout', 'normal' );
		add_meta_box( 'ditty-layout-css', __( 'Layout CSS', 'ditty-news-ticker' ), array( $this, 'metabox_layout_css' ), 'ditty_layout', 'normal' );
	}
	
	/**
	 * Save custom meta
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function metabox_save( $post_id ) {
		global $post;
		
		// verify nonce
		if ( ! isset( $_POST['ditty_layout_nonce'] ) || ! wp_verify_nonce( $_POST['ditty_layout_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}
	
		// check autosave
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) return $post_id;
		
		// don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) return $post_id;
	
		// check permissions
		if ( isset( $_POST['post_type'] ) && 'ditty_layout' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_ditty_layout', $post_id ) ) {
				return $post_id;
			}
		} elseif ( ! current_user_can( 'edit_ditty_layout', $post_id ) ) {
			return $post_id;
		}
		
		if ( ! isset( $_POST['_ditty_layout_type'] ) ) {
			return $post_id;
		}
		
		$layout_type = sanitize_text_field( $_POST['_ditty_layout_type'] );
		$layout_description = sanitize_text_field( $_POST['_ditty_layout_description'] );
		$layout_html = wp_kses_post( $_POST['_ditty_layout_html'] );	
		$layout_css = wp_kses_post( $_POST['_ditty_layout_css'] );	
		
		update_post_meta( $post_id, '_ditty_layout_type', $layout_type );
		update_post_meta( $post_id, '_ditty_layout_description', $layout_description );
		update_post_meta( $post_id, '_ditty_layout_html', $layout_html );
		update_post_meta( $post_id, '_ditty_layout_css', $layout_css );
		
		// Remove the version number of edited layouts
		delete_post_meta( $post_id, '_ditty_layout_version' );
	}
	
	/**
	 * Add the Layout info metabox
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function metabox_layout_info() {
		global $post;
		$layout_types = ditty_layout_types();
		$layout_type_options = array(
			'' => __( 'Select a Layout Type', 'ditty-news-ticker' ),
		);
		if ( is_array( $layout_types ) && count( $layout_types ) > 0 ) {
			foreach ( $layout_types as $id => $type ) {
				$layout_type_options[$id] = $type['label'];
			}
		}
		
		$layout_type = get_post_meta( $post->ID, '_ditty_layout_type', true );
		$layout_description = get_post_meta( $post->ID, '_ditty_layout_description', true );
		
		$layout_options = $this->select_field_options( $layout_type, __( 'Select a Layout', 'ditty-news-ticker' ) );
		
		$fields = array();
		$fields['layout_type'] = array(
			'type' 		=> 'select',
			'id'			=> '_ditty_layout_type',
			'name' 		=> __( 'Layout Type', 'ditty-news-ticker' ),
			'options' => $layout_type_options,
			'std' 		=> $layout_type,
		);
		$fields['layout_code'] = array(
			'type' 		=> 'select',
			'id'			=> '_ditty_layout_code',
			'name' 		=> __( 'Layout Code', 'ditty-news-ticker' ),
			'desc' 		=> __( "Overwrite the HTML & CSS with another layout's data. Choose carefully!", 'ditty' ),
			'options' => $layout_options,
		);
		$fields['desciption'] = array(
			'type' => 'textarea',
			'id'	=> '_ditty_layout_description',
			'name' => __( 'Description', 'ditty-news-ticker' ),
			'std' => $layout_description,
		);
		ditty_fields( $fields );
		echo '<input type="hidden" name="ditty_layout_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';
	}
	
	/**
	 * Add the Layout html metabox
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function metabox_layout_html() {
		global $post;
		$layout_html = get_post_meta( $post->ID, '_ditty_layout_html', true );	
		$field_args = array(
			'type' 	=> 'code',
			'id'		=> '_ditty_layout_html',
			'name' 	=> false,
			'std' 	=> $layout_html,
		);
		echo ditty_field( $field_args );
	}
	
	/**
	 * Add the Layout css metabox
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function metabox_layout_css() {
		global $post;
		$layout_css = get_post_meta( $post->ID, '_ditty_layout_css', true );
		$field_args = array(
			'type' 	=> 'code',
			'id'		=> '_ditty_layout_css',
			'name' 	=> false,
			'rows'	=> 8,
			'std' 	=> stripslashes( $layout_css ),
			'js_options' => array(
				'mode' => 'sass',
			),
		);
		echo ditty_field( $field_args );
	}

	/**
	 * Render the layout css styles
	 *
	 * @since    3.0
	 * @access   public
	 * @var      string    $css
	*/
	public function compile_layout_style( $css, $layout_id ) {	
		$styles = '';
		
		if ( is_numeric( $layout_id ) ) {
			$styles .= '.ditty-layout--' .$layout_id . '{';
				$styles .= html_entity_decode( $css );
			$styles .= '}';
			if ( is_ditty_post() ) {
				$styles .= '#poststuff .ditty-layout--' . $layout_id . '{';
					$styles .= html_entity_decode( $css );
				$styles .= '}';
			}
		} else {
			$styles .= '.ditty-layout--' . $layout_id . '{';
				$styles .= html_entity_decode( $css );
			$styles .= '}';
			if ( is_ditty_post() ) {
				$styles .= '#poststuff .ditty-layout--' . $layout_id . '{';
					$styles .= html_entity_decode( $css );
				$styles .= '}';
			}
		}

		// Compile the sass & remove whitespace
		try {
			$scss = new Compiler();
			$compiled_styles = $scss->compile( $styles );
		} catch ( \Exception $e ) {
			return false;
		}

		// Add auto-prefixes
		$autoprefixer = new Autoprefixer( $compiled_styles );
		$prefixed_css = $autoprefixer->compile();
				
		// Remove multiple white-spaces, tabs and new-lines
		$final_css = preg_replace( '/\s+/S', ' ', $prefixed_css );
		return wp_kses_post( trim( $final_css ) );
	}
	
	/**
	 * Return an array of all layouts for select fields
	 *
	 * @access  private
	 * @since   3.0
	 * @param   array    $options.
	 */
	private function select_field_options( $type, $placeholder = false ) {
		$options = array();
		if ( $placeholder ) {
			$options[''] = $placeholder;
		}
		if ( $layout_object = ditty_layout_type_object( $type ) ) {
			if ( $layouts = $this->get_layouts_by_type( $type ) ) {
				foreach ( $layouts as $id ) {
					$options[$id] = get_the_title( $id );
				}
			}
		}
		return $options;
	}

	/**
	 * Return an array of custom layouts
	 *
	 * @access  private
	 * @since   3.0
	 * @param   array    $layouts.
	 */
	public function get_layouts_by_type( $type, $fields = 'ids' ) {
		$args = array(
			'posts_per_page' 	=> -1,
			'orderby' 				=> 'post_title',
			'order' 					=> 'ASC',
			'post_type' 			=> 'ditty_layout',
			'meta_key'				=> '_ditty_layout_type',
			'meta_value'			=> $type,
			'fields' 					=> $fields,
		);
		return get_posts( $args );
	}
	
	/**
	 * Add the editor item icon
	 *
	 * @since    3.0
	 */
	public function editor_layout_icon( $layout ) {
		echo '<span class="ditty-data-list__item__icon"><i class="' . $layout->get_icon() . '" data-class="' . $layout->get_icon() . '"></i></span>';
	}
	
	/**
	 * Add the editor item label
	 *
	 * @since    3.0
	 */
	public function editor_layout_label( $layout ) {
		$version = $layout->get_version();
		$version_string = '';
		if ( $version ) {
			$version_string = " <small class='ditty-layout-version'>(v{$version})</small>";
		}
		?>
		<span class="ditty-data-list__item__label"><?php echo $layout->get_label(); ?><?php echo $version_string; ?></span>
		<?php
	}
	
	/**
	 * Add the editor edit html button
	 *
	 * @since    3.0
	 */
	public function editor_layout_edit_html( $layout ) {
		if ( current_user_can( 'edit_ditty_layouts' ) ) {
			echo '<a href="#" class="ditty-data-list__item__edit_html protip" data-pt-title="' . __( 'Edit HTML', 'ditty-news-ticker' ) . '"><i class="fas fa-code" data-class="fas fa-code"></i></a>';
		}
	}
	
	/**
	 * Add the editor edit css button
	 *
	 * @since    3.0
	 */
	public function editor_layout_edit_css( $layout ) {
		if ( current_user_can( 'edit_ditty_layouts' ) ) {
			echo '<a href="#" class="ditty-data-list__item__edit_css protip" data-pt-title="' . __( 'Edit CSS', 'ditty-news-ticker' ) . '"><i class="fas fa-eye" data-class="fas fa-eye"></i></a>';
		}
	}
	
	/**
	 * Add the editor clone button
	 *
	 * @since    3.0
	 */
	public function editor_layout_clone( $layout ) {
		if ( current_user_can( 'publish_ditty_layouts' ) ) {
			echo '<a href="#" class="ditty-data-list__item__clone protip" data-pt-title="' . __( 'Clone', 'ditty-news-ticker' ) . '"><i class="fas fa-clone" data-class="fas fa-clone"></i></a>';
		}
	}
	
	/**
	 * Add the editor delete button
	 *
	 * @since    3.0
	 */
	public function editor_layout_delete( $layout ) {
		if ( current_user_can( 'delete_ditty_layouts' ) ) {
			echo '<a href="#" class="ditty-data-list__item__delete protip" data-pt-title="' . __( 'Delete', 'ditty-news-ticker' ) . '"><i class="fas fa-trash-alt" data-class="fas fa-trash-alt"></i></a>';
		}
	}
	
	/**
	 * Ditty Layout Variations list panel
	 * @access public
	 * @since  3.0
	 */
	public function editor_layout_variations_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		if ( ! current_user_can( 'edit_ditty_layouts' ) ) {
			wp_die();
		}	
		$ditty_id_ajax 			= isset( $_POST['ditty_id'] ) 		? $_POST['ditty_id'] 			: false;
		$item_type_ajax 		= isset( $_POST['item_type'] ) 		? $_POST['item_type'] 		: false;
		$item_label_ajax 		= isset( $_POST['item_label'] ) 	? $_POST['item_label'] 		: false;
		$layout_value_ajax 	= isset( $_POST['layout_value'] )	? $_POST['layout_value'] 	: false;
		$draft_values_ajax 	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 	: false;
		ditty_set_draft_values( $draft_values_ajax );
		
		if ( is_array( $layout_value_ajax ) ) {
			$layout_value = $layout_value_ajax;
		} else {
			$layout_value = json_decode( stripslashes( $layout_value_ajax ), true );
		}
		?>
		<div class="ditty-editor-options ditty-metabox">
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__header">
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--start">
						<a href="#" class="ditty-editor-options__back"><i class="fas fa-chevron-left" data-class="fas fa-chevron-left"></i></a>
					</div>
					<h3 class="ditty-editor-options__title"><?php echo sprintf( __( 'Layout: <span>%s</span>', 'ditty-news-ticker' ), stripslashes( $item_label_ajax ) ); ?></h3>
				</div>
				<div class="ditty-data-list">
					<div class="ditty-data-list__items">
						<?php
						if ( $item_type_object = ditty_item_type_object( $item_type_ajax ) ) {
							$variations = $item_type_object->get_layout_variation_data( $layout_value );
							if ( is_array( $variations ) && count( $variations ) > 0 ) {
								foreach ( $variations as $id => $data ) {
									$layout_type_object = ditty_layout_type_object( $data['type'] );
									$layout_id = $data['template'];
									$layout = new Ditty_Layout( $layout_id );
									$version = $layout->get_version();
									$version_string = '';
									if ( $version ) {
										$version_string = " <small class='ditty-layout-version'>(v{$version})</small>";
									}
									?>	
									<div class="ditty-layout-variation ditty-layout-variation--<?php echo $id; ?> ditty-data-list__item" data-layout_variation_id="<?php echo $id; ?>" data-layout_variation_label="<?php echo $data['label']; ?>" data-layout_type="<?php echo $layout_type_object->get_type(); ?>" data-layout_id="<?php echo $layout_id; ?>">
										<span class="ditty-layout-variation__icon"><i class="<?php echo $layout_type_object->get_icon(); ?>"></i></span>
										<div class="ditty-layout-variation__content">
											<span class="ditty-layout-variation__label"><?php printf( __( 'Variation: %s', 'ditty-news-ticker' ), $data['label'] ); ?></span>
											<span class="ditty-layout-variation__template"><?php echo wp_sprintf( __( 'Template: <span>%s</span>%s', 'ditty-news-ticker' ), $layout->get_label(), $version_string ); ?></span>
											<div class="ditty-layout-variation__buttons">
												<a href="#" class="ditty-layout-variation__edit_html protip" data-pt-title="<?php _e( 'Edit HTML', 'ditty-news-ticker' ); ?>"><i class="fas fa-code" data-class="fas fa-code"></i></a>
												<a href="#" class="ditty-layout-variation__edit_css protip" data-pt-title="<?php _e( 'Edit CSS', 'ditty-news-ticker' ); ?>"><i class="fas fa-eye" data-class="fas fa-eye"></i></a>			
											</div>
										</div>
										<a href="#" class="ditty-layout-variation__change protip" data-pt-title="<?php _e( 'Change Template', 'ditty-news-ticker' ); ?>"><i class="fas fa-exchange-alt" data-class="fas fa-exchange-alt"></i></a>		
									</div>
									<?php
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		wp_die();
	}
	
	/**
	 * Ditty Layout list panel
	 * @access public
	 * @since  3.0
	 */
	public function editor_layouts_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		if ( ! current_user_can( 'edit_ditty_layouts' ) ) {
			wp_die();
		}	
		$ditty_id_ajax 				= isset( $_POST['ditty_id'] ) 				? $_POST['ditty_id'] 				: false;
		$item_type_ajax 			= isset( $_POST['item_type'] ) 				? $_POST['item_type'] 			: false;
		$variation_label_ajax	= isset( $_POST['variation_label'] ) 	? $_POST['variation_label'] : false;
		$layout_id_ajax 			= isset( $_POST['layout_id'] ) 				? $_POST['layout_id'] 			: false;
		$layout_type_ajax 		= isset( $_POST['layout_type'] )			? $_POST['layout_type'] 		: false;
		$draft_values_ajax 		= isset( $_POST['draft_values'] ) 		? $_POST['draft_values'] 		: false;
		ditty_set_draft_values( $draft_values_ajax );
		?>
		<div class="ditty-editor-options ditty-metabox">
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__header">
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--start">
						<a href="#" class="ditty-editor-options__back"><i class="fas fa-chevron-left" data-class="fas fa-chevron-left"></i></a>
					</div>
					<h3 class="ditty-editor-options__title"><?php echo sprintf( __( 'Variation: <span>%s</span>', 'ditty-news-ticker' ), stripslashes( $variation_label_ajax ) ); ?></h3>
				</div>
				<div class="ditty-data-list">
					<div class="ditty-data-list__items" data-active="<?php echo $layout_id_ajax; ?>">
						<?php
						if ( $layout_object = ditty_layout_type_object( $layout_type_ajax ) ) {
							if ( $custom_layouts = $this->get_layouts_by_type( $layout_type_ajax ) ) {
								foreach ( $custom_layouts as $i => $id ) {
									$layout = new Ditty_Layout( $id, $layout_type_ajax );
									echo $layout->render_editor_list_item( 'return' );
								}
							}
							if ( $drafts = ditty_draft_layout_get() ) {
								foreach ( $drafts as $id => $data ) {
									if ( false !== strpos( $id, 'new-' ) ) {
										$layout = new Ditty_Layout( $id, $layout_type_ajax );
										echo $layout->render_editor_list_item( 'return' );
									}		
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		wp_die();
	}
	
	/**
	 * Update the item's layout
	 * @access public
	 * @since  3.0
	 */
	public function editor_select_layout_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$layout_id_ajax 		= isset( $_POST['layout_id'] ) 		? $_POST['layout_id'] 		: false;
		$layout_type_ajax 	= isset( $_POST['layout_type'] ) 	? $_POST['layout_type'] 	: false;
		$item_id_ajax 			= isset( $_POST['item_id'] ) 			? $_POST['item_id'] 			: false;
		$draft_values_ajax 	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 	: false;
		if ( ! current_user_can( 'edit_ditty_items' ) || ! $item_id_ajax || ! $layout_id_ajax ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		$editor_item = new Ditty_Item( $item_id_ajax );
		$editor_layout = new Ditty_Layout( $layout_id_ajax, $layout_type_ajax );
		$data = array(
			'editor_item' 			=> $editor_item->render_editor_list_item( 'return' ),
			'display_items' 		=> $editor_item->get_display_items(),
			'layout_label'			=> $editor_layout->get_label(),
			'layout_css'				=> $editor_layout->get_css_compiled(),
			'layout_type'				=> $editor_layout->get_layout_type(),
		);	
		wp_send_json( $data );
	}
	
	/**
	 * Return the html field to edit
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_fields_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$edit_type_ajax 		= isset( $_POST['edit_type'] ) 		? sanitize_text_field( $_POST['edit_type'] ) 		: false;
		$layout_id_ajax 		= isset( $_POST['layout_id'] ) 		? sanitize_text_field( $_POST['layout_id'] ) 		: false;
		$layout_type_ajax 	= isset( $_POST['layout_type'] ) 	? sanitize_text_field( $_POST['layout_type'] ) 	: false;
		$layout_title_ajax 	= isset( $_POST['layout_title'] ) ? sanitize_text_field( $_POST['layout_title'] ) : false;
		$item_id_ajax 			= isset( $_POST['item_id'] ) 			? sanitize_text_field( $_POST['item_id'] ) 			: false;
		$draft_values_ajax 	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 	: false;
		if ( ! current_user_can( 'edit_ditty_layouts' ) ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		if ( ! $layout = new Ditty_Layout( $layout_id_ajax, $layout_type_ajax ) ) {
			wp_die();
		}
		
		$quick_change = '';
		$textarea_val = '';
		$tags_list = '';
		$html_tags = '';
		
		$title = $layout->get_label();
		if ( $layout_title_ajax && ( $title !== $layout_title_ajax ) ) {
			$layout->set_label( $layout_title_ajax );	
			$title = $layout_title_ajax;
		}
		
		switch( $edit_type_ajax ) {
			case 'html':
				$textarea_val = stripslashes( $layout->get_html() );
				$tags_list = $layout->get_html_tags_list();
				$quick_change = '<a href="#" class="ditty-editor-options__edit-css protip" data-pt-title="' . __( 'Edit CSS', 'ditty-news-ticker' ) . '"><i class="fas fa-eye" data-class="fas fa-eye"></i></a>';								
				break;
			case 'css':
				$textarea_val = stripslashes( $layout->get_css() );
				$tags_list = $layout->get_css_selectors_list();
				$quick_change = '<a href="#" class="ditty-editor-options__edit-html protip" data-pt-title="' . __( 'Edit HTML', 'ditty-news-ticker' ) . '"><i class="fas fa-code" data-class="fas fa-code"></i></a>';
				break;
			default:
				break;
		}		
		ob_start();
		?>
		<form class="ditty-editor-options ditty-metabox" data-layout_id="<?php echo $layout_id_ajax; ?>" data-layout_type="<?php echo $layout_type_ajax; ?>">
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__header">
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--start">
						<a href="#" class="ditty-editor-options__back"><i class="fas fa-chevron-left" data-class="fas fa-chevron-left"></i></a>
					</div>
					<input class="ditty-editor-options__title" type="text" name="title" placeholder="<?php _e( 'Title Goes Here...', 'ditty-news-ticker' ); ?>" value="<?php echo $title; ?>" />
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--end">
						<?php echo $quick_change; ?>
						<a href="#" class="ditty-editor-options__preview"><i class="fas fa-sync-alt" data-class="fas fa-sync-alt"></i></a>
					</div>
				</div>
				<div class="ditty-editor-options__body">
					<textarea class="ditty-editor-options__code" name="_ditty_layout_code" cols="80" rows="8"><?php echo $textarea_val; ?></textarea>
				</div>
				<div class="ditty-editor-options__footer">
					<div class="ditty-editor-options__tags">
						<h3><?php _e( 'Tags', 'ditty-news-ticker' ); ?><?php if ( 'html' == $edit_type_ajax ) { echo ' *'; } ?></h3>						
						<?php echo $tags_list; ?>
						<?php if ( 'html' == $edit_type_ajax ) {
						echo '<p style="margin-bottom:0;"><small>* ' . __( 'Hold the shift key when clicking the tag to paste all default attributes', 'ditty-news-ticker' ) . '</small></p>';
					} ?>
					</div>
				</div>
			</div>
		</form>
		<?php
		$form = ob_get_clean(); 	
		wp_send_json(
			array(
				'form' => $form,
				'html' => $layout->get_html(),
			)
		);
	}
	
	/**
	 * Update the layout meta
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$edit_type_ajax 		= isset( $_POST['edit_type'] ) 					? sanitize_text_field( $_POST['edit_type'] ) 		: false;
		$layout_id_ajax 		= isset( $_POST['layout_id'] ) 					? sanitize_text_field( $_POST['layout_id'] ) 		: false;
		$layout_type_ajax 	= isset( $_POST['layout_type'] ) 				? sanitize_text_field( $_POST['layout_type'] ) 	: false;
		$title_ajax 				= isset( $_POST['title'] ) 							? sanitize_text_field( $_POST['title'] ) 				: false;
		$item_ids_ajax 			= isset( $_POST['item_ids'] ) 					? $_POST['item_ids'] 														: array();
		$code_ajax 					= isset( $_POST['_ditty_layout_code'] )	? wp_kses_post( $_POST['_ditty_layout_code'] )	: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] ) 			? $_POST['draft_values']												: false;
		$display_items 			= array();
		if ( ! current_user_can( 'edit_ditty_layouts' ) ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		ditty_draft_layout_update( $layout_id_ajax, $edit_type_ajax, $code_ajax );
		if ( ! $editor_layout = new Ditty_Layout( $layout_id_ajax, $layout_type_ajax ) ) {
			wp_die();
		}

		switch( $edit_type_ajax ) {
			case 'html':
				$code = $editor_layout->set_html( $code_ajax );
				
				// Get updated items
				$item_ids = array_unique( $item_ids_ajax );
				if ( is_array( $item_ids ) && count( $item_ids ) > 0 ) {
					foreach ( $item_ids as $i => $item_id ) {
						$editor_item = new Ditty_Item( $item_id );
						$display_items = array_merge( $display_items, $editor_item->get_display_items() );
					}
				}
				break;
			case 'css':
				$editor_layout->set_css( $code_ajax );
				$code = $editor_layout->get_css_compiled();
				break;
			default:
				break;
		}
		
		if ( $title_ajax ) {
			$editor_layout->set_label( $title_ajax );
		}
		
		wp_send_json(
			array(
				'label' 				=> $editor_layout->get_label(),
				'code'					=> $code,
				'display_items' => $display_items,
				'draft_id' 			=> $editor_layout->get_layout_id(),
				'draft_meta'		=> $editor_layout->custom_meta(),
			)
		);
	}
	
	/**
	 * Clone a layout via ajax
	 *
	 * @since    3.0
	 */
	public function editor_clone_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$layout_id_ajax 		= isset( $_POST['layout_id'] ) 		? sanitize_text_field( $_POST['layout_id'] ) 		: false;
		$layout_type_ajax 	= isset( $_POST['layout_type'] ) 	? sanitize_text_field( $_POST['layout_type'] )	: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 												: false;
		if ( ! current_user_can( 'publish_ditty_layouts' ) || ! $layout_id_ajax || ! $layout_type_ajax ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );

		// Get an instance of the source layout
		$editor_layout = new Ditty_Layout( $layout_id_ajax, $layout_type_ajax );
		$draft_id = uniqid( 'new-' );
		$draft_label = sprintf( __( '%s Clone', 'ditty-news-ticker' ), $editor_layout->get_label() );
		$editor_layout->set_layout_id( $draft_id );
		$editor_layout->set_label( $draft_label );
		$data = array(
			'editor_layout' => $editor_layout->render_editor_list_item( 'return' ),
			'draft_id' 			=> $draft_id,
			'draft_meta'		=> $editor_layout->custom_meta(),
		);
		wp_send_json( $data );
	}
	
	/**
	 * Return the temporary new layout IDs
	 *
	 * @access public
	 * @since  3.0
	 * @param  array $new_layouts
	 */
	private function get_new_layouts() {
		if ( empty( $this->new_layouts ) ) {
			$this->new_layouts = array();
		}
		return $this->new_layouts;
	}
	
	/**
	 * Update the temporary new layout IDs
	 *
	 * @access public
	 * @since  3.0
	 * @param  array $new_displays
	 */
	private function update_new_layouts( $new_id, $post_id ) {
		$new_layouts = $this->get_new_layouts();
		$new_layouts[$new_id] = $post_id;
		$this->new_layouts = $new_layouts;
	}
	
	/**
	 * Modify a Ditty's draft meta
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function modify_ditty_item_db_data( $item_data, $ditty_id ) {
		if ( isset( $item_data['layout_value'] ) ) {
			$layout_value = maybe_unserialize( $item_data['layout_value'] );
			$new_layouts = $this->get_new_layouts();	
			$updated_layout_value = array();
			if ( is_array( $layout_value ) && count( $layout_value ) > 0 ) {
				foreach ( $layout_value as $type => $id ) {
					if ( false !== strpos( $id, 'new-' ) ) {
						if ( isset( $new_layouts[$id] ) ) {
							$updated_layout_value[$type] = $new_layouts[$id];
						}
					} else {
						$updated_layout_value[$type] = $id;
					}
				}
			}
			$item_data['layout_value'] = maybe_serialize( $updated_layout_value );
		}
		return $item_data;
	}

	/**
	 * Save layout draft values on Ditty update
	 *
	 * @access public
	 * @since  3.0
	 */
	public function update_drafts( $ditty_id, $draft_values ) {
		if ( ! current_user_can( 'edit_ditty_layouts' ) ) {
			return false;
		}
		
		$add_to_live_update = false;

		if ( isset( $draft_values['layouts'] ) && is_array( $draft_values['layouts'] ) && count( $draft_values['layouts'] ) > 0 ) {
			foreach ( $draft_values['layouts'] as $layout_id => $layout_data ) {
				
				$add_to_live_update = true;
				
				// Delete a layout
				if ( 'DELETE' == $layout_data ) {
					wp_trash_post( $layout_id );
					continue;
				
				} elseif( is_array( $layout_data ) ) {
					
					// Add or update a layout
					if ( false !== strpos( $layout_id, 'new-' ) ) {
						$postarr = array(
							'post_type'		=> 'ditty_layout',
							'post_status'	=> 'publish',
							'post_title'	=> $layout_data['label'],
						);
						$updated_layout_id = wp_insert_post( $postarr );
						$this->update_new_layouts( $layout_id, $updated_layout_id );
						$layout_id = $updated_layout_id;		
					} else {
						if ( isset( $layout_data['label'] ) ) {
							$postarr = array(
								'ID'					=> $layout_id,
								'post_title'	=> $layout_data['label'],
							);
							wp_update_post( $postarr );
						}
					}
					
					// Update a layout type
					if ( isset( $layout_data['layout_type'] ) ) {
						update_post_meta( $layout_id, '_ditty_layout_type', esc_attr( $layout_data['layout_type'] ) );
					}
	
					// Update a layout description
					if ( isset( $layout_data['description'] ) ) {
						update_post_meta( $layout_id, '_ditty_layout_description', wp_kses_post( $layout_data['description'] ) );
					}
					
					// Update a layout html
					if ( isset( $layout_data['html'] ) ) {
						update_post_meta( $layout_id, '_ditty_layout_html', wp_kses_post( $layout_data['html'] ) );
					}
					
					// Update a layout css
					if ( isset( $layout_data['css'] ) ) {
						update_post_meta( $layout_id, '_ditty_layout_css', wp_kses_post( $layout_data['css'] ) );
					}	
					
					// Remove the version number of edited layouts
					delete_post_meta( $layout_id, '_ditty_layout_version' );
				}
			}
		}
	}

}