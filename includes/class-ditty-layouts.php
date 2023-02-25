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
	private $updated_layouts;

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
		
		add_action( 'wp_ajax_ditty_install_layout', array( $this, 'install_layout' ) );
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
		
		$layout_description = sanitize_text_field( $_POST['_ditty_layout_description'] );
		$layout_html = wp_kses_post( $_POST['_ditty_layout_html'] );	
		$layout_css = wp_kses_post( $_POST['_ditty_layout_css'] );	

		update_post_meta( $post_id, '_ditty_layout_description', $layout_description );
		update_post_meta( $post_id, '_ditty_layout_html', $layout_html );
		update_post_meta( $post_id, '_ditty_layout_css', $layout_css );
		
		// Possibly add a uniq_id
		ditty_maybe_add_uniq_id( $post_id );
		
		// Remove the version number of edited layouts
		delete_post_meta( $post_id, '_ditty_layout_template' );
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
		$layout_description = get_post_meta( $post->ID, '_ditty_layout_description', true );

		$fields = array();
		$fields['description'] = array(
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
	 * @since    3.1
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
			$compiled_styles = $scss->compileString( $styles )->getCss();
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
	private function select_field_options( $placeholder = false ) {
		$options = array();
		if ( $placeholder ) {
			$options[''] = $placeholder;
		}
		if ( $layouts = ditty_layout_posts() ) {
			foreach ( $layouts as $layout_post ) {
				$title = $layout_post->post_title;
				if ( $version = get_post_meta( $layout_post->ID, '_ditty_layout_version', true ) ) {
					$title .= " (v{$version})";
				}
				$options[$layout_post->ID] = $title;
			}
		}
		return $options;
	}

	/**
	 * Add layout styles
	 */
	public function add_styles( $items ) {
		global $ditty_layout_styles;
		if ( empty( $ditty_layout_styles ) ) {
			$ditty_layout_styles = array();
		}
		$html = '';
		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $i => $item ) {
				if ( isset( $ditty_layout_styles[$item['layout_id']] ) ) {
					continue;
				}
				$ditty_layout_styles[$item['layout_id']] = $item['layout_id'];
				$styles = apply_filters( 'ditty_layout_styles', $item['css'], $item['layout_id'] );
				$html .= '<style id="ditty-layout--' . $item['layout_id'] . '">' . $styles . '</style>';
			}
		}
		return $html;
	}
	
	/**
	 * Add to the bulk updater
	 *
	 * @access public
	 * @since  3.0.17
	 * @param   json.
	 */
	public function bulk_export( $bulk_actions ) {
		$bulk_actions['ditty-export'] = esc_html__( 'Export Layouts', 'ditty-news-ticker' );
		return $bulk_actions;
	}

	/**
	 * Return layout tag attribute default settings
	 *
	 * @access  private
	 * @since   3.1
	 */
	public function tag_attribute_default_settings ( $tag, $default = false ) {
		switch( $tag ) {
			case 'wrapper':
				return [
					'type' => "select",
      		'id' =>  "wrapper",
      		'options' => [
						"div",
  					"h1",
						"h2",
						"h3",
						"h4",
						"h5",
						"h6",
						"p",
						"span",
						"none",
					],
					'help' =>  __(
						"Set the containing element of the rendered tag.",
						"ditty-news-ticker"
					),
      		'std' => $default ? $default : 'div',
				];
		}
	}
	
	/**
	 * Install default layouts
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function install_default( $layout_template = false, $layout_version = false ) {
		$args = array(
			'template' 	=> $layout_template,
			'version'		=> $layout_version,
			'fields'		=> 'ids',
		);
		if ( $layouts = ditty_layout_posts( $args ) ) {
			return end( $layouts );
		}

		$templates = ditty_layout_templates();
		if ( ! isset( $templates[$layout_template] ) ) {
			return false;
		}
		$postarr = array(
			'post_type'		=> 'ditty_layout',
			'post_status'	=> 'publish',
			'post_title'	=> $templates[$layout_template]['label'],
		);
		if ( $new_layout_id = wp_insert_post( $postarr ) ) {
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
		$layout_template_ajax	= isset( $_POST['layout_template'] )	? $_POST['layout_template']	: false;
		$layout_version_ajax	= isset( $_POST['layout_version'] )		? $_POST['layout_version']	: false;
		
		if ( ! current_user_can( 'publish_ditty_layouts' ) || ! $layout_template_ajax ) {
			wp_die();
		}
		$layout_id = $this->install_default( $layout_template_ajax, $layout_version_ajax );
		
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
	 * List the layout variation defaults
	 *
	 * @access public
	 * @since  3.0
	 * @param  html
	 */
	public function variation_defaults() {
		$html = '';
		$item_types = ditty_item_types();
		$variation_types = ditty_layout_variation_types();
		$settings = ditty_settings( 'variation_defaults' );
		$layout_options = $this->select_field_options( __( 'Choose a Layout', 'ditty-news-ticker' ) );

		if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
			$html .= '<div id="ditty-layout-variation-defaults">';
				$html .= '<h3>' . __( 'Layout Variations', 'ditty-news-ticker' ) . '</h3>';
				$html .= '<ul>';
				foreach ( $variation_types as $item_type => $item_type_variations ) {
					if ( ! isset( $item_types[$item_type] ) ) {
						continue;
					}
					$html .= '<li class="ditty-layout-variation-defaults__item_type">';
						if ( is_array( $item_type_variations ) && count( $item_type_variations ) > 0 ) {
							$fields = array();
							foreach ( $item_type_variations as $variation_id => $item_type_variation ) {
								$fields[] = array(
									'id'			=> "variation_default_{$item_type}_{$variation_id}",
									'type'		=> 'select',
									'name'		=> $item_type_variation['label'],
									'desc'		=> $item_type_variation['description'],
									'options'	=> $layout_options,
									'std'			=> ( isset( $settings[$item_type] ) && isset( $settings[$item_type][$variation_id] ) ) ? $settings[$item_type][$variation_id] : false,
								);
							}
							$args = array(
								'id'							=> 'variation_defaults',
								'type'						=> 'group',
								'name'						=> "<i class='{$item_types[$item_type]['icon']}'></i> " . $item_types[$item_type]['label'],
								'collapsible'			=> true,
								'multiple_fields' => true,
								'fields'					=> $fields,
								//'class'						=> 'ditty-field--variation_defaults',
							);
							$html .= ditty_field( $args );
						}
					$html .= '</li>';
				}
				$html .= '</ul>';
			$html .= '</div>';
		}
		
		return $html;
	}

	/**
	 * List the layout templates
	 *
	 * @access public
	 * @since  3.0
	 * @param  html
	 */
	public function layout_templates_list() {
		$html = '';
		$layout_templates = ditty_layout_templates();
		if ( is_array( $layout_templates ) && count( $layout_templates ) > 0 ) {
			$html .= '<div id="ditty-layout-templates">';
				$html .= '<h3>' . __( 'Layout Templates', 'ditty-news-ticker' ) . '</h3>';
				$html .= '<ul id="ditty-templates-list__templates">';
				foreach ( $layout_templates as $template_slug => $template_data ) {
					$args = array(
						'template' 	=> $template_slug,
						'fields'		=> 'ids',
						'return'		=> 'versions',
					);
					$layout_versions = ditty_layout_posts( $args );
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
									'data-layout_template' => $template_slug,
									'data-layout_version' => $template_data['version'],
								);
							}
						} else {
							$args['label'] = __( 'Install Template', 'ditty-ticker' );
							$args['input_class'] = 'ditty-default-layout-install';
							$args['icon_after'] = 'fas fa-download';
							$args['atts'] = array(
								'data-layout_template' => $template_slug,
								'data-layout_version' => $template_data['version'],
							);
						}
						$html .= ditty_field( $args );
					$html .= '</li>';
				}
				$html .= '</ul>';
			$html .= '</div>';
		}
		
		return $html;
	}
}