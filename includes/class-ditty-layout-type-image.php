<?php

/**
 * Ditty Layout Type WP Post Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Layout Type
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Layout_Type_Image extends Ditty_Layout_Type {
		
	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'image';
	
	/**
	 * The defined tags for this type
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $tags
	*/
	public function html_tags() {	
		$tags = array(
			'image' => array(
				'tag' 				=> 'image',
				'description' => __( 'The rendered image.', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_image',
				'atts'				=> array(
					'wrapper'			=> 'div',
					'before'			=> '',
					'after'				=> '',
					'width'				=> '',
					'height'			=> '',
					'fit'					=> '',
					'link'				=> '',
					'link_target' => '_blank',
					'link_rel'		=> '',
					'class'				=> '',
				),
			),
			'image_url' => array(
				'tag' 				=> 'image_url',
				'description' => __( 'The url of the image', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_image_url',
			),
			'icon' => array(
				'tag' 				=> 'icon',
				'description' => __( 'Display an icon', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_icon',
				'atts'				=> array(
					'wrapper' 		=> 'div',
					'before'			=> '',
					'after'				=> '',
					'link'				=> '',
					'link_target' => '_blank',
					'link_rel'		=> '',
					'class'				=> '',
				),
			),
			'title' => array(
				'tag' 				=> 'title',
				'description' => __( 'The title of the image', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_title',
				'atts'				=> array(
					'wrapper' 		=> 'h3',
					'before'			=> '',
					'after'				=> '',
					'link'				=> '',
					'link_target' => '_blank',
					'link_rel'		=> '',
					'class'				=> '',
				),
			),
			'caption' => array(
				'tag' 				=> 'caption',
				'description' => __( 'The caption of the image', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_caption',
				'atts'				=> array(
					'wrapper' 		=> 'div',
					'wpautop'			=> '',
					'before'			=> '',
					'after'				=> '',
					'link'				=> '',
					'link_target' => '_blank',
					'link_rel'		=> '',
					'class'				=> '',
				),
			),
			'time' => array(
				'tag' 				=> 'time',
				'description' => __( 'The date/time of the image', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_time',
				'atts'				=> array(
					'wrapper' 		=> 'div',
					'format' 			=> get_option( 'date_format' ),
					'ago'					=> '',
					'ago_string' 	=> __( '%s ago', 'ditty-news-ticker' ),
					'before'			=> '',
					'after'				=> '',
					'link'				=> '',
					'link_target' => '_blank',
					'link_rel'		=> '',
					'class'				=> '',
				),
			),
			'user_name' => array(
				'tag' 				=> 'user_name',
				'description' => __( "The user's name", 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_user_name',
				'atts'				=> array(
					'wrapper' 		=> 'div',
					'before'			=> '',
					'after'				=> '',
					'link'				=> 'user', // post, user
					'link_target' => '_blank',
					'link_rel'		=> '',
					'class'				=> '',
				),
			),
			'user_avatar' => array(
				'tag' 				=> 'user_avatar',
				'description' => __( "The user's avatar", 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_tag_user_avatar',
				'atts'				=> array(
					'wrapper'			=> 'div',
					'before'			=> '',
					'after'				=> '',
					'width'				=> '',
					'height'			=> '',
					'fit'					=> '',
					'link'				=> 'user',
					'link_target' => '_blank',
					'link_rel'		=> '',
					'class'				=> '',
				),
			),
			'permalink' => array(
				'tag' 				=> 'permalink',
				'description' => __( 'The permalink to the image', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_image_tag_permalink',
			),
		);
		return apply_filters( 'ditty_layout_html_tags', $tags, $this->type );
	}

	/**
	 * The defined css selectors for this type
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $tags
	*/
	public function css_selectors() {
		$selectors = array(
			'image' => array(
				'selector' 				=> '.ditty-item__image',
				'description' => __( 'The wrapper around the main image.', 'ditty-news-ticker' ),
			),
			'icon' => array(
				'selector' 				=> '.ditty-item__icon',
				'description' => __( 'The wrapper around the icon.', 'ditty-news-ticker' ),
			),
			'title' => array(
				'selector' 				=> '.ditty-item__title',
				'description' => __( 'The wrapper around the title.', 'ditty-news-ticker' ),
			),
			'caption' => array(
				'selector' 				=> '.ditty-item__caption',
				'description' => __( 'The wrapper around the caption.', 'ditty-news-ticker' ),
			),
			'time' => array(
				'selector' 				=> '.ditty-item__time',
				'description' => __( 'The wrapper around the date/time.', 'ditty-news-ticker' ),
			),
			'user_name' => array(
				'selector' 				=> '.ditty-item__user_name',
				'description' => __( 'The wrapper around the user name.', 'ditty-news-ticker' ),
			),
			'user_avatar' => array(
				'selector' 				=> '.ditty-item__user_avatar',
				'description' => __( 'The wrapper around the user avatar.', 'ditty-news-ticker' ),
			),
		);
		return apply_filters( 'ditty_layout_css_selectors', $selectors, $this->type );
	}
	
	/**
	 * Return an array of templates
	 *
	 * @access  private
	 * @since   3.0
	 */
	public function templates() {	
		$templates = array(
			'default' => array(
				'label'				=> __( 'Default Image Layout', 'ditty-news-ticker' ),
				'description' => __( 'Default layout for Images.', 'ditty-news-ticker' ),
				'html' 				=> $this->html_default(),
				'css' 				=> $this->css_default(),
				'version'			=> '1.0',
			),
		);		
		return apply_filters( 'ditty_layout_type_templates', $templates, $this->type );
	}
		
	/**
	 * The default html template for this type
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string
	*/
	private function html_default() {
		ob_start();
		?>
{image link="post"}
{icon}
{caption}
{time}
		<?php
		// Return the output
		return ob_get_clean();
	}
	
	/**
	 * The default css template for this type
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string
	*/
	private function css_default() {
		ob_start();
		?>
.ditty-item__elements {
	position: relative;
	font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", sans-serif;
	font-size: 15px;
	line-height: 1.3125;
}
.ditty-item__elements a {
	text-decoration: none;
}
.ditty-item__image {
	overflow: hidden;
}
.ditty-item__image img {
	display: block;
	line-height: 0;
	transition: transform .75s ease; 
}
.ditty-item__image a:hover img {
	transform: scale(1.05);
}
.ditty-item__icon {
	position: absolute;
	top: 15px;
	left: 15px;
	font-size: 25px;
	line-height: 25px;
	color: #FFF;
	opacity: .8;
	text-shadow: 0 0 2px rgba( 0, 0, 0, .3 );
	pointer-events: none;
}
.ditty-item__icon a {
	color: #FFF;
}
.ditty-tag__heading {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: flex-start;
	padding: 12px 10px 12px;
}
.ditty-item__user_avatar {
	flex: 0 0 auto;
	margin-right: 10px;
}
.ditty-item__user_avatar img {
	display: block;
	line-height: 0;
	border-radius: 50%;
}
.ditty-item__user_name {
	font-weight: 500;
}
.ditty-item__user_name a {
	color: #050505;
}
.ditty-item__time {
	font-size: 13px;
	font-weight: 300;
}
.ditty-item__time a {
	color: #6B6D71;
	text-decoration: none;
}
.ditty-item__time a:hover {
	text-decoration: underline;
}
.ditty-item__caption {
	padding: 15px;
}
.ditty-item__time {
	padding: 15px; 
}
.ditty-item__caption + .ditty-item__time {
	padding-top: 0;
	margin-top: -5px;
}
		<?php
		// Return the output
		return ob_get_clean();
	}
	
}