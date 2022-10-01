import { __ } from "@wordpress/i18n";
import Items from "./items";

const Panels = ({ items, currentPanel }) => {
  const panels = [
    {
      id: "items",
      content: <Items items={items} />,
    },
    {
      id: "display",
      content: <h2>Display</h2>,
    },
    {
      id: "settings",
      content: <h2>Settings</h2>,
    },
  ];

  function renderCurrentPanel() {
    const selectedPanels = panels.filter((panel) => panel === currentPanel);
    const selectedPanel = selectedPanels.length ? selectedPanels[0] : panels[0];
    return (
      <div
        className={`ditty-editor__panel ditty-editor__panel--${selectedPanel.id}`}
        key={selectedPanel.id}
      >
        {selectedPanel.content}
      </div>
    );
  }

  return <div className="ditty-editor__panels">{renderCurrentPanel()}</div>;
};
export default Panels;
