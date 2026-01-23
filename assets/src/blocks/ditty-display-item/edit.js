/**
 * Display Item Block - Edit Component
 *
 * A simple display item block with customizable styling.
 */

import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { useSelect, useDispatch } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { applyFilters } from '@wordpress/hooks';

export default function Edit({ attributes, setAttributes, clientId, context }) {
	const displayType = context['dittyDisplay/type'] || 'ticker';

	// Get inner block count
	const { innerBlockCount } = useSelect(
		select => {
			const { getBlocks } = select(blockEditorStore);
			const innerBlocks = getBlocks(clientId);
			return {
				innerBlockCount: innerBlocks.length,
			};
		},
		[clientId]
	);

	// Remove extra inner blocks if more than allowed
	const { removeBlock } = useDispatch(blockEditorStore);

	useEffect(() => {
		/**
		 * Filter to allow multiple inner blocks in display-item
		 *
		 * @param {boolean} allowMultiple Whether to allow multiple inner blocks
		 */
		const allowMultipleInnerBlocks = applyFilters(
			'ditty.displayItem.allowMultipleInnerBlocks',
			false
		);

		if (!allowMultipleInnerBlocks && innerBlockCount > 1) {
			const { getBlocks } = blockEditorStore.resolvers
				? blockEditorStore
				: window.wp.data.select(blockEditorStore);
			const innerBlocks = getBlocks(clientId);

			// Remove all blocks except the first one
			for (let i = 1; i < innerBlocks.length; i++) {
				removeBlock(innerBlocks[i].clientId, false);
			}
		}
	}, [innerBlockCount, clientId, removeBlock]);

	// Block props - useBlockProps automatically applies block support styles
	const blockProps = useBlockProps({
		className: `ditty-display-item ditty-display-item--${displayType}`,
	});

	/**
	 * Filter to modify allowed blocks in display-item
	 *
	 * @param {Array} allowedBlocks Array of block names
	 */
	const allowedBlocks = applyFilters('ditty.displayItem.allowedBlocks', [
		'core/paragraph',
		'core/heading',
	]);

	/**
	 * Filter to control whether multiple inner blocks are allowed
	 *
	 * @param {boolean} allowMultiple Whether to allow multiple inner blocks
	 */
	const allowMultipleInnerBlocks = applyFilters(
		'ditty.displayItem.allowMultipleInnerBlocks',
		false
	);

	// Inner blocks configuration
	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: allowedBlocks,
		template: [
			[
				'core/paragraph',
				{
					content: __(
						'This is a sample item. Please edit me!',
						'ditty-news-ticker'
					),
				},
			],
		],
		templateLock: allowMultipleInnerBlocks ? false : 'insert',
		renderAppender: false,
	});

	return <div {...innerBlocksProps} />;
}
