<?php
	
/* --------------------------------------------------------- */
/* !Create the base ticker class - 2.0.9 */
/* --------------------------------------------------------- */

class MTPHR_DNT {
	
	private $post_id;
	private $ticker_id;
	private $mode;
	private $type;
	private $ticks = array();
	private $total_ticks = 0;
	private $active;
	private $classes = '';
	private $meta = false;
	
	
	function __construct( $id='', $class='', $atts=false ) {
  	$this->post_id = $this->modify_id( $id );
  	$this->parse_meta( $atts );
		$this->classes = $class;
		$this->set_active();
		$this->set_ticker_id();
		$this->set_type();
		
		if( $this->active ) {
			$this->get_ticks();
		}
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Modify the ticker post id */
 	/* --------------------------------------------------------- */
 	
 	private function modify_id( $id ) {
	 	
	 	// Check for WPML language posts
	 	if( function_exists('icl_object_id') ) {
		 	$id = icl_object_id( $id, 'ditty_news_ticker', true );
	 	}
	 	
	 	return $id;	
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Set the active value of the ticker */
 	/* --------------------------------------------------------- */
 	
 	private function set_active() {
	 	
	 	$ticker = get_post( $this->post_id );
	 	if( $ticker && $ticker->post_status == 'publish' ) {
		 	$this->active = true;
	 	}
 	}
 	
 	public function is_active() {
	 	return $this->active;
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Set the ticker id */
 	/* --------------------------------------------------------- */
 	
 	private function set_ticker_id() {
	 	
	 	$ticker_id = 'mtphr-dnt-'.$this->post_id;
		if( isset($this->meta['_mtphr_dnt_unique_id']) && ($this->meta['_mtphr_dnt_unique_id'] != '') ) {
			$ticker_id = 'mtphr-dnt-'.$this->post_id.'-'.sanitize_html_class( $this->meta['_mtphr_dnt_unique_id'] );
		} 	
	 	$this->ticker_id = $ticker_id;
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Set the ticker type */
 	/* --------------------------------------------------------- */
 	
 	private function set_type() {
	 	$this->type = $this->meta['_mtphr_dnt_type'];
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Parse the metadata */
 	/* --------------------------------------------------------- */
 	
 	private function parse_meta( $atts ) {
	 			 	
	 	$custom_fields = get_post_custom( $this->post_id );
		$meta_data = array();
		foreach( $custom_fields as $key => $value ) {
			$meta_data[$key] = maybe_unserialize( $value[0] );
		}
	
		// Override meta data with supplied attributes
		if( is_array($atts) ) {
			foreach( $atts as $key => $value ) {
				$meta_data["_mtphr_dnt_{$key}"] = $value;
			}
		}
		
		$this->meta;
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Get the ticks */
 	/* --------------------------------------------------------- */
 	
 	private function add_ticks( $ticks ) {

	 	if( $this->meta['_mtphr_dnt_type'] == 'mixed' ) {
			$this->ticks = mtphr_dnt_mixed_ticks( $this->post_id, $this->meta );
		} else {
			$this->ticks = apply_filters( 'mtphr_dnt_tick_array', array(), $this->post_id, $this->meta );
		}
		
		// Randomize the ticks
		if( isset($this->meta['_mtphr_dnt_shuffle']) && $this->meta['_mtphr_dnt_shuffle'] ) {
			shuffle( $this->ticks );
		}
		
		$this->total_ticks = count( $this->ticks );
 	}
 	
 	private function get_ticks() {

	 	if( $this->meta['_mtphr_dnt_type'] == 'mixed' ) {
			$this->ticks = mtphr_dnt_mixed_ticks( $this->post_id, $this->meta );
		} else {
			$this->ticks = apply_filters( 'mtphr_dnt_tick_array', array(), $this->post_id, $this->meta );
		}
		
		// Randomize the ticks
		if( isset($this->meta['_mtphr_dnt_shuffle']) && $this->meta['_mtphr_dnt_shuffle'] ) {
			shuffle( $this->ticks );
		}
		
		$this->total_ticks = count( $this->ticks );
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Return a static ticker width */
 	/* --------------------------------------------------------- */
 	
 	private function static_ticker_width() {
	 	if( isset($this->meta['_mtphr_dnt_ticker_width']) && ($this->meta['_mtphr_dnt_ticker_width'] != 0) ) {
			return ' style="width:'.intval($_mtphr_dnt_ticker_width).'px"';
		}
 	}
 	
 	
 	/* --------------------------------------------------------- */
 	/* !Render the ticker */
 	/* --------------------------------------------------------- */
 	
 	public function render() {
	 	
	 	$html = '';
	 	$html .= '<div'.$this->static_ticker_width().' id="'.$this->ticker_id.'" '.mtphr_dnt_ticker_class( $this->post_id, $this->classes, $this->meta ).'>';
			$html .= '<div class="mtphr-dnt-wrapper mtphr-dnt-clearfix">';
		
				// Open the ticker container
				do_action( 'mtphr_dnt_before', $this->post_id, $this->meta );
				echo '<div class="mtphr-dnt-tick-container"'.$container_style.'>';
					do_action( 'mtphr_dnt_contents_before', $this->post_id, $this->meta );
					echo '<div class="mtphr-dnt-tick-contents">';
						do_action( 'mtphr_dnt_top', $this->post_id, $this->meta );
				
						// Print out the ticks
						if( is_array($this->ticks) ) {
							
							$html .= ob_get_clean();
							
							foreach( $this->ticks as $i => $tick_obj ) {
								
								ob_start();
							
								mtphr_dnt_tick_open( $tick_obj, $i, $this->post_id, $this->meta, $this->total_ticks );
								
								$tick = ( is_array($tick_obj) && isset($tick_obj['tick']) ) ? $tick_obj['tick'] : $tick_obj;
								echo $tick;
								
								mtphr_dnt_tick_close( $tick_obj, $i, $this->post_id, $this->meta, $this->total_ticks );
								
								$html .= ob_get_clean();
							}
							
							ob_start();
						}
				
						// Close the ticker container
						do_action( 'mtphr_dnt_bottom', $this->post_id, $this->meta );
					echo '</div>';
					do_action( 'mtphr_dnt_contents_after', $this->post_id, $this->meta, $this->total_ticks );
				echo '</div>';
				do_action( 'mtphr_dnt_after', $this->post_id, $this->meta, $this->total_ticks );
	
			echo '</div>';
		echo '</div>';
 	
 	}
 	
 	
	
	
}