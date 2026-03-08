import classnames from 'classnames';
const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;
const { useState } = wp.element;
import { Icon, Tabs } from '../components';
import { useEditorResize } from '../hooks/useEditorResize';
import PanelLayout from './PanelLayout';
import PanelItem from './PanelItem';
import PanelSettings from './PanelSettings';

const LayoutEditor = ({
	title,
	description,
	status,
	layout,
	editorItem,
	editorSettings,
	onUpdateLayout,
	onUpdateTitle,
	onUpdateDescription,
	onUpdateStatus,
	onUpdateEditorItem,
	onUpdateEditorSettings,
	className,
}) => {
	const [currentTabId, setCurrentTabId] = useState('layout');

	let editorWidth = editorSettings.editorWidth
		? Number(editorSettings.editorWidth)
		: 350;
	let editorHeight = editorSettings.editorHeight
		? Number(editorSettings.editorHeight)
		: 350;
	if (editorWidth < 300) {
		editorWidth = 300;
	}

	const handler = useEditorResize({
		width: editorWidth,
		height: editorHeight,
		onResize: (key, value) => {
			editorSettings[key] = value;
			onUpdateEditorSettings(editorSettings);
		},
	});

	const tabs = applyFilters('dittyLayoutEditor.tabs', [
		{
			id: 'layout',
			label: __('Layout', 'ditty-news-ticker'),
			icon: <Icon id="faPaintbrushPencil" />,
			content: (
				<PanelLayout
					editorItem={editorItem}
					layout={layout}
					onUpdateLayout={onUpdateLayout}
				/>
			),
		},
		{
			id: 'item',
			label: __('Item', 'ditty-news-ticker'),
			icon: <Icon id="faBarsStaggered" />,
			content: (
				<PanelItem
					editorItem={editorItem}
					onUpdateEditorItem={onUpdateEditorItem}
				/>
			),
		},
		{
			id: 'settings',
			label: __('Settings', 'ditty-news-ticker'),
			icon: <Icon id="faGear" />,
			content: (
				<PanelSettings
					title={title}
					description={description}
					status={status}
					settings={editorSettings}
					onUpdateTitle={onUpdateTitle}
					onUpdateDescription={onUpdateDescription}
					onUpdateStatus={onUpdateStatus}
					onUpdateSettings={onUpdateEditorSettings}
				/>
			),
		},
	]);

	const handleTabClick = tab => {
		setCurrentTabId(tab.id);
	};

	const renderCurrentPanel = () => {
		const index = tabs.findIndex(object => {
			return object.id === currentTabId;
		});
		return -1 === index ? '' : tabs[index].content ? tabs[index].content : '';
	};

	const classes = classnames(className);

	return (
		<div
			id="ditty-editor__editor"
			className={classes}
			style={{ width: `${editorWidth}px`, height: `${editorHeight}px` }}
		>
			<div
				id="ditty-editor__sizer"
				className="ditty-adminPage__app__sizer"
				onMouseDown={handler}
			></div>
			<Tabs
				tabs={tabs}
				currentTabId={currentTabId}
				tabClick={handleTabClick}
				type="primary"
			/>
			<div className="ditty-editor__panels">{renderCurrentPanel()}</div>
		</div>
	);
};
export default LayoutEditor;
