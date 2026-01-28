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
	const carousel = config.carousel || {};

	// Build Splide options from carousel config
	const options = {};

	// Gap setting - use carousel.gap if available (in CSS format), otherwise fallback to spacing
	if (carousel.gap !== undefined) {
		// Gap is already in CSS format (e.g., "25px", "1rem", etc.)
		options.gap = carousel.gap;
	} else {
		// Fallback to spacing value (for backward compatibility)
		const spacing = parseInt(config.spacing, 10) || 25;
		options.gap = spacing;
	}

	// Type/Loop settings
	if (carousel.rewind) {
		options.type = 'slide';
		options.rewind = true;
	} else {
		options.type = carousel.loop !== false ? 'loop' : 'slide';
	}

	// Speed settings
	if (carousel.speed !== undefined) {
		options.speed = parseInt(carousel.speed, 10);
	}
	if (carousel.rewindSpeed) {
		options.rewindSpeed = parseInt(carousel.rewindSpeed, 10);
	}
	if (carousel.rewindByDrag !== undefined) {
		options.rewindByDrag = Boolean(carousel.rewindByDrag);
	}

	// Dimension settings
	if (carousel.height) {
		options.height = carousel.height;
	}
	if (carousel.fixedWidth) {
		options.fixedWidth = carousel.fixedWidth;
	}
	if (carousel.fixedHeight) {
		options.fixedHeight = carousel.fixedHeight;
	}
	if (carousel.heightRatio) {
		options.heightRatio = parseFloat(carousel.heightRatio);
	}
	if (carousel.autoWidth !== undefined) {
		options.autoWidth = Boolean(carousel.autoWidth);
	}
	if (carousel.autoHeight !== undefined) {
		options.autoHeight = Boolean(carousel.autoHeight);
	}

	// Layout settings
	if (carousel.start !== undefined) {
		options.start = parseInt(carousel.start, 10);
	}
	if (carousel.perPage !== undefined) {
		options.perPage = parseInt(carousel.perPage, 10);
	}
	if (carousel.perMove) {
		options.perMove = parseInt(carousel.perMove, 10);
	}
	if (carousel.focus) {
		// Focus can be 'center' or a number
		options.focus = carousel.focus === 'center' ? 'center' : parseInt(carousel.focus, 10);
	}

	// Navigation settings
	if (carousel.arrows !== undefined) {
		options.arrows = Boolean(carousel.arrows);
	}
	if (carousel.pagination !== undefined) {
		options.pagination = Boolean(carousel.pagination);
	}
	if (carousel.paginationDirection) {
		options.paginationDirection = carousel.paginationDirection;
	}
	if (carousel.direction) {
		options.direction = carousel.direction;
	}

	// Animation settings
	if (carousel.easing) {
		options.easing = carousel.easing;
	}
	if (carousel.updateOnMove !== undefined) {
		options.updateOnMove = Boolean(carousel.updateOnMove);
	}

	// Interaction settings
	if (carousel.drag !== undefined) {
		// Drag can be true, false, or 'free'
		if (carousel.drag === 'false') {
			options.drag = false;
		} else if (carousel.drag === 'free') {
			options.drag = 'free';
		} else {
			options.drag = true;
		}
	}
	if (carousel.snap !== undefined) {
		options.snap = Boolean(carousel.snap);
	}

	// Autoplay settings
	if (carousel.autoplay !== undefined) {
		options.autoplay = Boolean(carousel.autoplay);
	}
	if (carousel.interval !== undefined) {
		options.interval = parseInt(carousel.interval, 10);
	}
	if (carousel.pauseOnHover !== undefined) {
		options.pauseOnHover = Boolean(carousel.pauseOnHover);
	}
	if (carousel.pauseOnFocus !== undefined) {
		options.pauseOnFocus = Boolean(carousel.pauseOnFocus);
	}
	if (carousel.resetProgress !== undefined) {
		options.resetProgress = Boolean(carousel.resetProgress);
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
	const dittyType = config.type || 'list';

	if (dittyType === 'ticker') {
		// Use custom DittyTicker for ticker type
		const tickerConfig = buildTickerConfig(config);
		const ticker = new DittyTicker(element, tickerConfig);

		// Store reference on element
		element._dittyTicker = ticker;
	} else {
		// Use Splide for list/carousel type
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
