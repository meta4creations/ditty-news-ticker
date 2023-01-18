<?php
/**
 * Ditty Display Item Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Display Item
 * @copyright   Copyright (c) 2023, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1
*/
use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Parser\RegularParser;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Thunder\Shortcode\Syntax\CommonSyntax;
use Thunder\Shortcode\Syntax\Syntax;
use Thunder\Shortcode\Syntax\SyntaxBuilder;

class Ditty_Display_Item_New {
	
	private $css;
	private $css_compiled;
	private $css_selectors;
	private $description;
	private $html;
	private $label;
	private $layout_tags;
	private $layout_id;
	private $item_type;
	private $item_value;
	private $item_meta;
	private $version;

	private $id;
	private $uniq_id;
	private $parent_id;
	private $layout;
	private $layouts;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $prepared_meta, $layouts = false ) {
		$this->id = $prepared_meta['item_id'];
		$this->uniq_id = isset( $prepared_meta['item_uniq_id'] ) ? $prepared_meta['item_uniq_id'] : $prepared_meta['item_id'];
		$this->parent_id = isset( $prepared_meta['parent_id'] ) ? $prepared_meta['parent_id'] : 0;
		$this->item_value = $prepared_meta['item_value'];	
		$this->item_type = $prepared_meta['item_type'];
		if ( isset( $prepared_meta['layout'] ) ) {
			$this->layout = $prepared_meta['layout'];
		} else {
			$layout_value = maybe_unserialize( $prepared_meta['layout_value'] );
			$this->layout = $layout_value['default'];
		}
		 if ( $layouts ) {
			$this->layouts = $layouts;
		 }
	}

	/**
	 * Return the item type
	 * @access public
	 * @since  3.0
	 * @return string $item_type
	 */
	public function get_item_type() {
		return $this->item_type;
	}
	
	/**
	 * Return the item meta
	 * @access public
	 * @since  3.0.13
	 * @return string $item_type
	 */
	public function get_item_meta() {
		return $this->item_meta;
	}

	/**
	 * Return the layout html
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_layout_id() {
		if ( is_array( $this->layout ) ) {
			return $this->uniq_id;
		} else {
			return $this->layout;
		}
	}

	/**
	 * Return the html tags
	 * @access public
	 * @since  3.0.12
	 * @return int $id
	 */
	public function get_layout_tags() {
		if ( ! $this->layout_tags ) {
			$this->layout_tags = ditty_layout_tags( $this->get_item_type(), $this->get_item_value() );
		}
		return $this->layout_tags;
	}

	/**
	 * Return the layout html
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_html() {
		if ( empty( $this->html ) ) {
			if ( is_array( $this->layout ) ) {
				$this->html = $this->layout['html'];
			} else {
				if ( $this->layouts ) {
					if ( is_array( $this->layouts ) && count( $this->layouts ) > 0 ) {
						foreach ( $this->layouts as $layout ) {
							if ( $this->layout === $layout['id'] ) {
								$this->html = $layout['html'];
								break;
							}
						}
					}
				} else {
					$this->html = get_post_meta( $this->layout, '_ditty_layout_html', true );
				}
			}
		}
		return stripslashes( $this->html );
	}
	
	/**
	 * Return the layout css
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_css() {
		if ( empty( $this->css ) ) {
			if ( is_array( $this->layout ) ) {
				$this->css = $this->layout['css'];
			} else {
				if ( $this->layouts ) {
					if ( is_array( $this->layouts ) && count( $this->layouts ) > 0 ) {
						foreach ( $this->layouts as $layout ) {
							if ( $this->layout === $layout['id'] ) {
								$this->html = $layout['css'];
								break;
							}
						}
					}
				} else {
					$this->css = get_post_meta( $this->layout, '_ditty_layout_css', true );
				}
			}
		}
		return stripslashes( $this->css );
	}
	
	/**
	 * Return the compiled layout css
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_css_compiled() {
		if ( empty( $this->css_compiled ) ) {
			$this->css_compiled = Ditty()->layouts->compile_layout_style( $this->css, $this->layout_id );
		}
		return $this->css_compiled;
	}

	/**
	 * Return the passed item value
	 * @access public
	 * @since  3.0.12
	 * @return string $html
	 */
	public function get_item_value() {
		return $this->item_value;
	}
	
	/**
	 * Parse layout atts
	 *
	 * @access private
	 * @since    3.0.16
	 * @var      array	$parsed_atts
	*/
	private function parse_atts( $atts = array(), $s = false ) {
		$parsed_atts = array();
		if ( $s && is_array( $atts ) && count( $atts ) > 0 ) {
			foreach ( $atts as $key => $value ) {
				if ( $custom_value = $s->getParameter( $key ) ) {
					$parsed_atts[$key] = $custom_value;
				} else {
					$parsed_atts[$key] = $value;
				}
			}
		}
		return $parsed_atts;
	}
	
	/**
	 * Render a layout tag
	 *
	 * @access private
	 * @since  3.0
	 * @return html
	 */
	private function render_tag( $tag, $item_type, $data, $atts = array(), $custom_wrapper = false ) {
		if ( ! $output = apply_filters( "ditty_layout_tag_{$tag}", false, $item_type, $data, $atts ) ) {
			return false;
		}
		if ( isset( $atts['wpautop'] ) && 'true' == strval( $atts['wpautop'] ) ) {
			$output = wpautop( $output );
		}
		return ditty_layout_render_tag( $output, "ditty-item__{$tag}", $item_type, $data, $atts, $custom_wrapper );
	}

	/**
	 * Return the layout css
	 * @access public
	 * @since  3.0.18
	 * @return html
	 */
	public function get_layout_css() {
		if ( empty( $this->css_compiled ) ) {
			$this->css_compiled = Ditty()->layouts->compile_layout_style( $this->get_css(), $this->get_layout_id() );
		}
		return $this->css_compiled;
	}
	
	/**
	 * Return the layout html
	 * @access public
	 * @since  3.0.18
	 * @return html
	 */
	public function get_layout_html() {
		$tags		= $this->get_layout_tags();
		$html		= $this->get_html();	
		$data 	= $this->get_item_value();	

		// Return an error if there is one
		if ( isset( $data['ditty_feed_error'] ) ) {
			return $data['ditty_feed_error'];
		}
		
		$handlers = new HandlerContainer();
		if ( is_array( $tags ) && count( $tags ) > 0 ) {
			foreach ( $tags as $i => $tag ) {
				$handlers->add( $tag['tag'], function( ShortcodeInterface $s ) use ( $tag, $data ) {
					$data['item_meta'] = $this->get_item_meta();
					$defaults = isset( $tag['atts'] ) ? $tag['atts'] : array();
					$atts = $this->parse_atts( $defaults, $s );
					$atts = apply_filters( 'ditty_layout_tag_atts', $atts, $tag['tag'], $this->get_item_type(), $data );
					$content = $s->getContent();
					if ( isset( $tag['func'] ) && function_exists( $tag['func'] ) ) {
						return call_user_func( $tag['func'], $tag['tag'], $this->get_item_type(), $data, $atts, $content );
					} else {
						return $this->render_tag( $tag['tag'], $this->get_item_type(), $data, $atts, $content );
					}
				} );
			}
		}
		$syntax = new Syntax( '{', '}', '/', '=', '"' ); // created explicitly
		$processor = new Processor( new RegularParser( $syntax ), $handlers );

		return stripslashes( $processor->process( $html ) );
	}

	public function ditty_data() {
		$data = array(
			'id'	 				=> ( string ) $this->id,
			'uniq_id'	 		=> ( string ) $this->uniq_id,
			'parent_id'	 	=> ( string ) $this->parent_id,
			'html' 				=> $this->get_layout_html(),
			'css'					=> $this->get_layout_css(),
			'layout_id'		=> $this->get_layout_id(),
			'is_disabled' => array_unique( apply_filters( 'ditty_item_disabled', array(), $this->id ) ),
		);
		return $data;
	}
}