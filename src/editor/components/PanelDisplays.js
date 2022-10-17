import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import DisplayList from "./displays/DisplayList";
import DisplayEdit from "./displays/DisplayEdit";

const PanelDisplays = ({ editor }) => {
  const { id, displays, actions } = useContext(editor);
  const [currentDisplay, setCurrentDisplay] = useState(null);

  const handleEditDisplay = (display) => {
    setCurrentDisplay(display);
  };

  const handleGoBack = () => {
    currentDisplay(null);
  };

  return currentDisplay ? (
    <DisplayEdit display={currentDisplay} goBack={handleGoBack} />
  ) : (
    <DisplayList
      id={id}
      displays={displays}
      actions={actions}
      editDisplay={handleEditDisplay}
    />
  );
};
export default PanelDisplays;
