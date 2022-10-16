import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import Tabs from "../Tabs";
import Panel from "../Panel";

const ItemEdit = ({ item, goBack, editor }) => {
  const [currentTabId, setCurrentTabId] = useState("settings");

  const tabs = window.dittyHooks.applyFilters("dittyItemEditTabs", [
    {
      id: "settings",
      icon: "fas fa-edit",
      label: __("Settings", "ditty-news-ticker"),
    },
    {
      id: "layout",
      icon: "fas fa-pencil-ruler",
      label: __("Layout", "ditty-news-ticker"),
    },
  ]);

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  const panelHeader = () => {
    return (
      <button className="ditty-button" onClick={goBack}>
        {__("Back", "ditty-news-ticker")}
      </button>
    );
  };

  const panelContent = () => {
    return (
      <>
        <Tabs
          tabs={tabs}
          currentTabId={currentTabId}
          tabClick={handleTabClick}
        />
        {window.dittyHooks.applyFilters(
          "dittyItemEditPanel",
          "",
          currentTabId,
          item,
          editor
        )}
      </>
    );
  };

  return (
    <Panel id="itemEdit" header={panelHeader()} content={panelContent()} />
  );
};
export default ItemEdit;
