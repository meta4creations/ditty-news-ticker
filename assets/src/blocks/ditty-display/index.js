/**
 * Ditty Display Block
 *
 * A dynamic display block with ticker and slider variations.
 */

import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import save from './save';
import metadata from './block.json';
import variations from './variations';
import './index.scss';

/**
 * Block Icon
 */
const icon = (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		width="24"
		height="24"
	>
		<path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z" />
	</svg>
);

/**
 * Register Block
 */
registerBlockType(metadata.name, {
	icon,
	variations,
	edit: Edit,
	save,
});
