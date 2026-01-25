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
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { View } from '@wordpress/primitives';

import DittyDisplayPlaceholder, {
	useShouldShowPlaceholder,
} from './placeholder';

/**
 * Template for inner blocks - display items
 */
const TEMPLATE = [
	['ditty/display-item'],
	['ditty/display-item'],
	['ditty/display-item'],
];

/**
 * Edit Component
 */
export default function Edit({ attributes, setAttributes, clientId, name }) {
	const {
		type,
		direction,
		speed,
		hoverPause,
		cloneItems,
		itemMaxWidth,
		itemElementsWrap,
		minHeight,
		fillHeight,
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

	// Check if this is a vertical ticker
	const isVertical =
		type === 'ticker' && (direction === 'up' || direction === 'down');

	// Build editor styles for vertical tickers
	const editorStyles = {};
	if (isVertical) {
		if (fillHeight) {
			editorStyles.flexDirection = 'column';
			editorStyles.flex = '1';
			editorStyles.height = '100%';
		}
		if (minHeight) {
			editorStyles.minHeight = minHeight;
		}
	}

	const blockProps = useBlockProps({
		className: `ditty-display-editor ditty-display-type-${type}`,
		style: editorStyles,
	});

	const [showPlaceholder, setShowPlaceholder] = useShouldShowPlaceholder({
		attributes,
		hasInnerBlocks,
	});

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ['ditty/display-item'],
		template: TEMPLATE,
		templateLock: false,
		renderAppender: InnerBlocks.ButtonBlockAppender,
	});

	const { selectBlock } = useDispatch(blockEditorStore);

	const selectVariation = nextVariation => {
		setAttributes(nextVariation.attributes);
		selectBlock(clientId, -1);
		setShowPlaceholder(false);
	};

	return (
		<>
			<InspectorControls>
				{/* General Settings Panel */}
				<PanelBody
					title={__('Display Settings', 'ditty-news-ticker')}
					initialOpen={true}
				>
					<SelectControl
						__next40pxDefaultSize
						label={__('Direction', 'ditty-news-ticker')}
						value={direction}
						options={
							type === 'ticker'
								? [
										{ label: __('Left', 'ditty-news-ticker'), value: 'left' },
										{ label: __('Right', 'ditty-news-ticker'), value: 'right' },
										{ label: __('Up', 'ditty-news-ticker'), value: 'up' },
										{ label: __('Down', 'ditty-news-ticker'), value: 'down' },
								  ]
								: [
										{ label: __('Left', 'ditty-news-ticker'), value: 'left' },
										{ label: __('Right', 'ditty-news-ticker'), value: 'right' },
								  ]
						}
						onChange={value => setAttributes({ direction: value })}
						__nextHasNoMarginBottom
					/>
					<RangeControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={__('Speed', 'ditty-news-ticker')}
						value={speed}
						onChange={value => setAttributes({ speed: value })}
						min={1}
						max={100}
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={__('Pause on Hover', 'ditty-news-ticker')}
						checked={hoverPause}
						onChange={value => setAttributes({ hoverPause: value })}
					/>
					{type === 'ticker' && (
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Clone Items', 'ditty-news-ticker')}
							help={__('Clone items for seamless looping', 'ditty-news-ticker')}
							checked={cloneItems}
							onChange={value => setAttributes({ cloneItems: value })}
						/>
					)}
					{type === 'ticker' &&
						(direction === 'up' || direction === 'down') && (
							<>
								<ToggleControl
									__nextHasNoMarginBottom
									label={__('Fill Parent Height', 'ditty-news-ticker')}
									help={__(
										'Stretch ticker to match parent container height',
										'ditty-news-ticker'
									)}
									checked={fillHeight}
									onChange={value => setAttributes({ fillHeight: value })}
								/>
								<UnitControl
									label={__('Min Height', 'ditty-news-ticker')}
									value={minHeight}
									onChange={value => setAttributes({ minHeight: value })}
								/>
							</>
						)}
				</PanelBody>
				{/* Item Settings Panel */}
				<PanelBody
					title={__('Item Settings', 'ditty-news-ticker')}
					initialOpen={false}
				>
					<UnitControl
						label={__('Max Width', 'ditty-news-ticker')}
						value={itemMaxWidth}
						onChange={value => setAttributes({ itemMaxWidth: value })}
					/>
					<SelectControl
						__next40pxDefaultSize
						label={__('Text Wrap', 'ditty-news-ticker')}
						value={itemElementsWrap}
						options={[
							{ label: __('No Wrap', 'ditty-news-ticker'), value: 'nowrap' },
							{ label: __('Wrap', 'ditty-news-ticker'), value: 'wrap' },
						]}
						onChange={value => setAttributes({ itemElementsWrap: value })}
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
