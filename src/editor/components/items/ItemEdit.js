import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPenToSquare,
  faPenRuler,
  faAngleLeft,
  faPencil,
} from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";

const ItemEdit = ({ item, goBack, deleteItem, editor }) => {
  const { items } = useContext(editor);
  const [currentTabId, setCurrentTabId] = useState("settings");

  const tabs = window.dittyHooks.applyFilters("dittyItemEditTabs", [
    {
      id: "settings",
      icon: <FontAwesomeIcon icon={faPenToSquare} />,
      label: __("Settings", "ditty-news-ticker"),
    },
    {
      id: "layout",
      icon: <FontAwesomeIcon icon={faPenRuler} />,
      label: __("Layout", "ditty-news-ticker"),
    },
  ]);

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  const panelHeader = () => {
    const count = items.length;
    return (
      <>
        <button onClick={goBack}>
          <FontAwesomeIcon icon={faAngleLeft} />
          {__(`Back - ${count} items`, "ditty-news-ticker")}
        </button>
        <button
          onClick={() => {
            deleteItem(item);
          }}
        >
          {__("Delete", "ditty-news-ticker")}
        </button>
      </>
    );
  };

  const panelContent = () => {
    return (
      <>
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
    <Panel
      id="itemEdit"
      header={panelHeader()}
      tabs={tabs}
      tabClick={handleTabClick}
      currentTabId={currentTabId}
      content={panelContent()}
    />
  );
};
export default ItemEdit;
