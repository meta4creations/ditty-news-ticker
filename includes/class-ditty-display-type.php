<?php

/**
 * Ditty Display Type Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Type
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
class Ditty_Display_Type {

	public $type = 'none';
	public $label;
	public $icon;
	public $description;
	public $metabox;
	public $templates;
	public $js_settings = false;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {

		$display_types = ditty_display_types();
		if (isset($display_types[$this->type])) {
			$this->label = $display_types[$this->type]['label'];
			$this->icon = $display_types[$this->type]['icon'];
			$this->description = $display_types[$this->type]['description'];
		}
		$this->metabox = $this->metabox();
		$this->templates = $this->templates();
	}

	/**
	 * Return the type
	 * @access  public
	 * @since   3.0
	 * @return string $type
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Return the label
	 * @access  public
	 * @since   3.0
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Return the icon
	 * @access  public
	 * @since   3.0
	 * @return string $icon
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Return the description
	 * @access  public
	 * @since   3.0
	 * @return string $description
	 * 
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Return the type
	 * @access  public
	 * @since   3.1
	 * @return string $type
	 */
	public function has_js_fields() {
		return property_exists( $this, 'js_fields' ) ? $this->js_fields : false;
	}

	/**
	 * Setup the fields metabox
	 * @access  public
	 * @since   3.0
	 */
	public function fields() {
	}

	/**
	 * Setup the fields metabox
	 * @access  public
	 * @since   3.0
	 */
	public function metabox() {
	}

	/**
	 * Setup the fields metabox
	 * @access  public
	 * @since   3.0
	 */
	public function default_settings() {
	}

	/**
	 * Setup the display settings
	 * @access  public
	 * @since   3.0.14
	 */
	public function settings($display_values = false, $action = 'render') {
		$values = $this->get_values($display_values);
		$fields = $this->fields($values);

		if ('return' == $action) {
			return ditty_fields($fields, $values, $action);
		} else {
			ditty_fields($fields, $values, $action);
		}
	}

	/**
	 * Update values sent from the editor
	 * @access  public
	 * @since   3.0
	 */
	// TODO: Sanitize display meta
	public function sanitize_settings($values) {
		$fields = $this->fields();
		return ditty_sanitize_fields($fields, $values, "ditty_display_type_{$this->get_type()}");
	}

	/**
	 * Return an array of default displays
	 * @access  private
	 * @since   3.0
	 */
	public function templates() {
		return array();
	}

	/**
	 * Return the templates array
	 * @access  public
	 * @since   3.0
	 */
	public function get_templates() {
		return $this->templates;
	}

	/**
	 * Return an array of default displays
	 * @access  public
	 * @since   3.0
	 */
	public function get_template($id) {
		if (isset($this->templates[$id])) {
			return $this->templates[$id];
		}
	}

	/**
	 * Get values to populate the metabox
	 *
	 * @access  public
	 * @since   3.0
	 */
	public function get_values($display_values = false) {
		$defaults = $this->default_settings();
		if (!$display_values) {
			return $defaults;
		}
		$values = wp_parse_args($display_values, $defaults);
		unset($values['draft_values']);
		return $values;
	}

	/**
	 * Return the display styles
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function import_export_settings($values) {
		$html = '';
		$html .= '<div class="ditty-editor__import-export ditty-editor__import-export--display-type">';
		$html .= '<textarea class="ditty-editor__import-export__field">';
		$html .= json_encode($values);
		$html .= '</textarea>';
		$html .= '<a class="ditty-editor__import-export__update ditty-button" href="#">' . __('Update', 'ditty-news-ticker') . '</a>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * Return arrow settings
	 *
	 * @since   3.0
	 * @var     $settings array
	 */
	public function arrow_settings($values, $id = 'arrowSettings') {
		$settings = array(
			'type' 							=> 'group',
			'id'								=> $id,
			'collapsible'				=> true,
			'default_state'			=> 'collapsed',
			'multiple_fields'		=> true,
			'name' 	=> __('Arrow Navigation Settings', 'ditty-news-ticker'),
			'help' 	=> __('Configure arrow navigation settings.', 'ditty-news-ticker'),
			'fields' => array(
				'arrows' => array(
					'type'		=> 'select',
					'id'			=> 'arrows',
					'name'		=> __('Arrows', 'ditty-news-ticker'),
					'help'		=> __('Set the arrow navigation style', 'ditty-news-ticker'),
					'options'	=> array(
						'none' 		=> __('Hide', 'ditty-news-ticker'),
						'style1' 	=> __('Show', 'ditty-news-ticker'),
					),
					'std'			=> isset($values['arrows']) ? $values['arrows'] : false,
				),
				'arrowsIconColor' => array(
					'type'	=> 'color',
					'id'		=> 'arrowsIconColor',
					'name'	=> __('Arrows Icon Color', 'ditty-news-ticker'),
					'help'	=> __('Add a custom icon color to the arrows', 'ditty-news-ticker'),
					'std'		=> isset($values['arrowsIconColor']) ? $values['arrowsIconColor'] : false,
				),
				'arrowsBgColor' => array(
					'type'	=> 'color',
					'id'		=> 'arrowsBgColor',
					'name'	=> __('Arrows Background Color', 'ditty-news-ticker'),
					'help'	=> __('Add a custom background color to the arrows', 'ditty-news-ticker'),
					'std'		=> isset($values['arrowsBgColor']) ? $values['arrowsBgColor'] : false,
				),
				'arrowsPosition' => array(
					'type'		=> 'select',
					'id'			=> 'arrowsPosition',
					'name'		=> __('Arrows Position', 'ditty-news-ticker'),
					'help'		=> __('Set the position of the arrows', 'ditty-news-ticker'),
					'options'	=> array(
						'flexStart' 	=> __('Top', 'ditty-news-ticker'),
						'center' 			=> __('Center', 'ditty-news-ticker'),
						'flexEnd' 		=> __('Bottom', 'ditty-news-ticker'),
					),
					'std'			=> isset($values['arrowsPosition']) ? $values['arrowsPosition'] : false,
				),
				'arrowsPadding' => array(
					'type'	=> 'spacing',
					'id'		=> 'arrowsPadding',
					'name'	=> __('Arrows Padding', 'ditty-news-ticker'),
					'help'	=> __('Add padding to the arrows container', 'ditty-news-ticker'),
					'std'		=> isset($values['arrowsPadding']) ? $values['arrowsPadding'] : false,
				),
				'arrowsStatic' => array(
					'type'	=> 'checkbox',
					'id'		=> 'arrowsStatic',
					'name'	=> __('Arrows Visibility', 'ditty-news-ticker'),
					'label'	=> __('Keep arrows visible at all times', 'ditty-news-ticker'),
					'help'	=> __('Keep arrows visible at all times', 'ditty-news-ticker'),
					'std'		=> isset($values['arrowsStatic']) ? $values['arrowsStatic'] : false,
				),
			),
		);
		return $settings;
	}

	/**
	 * Return bullet settings
	 *
	 * @since   3.0
	 * @var     $settings array
	 */
	public function bullet_settings($values, $id = 'bulletSettings') {
		$settings = array(
			'type' 							=> 'group',
			'id'								=> $id,
			'collapsible'				=> true,
			'default_state'			=> 'collapsed',
			'multiple_fields'		=> true,
			'name' 	=> __('Bullet Navigation Settings', 'ditty-news-ticker'),
			'help' 	=> __('Configure bullet navigation settings.', 'ditty-news-ticker'),
			'fields' => array(
				'bullets' => array(
					'type'		=> 'select',
					'id'			=> 'bullets',
					'name'		=> __('Bullets', 'ditty-news-ticker'),
					'help'		=> __('Set the bullet navigation style', 'ditty-news-ticker'),
					'options'	=> array(
						'none' 		=> __('Hide', 'ditty-news-ticker'),
						'style1' 	=> __('Show', 'ditty-news-ticker'),
					),
					'std'			=> isset($values['bullets']) ? $values['bullets'] : false,
				),
				'bulletsColor' => array(
					'type'	=> 'color',
					'id'		=> 'bulletsColor',
					'name'	=> __('Bullets Color', 'ditty-news-ticker'),
					'help'	=> __('Add a custom color to the bullets', 'ditty-news-ticker'),
					'std'		=> isset($values['bulletsColor']) ? $values['bulletsColor'] : false,
				),
				'bulletsColorActive' => array(
					'type'	=> 'color',
					'id'		=> 'bulletsColorActive',
					'name'	=> __('Bullets Active Color', 'ditty-news-ticker'),
					'help'	=> __('Add a custom color to the active bullet', 'ditty-news-ticker'),
					'std'		=> isset($values['bulletsColorActive']) ? $values['bulletsColorActive'] : false,
				),
				'bulletsPosition' => array(
					'type'		=> 'select',
					'id'			=> 'bulletsPosition',
					'name'		=> __('Bullets Position', 'ditty-news-ticker'),
					'help'		=> __('Set the position of the bullets', 'ditty-news-ticker'),
					'options'	=> array(
						'topLeft' 			=> __('Top Left', 'ditty-news-ticker'),
						'topCenter' 		=> __('Top Center', 'ditty-news-ticker'),
						'topRight' 			=> __('Top Right', 'ditty-news-ticker'),
						'bottomLeft' 		=> __('Bottom Left', 'ditty-news-ticker'),
						'bottomCenter' 	=> __('Bottom Center', 'ditty-news-ticker'),
						'bottomRight' 	=> __('Bottom Right', 'ditty-news-ticker'),
					),
					'std'			=> isset($values['bulletsPosition']) ? $values['bulletsPosition'] : false,
				),
				'bulletsSpacing' => array(
					'type'				=> 'slider',
					'id'					=> 'bulletsSpacing',
					'name'				=> __('Bullets Spacing', 'ditty-news-ticker'),
					'help'				=> __('Set the amount of space between bullets (in pixels).', 'ditty-news-ticker'),
					'suffix'			=> 'px',
					'js_options'	=> array(
						'min' 	=> 0,
						'max' 	=> 50,
						'step' 	=> 1,
					),
					'std'					=> isset($values['bulletsSpacing']) ? $values['bulletsSpacing'] : false,
				),
				'bulletsPadding' => array(
					'type'	=> 'spacing',
					'id'		=> 'bulletsPadding',
					'name'	=> __('Bullets Padding', 'ditty-news-ticker'),
					'help'	=> __('Add padding to the bullets container', 'ditty-news-ticker'),
					'std'		=> isset($values['bulletsPadding']) ? $values['bulletsPadding'] : false,
				),
			),
		);
		return $settings;
	}

	/**
	 * Return border settings
	 *
	 * @since   3.0
	 * @var     $settings array
	 */
	public function border_settings($values, $prefix = '') {
		$settings = array();
		$prefixed = ('' != $prefix) ? "{$prefix}Border" : 'border';
		$settings["{$prefixed}Color"] = array(
			'type'	=> 'color',
			'id'		=> "{$prefixed}Color",
			'name'	=> __('Border Color', 'ditty-news-ticker'),
			'help' 	=> __('Choose a color for the border.', 'ditty-news-ticker'),
			'std'		=> isset($values["{$prefixed}Color"]) ? $values["{$prefixed}Color"] : false,
		);
		$settings["{$prefixed}Style"] = array(
			'type'		=> 'select',
			'id'			=> "{$prefixed}Style",
			'name'		=> __('Border Style', 'ditty-news-ticker'),
			'help'		=> __('A border style must be set for a border to render.', 'ditty-news-ticker'),
			'options' => ditty_border_styles_array(),
			'std'			=> isset($values["{$prefixed}Style"]) ? $values["{$prefixed}Style"] : false,
		);
		$settings["{$prefixed}Width"] = array(
			'type'	=> 'spacing',
			'id'		=> "{$prefixed}Width",
			'name'	=> __('Border Width', 'ditty-news-ticker'),
			'help' 	=> __('Set custom border widths.', 'ditty-news-ticker'),
			'options' => array(
				'borderTopWidth'		=> __('Top', 'ditty-news-ticker'),
				'borderBottomWidth'	=> __('Bottom', 'ditty-news-ticker'),
				'borderLeftWidth'		=> __('Left', 'ditty-news-ticker'),
				'borderRightWidth'	=> __('Right', 'ditty-news-ticker'),
			),
			'std'		=> isset($values["{$prefixed}Width"]) ? $values["{$prefixed}Width"] : false,
		);
		$settings["{$prefixed}Radius"] = array(
			'type'	=> 'radius',
			'id'		=> "{$prefixed}Radius",
			'name'	=> __('Border Radius', 'ditty-news-ticker'),
			'help' 	=> __('Choose a custom border radius.', 'ditty-news-ticker'),
			'std'		=> isset($values["{$prefixed}Radius"]) ? $values["{$prefixed}Radius"] : false,
		);
		return $settings;
	}

	/**
	 * Return container style settings
	 *
	 * @since   3.0
	 * @var     $settings array
	 */
	public function container_style_settings($values, $id = 'containerStyles', $extra_fields = array()) {
		$base_fields = array(
			'maxWidth' => array(
				'type'	=> 'text',
				'id'		=> 'maxWidth',
				'name'	=> __('Max. Width', 'ditty-news-ticker'),
				'help'	=> __('Set a maximum width for the container', 'ditty-news-ticker'),
				'std'		=> isset($values['maxWidth']) ? $values['maxWidth'] : false,
			),
			'bgColor' => array(
				'type'	=> 'color',
				'id'		=> 'bgColor',
				'name'	=> __('Background Color', 'ditty-news-ticker'),
				'std'		=> isset($values['bgColor']) ? $values['bgColor'] : false,
			),
			'padding' => array(
				'type'	=> 'spacing',
				'id'		=> 'padding',
				'name'	=> __('Padding', 'ditty-news-ticker'),
				'std'		=> isset($values['padding']) ? $values['padding'] : false,
			),
			'margin' => array(
				'type'	=> 'spacing',
				'id'		=> 'margin',
				'name'	=> __('Margin', 'ditty-news-ticker'),
				'options' => array(
					'marginTop'			=> __('Top', 'ditty-news-ticker'),
					'marginBottom'	=> __('Bottom', 'ditty-news-ticker'),
					'marginLeft'		=> __('Left', 'ditty-news-ticker'),
					'marginRight'		=> __('Right', 'ditty-news-ticker'),
				),
				'std'		=> isset($values['margin']) ? $values['margin'] : false,
			),
		);
		$border_fields = $this->border_settings($values);
		$fields = array_merge($base_fields, $border_fields, $extra_fields);
		$settings = array(
			'type' 							=> 'group',
			'id'								=> $id,
			'collapsible'				=> true,
			'default_state'			=> 'collapsed',
			'multiple_fields'		=> true,
			'name' 							=> __('Container Styles', 'ditty-news-ticker'),
			'help' 							=> __('Add custom container styles.', 'ditty-news-ticker'),
			'fields' 						=> $fields,
		);
		return $settings;
	}

	/**
	 * Return title style settings
	 *
	 * @since   3.0.13
	 * @var     $settings array
	 */
	public function title_style_settings($values, $id = 'titleStyles', $extra_fields = array()) {
		$base_fields = array(
			'titleDisplay' => array(
				'type'	=> 'select',
				'id'		=> 'titleDisplay',
				'name'	=> __('Display', 'ditty-news-ticker'),
				'help' 	=> __('Show the Ditty title with your ticker.', 'ditty-news-ticker'),
				'options' => [
					'none' 		=> __('None', 'ditty-news-ticker'),
					'top' 		=> __('Top', 'ditty-news-ticker'),
					'bottom' 	=> __('Bottom', 'ditty-news-ticker'),
					'left'		=> __('Left', 'ditty-news-ticker'),
					'right'		=> __('Right', 'ditty-news-ticker'),
				],
				'std'		=> isset($values['titleDisplay']) ? $values['titleDisplay'] : false,
			),
			'titleElement' => array(
				'type'	=> 'select',
				'id'		=> 'titleElement',
				'name'	=> __('Element', 'ditty-news-ticker'),
				'help' 	=> __('Select the HTML element to use for the title.', 'ditty-news-ticker'),
				'options' => [
					'h1' 	=> 'h1',
					'h2' 	=> 'h2',
					'h3'	=> 'h3',
					'h4'	=> 'h4',
					'h5'	=> 'h5',
					'h6'	=> 'h6',
					'p'		=> 'p',
				],
				'std'		=> isset($values['titleElement']) ? $values['titleElement'] : false,
			),
			'titleElementPosition' => array(
				'type'	=> 'radio',
				'id'		=> 'titleElementPosition',
				'name'	=> __('Element Position', 'ditty-news-ticker'),
				'help' 	=> __('Set the position of the element within the title area.', 'ditty-news-ticker'),
				'options'	=> [
					'start' 	=> __('Start', 'ditty-news-ticker'),
					'center' 	=> __('Center', 'ditty-news-ticker'),
					'end' 		=> __('End', 'ditty-news-ticker'),
				],
				'inline' => true,
				'std'		=> isset($values['titleElementPosition']) ? $values['titleElementPosition'] : false,
			),
			'titleFontSize' => array(
				'type'	=> 'text',
				'id'		=> 'titleFontSize',
				'name'	=> __('Font Size', 'ditty-news-ticker'),
				'help' 	=> __('Set a custom font size.', 'ditty-news-ticker'),
				'std'		=> isset($values['titleFontSize']) ? $values['titleFontSize'] : false,
			),
			'titleLineHeight' => array(
				'type'	=> 'text',
				'id'		=> 'titleLineHeight',
				'name'	=> __('Line Height', 'ditty-news-ticker'),
				'help' 	=> __('Set a custom line height.', 'ditty-news-ticker'),
				'std'		=> isset($values['titleLineHeight']) ? $values['titleLineHeight'] : false,
			),
			'titleColor' => array(
				'type'	=> 'color',
				'id'		=> 'titleColor',
				'name'	=> __('Text Color', 'ditty-news-ticker'),
				'help' 	=> __('Set a custom font color.', 'ditty-news-ticker'),
				'std'		=> isset($values['titleColor']) ? $values['titleColor'] : false,
			),
			'titleBgColor' => array(
				'type'	=> 'color',
				'id'		=> 'titleBgColor',
				'name'	=> __('Background Color', 'ditty-news-ticker'),
				'help' 	=> __('Add a background title to the title area.', 'ditty-news-ticker'),
				'std'		=> isset($values['titleBgColor']) ? $values['titleBgColor'] : false,
			),
			'titleMargin' => array(
				'type'	=> 'spacing',
				'id'		=> 'titleMargin',
				'name'	=> __('Margin', 'ditty-news-ticker'),
				'help' 	=> __('Add custom margins around the title area.', 'ditty-news-ticker'),
				'options' => array(
					'marginTop'			=> __('Top', 'ditty-news-ticker'),
					'marginBottom'	=> __('Bottom', 'ditty-news-ticker'),
					'marginLeft'		=> __('Left', 'ditty-news-ticker'),
					'marginRight'		=> __('Right', 'ditty-news-ticker'),
				),
				'std'		=> isset($values['titleMargin']) ? $values['titleMargin'] : false,
			),
			'titlePadding' => array(
				'type'	=> 'spacing',
				'id'		=> 'titlePadding',
				'name'	=> __('Padding', 'ditty-news-ticker'),
				'help' 	=> __('Add custom padding around the title area.', 'ditty-news-ticker'),
				'options' => array(
					'paddingTop'		=> __('Top', 'ditty-news-ticker'),
					'paddingBottom'	=> __('Bottom', 'ditty-news-ticker'),
					'paddingLeft'		=> __('Left', 'ditty-news-ticker'),
					'paddingRight'	=> __('Right', 'ditty-news-ticker'),
				),
				'std'		=> isset($values['titlePadding']) ? $values['titlePadding'] : false,
			),
		);
		$border_fields = $this->border_settings($values, 'title');
		$fields = array_merge($base_fields, $border_fields, $extra_fields);
		$settings = array(
			'type' 							=> 'group',
			'id'								=> $id,
			'collapsible'				=> true,
			'default_state'			=> 'collapsed',
			'multiple_fields'		=> true,
			'name' 							=> __('Title Styles', 'ditty-news-ticker'),
			'help' 							=> __('Add custom title styles.', 'ditty-news-ticker'),
			'fields' 						=> $fields,
		);
		return $settings;
	}

	/**
	 * Return content style settings
	 *
	 * @since   3.0
	 * @var     $settings array
	 */
	public function content_style_settings($values, $id = 'content_styles', $extra_fields = array()) {
		$base_fields = array(
			'contentsBgColor' => array(
				'type'	=> 'color',
				'id'		=> 'contentsBgColor',
				'name'	=> __('Background Color', 'ditty-news-ticker'),
				'std'		=> isset($values['contentsBgColor']) ? $values['contentsBgColor'] : false,
			),
			'contentsPadding' => array(
				'type'	=> 'spacing',
				'id'		=> 'contentsPadding',
				'name'	=> __('Padding', 'ditty-news-ticker'),
				'std'		=> isset($values['contentsPadding']) ? $values['contentsPadding'] : false,
			),
		);
		$border_fields = $this->border_settings($values, 'contents');
		$fields = array_merge($base_fields, $border_fields, $extra_fields);
		$settings = array(
			'type' 							=> 'group',
			'id'								=> $id,
			'collapsible'				=> true,
			'default_state'			=> 'collapsed',
			'multiple_fields'		=> true,
			'name' 							=> __('Content Styles', 'ditty-news-ticker'),
			'help' 							=> __('Add custom content styles.', 'ditty-news-ticker'),
			'fields' 						=> $fields,
		);
		return $settings;
	}

	/**
	 * Return page style settings
	 *
	 * @since   3.0
	 * @var     $settings array
	 */
	public function page_style_settings($values, $id = 'pageStyles', $extra_fields = array()) {
		$base_fields = array(
			'pageBgColor' => array(
				'type'	=> 'color',
				'id'		=> 'pageBgColor',
				'name'	=> __('Background Color', 'ditty-news-ticker'),
				'std'		=> isset($values['pageBgColor']) ? $values['pageBgColor'] : false,
			),
			'pagePadding' => array(
				'type'	=> 'spacing',
				'id'		=> 'pagePadding',
				'name'	=> __('Padding', 'ditty-news-ticker'),
				'std'		=> isset($values['pagePadding']) ? $values['pagePadding'] : false,
			),
		);
		$border_fields = $this->border_settings($values, 'page');
		$fields = array_merge($base_fields, $border_fields, $extra_fields);
		$settings = array(
			'type' 							=> 'group',
			'id'								=> $id,
			'collapsible'				=> true,
			'default_state'			=> 'collapsed',
			'multiple_fields'		=> true,
			'name' 							=> __('Page Styles', 'ditty-news-ticker'),
			'help' 							=> __('Add custom page styles.', 'ditty-news-ticker'),
			'fields' 						=> $fields,
		);
		return $settings;
	}

	/**
	 * Return item style settings
	 *
	 * @since   3.0
	 * @var     $settings array
	 */
	public function item_style_settings($values, $id = 'itemStyles', $extra_fields = array()) {
		$base_fields = array(
			'itemTextColor' => array(
				'type'	=> 'color',
				'id'		=> 'itemTextColor',
				'name'	=> __('Text Color', 'ditty-news-ticker'),
				'std'		=> isset($values['itemTextColor']) ? $values['itemTextColor'] : false,
			),
			'itemBgColor' => array(
				'type'	=> 'color',
				'id'		=> 'itemBgColor',
				'name'	=> __('Background Color', 'ditty-news-ticker'),
				'std'		=> isset($values['itemBgColor']) ? $values['itemBgColor'] : false,
			),
			'itemPadding' => array(
				'type'	=> 'spacing',
				'id'		=> 'itemPadding',
				'name'	=> __('Padding', 'ditty-news-ticker'),
				'std'		=> isset($values['itemPadding']) ? $values['itemPadding'] : false,
			),
		);
		$border_fields = $this->border_settings($values, 'item');
		$fields = array_merge($base_fields, $border_fields, $extra_fields);
		$settings = array(
			'type' 							=> 'group',
			'id'								=> $id,
			'collapsible'				=> true,
			'default_state'			=> 'collapsed',
			'multiple_fields'		=> true,
			'name' 							=> __('Item Styles', 'ditty-news-ticker'),
			'help' 							=> __('Add custom item styles.', 'ditty-news-ticker'),
			'fields' 						=> $fields,
		);
		return $settings;
	}
}
