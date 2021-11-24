<?php

/**
 * Ditty Layout Type Default Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Layout Type Default
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class Ditty_Layout_Type_Default extends Ditty_Layout_Type {
		
	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'default';
	
	/**
	 * The defined tags for this type
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $tags
	*/
	public function html_tags() {	
		$tags = array(
			'content' => array(
				'tag' 				=> 'content',
				'description' => __( 'The value of the text field.', 'ditty-news-ticker' ),
				//'func'    		=> 'ditty_layout_default_tag_content',
				'atts'				=> array(
					'wrapper' => 'div',
					'wpautop' => false,
					'before'	=> '',
					'after'		=> '',
					'class'		=> '',
				),
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
			'content' => array(
				'selector' 				=> '.ditty-default__content',
				'description' => __( 'The class used for the content wrapper element.', 'ditty-news-ticker' ),
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
				'label'				=> __( 'Default', 'ditty-news-ticker' ),
				'description' => __( 'Default layout for Default items.', 'ditty-news-ticker' ),
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
{content}
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
	font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", sans-serif;
	font-size: 15px;
	line-height: 1.3125;
	text-align: left;
}
.ditty-item__link {
	text-decoration: underline;
}
h1, h2, h3, h4, h5, h6 {
	line-height: 1.3125;
	font-weight: bold;
	margin: 0 0 5px;
	padding: 0;
}
h1 {
	font-size: 19px;
}
h2 {
	font-size: 17px;
}
h3, h4, h5, h6 {
	font-size: 15px;
}
p {
	font-size: 15px;
	line-height: 1.3125;
	margin: 0 0 5px;
}
ul {
	list-style: disc;
	padding: 0 0 0 20px;
	margin: 0 0 5px;
}
ol {
	padding: 0 0 0 20px;
	margin: 0 0 5px;
}
li {
	margin: 0 0 5px 0;
}
		<?php
		// Return the output
		return ob_get_clean();
	}

}