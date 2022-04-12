<?php

/**
 * Ditty Display Type List Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Type List
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Display_Type_List extends Ditty_Display_Type {

	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'list';
	
	/**
	 * Setup the display settings
	 *
	 * @access  public
	 * @since   3.0.19
	 */
	public function fields( $values = array() ) {	
		$fields = array(
			'listSettings' => array(
				'type' 							=> 'group',
				'id'								=> 'listSettings',
				'collapsible'				=> true,
				'default_state'			=> 'expanded',
				'multiple_fields'		=> true,
				'name' 	=> __( 'General Settings', 'ditty-news-ticker' ),
				'help' 	=> __( 'Configure the general functionality settings.', 'ditty-news-ticker' ),
				'fields' => array(
					'spacing' => array(
						'type'				=> 'slider',
						'id'					=> 'spacing',
						'name'				=> __( 'Spacing', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the amount of space between items (in pixels).', 'ditty-news-ticker' ),
						'suffix'			=> 'px',	
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 100,
							'step' 	=> 1,
						),
						'std'					=> isset( $values['spacing'] ) ? $values['spacing'] : false,
					),
					'paging' => array(
						'type'		=> 'radio',
						'id'			=> 'paging',
						'name'		=> __( 'Paging', 'ditty-news-ticker' ),
						'help'		=> __( 'Split the list into pages', 'ditty-news-ticker' ),
						'inline'	=> true,
						'options'	=> array(
							0 	=> __( 'No', 'ditty-news-ticker' ),
							1 	=> __( 'Yes', 'ditty-news-ticker' ),
						),
						'std'			=> isset( $values['paging'] ) ? $values['paging'] : false,
					),
					'perPage' => array(
						'type'		=> 'number',
						'id'			=> 'perPage',
						'name'		=> __( 'Items Per Page', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the number of items to show per page', 'ditty-news-ticker' ),
						'visible' => array( '_ditty_listSettings[paging]', '=', '1' ),
						'std'			=> isset( $values['perPage'] ) ? $values['perPage'] : false,
					),
					'transition' => array(
						'type'		=> 'select',
						'id'			=> 'transition',
						'name'		=> __( 'Page Transition', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the type of transition to use between pages', 'ditty-news-ticker' ),
						'options'	=> ditty_slider_transitions(),
						'std'			=> isset( $values['transition'] ) ? $values['transition'] : false,
					),
					'transitionEase' => array(
						'type'		=> 'select',
						'id'			=> 'transitionEase',
						'name'		=> __( 'Page Transition Ease', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the easing of the transition between pages.', 'ditty-news-ticker' ),
						'options' => ditty_ease_array(),
						'std'			=> isset( $values['transitionEase'] ) ? $values['transitionEase'] : false,
					),
					'transitionSpeed' => array(
						'type'				=> 'slider',
						'id'					=> 'transitionSpeed',
						'name'				=> __( 'Page Transition Speed', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the speed of the transition between pages.', 'ditty-news-ticker' ),
						'suffix'			=> ' ' . __( 'second(s)', 'ditty-news-ticker' ),
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 10,
							'step' 	=> 0.25,
						),
						'std'					=> isset( $values['transitionSpeed'] ) ? $values['transitionSpeed'] : false,
					),
					'heightEase' => array(
						'type'		=> 'select',
						'id'			=> 'heightEase',
						'name'		=> __( 'Height Ease', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the easing of the list height.', 'ditty-news-ticker' ),
						'options' => ditty_ease_array(),
						'std'			=> isset( $values['heightEase'] ) ? $values['heightEase'] : false,
					),
					'heightSpeed' => array(
						'type'				=> 'slider',
						'id'					=> 'heightSpeed',
						'name'				=> __( 'Height Speed', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the speed of the list height.', 'ditty-news-ticker' ),
						'suffix'			=> ' ' . __( 'second(s)', 'ditty-news-ticker' ),	
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 10,
							'step' 	=> 0.25,
						),
						'std'					=> isset( $values['heightSpeed'] ) ? $values['heightSpeed'] : false,
					),
					'autoplay' => array(
						'type'		=> 'radio',
						'id'			=> 'autoplay',
						'name'		=> __( 'Auto Play', 'ditty-news-ticker' ),
						'help'		=> __( 'Auto play the slider', 'ditty-news-ticker' ),
						'inline'	=> true,
						'options'	=> array(
							0 	=> __( 'No', 'ditty-news-ticker' ),
							1 	=> __( 'Yes', 'ditty-news-ticker' ),
						),
						'std'			=> isset( $values['autoplay'] ) ? $values['autoplay'] : false,	
					),
					'autoplayPause' => array(
						'type'	=> 'checkbox',
						'id'		=> 'autoplayPause',
						'name'	=> __( 'Pause Autoplay on Hover', 'ditty-news-ticker' ),
						'label'	=> __( 'Pause the autoplay on mouse over', 'ditty-news-ticker' ),
						'help'	=> __( 'Pause the autoplay on mouse over', 'ditty-news-ticker' ),
						'std'		=> isset( $values['autoplayPause'] ) ? $values['autoplayPause'] : false,
					),
					'autoplaySpeed' => array(
						'type'				=> 'slider',
						'id'					=> 'autoplaySpeed',
						'name'				=> __( 'Auto Play Speed', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the amount of delay between slides', 'ditty-news-ticker' ),
						'suffix'			=> ' ' . __( 'seconds', 'ditty-news-ticker' ),
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 60,
							'step' 	=> 0.25,
						),
						'std'					=> isset( $values['autoplaySpeed'] ) ? $values['autoplaySpeed'] : false,
					),
					'shuffle' => array(
						'type'	=> 'checkbox',
						'id'		=> 'shuffle',
						'name'	=> __( 'Shuffle Items', 'ditty-news-ticker' ),
						'label'	=> __( 'Randomly shuffle items on each page load', 'ditty-news-ticker' ),
						'help'	=> __( 'Randomly shuffle items on each page load', 'ditty-news-ticker' ),
						'std'		=> isset( $values['shuffle'] ) ? $values['shuffle'] : false,
					),
				),
			),
			'initialSettings' => array(
				'type' 							=> 'group',
				'id'								=> 'initSettings',
				'collapsible'				=> true,
				'default_state'			=> 'collapsed',
				'multiple_fields'		=> true,
				'name' 	=> __( 'Initial Settings', 'ditty-news-ticker' ),
				'help' 	=> __( 'Configure the initial display settings.', 'ditty-news-ticker' ),
				'fields' => array(
					'initTransition' => array(
						'type'		=> 'select',
						'id'			=> 'initTransition',
						'name'		=> __( 'Initial Page Transition', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the transition for initial display.', 'ditty-news-ticker' ),
						'options'	=> ditty_slider_transitions(),
						'std'			=> isset( $values['initTransition'] ) ? $values['initTransition'] : false,
					),
					'initTransitionEase' => array(
						'type'		=> 'select',
						'id'			=> 'initTransitionEase',
						'name'		=> __( 'Initial Page Transition Ease', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the easing for initial display.', 'ditty-news-ticker' ),
						'options' => ditty_ease_array(),
						'std'			=> isset( $values['initTransitionEase'] ) ? $values['initTransitionEase'] : false,
					),
					'initTransitionSpeed' => array(
						'type'				=> 'slider',
						'id'					=> 'initTransitionSpeed',
						'name'				=> __( 'Initial Page Transition Speed', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the transition speed for initial display.', 'ditty-news-ticker' ),
						'suffix'			=> ' ' . __( 'second(s)', 'ditty-news-ticker' ),
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 10,
							'step' 	=> 0.25,
						),
						'std'					=> isset( $values['initTransitionSpeed'] ) ? $values['initTransitionSpeed'] : false,
					),
					'initHeightEase' => array(
						'type'		=> 'select',
						'id'			=> 'initHeightEase',
						'name'		=> __( 'Initial Height Ease', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the height easing for initial display.', 'ditty-news-ticker' ),
						'options' => ditty_ease_array(),
						'std'			=> isset( $values['initHeightEase'] ) ? $values['initHeightEase'] : false,
					),
					'initHeightSpeed' => array(
						'type'				=> 'slider',
						'id'					=> 'initHeightSpeed',
						'name'				=> __( 'Initial Height Speed', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the height speed for initial display.', 'ditty-news-ticker' ),
						'suffix'			=> ' ' . __( 'second(s)', 'ditty-news-ticker' ),	
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 10,
							'step' 	=> 0.25,
						),
						'std'					=> isset( $values['initHeightSpeed'] ) ? $values['initHeightSpeed'] : false,
					),
				),
			),
			'arrowSettings' 		=> parent::arrow_settings( $values ),
			'bulletSettings' 		=> parent::bullet_settings( $values ),
			'containerStyles' 	=> parent::container_style_settings( $values ),
			'contentStyles' 		=> parent::content_style_settings( $values ),
			'pageStyles' 				=> parent::page_style_settings( $values ),
			'itemStyles' 				=> parent::item_style_settings( $values ),
		);	
		if ( WP_DEBUG ) {
			$fields['importExportSettings'] = array(
				'type' 							=> 'group',
				'id'								=> 'importExportSettings',
				'collapsible'				=> true,
				'default_state'			=> 'collapsed',
				'multiple_fields'		=> true,
				'name' 							=> __( 'Import/Export', 'ditty-news-ticker' ),
				'help' 							=> __( 'Import or export the display settings.', 'ditty-news-ticker' ),
				'fields' 						=> array(
					'importExport' => array(
						'type'	=> 'html',
						'id'		=> 'importExport',
						'std'		=> parent::import_export_settings( $values ),
					),
				),
			);
		}
		return apply_filters( 'ditty_display_type_fields', $fields, $this->get_type() );
	}
	
	
	/**
	 * Set the metabox defaults
	 * @access  public
	 * @since   3.0
	 */
	public function default_settings() {	
		$defaults = json_decode( '{"spacing":"14","paging":"1","perPage":"10","transition":"fade","transitionEase":"easeInOutQuint","transitionSpeed":"1","heightEase":"easeInOutQuint","heightSpeed":"1","autoplay":"0","autoplaySpeed":"0","arrows":"none","arrowsIconColor":"","arrowsBgColor":"","arrowsPosition":"center","arrowsPadding":{"paddingTop":"","paddingBottom":"20px","paddingLeft":"","paddingRight":""},"bullets":"style1","bulletsColor":"","bulletsColorActive":"","bulletsPosition":"bottomCenter","bulletsSpacing":"2","bulletsPadding":{"paddingTop":"20px","paddingBottom":"","paddingLeft":"","paddingRight":""},"maxWidth":"","bgColor":"","padding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"margin":{"marginTop":"","marginBottom":"","marginLeft":"","marginRight":""},"borderColor":"","borderStyle":"none","borderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"borderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"contentsBgColor":"","contentsPadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"contentsBorderColor":"","contentsBorderStyle":"none","contentsBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"contentsBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"pageBgColor":"","pagePadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"pageBorderColor":"","pageBorderStyle":"none","pageBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"pageBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"itemTextColor":"","itemBgColor":"","itemPadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"itemBorderColor":"","itemBorderStyle":"none","itemBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"itemBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""}}', true );
		return apply_filters( 'ditty_display_default_settings', $defaults, $this->type );
	}
	
	/**
	 * Return an array of default displays
	 * @access  private
	 * @since   3.0
	 */
	public function templates() {		
		$defaults = $this->default_settings();
		
		$templates = array();		
		$templates['default'] = array(
			'label' => __( 'Default List', 'ditty-news-ticker' ),
			'description' => __( 'Default list display', 'ditty-news-ticker' ),
			'settings'		=> $defaults,
			'version'			=> '1.0',
		);	
		$templates['default_slider'] = array(
			'label' 			=> __( 'Default Slider', 'ditty-news-ticker' ),
			'description' => __( 'Default slider display', 'ditty-news-ticker' ),
			'settings'		=> json_decode( '{"spacing":"20","paging":"1","perPage":"1","transition":"slideLeft","transitionEase":"easeInOutQuint","transitionSpeed":"1","heightEase":"easeInOutQuint","heightSpeed":"1","autoplay":"1","autoplaySpeed":"7","arrows":"style1","arrowsIconColor":"","arrowsBgColor":"","arrowsPosition":"center","arrowsPadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"arrowsStatic":"1","bullets":"style1","bulletsColor":"","bulletsColorActive":"","bulletsPosition":"bottomCenter","bulletsSpacing":"2","bulletsPadding":{"paddingTop":"20px","paddingBottom":"","paddingLeft":"","paddingRight":""},"maxWidth":"","bgColor":"","padding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"margin":{"marginTop":"","marginBottom":"","marginLeft":"auto","marginRight":"auto"},"borderColor":"","borderStyle":"none","borderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"borderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"contentsBgColor":"","contentsPadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"40px","paddingRight":"40px"},"contentsBorderColor":"","contentsBorderStyle":"none","contentsBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"contentsBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"pageBgColor":"","pagePadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"pageBorderColor":"","pageBorderStyle":"none","pageBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"pageBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"itemTextColor":"","itemBgColor":"","itemPadding":{"paddingTop":"5px","paddingBottom":"5px","paddingLeft":"5px","paddingRight":"5px"},"itemBorderColor":"","itemBorderStyle":"none","itemBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"itemBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""}}', true ),
			'version'			=> '1.0',
		);
		
		return apply_filters( 'ditty_display_type_templates', $templates, $this->type );
	}

}