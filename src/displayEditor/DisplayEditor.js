import classnames from "classnames";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faBarsStaggered,
  faTabletScreen,
  faGear,
} from "@fortawesome/pro-regular-svg-icons";
import { Tabs } from "../components";
import PanelDisplays from "./PanelDisplays";
import PanelSettings from "./PanelSettings";

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
  const [currentTabId, setCurrentTabId] = useState("display");

  let editorWidth = editorSettings.editorWidth
    ? Number(editorSettings.editorWidth)
    : 350;
  let editorHeight = editorSettings.editorHeight
    ? Number(editorSettings.editorHeight)
    : 350;
  if (editorWidth < 300) {
    editorWidth = 300;
  }

  const handler = (mouseDownEvent) => {
    const isVertical = window.innerWidth < 782;

    const startSize = isVertical ? editorHeight : editorWidth;
    const startPosition = isVertical
      ? mouseDownEvent.pageY
      : mouseDownEvent.pageX;

    function onMouseMove(mouseMoveEvent) {
      let newSize = isVertical
        ? startSize + startPosition - mouseMoveEvent.pageY
        : startSize + startPosition - mouseMoveEvent.pageX;
      if (newSize < 300) {
        newSize = 300;
      }

      if (isVertical) {
        editorSettings.editorHeight = newSize;
      } else {
        editorSettings.editorWidth = newSize;
      }
      onUpdateDisplaySettings(editorSettings);
    }
    function onMouseUp() {
      document.body.removeEventListener("mousemove", onMouseMove);
      // uncomment the following line if not using `{ once: true }`
      // document.body.removeEventListener("mouseup", onMouseUp);
    }

    document.body.addEventListener("mousemove", onMouseMove);
    document.body.addEventListener("mouseup", onMouseUp, { once: true });
  };

  const tabs = dittyEditor.applyFilters("dittyDisplayEditorTabs", [
    {
      id: "display",
      label: __("Display", "ditty-news-ticker"),
      icon: <FontAwesomeIcon icon={faTabletScreen} />,
      content: (
        <PanelDisplays
          display={display}
          title={title}
          description={description}
          onUpdateDisplaySettings={onUpdateDisplaySettings}
          onUpdateDisplayType={onUpdateDisplayType}
        />
      ),
    },
    {
      id: "settings",
      label: __("Settings", "ditty-news-ticker"),
      icon: <FontAwesomeIcon icon={faGear} />,
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

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  const renderCurrentPanel = () => {
    const index = tabs.findIndex((object) => {
      return object.id === currentTabId;
    });
    return -1 === index ? "" : tabs[index].content ? tabs[index].content : "";
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
