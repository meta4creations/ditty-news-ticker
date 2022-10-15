import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";

import { EditorContext } from "../context";
//import Panel from "./Panel";
import PanelItems from "./PanelItems";
import PanelDisplays from "./PanelDisplays";

const Panels = () => {
  const { currentPanel } = useContext(EditorContext);

  const panels = [
    {
      id: "items",
      // header: (
      //   <button className="ditty-button">
      //     {__("Add Item", "ditty-news-ticker")}
      //   </button>
      // ),
      content: <PanelItems />,
    },
    {
      id: "display",
      // header: (
      //   <button className="ditty-button">
      //     {__("Add Display", "ditty-news-ticker")}
      //   </button>
      // ),
      content: <PanelDisplays />,
    },
    {
      id: "settings",
      content: <h2>Settings</h2>,
    },
  ];

  const renderCurrentPanel = () => {
    const selectedPanels = panels.filter((panel) => panel.id === currentPanel);
    const selectedPanel = selectedPanels.length ? selectedPanels[0] : panels[0];
    //return selectedPanel.content;

    return window.dittyHooks.applyFilters(
      "dittyEditorPanel",
      "",
      currentPanel,
      EditorContext
    );

    // return (
    //   <Panel
    //     id={selectedPanel.id}
    //     header={selectedPanel.header ? selectedPanel.header : null}
    //     content={selectedPanel.content}
    //   />
    // );
  };

  return <div className="ditty-editor__panels">{renderCurrentPanel()}</div>;
};
export default Panels;
