import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { Button, ButtonGroup } from "@wordpress/components";
import Panel from "../Panel";
import {
  getDisplayObject,
  getDisplayTypeLabel,
} from "../../utils/displayTypes";

const DisplayTemplate = ({ viewTemplates, editTemplate, editor }) => {
  const { currentDisplay, displays, helpers, actions } = useContext(editor);
  const displayObject = getDisplayObject(currentDisplay, displays);

  const panelHeader = () => {
    return (
      <>
        <h3>{__("You are currently using a template", "ditty-news-ticker")}</h3>
        <ButtonGroup>
          <Button onClick={viewTemplates} variant="secondary">
            {__("Change Template", "ditty-news-ticker")}
          </Button>
          <Button
            onClick={() => editTemplate(displayObject)}
            variant="secondary"
          >
            {__(
              "Create Custom Display Based on this Template",
              "ditty-news-ticker"
            )}
          </Button>
        </ButtonGroup>
      </>
    );
  };

  const panelContent = () => {
    return (
      <>
        <div className="ditty-display-template">
          <div className="ditty-display-template__header">
            <div className="ditty-display-template__icon">
              {helpers.displayTypeIcon(displayObject)}
            </div>
            <h3 className="ditty-display-template__title">
              {displayObject.title}
            </h3>
          </div>

          {displayObject.description && (
            <p className="ditty-display-template__description">
              {displayObject.description}
            </p>
          )}
          <p className="ditty-display-template__type">
            {__("Type", "ditty-news-ticker")}:{" "}
            <strong>{getDisplayTypeLabel(displayObject)}</strong>
          </p>
          <p className="ditty-display-template__postid">
            {__("ID", "ditty-news-ticker")}:{" "}
            <Button
              href={displayObject.edit_url}
              target="_blank"
              variant="link"
            >
              <strong>{displayObject.id}</strong>
            </Button>
          </p>
        </div>
      </>
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
