<?php

/* --------------------------------------------------------- */
/* !Create a settings label - 1.4.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_settings_label') ) {
function mtphr_dnt_settings_label( $title, $description = '' ) {

	$label = '<div class="mtphr-dnt-label-alt">';
		$label .= '<label>'.$title.'</label>';
		if( $description != '' ) {
			$label .= '<small>'.$description.'</small>';
		}
	$label .= '</div>';

	return $label;
}
}
