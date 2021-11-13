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
	private $html_tags;
	private $icon;
	private $label;
	private $layout_id;
	private $layout_type;
	private $layout_type_object;
	private $item_value;
	private $version;

	/**
	 * Get things started
	 * @access  public
	 * @since   3.0
	 */
	public function __construct( $layout_value, $layout_type = false, $item_value = array() ) {
		$this->item_value = $item_value;		
		if ( is_array( $layout_value ) && $layout_type ) {
			$this->layout_type = $layout_type;
			if ( $layout_type_object = $this->get_layout_type_object() ) {
				$layout_id = $layout_type_object->parse_layout_id( $layout_value, $item_value );
			}
		} else {	
			$layout_id = $layout_value;
		}
		
		// If this is a new layout
		if ( false !== strpos( $layout_id, 'new-' ) ) {
			$this->parse_draft_data( $layout_id );
		
		// Else, this is an existing layout
		} elseif ( get_post( $layout_id ) ) {
			$this->construct_from_id( $layout_id );
			$this->parse_draft_data( $layout_id );
		}
		$this->construct_type_object_data();
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
		$this->layout_type 	= isset( $draft_values['layout_type'] ) ? $draft_values['layout_type']	: $this->layout_type;
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
		$this->layout_id 			= $layout_id;
		$this->layout_type 		= get_post_meta( $layout_id, '_ditty_layout_type', true );
		$this->label 					= get_the_title( $layout_id );
		$this->description		= get_post_meta( $layout_id, '_ditty_layout_description', true );
		$this->html 					= get_post_meta( $layout_id, '_ditty_layout_html', true );
		$this->css 						= get_post_meta( $layout_id, '_ditty_layout_css', true );
		$this->version 				= get_post_meta( $layout_id, '_ditty_layout_version', true );
	}

	/**
	 * Construct the type object data
	 *
	 * @access public
	 * @since  3.0
	 */
	public function construct_type_object_data() {
		if ( ! $layout_type_object = $this->get_layout_type_object() ) {
			return false;
		}
		$this->icon 					= $layout_type_object->get_icon();
		$this->html_tags 			= $layout_type_object->html_tags();
		$this->css_selectors 	= $layout_type_object->css_selectors();		
	}
	
	/**
	 * Return the layout type object
	 * @access public
	 * @since  3.0
	 * @return int $layout_type_object
	 */
	public function get_layout_type_object() {
		if ( empty( $this->layout_type_object ) ) {
			$this->layout_type_object = ditty_layout_type_object( $this->layout_type );
		}
		return $this->layout_type_object;
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
	 * Return the layout type
	 * @access public
	 * @since  3.0
	 * @return string $layout_type
	 */
	public function get_layout_type() {
		return $this->layout_type;
	}
	
	/**
	 * Return the html tags
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_html_tags() {
		return $this->html_tags;
	}
	
	/**
	 * Return the html tags list
	 * @access public
	 * @since  3.0
	 * @return html $html_tags_list
	 */
	public function get_html_tags_list() {
		$html_tags_list = '';
		$html_tags = $this->get_html_tags();
		if ( is_array( $html_tags ) && count( $html_tags ) > 0 ) {
			$html_tags_list .= '<ul class="ditty-editor-options__tags__list ditty-editor-options__tags__list--' . $this->get_layout_type() . '">';
			foreach ( $html_tags as $data ) {
				$class = ( isset( $data['class'] ) && '' != $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';			
				$atts = array(
					'class' => 'ditty-editor-options__tag protip' . $class,
					'data-pt-title' => $data['description'],
					'data-atts' => ( isset( $data['atts'] ) ) ? htmlentities( json_encode( $data['atts'] ) ) : false,
				);
				$html_tags_list .= '<li ' . ditty_attr_to_html( $atts ) . '>{' . $data['tag'] . '}</li>';
			}
			$html_tags_list .= '</ul>';
		}
		return $html_tags_list;
	}
	
	/**
	 * Return the css selectors
	 * @access public
	 * @since  3.0
	 * @return int $id
	 */
	public function get_css_selectors() {
		return $this->css_selectors;
	}
	
	/**
	 * Return the css selectors list
	 * @access public
	 * @since  3.0
	 * @return html $css_selectors_list
	 */
	public function get_css_selectors_list() {
		$css_selectors_list = '';
		$css_selectors = $this->get_css_selectors();
		if ( is_array( $css_selectors ) && count( $css_selectors ) > 0 ) {
			$css_selectors_list .= '<ul class="ditty-editor-options__tags__list ditty-editor-options__tags__list--' . $this->get_layout_type() . '">';
			foreach ( $css_selectors as $data ) {
				$class = ( isset( $data['class'] ) && '' != $data['class'] ) ? ' ' . esc_attr( $data['class'] ) : '';
				$css_selectors_list .= '<li class="ditty-editor-options__tag protip' . $class . '" data-pt-title="' . $data['description'] . '">' . $data['selector'] . '</li>';
			}
			$css_selectors_list .= '</ul>';
		}
		return $css_selectors_list;
	}

	/**
	 * Return the layout icon
	 * @access public
	 * @since  3.0
	 * @return string $icon
	 */
	public function get_icon() {
		return $this->icon;
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
			$this->css_compiled = Ditty()->layouts->compile_layout_style( $this->css, $this->layout_id, $this->layout_type );
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
	 * @since  3.0
	 * @return string $html
	 */
	public function get_value() {
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
			'layout_type' => $this->get_layout_type(),
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
		$classes[] = 'ditty-layout--' . esc_attr( $this->layout_id );
		$classes[] = 'ditty-layout-type--' . esc_attr( $this->layout_type );		
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
		?>	
		<div id="ditty-editor-layout--<?php echo $this->layout_id; ?>" class="ditty-editor-layout ditty-editor-layout--<?php echo $this->layout_type; ?> ditty-data-list__item" data-layout_id="<?php echo $this->layout_id; ?>" data-layout_type="<?php echo $this->layout_type; ?>">
			<?php do_action( 'ditty_editor_layout_elements', $this ); ?>
		</div>
		<?php
		if ( 'return' == $render ) {
			return trim( ob_get_clean() );
		}
	}
	
	/**
	 * Render the layout
	 * @access public
	 * @since  3.0
	 * @return html
	 */
	public function render() {
		$tags		= $this->get_html_tags();
		$html		= $this->get_html();	
		$value 	= $this->get_value();	
		
		// Return an error if there is one
		if ( isset( $value['ditty_feed_error'] ) ) {
			return $value['ditty_feed_error'];
		}
		
		$handlers = new HandlerContainer();
		if ( is_array( $tags ) && count( $tags ) > 0 ) {
			foreach ( $tags as $i => $tag ) {
				$handlers->add( $tag['tag'], function( ShortcodeInterface $s ) use ( $tag, $value ) {
					$defaults = isset( $tag['atts'] ) ? $tag['atts'] : array();
					$atts = ditty_layout_parse_atts( $defaults, $s );
					$content = $s->getContent();
					return call_user_func( $tag['func'], $value, $atts, $content );
				} );
			}
		}
		$syntax = new Syntax( '{', '}', '/', '=', '"' ); // created explicitly
		$processor = new Processor( new RegularParser( $syntax ), $handlers );

		return stripslashes( $processor->process( $html ) );
	}
}