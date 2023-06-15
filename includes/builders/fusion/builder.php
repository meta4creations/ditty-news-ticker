<?php
function ditty_fusion_elements() {
	$ditty_options = array( '' => esc_html__( 'Select a Ditty', 'ditty-news-ticker' ) ) + Ditty()->singles->select_field_options();
	$display_options = array( '' => esc_html__( 'Use Default Display', 'ditty-news-ticker' ) ) + Ditty()->displays->select_field_options();
	$layout_options = array( '' => esc_html__( 'Use Default Layout', 'ditty-news-ticker' ) ) + Ditty()->layouts->select_field_options();
	
	$args = array(
		'name'            => esc_attr__( 'Ditty', 'ditty-news-ticker' ),
		'shortcode'       => 'ditty',
		'icon'            => 'ditty',
		'preview'         => DITTY_DIR . 'includes/builders/fusion/preview.php',
		'preview_id'      => 'fusion-builder-block-module-ditty-template',
		'allow_generator' => true,
		'params'          => array(
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Ditty', 'ditty-news-ticker' ),
				'description' => esc_attr__( 'Select a Ditty to display.', 'ditty-news-ticker' ),
				'param_name'  => 'id',
				'value'				=> $ditty_options,
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Display', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Optional: Select a custom display to use with the Ditty.', 'ditty-news-ticker' ),
				'param_name'  => 'display',
				'value'				=> $display_options,
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Layout', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Optional: Select a custom layout to use with the Ditty.', 'ditty-news-ticker' ),
				'param_name'  => 'layout',
				'value'				=> $layout_options,
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Custom ID', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Optional: Add a custom ID to the Ditty.', 'ditty-news-ticker' ),
				'param_name'  => 'el_id',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Custom Classes', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Optional: Add custom classes to the Ditty.', 'ditty-news-ticker' ),
				'param_name'  => 'class',
			),
			array(
				'group'				=> esc_html__( 'Advanced', 'ditty-news-ticker' ),
				'type'        => 'textarea',
				'heading'     => esc_html__( 'Custom Display Settings', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Add custom display settings, using a query string.', 'ditty-news-ticker' ),
				'param_name'  => 'display_settings',
			),
			array(
				'group'				=> esc_html__( 'Advanced', 'ditty-news-ticker' ),
				'type'        => 'textarea',
				'heading'     => esc_html__( 'Custom Layout Settings', 'ditty-news-ticker' ),
				'description' => esc_html__( 'Add custom layout settings, using a query string.', 'ditty-news-ticker' ),
				'param_name'  => 'layout_settings',
			),
		),
	);
	fusion_builder_map( $args );
}
add_action( 'fusion_builder_before_init', 'ditty_fusion_elements' );