import classnames from 'classnames';
const { __ } = wp.i18n;
const { applyFilters } = wp.hooks;
const { useState } = wp.element;
import { Icon, Tabs } from '../components';
import { useEditorResize } from '../hooks/useEditorResize';
import PanelDisplay from './PanelDisplay';
import PanelSettings from './PanelSettings';

const DisplayEditor = ({
	display,
	title,
	description,
	status,
	editorSettings,
	onUpdateDisplaySettings,
	onUpdateDisplayType,
	onUpdateTitle,
	onUpdateDescription,
	onUpdateStatus,
	onUpdateEditorSettings,
	className,
}) => {
	const [currentTabId, setCurrentTabId] = useState('display');

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

	const tabs = applyFilters('dittyDisplayEditor.tabs', [
		{
			id: 'display',
			label: __('Display', 'ditty-news-ticker'),
			icon: <Icon id="faTabletScreen" />,
			content: (
				<PanelDisplay
					display={display}
					title={title}
					description={description}
					onUpdateDisplaySettings={onUpdateDisplaySettings}
					onUpdateDisplayType={onUpdateDisplayType}
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
export default DisplayEditor;
