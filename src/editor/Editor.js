import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faBarsStaggered,
  faTabletScreen,
  faGear,
} from "@fortawesome/pro-regular-svg-icons";
import { Tabs } from "../components";
import PanelDisplays from "./PanelDisplays";
import { EditorContext } from "./context";

const Editor = () => {
  const { currentDisplay } = useContext(EditorContext);
  const [currentTabId, setCurrentTabId] = useState("display");

  const tabs = window.dittyHooks.applyFilters(
    "dittyEditorTabs",
    [
      {
        id: "items",
        label: __("Items", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faBarsStaggered} />,
      },
      {
        id: "display",
        label: __("Display", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faTabletScreen} />,
        content: <PanelDisplays key={currentDisplay.type} />,
      },
      {
        id: "settings",
        label: __("Settings", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faGear} />,
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

    return window.dittyHooks.applyFilters(
      "dittyEditorPanel",
      content,
      currentTabId,
      EditorContext
    );
  };

  return (
    <div id="ditty-editor__editor">
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
