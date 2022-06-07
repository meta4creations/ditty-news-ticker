<?php
/**
 * Ditty Layout Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Layout
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Parser\RegularParser;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use Thunder\Shortcode\Syntax\CommonSyntax;
use Thunder\Shortcode\Syntax\Syntax;
use Thunder\Shortcode\Syntax\SyntaxBuilder;

class Ditty_Layout {
	
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

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $layout_id, $item_type = false, $item_value = array(), $item_meta = array() ) {
		$this->item_value = $item_value;	
		$this->item_type = $item_type;
		$this->item_meta = $item_meta;
		
		// If this is a new layout
		if ( is_string( $layout_id ) && false !== strpos( $layout_id, 'new-' ) ) {
			$this->parse_draft_data( $layout_id );
		
		// Else, this is an existing layout
		} elseif ( get_post( $layout_id ) ) {
			$this->construct_from_id( $layout_id );
			$this->parse_draft_data( $layout_id );
		}
		
		return $this;
	}
	
	/**
	 * Construct the class from meta
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function parse_draft_data( $layout_id ) {
		$this->layout_id = $layout_id;
		$draft_values = ditty_draft_layout_get( $layout_id );
		if ( ! $draft_values ) {
			return false;
		}
		$this->label 				= isset( $draft_values['label'] ) 			? $draft_values['label'] 				: $this->label;
		$this->description 	= isset( $draft_values['description'] ) ? $draft_values['description']	: $this->description;
		$this->html 				= isset( $draft_values['html'] ) 				? $draft_values['html'] 				: $this->html;
		$this->css					= isset( $draft_values['css'] )					? $draft_values['css'] 					: $this->css;
		$this->version			= isset( $draft_values['version'] )			? $draft_values['version'] 			: $this->version;
	}
	
	/**
	 * Construct class from ID
	 *
	 * @access public
	 * @since  3.0
	 */
	public function construct_from_id( $layout_id ) {
		if ( 'publish' == get_post_status( $layout_id ) ) {
			$this->layout_id 			= $layout_id;
			$this->label 					= get_the_title( $layout_id );
			$this->description		= get_post_meta( $layout_id, '_ditty_layout_description', true );
			$this->html 					= get_post_meta( $layout_id, '_ditty_layout_html', true );
			$this->css 						= get_post_meta( $layout_id, '_ditty_layout_css', true );
			$this->version 				= get_post_meta( $layout_id, '_ditty_layout_version', true );
		} else {
			$this->label					=  __( 'Ditty Layout does not exist.', 'ditty-news-ticker' );
		}
	}

	/**
	 * Return the layout id
	 * @access public
	 * @since  3.0
	 * @return int $layout_id
	 */
	public function get_layout_id() {
		return $this->layout_id;
	}
	
	/**
	 * Set the layout id
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function set_layout_id( $layout_id ) {
		$this->layout_id = $layout_id;
		return $this->layout_id;
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
	 * Return the html tags list
	 * @access public
	 * @since  3.0.12
	 * @return html $tags_list
	 */
	public function get_tags_list() {
		$tags_list = '';
		$tags = apply_filters( 'ditty_layout_tags_list', $this->get_layout_tags(), $this->get_item_type(), 'html', $this->get_item_value() );
		if ( is_array( $tags ) && count( $tags ) > 0 ) {
			$tags_list .= '<ul class="ditty-editor-options__tags__list ditty-editor-options__tags__list--' . $this->get_item_type() . '">';
			foreach ( $tags as $data ) {
				$class = ( isset( $data['class'] ) && '' != $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';			
				$atts = array(
					'class' 				=> 'ditty-editor-options__tag protip' . $class,
					'data-pt-title' => isset( $data['description'] ) ? $data['description'] : false,
					'data-atts' 		=> ( isset( $data['atts'] ) ) ? htmlentities( json_encode( $data['atts'] ) ) : false,
				);
				$tags_list .= '<li ' . ditty_attr_to_html( $atts ) . '>{' . $data['tag'] . '}</li>';
			}
			$tags_list .= '</ul>';
		}
		return $tags_list;
	}
	
	/**
	 * Return the css selectors
	 * @access public
	 * @since  3.0.12
	 * @return int $id
	 */
	public function get_css_selectors_list() {
		$tags = apply_filters( 'ditty_layout_tags_list', $this->get_layout_tags(), $this->get_item_type(), 'css', $this->get_item_value() );
		$selectors_list = '';
		if ( is_array( $tags ) && count( $tags ) > 0 ) {
			$selectors_list .= '<ul class="ditty-editor-options__tags__list ditty-editor-options__tags__list--' . $this->get_item_type() . '">';
				$selectors_list .= '<li class="ditty-editor-options__tag">.ditty-item__elements</li>';
				foreach ( $tags as $data ) {
					$selectors_list .= '<li class="ditty-editor-options__tag">.ditty-item__' . $data['tag'] . '</li>';
				}
			$selectors_list .= '</ul>';
		}
		return $selectors_list;
	}

	/**
	 * Return the layout label
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Set the layout label
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function set_label( $label ) {
		if ( $label != $this->label ) {
			$sanitized_label = sanitize_text_field( $label );
			$this->label = $sanitized_label;
			return $this->label;
		}
	}
	
	/**
	 * Return the version
	 * @access public
	 * @since  3.0
	 * @return string $version
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Set the version
	 * @access public
	 * @since  3.0
	 * @return string $version
	 */
	public function remove_version() {
		$this->version = false;
	}
	
	/**
	 * Return the layout description
	 * @access public
	 * @since  3.0
	 * @return string $description
	 */
	public function get_description() {
		return $this->description;
	}
	
	/**
	 * Return the layout html
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_html() {
		return stripslashes( $this->html );
	}
	
	/**
	 * Set the layout html
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function set_html( $html ) {
		$sanitized_html = wp_kses_post( $html );
		$this->html = $sanitized_html;
		return $this->html;
	}
	
	/**
	 * Return the layout css
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function get_css() {
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
	 * Set the layout html
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function set_css( $css ) {
		$sanitized_css = wp_kses_post( $css );
		$this->css = $sanitized_css;
		$this->css_compiled = null;
		return $this->css;
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
	 * Set the item value
	 * @access public
	 * @since  3.0
	 * @return string $html
	 */
	public function set_item_value( $item_value ) {
		$this->item_value = $item_value;
	}
	
	/**
	 * Return all custom meta for the layout
	 * @access public
	 * @since  3.0
	 * @return array $custom_meta
	 */
	public function custom_meta() {
		return array(
			'label' 			=> $this->get_label(),
			'description' => $this->get_description(),
			'html'				=> $this->get_html(),
			'css'					=> $this->get_css(),
		);
	}

	/**
	 * Setup the layout classes
	 * @access public
	 * @since  3.0
	 * @return string $classes
	 */
	public function get_classes() {	
		$classes = array();
		$classes[] = 'ditty-layout';
		$classes[] = 'ditty-layout--' . esc_attr( $this->get_layout_id() );
		$classes[] = 'ditty-item-type--' . esc_attr( $this->get_item_type() );		
		$classes = apply_filters( 'ditty-layout-classes', $classes );
		return implode( ' ', $classes );
	}
	
	/**
	 * Render the admin edit row
	 * @access public
	 * @since  3.0
	 * @return html
	 */
	public function render_editor_list_item( $render='echo' ) {
		if ( 'return' == $render ) {
			ob_start();
		}
		$atts = array(
			'id' 									=> "ditty-editor-layout--{$this->get_layout_id()}",
			'class'								=> 'ditty-editor-layout ditty-data-list__item',
			'data-layout_id' 			=> $this->get_layout_id(),
			'data-layout_version'	=> $this->get_version(),
		);
		?>	
		<div <?php echo ditty_attr_to_html( $atts ); ?>>
			<?php do_action( 'ditty_editor_layout_elements', $this ); ?>
		</div>
		<?php
		if ( 'return' == $render ) {
			return trim( ob_get_clean() );
		}
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
	 * Render the layout
	 * @access public
	 * @since  3.0.18
	 * @return html
	 */
	public function render() {
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
}