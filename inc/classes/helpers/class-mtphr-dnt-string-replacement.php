<?php
	
/* --------------------------------------------------------- */
/* !Create a string replacement class - 2.0.8 */
/* --------------------------------------------------------- */

class MTPHR_DNT_String_Replacement {
	
	private $str = '';
	private $replacements = array();
	
	
	function __construct( $str='' ) {
  	$this->str = $str;
 	}
 	
 	
 	public function str( $str='' ) {
		$this->str = $str;
	}
	
	
	public function add_replacement( $placeholder, $replacement ) {
		$this->replacements[] = array(
			'placeholder' => $placeholder,
			'replacement' => $replacement,
			'type' => 'simple'
		);
	}
	
	public function add_hashtag_replacement( $url, $target="_blank" ) {
		$this->replacements[] = array(
			'url' => $url,
			'target' => $target,
			'type' => 'hashtag'
		);
	}
	
	public function add_at_replacement( $url, $target="_blank" ) {
		$this->replacements[] = array(
			'url' => $url,
			'target' => $target,
			'type' => 'at'
		);
	}
	
	public function render() {
		return $this->replace_strings();
	}
	
	
	private function replace_strings() {
		
		$str = $this->str;
		if( is_array($this->replacements) && count($this->replacements) > 0 ) {
			foreach( $this->replacements as $i=>$replacement ) {
				if( $replacement['type'] == 'simple' ) {
					$str = preg_replace( '%'.$replacement['placeholder'].'%i', $replacement['replacement'], $str );
				} elseif( $replacement['type'] == 'hashtag' ) {
					$str = preg_replace( '/ [#]+([A-Za-z0-9-_]+)/i', '<a href="'.$replacement['url'].'\\1" target="'.$replacement['target'].'">\\0</a>', $str );
					$str = preg_replace( '/^[#]+([A-Za-z0-9-_]+)/i', '<a href="'.$replacement['url'].'\\1" target="'.$replacement['target'].'">\\0</a>', $str );
					$str = preg_replace( '/\s+[#]+([A-Za-z0-9-_]+)/i', '<a href="'.$replacement['url'].'\\1" target="'.$replacement['target'].'">\\0</a>', $str );
				} elseif( $replacement['type'] == 'at' ) {
					$str = preg_replace( '/ [@]+([A-Za-z0-9-_]+)/i', '<a href="'.$replacement['url'].'\\1" target="'.$replacement['target'].'">\\0</a>', $str );
					$str = preg_replace( '/^[@]+([A-Za-z0-9-_]+)/i', '<a href="'.$replacement['url'].'\\1" target="'.$replacement['target'].'">\\0</a>', $str );
					$str = preg_replace( '/\s+[@]+([A-Za-z0-9-_]+)/i', '<a href="'.$replacement['url'].'\\1" target="'.$replacement['target'].'">\\0</a>', $str );
				}
			}
		}
		return $str;
	}


}