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
	public $js_settings = true;

	/**
	 * Return an array of default displays
	 * @access  private
	 * @since   3.0
	 */
	public function templates() {
		$defaults = $this->default_settings();

		$templates = array();
		$templates['default'] = array(
			'label' => __('Default List', 'ditty-news-ticker'),
			'description' => __('Default list display', 'ditty-news-ticker'),
			'settings'		=> $defaults,
			'version'			=> '1.0',
		);
		$templates['default_slider'] = array(
			'label' 			=> __('Default Slider', 'ditty-news-ticker'),
			'description' => __('Default slider display', 'ditty-news-ticker'),
			'settings'		=> json_decode('{"spacing":"20","paging":"1","perPage":"1","transition":"slideLeft","transitionEase":"easeInOutQuint","transitionSpeed":"1","heightEase":"easeInOutQuint","heightSpeed":"1","autoplay":"1","autoplaySpeed":"7","arrows":"style1","arrowsIconColor":"","arrowsBgColor":"","arrowsPosition":"center","arrowsPadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"arrowsStatic":"1","bullets":"style1","bulletsColor":"","bulletsColorActive":"","bulletsPosition":"bottomCenter","bulletsSpacing":"2","bulletsPadding":{"paddingTop":"20px","paddingBottom":"","paddingLeft":"","paddingRight":""},"maxWidth":"","bgColor":"","padding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"margin":{"marginTop":"","marginBottom":"","marginLeft":"auto","marginRight":"auto"},"borderColor":"","borderStyle":"none","borderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"borderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"contentsBgColor":"","contentsPadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"40px","paddingRight":"40px"},"contentsBorderColor":"","contentsBorderStyle":"none","contentsBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"contentsBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"pageBgColor":"","pagePadding":{"paddingTop":"","paddingBottom":"","paddingLeft":"","paddingRight":""},"pageBorderColor":"","pageBorderStyle":"none","pageBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"pageBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""},"itemTextColor":"","itemBgColor":"","itemPadding":{"paddingTop":"5px","paddingBottom":"5px","paddingLeft":"5px","paddingRight":"5px"},"itemBorderColor":"","itemBorderStyle":"none","itemBorderWidth":{"borderTopWidth":"","borderBottomWidth":"","borderLeftWidth":"","borderRightWidth":""},"itemBorderRadius":{"borderTopLeftRadius":"","borderTopRightRadius":"","borderBottomLeftRadius":"","borderBottomRightRadius":""}}', true),
			'version'			=> '1.0',
		);

		return apply_filters('ditty_display_type_templates', $templates, $this->type);
	}
}
