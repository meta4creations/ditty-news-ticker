/**
 * Ditty Display Block - Slider Preview Component
 *
 * Renders the slider preview in the editor when editMode is 'preview'
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
import Splide from '@splidejs/splide';
import '@splidejs/splide/css';

/**
 * Build Splide options from slider attributes
 *
 * @param {Object} attributes Block attributes
 * @return {Object} Splide options
 */
function buildSplideOptions(attributes) {
	const {
		sliderLoop = true,
		sliderSpeed = 400,
		sliderRewind = false,
		sliderRewindSpeed = 0,
		sliderRewindByDrag = false,
		sliderHeight = '',
		sliderFixedWidth = '',
		sliderFixedHeight = '',
		sliderHeightRatio = 0,
		sliderAutoWidth = false,
		sliderAutoHeight = false,
		sliderStart = 0,
		sliderPerPage = 1,
		sliderPerMove = 0,
		sliderFocus = '',
		sliderArrows = true,
		sliderPagination = true,
		sliderPaginationDirection = '',
		sliderEasing = 'cubic-bezier(0.25, 1, 0.5, 1)',
		sliderDrag = 'true',
		sliderSnap = false,
		sliderAutoplay = true,
		sliderInterval = 3000,
		sliderPauseOnHover = true,
		sliderPauseOnFocus = true,
		sliderResetProgress = true,
		sliderDirection = 'ltr',
		sliderUpdateOnMove = false,
	} = attributes;

	const resolveWpPresetVar = value => {
		// Gutenberg stores preset refs like: "var:preset|spacing|20"
		// Convert to CSS var: "var(--wp--preset--spacing--20)"
		if (typeof value === 'string' && value.startsWith('var:preset|')) {
			const parts = value.split('|'); // ["var:preset", "spacing", "20"]
			if (parts.length === 3) {
				return `var(--wp--preset--${parts[1]}--${parts[2]})`;
			}
		}
		return value;
	};

	// Get gap from block supports (style.spacing.blockGap)
	const gap =
		resolveWpPresetVar(attributes?.style?.spacing?.blockGap) || '25px';

	const options = {
		gap: gap,
	};

	// Type/Loop settings
	if (sliderRewind) {
		options.type = 'slide';
		options.rewind = true;
	} else {
		options.type = sliderLoop !== false ? 'loop' : 'slide';
	}

	// Speed settings
	if (sliderSpeed !== undefined) {
		options.speed = parseInt(sliderSpeed, 10);
	}
	if (sliderRewindSpeed) {
		options.rewindSpeed = parseInt(sliderRewindSpeed, 10);
	}
	if (sliderRewindByDrag !== undefined) {
		options.rewindByDrag = Boolean(sliderRewindByDrag);
	}

	// Dimension settings
	if (sliderHeight) {
		options.height = resolveWpPresetVar(sliderHeight);
	}
	if (sliderFixedWidth) {
		options.fixedWidth = resolveWpPresetVar(sliderFixedWidth);
	}
	if (sliderFixedHeight) {
		options.fixedHeight = resolveWpPresetVar(sliderFixedHeight);
	}
	if (sliderHeightRatio) {
		options.heightRatio = parseFloat(sliderHeightRatio);
	}
	// Important: only treat literal boolean true as enabled.
	// (Avoid Boolean('false') === true if values ever come through as strings.)
	options.autoWidth = sliderAutoWidth === true;
	options.autoHeight = sliderAutoHeight === true;

	// Layout settings
	if (sliderStart !== undefined) {
		options.start = parseInt(sliderStart, 10);
	}
	if (sliderPerPage !== undefined) {
		options.perPage = parseInt(sliderPerPage, 10);
	}
	if (sliderPerMove) {
		options.perMove = parseInt(sliderPerMove, 10);
	}
	if (sliderFocus) {
		options.focus =
			sliderFocus === 'center' ? 'center' : parseInt(sliderFocus, 10);
	}

	// Navigation settings
	options.arrows = sliderArrows !== false;
	options.pagination = sliderPagination !== false;
	if (sliderPaginationDirection) {
		options.paginationDirection = sliderPaginationDirection;
	}
	if (sliderDirection) {
		options.direction = sliderDirection;
	}

	// Animation settings
	if (sliderEasing) {
		options.easing = sliderEasing;
	}
	if (sliderUpdateOnMove !== undefined) {
		options.updateOnMove = Boolean(sliderUpdateOnMove);
	}

	// Interaction settings
	if (sliderDrag !== undefined) {
		if (sliderDrag === 'false') {
			options.drag = false;
		} else if (sliderDrag === 'free') {
			options.drag = 'free';
		} else {
			options.drag = true;
		}
	}
	if (sliderSnap !== undefined) {
		options.snap = Boolean(sliderSnap);
	}

	// Autoplay settings
	options.autoplay = sliderAutoplay !== false;
	if (sliderInterval !== undefined) {
		options.interval = parseInt(sliderInterval, 10);
	}
	options.pauseOnHover = sliderPauseOnHover !== false;
	options.pauseOnFocus = sliderPauseOnFocus !== false;
	options.resetProgress = sliderResetProgress !== false;

	return options;
}

/**
 * Slider Preview Component
 *
 * @param {Object} props Component props
 * @param {Object} props.blockProps Block props from useBlockProps
 * @param {Object} props.innerBlocksProps Inner blocks props
 * @param {Object} props.attributes Block attributes
 * @param {string} props.clientId Block client ID
 * @return {JSX.Element} The slider preview
 */
export default function SliderPreview({
	blockProps,
	innerBlocksProps,
	attributes,
	clientId,
}) {
	const splideRef = useRef(null);
	const splideInstanceRef = useRef(null);

	// Extract ref from blockProps to avoid conflicts
	const { ref: blockPropsRef, ...restBlockProps } = blockProps || {};

	// Pull the actual inner blocks from the editor store.
	// `innerBlocksProps.children` is a single wrapper element (it contains *all* inner blocks),
	// so we can't use it to create one slide per block.
	const innerBlocks = useSelect(
		select => {
			const { getBlocks } = select(blockEditorStore);
			return getBlocks(clientId);
		},
		[clientId]
	);

	// State to track server-rendered HTML for dynamic blocks
	const [renderedBlocks, setRenderedBlocks] = useState([]);
	const [renderingBlocks, setRenderingBlocks] = useState(new Set());

	console.log('renderedBlocks', renderedBlocks);

	/**
	 * Fetch server-rendered HTML for a block
	 */
	const fetchBlockHTML = async block => {
		const blockId = block.clientId;

		// Skip if already rendering or rendered
		if (renderingBlocks.has(blockId) || renderedBlocks[blockId]) {
			return;
		}

		// Mark as rendering
		setRenderingBlocks(prev => new Set(prev).add(blockId));

		try {
			// Use the WordPress block renderer API to render the inner block
			const path = addQueryArgs(`/wp/v2/block-renderer/${block.name}`, {
				context: 'edit',
			});

			const response = await apiFetch({
				path,
				method: 'POST',
				data: {
					attributes: block.attributes || {},
				},
			});

			const items = response.rendered.match(/<li\b[^>]*>[\s\S]*?<\/li>/gi);
			if (response && response.rendered && items && items.length > 0) {
				setRenderedBlocks(prev => [...prev, ...items]);
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

		innerBlocks.forEach((block, index) => {
			if (hasDynamicBlocks(block)) {
				console.log('index', index);
				fetchBlockHTML(block);
			}
		});
	}, [innerBlocks]);

	/**
	 * Build slide nodes for each `ditty/display-item`.
	 *
	 * Notes:
	 * - `serialize([block])` outputs block comments, not the rendered wrapper styles.
	 * - `ditty/display-item` is dynamic and its `save()` does not output its wrapper.
	 * - For static blocks, we render inner content + manually apply wrapper styles
	 *   from the Display Item's attributes (background, padding, radius, etc).
	 * - For dynamic blocks, we use server-rendered HTML from the API.
	 */
	const slides = useMemo(() => {
		const resolveWpPresetVar = value => {
			if (typeof value === 'string' && value.startsWith('var:preset|')) {
				const parts = value.split('|');
				if (parts.length === 3) {
					return `var(--wp--preset--${parts[1]}--${parts[2]})`;
				}
			}
			return value;
		};

		const renderDisplayItem = block => {
			const blockId = block.clientId;

			// If this block has dynamic content and we have rendered HTML, use it
			if (hasDynamicBlocks(block) && renderedBlocks[blockId]) {
				return <RawHTML key={blockId}>{renderedBlocks[blockId]}</RawHTML>;
			}

			// If block is being rendered, show a loading state
			if (hasDynamicBlocks(block) && renderingBlocks.has(blockId)) {
				console.log('hasDynamicBlocks', blockId);
				return (
					<div className="ditty-display__item ditty-display__item--loading">
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

			const innerHtml = serialize(block?.innerBlocks || []);

			return (
				<div className={classes.join(' ')} style={wrapperStyle}>
					<div className="ditty-display__item__elements">
						<RawHTML>{innerHtml}</RawHTML>
					</div>
				</div>
			);
		};

		if (Array.isArray(innerBlocks) && innerBlocks.length) {
			return innerBlocks.map(block => ({
				key: block.clientId,
				node: renderDisplayItem(block),
				innerLen: serialize(block?.innerBlocks || []).length,
			}));
		}

		return [];
	}, [innerBlocks, renderedBlocks, renderingBlocks]);

	// Build a stable options key so we don't destroy/reinit Splide on every render
	// (Gutenberg frequently passes a new `attributes` object even if values didn't change).
	const { splideOptions, splideOptionsKey } = useMemo(() => {
		const options = buildSplideOptions(attributes);
		return {
			splideOptions: options,
			splideOptionsKey: JSON.stringify(options),
		};
	}, [attributes]);

	// Track slide changes (keys + inner lengths is enough to detect meaningful changes)
	const slidesKey = useMemo(() => {
		return slides.map(s => `${s.key}:${s.innerLen}`).join('|');
	}, [slides]);

	// Merge refs: blockProps might have a ref, so we need to handle both
	const mergedRef = element => {
		splideRef.current = element;
		// If blockProps had a ref, call it too
		if (blockPropsRef) {
			if (typeof blockPropsRef === 'function') {
				blockPropsRef(element);
			} else if (blockPropsRef?.current !== undefined) {
				blockPropsRef.current = element;
			}
		}
	};

	useEffect(() => {
		if (!splideRef.current) {
			return;
		}

		// If Splide isn't mounted yet, mount it once.
		if (!splideInstanceRef.current) {
			const splide = new Splide(splideRef.current, splideOptions);
			splide.mount();
			splideInstanceRef.current = splide;
		} else {
			// Update options + refresh when slides/options change.
			// Note: Splide supports updating responsive options via `.options`.
			splideInstanceRef.current.options = splideOptions;
			splideInstanceRef.current.refresh();
		}

		// Cleanup on unmount
		return () => {
			if (splideInstanceRef.current) {
				splideInstanceRef.current.destroy();
				splideInstanceRef.current = null;
			}
		};
	}, [splideOptionsKey, slidesKey]);

	return (
		<div
			{...restBlockProps}
			ref={mergedRef}
			className={`${restBlockProps.className || ''} splide`.trim()}
			// Gutenberg sets `draggable=true` on the block wrapper, which can hijack pointerup
			// and leave Splide in a stuck dragging state. Disable native drag for the slider preview.
			draggable={false}
			onDragStart={e => e.preventDefault()}
		>
			<div className="splide__track">
				<ul className="splide__list">
					{slides.map((slide, index) => (
						<li key={slide.key || index} className="splide__slide">
							{slide.node}
						</li>
					))}
				</ul>
			</div>
		</div>
	);
}
