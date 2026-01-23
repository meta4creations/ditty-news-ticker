/**
 * Display Item Block - Save Component
 *
 * Server-side rendering handles the wrapper and styling.
 * We only save the inner block content.
 */

import { InnerBlocks } from '@wordpress/block-editor';

export default function save() {
	// Server-side rendering via render.php handles the wrapper
	return <InnerBlocks.Content />;
}
