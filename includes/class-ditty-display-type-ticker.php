<?php

/**
 * Ditty Display Type Ticker Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Type Ticker
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Display_Type_Ticker extends Ditty_Display_Type {
		
	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'ticker';
	
	/**
	 * Setup the fields
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function fields( $values = array() ) {	
		$fields = array(
			'tickerSettings' => array(
				'type' 							=> 'group',
				'id'								=> 'tickerSettings',
				'collapsible'				=> true,
				'default_state'			=> 'expanded',
				'multiple_fields'		=> true,
				'name' 	=> __( 'General Settings', 'ditty-news-ticker' ),
				'help' 	=> __( 'Configure the general functionality settings.', 'ditty-news-ticker' ),
				'fields' => array(
					'direction' => array(
						'type'		=> 'radio',
						'id'			=> 'direction',
						'name'		=> __( 'Direction', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the direction of the ticker.', 'ditty-news-ticker' ),
						'options'	=> array(
							'left' 	=> __( 'Left', 'ditty-news-ticker' ),
							'right' => __( 'Right', 'ditty-news-ticker' ),
							'down' 	=> __( 'Down', 'ditty-news-ticker' ),
							'up' 		=> __( 'Up', 'ditty-news-ticker' ),
						),
						'inline'	=> true,
						'std'			=> isset( $values['direction'] ) ? $values['direction'] : false,		
					),
					'spacing' => array(
						'type'				=> 'slider',
						'id'					=> 'spacing',
						'name'				=> __( 'Spacing', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the amount of space between items (in pixels).', 'ditty-news-ticker' ),
						'suffix'			=> 'px',
						'js_options'	=> array(
							'min' 		=> 0,
							'max' 		=> 100,
							'step' 		=> 1,
							'postfix' => 'px',
						),
						'std'					=> isset( $values['spacing'] ) ? $values['spacing'] : false,
					),
					'speed' => array(
						'type'				=> 'slider',
						'id'					=> 'speed',
						'name'				=> __( 'Speed', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the speed of the ticker.', 'ditty-news-ticker' ),
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 50,
							'step' 	=> 1,
						),
						'std'					=> isset( $values['speed'] ) ? $values['speed'] : false,
					),
					'minHeight' => array(
						'type'				=> 'text',
						'id'					=> 'minHeight',
						'name'				=> __( 'Min. Height', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the minimum height of the Ditty for vertical scrolling tickers.', 'ditty-news-ticker' ),
						'std'					=> isset( $values['minHeight'] ) ? $values['minHeight'] : false,
					),
					'maxHeight' => array(
						'type'				=> 'text',
						'id'					=> 'maxHeight',
						'name'				=> __( 'Max. Height', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the maximum height of the Ditty for vertical scrolling tickers.', 'ditty-news-ticker' ),
						'std'					=> isset( $values['maxHeight'] ) ? $values['maxHeight'] : false,
					),
					'hoverPause' => array(
						'type'	=> 'checkbox',
						'id'		=> 'hoverPause',
						'name'	=> __( 'Hover Pause', 'ditty-news-ticker' ),
						'label'	=> __( 'Pause the ticker on mouse over', 'ditty-news-ticker' ),
						'help'	=> __( 'Pause the ticker on mouse over.', 'ditty-news-ticker' ),
						'std'		=> isset( $values['hoverPause'] ) ? $values['hoverPause'] : false,
					),
					'scrollInit' => array(
						'type'		=> 'radio',
						'id'			=> 'scrollInit',
						'name'		=> __( 'Initial Display', 'ditty-news-ticker' ),
						'help'		=> __( 'Choose how the ticker should initialize', 'ditty-news-ticker' ),
						'options'	=> array(
							'empty' 	=> __( 'Empty', 'ditty-news-ticker' ),
							'filled' 	=> __( 'Filled', 'ditty-news-ticker' ),
						),
						'inline'	=> true,
						'std'			=> isset( $values['scrollInit'] ) ? $values['scrollInit'] : false,
					),
					'scrollDelay' => array(
						'type'				=> 'slider',
						'id'					=> 'scrollDelay',
						'name'				=> __( 'Scroll Delay', 'ditty-news-ticker' ),
						'help'				=> __( 'Delay the start of scrolling for filled tickers', 'ditty-news-ticker' ),
						'suffix'			=> ' ' . __( 'seconds', 'ditty-news-ticker' ),
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 10,
							'step' 	=> 0.25,
						),
						'std'					=> isset( $values['scrollDelay'] ) ? $values['scrollDelay'] : false,	
					),
					'heightEase' => array(
						'type'		=> 'select',
						'id'			=> 'heightEase',
						'name'		=> __( 'Height Ease', 'ditty-news-ticker' ),
						'help'		=> __( 'Set the easing of the ticker height.', 'ditty-news-ticker' ),
						'options' => ditty_ease_array(),
						'std'			=> isset( $values['heightEase'] ) ? $values['heightEase'] : false,		
					),
					'heightSpeed' => array(
						'type'				=> 'slider',
						'id'					=> 'heightSpeed',
						'name'				=> __( 'Height Speed', 'ditty-news-ticker' ),
						'help'				=> __( 'Set the speed of the ticker height.', 'ditty-news-ticker' ),
						'suffix'			=> ' ' . __( 'second(s)', 'ditty-news-ticker' ),
						'js_options'	=> array(
							'min' 	=> 0,
							'max' 	=> 10,
							'step' 	=> 0.25,
						),
						'std'					=> isset( $values['heightSpeed'] ) ? $values['heightSpeed'] : false,
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
			'containerStyles' 	=> parent::container_style_settings( $values ),
			'contentStyles' 		=> parent::content_style_settings( $values ),
			'itemStyles' 				=> parent::item_style_settings( $values, false, array(
				'itemMaxWidth' => array(
					'type'	=> 'text',
					'id'		=> 'itemMaxWidth',
					'name'	=> __( 'Max Width', 'ditty-news-ticker' ),
					'help'	=> __( 'Set a maximum width for items', 'ditty-news-ticker' ),
					'std'		=> isset( $values['itemMaxWidth'] ) ? $values['itemMaxWidth'] : false,
				),
			) ),
			'importExportDivider' => array(
				'type' 	=> 'divider',
				'id'		=> 'importExportDivider',
				'name' 	=> __( 'Import/Export', 'ditty-news-ticker' ),
				'help' 	=> __( 'Import or export the display settings.', 'ditty-news-ticker' ),
			),
			'importExport' => array(
				'type'	=> 'html',
				'id'		=> 'importExport',
				'std'		=> parent::import_export_settings( $values ),
			),
		);
		return $fields;
	}
	
	/**
	 * Set the metabox defaults
	 * @access  public
	 * @since   3.0
	 */
	public function default_settings() {	
		
		$defaults = json_decode( '{"direction":"left","spacing":"25","speed":"10","hoverPause":"1","scrollInit":"empty","scrollDelay":"3","heightEase":"easeInOutQuint","heightSpeed":"1.5","maxWidth":"","bgColor":"","padding":{"paddingTop":"5px","paddingBottom":"5px","paddingLeft":"5px","paddingRight":"5px"},"margin":{"marginTop":"","marginBottom":"","marginLeft":"","marginRight":""},"borderColor":"","borderStyle":"none","borderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"borderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"contentsBgColor":"rgba(255, 255, 255, 0.5)","contentsPadding":{"paddingTop":"10px","paddingBottom":"10px","paddingLeft":"10px","paddingRight":"10px"},"contentsBorderColor":"","contentsBorderStyle":"none","contentsBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"contentsBorderRadius":{"borderTopLeftRadius":"3px","borderTopRightRadius":"3px","borderBottomLeftRadius":"3px","borderBottomRightRadius":"3px"},"itemTextColor":"","itemBgColor":"","itemPadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"itemBorderColor":"","itemBorderStyle":"none","itemBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"itemBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"itemMaxWidth":"400px"}', true );
			
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
			'label'				=> __( 'Default Ticker', 'ditty-news-ticker' ),
			'description' => __( 'Default ticker display', 'ditty-news-ticker' ),
			'settings'		=> $defaults,
			'version'			=> '1.0',
		);	
		return apply_filters( 'ditty_display_type_templates', $templates, $this->type );
	}

}