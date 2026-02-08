/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { store as blocksStore } from '@wordpress/blocks';
import { Path, SVG, Button, Placeholder } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

/**
 * Returns a custom variation icon for the placeholder.
 *
 * @param {string} name The block variation name.
 * @return {JSX.Element} The SVG element.
 */
const getPlaceholderIcons = (name = 'ticker') => {
	const icons = {
		ticker: (
			<SVG
				xmlns="http://www.w3.org/2000/svg"
				width="48"
				height="48"
				viewBox="0 0 48 48"
			>
				<Path d="M4 20h28v8H4v-8zm32 4l8-6v12l-8-6z" />
			</SVG>
		),
		slider: (
			<SVG
				xmlns="http://www.w3.org/2000/svg"
				width="48"
				height="48"
				viewBox="0 0 48 48"
			>
				<Path d="M10 12h28v24H10V12zM2 18h6v12H2V18zm38 0h6v12h-6V18z" />
			</SVG>
		),
	};
	return icons?.[name];
};

/**
 * A custom hook to determine if the placeholder should be shown.
 *
 * @param {Object}  props                  Arguments to pass to hook.
 * @param {Object}  [props.attributes]     The block's attributes.
 * @param {string}  [props.usedLayoutType] The block's current layout type.
 * @param {boolean} [props.hasInnerBlocks] Whether the block has inner blocks.
 *
 * @return {[boolean, Function]} A state value and setter function.
 */
export function useShouldShowPlaceholder({
	attributes = {},
	hasInnerBlocks = false,
}) {
	const { style, backgroundColor, textColor } = attributes;

	// Show placeholder when no inner blocks and no styles applied
	const [showPlaceholder, setShowPlaceholder] = useState(
		!hasInnerBlocks && !backgroundColor && !textColor && !style
	);

	useEffect(() => {
		if (!!hasInnerBlocks || !!backgroundColor || !!textColor || !!style) {
			setShowPlaceholder(false);
		}
	}, [backgroundColor, textColor, style, hasInnerBlocks]);

	return [showPlaceholder, setShowPlaceholder];
}

/**
 * Display variations if none is selected.
 *
 * @param {Object}   props          Component props.
 * @param {string}   props.name     The block's name.
 * @param {Function} props.onSelect Function to set block's attributes.
 *
 * @return {JSX.Element} The placeholder.
 */
function DittyDisplayPlaceholder({ name, onSelect }) {
	const variations = useSelect(
		select => select(blocksStore).getBlockVariations(name, 'block'),
		[name]
	);

	const blockProps = useBlockProps({
		className: 'wp-block-metaphorcreations-ditty-display__placeholder',
	});

	useEffect(() => {
		if (variations && variations.length === 1) {
			onSelect(variations[0]);
		}
	}, [onSelect, variations]);

	return (
		<div {...blockProps}>
			<Placeholder
				instructions={__(
					'Create a dynamic display. Select a layout:',
					'ditty-news-ticker'
				)}
			>
				<ul
					role="list"
					className="wp-block-metaphorcreations-ditty-display__variations"
					aria-label={__('Block variations', 'ditty-news-ticker')}
				>
					{variations.map(variation => (
						<li key={variation.name}>
							<Button
								__next40pxDefaultSize
								variant="tertiary"
								icon={getPlaceholderIcons(variation.name)}
								iconSize={48}
								onClick={() => onSelect(variation)}
								className="wp-block-metaphorcreations-ditty-display__variation-button"
								label={`${variation.title}: ${variation.description}`}
							/>
						</li>
					))}
				</ul>
			</Placeholder>
		</div>
	);
}

export default DittyDisplayPlaceholder;
