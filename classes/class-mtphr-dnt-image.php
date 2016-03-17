<?php
	
/* --------------------------------------------------------- */
/* !Create an image class - 2.0.8 */
/* --------------------------------------------------------- */

class MTPHR_DNT_Image {
	
	private $src;
	private $width = 0;
	private $height = 0;
	private $alt = '';
	private $link = false;
	private $target = '_blank';
	private $caption = false;
	private $caption_position = 'below';
	private $caption_hover = true;
	
	
	function __construct( $src, $width=0, $height=0, $alt='' ) {
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
		$this->width = $width;
	}
	
	public function get_width() {
		return $this->width;
	}

	public function set_height( $height=0 ) {
		$this->height = $height;
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

 	public function set_link( $link=false, $target='_blank' ) {
	 	$this->link = $link;
	 	$this->target = $target;
	}
	
	public function get_link() {
	 	return $this->link;
	}
	
	public function get_target() {
	 	return $this->target = $target;
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
		
		$hover = ( $this->caption_hover && ($this->caption_position == 'top' || $this->caption_position == 'bottom') ) ? ' mtphr-dnt-image-caption-hover' : '';
		echo '<div class="mtphr-dnt-image-container mtphr-dnt-image-caption-'.$this->caption_position.$hover.'">';
		
			switch( $this->caption_position ) {
				case 'above':
					$this->render_caption();
					$this->render_image();
					break;
					
				default:
					$this->render_image();
					$this->render_caption();
					break;
			}
		
		echo '</div>';
	}
	
	
	private function render_image() {
		
		echo '<div class="mtphr-dnt-image-photo">';
			if( $this->link ) {
				echo '<a href="'.esc_url_raw($this->link).'" target="'.$this->target.'">';	
			}
			echo '<div class="mtphr-dnt-image-placeholder" data-src="'.$this->src.'" data-width="'.$this->width.'" data-height="'.$this->height.'" style="width:'.$this->width.'px;height:'.$this->height.'px;"><i class="mtphr-dnt-icon-spinner"></i></div>';
			echo '<div class="mtphr-dnt-image-placeholder-sizer"></div>';
			
			if( $this->link ) {
				echo '</a>';	
			}
		echo '</div>';
	}
	
	
	private function render_caption() {
		
		if( $this->caption ) {
			echo '<div class="mtphr-dnt-image-caption">';
				echo $this->caption;
			echo '</div>';
		}
	}
	
	
	
}