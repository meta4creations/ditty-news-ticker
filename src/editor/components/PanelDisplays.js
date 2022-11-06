import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import DisplayList from "./displays/DisplayList";
import DisplayEdit from "./displays/DisplayEdit";
import DisplayTemplate from "./displays/DisplayTemplate";

const PanelDisplays = ({ editor }) => {
  const { id, currentDisplay, displays, actions } = useContext(editor);

  //const initialPanel = "Object" === typeof currentDisplay ? "edit" : "template";
  const [currentPanel, setCurrentPanel] = useState("template");
  const [customDisplay, setCustomDisplay] = useState({});

  const handleViewTemplates = () => {
    setCurrentPanel("list");
  };

  const handleEditTemplate = (customDisplay) => {
    setCustomDisplay(customDisplay);
    setCurrentPanel("edit");
  };

  const handleGoBack = (panel = "template") => {
    setCurrentPanel(panel);
  };

  const renderContent = () => {
    if ("edit" === currentPanel) {
      return (
        <DisplayEdit
          currentDisplay={currentDisplay}
          goBack={handleGoBack}
          editor={editor}
        />
      );
    } else if ("template" === currentPanel) {
      return (
        <DisplayTemplate editor={editor} viewTemplates={handleViewTemplates} />
      );
    } else {
      return (
        <DisplayList
          id={id}
          displays={displays}
          actions={actions}
          goBack={handleGoBack}
          editor={editor}
        />
      );
    }
  };

  return renderContent();
};
export default PanelDisplays;
