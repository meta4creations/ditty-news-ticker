<?php

namespace Ditty\WPML;

/**
 * Delete a ditty package
 */
function delete_ditty_package( $post_id ) {
	do_action( 'wpml_delete_package', $post_id, __( 'Ditty', 'ditty-news-ticker' ) );
}
add_action( 'ditty_before_delete_post_items', 'Ditty\WPML\delete_ditty_package' );
