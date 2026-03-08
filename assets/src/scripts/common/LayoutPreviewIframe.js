import classnames from 'classnames';
const { useState, useEffect, useRef } = wp.element;
const { __ } = wp.i18n;
import { useDebounce } from '../hooks/useDebounce';
import axios from 'axios';

/**
 * LayoutPreviewIframe Component
 *
 * Renders a layout preview in an iframe using the endpoint approach
 * so theme styles from the frontend are reflected in the editor.
 *
 * Supports fast CSS-only updates via postMessage to avoid full
 * iframe reloads when only CSS changes.
 */
const LayoutPreviewIframe = ({
	displayItem = { html: '', css: '' },
	cssOverride,
	previewStyles = {},
	debounceDelay = 500,
	className,
}) => {
	const iframeRef = useRef(null);
	const [isLoading, setIsLoading] = useState(true);
	const [error, setError] = useState(null);
	const [previewUrl, setPreviewUrl] = useState('');

	const debouncedDisplayItem = useDebounce(displayItem, debounceDelay);
	const debouncedStyles = useDebounce(previewStyles, debounceDelay);

	useEffect(() => {
		const handleMessage = event => {
			if (event.data && event.data.type === 'ditty_preview_loaded') {
				setIsLoading(false);
				setError(null);
			}
		};
		window.addEventListener('message', handleMessage);
		return () => window.removeEventListener('message', handleMessage);
	}, []);

	useEffect(() => {
		if (!debouncedDisplayItem.html) return;

		const storeAndLoad = async () => {
			setIsLoading(true);
			setError(null);

			try {
				const key = `layout_${Date.now()}_${Math.random()
					.toString(36)
					.substr(2, 9)}`;

				const previewData = {
					type: 'layout',
					html: debouncedDisplayItem.html,
					css: debouncedDisplayItem.css || '',
					styles: debouncedStyles,
					timestamp: Date.now(),
				};

				const formData = new FormData();
				formData.append('action', 'ditty_store_preview_data');
				formData.append(
					'preview_nonce',
					dittyEditorVars.previewNonce || dittyEditorVars.nonce
				);
				formData.append('preview_key', key);
				formData.append('preview_data', JSON.stringify(previewData));

				const response = await axios.post(dittyEditorVars.ajaxUrl, formData, {
					withCredentials: true,
				});

				if (response.data && response.data.success) {
					const url = `${dittyEditorVars.adminUrl}admin.php?action=ditty_layout_preview&preview_key=${key}`;
					setPreviewUrl(url);
				} else {
					throw new Error(
						response.data?.data?.message || 'Failed to store preview data'
					);
				}
			} catch (err) {
				console.error('[Ditty Layout Preview] Error:', err);
				setError(
					err.message || __('Failed to load preview', 'ditty-news-ticker')
				);
				setIsLoading(false);
			}
		};

		storeAndLoad();
	}, [debouncedDisplayItem, debouncedStyles]);

	useEffect(() => {
		if (
			cssOverride !== undefined &&
			iframeRef.current &&
			iframeRef.current.contentWindow
		) {
			iframeRef.current.contentWindow.postMessage(
				{ type: 'ditty_update_css', css: cssOverride },
				'*'
			);
		}
	}, [cssOverride]);

	const iframeClasses = classnames('ditty-preview-iframe', className, {
		'ditty-preview-iframe--loading': isLoading,
		'ditty-preview-iframe--error': error,
	});

	return (
		<div className="ditty-preview-iframe-wrapper">
			{isLoading && (
				<div className="ditty-preview-loading">
					<div className="ditty-spinner"></div>
					<p>{__('Loading preview...', 'ditty-news-ticker')}</p>
				</div>
			)}
			{error && (
				<div className="ditty-preview-error">
					<p className="ditty-preview-error__message">{error}</p>
					<button
						className="ditty-button"
						onClick={() => {
							setError(null);
							setIsLoading(true);
							if (iframeRef.current) {
								iframeRef.current.src = iframeRef.current.src;
							}
						}}
					>
						{__('Retry', 'ditty-news-ticker')}
					</button>
				</div>
			)}
			{previewUrl && (
				<iframe
					ref={iframeRef}
					className={iframeClasses}
					src={previewUrl}
					title={__('Layout Preview', 'ditty-news-ticker')}
					onLoad={() => {
						setIsLoading(false);
						setError(null);
					}}
					onError={() => {
						setIsLoading(false);
						setError(__('Failed to load preview', 'ditty-news-ticker'));
					}}
					style={{ display: isLoading || error ? 'none' : 'block' }}
				/>
			)}
		</div>
	);
};

export default LayoutPreviewIframe;
