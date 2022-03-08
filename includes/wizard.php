<?php

/**
 * Render the wizard
 * 
 * @since  3.0.13
 * @return void
 */
function ditty_wizard( $post ) {
	$item_types = ditty_item_types();
	$item_type_variations = array();
	$counter = 0;
	?>
	<div id="ditty-page" class="wrap ditty-wizard">	
		<div id="ditty-page__header" class="ditty-wizard-header">
			<h2><?php _e( 'Ditty Wizard', 'ditty-news-ticker' ); ?></h2>
<!-- 			<div class="ditty-wizard-header__contents">
				<ul>
					<li>
						<h4><i class="fas fa-stream"></i> <?php esc_html_e( 'Add your first Item type', 'ditty-news-ticker' ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</li>
					<li>
						<h4><i class="fas fa-pencil-ruler"></i> <?php esc_html_e( 'Select a Layout', 'ditty-news-ticker' ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</li>
					<li>
						<h4><i class="fas fa-tablet-alt"></i> <?php esc_html_e( 'Select a Display', 'ditty-news-ticker' ); ?></h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
					</li>
				</ul>
			</div> -->
		</div>	
		<div id="ditty-page__content">
			<div id="ditty-editor">
				<div id="ditty-editor__initialize">
					
					<?php $counter++; // Ditty title ?>
					<div class="ditty-wizard-setting ditty-wizard-setting--title">
						<div class="ditty-instruction-number"><span><?php echo intval( $counter ); ?></span></div>
						<div class="ditty-wizard-setting__content">
							<h3 class="ditty-wizard-setting__title"><?php _e( 'Add a title', 'ditty-news-ticker' ); ?></h3>
							<p class="ditty-wizard-setting__description"><?php _e( 'Set a title for your Ditty.', 'ditty-news-ticker' ); ?></p>
							<input type="text" name="ditty_title" placeholder="<?php printf( __( 'Ditty %d', 'ditty-news-ticker' ), $post->ID ); ?>" />
						</div>
					</div>
					
					<?php $counter++; // Item type selection ?>
					<div class="ditty-wizard-setting ditty-wizard-setting--item-type">
						<div class="ditty-instruction-number"><span><?php echo intval( $counter ); ?></span></div>
						<div class="ditty-wizard-setting__content">
							<h3 class="ditty-wizard-setting__title"><?php _e( 'Create your first Item', 'ditty-news-ticker' ); ?></h3>
							<p class="ditty-wizard-setting__description"><?php _e( 'Choose the Item type you want to use with your first item. You will be able to add more once the Ditty has been created!', 'ditty-news-ticker' ); ?></p>
							<div class="ditty-wizard-option ditty-option-grid">
								<?php 
								if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
									foreach ( $item_types as $slug => $item_type ) {
										$item_type_object = ditty_item_type_object( $slug );
										$variation_types = $item_type_object->get_layout_variation_types();
										if ( is_array( $variation_types ) && count( $variation_types ) > 0 ) {
											foreach ( $variation_types as $id => $variation_type ) {
												if ( ! isset( $item_type_variations[$id] ) ) {
													$item_type_variations[$id] = array(
														'title' => $variation_type['label'],
														'description' => $variation_type['description'],
														'item_types' => array(),
													);
												}
												$item_type_variations[$id]['item_types'][] = $slug;
											}
										}
										echo '<button class="ditty-option-grid__item" data-value="' . esc_attr( $slug ) . '" data-layout_variations="' . htmlspecialchars( json_encode( $variation_types ) ). ' ">';
											echo '<i class="' . esc_attr( $item_type['icon'] ) . '"></i>';
											echo '<h4 class="ditty-option-grid__item__title">' . sanitize_text_field( $item_type['label'] ) . '</h4>';
											echo '<span class="ditty-option-grid__item__description">' . sanitize_text_field( $item_type['description'] ) . '</span>';
										echo '</button>';
									}
								}
								?>
							</div>
						</div>
					</div>
					
					<?php $counter++; // Item type settings ?>
					<div class="ditty-wizard-setting ditty-wizard-setting--item-type-settings">
						<div class="ditty-instruction-number"><span><?php echo intval( $counter ); ?></span></div>
						<div class="ditty-wizard-setting__content">
							
							<h3 class="ditty-wizard-setting__title"><?php _e( "Set the Item options", 'ditty-news-ticker' ); ?></h3>
							<p class="ditty-wizard-setting__description"><?php _e( "Each item type has it's own set of options to customize.", 'ditty-news-ticker' ); ?></p>
							<div class="ditty-wizard-option ditty-item-type-settings">
								<?php 
								if ( is_array( $item_types ) && count( $item_types ) > 0 ) {
									foreach ( $item_types as $slug => $item_type ) {
										echo '<div class="ditty-item-type-settings__group" data-id="' . esc_attr( $slug ) . '">';
											$item_type_object = ditty_item_type_object( $slug );
											$item_type_object->settings();
										echo '</div>';
									}
								}
								?>
							</div>
							<button href="#" class="ditty-option-submit ditty-button ditty-button--primary"><?php _e( 'Confirm Settings', 'ditty-news-ticker' ); ?></button>
							
							<div class="ditty-updating-overlay">
								<div class="ditty-updating-overlay__inner">
									<i class="fas fa-sync-alt fa-spin"></i>
								</div>
							</div>	
						</div>
					</div>
					
					<?php $counter++; // Layouts ?>
					<div class="ditty-wizard-setting ditty-wizard-setting--layout">
						<div class="ditty-instruction-number"><span><?php echo intval( $counter ); ?></span></div>
						<div class="ditty-wizard-setting__content">
							<h3 class="ditty-wizard-setting__title"><?php _e( 'Select Variation Layouts for the Item', 'ditty-news-ticker' ); ?></h3>
							<p class="ditty-wizard-setting__description"><?php _e( 'Layouts are used to render the Item data. Each Item type may have multiple Layout variations.', 'ditty-news-ticker' ); ?></p>
							<?php	
							$layouts = ditty_layout_posts();
							$layout_options = '';
							$layout_options .= '<div class="ditty-wizard-option ditty-option-grid">';
								if ( is_array( $layouts ) && count( $layouts ) > 0 ) {
									foreach ( $layouts as $layout ) {
										$icon = get_post_meta( $layout->ID, '_ditty_layout_icon', true );
										$description = get_post_meta( $layout->ID, '_ditty_layout_description', true );
										$version_string = '';
										$version = get_post_meta( $layout->ID, '_ditty_layout_version', true );
										if ( $version ) {
											$version_string = " <small class='ditty-layout-version'>(v{$version})</small>";
										}
										$layout_options .= '<button class="ditty-option-grid__item" data-value="' . esc_attr( $layout->ID ) . '">';
											if ( '' == $icon ) {
												$icon = 'fas fa-pencil-ruler';
											}
											$layout_options .= '<i class="' . esc_attr( $icon ) . '"></i>';
											$layout_options .= '<h4 class="ditty-option-grid__item__title">' . sanitize_text_field( $layout->post_title ) . $version_string . '</h4>';
											$layout_options .= '<span class="ditty-option-grid__item__id">' . sprintf( __( 'ID: %d' ), $layout->ID ) . '</span>';
											$layout_options .= '<span class="ditty-option-grid__item__date">' . get_the_time( 'm/d/y \a\t g:ia', $layout->ID ) . '</span>';
											if ( $description ) {
												$layout_options .= '<span class="ditty-option-grid__item__description">' . sanitize_text_field( $description ) . '</span>';
											}
										$layout_options .= '</button>';
									}
								}
							$layout_options .= '</div>';
							
							if ( is_array( $item_type_variations ) && count( $item_type_variations ) > 0 ) {
								foreach ( $item_type_variations as $id => $item_type_variation ) {
									echo '<div class="ditty-wizard-setting--layout__variation ' . esc_attr( implode( ' ', $item_type_variation['item_types'] ) ) . '" data-id="' . esc_attr( $id ) . '">';
										echo '<h4 class="ditty-wizard-setting--layout__variation__title">' . sprintf( __( 'Layout Variation: %s', 'ditty-news-ticker' ), sanitize_text_field( $item_type_variation['title'] ) ) . '</h4>';
										echo '<div class="ditty-wizard-setting--layout__variation__description">' . sanitize_text_field( $item_type_variation['description'] ) . '</div>';
										echo $layout_options;
									echo '</div>';
								}
							}
							?>
						</div>
					</div>
					
					<?php $counter++; // Display ?>
					<div class="ditty-wizard-setting ditty-wizard-setting--display">
						<div class="ditty-instruction-number"><span><?php echo intval( $counter ); ?></span></div>
						<div class="ditty-wizard-setting__content">
							<h3 class="ditty-wizard-setting__title"><?php _e( 'Select a Display', 'ditty-news-ticker' ); ?></h3>
							<p class="ditty-wizard-setting__description"><?php _e( 'Displays are what ulimately display your Ditty on your site.', 'ditty-news-ticker' ); ?></p>
							<?php
							$args = array(
								'posts_per_page' => -1,
								'orderby' 		=> 'post_title',
								'order' 			=> 'ASC',
								'post_type' 	=> 'ditty_display',
							);
							$displays = get_posts( $args );
							$display_types = ditty_display_types();
							$display_options = '';
							$display_options .= '<div class="ditty-wizard-option ditty-option-grid">';
								if ( is_array( $displays ) && count( $displays ) > 0 ) {
									foreach ( $displays as $display ) {
										$type = get_post_meta( $display->ID, '_ditty_display_type', true );
										if ( ! isset( $display_types[$type] ) ) {
											continue;
										}	
										$description = get_post_meta( $display->ID, '_ditty_display_description', true );
										$version_string = '';
										$version = get_post_meta( $display->ID, '_ditty_display_version', true );
										if ( $version ) {
											$version_string = " <small class='ditty-display-version'>(v{$version})</small>";
										}
										$display_options .= '<button class="ditty-option-grid__item" data-value="' . esc_attr( $display->ID ) . '">';
											$display_options .= '<i class="' . esc_attr( $display_types[$type]['icon'] ) . '"></i>';
											$display_options .= '<h4 class="ditty-option-grid__item__title">' . sanitize_text_field( $display->post_title ) . $version_string . '</h4>';
											$display_options .= '<span class="ditty-option-grid__item__type">' . sanitize_text_field( $display_types[$type]['label'] ) . '</span>';
											$display_options .= '<span class="ditty-option-grid__item__id">' . sprintf( __( 'ID: %d' ), $display->ID ) . '</span>';
											$display_options .= '<span class="ditty-option-grid__item__date">' . get_the_time( 'm/d/y \a\t g:ia', $display->ID ) . '</span>';
											if ( $description ) {
												$display_options .= '<span class="ditty-option-grid__item__description">' . sanitize_text_field( $description ) . '</span>';
											}
										$display_options .= '</button>';
									}
								}
							$display_options .= '</div>';
							echo $display_options;
							?>
						</div>
					</div>
					
					<div class="ditty-wizard-setting ditty-wizard-setting--submit">
						<button id="ditty-wizard-submit" class="ditty-button ditty-button--primary" data-ditty_id="<?php echo $post->ID; ?>" data-submitting="<?php _e( 'Building Your Ditty...', 'ditty-news-ticker' ); ?>"><?php _e( 'Create Ditty!', 'ditty-news-ticker' ); ?></button>
					</div>
					
					<div id="ditty-wizard-overlay" class="ditty-updating-overlay">
						<div class="ditty-updating-overlay__inner">
							<i class="fas fa-sync-alt fa-spin"></i>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div><!-- /.wrap -->
	<?php
} 

/**
 * Save the wizard options
 *
 * @access public
 * @since  3.0.13
 */
function ditty_submit_wizard_ajax() {
	check_ajax_referer( 'ditty', 'security' );
	$ditty_id_ajax = isset( $_POST['ditty_id'] ) ? $_POST['ditty_id'] : false;
	$init_values_ajax = isset( $_POST['init_values'] ) ? $_POST['init_values'] : false;
	if ( ! $ditty_id_ajax || ! $init_values_ajax ) {
		wp_die();
	}
	
	// Set the initialized wp_ajax_toggle
	update_post_meta( $ditty_id_ajax, '_ditty_init', 'yes' );

	// Update the post data
	$ditty_post_data = array();
	$ditty_post_data['post_title'] = ( isset( $init_values_ajax['title'] ) && '' != $init_values_ajax['title'] ) ? sanitize_text_field( $init_values_ajax['title'] ) : sprintf( __( 'Ditty %d', 'ditty-news-ticker' ), $ditty_id_ajax );
	$ditty_post_data['post_status'] = 'publish';
	$ditty_post_data['post_type'] = 'ditty';
	$ditty_post_data['ID'] = $ditty_id_ajax;
	wp_update_post( $ditty_post_data );
	
	// Add the ditty settings
	$sanitized_settings = Ditty()->singles->sanitize_settings( array() );
	update_post_meta( $ditty_id_ajax, '_ditty_settings', $sanitized_settings );
	
	// Add the display
	$display = isset( $init_values_ajax['display'] ) ? intval( $init_values_ajax['display'] ) : ditty_default_display( $ditty_id_ajax );
	update_post_meta( $ditty_id_ajax, '_ditty_display', $display );
	
	// Possibly add a uniq_id
	ditty_maybe_add_uniq_id( $ditty_id_ajax );
	
	// Add the first item
	$item_data = array(
		'ditty_id'			=> intval( $ditty_id_ajax ),
		'item_type' 		=> isset( $init_values_ajax['itemType'] ) 				? esc_attr( $init_values_ajax['itemType'] ) : 'default',
		'item_value' 		=> isset( $init_values_ajax['itemTypeValues'] ) 	? $init_values_ajax['itemTypeValues'] 			: false,
		'layout_value' 	=> isset( $init_values_ajax['layoutVariations'] ) ? $init_values_ajax['layoutVariations'] 		: false,
	);
	$sanitized_item_data = Ditty()->singles->sanitize_item_data( $item_data );
	
	// Add the item to the database
	Ditty()->db_items->insert( apply_filters( 'ditty_item_db_data', $sanitized_item_data, $ditty_id_ajax ), 'item' );

	$data = array(
		'id' => $ditty_id_ajax,
		'values' => $init_values_ajax,
		'edit_url' => get_edit_post_link( $ditty_id_ajax ),
	);	
	wp_send_json( $data );
}
add_action( 'wp_ajax_ditty_submit_wizard', 'ditty_submit_wizard_ajax' );