/**
 * Display Item Block - Registration
 *
 * A single display item that can contain paragraph, heading, or HTML content.
 * The first item acts as a template for subsequent items.
 */

import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';
import save from './save';
import metadata from './block.json';
import './index.scss';

registerBlockType(metadata.name, {
	edit,
	save,
});
