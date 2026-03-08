/**
 * Shared hook for the editor sidebar resize (drag) behavior.
 *
 * Handles horizontal resize on desktop (>= 782px) and vertical resize
 * on mobile (< 782px). Disables pointer events on iframes during drag
 * so the parent document continues to receive mouse events.
 *
 * @param {Object}   options
 * @param {number}   options.width    - Current editor width
 * @param {number}   options.height   - Current editor height
 * @param {number}   [options.minSize=300] - Minimum allowed size
 * @param {Function} options.onResize - Called with (key, value) where key is "editorWidth" or "editorHeight"
 * @returns {Function} mouseDown handler to attach to the sizer element
 */
export function useEditorResize({ width, height, minSize = 300, onResize }) {
	const handleMouseDown = mouseDownEvent => {
		const isVertical = window.innerWidth < 782;
		const startSize = isVertical ? height : width;
		const startPosition = isVertical
			? mouseDownEvent.pageY
			: mouseDownEvent.pageX;

		const iframes = document.querySelectorAll('iframe');
		iframes.forEach(iframe => {
			iframe.style.pointerEvents = 'none';
		});

		function onMouseMove(mouseMoveEvent) {
			let newSize = isVertical
				? startSize + startPosition - mouseMoveEvent.pageY
				: startSize + startPosition - mouseMoveEvent.pageX;
			if (newSize < minSize) {
				newSize = minSize;
			}
			onResize(isVertical ? 'editorHeight' : 'editorWidth', newSize);
		}

		function onMouseUp() {
			document.body.removeEventListener('mousemove', onMouseMove);
			iframes.forEach(iframe => {
				iframe.style.pointerEvents = '';
			});
		}

		document.body.addEventListener('mousemove', onMouseMove);
		document.body.addEventListener('mouseup', onMouseUp, { once: true });
	};

	return handleMouseDown;
}

export default useEditorResize;
