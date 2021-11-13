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
class Ditty_Layout_Type_WP_Post extends Ditty_Layout_Type {
		
	/**
	 * Type
	 *
	 * @since 3.0
	 */
	public $type = 'wp_post';
	
	/**
	 * The defined tags for this type
	 *
	 * @since    3.0
	 * @access   public
	 * @var      array    $tags
	*/
	public function html_tags() {	
		$tags = array(
			'post_id' => array(
				'tag' 				=> 'post_id',
				'description' => __( 'The ID of the post.', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_wp_post_tag_id',
			),
			'post_title' => array(
				'tag' 				=> 'post_title',
				'description' => __( 'The title of the post.', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_wp_post_tag_title',
			),
			'post_date' => array(
				'tag' 				=> 'post_date',
				'description' => __( 'The date of the post.', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_wp_post_tag_date',
			),
			'post_excerpt' => array(
				'tag' 				=> 'post_excerpt',
				'description' => __( 'The excerpt of the post.', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_wp_post_tag_excerpt',
			),
			'post_link' => array(
				'tag' 				=> 'post_link',
				'description' => __( 'The permalink of the post.', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_wp_post_tag_link',
			),
			'post_featured_url' => array(
				'tag' 				=> 'post_featured_url',
				'description' => __( 'The featured image url of the post.', 'ditty-news-ticker' ),
				'func'    		=> 'ditty_layout_wp_post_tag_featured_url',
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
			'title' => array(
				'selector' 				=> '.ditty-item__title',
				'description' => __( 'The post title.', 'ditty-news-ticker' ),
			),
			'date' => array(
				'selector' 				=> '.ditty-item__date',
				'description' => __( 'The post date', 'ditty-news-ticker' ),
			),
			'excerpt' => array(
				'selector' 				=> '.ditty-item__excerpt',
				'description' => __( 'The post excerpt', 'ditty-news-ticker' ),
			),
			'readmore' => array(
				'selector' 				=> '.ditty-item__readmore',
				'description' => __( 'The read more link', 'ditty-news-ticker' ),
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
				'label'				=> __( 'Default Layout', 'ditty-news-ticker' ),
				'description' => __( 'Default layout for WordPress posts.', 'ditty-news-ticker' ),
				'html' 				=> $this->html_default(),
				'css' 				=> $this->css_default(),
				'version'			=> '1.0',
			),
			'default_inline' => array(
				'label'				=> __( 'Default Inline Layout', 'ditty-news-ticker' ),
				'description' => __( 'Default inline layout for WordPress posts.', 'ditty-news-ticker' ),
				'html' 				=> $this->html_default_inline(),
				'css' 				=> $this->css_default_inline(),
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
<a href="{post_link}" class="ditty-item__featured-image" style="background-image:url({post_featured_url})"></a>
<h3 class="ditty-item__title">
	<a href="{post_link}">{post_title}</a>
</h3>
<div class="ditty-item__date">
	{post_date}
</div>
<p class="ditty-item__excerpt">
	{post_excerpt}
</p>
<a class="ditty-item__readmore" href="{post_link}"><?php _e( 'Read More', 'ditty-news-ticker' ); ?></a>		
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
}
.ditty-item__featured-image {
	display: block;
	background-size: cover;
	background-position: center center;
	padding-top: 25%;
	margin: 0 0 10px;
}
.ditty-item__title {
	font-size: 19px;
	line-height: 1.3125;
	font-weight: bold;
	margin: 0 0 5px;
	padding: 0;
}
.ditty-item__title a {
	text-decoration: none;
}
.ditty-item__date {
	font-size: 14px;
	font-weight: bold;
	line-height: 1.3125;
	margin: 0 0 10px;
}
.ditty-item__excerpt {
	font-size: 15px;
	line-height: 1.3125;
	margin: 0 0 10px;
}
.ditty-item__readmore {
	font-size: 15px;
	font-weight: bold;
	line-height: 1.3125;
	margin: 0;
}
		<?php
		// Return the output
		return ob_get_clean();
	}
	
	/**
	 * The default inline html template for this type
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string
	*/
	private function html_default_inline() {
		ob_start();
		?>
<a href="{post_link}" class="ditty-item__link">
	<span class="ditty-item__featured-image" style="background-image:url({post_featured_url})"></span>
	<h3 class="ditty-item__title">
	{post_title}:
</h3>
	<p class="ditty-item__excerpt">
	{post_excerpt}
</p>	
</a>
		<?php
		// Return the output
		return ob_get_clean();
	}
	
	/**
	 * The default inline css template for this type
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string
	*/
	private function css_default_inline() {
		ob_start();
		?>
.ditty-item__elements {
	font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", sans-serif;
	font-size: 15px;
	line-height: 1.3125;
}
.ditty-item__link {
	display: flex;
	align-items: center;
	justify-content: flex-start;
	text-decoration: none;
	color: inherit;
	padding: 5px;
	
	border-radius: 3px;
	transition: background-color .25s ease;
}
.ditty-item__link:hover {
	background: rgba( 0, 0, 0, .1);
}
.ditty-item__featured-image {
	flex: 0 0 auto;
	display: block;
	background-size: cover;
	background-position: center center;
	width: 30px;
	height: 30px;
	padding: 0;
	margin: 0 5px 0 0;
}
.ditty-item__title {
	font-size: 15px;
	line-height: 1.3125;
	font-weight: bold;
	margin: 0 5px 0 0;
	padding: 0;
	white-space: nowrap;
}
.ditty-item__excerpt {
	font-size: 15px;
	line-height: 1.3125;
	padding: 0;
	margin: 0;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}	
		<?php
		// Return the output
		return ob_get_clean();
	}

}