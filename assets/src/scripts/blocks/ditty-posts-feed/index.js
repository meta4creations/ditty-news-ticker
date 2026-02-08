/**
 * Posts Feed Block - Registration
 *
 * Displays a feed of recent blog posts.
 */

import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';
import save from './save';
import metadata from './block.json';

registerBlockType(metadata.name, {
	edit,
	save,
});
