<?php
/**
 * Ditty News Ticker API for creating Layout template tags
 *
 * Layout tags are wrapped in { }
 *
 * A few examples:
 *
 * {content}
 * {link}
 * {avatar}
 *
 *
 * To replace tags in content, use: dnt_do_layout_tags( $content, avatar );
 *
 * To add tags, use: dnt_add_layout_tag( $tag, $description, $func ). Be sure to wrap dnt_add_layout_tag()
 * in a function hooked to the 'dnt_add_layout_tags' action
 *
 * @package     DNT
 * @subpackage  Layouts
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 * @author      Barry Kooij
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DNT_Layout_Template_Tags {

	/**
	 * Container for storing all tags
	 *
	 * @since 3.0
	 */
	private $tags = array();

	/**
	 * Layout type
	 *
	 * @since 3.0
	 */
	private $layout_type;

	/**
	 * Add a layout tag
	 *
	 * @since 3.0
	 *
	 * @param string   $tag  Layout tag to be replace ticker layout
	 * @param callable $func Hook to run when layout tag is found
	 */
	public function add( $layout, $tag, $description, $func ) {
		if( is_callable( $func ) ) {
			if( !isset($this->tags[$layout]) ) {
				$this->tags[$layout] = array();
			}
			$this->tags[$layout][$tag] = array(
				'tag'         => $tag,
				'description' => $description,
				'func'        => $func
			);
		}
	}

	/**
	 * Remove an email tag
	 *
	 * @since 3.0
	 *
	 * @param string $tag Layout tag to remove hook from
	 */
	public function remove( $layout, $tag ) {
		unset( $this->tags[$layout][$tag] );
	}

	/**
	 * Check if $tag is a registered layout tag
	 *
	 * @since 3.0
	 *
	 * @param string $tag Layout tag that will be searched
	 *
	 * @return bool
	 */
	public function layout_tag_exists( $layout, $tag ) {
		
		if( !array_key_exists( $layout, $this->tags ) ) {
			return false;
		}
		
		return array_key_exists( $tag, $this->tags[$layout] );
	}

	/**
	 * Returns a list of all layout tags
	 *
	 * @since 3.0
	 *
	 * @return array
	 */
	public function get_tags( $layout=false ) {
		
		if( $layout ) {
			return $this->tags[$layout];
		}
		
		return $this->tags;
	}

	/**
	 * Search content for layout tags and filter layout tags through their hooks
	 *
	 * @param string $content Content to search for layout tags
	 * @param string $layout_type The layout type
	 *
	 * @since 3.0
	 *
	 * @return string Content with layout tags filtered out.
	 */
	public function do_tags( $content, $layout_type ) {

		// Check if there is atleast one tag added
		if( empty( $this->tags ) || ! is_array( $this->tags ) ) {
			return $content;
		}

		$this->layout_type = $layout_type;

		$new_content = preg_replace_callback( "/{([A-z0-9\-\_]+)}/s", array( $this, 'do_tag' ), $content );

		$this->layout_type = null;

		return $new_content;
	}

	/**
	 * Do a specific tag, this function should not be used. Please use dnt_do_layout_tags instead.
	 *
	 * @since 3.0
	 *
	 * @param $m message
	 *
	 * @return mixed
	 */
	public function do_tag( $m ) {

		// Get tag
		$tag = $m[1];

		// Return tag if tag not set
		if ( ! $this->layout_tag_exists( $tag ) ) {
			return $m[0];
		}

		return call_user_func( $this->tags[$tag]['func'], $this->layout_type, $tag );
	}

}

/**
 * Add a layout tag
 *
 * @since 3.0
 *
 * @param string   $tag  Layout tag to be replace in ticker layout
 * @param callable $func Hook to run when layout tag is found
 */
function dnt_add_layout_tag( $layout, $tag, $description, $func ) {
	DNT()->layout_tags->add( $layout, $tag, $description, $func );
}

/**
 * Remove a layout tag
 *
 * @since 3.0
 *
 * @param string $tag Layout tag to remove hook from
 */
function dnt_remove_layout_tag( $tag ) {
	DNT()->layout_tags->remove( $tag );
}

/**
 * Check if $tag is a registered layout tag
 *
 * @since 3.0
 *
 * @param string $tag Layout tag that will be searched
 *
 * @return bool
 */
function dnt_layout_tag_exists( $tag ) {
	return DNT()->layout_tags->layout_tag_exists( $tag );
}

/**
 * Get all layout tags
 *
 * @since 3.0
 *
 * @return array
 */
function dnt_get_layout_tags( $layout ) {
	return DNT()->layout_tags->get_tags( $layout );
}

/**
 * Get a formatted HTML list of all available layout tags
 *
 * @since 3.0
 *
 * @return string
 */
function dnt_get_layout_tags_list( $layout ) {
	// The list
	$list = '';

	// Get all tags
	$layout_tags = (array) dnt_get_layout_tags( $layout );

	// Check
	if ( count( $layout_tags ) > 0 ) {

		// Loop
		foreach ( $layout_tags as $layout_tag ) {

			// Add layout tag to list
			$list .= '{' . $layout_tag['tag'] . '} - ' . $layout_tag['description'] . '<br/>';

		}

	}

	// Return the list
	return $list;
}

/**
 * Search content for layout tags and filter layout tags through their hooks
 *
 * @param string $content Content to search for layout tags
 * @param string $layout_type The layout type
 *
 * @since 3.0
 *
 * @return string Content with layout tags filtered out.
 */
function dnt_do_layout_tags( $content, $layout_type ) {

	// Replace all tags
	$content = DNT()->layout_tags->do_tags( $content, $layout_type );

	// Return content
	return $content;
}

/**
 * Load layout tags
 *
 * @since 3.0
 */
function dnt_load_layout_tags() {
	do_action( 'dnt_add_layout_tags' );
}
add_action( 'init', 'dnt_load_layout_tags', -999 );

/**
 * Add default EDD email template tags
 *
 * @since 1.9
 */
function dnt_setup_layout_tags() {

	// Setup default tags array
	$layout_tags = array(
		array(
			'layout'			=> 'default',
			'tag'         => 'text',
			'description' => __( 'The text added to a tick', 'ditty-news-ticker' ),
			'function'    => 'dnt_layout_tag_default_text'
		),
		array(
			'layout'			=> 'default',
			'tag'         => 'link',
			'description' => __( 'The link added to a tick', 'ditty-news-ticker' ),
			'function'    => 'dnt_layout_tag_default_link'
		),
		array(
			'layout'			=> 'default',
			'tag'         => 'target',
			'description' => __( 'The link target added to a tick', 'ditty-news-ticker' ),
			'function'    => 'dnt_layout_tag_default_target'
		),
		array(
			'layout'			=> 'default',
			'tag'         => 'nofollow',
			'description' => __( 'The link nofollow attribute added to a tick', 'ditty-news-ticker' ),
			'function'    => 'dnt_layout_tag_default_nofollow'
		),
		array(
			'layout'			=> 'default',
			'tag'         => 'link_open',
			'description' => __( 'The opening tag for a link added to a tick (includes target and nofollow)', 'ditty-news-ticker' ),
			'function'    => 'dnt_layout_tag_default_link_open'
		),
		array(
			'layout'			=> 'default',
			'tag'         => 'link_close',
			'description' => __( 'The closing tag for a link added to a tick', 'ditty-news-ticker' ),
			'function'    => 'dnt_layout_tag_default_link_close'
		),
	);

	// Apply dnt_layout_tags filter
	$layout_tags = apply_filters( 'dnt_layout_tags', $layout_tags );

	// Add email tags
	foreach ( $layout_tags as $layout_tag ) {
		dnt_add_layout_tag( $layout_tag['layout'], $layout_tag['tag'], $layout_tag['description'], $layout_tag['function'] );
	}
}
add_action( 'dnt_add_layout_tags', 'dnt_setup_layout_tags' );

/**
 * Layout template tag: text
 * The text added to a tick
 *
 * @param string $layout_type
 *
 * @return string text
 */
function dnt_layout_tag_default_text( $layout_type ) {

	return $email_name['name'];
}

/**
 * Layout template tag: link
 * The link added to a tick
 *
 * @param string $layout_type
 *
 * @return string link
 */
function dnt_layout_tag_default_link( $layout_type ) {

	return $email_name['name'];
}

/**
 * Layout template tag: target
 * The link target added to a tick
 *
 * @param string $layout_type
 *
 * @return string target
 */
function dnt_layout_tag_default_target( $layout_type ) {

	return $email_name['name'];
}

/**
 * Layout template tag: nofollow
 * The link nofollow attribute added to a tick
 *
 * @param string $layout_type
 *
 * @return string nofollow
 */
function dnt_layout_tag_default_nofollow( $layout_type ) {

	return $email_name['name'];
}

/**
 * Layout template tag: link_open
 * The opening tag for a link added to a tick
 *
 * @param string $layout_type
 *
 * @return string link open
 */
function dnt_layout_tag_default_link_open( $layout_type ) {

	return $email_name['name'];
}

/**
 * Layout template tag: link_close
 * The closing tag for a link added to a tick
 *
 * @param string $layout_type
 *
 * @return string link close
 */
function dnt_layout_tag_default_link_close( $layout_type ) {

	return $email_name['name'];
}