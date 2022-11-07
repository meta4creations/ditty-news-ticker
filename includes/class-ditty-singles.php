<?php

/**
 * Ditty Singles Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Singles
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

class Ditty_Singles {
	
	/**
	 * Types
	 *
	 * @since 3.0
	 */
	public $types = array();

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
	
		// WP metabox hooks
		if ( is_ditty_dev() ) {
			add_filter( 'get_edit_post_link', array( $this, 'modify_edit_post_link' ), 10, 3 );
			add_action( 'admin_menu', array( $this, 'add_ditty_page' ) );
		} else {
			add_action( 'edit_form_top', array( $this, 'edit_preview' ) );
		}

		// General hooks
		add_filter( 'post_row_actions', array( $this, 'modify_list_row_actions' ), 10, 2 );
		add_action( 'mtphr_post_duplicator_created', array( $this, 'after_duplicate_post' ), 10, 3 );	
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
		
		// Editor elements
		add_action( 'ditty_editor_tabs', array( $this, 'editor_tab' ), 100, 2 );
		add_action( 'ditty_editor_panels', array( $this, 'editor_settings_panel' ), 10, 2 );
		
		// Ditty post modifications
		add_action( 'admin_menu', array( $this, 'remove_metaboxes' ) );
		add_filter( 'get_user_option_screen_layout_ditty', array( $this, 'force_post_layout' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'remove_screen_options' ), 10, 2 );

		// Shortcodes
		add_shortcode( 'ditty', array( $this, 'do_shortcode' ) );
		
		// Ajax
		add_action( 'wp_ajax_ditty_init', array( $this, 'init_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_init', array( $this, 'init_ajax' ) );
		add_action( 'wp_ajax_ditty_live_updates', array( $this, 'live_updates_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_live_updates', array( $this, 'live_updates_ajax' ) );
		
		// Editor Ajax
		add_action( 'wp_ajax_ditty_editor_settings_update', array( $this, 'editor_settings_update_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_settings_update', array( $this, 'editor_settings_update_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_save', array( $this, 'editor_save_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_save', array( $this, 'editor_save_ajax' ) );
	}
	
	/**
	 * Add to the editor tabs
	 *
	 * @access  public
	 * @since   3.0
	 * @param   $html
	 */
	public function editor_tab( $tabs, $ditty_id ) {
		if ( ! current_user_can( 'edit_dittys' ) ) {
			return false;
		}
		$tabs['settings'] = array(
			'icon' 		=> 'fas fa-cog',
			'label'		=> __( 'Settings', 'ditty-news-ticker' ),
		);
		return $tabs;
	}
	
	/**
	 * Add the editor item types panel
	 *
	 * @access public
	 * @since  3.0.11
	 */
	public function editor_settings_panel( $panels, $ditty_id ) {
		if ( ! current_user_can( 'edit_dittys' ) ) {
			return false;
		}	
		ob_start();
		?>
		<form class="ditty-editor-options ditty-metabox" data-ditty_id="<?php echo $ditty_id; ?>">
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__body">
					<?php
					$initialized = get_post_meta( $ditty_id, '_ditty_init', true );
					$title = ( ! $initialized ) ? sprintf( __( 'Ditty %d', 'ditty-news-ticker' ), $ditty_id ) : get_the_title( $ditty_id );
					$status = get_post_status( $ditty_id );
					if ( ! $initialized ) {
						$status = 'publish';
					}
					$shortcode = "[ditty id={$ditty_id}]";

					$settings = ditty_single_settings( $ditty_id );

					$fields = array(
						'title' => array(
							'type'				=> 'text',
							'id'					=> 'title',
							'name'				=> __( 'Title', 'ditty-news-ticker' ),
							'std'					=> $title,
							'placeholder' => ditty_strings( 'add_title' ),
						),
						'shortcode' => array(
							'type'	=> 'text',
							'id'		=> 'shortcode',
							'name'	=> __( 'Shortcode', 'ditty-news-ticker' ),
							'std'		=> $shortcode,
						),
						'status' => array(
							'type'	=> 'radio',
							'id'		=> 'status',
							'name'	=> __( 'Status', 'ditty-news-ticker' ),
							'options' => [
								'publish' => __( 'Active', 'ditty-news-ticker' ),
								'draft' => __( 'Disabled', 'ditty-news-ticker' ),
							],
							'inline' => true,
							'std'		=> ( 'publish' != $status ) ? 'draft' : $status,
						),
						'ajax_loading' => array(
							'type'	=> 'radio',
							'id'		=> 'ajax_loading',
							'name'	=> __( 'Ajax Loading', 'ditty-news-ticker' ),
							'options' => [
								'no' 		=> __( 'No', 'ditty-news-ticker' ),
								'yes' 	=> __( 'Yes', 'ditty-news-ticker' ),
							],
							'inline' 	=> true,
							'std'			=> isset( $settings['ajax_loading'] ) ? $settings['ajax_loading'] : 'no',
						),
						'live_updates' => array(
							'type'	=> 'radio',
							'id'		=> 'live_updates',
							'name'	=> __( 'Live Updates', 'ditty-news-ticker' ),
							'options' => [
								'no' 		=> __( 'No', 'ditty-news-ticker' ),
								'yes' 	=> __( 'Yes', 'ditty-news-ticker' ),
							],
							'inline' 	=> true,
							'std'			=> isset( $settings['live_updates'] ) ? $settings['live_updates'] : 'no',
						),
						'preview_settings' => array(
							'type' 							=> 'group',
							'id'								=> 'preview_settings',
							'collapsible'				=> true,
							'default_state'			=> 'expanded',
							'multiple_fields'		=> true,
							'name' 	=> __( 'Preview Settings', 'ditty-news-ticker' ),
							'help' 	=> __( 'Configure the editor preview style.', 'ditty-news-ticker' ),
							'fields' => array(
								'previewBg' => array(
									'type'	=> 'color',
									'id'		=> 'previewBg',
									'name'	=> __( 'Preview Background Color', 'ditty-news-ticker' ),
									'help'	=> __( 'Set a custom background color for the preview area while editing.', 'ditty-news-ticker' ),
									'std'		=> isset( $settings['previewBg'] ) ? $settings['previewBg'] : false,
								),
								'previewPadding' => array(
									'type'	=> 'spacing',
									'id'		=> 'previewPadding',
									'name'	=> __( 'Preview Padding', 'ditty-news-ticker' ),
									'std'		=> isset( $settings['previewPadding'] ) ? $settings['previewPadding'] : false,
								),
							),
						),
					);
					ditty_fields( $fields );
					?>
				</div>
			</div>
		</form>
		<?php
		$panels['settings'] = ob_get_clean();
		return $panels;
	}
	
	/**
	 * Add the edit page preview
	 * @access  public
	 * @since   3.0.12
	 */
	public function edit_preview() {
		global $post;
		if ( 'ditty' != $post->post_type ) {
			return false;
		}
		$initialized = get_post_meta( $post->ID, '_ditty_init', true );
		if ( ! $initialized && ditty_wizard_enabled() ) {
			ditty_wizard( $post );
		} else {
			$settings = get_post_meta( $post->ID, '_ditty_settings', true );
			$title = ( ! $initialized ) ? sprintf( __( 'Ditty %d', 'ditty-news-ticker' ), $post->ID ) : $post->post_title;
			$style = '';
			if ( is_array( $settings ) && isset( $settings['previewBg'] ) ) {
				$style .= "background-color:{$settings['previewBg']};";
			}
			if ( is_array( $settings ) && isset( $settings['previewPadding'] ) && is_array( $settings['previewPadding'] ) ) {
				if ( isset( $settings['previewPadding']['paddingTop'] ) ) {
					$style .= "padding-top:{$settings['previewPadding']['paddingTop']};";
				}
				if ( isset( $settings['previewPadding']['paddingBottom'] ) ) {
					$style .= "padding-bottom:{$settings['previewPadding']['paddingBottom']};";
				}
				if ( isset( $settings['previewPadding']['paddingLeft'] ) ) {
					$style .= "padding-left:{$settings['previewPadding']['paddingLeft']};";
				}
				if ( isset( $settings['previewPadding']['paddingRight'] ) ) {
					$style .= "padding-right:{$settings['previewPadding']['paddingRight']};";
				}
			}
			?>
			<div id="ditty-page" class="wrap">
				<div id="ditty-page__header">
					<h2><span class="ditty-post__title"><?php echo $title; ?></span></h2>
				</div>		
				<div id="ditty-page__content">
					<div id="ditty-editor">
						<div id="ditty-editor__settings"></div>
						<div id="ditty-editor__preview" style="<?php echo $style; ?>">
							<?php
							$display = get_post_meta( $post->ID, '_ditty_display', true );
							if ( ! $display || ! ditty_display_exists( $display ) ) {
								$display = ditty_default_display( $post->ID );
							}
							$atts = array(
								'id' 					=> $post->ID,
								'display' 		=> $display,
								'uniqid'			=> 'ditty-preview-' . $post->ID,
								'class'				=> 'ditty-preview',
								'show_editor'	=> 1,
								//'load_type'		=> '',
							);
							echo ditty_render( $atts );
							?>
							<div id="ditty-preview__overlay" class="ditty-updating-overlay">
								<div class="ditty-updating-overlay__inner">
									<i class="fas fa-sync-alt fa-spin"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.wrap -->
			<div id="dittyEditor"></div>
			<?php
		}
	}

	public function add_ditty_page() {
		add_menu_page(
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			esc_html__( 'Ditty', 'ditty-news-ticker' ),
			'edit_dittys',
			'ditty',
			array( $this, 'page_display' )
		);
	}

	public function page_display() {
		$ditty_id = ditty_editing();
		if ( ! $ditty_id ) {
			return false;
		}
		$item_types = ditty_item_types();

		$ditty = get_post( $ditty_id );
		$initialized = get_post_meta( $ditty_id, '_ditty_init', true );
		
		$settings = get_post_meta( $ditty_id, '_ditty_settings', true );
		
		$title = ( ! $initialized ) ? sprintf( __( 'Ditty %d', 'ditty-news-ticker' ), $ditty->ID ) : $ditty->post_title;
		$items_meta = ditty_items_meta( $ditty_id );
		
		// Do not pass serialized data
		$unserialized_items = array();
		if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {
			foreach ( $items_meta as $i => $item_meta ) {
				
				// Possibly render items if they aren't set to render via javascript
				$item_type_data =  isset( $item_types[$item_meta->item_type] ) ? $item_types[$item_meta->item_type] : array();
				if ( ! isset( $item_type_data['ditty_version'] ) || version_compare( $item_type_data['ditty_version'], DITTY_VERSION, '<' ) ) {
					$rendered_items = [];
					$prepared_items = ditty_prepare_display_items( $item_meta );
					if ( is_array( $prepared_items ) && count( $prepared_items ) > 0 ) {
						foreach ( $prepared_items as $i => $prepared_meta ) {
							$display_item = new Ditty_Display_Item( $prepared_meta );
							if ( $data = $display_item->compile_data( 'javascript' ) ) {
								$rendered_items[] = $data;
							}
						}
					}
					$item_meta->rendered_items = $rendered_items;
				}

				$item_meta->layout_value = maybe_unserialize( $item_meta->layout_value );
				$unserialized_items[] = $item_meta;
			}
		}

		

		$display = get_post_meta( $ditty->ID, '_ditty_display', true );
		if ( (! $display && ! is_array( $display ) ) || ! ditty_display_exists( $display ) ) {
			$display = ditty_default_display( $ditty->ID );
		}

		$display_items = ditty_display_items( $ditty->ID, 'force' );
		if ( ! is_array( $display_items ) ) {
			$display_items = array();
		}
		// $atts = array(
		// 	'id' 					=> $ditty->ID,
		// 	'display' 		=> $display,
		// 	'uniqid'			=> 'ditty-preview-' . $ditty->ID,
		// 	'class'				=> 'ditty-preview',
		// );
		// $ditty_render = ditty_render( $atts );
		//echo '<pre>';print_r($unserialized_items);echo '</pre>';
		$atts = array(
			'data-id' 						=> $ditty_id,
			'data-title' 					=> $title,
			'data-settings' 			=> json_encode( $settings ),
			'data-items' 					=> json_encode( $unserialized_items ),
			'data-displayitems' 	=> json_encode( $display_items ),
			'data-display' 				=> is_array( $display ) ? false : $display,
			'data-displayobject' 	=> is_array( $display ) ? json_encode( $display ) : false,
			//'data-render'		=> htmlentities( $ditty_render ),
		);
		//echo '<pre>';print_r($atts);echo '</pre>';
		?>
		<div id="ditty-editor__wrapper" <?php echo ditty_attr_to_html( $atts ); ?>></div>
		<?php
	}

	/**
	 * Modify the edit post link
	 *
	 * @access public
	 * @since  3.1
	 */
	public function modify_edit_post_link( $link, $post_id, $text ) {
		if ( 'ditty' == get_post_type( $post_id ) ) {
			return add_query_arg( ['page' => 'ditty', 'id' => $post_id, 'dittyDev' => 1 ], admin_url( 'admin.php' ) );
		}
		return $link;
	}
	
	/**
	 * Add to the admin body class
	 *
	 * @access public
	 * @since  3.0.13
	 */
	public function add_admin_body_class( $classes ) {
		if ( ditty_wizard_enabled() ) {
			$classes .= ' ditty-wizard-enabled ';
		}
		return $classes;
	}

	/**
	 * Duplicate Ditty items on Post Duplicator duplication
	 * 
	 * @since  3.0.13
	 * @return void
	 */
	public function after_duplicate_post( $original_id, $duplicate_id, $settings ) {
		if ( 'ditty' == get_post_type( $original_id ) && 'ditty' == get_post_type( $duplicate_id ) ) {
			
			// Duplicate and add original Ditty items
			$all_meta = Ditty()->db_items->get_items( $original_id );
			if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
				foreach ( $all_meta as $i => $meta ) {
					unset( $meta->item_id );
					unset( $meta->date_created );
					unset( $meta->date_modified );
					$meta->ditty_id = $duplicate_id;
					Ditty()->db_items->insert( $meta, 'item' );
				} 
			}
		}
	}
	
	/**
	 * Add the post ID to the list row actions
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function modify_list_row_actions( $actions, $post ) {
		if ( $post->post_type == 'ditty' ) {
			//$id_string = sprintf( __( 'ID: %d', 'ditty-news-ticker' ), $post->ID );
			$id_array = array(
				'id' => sprintf( __( 'ID: %d', 'ditty-news-ticker' ), $post->ID ),
			);
			$actions = array_merge( $id_array, $actions );
		}
		return $actions;
	}
	
	/**
	 * Remove the submit div
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function remove_metaboxes() {
		remove_meta_box( 'submitdiv', 'ditty', 'side' );
		remove_meta_box( 'authordiv', 'ditty', 'side' );
	}
	
	/**
	 * Force a single column layout
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function force_post_layout() {
		return '1';
	}
	
	/**
	 * Remove screen options
	 * 
	 * @since  3.0
	 * @return void
	 */
	public function remove_screen_options( $show_screen, $hook ) {
		if ( 'ditty' === $hook->post_type && 'post' === $hook->base ) {
			return false;
		} 
		return $show_screen;
	}

	/**
	 * Display the Ditty via shortcode
	 *
	 * @since    3.0
	 * @access   public
	 * @var      html
	 */
	public function do_shortcode( $atts ) {
		if ( ! is_admin() ) {
			return ditty_render( $atts );
		}
	}
	
	/**
	 * Setup and parse the render atts
	 *
	 * @since    3.2
	 * @access   public
	 * @var      html
	 */
	public function parse_render_atts( $atts ) {
		$defaults = array(
			'id' 								=> '',
			'display' 					=> '',
			'display_settings' 	=> '',
			'layout_settings' 	=> '',
			'uniqid' 						=> '',
			'class' 						=> '',
			'el_id'							=> '',
			'show_editor' 			=> 0,
		);
		$args = shortcode_atts( $defaults, $atts );
		
		// Check for WPML language posts
		$args['id'] = function_exists('icl_object_id') ? icl_object_id( $args['id'], 'ditty', true ) : $args['id'];
	
		// Make sure the ditty exists & is published
		if ( ! ditty_exists( intval( $args['id'] ) ) ) {
			return false;
		}
		if ( ! is_admin() && 'publish' !== get_post_status( intval( $args['id'] ) ) ) {
			return false;
		}
	
		if ( '' == $args['uniqid'] ) {
			$args['uniqid'] = uniqid( 'ditty-' );
		}
	
		$class = 'ditty ditty--pre';
		if ( '' != $args['class'] ) {
			$class .= ' ' . esc_attr( $args['class'] );
		}
		
		$ditty_settings 		= get_post_meta( $args['id'], '_ditty_settings', true );
		$ajax_load 					= ( isset( $ditty_settings['ajax_loading'] ) && 'yes' == $ditty_settings['ajax_loading'] ) ? '1' : 0;
		$live_updates 			= ( isset( $ditty_settings['live_updates'] ) && 'yes' == $ditty_settings['live_updates'] ) ? '1' : 0;
		$items 							= ditty_display_items( $args['id'], 'force' );
		$display_id 				= ( $args['display'] != '' ) ? $args['display'] : get_post_meta( $args['id'], '_ditty_display', true );
		$display_type 			= get_post_meta( $display_id, '_ditty_display_type', true );
		$display_settings 	= get_post_meta( $display_id, '_ditty_display_settings', true );
		$title_settings			= Ditty()->displays->title_settings( $display_settings );
	
		$html_atts = array(
			'id'										=> ( '' != $args['el_id'] ) ? sanitize_title( $args['el_id'] ) : false,
			'class' 								=> $class,
			'data-id' 							=> $args['id'],
			'data-uniqid' 					=> $args['uniqid'],
			'data-display' 					=> $display_id,
			'data-type'							=> $display_type,
			'data-settings' 				=> htmlspecialchars( json_encode( $display_settings ) ),
			//'data-items' 						=> htmlspecialchars( json_encode( $items ) ),
			'data-title'						=> $title_settings['titleDisplay'],
			'data-title_position' 	=> $title_settings['titleElementPosition'],
			//'data-display_settings' => ( '' != $args['display_settings'] ) ? $args['display_settings'] : false,
			//'data-layout_settings' 	=> ( '' != $args['layout_settings'] ) ? $args['layout_settings'] : false,
			//'data-show_editor' 			=> ( 0 != intval( $args['show_editor'] ) ) ? '1' : false,
			'data-ajax_load' 				=> $ajax_load,
			'data-live_updates' 		=> $live_updates,
		);
	
		// Add scripts
		ditty_add_scripts( $args['id'], $args['display']);
	
		return array(
			'ditty' => $args['id'],
			'ditty_settings' => $ditty_settings,
			'display' => $display_id,
			'display_type' => $display_type,
			'display_settings' => $display_settings,
			'title_settings' => $title_settings,
			'items' => $items,
			'html_atts' => $html_atts,
		);
	}
	
	/**
	 * Return an array of Dittys for select fields
	 *
	 * @access  public
	 * @since   3.0
	 * @param   array    $options.
	 */
	public function select_field_options() {	
		$options = array();
		$args = array(
			'posts_per_page' => -1,
			'orderby' 		=> 'post_title',
			'order' 			=> 'ASC',
			'post_type' 	=> 'ditty',
		);
		$posts = get_posts( $args );
		if ( is_array( $posts ) && count( $posts ) > 0 ) {
			foreach ( $posts as $i => $post ) {
				$options[$post->ID] = $post->post_title;
			}
		}	
		return $options;
	}
	
	/**
	 * Parse custom display settings
	 *
	 * @access public
	 * @since  3.0
	 */
	public function parse_custom_display_settings( $args, $display_settings ) {
		if ( '' != $display_settings && 'false' != $display_settings ) {
			parse_str( html_entity_decode( $display_settings ), $custom_display_settings );
			if ( is_array( $custom_display_settings ) && count( $custom_display_settings ) > 0 ) {
				foreach ( $custom_display_settings as $key => $value ) {
					$parts = explode( '|', $value );
					if ( is_array( $parts ) && count( $parts ) > 0 ) {
						foreach ( $parts as $subvalue ) {
							$subparts = explode( ':', $subvalue );
							if ( count( $subparts ) > 1 ) {
								if ( ! isset( $args[$key] ) ) {
									$args[$key] = array();
								}
								if ( is_array( $args[$key] ) ) {
									$args[$key][$subparts[0]] = $subparts[1];
								}
							} else {
								$args[$key] = $subparts[0];
							}
						}
					}
				}
			}
		}
		return $args;
	}
	
	/**
	 * Return data for a Ditty to load via ajax
	 *
	 * @access public
	 * @since  3.0.12
	 */
	public function init_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$id_ajax 								= isset( $_POST['id'] ) 							? intval( $_POST['id'] ) 									: false;
		$uniqid_ajax 						= isset( $_POST['uniqid'] ) 					? esc_attr( $_POST['uniqid'] ) 						: false;
		$display_ajax 					= isset( $_POST['display'] ) 					? esc_attr( $_POST['display'] ) 					: false;
		$display_settings_ajax 	= isset( $_POST['display_settings'] ) ? esc_attr( $_POST['display_settings'] ) 	: false;
		$layout_settings_ajax 	= isset( $_POST['layout_settings'] ) 	? esc_attr( $_POST['layout_settings'] ) 	: false;
		$editor_ajax 						= isset( $_POST['editor'] )						? intval( $_POST['editor'] ) 							: false;

		// Get the display attributes
		if ( ! $display_ajax ) {
			$display_ajax = get_post_meta( $id_ajax, '_ditty_display', true );
		}
		if ( ! $display_ajax || '' == $display_ajax || ! ditty_display_exists( $display_ajax ) ) {
			$display_ajax = ditty_default_display( $id_ajax );
		}
		
		$display = new Ditty_Display( $display_ajax );

		// Setup the ditty values
		$status = get_post_status( $id_ajax );
		$args 									= $display->get_values();
		$args['id'] 						= $id_ajax;
		$args['uniqid'] 				= $uniqid_ajax;
		$args['title'] 					= get_the_title( $id_ajax );
		$args['status'] 				= $status;
		$args['display'] 				= $display->get_display_id();
		$args['showEditor'] 		= $editor_ajax;

		$items = ditty_display_items( $id_ajax, 'force', $layout_settings_ajax );
		if ( ! is_array( $items ) ) {
			$items = array();
		}
		$args['items'] = $items;
		$args = $this->parse_custom_display_settings( $args, $display_settings_ajax );

		do_action( 'ditty_init', $id_ajax );
		
		$data = array(
			'display_type' 	=> $display->get_display_type(),
			'args' 					=> $args,
		);
		wp_send_json( $data );
	}
	
	/**
	 * Return data for a Ditty to load via ajax
	 *
	 * @access public
	 * @since  3.0.12
	 */
	public function init( $atts ) {
		if ( ! $atts['data-id'] ) {
			return false;
		}

		$ditty_id 				= $atts['data-id'];
		$uniqid 					= isset( $atts['data-uniqid'] ) 					? $atts['data-uniqid'] 						: false;
		$display_id 			= isset( $atts['data-display'] ) 					? $atts['data-display'] 					: false;
		$display_settings = isset( $atts['data-display_settings'] )	? $atts['data-display_settings']	: false;
		$layout_settings 	= isset( $atts['data-layout_settings'] ) 	? $atts['data-layout_settings'] 	: false;
		$show_editor 			= isset( $atts['data-show_editor'] ) 			? $atts['data-show_editor'] 			: false;
	
		// Get the display attributes
		if ( ! $display_id ) {
			$display_id = get_post_meta( $ditty_id, '_ditty_display', true );
		}
		if ( ! $display_id || '' == $display_id || ! ditty_display_exists( $display_id ) ) {
			$display_id = ditty_default_display( $ditty_id );
		}
		$display = new Ditty_Display( $display_id );
		$display_type = $display->get_display_type();
	
		// Setup the ditty values
		$status = get_post_status( $ditty_id );
		$args = $display->get_values();
		
		$args['id'] 				= $ditty_id;
		$args['uniqid'] 		= $uniqid;
		$args['title'] 			= get_the_title( $ditty_id );
		$args['status'] 		= $status;
		$args['display'] 		= $display->get_display_id();
		$args['showEditor'] = $show_editor;

		$items = ditty_display_items( $ditty_id, 'force', $layout_settings );
		if ( ! is_array( $items ) ) {
			$items = array();
		}
		$args['items'] = $items;
		$args = $this->parse_custom_display_settings( $args, $display_settings );
	
		do_action( 'ditty_init', $ditty_id );
		
		?>
		$( 'div[data-uniqid="<?php echo esc_attr( $uniqid ); ?>"]' ).ditty_<?php echo esc_attr( $display_type ); ?>(<?php echo json_encode( $args ); ?>);
		<?php
	}
	
	/**
	 * Return live updates
	 *
	 * @access public
	 * @since  3.0.11
	 */
	public function live_updates_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$live_ids = isset( $_POST['live_ids'] ) ? $_POST['live_ids'] 	: false;
		if ( ! $live_ids ) {
			wp_die();
		}
		$updated_items = array();
		if ( is_array( $live_ids ) && count( $live_ids ) > 0 ) {
			foreach ( $live_ids as $ditty_id => $data ) {
				$layout_settings = isset( $data['layout_settings'] ) ? $data['layout_settings'] : false;
				$updated_items[$ditty_id] = ditty_display_items( $ditty_id, 'cache', $layout_settings );
			}
		}
		$data = array(
			'updated_items' => $updated_items,
		);	
		wp_send_json( $data );
	}
	
	/**
	 * Update the settings via ajax
	 *
	 * @since    3.0
	 */
	public function editor_settings_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$ditty_id_ajax 			= isset( $_POST['ditty_id'] ) 		? $_POST['ditty_id'] 			: false;
		$draft_values_ajax 	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 	: false;
		if ( ! current_user_can( 'edit_dittys' ) || ! $ditty_id_ajax ) {
			return false;
		}
		ditty_set_draft_values( $draft_values_ajax );
		unset( $_POST['action'] );
		unset( $_POST['draft_values'] );
		unset( $_POST['security'] );
		wp_send_json( $_POST );
	}
	
	/**
	 * Sanitize setting values before saving to the database
	 *
	 * @access public
	 * @since  3.0.13
	 */
	public function sanitize_settings( $settings ) {	
		$defaults = ditty_single_settings_defaults();
		
		$sanitized_settings = array();
		$sanitized_settings['ajax_loading'] = isset( $settings['ajax_loading'] ) ? esc_attr( $settings['ajax_loading'] )	: esc_attr( $defaults['ajax_loading'] ) ;
		$sanitized_settings['live_updates'] = isset( $settings['live_updates'] ) ? esc_attr( $settings['live_updates'] )	: esc_attr( $defaults['ajax_loading'] ) ;
		
		// Sanitize preview settings
		$sanitized_settings['previewBg'] = isset( $settings['previewBg'] ) ? sanitize_text_field( $settings['previewBg'] ) : sanitize_text_field( $defaults['previewBg'] );
		$sanitized_padding = array();
		if ( isset( $settings['previewPadding'] ) && is_array( $settings['previewPadding'] ) && count( $settings['previewPadding'] ) > 0 ) {
			foreach ( $settings['previewPadding'] as $key => $value ) {
				$sanitized_padding[$key] = sanitize_text_field( $value );
			}
		}
		$sanitized_settings['previewPadding'] = $sanitized_padding;
		return $sanitized_settings;
	}
	
	/**
	 * Sanitize item values before saving to the database
	 *
	 * @access public
	 * @since  3.0.17
	 */
	public function sanitize_item_data( $item_data ) {
		$item_type 		= isset( $item_data['item_type'] ) ? $item_data['item_type'] : false;
		$item_value 	= isset( $item_data['item_value'] ) ? $item_data['item_value'] : false;
		$layout_value = isset( $item_data['layout_value'] ) ? $item_data['layout_value'] : false;
		
		// Sanitize values by item type
		$sanitized_item_value = false;
		if ( $item_type && $item_value ) {
			if ( $item_type_object = ditty_item_type_object( $item_type ) ) {
				$sanitized_item_value = $item_type_object->sanitize_settings( $item_value );
			}
		}
		
		// Sanitize the layout values
		$sanitized_layout_value = false;
		if ( is_array( $layout_value ) && count( $layout_value ) > 0 ) {
			foreach ( $layout_value as $variation => $layout ) {
				$sanitized_layout_value[esc_attr( $variation )] = esc_attr( $layout );
			}
		}
		
		$sanitized_item = array();
		if ( isset( $item_data['ditty_id'] ) ) {
			$sanitized_item['ditty_id'] = intval( $item_data['ditty_id'] );
		}
		if ( isset( $item_data['item_id'] ) ) {
			$sanitized_item['item_id'] = esc_attr( $item_data['item_id'] );
		}
		if ( isset( $item_data['item_index'] ) ) {
			$sanitized_item['item_index'] = intval( $item_data['item_index'] );
		}
		if ( isset( $item_data['item_type'] ) ) {
			$sanitized_item['item_type'] = esc_attr( $item_data['item_type'] );
		}
		if ( isset( $item_data['item_value'] ) ) {
			$sanitized_item['item_value'] = maybe_serialize( $sanitized_item_value );
		}
		if ( isset( $item_data['layout_id'] ) ) {
			$sanitized_item['layout_id'] = esc_attr( $item_data['layout_id'] );
		}
		if ( isset( $item_data['layout_value'] ) ) {
			$sanitized_item['layout_value'] = maybe_serialize( $sanitized_layout_value );
		}
		if ( isset( $item_data['item_author'] ) ) {
			$sanitized_item['item_author'] = intval( $item_data['item_author'] );
		}
		return $sanitized_item;
	}
	
	/**
	 * Save draft values on Ditty editor update
	 *
	 * @access public
	 * @since  3.0.17
	 */
	public function editor_save_ajax() {	
		check_ajax_referer( 'ditty', 'security' );
		$ditty_id_ajax = isset( $_POST['ditty_id'] ) ? $_POST['ditty_id'] : false;
		$return_items_ajax = isset( $_POST['return_items'] ) ? $_POST['return_items'] : false;
		$draft_values_ajax = isset( $_POST['draft_values'] ) ? $_POST['draft_values'] : false;
		if ( ! current_user_can( 'edit_dittys' ) || ! $ditty_id_ajax ) {
			wp_die();
		}
		$initialized = get_post_meta( $ditty_id_ajax, '_ditty_init', true );
		$add_display = false;
		$add_item = false;

		do_action( 'ditty_editor_update', $ditty_id_ajax, $draft_values_ajax );

		$json_data = array();

		$ditty_post_data = array();
		if ( ! $initialized ) {
			$ditty_post_data['post_title'] = sprintf( __( 'Ditty %d', 'ditty-news-ticker' ), $ditty_id_ajax );
			$ditty_post_data['post_status'] = 'publish';
		}
		if ( isset( $draft_values_ajax['settings'] ) ) {
			if ( isset( $draft_values_ajax['settings']['title'] ) ) {
				$ditty_post_data['post_title'] = $draft_values_ajax['settings']['title'];
			}
			if ( isset( $draft_values_ajax['settings']['status'] ) ) {
				$ditty_post_data['post_status'] = esc_attr( $draft_values_ajax['settings']['status'] );
			}
			$sanitized_settings = $this->sanitize_settings( $draft_values_ajax['settings'] );
			update_post_meta( $ditty_id_ajax, '_ditty_settings', $sanitized_settings );
		} elseif( ! $initialized ) {
			$sanitized_settings = $this->sanitize_settings( array() );
			update_post_meta( $ditty_id_ajax, '_ditty_settings', $sanitized_settings );
		}

		// Publish the ditty if this is a new post
		if ( ! $initialized ) {
			$ditty_post_data['post_type'] = 'ditty';
			$ditty_post_data['ID'] = $ditty_id_ajax;
		  wp_update_post( $ditty_post_data );
		  $json_data['new_ditty_url'] = get_edit_post_link( $ditty_id_ajax );
			$initialized = 'yes';
			update_post_meta( $ditty_id_ajax, '_ditty_init', $initialized );
			$add_display = true;
			$add_item = true;

		// Update the ditty title
		} elseif( ! empty( $ditty_post_data ) ) {
			$ditty_post_data['ID'] = $ditty_id_ajax;
		  wp_update_post( $ditty_post_data );
		}
		
		// Sanitize default post meta
		$ditty_post_meta = ( isset( $draft_values_ajax['post_meta'] ) && is_array( $draft_values_ajax['post_meta'] ) ) ? $draft_values_ajax['post_meta'] : false;
		if ( $ditty_post_meta ) {
			if ( is_array( $ditty_post_meta ) && count( $ditty_post_meta ) > 0 ) {
				foreach ( $ditty_post_meta as $meta_key => $meta_value ) {
					if ( '_ditty_display' == $meta_key ) {
						$add_display = false;
					}
					$meta_value = apply_filters( 'ditty_post_meta_update', $meta_value, $meta_key, $ditty_id_ajax );
					update_post_meta( $ditty_id_ajax, $meta_key, sanitize_text_field( $meta_value ) );
				}
			}
		}
		
		// If this is a new post and no display has been selected
		if ( $add_display ) {
			$default_display = ditty_default_display( $ditty_id_ajax );
			update_post_meta( $ditty_id_ajax, '_ditty_display', $default_display );
		}

		// Update items
		if ( isset( $draft_values_ajax['items'] ) && is_array( $draft_values_ajax['items'] ) && count( $draft_values_ajax['items'] ) > 0 ) {
			foreach ( $draft_values_ajax['items'] as $item_id => $item_data ) {	
				
				if ( 'DELETE' == $item_data ) {
					
					ditty_item_delete_all_meta( $item_id );
					Ditty()->db_items->delete( $item_id );
					continue;
					
				} elseif( is_array( $item_data ) ) {
					
					// Add or update a item
					if ( isset( $item_data['data'] ) ) {
						$sanitized_item_data = $this->sanitize_item_data( $item_data['data'] );	
						if ( false !== strpos( $item_id, 'new-' ) ) {
							
							// Set the item_author
							$sanitized_item_data['item_author'] = get_current_user_id();
							if ( $new_item_id = Ditty()->db_items->insert( apply_filters( 'ditty_item_db_data', $sanitized_item_data, $ditty_id_ajax ), 'item' ) ) {
								if ( ! isset( $json_data['ditty_new_item_ids'] ) ) {
									$json_data['ditty_new_item_ids'] = array();
								}
								$json_data['ditty_new_item_ids'][$item_id] = $new_item_id;
								$item_id = $new_item_id;
							}
						} else {
							// Only update the modified date if the value is changed
							if ( array_key_exists( 'item_value', $sanitized_item_data ) ) {
								$sanitized_item_data['date_modified'] = date( 'Y-m-d H:i:s' );
							}
							Ditty()->db_items->update( $item_id, apply_filters( 'ditty_item_db_data', $sanitized_item_data, $ditty_id_ajax ), 'item_id' );
						}
					}
					
					// Add or update item meta
					// TODO: Sanitize item meta on save
					if ( isset( $item_data['meta'] ) && is_array( $item_data['meta'] ) && count( $item_data['meta'] ) > 0 ) {
						foreach ( $item_data['meta'] as $meta_key => $meta_value ) {
							if ( 'delete_meta' == $meta_value ) {
								ditty_item_delete_meta( $item_id, $meta_key );
							} else {
								ditty_item_update_meta( $item_id, $meta_key, $meta_value );
							}
						}
					}	
				}
			}
		} elseif ( $add_item ) {
			$item = ditty_get_new_item_meta( $ditty_id_ajax );
			unset( $item['item_id'] );
			$sanitized_item_data = $this->sanitize_item_data( $item );	
			Ditty()->db_items->insert( apply_filters( 'ditty_item_db_data', $sanitized_item_data, $ditty_id_ajax ), 'item' );
		}
		
		// Possibly add a uniq_id
		ditty_maybe_add_uniq_id( $ditty_id_ajax );

		$display_items = ditty_display_items( $ditty_id_ajax, 'force' );
		if ( boolval( $return_items_ajax ) ) {
			$json_data['display_items'] = $display_items;
		}
		$json_data = apply_filters( 'ditty_editor_save_data', $json_data, $ditty_id_ajax );
		wp_send_json( $json_data );	
	}
	
}