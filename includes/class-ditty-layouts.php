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

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
		add_action( 'admin_init', array( $this, 'edit_page_redirects' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ), 10, 5 );
		add_filter( 'get_edit_post_link', array( $this, 'modify_edit_post_link' ), 10, 3 );
		add_filter( 'post_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );
		add_action( 'wp_delete_post', array( $this, 'after_delete' ), 10, 2 );

		add_action( 'wp_ajax_ditty_install_layout', array( $this, 'install_layout' ) );
	}

	public function install_default( $layout_template = false, $layout_version = false ) {
		// Keep function to not cause fatal errors from other plugins
	}
	public function install_layout() {
		// Keep function to not cause fatal errors from other plugins
	}

	/**
	 * Add to the admin body class
	 *
	 * @access public
	 * @since  3.1
	 */
	public function add_admin_body_class( $classes ) {
		if ( ditty_layout_editing() ) {
			$classes .= ' ditty-page';
      $classes .= ' ditty-page--layout';
		}
		return $classes;
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
	 * Modify the edit post link
	 *
	 * @access public
	 * @since  3.1
	 */
	public function modify_edit_post_link( $link, $post_id, $text ) {
		if ( 'ditty_layout' == get_post_type( $post_id ) ) {
			return add_query_arg( ['page' => 'ditty_layout', 'id' => $post_id], admin_url( 'admin.php' ) );
		}
		return $link;
	}
	
	/**
	 * Redirect Ditty edit pages to custom screens
	 * @access  public
	 *
	 * @since   3.1.19
	 */
	public function edit_page_redirects() {
    ditty_edit_post_type_redirects( 'ditty_layout' );
	}

	/**
	 * Add custom Ditty pages
	 * @access  public
	 *
	 * @since   3.1.19
	 */
	public function add_admin_pages() {
		add_submenu_page(
			'edit.php?post_type=ditty',
			esc_html__( 'Layout', 'ditty-news-ticker' ),
			esc_html__( 'Layout', 'ditty-news-ticker' ),
			'edit_ditty_layouts',
			'ditty_layout',
			array( $this, 'page_display' )
		);
		
		add_submenu_page(
			'edit.php?post_type=ditty',
			esc_html__( 'New Layout', 'ditty-news-ticker' ),
			esc_html__( 'New Layout', 'ditty-news-ticker' ),
			'edit_ditty_layouts',
			'ditty_layout-new',
			array( $this, 'page_display' )
		);
	}

	/**
	 * Render the custom new Display page
	 * @access  public
	 *
	 * @since   3.1
	 */
	public function page_display() {	
		?>
		<div id="ditty-layout-editor__wrapper" class="ditty-adminPage"></div>
		<?php
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
			$styles .= '.ditty .ditty-layout--' .$layout_id . '{';
				$styles .= html_entity_decode( $css );
			$styles .= '}';
		} else {
			$styles .= '.ditty .ditty-layout--' . $layout_id . '{';
				$styles .= html_entity_decode( $css );
			$styles .= '}';
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
	 * @since   3.1.15
	 * @param   array    $options.
	 */
	public function select_field_options( $placeholder = false ) {
		$options = array();
		if ( $placeholder ) {
			$options[''] = $placeholder;
		}

		$query_args = array(
			'posts_per_page' 	=> -1,
			'post_type' 			=> 'ditty_layout',
			'post_status'			=> 'any',
			'orderby'					=> 'title',
			'order'						=> 'ASC',
		);
		if ( $layouts = get_posts( $query_args ) ) {
			foreach ( $layouts as $layout_post ) {
				$title = $layout_post->post_title;
				if ( 'publish' != $layout_post->post_status ) {
					$title .= " ({$layout_post->post_status})";
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
	public function tag_attribute_default_settings ( $tag, $default = false, $options = false ) {
		switch( $tag ) {
			case 'after':
				return [
					'type' => "text",
					'id' =>  "after",
					'help' =>  __(
						"Add text after the rendered tag.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'before':
				return [
					'type' => "text",
      		'id' =>  "before",
					'help' =>  __(
						"Add text before the rendered tag.",
						"ditty-news-ticker"
					),
      		'std' => $default ? $default : '',
				];
			case 'class':
				return [
					'type' => "text",
					'id' =>  "class",
					'help' =>  __(
						"Add a custom class name to the element.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'content_display':
				return [
					'type' => "select",
					'id' =>  "content_display",
					'options' => $options ? $options : [
						"full",
						"excerpt",
					],
					'help' =>  __(
						"Choose how to display the content.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'excerpt_length':
				return [
					'type' => "number",
					'id' =>  "excerpt_length",
					'min' => 0,
					'help' =>  __(
						"Set the length of the excerpt.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'fit':
				return [
					'type' => "select",
					'id' =>  "fit",
					'options' => $options ? $options : [
						"none",
						"fill",
						"contain",
						"cover",
						"scale-down",
					],
					'help' =>  __(
						"Set the object fit property of the image.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'height':
				return [
					'type' => "unit",
					'id' =>  "height",
					'help' =>  __(
						"Set the height of the image.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'link':
				return [
					'type' => "select",
					'id' =>  "link",
					'help' =>  __(
						"Add a link to the element.",
						"ditty-news-ticker"
					),
					'options' => $options ? $options : [
						"none",
					],
					'std' => $default ? $default : '',
				];
			case 'link_after':
				return [
					'type' => "text",
					'id' =>  "link_after",
					'help' =>  __(
						"Add text after the link.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'link_before':
				return [
					'type' => "text",
					'id' =>  "link_before",
					'help' =>  __(
						"Add text before the link.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'link_rel':
				return [
					'type' => "text",
					'id' =>  "link_rel",
					'help' =>  __(
						"Add a rel attribute to the link.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'link_target':
				return [
					'type' => "select",
					'id' =>  "link_target",
					'options' => $options ? $options : [
						'_blank',
						'_self',
						'_parent',
						'_top',
					],
					'help' =>  __(
						"Set the target attribute for the link.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '_self',
				];
			case 'more':
				return [
					'type' => "text",
					'id' =>  "more",
					'help' =>  __(
						'Set the "more" text after the content/excerpt.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'more_after':
				return [
					'type' => "text",
					'id' =>  "more_after",
					'help' =>  __(
						'Add text after the "more" element.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'more_before':
				return [
					'type' => "text",
					'id' =>  "more_before",
					'help' =>  __(
						'Add text before the "more" element.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'more_link':
				return [
					'type' => "select",
					'id' =>  "more_link",
					'help' =>  __(
						'Add a link to the "more" element.',
						"ditty-news-ticker"
					),
					'options' => $options ? $options : [
						"none",
					],
					'std' => $default ? $default : '',
				];
			case 'more_link_after':
				return [
					'type' => "text",
					'id' =>  "more_link_after",
					'help' =>  __(
						'Add text after the "more" link.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'more_link_before':
				return [
					'type' => "text",
					'id' =>  "more_link_before",
					'help' =>  __(
						'Add text before the "more" link.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'more_link_rel':
				return [
					'type' => "text",
					'id' =>  "more_link_rel",
					'help' =>  __(
						'Add a rel attribute to the "more" link.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'more_link_target':
				return [
					'type' => "select",
					'id' =>  "more_link_target",
					'options' => $options ? $options : [
						'_blank',
						'_self',
						'_parent',
						'_top',
					],
					'help' =>  __(
						'Set the target attribute for the "more" link.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '_self',
				];
			case 'separator':
				return [
					'type' => "text",
					'id' =>  "separator",
					'help' =>  __(
						'What would you like to use to separate the data.',
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'width':
				return [
					'type' => "unit",
					'id' =>  "width",
					'help' =>  __(
						"Set the width of the image.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'wpautop':
				return [
					'type' => "checkbox",
					'id' =>  "wpautop",
					'help' =>  __(
						"Automatically add paragraph tags to the text.",
						"ditty-news-ticker"
					),
					'std' => $default ? $default : '',
				];
			case 'wrapper':
				return [
					'type' => "select",
					'id' =>  "wrapper",
					'options' => $options ? $options : [
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
			default:
				return $default;
		}
	}

	/**
	 * List the layout variation defaults
	 *
	 * @access public
	 * @since  3.1.15
	 * @param  html
	 */
	public function variation_defaults() {
		$html = '';
		$item_types = ditty_item_types();
		$variation_types = ditty_layout_variation_types();
		$settings = get_ditty_settings( 'variation_defaults' );
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

	/**
	 * Possibly modify variation defaults if a layout is deleted
	 *
	 * @access  public
	 * @since   3.1.15
	 */
	public function after_delete( $postid, $force_delete ) {
		if ( ! $force_delete ) {
			return false;
		}
		$variation_defaults = get_ditty_settings( 'variation_defaults' );
		$sanitized_variation_defaults = [];
		if ( is_array( $variation_defaults ) && count( $variation_defaults ) > 0 ) {
			foreach ( $variation_defaults as $item_type => $defaults ) {
				$sanitized_defaults = [];
				if ( is_array( $defaults ) && count( $defaults ) > 0 ) {
					foreach ( $defaults as $variation => $layout_id ) {
						if ( ! $layout_id || $postid == $layout_id ) {
							continue;
						}
						$sanitized_defaults[$variation] = $layout_id;
					}
				}
				$sanitized_variation_defaults[$item_type] = $sanitized_defaults;
			}
		}
		update_ditty_settings( 'variation_defaults', $sanitized_variation_defaults );
	}

	/**
	 * Save a layout
	 *
	 * @access  public
	 * @since   3.1.9
	 * @param   array
	 */
	public function save( $data ) {	
		$userId = isset( $data['userId'] ) ? $data['userId'] : 0;
		$title = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : false;
		$description = isset( $data['description'] ) ? $data['description'] : false;
		$status = isset( $data['status'] ) ? esc_attr( $data['status'] ) : false;
		$editor_item = isset( $data['editorItem'] ) ? $data['editorItem'] : false;
		$editor_settings = isset( $data['editorSettings'] ) ? $data['editorSettings'] : false;

		$layout = isset( $data['layout'] ) ? $data['layout'] : array();
		$layout_id = isset( $layout['id'] ) ? $layout['id'] : 'ditty_layout-new';
		$layout_html = isset( $layout['html'] ) ? $layout['html'] : false;
		$layout_css = isset( $layout['css'] ) ? $layout['css'] : false;

		$author = false;
		if ( 'ditty_layout-new' != $layout_id ) {
			$layout_post = get_post( $layout_id );
			$layout_author = $layout_post->post_author;
			$author = 0 == $layout_author ? $userId : false;
		}

		$updates = array();
		$errors = array();

		if ( $layout_id && 'ditty_layout-new' != $layout_id ) {
			if ( $title || $status || $author ) {
				$postarr = array(
					'ID' => $layout_id,
				);
				if ( $title ) {
					$postarr['post_title'] = $title;
				}
				if ( $status ) {
					$postarr['post_status'] = $status;
				}
				if ( $author ) {
					$postarr['post_author'] = $author;
				}
				if ( wp_update_post( $postarr ) ) {
					if ( $title ) {
						$updates['title'] = $title;
					}
					if ( $status ) {
						$updates['status'] = $status;
					}
					if ( $author ) {
						$updates['author'] = $author;
					}
				} else {
					if ( $title ) {
						$errors['title'] = $title;
					}
					if ( $status ) {
						$errors['status'] = $status;
					}
					if ( $author ) {
						$errors['author'] = $author;
					}
				}
			}
		} else {
			$postarr = array(
				'post_type'		=> 'ditty_layout',
				'post_title'	=> $title,
				'post_status'	=> $status ? $status : 'publish',
				'post_author' => $userId,
			);
			$layout_id = wp_insert_post( $postarr );
			$updates['new'] = $layout_id;
			$updates['title'] = $title;
		}

		// Update the layout description
		if ( $description ) {
			$sanitized_description = wp_kses_post( $description );
			if ( update_post_meta( $layout_id, '_ditty_layout_description', $sanitized_description ) ) {
				$updates['description'] = $sanitized_description;
			} else {
				$errors['description'] = $sanitized_description;
			}		
		}
		
		// Update the layout type
		if ( $layout_html ) {
			$html = wp_kses_post( stripslashes( $layout_html ) );
			if ( update_post_meta( $layout_id, '_ditty_layout_html', $html ) ) {
				$updates['html'] = $html;
			} else {
				$errors['html'] = $html;
			}		
		}

		// Update the layout settings
		if ( $layout_css ) {
			$css = wp_kses_post( $layout_css );
			if ( update_post_meta( $layout_id, '_ditty_layout_css', $css ) ) {
				$updates['css'] = $css;
			} else {
				$errors['css'] = $css;
			}	
		}

		// Update the editor item
		if ( $editor_item ) {
			$sanitized_editor_item = Ditty()->singles->sanitize_item_data( $editor_item );
			if ( update_post_meta( $layout_id, '_ditty_editor_item', $sanitized_editor_item ) ) {
				$updates['editorItem'] = $sanitized_editor_item;
			} else {
				$errors['editorItem'] = $sanitized_editor_item;
			}	
		}

		// Update the editor settings
		if ( $editor_settings ) {
			$sanitized_editor_settings = ditty_sanitize_settings( $editor_settings );
			if ( update_post_meta( $layout_id, '_ditty_editor_settings', $sanitized_editor_settings ) ) {
				$updates['editorSettings'] = $sanitized_editor_settings;
			} else {
				$errors['editorSettings'] = $sanitized_editor_settings;
			}	
		}

		// Clear display items cache so layouts update right away
		Ditty()->singles->delete_items_cache();

		return array(
			'updates' => $updates,
			'errors'	=> $errors,
		);
	}
}