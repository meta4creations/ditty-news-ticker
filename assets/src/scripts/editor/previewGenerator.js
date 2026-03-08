/**
 * Preview Generator for Client-Side Rendering
 *
 * Generates standalone HTML for iframe srcdoc attribute with v4 scripts inlined.
 *
 * @package Ditty
 * @since   4.0
 */

/**
 * Generate complete HTML document for iframe preview
 *
 * @param {Object} options - Preview options
 * @param {string} options.dittyId - Ditty ID
 * @param {string} options.title - Ditty title
 * @param {Object} options.display - Display settings
 * @param {Array} options.items - Array of display items with HTML
 * @param {Object} options.styles - Custom preview styles
 * @param {string} options.v4CssUrl - URL to v4 CSS file
 * @param {string} options.v4JsUrl - URL to v4 JS file
 * @returns {string} Complete HTML document
 */
export function generatePreviewHTML({
	dittyId = 'preview',
	title = '',
	display = {},
	items = [],
	styles = {},
	v4CssUrl = '',
	v4JsUrl = '',
}) {
	// Build display configuration
	const displayType = display.type || 'ticker';
	const displaySettings = display.settings || {};

	// Merge with v4 defaults
	const config = {
		type: displayType,
		direction: displaySettings.direction || 'left',
		speed: displaySettings.speed || 10,
		spacing: displaySettings.spacing || 25,
		hoverPause: displaySettings.hoverPause || false,
		cloneItems:
			displaySettings.cloneItems !== undefined
				? displaySettings.cloneItems
				: true,
		...displaySettings,
	};

	// Build data attribute
	const dataConfig = JSON.stringify(config)
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#39;');

	// Build container styles
	const containerStyles = buildContainerStyles(config);
	const contentsStyles = buildContentsStyles(config);
	const itemStyles = buildItemStyles(config);

	// Build custom preview styles
	let customStylesCSS = '';
	if (styles && Object.keys(styles).length > 0) {
		const styleProps = Object.entries(styles)
			.map(([key, value]) => {
				if (value) {
					// Handle spacing object (top/right/bottom/left)
					if (typeof value === 'object' && value !== null) {
						const order = ['top', 'right', 'bottom', 'left'];
						const parts = order
							.map(side => value[side])
							.filter(part => part !== undefined && part !== '');
						value = parts.length ? parts.join(' ') : '';
					}

					// Convert camelCase to kebab-case
					const cssKey = key.replace(/([A-Z])/g, '-$1').toLowerCase();
					return `${cssKey}: ${value}`;
				}
				return '';
			})
			.filter(s => s)
			.join('; ');

		if (styleProps) {
			customStylesCSS = `.ditty-display { ${styleProps}; }`;
		}
	}

	// Render items HTML
	const itemsHTML =
		items && items.length > 0
			? items
					.map(item => {
						const itemHTML = item.html || '';
						if (displayType === 'ticker') {
							return `<div class="ditty-display__item"${
								itemStyles ? ` style="${itemStyles}"` : ''
							}>${itemHTML}</div>`;
						} else {
							// For slider/list, wrap in Splide structure
							return `<li class="splide__slide"><div class="ditty-display__item"${
								itemStyles ? ` style="${itemStyles}"` : ''
							}>${itemHTML}</div></li>`;
						}
					})
					.join('')
			: '<div class="ditty-preview-empty"><p>No items to display</p></div>';

	// Build the display HTML
	let displayHTML = '';
	const containerClass =
		displayType === 'ticker'
			? 'ditty-display ditty-type-ticker'
			: `ditty-display ditty-type-${displayType} splide`;

	displayHTML += `<div class="${containerClass}" data-ditty-config="${dataConfig}"${
		containerStyles ? ` style="${containerStyles}"` : ''
	}>`;

	// Add title if present
	if (title && config.titleDisplay !== 'none') {
		displayHTML += buildTitleHTML(title, config);
	}

	// Add contents wrapper
	displayHTML += `<div class="ditty-display__contents"${
		contentsStyles ? ` style="${contentsStyles}"` : ''
	}>`;

	if (displayType === 'ticker') {
		// Ticker: items container
		displayHTML += `<div class="ditty-display__items">${itemsHTML}</div>`;
	} else {
		// Slider/List: Splide structure
		displayHTML += `<div class="splide__track"><ul class="splide__list">${itemsHTML}</ul></div>`;
	}

	displayHTML += `</div>`; // .ditty-display__contents
	displayHTML += `</div>`; // .ditty-display

	// Generate complete HTML document
	return `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Ditty Preview: ${escapeHtml(dittyId)}</title>
  ${v4CssUrl ? `<link rel="stylesheet" href="${escapeHtml(v4CssUrl)}">` : ''}
  <style>
    html {
      margin: 0 !important;
    }
    body {
      margin: 0;
      padding: 0;
      background: transparent;
      overflow: auto;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }
    .ditty-preview-body {
      padding: 0;
    }
    ${customStylesCSS}
    .ditty-preview-empty {
      padding: 40px 20px;
      text-align: center;
      color: #666;
      font-size: 14px;
    }
  </style>
</head>
<body class="ditty-preview-body">
  ${displayHTML}
  ${v4JsUrl ? `<script src="${escapeHtml(v4JsUrl)}"></script>` : ''}
  <script>
    // Notify parent frame that preview has loaded
    if (window.parent !== window) {
      window.parent.postMessage({ type: 'ditty_preview_loaded', timestamp: Date.now() }, '*');
    }
  </script>
</body>
</html>`;
}

/**
 * Build container inline styles
 *
 * @param {Object} args - Display arguments
 * @returns {string} Inline style string
 */
function buildContainerStyles(args) {
	const styles = [];

	if (args.maxWidth) styles.push(`max-width:${args.maxWidth}`);
	if (args.bgColor) styles.push(`background-color:${args.bgColor}`);
	if (args.padding) styles.push(`padding:${args.padding}`);
	if (args.margin) styles.push(`margin:${args.margin}`);
	if (args.borderColor) styles.push(`border-color:${args.borderColor}`);
	if (args.borderStyle) styles.push(`border-style:${args.borderStyle}`);
	if (args.borderWidth) styles.push(`border-width:${args.borderWidth}`);
	if (args.borderRadius) styles.push(`border-radius:${args.borderRadius}`);

	return styles.length > 0 ? styles.join(';') : '';
}

/**
 * Build contents wrapper inline styles
 *
 * @param {Object} args - Display arguments
 * @returns {string} Inline style string
 */
function buildContentsStyles(args) {
	const styles = [];

	if (args.contentsBgColor)
		styles.push(`background-color:${args.contentsBgColor}`);
	if (args.contentsPadding) styles.push(`padding:${args.contentsPadding}`);
	if (args.contentsBorderColor)
		styles.push(`border-color:${args.contentsBorderColor}`);
	if (args.contentsBorderStyle)
		styles.push(`border-style:${args.contentsBorderStyle}`);
	if (args.contentsBorderWidth)
		styles.push(`border-width:${args.contentsBorderWidth}`);
	if (args.contentsBorderRadius)
		styles.push(`border-radius:${args.contentsBorderRadius}`);

	return styles.length > 0 ? styles.join(';') : '';
}

/**
 * Build item inline styles
 *
 * @param {Object} args - Display arguments
 * @returns {string} Inline style string
 */
function buildItemStyles(args) {
	const styles = [];

	if (args.itemBgColor) styles.push(`background-color:${args.itemBgColor}`);
	if (args.itemPadding) styles.push(`padding:${args.itemPadding}`);
	if (args.itemBorderColor) styles.push(`border-color:${args.itemBorderColor}`);
	if (args.itemBorderStyle) styles.push(`border-style:${args.itemBorderStyle}`);
	if (args.itemBorderWidth) styles.push(`border-width:${args.itemBorderWidth}`);
	if (args.itemBorderRadius)
		styles.push(`border-radius:${args.itemBorderRadius}`);
	if (args.itemMaxWidth) styles.push(`max-width:${args.itemMaxWidth}`);
	if (args.itemElementsWrap === 'nowrap') styles.push(`white-space:nowrap`);

	return styles.length > 0 ? styles.join(';') : '';
}

/**
 * Build title HTML
 *
 * @param {string} title - Title text
 * @param {Object} args - Display arguments
 * @returns {string} Title HTML
 */
function buildTitleHTML(title, args) {
	if (!title || args.titleDisplay === 'none') {
		return '';
	}

	const titleElement = args.titleElement || 'h3';
	const titleWrapperStyles = [];
	const titleStyles = [];

	// Title wrapper styles
	if (args.titleMargin) titleWrapperStyles.push(`margin:${args.titleMargin}`);

	// Title content styles
	if (args.titleColor) titleStyles.push(`color:${args.titleColor}`);
	if (args.titleBgColor)
		titleStyles.push(`background-color:${args.titleBgColor}`);
	if (args.titlePadding) titleStyles.push(`padding:${args.titlePadding}`);
	if (args.titleMinWidth) titleStyles.push(`min-width:${args.titleMinWidth}`);
	if (args.titleMaxWidth) titleStyles.push(`max-width:${args.titleMaxWidth}`);
	if (args.titleMinHeight)
		titleStyles.push(`min-height:${args.titleMinHeight}`);
	if (args.titleMaxHeight)
		titleStyles.push(`max-height:${args.titleMaxHeight}`);
	if (args.titleBorderColor)
		titleStyles.push(`border-color:${args.titleBorderColor}`);
	if (args.titleBorderStyle)
		titleStyles.push(`border-style:${args.titleBorderStyle}`);
	if (args.titleBorderWidth)
		titleStyles.push(`border-width:${args.titleBorderWidth}`);
	if (args.titleBorderRadius)
		titleStyles.push(`border-radius:${args.titleBorderRadius}`);

	const wrapperStyleAttr =
		titleWrapperStyles.length > 0
			? ` style="${titleWrapperStyles.join(';')}"`
			: '';
	const contentStyleAttr =
		titleStyles.length > 0 ? ` style="${titleStyles.join(';')}` : '';

	return `<div class="ditty-display__title"${wrapperStyleAttr}>
    <div class="ditty-display__title__contents"${contentStyleAttr}>
      <${titleElement} class="ditty-display__title__element">${escapeHtml(
		title
	)}</${titleElement}>
    </div>
  </div>`;
}

/**
 * Escape HTML to prevent XSS
 *
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
function escapeHtml(text) {
	const div = document.createElement('div');
	div.textContent = text;
	return div.innerHTML;
}

/**
 * Generate preview data object for storing in transient
 *
 * @param {Object} options - Preview options
 * @returns {Object} Preview data object
 */
export function generatePreviewData({
	dittyId,
	title,
	display,
	items,
	styles,
}) {
	return {
		ditty_id: dittyId,
		title: title,
		display: display,
		items: items,
		styles: styles,
		timestamp: Date.now(),
	};
}

export default generatePreviewHTML;
