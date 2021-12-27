<?php

/**
 * Create a class for the widget
 *
 * @since 3.0
 */
class ditty_widget extends WP_Widget {
		
	/** Constructor */
	function __construct() {
		parent::__construct(
			'ditty-widget',
			__( 'Ditty', 'ditty-news-ticker' ),
			array(
				'classname' => 'ditty-widget',
				'description' => __( 'Displays a Ditty.', 'ditty-news-ticker' )
			)
		);
	}
		
	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		
		extract( $args );
	
		// User-selected settings	
		$title = $instance['title'];
		$title = apply_filters( 'widget_title', $title );
		
		$ditty = isset( $instance['ditty'] ) ? $instance['ditty'] : '';
		$display = isset( $instance['display'] ) ? $instance['display'] : '';
		
		ob_start();
		
		// Display the ticker
		if( '' != $ditty ) {
			
			// Before widget (defined by themes)
			echo $before_widget;
			
			// Title of widget (before and after defined by themes)
			if ( $title && '' != $title ) {
				echo $before_title . $title . $after_title;
			}
			
			$atts = array(
				'id' => $ditty,
				'display' => ( '' != $display ) ? $display : false,
			);
			echo ditty_render( $atts );

			// After widget (defined by themes)
			echo $after_widget;
		}
	}
	
	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		// Strip tags (if needed) and update the widget settings
		$instance['title'] 		= isset( $new_instance['title'] ) 	? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['ditty'] 		= isset( $new_instance['ditty'] ) 	? intval( $new_instance['ditty'] ) 							: '';
		$instance['display'] 	= isset( $new_instance['display'] ) ? intval( $new_instance['display'] ) 						: '';
	
		return $instance;
	}
	
	/** @see WP_Widget::form */
	function form( $instance ) {
	
		// Set up some default widget settings
		$defaults = array(
			'title' => '',
			'ditty' => '',
			'display' => '',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
	  <!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ditty-news-ticker' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>
	    
	  <!-- Ditty: Select -->
		<p>
			<label for="<?php echo $this->get_field_id( 'ditty' ); ?>"><?php esc_html_e( 'Select a Ditty', 'ditty-news-ticker' ); ?></label><br/>
			<select id="<?php echo $this->get_field_id( 'ditty' ); ?>" name="<?php echo $this->get_field_name( 'ditty' ); ?>">
			<?php
			$options = Ditty()->singles->select_field_options();
			echo '<option value="">' . __( 'Select a Ditty', 'ditty-news-ticker' ) . '</option>';
			foreach( $options as $id => $label ) {
				echo '<option value="' . $id . '" ' . selected( $instance['ditty'], $id ) . '>' . $label . '</option>';
			}
			?>
			</select>
		</p>
		
		<!-- Display: Select -->
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php esc_html_e( 'Optional: Select a custom display to use with the Ditty.', 'ditty-news-ticker' ); ?></label><br/>
			<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
			<?php
			$options = Ditty()->displays->select_field_options();
			echo '<option value="">' . __( 'Use Default Display', 'ditty-news-ticker' ) . '</option>';
			foreach( $options as $id => $label ) {
				echo '<option value="' . $id . '" ' . selected( $instance['display'], $id ) . '>' . $label . '</option>';
			}
			?>
			</select>
		</p>
	  	
	<?php
	}
}


/* --------------------------------------------------------- */
/* !Register the widget - 1.5.7 */
/* --------------------------------------------------------- */

function ditty_widget_init() {
	register_widget( 'ditty_widget' );
}
add_action( 'widgets_init', 'ditty_widget_init' );

