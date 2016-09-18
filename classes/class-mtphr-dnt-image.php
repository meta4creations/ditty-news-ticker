<?php
	
/* --------------------------------------------------------- */
/* !Create an image class - 2.0.12 */
/* --------------------------------------------------------- */

class MTPHR_DNT_Image {
	
	private $src;
	private $width = '';
	private $height = '';
	private $static_dimensions = false;
	private $alt = '';
	private $link = false;
	private $target = '_blank';
	private $nofollow = false;
	private $caption = false;
	private $caption_position = 'below';
	private $caption_hover = true;
	
	
	function __construct( $src, $width='', $height='', $alt='' ) {
  	$this->src = $src;
		$this->width = $width;
		$this->height = $height;
		$this->alt = $alt;
 	}
 	
 	
 	public function set_src( $src='' ) {
		$this->src = $src;
	}
	
	public function get_src() {
		return $this->src;
	}

	public function set_width( $width=0 ) {
		$this->width = intval($width);
	}
	
	public function get_width() {
		return $this->width;
	}

	public function set_height( $height=0 ) {
		$this->height = intval($height);
	}
	
	public function get_height() {
		return $this->height;
	}

	public function set_alt( $alt='' ) {
		$this->alt = $alt;
	}
	
	public function get_alt() {
		return $this->alt;
	}
	
	public function enable_static_dimensions() {
		$this->static_dimensions = true;
	}
	
	public function disable_static_dimensions() {
		$this->static_dimensions = false;
	}
	
	public function get_static_dimensions() {
		return $this->static_dimensions;
	}

 	public function set_link( $link=false, $target='_blank', $nofollow=false ) {
	 	$this->link = $link;
	 	$this->target = $target;
	 	$this->nofollow = $nofollow;
	}
	
	public function get_link() {
	 	return $this->link;
	}
	
	public function get_target() {
	 	return $this->target;
	}
	
	public function get_nofollow() {
	 	return $this->nofollow;
	}
	
	public function set_caption( $caption=false ) {
	 	$this->caption = $caption;
	}
	
	public function get_caption() {
	 	return $this->caption;
	}

	public function set_caption_position( $caption_position='' ) {
	 	$this->caption_position = $caption_position;
	}
	
	public function get_caption_position() {
	 	return $this->caption_position;
	}

	public function set_caption_hover( $caption_hover=true ) {
	 	$this->caption_hover = $caption_hover;
	}
	
	public function get_caption_hover( $caption_hover=true ) {
	 	return $this->caption_hover;
	}
	
	public function render() {
		
		$html = '';
		$hover = ( $this->caption_hover && ($this->caption_position == 'top' || $this->caption_position == 'bottom') ) ? ' mtphr-dnt-image-caption-hover' : '';
		
		$html .= '<div class="mtphr-dnt-image-container mtphr-dnt-image-caption-'.$this->caption_position.$hover.'">';
		
			switch( $this->caption_position ) {
				case 'above':
					$html .= $this->render_caption();
					$html .= $this->render_image();
					break;
					
				default:
					$html .= $this->render_image();
					$html .= $this->render_caption();
					break;
			}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private function add_static_dimensions() {
		
		$html = '';
		
		if( $this->static_dimensions && (($this->width >= 0) || ($this->height >= 0)) ) {
			$html .= ' style="';
			if( $this->width >= 0 ) {
				$html .= 'width:'.$this->width.'px;';
			}
			if( $this->height >= 0 ) {
				$html .= 'height:'.$this->height.'px;';
			}
			$html .= '"';
		}
		
		return $html;
	}
	
	
	private function render_image() {
		
		$html = '';
		
		$html .= '<div class="mtphr-dnt-image-photo"'.$this->add_static_dimensions().'>';
			if( $this->link ) {
				$nofollow = $this->nofollow ? ' rel="nofollow"' : '';
				$html .= '<a href="'.esc_url_raw($this->link).'" target="'.$this->target.'"'.$nofollow.'>';	
			}
			
			$html .= '<img src="'.$this->src.'" width="'.$this->width.'" height="'.$this->height.'" alt="'.$this->alt.'" />';
			
			//$html .= '<span class="mtphr-dnt-image-placeholder" data-src="'.$this->src.'" data-width="'.$this->width.'" data-height="'.$this->height.'" style="width:'.$this->width.'px;height:'.$this->height.'px;"><i class="mtphr-dnt-icon-spinner"></i></span>';
			//$html .= '<span class="mtphr-dnt-image-placeholder-sizer"></span>';
			
			if( $this->link ) {
				$html .= '</a>';	
			}
		$html .= '</div>';
		
		return $html;
	}
	
	
	private function render_caption() {
		
		$html = '';
		if( $this->caption ) {
			$html .= '<span class="mtphr-dnt-image-caption">';
				$html .= $this->caption;
			$html .= '</span>';
		}
		
		return $html;
	}
	
	
	
}