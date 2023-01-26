<?php
/**
 * Ditty Items
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Items
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Items {


	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
		// Include external class files
		//add_action( 'plugins_loaded', array( $this, 'includes' ), 1 );
		
		// General item filters
		add_filter( 'ditty_type_css_selectors', array( $this, 'global_css_selectors' ) );
		
		// Editor elements
		add_action( 'ditty_editor_tabs', array( $this, 'editor_tab' ), 5, 2 );
		add_action( 'ditty_editor_panels', array( $this, 'editor_items_panel' ), 10, 2 );
		add_action( 'ditty_editor_panels', array( $this, 'editor_item_types_panel' ), 10, 2 );
		
		// Item elements
		add_action( 'ditty_editor_item_elements', array( $this, 'editor_item_icon' ), 5 );
		add_action( 'ditty_editor_item_elements', array( $this, 'editor_item_label' ), 10 );
		add_action( 'ditty_editor_item_elements', array( $this, 'editor_item_edit' ), 15 );
		add_action( 'ditty_editor_item_elements', array( $this, 'editor_item_layout' ), 20 );
		add_action( 'ditty_editor_item_elements', array( $this, 'editor_item_clone' ), 25 );
		add_action( 'ditty_editor_item_elements', array( $this, 'editor_item_delete' ), 30 );
		add_action( 'ditty_editor_item_elements', array( $this, 'editor_item_move' ), 35 );
		
		// Ajax
		//add_action( 'wp_ajax_ditty_editor_item_types', array( $this, 'editor_item_types_ajax' ) );
		//add_action( 'wp_ajax_nopriv_ditty_editor_item_types', array( $this, 'editor_item_types_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_item_type_update', array( $this, 'editor_item_type_update_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_item_type_update', array( $this, 'editor_item_type_update_ajax' ) );
		
		add_action( 'wp_ajax_ditty_editor_item_fields', array( $this, 'editor_fields_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_item_fields', array( $this, 'editor_fields_ajax' ) );
		
		add_action( 'wp_ajax_ditty_editor_item_update', array( $this, 'editor_item_update_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_item_update', array( $this, 'editor_item_update_ajax' ) );
		
		add_action( 'wp_ajax_ditty_editor_item_add', array( $this, 'editor_item_add_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_item_add', array( $this, 'editor_item_add_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_item_clone', array( $this, 'editor_item_clone_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_item_clone', array( $this, 'editor_item_clone_ajax' ) );
		add_action( 'wp_ajax_ditty_editor_item_index_update', array( $this, 'editor_item_index_update_ajax' ) );
		add_action( 'wp_ajax_nopriv_ditty_editor_item_index_update', array( $this, 'editor_item_index_update_ajax' ) );
	}
		
	/**
	 * Load the required dependencies for the item types.
	 *
	 * @access  private
	 * @since   3.0
	 */
	// public function includes() {		
	// 	require_once DITTY_DIR . 'includes/class-ditty-item-type.php';	
	// 	$item_types = ditty_item_types();
	// 	if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
	// 		foreach ( $item_types as $i => $type ) {
	// 			if ( isset( $type['class_path'] ) ) {
	// 				require_once $type['class_path'];
	// 			}
	// 		}
	// 	}
	// }

	/**
	 * Add global css selectors for item types
	 *
	 * @access  private
	 * @since   3.0
	 * @return	array $selectors
	 */
	public function global_css_selectors( $selectors ) {
		$globals = array(
			'elements' => array(
				'selector' 				=> '.ditty-item__elements',
				'description' => __( 'The item elements container', 'ditty-news-ticker' ),
			),
		);
		
		return $globals + $selectors;
	}
	
	/**
	 * Add the editor item icon
	 *
	 * @since    3.0
	 */
	public function editor_item_icon( $item ) {
		if ( current_user_can( 'edit_ditty_items' ) ) {
			echo '<a href="#" class="ditty-data-list__item__icon protip" data-pt-title="' . __( 'Change Type', 'ditty-news-ticker' ) . '" data-pt-position="top-left"><i class="' . $item->get_icon() . '" data-class="' . $item->get_icon() . '"></i></a>';
		}
	}
	
	/**
	 * Add the editor item label
	 *
	 * @since    3.0
	 */
	public function editor_item_label( $item ) {
		?>
		<span class="ditty-data-list__item__label"><?php echo $item->get_preview(); ?></span>
		<?php
	}

	/**
	 * Add the editor move button
	 *
	 * @since    3.0
	 */
	public function editor_item_move( $item ) {
		if ( current_user_can( 'edit_ditty_items' ) ) {
			echo '<a href="#" class="ditty-data-list__item__move protip" data-pt-title="' . __( 'Re-arrange', 'ditty-news-ticker' ) . '"><i class="fas fa-bars" data-class="fas fa-bars"></i></a>';
		}
	}
	
	/**
	 * Add the editor edit button
	 *
	 * @since    3.0
	 */
	public function editor_item_edit( $item ) {
		if ( current_user_can( 'edit_ditty_items' ) ) {
			echo '<a href="#" class="ditty-data-list__item__edit protip" data-pt-title="' . __( 'Edit Item', 'ditty-news-ticker' ) . '"><i class="fas fa-edit" data-class="fas fa-edit"></i></a>';
		}
	}
	
	/**
	 * Add the editor layout button
	 *
	 * @since    3.0
	 */
	public function editor_item_layout( $item ) {
		if ( current_user_can( 'edit_ditty_items' ) ) {
			echo '<a href="#" class="ditty-data-list__item__layout protip" data-pt-title="' . __( 'Edit Layout', 'ditty-news-ticker' ) . '"><i class="fas fa-pencil-ruler" data-class="fas fa-pencil-ruler"></i></a>';
		}
	}
	
	/**
	 * Add the editor clone button
	 *
	 * @since    3.0
	 */
	public function editor_item_clone( $item ) {
		if ( current_user_can( 'publish_ditty_items' ) ) {
			echo '<a href="#" class="ditty-data-list__item__clone protip" data-pt-title="' . __( 'Clone', 'ditty-news-ticker' ) . '"><i class="fas fa-clone" data-class="fas fa-clone"></i></a>';
		}
	}
	
	/**
	 * Add the editor delete button
	 *
	 * @since    3.0
	 */
	public function editor_item_delete( $item ) {
		if ( current_user_can( 'delete_ditty_items' ) ) {
			echo '<a href="#" class="ditty-data-list__item__delete protip" data-pt-title="' . __( 'Delete', 'ditty-news-ticker' ) . '"><i class="fas fa-trash-alt" data-class="fas fa-trash-alt"></i></a>';
		}
	}
	
	/**
	 * Add to the editor tabs
	 *
	 * @access  public
	 * @since   3.0
	 * @param   $html
	 */
	public function editor_tab( $tabs, $ditty_id ) {
		if ( ! current_user_can( 'edit_ditty_items' ) ) {
			return false;
		}
		$tabs['items'] = array(
			'icon' 		=> 'fas fa-stream',
			'label'		=> __( 'Items', 'ditty-news-ticker' ),
		);
		return $tabs;
	}
	
	/**
	 * Add the editor items panel
	 *
	 * @access  public
	 * @since   3.0
	 * @param   $html
	 */
	public function editor_items_panel( $panels, $ditty_id ) {
		if ( ! current_user_can( 'edit_dittys' ) ) {
			return false;
		}	
		ob_start();
		?>
		<div class="ditty-editor-options ditty-metabox">
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__header">
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--end">
						<a class="ditty-editor-options__add ditty-button ditty-button--small" href="#"><i class="fas fa-plus-circle"></i> <?php _e( 'Add Item', 'ditty-news-ticker' ); ?></a>
					</div>
				</div>
				<div class="ditty-data-list">
					<div class="ditty-data-list__items">
						<?php
						$items_meta = ditty_items_meta( $ditty_id );
						if ( is_array( $items_meta ) && count( $items_meta ) > 0 ) {
							foreach ( $items_meta as $i => $meta ) {
								$editor_item = new Ditty_Item( $meta );
								echo $editor_item->render_editor_list_item( 'return' );
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		$panels['items'] = ob_get_clean();
		return $panels;
	}
	
	/**
	 * Add the editor item types panel
	 *
	 * @access public
	 * @since  3.0
	 */
	public function editor_item_types_panel( $panels, $ditty_id ) {
		if ( ! current_user_can( 'edit_dittys' ) ) {
			return false;
		}	
		ob_start();
		?>
		<div class="ditty-editor-options ditty-metabox" data-ditty_id="<?php echo $ditty_id; ?>">
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__header">
					<h3 class="ditty-editor-options__title"><?php _e( 'Item Types', 'ditty-news-ticker' ); ?></h3>
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--end">
						<a href="#" class="ditty-editor-options__back protip" data-pt-title="<?php _e( 'Go Back', 'ditty-news-ticker' ); ?>"><i class="fas fa-chevron-right" data-class="fas fa-chevron-right"></i></a>
					</div>
				</div>
				<div class="ditty-data-list">
					<div class="ditty-data-list__items">
						<?php
						$item_types = ditty_item_types();
						if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
							foreach ( $item_types as $slug => $item_type ) {
								?>
								<div class="ditty-editor-item-type ditty-data-list__item protip" data-item_type="<?php echo $slug; ?>" data-pt-title="<?php echo $item_type['description']; ?>" data-pt-position="top-left">
									<span class="ditty-data-list__item__icon"><i class="<?php echo $item_type['icon']; ?>"></i></span>
									<span class="ditty-data-list__item__label"><?php echo $item_type['label']; ?></span>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		$panels['item_types'] = ob_get_clean();
		return $panels;
	}

	/**
	 * Return a item values to edit
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_fields_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$item_id_ajax 			= isset( $_POST['item_id'] ) 			? $_POST['item_id'] 			: false;	
		$draft_values_ajax 	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 	: false;
		if ( ! current_user_can( 'edit_ditty_items' ) || ! $item_id_ajax ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		
		$editor_item = new Ditty_Item( $item_id_ajax );
		if ( ! $editor_item ) {
			wp_die();
		}
		$atts = array(
			'class' 					=> "ditty-editor-options ditty-editor-options--{$editor_item->get_type()} ditty-metabox",
			'data-item_id' 		=> $editor_item->get_id(),
			'data-item_type' 	=> $editor_item->get_type(),
			'data-ditty_id'		=> $editor_item->get_ditty_id(),
		);
		?>
		<form <?php echo ditty_attr_to_html( $atts ); ?> />
			<div class="ditty-editor-options__contents">
				<div class="ditty-editor-options__header">
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--start">
						<a href="#" class="ditty-editor-options__back"><i class="fas fa-chevron-left" data-class="fas fa-chevron-left"></i></a>
					</div>
					<h3 class="ditty-editor-options__title"><?php echo $editor_item->get_preview(); ?></h3>
					<div class="ditty-editor-options__buttons ditty-editor-options__buttons--end">
						<a href="#" class="ditty-editor-options__preview"><i class="fas fa-sync-alt" data-class="fas fa-sync-alt"></i></a>
					</div>
				</div>
				<div class="ditty-editor-options__body">
					<?php
					echo $editor_item->get_setting_fields();
					?>
				</div>
			</div>
		</form>
		<?php
		wp_die();
	}
	
	/**
	 * Add a item via ajax
	 *
	 * @since    3.0
	 */
	public function editor_item_add_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$ditty_id_ajax 		= isset( $_POST['ditty_id'] ) 		? intval( $_POST['ditty_id'] ) : false;
		$draft_values_ajax 	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 				: false;
		if ( ! current_user_can( 'edit_dittys' ) || ! $ditty_id_ajax ) {
			return false;
		}	
		ditty_set_draft_values( $draft_values_ajax );
		
		$new_meta = ditty_get_new_item_meta( $ditty_id_ajax );
		$editor_item = new Ditty_Item( $new_meta );
		$data = array(
			'display_items'	=> $editor_item->get_display_items(),
			'editor_item' 	=> $editor_item->render_editor_list_item( 'return' ),
			'draft_id'			=> $editor_item->get_id(),
			'draft_data' 		=> $editor_item->get_db_data(),
			'draft_meta' 		=> $editor_item->custom_meta(),
		);
		wp_send_json( $data );
	}
	
	/**
	 * Clone a item via ajax
	 *
	 * @since    3.0
	 */
	public function editor_item_clone_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$item_id_ajax 			= isset( $_POST['item_id'] ) 			? sanitize_text_field( $_POST['item_id'] ) 	: false;
		$draft_values_ajax 	= isset( $_POST['draft_values'] ) ? $_POST['draft_values'] 										: false;
		if ( ! current_user_can( 'publish_ditty_items' ) || ! $item_id_ajax ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		
		$editor_item = new Ditty_Item( $item_id_ajax );
		$draft_id = uniqid( 'new-' );
		$draft_index = $editor_item->get_index() + 1;
		$editor_item->set_id( $draft_id );
		$editor_item->set_date_created();
		$editor_item->set_date_modified();
		$editor_item->set_item_index( $draft_index );
		$data = array(
			'editor_item' 	=> $editor_item->render_editor_list_item( 'return' ),
			'display_items'	=> $editor_item->get_display_items(),
			'draft_id'			=> $draft_id,
			'draft_data' 		=> $editor_item->get_db_data(),
			'draft_meta' 		=> $editor_item->custom_meta(),
		);
		wp_send_json( $data );
	}

	/**
	 * Update the item via ajax
	 *
	 * @since    3.0
	 */
	public function editor_item_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$item_id_ajax				= isset( $_POST['item_id'] ) 			? $_POST['item_id']				: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] ) ? $_POST['draft_values']	: false;
		if ( ! current_user_can( 'edit_ditty_items' ) || ! $item_id_ajax ) {
			return false;
		}
		ditty_set_draft_values( $draft_values_ajax );
		unset( $_POST['action'] );
		unset( $_POST['draft_values'] );
		unset( $_POST['security'] );
		unset( $_POST['item_id'] );
		
		$editor_item 	= new Ditty_Item( $item_id_ajax );
		$editor_item->set_item_value( $_POST );	

		// Find updated values
		$value_updates = array();
		$sanitized_values = $editor_item->get_value();
		if ( is_array( $sanitized_values ) && count( $sanitized_values ) > 0 ) {
			foreach ( $sanitized_values as $key => $value ) {
				if ( is_array( $value ) ) {
					if ( strlen( maybe_serialize( $value ) ) !== strlen( maybe_serialize( $_POST[$key] ) ) ) {
						$value_updates[$key] = $value;
					}					
				} else {
					if ( isset( $_POST[$key] ) && $value !== $_POST[$key] ) {
						$value_updates[$key] = $value;
					}
				}	
			}
		}
		
		$json_data = array(
			'editor_item' 	=> $editor_item->render_editor_list_item( 'return' ),
			'display_items'	=> $editor_item->get_display_items(),
			'draft_id'			=> $editor_item->get_id(),
			'draft_data' 		=> $editor_item->get_db_data(),
			'draft_meta' 		=> $editor_item->custom_meta(),
			'value_updates'	=> $value_updates,
		);
		
		wp_send_json( $json_data );
	}
	
	/**
	 * Update a item's type
	 *
	 * @access public
	 * @since  3.0
	 * @param   json.
	 */
	public function editor_item_type_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		$item_id_ajax 			= isset( $_POST['item_id'] ) 			? $_POST['item_id'] 			: false;
		$item_type_ajax 		= isset( $_POST['item_type'] ) 		? $_POST['item_type'] 			: false;
		$draft_values_ajax	= isset( $_POST['draft_values'] ) ? $_POST['draft_values']	: false;
		if ( ! current_user_can( 'edit_ditty_items' ) || ! $item_id_ajax  ) {
			wp_die();
		}
		ditty_set_draft_values( $draft_values_ajax );
		
		$editor_item = new Ditty_Item( $item_id_ajax );
		$editor_item->set_item_type( $item_type_ajax );
		$data = array(
			'editor_item' 	=> $editor_item->render_editor_list_item( 'return' ),
			'display_items' => $editor_item->get_display_items(),
			'draft_id' 			=> $item_id_ajax,
			'draft_data' 		=> $editor_item->get_db_data(),
		);	
		wp_send_json( $data );
	}
	
	/**
	 * Update the order of items
	 *
	 * @since    3.0
	 */
	public function editor_item_index_update_ajax() {
		check_ajax_referer( 'ditty', 'security' );
		if ( ! current_user_can( 'edit_ditty_items' ) ) {
			return false;
		}

		$ditty_id_ajax = isset( $_POST['ditty_id'] ) 	? $_POST['ditty_id'] : false;
		$item_ids_ajax 	= isset( $_POST['item_ids'] ) 	? $_POST['item_ids'] 	: false;
		$success 				= false;
		
		if ( is_array( $item_ids_ajax ) && count( $item_ids_ajax ) > 0 ) {
			foreach ( $item_ids_ajax as $index => $item_id ) {
				$editor_item = new Ditty_Item( $item_id );
				$editor_item->set_item_index( $index );
			}
		}	
		wp_send_json(
			array(
				'success' => true,
			)
		);
	}

}