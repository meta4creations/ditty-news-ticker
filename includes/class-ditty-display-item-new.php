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
	private $id;
	private $uniq_id;
	private $parent_id;
	private $item_meta;
	private $item_type;
	private $item_value;
	private $layout;
	private $variation_id;
	private $layout_id;
	private $layout_tags;
	private $css_compiled;
	private $has_error;
	private $custom_classes;
	

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $prepared_meta, $layouts = false ) {
		$this->item_meta = $prepared_meta;
		$this->id = $prepared_meta['item_id'];
		$this->uniq_id = isset( $prepared_meta['item_uniq_id'] ) ? $prepared_meta['item_uniq_id'] : $prepared_meta['item_id'];
		$this->parent_id = isset( $prepared_meta['parent_id'] ) ? $prepared_meta['parent_id'] : 0;
		$this->item_value = $prepared_meta['item_value'];	
		$this->item_type = $prepared_meta['item_type'];
		$this->has_error = isset( $prepared_meta['has_error'] ) ? $prepared_meta['has_error'] : false;
		$this->custom_classes = isset( $prepared_meta['custom_classes'] ) ? $prepared_meta['custom_classes'] : false;
		$this->configure_layout( $prepared_meta, $layouts );
	}

	/**
	 * Return the item type
	 * @access public
	 * @since  3.0
	 * @return string $item_type
	 */
	private function configure_layout( $meta, $layouts = false ) {
		if ( isset( $meta['layout'] ) ) {
			if ( is_array( $meta['layout'] ) ) {
				$layout = $meta['layout'];
			} else {
				$layout = ( '{' == substr( $meta['layout'], 0, 1 ) ) ? json_decode( $meta['layout'], true ) : $meta['layout'];
			}		
		} else {
			$layout_value = maybe_unserialize( $meta['layout_value'] );
			$layout = 0;
			if ( isset( $layout_value['default'] ) ) {
				$layout = is_array( $layout_value['default'] ) ? $layout_value['default'] : ( ( '{' == substr( $layout_value['default'], 0, 1 ) ) ? json_decode( $layout_value['default'], true ) : $layout_value['default'] );
			}
		}
		
		if ( is_array( $layout ) ) {
			$variation_id = isset( $meta['layout_variation'] ) ? $meta['layout_variation'] : 'default';
			$this->layout_id = "{$this->id}_{$variation_id}";
			$this->layout = $layout;
		} else {
			$this->layout_id = $layout;
			if ( $layouts ) {
				if ( is_array( $layouts ) && count( $layouts ) > 0 ) {
					foreach ( $layouts as $layout_obj ) {
						if ( $layout == $layout_obj['id'] ) {
							$this->layout = array(
								'html' => $layout_obj['html'],
								'css' => $layout_obj['css'],
							);
							break;
						}
					}
				}
			} else {
				$this->layout = array(
					'html' => get_post_meta( $layout , '_ditty_layout_html', true ),
					'css' => get_post_meta( $layout , '_ditty_layout_css', true ),
				);
			}
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
		return $this->layout_id;
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
		if ( ! empty( $this->layout ) ) {
			return stripslashes( $this->layout['html'] );
		}
	}
	
	/**
	 * Return the layout css
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_css() {
		if ( ! empty( $this->layout ) ) {
			return stripslashes( $this->layout['css'] );
		}
	}
	
	/**
	 * Return the compiled layout css
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_css_compiled() {
		if ( empty( $this->css_compiled ) ) {
			$this->css_compiled = Ditty()->layouts->compile_layout_style( $this->get_css(), $this->layout_id );
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

	/**
	 * Return custom classes
	 *
	 * @access private
	 * @since  3.0
	 * @return string $classes
	 */
	private function get_custom_classes() {	
		if ( $this->custom_classes ) {
			$classes = explode( ' ', $this->custom_classes );
			return $classes;
		}
	}

	/**
	 * Setup the item classes
	 *
	 * @access private
	 * @since  3.0
	 * @return string $classes
	 */
	private function get_classes() {	
		$classes = array();
		$classes[] = 'ditty-item';
		$classes[] = 'ditty-item--' . esc_attr( $this->id );
		if ( $this->id != $this->uniq_id ) {
			$classes[] = 'ditty-item--' . esc_attr( $this->uniq_id );
		}
		$classes[] = 'ditty-item-type--' . esc_attr( $this->item_type );
		if ( $this->layout_id ) {
			$classes[] = 'ditty-layout--' . esc_attr( $this->layout_id );
		} else {
			$classes[] = 'ditty-layout--default';
		}
		if ( $this->has_error ) {
			$classes[] = 'ditty-item--error';
		}
		if ( $custom_classes = $this->get_custom_classes() ) {
			$classes = array_merge( $classes, $custom_classes );
		}		
		$classes = apply_filters( 'ditty_display_item_classes', $classes, $this->id );	
		return implode( ' ', $classes );
	}

	/**
	 * Render item html
	 *
	 * @access private
	 * @since  3.1
	 * @return html
	 */
	private function render_html() {
		$atts = array(
			'class' 						=> $this->get_classes(),
			'data-item_id' 			=> $this->id,
			'data-item_uniq_id' => $this->uniq_id,
			'data-parent_id' 		=> $this->parent_id,
			'data-item_type' 		=> $this->item_type,
			'data-layout_id' 		=> $this->layout_id,
		);
		
		$html = '<div ' . ditty_attr_to_html( $atts ) . '>';	
			$html .= '<div class="ditty-item__elements">';
				$html .= $this->get_layout_html();
			$html .= '</div>';
		$html .= '</div>';

		// Filter the html
		return apply_filters( 'ditty_render_item', $html, $this );
	}

	public function ditty_data() {
		$data = array(
			'id'	 				=> ( string ) $this->id,
			'uniq_id'	 		=> ( string ) $this->uniq_id,
			'parent_id'	 	=> ( string ) $this->parent_id,
			'html' 				=> $this->render_html(),
			'css'					=> $this->get_layout_css(),
			'layout_id'		=> $this->get_layout_id(),
			'is_disabled' => array_unique( apply_filters( 'ditty_item_disabled', array(), $this->id ) ),
		);
		return $data;
	}
}