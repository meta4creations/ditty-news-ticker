<?php

/**
 * Upgrade the ticker types data
 * @since   3.0
 */
function mtphr_dnt_types_upgrade( $types ) {
	
	$types_upgrade = array();
	if( is_array($types) && count($types) > 0 ) {
		foreach( $types as $slug=>$data ) {
			
			$label = isset($data['button']) ? $data['button'] : $data['label'];	
			if( isset($data['icon']) ) {
				switch( $slug ) {
					case 'default':
						$icon_class = 'fas fa-pencil-alt';
						break;
					case 'mixed':
						$icon_class = 'fas fa-random';
						break;
					case 'fb_posts':
					case 'fb_images':
						$icon_class = 'fab fa-facebook-f';
						break;
					case 'flickr':
						$icon_class = 'fab fa-flickr';
						break;
					case 'image':
						$icon_class = 'fas fa-image';
						break;
					case 'instagram':
						$icon_class = 'fab fa-instagram';
						break;
					case 'posts':
						$icon_class = 'fas fa-thumbtack';
						break;
					case 'rss':
						$icon_class = 'fas fa-rss';
						break;
					case 'twitter':
						$icon_class = 'fab fa-twitter';
						break;
					default:
						$icon_class = $data['icon'];
						break;
				}
			} else {
				$icon_class = $data['icon_class'];;
			}

			$types_upgrade[$slug] = array(
				'label' => $label,
				'icon_class' => $icon_class
			);
		}
	}
	
	return $types_upgrade;
}
add_filter( 'mtphr_dnt_types', 'mtphr_dnt_types_upgrade', 99 );