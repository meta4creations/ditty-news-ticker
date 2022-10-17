import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faBarsStaggered,
  faTabletScreen,
  faGear,
} from "@fortawesome/pro-regular-svg-icons";
import Tabs from "./Tabs";
import { EditorContext } from "../context";

export default () => {
  const [currentTabId, setCurrentTabId] = useState("items");

  const tabs = window.dittyHooks.applyFilters("dittyEditorTabs", [
    {
      id: "items",
      label: __("Items", "ditty-news-ticker"),
      icon: <FontAwesomeIcon icon={faBarsStaggered} />,
    },
    {
      id: "display",
      label: __("Display", "ditty-news-ticker"),
      icon: <FontAwesomeIcon icon={faTabletScreen} />,
    },
    {
      id: "settings",
      label: __("Settings", "ditty-news-ticker"),
      icon: <FontAwesomeIcon icon={faGear} />,
    },
  ]);

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  const renderCurrentPanel = () => {
    return window.dittyHooks.applyFilters(
      "dittyEditorPanel",
      "",
      currentTabId,
      EditorContext
    );
  };

  return (
    <div className="ditty-editor__contents">
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
