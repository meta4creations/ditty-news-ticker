import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import Panel from "../Panel";

const ItemEdit = ({ item, goBack }) => {
  const [currentTab, setCurrentTab] = useState("edit");

  const tabs = window.dittyHooks.applyFilters(
    "dittyItemsTabs",
    [
      {
        id: "edit",
        icon: "",
        label: __("Edit", "ditty-news-ticker"),
        content: <h2>Edit panel</h2>,
      },
    ],
    currentPanel,
    editor
  );

  console.log(tabs);

  const panelHeader = () => {
    return (
      <button className="ditty-button" onClick={goBack}>
        {__("Go Back", "ditty-news-ticker")}
      </button>
    );
  };

  const panelContent = () => {
    return <h1>Item #{item.item_id}</h1>;
  };

  return (
    <Panel id="itemEdit" header={panelHeader()} content={panelContent()} />
  );
};
export default ItemEdit;
