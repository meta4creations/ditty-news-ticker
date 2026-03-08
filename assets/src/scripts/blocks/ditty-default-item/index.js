/**
 * Default Item Block - Registration
 *
 * A default text item with optional link settings.
 */

import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';
import save from './save';
import metadata from './block.json';

registerBlockType(metadata.name, {
	edit,
	save,
});
