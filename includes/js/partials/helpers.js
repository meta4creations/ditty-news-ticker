/**
 * Update item css
 *
 * @since    3.0
 * @return   null
*/
function dittyLayoutCss( layoutCss, layoutId, layoutType ) {
	var $styles = jQuery( 'style#ditty-' + layoutType + '--' + layoutId );
	if ( undefined === $styles[0] ) {
		$styles = jQuery( '<style id="ditty-' + layoutType + '--' + layoutId + '"></style>' );
		jQuery( 'head' ).append( $styles );
	}
	layoutCss = layoutCss.replace( '&gt;', '>' );
	$styles.html( layoutCss );
}

/**
 * Update items
 *
 * @since    3.0
 * @return   null
*/
function dittyUpdateItems( itemSwaps ) {
	var animationSpeed = 500;
	
	jQuery.each( itemSwaps, function( index, data ) {
		var $current = data.currentItem,
				$new = data.newItem;
		
		$current.wrap( '<div class="ditty-update-wrapper"></div>' );
		var $updateWrapper = $current.parent();
		
		$updateWrapper.stop().css( {
			height: $current.outerHeight()
		} );
		$current.stop().css( {
			position: 'absolute',
			top: 0,
			left: 0,
			width: '100%'
		} );
		$new.stop().css( {
			position: 'absolute',
			top: 0,
			left: 0,
			width: '100%',
			opacity: 0
		} );
		$current.after( $new );
		
		$current.stop().animate( {
			opacity : 0
		}, animationSpeed * 0.75, 'linear' );
		
		$new.stop().animate( {
			opacity : 1
		}, animationSpeed * 0.75, 'linear' );
		
		$updateWrapper.stop().animate( {
			height : $new.outerHeight()
		}, animationSpeed, 'easeOutQuint', function() {
			$updateWrapper.removeAttr( 'style' );
			$current.unwrap();
			$current.remove();
			$new.css( {
				position: 'relative',
				top: 'auto',
				left: 'auto',
				width: 'auto'
			} );
			if ( $new.hasClass( 'ditty-temp-item' ) ) {
				$new.remove();
			}
		} );
	} );
}
