/**
 * Ditty Display Block - Edit Component
 */

const { __ } = wp.i18n;
import { useSelect, useDispatch } from '@wordpress/data';
import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
	BlockControls,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	ToggleControl,
	SelectControl,
	TextControl,
	__experimentalUnitControl as UnitControl,
	__experimentalNumberControl as NumberControl,
	ToolbarGroup,
	ToolbarButton,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { View } from '@wordpress/primitives';

import DittyDisplayPlaceholder, {
	useShouldShowPlaceholder,
} from './placeholder';
import CarouselPreview from './previews/carousel';

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
		editMode = 'edit',
		carouselLoop,
		carouselSpeed,
		carouselRewind,
		carouselRewindSpeed,
		carouselRewindByDrag,
		carouselHeight,
		carouselFixedWidth,
		carouselFixedHeight,
		carouselHeightRatio,
		carouselAutoWidth,
		carouselAutoHeight,
		carouselStart,
		carouselPerPage,
		carouselPerMove,
		carouselFocus,
		carouselArrows,
		carouselPagination,
		carouselPaginationDirection,
		carouselEasing,
		carouselDrag,
		carouselSnap,
		carouselAutoplay,
		carouselInterval,
		carouselPauseOnHover,
		carouselPauseOnFocus,
		carouselResetProgress,
		carouselDirection,
		carouselUpdateOnMove,
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

	const toggleEditMode = () => {
		setAttributes({
			editMode: editMode === 'edit' ? 'preview' : 'edit',
		});
	};

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon={editMode === 'edit' ? 'visibility' : 'edit'}
						label={
							editMode === 'edit'
								? __('Switch to Preview', 'ditty-news-ticker')
								: __('Switch to Edit', 'ditty-news-ticker')
						}
						onClick={toggleEditMode}
						isPressed={editMode === 'preview'}
					/>
				</ToolbarGroup>
			</BlockControls>
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

				{/* Carousel Layout Settings - Only show for carousel type */}
				{type === 'list' && (
					<PanelBody
						title={__('Carousel Layout', 'ditty-news-ticker')}
						initialOpen={false}
					>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Slides Per Page', 'ditty-news-ticker')}
							help={__(
								'Number of slides to display at once',
								'ditty-news-ticker'
							)}
							value={carouselPerPage}
							onChange={value => setAttributes({ carouselPerPage: value })}
							min={1}
							max={10}
						/>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Slides Per Move', 'ditty-news-ticker')}
							help={__(
								'Number of slides to move at once (0 = same as per page)',
								'ditty-news-ticker'
							)}
							value={carouselPerMove}
							onChange={value => setAttributes({ carouselPerMove: value })}
							min={0}
							max={10}
						/>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Start Index', 'ditty-news-ticker')}
							help={__(
								'The initial slide index to display',
								'ditty-news-ticker'
							)}
							value={carouselStart}
							onChange={value => setAttributes({ carouselStart: value })}
							min={0}
							max={20}
						/>
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Focus', 'ditty-news-ticker')}
							help={__(
								'Which slide should be active when multiple slides are shown',
								'ditty-news-ticker'
							)}
							value={carouselFocus}
							options={[
								{ label: __('Default', 'ditty-news-ticker'), value: '' },
								{ label: __('Center', 'ditty-news-ticker'), value: 'center' },
								{ label: __('Index 0', 'ditty-news-ticker'), value: '0' },
								{ label: __('Index 1', 'ditty-news-ticker'), value: '1' },
								{ label: __('Index 2', 'ditty-news-ticker'), value: '2' },
							]}
							onChange={value => setAttributes({ carouselFocus: value })}
						/>
						<UnitControl
							label={__('Height', 'ditty-news-ticker')}
							help={__(
								'Defines the carousel height (CSS format)',
								'ditty-news-ticker'
							)}
							value={carouselHeight}
							onChange={value => setAttributes({ carouselHeight: value })}
						/>
						<UnitControl
							label={__('Fixed Width', 'ditty-news-ticker')}
							help={__(
								'Fixes width of slides (CSS format)',
								'ditty-news-ticker'
							)}
							value={carouselFixedWidth}
							onChange={value => setAttributes({ carouselFixedWidth: value })}
						/>
						<UnitControl
							label={__('Fixed Height', 'ditty-news-ticker')}
							help={__(
								'Fixes height of slides (CSS format)',
								'ditty-news-ticker'
							)}
							value={carouselFixedHeight}
							onChange={value => setAttributes({ carouselFixedHeight: value })}
						/>
						<TextControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Height Ratio', 'ditty-news-ticker')}
							help={__(
								'Height by ratio to carousel width (e.g., 0.5 = 50% height)',
								'ditty-news-ticker'
							)}
							type="number"
							value={carouselHeightRatio}
							onChange={value =>
								setAttributes({ carouselHeightRatio: parseFloat(value) || 0 })
							}
							min={0}
							max={2}
							step={0.1}
						/>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Auto Width', 'ditty-news-ticker')}
							help={__(
								'Width determined by slide content',
								'ditty-news-ticker'
							)}
							checked={carouselAutoWidth}
							onChange={value => setAttributes({ carouselAutoWidth: value })}
						/>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Auto Height', 'ditty-news-ticker')}
							help={__(
								'Height determined by slide content',
								'ditty-news-ticker'
							)}
							checked={carouselAutoHeight}
							onChange={value => setAttributes({ carouselAutoHeight: value })}
						/>
					</PanelBody>
				)}

				{/* Carousel Navigation Settings - Only show for carousel type */}
				{type === 'list' && (
					<PanelBody
						title={__('Carousel Navigation', 'ditty-news-ticker')}
						initialOpen={false}
					>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Loop', 'ditty-news-ticker')}
							help={__('Enable continuous loop mode', 'ditty-news-ticker')}
							checked={carouselLoop}
							onChange={value => setAttributes({ carouselLoop: value })}
						/>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Rewind', 'ditty-news-ticker')}
							help={__(
								'Rewind to start/end instead of looping',
								'ditty-news-ticker'
							)}
							checked={carouselRewind}
							onChange={value => setAttributes({ carouselRewind: value })}
						/>
						{carouselRewind && (
							<>
								<RangeControl
									__next40pxDefaultSize
									__nextHasNoMarginBottom
									label={__('Rewind Speed (ms)', 'ditty-news-ticker')}
									help={__(
										'Transition speed for rewind (0 = use main speed)',
										'ditty-news-ticker'
									)}
									value={carouselRewindSpeed}
									onChange={value =>
										setAttributes({ carouselRewindSpeed: value })
									}
									min={0}
									max={2000}
									step={100}
								/>
								<ToggleControl
									__nextHasNoMarginBottom
									label={__('Rewind By Drag', 'ditty-news-ticker')}
									help={__('Allow rewind by dragging', 'ditty-news-ticker')}
									checked={carouselRewindByDrag}
									onChange={value =>
										setAttributes({ carouselRewindByDrag: value })
									}
								/>
							</>
						)}
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Direction', 'ditty-news-ticker')}
							value={carouselDirection}
							options={[
								{
									label: __('Left to Right', 'ditty-news-ticker'),
									value: 'ltr',
								},
								{
									label: __('Right to Left', 'ditty-news-ticker'),
									value: 'rtl',
								},
								{
									label: __('Top to Bottom', 'ditty-news-ticker'),
									value: 'ttb',
								},
							]}
							onChange={value => setAttributes({ carouselDirection: value })}
						/>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Show Arrows', 'ditty-news-ticker')}
							checked={carouselArrows}
							onChange={value => setAttributes({ carouselArrows: value })}
						/>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Show Pagination', 'ditty-news-ticker')}
							checked={carouselPagination}
							onChange={value => setAttributes({ carouselPagination: value })}
						/>
						{carouselPagination && (
							<SelectControl
								__next40pxDefaultSize
								__nextHasNoMarginBottom
								label={__('Pagination Direction', 'ditty-news-ticker')}
								help={__(
									'Override pagination direction (leave empty for auto)',
									'ditty-news-ticker'
								)}
								value={carouselPaginationDirection}
								options={[
									{ label: __('Auto', 'ditty-news-ticker'), value: '' },
									{
										label: __('Left to Right', 'ditty-news-ticker'),
										value: 'ltr',
									},
									{
										label: __('Right to Left', 'ditty-news-ticker'),
										value: 'rtl',
									},
									{
										label: __('Top to Bottom', 'ditty-news-ticker'),
										value: 'ttb',
									},
								]}
								onChange={value =>
									setAttributes({ carouselPaginationDirection: value })
								}
							/>
						)}
					</PanelBody>
				)}

				{/* Carousel Animation Settings - Only show for carousel type */}
				{type === 'list' && (
					<PanelBody
						title={__('Carousel Animation', 'ditty-news-ticker')}
						initialOpen={false}
					>
						<RangeControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Transition Speed (ms)', 'ditty-news-ticker')}
							help={__(
								'Speed of slide transitions in milliseconds',
								'ditty-news-ticker'
							)}
							value={carouselSpeed}
							onChange={value => setAttributes({ carouselSpeed: value })}
							min={0}
							max={2000}
							step={100}
						/>
						<TextControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Easing Function', 'ditty-news-ticker')}
							help={__(
								'CSS timing function (e.g., ease, linear, cubic-bezier)',
								'ditty-news-ticker'
							)}
							value={carouselEasing}
							onChange={value => setAttributes({ carouselEasing: value })}
						/>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Update on Move', 'ditty-news-ticker')}
							help={__(
								'Update active status before transition completes',
								'ditty-news-ticker'
							)}
							checked={carouselUpdateOnMove}
							onChange={value => setAttributes({ carouselUpdateOnMove: value })}
						/>
					</PanelBody>
				)}

				{/* Carousel Interaction Settings - Only show for carousel type */}
				{type === 'list' && (
					<PanelBody
						title={__('Carousel Interaction', 'ditty-news-ticker')}
						initialOpen={false}
					>
						<SelectControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__('Drag', 'ditty-news-ticker')}
							help={__(
								'Enable dragging to navigate slides',
								'ditty-news-ticker'
							)}
							value={carouselDrag}
							options={[
								{ label: __('Enabled', 'ditty-news-ticker'), value: 'true' },
								{ label: __('Disabled', 'ditty-news-ticker'), value: 'false' },
								{ label: __('Free Drag', 'ditty-news-ticker'), value: 'free' },
							]}
							onChange={value => setAttributes({ carouselDrag: value })}
						/>
						{carouselDrag === 'free' && (
							<ToggleControl
								__nextHasNoMarginBottom
								label={__('Snap', 'ditty-news-ticker')}
								help={__(
									'Snap to closest slide in free drag mode',
									'ditty-news-ticker'
								)}
								checked={carouselSnap}
								onChange={value => setAttributes({ carouselSnap: value })}
							/>
						)}
					</PanelBody>
				)}

				{/* Carousel Autoplay Settings - Only show for carousel type */}
				{type === 'list' && (
					<PanelBody
						title={__('Carousel Autoplay', 'ditty-news-ticker')}
						initialOpen={false}
					>
						<ToggleControl
							__nextHasNoMarginBottom
							label={__('Enable Autoplay', 'ditty-news-ticker')}
							checked={carouselAutoplay}
							onChange={value => setAttributes({ carouselAutoplay: value })}
						/>
						{carouselAutoplay && (
							<>
								<RangeControl
									__next40pxDefaultSize
									__nextHasNoMarginBottom
									label={__('Interval (ms)', 'ditty-news-ticker')}
									help={__(
										'Time between automatic slide transitions',
										'ditty-news-ticker'
									)}
									value={carouselInterval}
									onChange={value => setAttributes({ carouselInterval: value })}
									min={500}
									max={10000}
									step={500}
								/>
								<ToggleControl
									__nextHasNoMarginBottom
									label={__('Pause on Hover', 'ditty-news-ticker')}
									checked={carouselPauseOnHover}
									onChange={value =>
										setAttributes({ carouselPauseOnHover: value })
									}
								/>
								<ToggleControl
									__nextHasNoMarginBottom
									label={__('Pause on Focus', 'ditty-news-ticker')}
									help={__(
										'Pause when carousel contains focused element',
										'ditty-news-ticker'
									)}
									checked={carouselPauseOnFocus}
									onChange={value =>
										setAttributes({ carouselPauseOnFocus: value })
									}
								/>
								<ToggleControl
									__nextHasNoMarginBottom
									label={__('Reset Progress', 'ditty-news-ticker')}
									help={__(
										'Reset progress when autoplay restarts',
										'ditty-news-ticker'
									)}
									checked={carouselResetProgress}
									onChange={value =>
										setAttributes({ carouselResetProgress: value })
									}
								/>
							</>
						)}
					</PanelBody>
				)}
			</InspectorControls>

			{editMode === 'preview' ? (
				type === 'list' ? (
					<CarouselPreview
						blockProps={blockProps}
						innerBlocksProps={innerBlocksProps}
						attributes={attributes}
						clientId={clientId}
					/>
				) : (
					<div {...blockProps}>
						<ServerSideRender block={name} attributes={attributes} />
					</div>
				)
			) : (
				<>
					{showPlaceholder && (
						<View>
							{innerBlocksProps.children}
							<DittyDisplayPlaceholder name={name} onSelect={selectVariation} />
						</View>
					)}

					{!showPlaceholder && <div {...innerBlocksProps} />}
				</>
			)}
		</>
	);
}
