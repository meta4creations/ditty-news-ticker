/**
 * Ditty Display Block - Ticker Preview Component
 *
 * Renders the ticker preview in the editor when editMode is 'preview'
 */

import {
	RawHTML,
	useEffect,
	useMemo,
	useRef,
	useState,
} from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { serialize } from '@wordpress/blocks';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import DittyTicker from '../../../v4/DittyTicker';

/**
 * Build ticker configuration from attributes
 *
 * @param {Object} attributes Block attributes
 * @return {Object} DittyTicker config
 */
function buildTickerConfig(attributes) {
	const {
		direction = 'left',
		speed = 10,
		hoverPause = false,
		cloneItems = true,
	} = attributes;

	// Extract blockGap value for spacing
	let spacing = 25;
	const blockGap = attributes?.style?.spacing?.blockGap;
	if (blockGap) {
		// Parse blockGap (e.g., "25px" or "var:preset|spacing|50")
		if (typeof blockGap === 'string') {
			if (blockGap.startsWith('var:preset|')) {
				// For presets, we'll need to estimate or use default
				spacing = 25;
			} else {
				const parsed = parseInt(blockGap, 10);
				if (!isNaN(parsed)) {
					spacing = parsed;
				}
			}
		}
	}

	return {
		direction,
		speed: parseInt(speed, 10),
		spacing,
		hoverPause: Boolean(hoverPause),
		cloneItems: Boolean(cloneItems),
	};
}

/**
 * Ticker Preview Component
 *
 * @param {Object} props Component props
 * @param {Object} props.blockProps Block props from useBlockProps
 * @param {Object} props.innerBlocksProps Inner blocks props
 * @param {Object} props.attributes Block attributes
 * @param {string} props.clientId Block client ID
 * @return {JSX.Element} The ticker preview
 */
export default function TickerPreview({
	blockProps,
	innerBlocksProps,
	attributes,
	clientId,
}) {
	const tickerRef = useRef(null);
	const tickerInstanceRef = useRef(null);

	// Extract ref from blockProps to avoid conflicts
	const { ref: blockPropsRef, ...restBlockProps } = blockProps || {};

	// Pull the actual inner blocks from the editor store
	const innerBlocks = useSelect(
		select => {
			const { getBlocks } = select(blockEditorStore);
			return getBlocks(clientId);
		},
		[clientId]
	);

	// Build ticker configuration
	const tickerConfig = useMemo(
		() => buildTickerConfig(attributes),
		[
			attributes.direction,
			attributes.speed,
			attributes.hoverPause,
			attributes.cloneItems,
			attributes?.style?.spacing?.blockGap,
		]
	);

	// Generate a stable key for ticker config
	const tickerConfigKey = JSON.stringify(tickerConfig);

	// State to track server-rendered HTML for dynamic blocks
	const [renderedBlocks, setRenderedBlocks] = useState({});
	const [renderingBlocks, setRenderingBlocks] = useState(new Set());

	/**
	 * Fetch server-rendered HTML for a block
	 */
	const fetchBlockHTML = async block => {
		const blockId = block.clientId;
		const innerBlock = block?.innerBlocks?.[0];
		if (!innerBlock) {
			return;
		}

		// Skip if already rendering or rendered
		if (renderingBlocks.has(blockId) || renderedBlocks[blockId]) {
			return;
		}

		// Mark as rendering
		setRenderingBlocks(prev => new Set(prev).add(blockId));

		try {
			// Use the WordPress block renderer API to render the inner block
			const path = addQueryArgs(`/wp/v2/block-renderer/${innerBlock.name}`, {
				context: 'edit',
			});

			const response = await apiFetch({
				path,
				method: 'POST',
				data: {
					attributes: innerBlock.attributes || {},
				},
			});

			if (response && response.rendered) {
				setRenderedBlocks(prev => ({
					...prev,
					[blockId]: response.rendered,
				}));
			}
		} catch (error) {
			console.error('Error rendering block:', error);
		} finally {
			// Remove from rendering set
			setRenderingBlocks(prev => {
				const next = new Set(prev);
				next.delete(blockId);
				return next;
			});
		}
	};

	// Check if a block or its children contain dynamic blocks
	const hasDynamicBlocks = block => {
		if (!block) return false;

		// List of known dynamic block types
		const dynamicBlockTypes = [
			'ditty/posts-feed',
			// Add other dynamic block types here as needed
		];

		// Check this block
		if (dynamicBlockTypes.includes(block.name)) {
			return true;
		}

		// Check inner blocks recursively
		if (block.innerBlocks && block.innerBlocks.length > 0) {
			return block.innerBlocks.some(innerBlock => hasDynamicBlocks(innerBlock));
		}

		return false;
	};

	// Trigger fetching for blocks that need server-side rendering
	useEffect(() => {
		if (!innerBlocks || innerBlocks.length === 0) return;

		innerBlocks.forEach(block => {
			if (hasDynamicBlocks(block)) {
				fetchBlockHTML(block);
			}
		});
	}, [innerBlocks]);

	/**
	 * Build item nodes for each `ditty/display-item`.
	 *
	 * This is the same rendering logic as in slider.js - we manually construct
	 * the display item wrapper with its styles and serialize the inner content.
	 * For dynamic blocks like posts-feed, we use server-rendered HTML.
	 */
	const items = useMemo(() => {
		const resolveWpPresetVar = value => {
			if (typeof value === 'string' && value.startsWith('var:preset|')) {
				const parts = value.split('|');
				if (parts.length === 3) {
					return `var(--wp--preset--${parts[1]}--${parts[2]})`;
				}
			}
			return value;
		};

		const renderDisplayItem = (block, key) => {
			const blockId = block.clientId;

			// If this block has dynamic content and we have rendered HTML, use it
			if (hasDynamicBlocks(block) && renderedBlocks[blockId]) {
				return <RawHTML key={key}>{renderedBlocks[blockId]}</RawHTML>;
			}

			// If block is being rendered, show a loading state
			if (hasDynamicBlocks(block) && renderingBlocks.has(blockId)) {
				return (
					<div
						key={key}
						className="ditty-display__item ditty-display__item--loading"
					>
						<div className="ditty-display__item__elements">Loading...</div>
					</div>
				);
			}

			// Otherwise, render statically (existing code)
			const a = block?.attributes || {};
			const s = a.style || {};

			const classes = ['wp-block-ditty-display-item', 'ditty-display__item'];
			if (a.className) {
				classes.push(a.className);
			}
			if (a.backgroundColor) {
				classes.push(
					`has-${a.backgroundColor}-background-color`,
					'has-background'
				);
			}
			if (a.textColor) {
				classes.push(`has-${a.textColor}-color`, 'has-text-color');
			}
			if (a.fontSize) {
				classes.push(`has-${a.fontSize}-font-size`);
			}

			const wrapperStyle = {};

			// Support: padding
			const padding = s?.spacing?.padding;
			if (padding) {
				if (padding.top)
					wrapperStyle.paddingTop = resolveWpPresetVar(padding.top);
				if (padding.right)
					wrapperStyle.paddingRight = resolveWpPresetVar(padding.right);
				if (padding.bottom)
					wrapperStyle.paddingBottom = resolveWpPresetVar(padding.bottom);
				if (padding.left)
					wrapperStyle.paddingLeft = resolveWpPresetVar(padding.left);
			}

			// Support: border radius
			const radius = s?.border?.radius;
			if (radius) {
				if (typeof radius === 'string' || typeof radius === 'number') {
					wrapperStyle.borderRadius = resolveWpPresetVar(radius);
				} else {
					if (radius.topLeft)
						wrapperStyle.borderTopLeftRadius = resolveWpPresetVar(
							radius.topLeft
						);
					if (radius.topRight)
						wrapperStyle.borderTopRightRadius = resolveWpPresetVar(
							radius.topRight
						);
					if (radius.bottomRight)
						wrapperStyle.borderBottomRightRadius = resolveWpPresetVar(
							radius.bottomRight
						);
					if (radius.bottomLeft)
						wrapperStyle.borderBottomLeftRadius = resolveWpPresetVar(
							radius.bottomLeft
						);
				}
			}

			// Optional: explicit background/text from style object (if present)
			if (s?.color?.background) {
				wrapperStyle.backgroundColor = resolveWpPresetVar(s.color.background);
			}
			if (s?.color?.text) {
				wrapperStyle.color = resolveWpPresetVar(s.color.text);
			}

			// Parent display settings that affect the inner elements wrapper
			const itemMaxWidth = attributes?.itemMaxWidth || '';
			const itemElementsWrap = attributes?.itemElementsWrap || 'nowrap';
			const elementsStyle = {};
			if (itemMaxWidth) {
				elementsStyle.maxWidth = resolveWpPresetVar(itemMaxWidth);
			}
			if (itemElementsWrap === 'nowrap') {
				elementsStyle.whiteSpace = 'nowrap';
			}

			const innerHtml = serialize(block?.innerBlocks || []);

			return (
				<div key={key} className={classes.join(' ')} style={wrapperStyle}>
					<div className="ditty-display__item__elements" style={elementsStyle}>
						<RawHTML>{innerHtml}</RawHTML>
					</div>
				</div>
			);
		};

		if (Array.isArray(innerBlocks) && innerBlocks.length) {
			return innerBlocks.map(block => ({
				key: block.clientId,
				node: renderDisplayItem(block, block.clientId),
				innerLen: serialize(block?.innerBlocks || []).length,
			}));
		}

		return [];
	}, [
		attributes?.itemElementsWrap,
		attributes?.itemMaxWidth,
		innerBlocks,
		renderedBlocks,
		renderingBlocks,
	]);

	// Generate a stable key for items
	const itemsKey = JSON.stringify(
		items.map(item => ({ key: item.key, innerLen: item.innerLen }))
	);

	// Merge refs callback
	const mergedRef = element => {
		tickerRef.current = element;
		// If blockProps had a ref, call it too
		if (blockPropsRef) {
			if (typeof blockPropsRef === 'function') {
				blockPropsRef(element);
			} else if (blockPropsRef?.current !== undefined) {
				blockPropsRef.current = element;
			}
		}
	};

	// Calculate vertical styles if needed
	const isVertical =
		attributes.direction === 'up' || attributes.direction === 'down';
	const contentsStyle = {};
	const itemsStyle = {};

	if (isVertical) {
		if (attributes.fillHeight) {
			contentsStyle.flexDirection = 'column';
			contentsStyle.flex = '1';
			contentsStyle.height = '100%';
			itemsStyle.flexDirection = 'column';
			itemsStyle.flex = '1';
			itemsStyle.height = '100%';
		}
		if (attributes.minHeight) {
			contentsStyle.minHeight = attributes.minHeight;
			itemsStyle.minHeight = attributes.minHeight;
		}
	}

	// Initialize and manage DittyTicker
	useEffect(() => {
		if (!tickerRef.current) {
			return;
		}

		// Destroy existing ticker if it exists
		if (tickerInstanceRef.current) {
			tickerInstanceRef.current.destroy();
			tickerInstanceRef.current = null;
		}

		// Initialize new ticker with current config
		try {
			const ticker = new DittyTicker(tickerRef.current, tickerConfig);
			tickerInstanceRef.current = ticker;
		} catch (error) {
			console.warn('DittyTicker initialization failed:', error);
		}

		// Cleanup on unmount
		return () => {
			if (tickerInstanceRef.current) {
				tickerInstanceRef.current.destroy();
				tickerInstanceRef.current = null;
			}
		};
	}, [tickerConfigKey, itemsKey]);

	return (
		<div
			{...restBlockProps}
			ref={mergedRef}
			// Match frontend wrapper classes so ticker CSS applies (absolute positioning / hiding originals).
			className={`${
				restBlockProps.className || ''
			} ditty-display ditty-type-ticker`.trim()}
		>
			<div className="ditty-display__contents" style={contentsStyle}>
				<div className="ditty-display__items" style={itemsStyle}>
					{items.map((item, index) => item.node)}
				</div>
			</div>
		</div>
	);
}
