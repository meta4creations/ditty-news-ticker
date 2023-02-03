import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faBarsStaggered,
  faTabletScreen,
  faGear,
} from "@fortawesome/pro-regular-svg-icons";
import { Tabs } from "../components";
import PanelItems from "./PanelItems";
import PanelDisplays from "./PanelDisplays";
import PanelSettings from "./PanelSettings";
import { EditorContext } from "./context";

const Editor = () => {
  const { settings, actions } = useContext(EditorContext);
  const [currentTabId, setCurrentTabId] = useState("items");
  let editorWidth = settings.editorWidth ? Number(settings.editorWidth) : 350;
  if (editorWidth < 300) {
    editorWidth = 300;
  }

  const handler = (mouseDownEvent) => {
    const startSize = editorWidth;
    const startPosition = mouseDownEvent.pageX;

    function onMouseMove(mouseMoveEvent) {
      let newSize = startSize + startPosition - mouseMoveEvent.pageX;
      if (newSize < 300) {
        newSize = 300;
      }

      settings.editorWidth = newSize;
      actions.updateSettings(settings);
    }
    function onMouseUp() {
      document.body.removeEventListener("mousemove", onMouseMove);
      // uncomment the following line if not using `{ once: true }`
      // document.body.removeEventListener("mouseup", onMouseUp);
    }

    document.body.addEventListener("mousemove", onMouseMove);
    document.body.addEventListener("mouseup", onMouseUp, { once: true });
  };

  const tabs = dittyEditor.applyFilters(
    "dittyEditorTabs",
    [
      {
        id: "items",
        label: __("Items", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faBarsStaggered} />,
        content: <PanelItems />,
      },
      {
        id: "display",
        label: __("Display", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faTabletScreen} />,
        content: <PanelDisplays />,
      },
      {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faGear} />,
        content: <PanelSettings editor={EditorContext} />,
      },
    ],
    EditorContext
  );

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  const renderCurrentPanel = () => {
    const index = tabs.findIndex((object) => {
      return object.id === currentTabId;
    });
    const content =
      -1 === index ? "" : tabs[index].content ? tabs[index].content : "";

    return dittyEditor.applyFilters(
      "dittyEditorPanel",
      content,
      currentTabId,
      EditorContext
    );
  };

  return (
    <div id="ditty-editor__editor" style={{ width: `${editorWidth}px` }}>
      <div id="ditty-editor__editor__sizer" onMouseDown={handler}></div>
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
export default Editor;
