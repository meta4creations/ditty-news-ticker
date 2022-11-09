import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import DisplayList from "./displays/DisplayList";
import DisplayEdit from "./displays/DisplayEdit";
import DisplayTemplate from "./displays/DisplayTemplate";
import { getDisplayObject } from "../utils/displayTypes";

const PanelDisplays = ({ editor }) => {
  const { id, currentDisplay, displays, actions } = useContext(editor);
  const currentDisplayObject = getDisplayObject(currentDisplay, displays);
  const [currentPanel, setCurrentPanel] = useState("template");

  const handleViewTemplates = () => {
    setCurrentPanel("list");
  };

  const handleEditTemplate = (displayObject) => {
    const customDisplayObject = {
      type: displayObject.type,
      settings: displayObject.settings,
    };
    actions.setCurrentDisplay(customDisplayObject);
  };

  const handleGoBack = (panel = "template") => {
    setCurrentPanel(panel);
  };

  const renderContent = () => {
    if (!currentDisplayObject.id) {
      return (
        <DisplayEdit
          displayObject={currentDisplayObject}
          goBack={handleGoBack}
          editor={editor}
        />
      );
    } else if ("template" === currentPanel) {
      return (
        <DisplayTemplate
          editor={editor}
          viewTemplates={handleViewTemplates}
          editTemplate={handleEditTemplate}
        />
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
