/**
 * Default Item Block - Edit Component
 *
 * Provides controls for a default text item with optional link settings.
 */

import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	SelectControl,
	CheckboxControl,
	PanelRow,
	Dashicon,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import PostControlDynamic from '../../blockComponents/post-control-dynamic';

export default function Edit({ attributes, setAttributes, context }) {
	const {
		content,
		link_url,
		link_title,
		link_target,
		link_nofollow,
		editor_label,
		layout,
	} = attributes;
	const editMode = context['dittyDisplay/editMode'] || 'edit';

	const blockProps = useBlockProps({
		className: 'ditty-default-item',
	});

	const contentPreview = (() => {
		if (editor_label) {
			return editor_label;
		}
		const text = content ? content.replace(/<[^>]*>/g, '') : '';
		return text || __('No text set...', 'ditty-news-ticker');
	})();

	const inspectorControls = (
		<InspectorControls>
			<PanelBody title={__('Settings', 'ditty-news-ticker')}>
				<TextareaControl
					__nextHasNoMarginBottom
					label={__('Content', 'ditty-news-ticker')}
					help={__(
						'Add the content of your item. HTML and inline styles are supported.',
						'ditty-news-ticker'
					)}
					value={content}
					onChange={value => setAttributes({ content: value })}
					rows={4}
				/>
				<TextControl
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					label={__('Link', 'ditty-news-ticker')}
					help={__(
						'Add a custom link to your content. You can also add a link directly into your content.',
						'ditty-news-ticker'
					)}
					type="url"
					value={link_url}
					onChange={value => setAttributes({ link_url: value })}
				/>
				<TextControl
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					label={__('Link Title', 'ditty-news-ticker')}
					help={__(
						'Add a title to the custom link.',
						'ditty-news-ticker'
					)}
					value={link_title}
					onChange={value => setAttributes({ link_title: value })}
				/>
				<SelectControl
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					label={__('Link Target', 'ditty-news-ticker')}
					help={__('Set a target for your link.', 'ditty-news-ticker')}
					value={link_target}
					options={[
						{ label: '_self', value: '_self' },
						{ label: '_blank', value: '_blank' },
					]}
					onChange={value => setAttributes({ link_target: value })}
				/>
				<CheckboxControl
					__nextHasNoMarginBottom
					label={__('Add "nofollow" to link', 'ditty-news-ticker')}
					help={__(
						"Enabling this setting will add an attribute called 'nofollow' to your link. This tells search engines to not follow this link.",
						'ditty-news-ticker'
					)}
					checked={link_nofollow === '1'}
					onChange={value =>
						setAttributes({ link_nofollow: value ? '1' : '' })
					}
				/>
				<TextControl
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					label={__('Label', 'ditty-news-ticker')}
					help={__(
						'Add a custom label to display in the item list.',
						'ditty-news-ticker'
					)}
					value={editor_label}
					onChange={value => setAttributes({ editor_label: value })}
				/>
			</PanelBody>
			<PanelBody
				title={__('Layout', 'ditty-news-ticker')}
				initialOpen={false}
			>
				<PanelRow>
					<PostControlDynamic
						controlType="select"
						postType="ditty_layout"
						label={__('Layout', 'ditty-news-ticker')}
						help={__(
							'Select a layout to format the item',
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
	);

	if (editMode === 'edit') {
		return (
			<>
				{inspectorControls}
				<div {...blockProps}>
					<div className="ditty-default-item__summary">
						<Dashicon icon="edit" />
						<span className="ditty-default-item__summary-label">
							{contentPreview}
						</span>
					</div>
				</div>
			</>
		);
	}

	return (
		<>
			{inspectorControls}
			<div {...blockProps}>
				<ServerSideRender
					block="ditty/default-item"
					attributes={attributes}
				/>
			</div>
		</>
	);
}
