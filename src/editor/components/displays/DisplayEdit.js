import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import Tabs from "../Tabs";
import Panel from "../Panel";

const DisplayEdit = ({ display, goBack }) => {
  const [currentTab, setCurrentTab] = useState("edit");

  const tabs = window.dittyHooks.applyFilters(
    "dittyDisplaysTabs",
    [
      {
        id: "back",
        icon: "",
        label: __("Go Back", "ditty-news-ticker"),
        content: <h2>Edit panel</h2>,
      },
    ],
    currentTab
  );

  const panelHeader = () => {
    return <Tabs tabs={tabs} />;
  };

  const panelContent = () => {
    return <h1>Display #{display.id}</h1>;
  };

  return (
    <Panel id="displayEdit" header={panelHeader()} content={panelContent()} />
  );
};
export default DisplayEdit;
