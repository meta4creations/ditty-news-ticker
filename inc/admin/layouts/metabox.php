<?php
/**
 * Metabox Functions
 *
 * @package     DNT
 * @subpackage  Admin/Layouts
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add the custom meta boxes under the title for the Layout custom post type
 * 
 * @since  3.0
 * @return void
 */
function dnt_layout_after_title() {
	
	$screen = get_current_screen();
	if ( 'ditty_layout' == $screen->post_type ) {
		
		global $post;

		/*
		 * Output the layout template fields
		 * @since 3.0
		 */
		do_action( 'dnt_meta_box_layout_template_fields', $post->ID );
		
		wp_nonce_field( basename( __FILE__ ), 'dnt_layout_meta_box_nonce' );
	}	
}
add_action( 'edit_form_after_title', 'dnt_layout_after_title' );

/**
 * Returns default DNT Layout meta fields.
 *
 * @since 3.0
 * @return array $fields Array of fields.
 */
function dnt_layout_metabox_fields() {

	$fields = array(
		'_dnt_layout_structure',
		'_dnt_layout_style',
	);

	return apply_filters( 'dnt_layout_metabox_fields_save', $fields );
}


/**
 * Save post meta when the save_post action is called
 *
 * @since 3.0
 * @param int $post_id Layout (Post) ID
 * @global array $post All the data of the the current post
 * @return void
 */
function dnt_layout_meta_box_save( $post_id, $post ) {

	if ( ! isset( $_POST[ 'dnt_layout_meta_box_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'dnt_layout_meta_box_nonce' ], basename( __FILE__ ) ) ) {
		return;
	}

	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST[ 'bulk_edit' ] ) ) {
		return;
	}

	if ( isset( $post->post_type ) && 'revision' == $post->post_type ) {
		return;
	}

	if ( ! current_user_can( 'edit_ditty_layout', $post_id ) ) {
		return;
	}

	// The default fields that get saved
	$fields = dnt_layout_metabox_fields();

	foreach ( $fields as $field ) {
		
		if ( '_dnt_layout_structure' == $field ) {
			
			if ( ! empty( $_POST[$field] ) ) {

				$structure_variations = apply_filters( 'dnt_metabox_save_' . $field, $_POST[$field] );

				$new = array();
				
				if( is_array( $structure_variations ) && count( $structure_variations ) > 0 ) {
					foreach( $structure_variations as $variation => $structure ) {
						$new[$variation] = wp_kses_post( $structure );
					}
				}
				
				update_post_meta( $post_id, $field, wp_kses_post( $new ) );
			} else {
				delete_post_meta( $post_id, $field );
			}
			
		} elseif( '_dnt_layout_style' == $field ) {
			
			if ( ! empty( $_POST[$field] ) ) {
				$new = apply_filters( 'dnt_metabox_save_' . $field, $_POST[$field] );
				update_post_meta( $post_id, $field, wp_kses_post( $new ) );
			} else {
				delete_post_meta( $post_id, $field );
			}

		} else {
			
			if ( ! empty( $_POST[$field] ) ) {
				$new = apply_filters( 'dnt_metabox_save_' . $field, $_POST[$field] );
				update_post_meta( $post_id, $field, $new );
			} else {
				delete_post_meta( $post_id, $field );
			}
		}
	}

	do_action( 'dnt_save_layout', $post_id, $post );
}

add_action( 'save_post', 'dnt_layout_meta_box_save', 10, 2 );


/**
 * Render the layout template fields
 * 
 * @since  3.0
 * @return void
 */
function dnt_render_layout_template_fields( $post_id ) {

	$structure = get_post_meta( $post_id, '_dnt_layout_structure', true );
	$style = get_post_meta( $post_id, '_dnt_layout_style', true );
	$types = dnt_types();
	?>
	<div class="dnt-tabs dnt-layout__meta">
		<div class="dnt-tab__links">
			<div class="dnt-tab">
				<a class="dnt-tab__link" href="#" data-panel="structure">
					<span class="dnt-tab__icon"><i class="far fa-code"></i></span>
					<span class="dnt-tab__label"><?php _e( 'Structure', 'ditty-news-ticker' ); ?></span>
				</a>
			</div>
			<div class="dnt-tab__sep"></div>
			<div class="dnt-tab">
				<a class="dnt-tab__link" href="#" data-panel="style">
					<span class="dnt-tab__icon"><i class="fas fa-pencil-paintbrush"></i></span>
					<span class="dnt-tab__label"><?php _e( 'Style', 'ditty-news-ticker' ); ?></span>
				</a>
			</div>
			<div class="dnt-tab__sep"></div>
		</div>
		<div class="dnt-panels">
			
			<?php // HTML structure panel ?>
			<div class="dnt-panel" data-id="structure">
				<div class="dnt-panel__contents">
					<div class="dnt-panel__variations">
						<?php
						if ( is_array( $types ) && count( $types ) > 0 ) {
							$counter = 0;
							foreach ( $types as $type => $data ) {										
								if ( 0 != $counter ) {
									?>
									<div class="dnt-panel__variation__sep"></div>
									<?php
								}
								?>	
								<a class="dnt-panel__variation" data-variation="<?php echo $type; ?>">
									<span class="dnt-panel__variation__icon"><i class="<?php echo $data[ 'icon' ]; ?>"></i></span>
									<span class="dnt-panel__variation__label"><?php echo $data[ 'label' ]; ?></span>
								</a>	
								<?php
								$counter++;							
							}
						}
						?>
					</div>
					<div class="dnt-panel__body">
						<h3 class="dnt-panel__title"><?php _e( 'HTML Structure', 'ditty-news-ticker' ); ?></h3>
						<p class="dnt-panel__description">
							<?php _e( 'Use HTML and merge tags build your layout.', 'ditty-news-ticker' ); ?>
						</p>
						<?php	
						if ( is_array( $types ) && count( $types ) > 0 ) {
							foreach ( $types as $type => $data ) {
								$value = isset( $structure[$type] ) ? $structure[$type] : '';
								?>
								<div class="dnt-panel__input dnt-panel__input--variation" data-id="<?php echo $type; ?>">									
									<textarea id="_dnt_layout_structure_<?php echo $type; ?>" name="_dnt_layout_structure[<?php echo $type; ?>]" class="dnt-layout-structure-textarea" rows="20"><?php echo $value; ?></textarea>
									<p style="margin-bottom:5px;"><strong><?php _e( 'Use the following merge tags in your structure', 'ditty-news-ticker' ); ?>:</strong></p>
									<pre style="margin:0;"><?php echo dnt_get_layout_tags_list( $type ); ?></pre>
								</div>	
								<?php			
							}
						}
						?>		
					</div>
				</div>
			</div>
			<?php // End HTML structure panel ?>
			
			<?php // CSS Styles panel ?>
			<div class="dnt-panel" data-id="style">
				<div class="dnt-panel__contents">
					<div class="dnt-panel__body">
						<h3 class="dnt-panel__title"><?php _e( 'CSS Styles', 'ditty-news-ticker' ); ?></h3>
						<p class="dnt-panel__description">
							<?php _e( 'Use CSS to style your layout to your specifications.', 'ditty-news-ticker' ); ?>
						</p>
						<div class="dnt-panel__input">
							<textarea id="_dnt_layout_style" name="_dnt_layout_style" class="dnt-layout-style-textarea" rows="20"><?php echo $style; ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<?php // End CSS Styles panel ?>
			
		</div>
	</div>
	<?php
}
add_action( 'dnt_meta_box_layout_template_fields', 'dnt_render_layout_template_fields' );