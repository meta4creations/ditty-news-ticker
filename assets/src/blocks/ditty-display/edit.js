/**
 * Ditty Display Block - Edit Component
 */

const { __ } = wp.i18n;
import { useSelect, useDispatch } from '@wordpress/data';
import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	ToggleControl,
	SelectControl,
	BaseControl,
	ColorPicker,
	TextControl,
} from '@wordpress/components';
import { View } from '@wordpress/primitives';

import DittyDisplayPlaceholder, {
	useShouldShowPlaceholder,
} from './placeholder';

/**
 * Template for inner blocks - locked title and contents
 */
const TEMPLATE = [
	['ditty/display-title', { lock: { remove: true, move: true } }],
	['ditty/display-contents', { lock: { remove: true, move: true } }],
];

/**
 * Edit Component
 */
export default function Edit({ attributes, setAttributes, clientId, name }) {
	const {
		type,
		showTitle,
		direction,
		speed,
		spacing,
		hoverPause,
		cloneItems,
		itemBgColor,
		itemPadding,
		itemBorderColor,
		itemBorderStyle,
		itemBorderWidth,
		itemBorderRadius,
		itemMaxWidth,
		itemElementsWrap,
	} = attributes;

	const { hasInnerBlocks } = useSelect(
		select => {
			const { getBlocks } = select(blockEditorStore);
			const blocks = getBlocks(clientId);
			return {
				hasInnerBlocks: blocks.length > 0,
			};
		},
		[clientId]
	);

	const blockProps = useBlockProps({
		className: `ditty-display-editor ditty-display-type-${type}`,
	});

	const [showPlaceholder, setShowPlaceholder] = useShouldShowPlaceholder({
		attributes,
		hasInnerBlocks,
	});

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		template: TEMPLATE,
		templateLock: 'all',
		renderAppender: false,
	});

	const { selectBlock } = useDispatch(blockEditorStore);

	const selectVariation = nextVariation => {
		setAttributes(nextVariation.attributes);
		selectBlock(clientId, -1);
		setShowPlaceholder(false);
	};

	const borderStyleOptions = [
		{ label: __('None', 'ditty-news-ticker'), value: '' },
		{ label: __('Solid', 'ditty-news-ticker'), value: 'solid' },
		{ label: __('Dashed', 'ditty-news-ticker'), value: 'dashed' },
		{ label: __('Dotted', 'ditty-news-ticker'), value: 'dotted' },
		{ label: __('Double', 'ditty-news-ticker'), value: 'double' },
	];

	return (
		<>
			<InspectorControls>
				{/* General Settings Panel */}
				<PanelBody
					title={__('Display Settings', 'ditty-news-ticker')}
					initialOpen={true}
				>
					<ToggleControl
						label={__('Show Title', 'ditty-news-ticker')}
						checked={showTitle}
						onChange={value => setAttributes({ showTitle: value })}
					/>
					<SelectControl
						label={__('Direction', 'ditty-news-ticker')}
						value={direction}
						options={[
							{ label: __('Left', 'ditty-news-ticker'), value: 'left' },
							{ label: __('Right', 'ditty-news-ticker'), value: 'right' },
						]}
						onChange={value => setAttributes({ direction: value })}
						__nextHasNoMarginBottom
					/>
					<RangeControl
						label={__('Speed', 'ditty-news-ticker')}
						value={speed}
						onChange={value => setAttributes({ speed: value })}
						min={1}
						max={100}
					/>
					<RangeControl
						label={__('Spacing (px)', 'ditty-news-ticker')}
						value={spacing}
						onChange={value => setAttributes({ spacing: value })}
						min={0}
						max={200}
					/>
					<ToggleControl
						label={__('Pause on Hover', 'ditty-news-ticker')}
						checked={hoverPause}
						onChange={value => setAttributes({ hoverPause: value })}
					/>
					{type === 'ticker' && (
						<ToggleControl
							label={__('Clone Items', 'ditty-news-ticker')}
							help={__('Clone items for seamless looping', 'ditty-news-ticker')}
							checked={cloneItems}
							onChange={value => setAttributes({ cloneItems: value })}
						/>
					)}
				</PanelBody>

				{/* Item Styles Panel */}
				<PanelBody
					title={__('Item Styles', 'ditty-news-ticker')}
					initialOpen={false}
				>
					<BaseControl label={__('Background Color', 'ditty-news-ticker')}>
						<ColorPicker
							color={itemBgColor}
							onChange={value => setAttributes({ itemBgColor: value })}
							enableAlpha
						/>
					</BaseControl>
					<TextControl
						label={__('Padding', 'ditty-news-ticker')}
						value={itemPadding}
						onChange={value => setAttributes({ itemPadding: value })}
						placeholder="e.g. 10px"
					/>
					<TextControl
						label={__('Max Width', 'ditty-news-ticker')}
						value={itemMaxWidth}
						onChange={value => setAttributes({ itemMaxWidth: value })}
						placeholder="e.g. 300px"
					/>
					<SelectControl
						label={__('Text Wrap', 'ditty-news-ticker')}
						value={itemElementsWrap}
						options={[
							{ label: __('No Wrap', 'ditty-news-ticker'), value: 'nowrap' },
							{ label: __('Wrap', 'ditty-news-ticker'), value: 'wrap' },
						]}
						onChange={value => setAttributes({ itemElementsWrap: value })}
						__nextHasNoMarginBottom
					/>
					<SelectControl
						label={__('Border Style', 'ditty-news-ticker')}
						value={itemBorderStyle}
						options={borderStyleOptions}
						onChange={value => setAttributes({ itemBorderStyle: value })}
						__nextHasNoMarginBottom
					/>
					{itemBorderStyle && (
						<>
							<TextControl
								label={__('Border Width', 'ditty-news-ticker')}
								value={itemBorderWidth}
								onChange={value => setAttributes({ itemBorderWidth: value })}
								placeholder="e.g. 1px"
							/>
							<BaseControl label={__('Border Color', 'ditty-news-ticker')}>
								<ColorPicker
									color={itemBorderColor}
									onChange={value => setAttributes({ itemBorderColor: value })}
									enableAlpha
								/>
							</BaseControl>
						</>
					)}
					<TextControl
						label={__('Border Radius', 'ditty-news-ticker')}
						value={itemBorderRadius}
						onChange={value => setAttributes({ itemBorderRadius: value })}
						placeholder="e.g. 4px"
					/>
				</PanelBody>
			</InspectorControls>

			{showPlaceholder && (
				<View>
					{innerBlocksProps.children}
					<DittyDisplayPlaceholder name={name} onSelect={selectVariation} />
				</View>
			)}

			{!showPlaceholder && <div {...innerBlocksProps} />}
		</>
	);
}
