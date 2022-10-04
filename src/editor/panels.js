import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "./context";
import PanelItems from "./panelItems";
import PanelDisplays from "./panelDisplays";

const Panels = () => {
  const { currentPanel } = useContext(EditorContext);

  const panels = [
    {
      id: "items",
      content: <PanelItems />,
    },
    {
      id: "display",
      content: <PanelDisplays />,
    },
    {
      id: "settings",
      content: <h2>Settings</h2>,
    },
  ];

  function renderCurrentPanel() {
    const selectedPanels = panels.filter((panel) => panel.id === currentPanel);
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
