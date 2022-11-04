import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import DisplayList from "./displays/DisplayList";
import DisplayEdit from "./displays/DisplayEdit";
import DisplayTemplate from "./displays/DisplayTemplate";

const PanelDisplays = ({ editor }) => {
  const { id, currentDisplay, displays, actions } = useContext(editor);
  const [currentPanel, setCurrentPanel] = useState("edit");

  const handleViewTemplates = () => {
    setCurrentPanel("list");
  };

  const handleEditDisplay = () => {
    setCurrentPanel("edit");
  };

  const handleGoBack = () => {
    setCurrentPanel("edit");
  };

  return "edit" === currentPanel ? (
    typeof currentDisplay === "object" ? (
      <DisplayEdit
        currentDisplay={currentDisplay}
        goBack={handleGoBack}
        editor={editor}
      />
    ) : (
      <DisplayTemplate editor={editor} viewTemplates={handleViewTemplates} />
    )
  ) : (
    <DisplayList
      id={id}
      displays={displays}
      actions={actions}
      editDisplay={handleEditDisplay}
      goBack={handleGoBack}
      editor={editor}
    />
  );
};
export default PanelDisplays;
