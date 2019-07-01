<?php
/**
 * Metabox Functions
 *
 * @package     DNT
 * @subpackage  Admin/Tickers
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 *  Add the custom meta boxes under the title for the Ticker custom post type
 * 
 * @since   3.0
 * @return void
 */
function dnt_ticker_after_title() {
	
	$screen = get_current_screen();
	if( $screen->post_type == 'ditty_news_ticker' ) {
		
		global $post;

		/*
		 * Output the ticker template fields
		 * @since 3.0
		 */
		do_action( 'dnt_meta_box_ticker_template_fields', $post->ID );
		
		wp_nonce_field( basename( __FILE__ ), 'dnt_ticker_meta_box_nonce' );	
	}
}
add_action( 'edit_form_after_title', 'dnt_ticker_after_title' );


/**
 * Render the ticker template fields
 * 
 * @since  3.0
 * @return void
 */
function dnt_render_ticker_template_fields( $post_id ) {
	?>
	<div id="dnt-ticks"></div>
	<div class="dnt-ticks">
		
		<div class="dnt-ticks__add dnt-ticks__add--top">
			<a href="#"><?php _e( 'Add Tick To Top', 'ditty-news-ticker' ); ?></a>
		</div>
		
		<ul class="dnt-ticks__list">
			<?php
			$tick = new DNT_Tick();
			$tick->render_new_edit_row();	
			?>
		</ul>
		
		<div class="dnt-ticks__add dnt-ticks__add--bottom">
			<a href="#"><?php _e( 'Add Tick To Bottom', 'ditty-news-ticker' ); ?></a>
		</div>
		
	</div>
	<?php
}
add_action( 'dnt_meta_box_ticker_template_fields', 'dnt_render_ticker_template_fields' );



function dnt_render_tick_type_fields( $type, $value ) {
	if( 'default' == $type ) {
		
		$fields = array(
			array(
				'type' 				=> 'text',
				'name' 				=> __( 'Ticker text', 'ditty-news-ticker' ),
				'id'					=> 'textarea',
				'rows' 				=> 2,
				'placeholder' => __('Add your content here. HTML and inline styles are supported.', 'ditty-news-ticker'),
				'help' 				=> __('Add the content of your tick. HTML and inline styles are supported.', 'ditty-news-ticker'),
			)
		);
		
		?>
		<div class="dnt-grid">
			<div class="dnt-grid__row">
				<div class="dnt-grid__column dnt-grid__column--12">
					<div class="dnt-field dnt-field--text">
						<label class="dnt-field__label" for=""><?php _e( 'Ticker text', 'ditty-news-ticker' ); ?></label>
						<input class="dnt-field__input" type="text" name="" value="<?php echo $value; ?>" />
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'dnt_tick_type_fields', 'dnt_render_tick_type_fields', 10, 3 );