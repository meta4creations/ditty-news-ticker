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
 * Slider icon - slides with navigation
 */
const sliderIcon = (
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
		attributes: {
			type: 'ticker',
			style: {
				spacing: {
					blockGap: '25px',
				},
			},
		},
		isDefault: true,
		scope: ['block', 'inserter', 'transform'],
		isActive: blockAttributes => blockAttributes.type === 'ticker',
		icon: tickerIcon,
		innerBlocks: [
			['ditty/display-item'],
			['ditty/display-item'],
			['ditty/display-item'],
		],
	},
	{
		name: 'slider',
		title: __('Slider', 'ditty-news-ticker'),
		description: __('A slider/carousel display.', 'ditty-news-ticker'),
		attributes: {
			type: 'slider',
			style: {
				spacing: {
					blockGap: '25px',
				},
			},
		},
		scope: ['block', 'inserter', 'transform'],
		isActive: blockAttributes => blockAttributes.type === 'slider',
		icon: sliderIcon,
		innerBlocks: [
			['ditty/display-item'],
			['ditty/display-item'],
			['ditty/display-item'],
		],
	},
];

export default variations;

export { tickerIcon, sliderIcon };
