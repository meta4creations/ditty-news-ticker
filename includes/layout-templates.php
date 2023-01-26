<?php

/**
 * Return all possible layout templates
 *
 * @since    3.0.18
 * @var      html
*/
function ditty_layout_templates() {
	$layout_templates = array(
		'default' => array(
			'label'				=> __( 'Default Layout', 'ditty-news-ticker' ),
			'description' => __( 'Default layout for custom content.', 'ditty-news-ticker' ),
			'html' 				=> ditty_layout_default_html(),
			'css' 				=> ditty_layout_default_css(),
			'version'			=> '1.0',
		),
		'default_image' => array(
			'label'				=> __( 'Default Image Layout', 'ditty-news-ticker' ),
			'description' => __( 'Default layout for Images.', 'ditty-news-ticker' ),
			'html' 				=> ditty_layout_default_image_html(),
			'css' 				=> ditty_layout_default_image_css(),
			'version'			=> '1.0',
		),
		'default_post' => array(
			'label'				=> __( 'Default Post Layout', 'ditty-news-ticker' ),
			'description' => __( 'Default layout for Posts.', 'ditty-news-ticker' ),
			'html' 				=> ditty_layout_default_post_html(),
			'css' 				=> ditty_layout_default_post_css(),
			'version'			=> '1.2',
		),
	);
	return apply_filters( 'ditty_layout_templates', $layout_templates );
}

/**
 * The default template
 *
 * @since    3.0
 * @var      string
*/
function ditty_layout_default_html() {
	ob_start();
	?>
{content}
	<?php
	return ob_get_clean();
}
function ditty_layout_default_css() {
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
	return ob_get_clean();
}

/**
 *  The default image template
 *
 * @since    3.0
 * @access   private
 * @var      string
*/
function ditty_layout_default_image_html() {
	ob_start();
	?>
{image link="post"}
{icon}
{caption}
{time}
	<?php
	return ob_get_clean();
}
function ditty_layout_default_image_css() {
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
	width: 100%;
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
.ditty-item__caption {
	margin: 10px 0 0;
}
.ditty-item__time {
	font-size: 13px;
	font-weight: 300;
	margin: 10px 0 0;
}
.ditty-item__time a {
	color: #6B6D71;
	text-decoration: none;
}
.ditty-item__time a:hover {
	text-decoration: underline;
}
.ditty-item__caption + .ditty-item__time {
	margin-top: 5px;
}
	<?php
	return ob_get_clean();
}

/**
 *  The default post template
 *
 * @since    3.0.18
 * @access   private
 * @var      string
*/
function ditty_layout_default_post_html() {
	ob_start();
	?>
{image link="post"}
{icon}
<div class="ditty-item-heading">
	{author_avatar width="50px" height="50px" fit="cover" link="author"}
	<div class="ditty-item-heading__content">
		{author_name link="author"}
		{time link="post"}
	</div>
</div>
{title link="post"}
{content}
	<?php
	return ob_get_clean();
}
function ditty_layout_default_post_css() {
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
	margin-bottom: 15px;
}
.ditty-item__image img {
	display: block;
	width: 100%;
	line-height: 0;
	transition: transform .75s ease; 
}
.ditty-item__image a:hover img {
	transform: scale(1.05);
}
.ditty-item__icon {
	display: none;
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
.ditty-item__image + .ditty-item__icon {
	display: block;
}
.ditty-item-heading {
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: flex-start;
	margin-bottom: 15px;
}
.ditty-item__author_avatar {
	flex: 0 0 auto;
	margin-right: 10px;
}
.ditty-item__author_avatar img {
	display: block;
	line-height: 0;
	border-radius: 50%;
}
.ditty-item__author_name {
	font-weight: 500;
}
.ditty-item__author_name a {
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
.ditty-item__title {
	font-size: 18px;
	margin: 0;
}
.ditty-item__content,
.ditty-item__excerpt {
	font-size: 15px;
	line-height: 1.3125;
	margin: 5px 0 0 0;
}
.ditty-item__content p {
	font-size: 15px;
	line-height: 1.3125;
	margin-top: 0;
}
.ditty-item__content p:last-child {
	margin-bottom: 0;
}
	<?php
	return ob_get_clean();
}