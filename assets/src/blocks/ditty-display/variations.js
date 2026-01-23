/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Path, SVG } from '@wordpress/components';

/**
 * Ticker icon - horizontal scrolling arrows
 */
const tickerIcon = (
	<SVG
		xmlns="http://www.w3.org/2000/svg"
		width="48"
		height="48"
		viewBox="0 0 48 48"
	>
		<Path d="M4 20h28v8H4v-8zm32 4l8-6v12l-8-6z" />
	</SVG>
);

/**
 * Carousel icon - slides with navigation
 */
const carouselIcon = (
	<SVG
		xmlns="http://www.w3.org/2000/svg"
		width="48"
		height="48"
		viewBox="0 0 48 48"
	>
		<Path d="M10 12h28v24H10V12zM2 18h6v12H2V18zm38 0h6v12h-6V18z" />
	</SVG>
);

/**
 * Block variations
 */
const variations = [
	{
		name: 'ticker',
		title: __('Ticker', 'ditty-news-ticker'),
		description: __(
			'A continuously scrolling ticker display.',
			'ditty-news-ticker'
		),
		attributes: { type: 'ticker' },
		isDefault: true,
		scope: ['block', 'inserter', 'transform'],
		isActive: blockAttributes => blockAttributes.type === 'ticker',
		icon: tickerIcon,
		innerBlocks: [
			['ditty/display-title', { lock: { remove: true, move: true } }],
			['ditty/display-contents', { lock: { remove: true, move: true } }],
		],
	},
	{
		name: 'carousel',
		title: __('Carousel', 'ditty-news-ticker'),
		description: __('A carousel/slider display.', 'ditty-news-ticker'),
		attributes: { type: 'list' },
		scope: ['block', 'inserter', 'transform'],
		isActive: blockAttributes => blockAttributes.type === 'list',
		icon: carouselIcon,
		innerBlocks: [
			['ditty/display-title', { lock: { remove: true, move: true } }],
			['ditty/display-contents', { lock: { remove: true, move: true } }],
		],
	},
];

export default variations;

export { tickerIcon, carouselIcon };
