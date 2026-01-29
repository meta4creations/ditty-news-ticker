/**
 * Posts Feed Block - Edit Component
 *
 * Provides a control to set the number of posts to display.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, PanelRow } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import PostControlDynamic from '../../blockComponents/post-control-dynamic';

export default function Edit({ attributes, setAttributes }) {
	const { limit, layout } = attributes;

	const blockProps = useBlockProps({
		className: 'ditty-posts-feed',
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Posts Feed Settings', 'ditty-news-ticker')}>
					<RangeControl
						__next40pxDefaultSize
						__nextHasNoMarginBottom
						label={__('Number of Posts', 'ditty-news-ticker')}
						help={__('Set the number of Posts to display', 'ditty-news-ticker')}
						value={limit}
						onChange={value => setAttributes({ limit: value })}
						min={1}
						max={20}
					/>
					<PanelRow>
						<PostControlDynamic
							controlType="select"
							postType="ditty_layout"
							label={__('Layout', 'ditty-news-ticker')}
							help={__(
								'Select a layout to format the posts',
								'ditty-news-ticker'
							)}
							placeholder={__('Select a Layout', 'ditty-news-ticker')}
							value={layout}
							onChange={selected => {
								setAttributes({
									layout: selected ? Number(selected[0].id) : 0,
								});
							}}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<ServerSideRender block="ditty/posts-feed" attributes={attributes} />
			</div>
		</>
	);
}
