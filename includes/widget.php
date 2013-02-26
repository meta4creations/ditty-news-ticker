<?php
/**
 * Create a sidebar widget
 *
 * @package Ditty News Ticker
 */
 
 
 
/* Register the widget - @since 1.0 */
add_action( 'widgets_init', 'mtphr_dnt_widget_init' );

/**
 * Register the widget
 *
 * @since 1.0
 */
function mtphr_dnt_widget_init() {
	register_widget( 'mtphr_dnt_widget' );
}




/**
 * Create a class for the widget
 *
 * @since 1.0
 */
class mtphr_dnt_widget extends WP_Widget {
	
/**
 * Widget setup
 *
 * @since 1.0
 */
function mtphr_dnt_widget() {
	
	// Widget settings
	$widget_ops = array(
		'classname' => 'mtphr-dnt-widget',
		'description' => __('Displays a Ditty News Ticker.', 'ditty-news-ticker')
	);

	// Widget control settings
	$control_ops = array(
		'id_base' => 'mtphr-dnt-widget'
	);

	// Create the widget
	$this->WP_Widget( 'mtphr-dnt-widget', __('Ditty News Ticker', 'ditty-news-ticker'), $widget_ops, $control_ops );
}

/**
 * Display the widget
 *
 * @since 1.0.4
 */
function widget( $args, $instance ) {
	
	extract( $args );

	// User-selected settings	
	$title = $instance['title'];
	$title = apply_filters( 'widget_title', $title );
	
	$ticker = $instance['ticker'];
	$ticker_title = isset( $instance['ticker_title'] );
	
	// Before widget (defined by themes)
	echo $before_widget;
	
	// Title of widget (before and after defined by themes)
	if ( $title ) {
		echo $before_title . $title . $after_title;
	}
	
	// Set custom attributes
	$atts = array();
	
	// Set the ticker title visibility
	$atts['title'] = 0;
	if( $ticker_title ) {
		$atts['title'] = 1;
	}

	// Add a unique widget ID
	$atts['unique_id'] = 'widget';
	
	// Add in_widget attribute for customization
	$atts['in_widget'] = 1;
	
	// Display the ticker
	if( $ticker != '' ) {
		ditty_news_ticker( $ticker, '', $atts );
	}
	
	// After widget (defined by themes)
	echo $after_widget;
}

/**
 * Update the widget
 *
 * @since 1.0
 */
function update( $new_instance, $old_instance ) {
	
	$instance = $old_instance;

	// Strip tags (if needed) and update the widget settings
	$instance['title'] = sanitize_text_field( $new_instance['title'] );
	$instance['ticker'] = $new_instance['ticker'];
	$instance['ticker_title'] = $new_instance['ticker_title'];

	return $instance;
}

/**
 * Widget settings
 *
 * @since 1.0.5
 */
function form( $instance ) {

	// Set up some default widget settings
	$defaults = array(
		'title' => '',
		'ticker' => '',
		'ticker_title' => ''
	);
	
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	
  <!-- Widget Title: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ditty-news-ticker' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
	</p>
    
  <!-- Ticker: Select -->
	<p>
		<label for="<?php echo $this->get_field_id( 'ticker' ); ?>"><?php _e( 'Select a Ticker:', 'ditty-news-ticker' ); ?></label><br/>
		<select id="<?php echo $this->get_field_id( 'ticker' ); ?>" name="<?php echo $this->get_field_name( 'ticker' ); ?>">
		<?php
		$tickers = get_posts( 'numberposts=-1&post_type=ditty_news_ticker&orderby=name&order=ASC' );
		foreach( $tickers as $ticker ) {
			if( $instance['ticker'] == $ticker->ID ) {
				echo '<option value="'.$ticker->ID.'" selected="selected">'.$ticker->post_title.'</option>';
			} else {
				echo '<option value="'.$ticker->ID.'">'.$ticker->post_title.'</option>';
			}
		}
		?>
		</select>
	</p>

	<!-- Display Ticker Title: Checkbox -->
	<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['ticker_title'], 'on' ); ?> id="<?php echo $this->get_field_id( 'ticker_title' ); ?>" name="<?php echo $this->get_field_name( 'ticker_title' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'ticker_title' ); ?>"><?php _e( 'Display Ticker Title?', 'ditty-news-ticker' ); ?></label>
	</p>
  	
	<?php
}
}

