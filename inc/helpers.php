<?php
	
/**
 * Return an array of ticker types
 * @since   3.0
 */
function mtphr_dnt_types_array() {

	/* Create the types array. */
	$dnt_types_array = array();
	$dnt_types_array['default'] = array(
		'button' => __('Default', 'ditty-news-ticker'),
		'metabox_id' => 'mtphr-dnt-default-metabox',
		'icon' => 'dashicons dashicons-edit'
	);
	$dnt_types_array['mixed'] = array(
		'button' => __('Mixed', 'ditty-news-ticker'),
		'metabox_id' => 'mtphr-dnt-mixed-metabox',
		'icon' => 'dashicons dashicons-randomize'
	);
	
	return apply_filters('mtphr_dnt_types', $dnt_types_array);
}


/**
 * Return the labels of ticker types
 * @since   3.0
 */
function mtphr_dnt_types_labels() {
	
	$types = mtphr_dnt_types_array();
	$labels = array();
	
	if( is_array($types) && count($types) > 0 ) {
		foreach( $types as $i=>$type ) {
			$labels[$i] = $type['button'];
		}
	}
	
	return $labels;
}

/**
 * Return an array of ticker types
 * @since   3.0
 */
function dnt_types() {

	/* Create the types array. */
	$dnt_types = array();

	$dnt_types['default'] = array(
		'type' 				=> 'default',
		'label' 			=> __( 'Default', 'ditty-news-ticker' ),
		'icon' 				=> 'fas fa-pencil-alt',
		'description' => __( 'Manually add HTML to the tick.', 'ditty-news-ticker' ),
		'class_name'	=> 'DNT_Type_Default',
	);
	$dnt_types['twitter_feed'] = array(
		'type' 				=> 'twitter_feed',
		'label' 			=> __('Twitter Feed', 'ditty-news-ticker'),
		'icon' 				=> 'fab fa-twitter',
		'description' => __( 'Display a Twitter feed using a set of parameters.', 'ditty-news-ticker' ),
		'class_name'	=> 'DNT_Type_Twitter_Feed',
	);
	
	$dnt_types['twitter_tweet'] = array(
		'type' 				=> 'twitter_tweet',
		'label' 			=> __('Single Tweet', 'ditty-news-ticker'),
		'icon' 				=> 'fab fa-twitter',
		'description' => __( 'Display a single Tweet by entering a URL or ID.', 'ditty-news-ticker' ),
		'class_name'	=> 'DNT_Type_Twitter_Tweet',
	);

	return apply_filters( 'dnt_types', $dnt_types );
}

/**
 * Return an array of ticker types
 * @since   3.0
 */
function dnt_fields() {

	/* Create the fields array. */
	$dnt_fields = array();
	$dnt_types = dnt_types();
	
	if ( is_array( $dnt_types ) && count( $dnt_types ) > 0 ) {
		foreach ( $dnt_types as $data ) {
			if ( isset( $data['class_name'] ) && class_exists( $data['class_name'] ) ) {
				
				$type = new $data['class_name'];
				$dnt_fields[ $data[ 'type' ] ] = $type->fields();
				
			}
		}
	}

	return apply_filters( 'dnt_fields', $dnt_fields );
}

/**
 * Return an array of ticks for a ticker
 * @since   3.0
 */
function dnt_ticks( $id=false ) {
	
	$ticker_id = $id ? $id : get_the_id();
	
	if ( 'ditty_news_ticker' != get_post_type( $ticker_id ) ) {
		return;
	}

	/* Get the ticks array */
	$dnt_ticks = array();

	return apply_filters( 'dnt_ticks', $dnt_ticks, $ticker_id );
}