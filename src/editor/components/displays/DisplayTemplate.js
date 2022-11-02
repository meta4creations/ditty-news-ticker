import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import Panel from "../Panel";
import { getDisplayObject } from "../../utils/helpers";

const DisplayTemplate = ({ viewTemplates, editor }) => {
  const { currentDisplay, displays, helpers } = useContext(editor);
  const displayObject = getDisplayObject(currentDisplay, displays);
  console.log("displayObject", displayObject);

  const panelHeader = () => {
    return (
      <div className="ditty-editor__panel__header__buttons">
        <button className="ditty-button" onClick={viewTemplates}>
          {__("Change Template", "ditty-news-ticker")}
        </button>
        <button className="ditty-button">
          {__("Edit Template", "ditty-news-ticker")}
        </button>
      </div>
    );
  };

  const panelContent = () => {
    return (
      <div className="ditty-display-template">
        {helpers.displayTypeIcon(displayObject)}
        <h3>{displayObject.label}</h3>
        <p>{`Display id: ${displayObject.id}`}</p>
        <p>{displayObject.description}</p>
      </div>
    );
  };

  return (
    <Panel
      id="displayTemplate"
      header={panelHeader()}
      content={panelContent()}
    />
  );
};
export default DisplayTemplate;
