/**
 * Ditty V4 - Display Integration
 *
 * Handles initialization of Ditty displays using either:
 * - Custom DittyTicker for ticker type (continuous scrolling)
 * - Splide for list/carousel type (slide-based)
 *
 * @package Ditty
 * @since   4.0
 */

import Splide from '@splidejs/splide';
import '@splidejs/splide/css';
import DittyTicker from './DittyTicker';
import './dittyV4.scss';

/**
 * Parse the Ditty configuration from data attribute
 *
 * @param {HTMLElement} element - The Ditty element
 * @returns {Object} Configuration object
 */
function parseConfig(element) {
	const configAttr = element.dataset.dittyConfig;
	if (!configAttr) {
		return { type: 'list' };
	}

	try {
		return JSON.parse(configAttr);
	} catch (e) {
		console.warn('Ditty: Failed to parse config', e);
		return { type: 'list' };
	}
}

/**
 * Build ticker configuration from Ditty config
 *
 * @param {Object} config - Ditty configuration
 * @returns {Object} Ticker configuration
 */
function buildTickerConfig(config) {
	return {
		direction: config.direction || 'left',
		speed: parseInt(config.speed, 10) || 10,
		spacing: parseInt(config.spacing, 10) || 25,
		hoverPause: Boolean(config.hoverPause),
		cloneItems: config.cloneItems !== 'no',
	};
}

/**
 * Build Splide options for list/carousel type
 *
 * @param {Object} config - Ditty configuration
 * @returns {Object} Splide options
 */
function buildListOptions(config) {
	const slider = config.slider || {};

	// Build Splide options from slider config
	const options = {};

	// Gap setting - use slider.gap if available (in CSS format), otherwise fallback to spacing
	if (slider.gap !== undefined) {
		// Gap is already in CSS format (e.g., "25px", "1rem", etc.)
		options.gap = slider.gap;
	} else {
		// Fallback to spacing value (for backward compatibility)
		const spacing = parseInt(config.spacing, 10) || 25;
		options.gap = spacing;
	}

	// Type/Loop settings
	if (slider.rewind) {
		options.type = 'slide';
		options.rewind = true;
	} else {
		options.type = slider.loop !== false ? 'loop' : 'slide';
	}

	// Speed settings
	if (slider.speed !== undefined) {
		options.speed = parseInt(slider.speed, 10);
	}
	if (slider.rewindSpeed) {
		options.rewindSpeed = parseInt(slider.rewindSpeed, 10);
	}
	if (slider.rewindByDrag !== undefined) {
		options.rewindByDrag = Boolean(slider.rewindByDrag);
	}

	// Dimension settings
	if (slider.height) {
		options.height = slider.height;
	}
	if (slider.fixedWidth) {
		options.fixedWidth = slider.fixedWidth;
	}
	if (slider.fixedHeight) {
		options.fixedHeight = slider.fixedHeight;
	}
	if (slider.heightRatio) {
		options.heightRatio = parseFloat(slider.heightRatio);
	}
	if (slider.autoWidth !== undefined) {
		options.autoWidth = Boolean(slider.autoWidth);
	}
	if (slider.autoHeight !== undefined) {
		options.autoHeight = Boolean(slider.autoHeight);
	}

	// Layout settings
	if (slider.start !== undefined) {
		options.start = parseInt(slider.start, 10);
	}
	if (slider.perPage !== undefined) {
		options.perPage = parseInt(slider.perPage, 10);
	}
	if (slider.perMove) {
		options.perMove = parseInt(slider.perMove, 10);
	}
	if (slider.focus) {
		// Focus can be 'center' or a number
		options.focus =
			slider.focus === 'center' ? 'center' : parseInt(slider.focus, 10);
	}

	// Navigation settings
	if (slider.arrows !== undefined) {
		options.arrows = Boolean(slider.arrows);
	}
	if (slider.pagination !== undefined) {
		options.pagination = Boolean(slider.pagination);
	}
	if (slider.paginationDirection) {
		options.paginationDirection = slider.paginationDirection;
	}
	if (slider.direction) {
		options.direction = slider.direction;
	}

	// Animation settings
	if (slider.easing) {
		options.easing = slider.easing;
	}
	if (slider.updateOnMove !== undefined) {
		options.updateOnMove = Boolean(slider.updateOnMove);
	}

	// Interaction settings
	if (slider.drag !== undefined) {
		// Drag can be true, false, or 'free'
		if (slider.drag === 'false') {
			options.drag = false;
		} else if (slider.drag === 'free') {
			options.drag = 'free';
		} else {
			options.drag = true;
		}
	}
	if (slider.snap !== undefined) {
		options.snap = Boolean(slider.snap);
	}

	// Autoplay settings
	if (slider.autoplay !== undefined) {
		options.autoplay = Boolean(slider.autoplay);
	}
	if (slider.interval !== undefined) {
		options.interval = parseInt(slider.interval, 10);
	}
	if (slider.pauseOnHover !== undefined) {
		options.pauseOnHover = Boolean(slider.pauseOnHover);
	}
	if (slider.pauseOnFocus !== undefined) {
		options.pauseOnFocus = Boolean(slider.pauseOnFocus);
	}
	if (slider.resetProgress !== undefined) {
		options.resetProgress = Boolean(slider.resetProgress);
	}

	return options;
}

/**
 * Initialize a single Ditty element
 *
 * @param {HTMLElement} element - The Ditty element
 */
function initDittyElement(element) {
	// Skip if already initialized
	if (element.classList.contains('ditty-display--initialized')) {
		return;
	}

	const config = parseConfig(element);
	const dittyType = config.type || 'slider';

	if (dittyType === 'ticker') {
		// Use custom DittyTicker for ticker type
		const tickerConfig = buildTickerConfig(config);
		const ticker = new DittyTicker(element, tickerConfig);

		// Store reference on element
		element._dittyTicker = ticker;
	} else if (dittyType === 'slider' || dittyType === 'list') {
		// Use Splide for slider/carousel type (support 'list' for backwards compatibility)
		const splideOptions = buildListOptions(config);
		const splide = new Splide(element, splideOptions);
		splide.mount();

		// Store reference and mark as initialized
		element._dittySplide = splide;
		element.classList.add('ditty-display--initialized');

		// Set up visibility observer for Splide autoplay
		initSplideVisibilityObserver(element, splide);
	}
}

/**
 * Initialize visibility observer for Splide to pause/play when off-screen
 *
 * @param {HTMLElement} element - The Ditty element
 * @param {Splide} splide - The Splide instance
 */
function initSplideVisibilityObserver(element, splide) {
	if (typeof IntersectionObserver === 'undefined') {
		return;
	}

	// Only observe if autoplay is enabled
	if (!splide.options.autoplay) {
		return;
	}

	let isVisible = false;

	const observer = new IntersectionObserver(
		entries => {
			entries.forEach(entry => {
				const wasVisible = isVisible;
				isVisible = entry.isIntersecting;

				if (wasVisible !== isVisible) {
					if (isVisible) {
						// Resume autoplay when visible
						splide.Components.Autoplay.play();
					} else {
						// Pause autoplay when not visible
						splide.Components.Autoplay.pause();
					}
				}
			});
		},
		{ threshold: 0.01 }
	);

	observer.observe(element);

	// Store observer reference for cleanup
	element._dittyVisibilityObserver = observer;
}

/**
 * Initialize all Ditty elements on the page
 */
function initDittySplide() {
	const dittyElements = document.querySelectorAll('.ditty-display');
	dittyElements.forEach(initDittyElement);
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initDittySplide);
} else {
	initDittySplide();
}

// Export for potential external use
window.initDittySplide = initDittySplide;
window.DittyTicker = DittyTicker;
