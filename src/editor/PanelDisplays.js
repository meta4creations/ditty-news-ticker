import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { updateDisplayOptions } from "../services/dittyService";
import { Button, ButtonGroup, IconBlock, Link, Panel } from "../components";
import { Field, FieldList } from "../fields";
import {
  getDisplayTypeObject,
  getDisplayTypeSettings,
} from "./utils/displayTypes";
import { EditorContext } from "./context";
import {
  DisplayTemplateSavePopup,
  DisplayTemplateSelectorPopup,
  DisplayTypeSelectorPopup,
} from "./displays";

const PanelDisplays = () => {
  const { actions, currentDisplay, displays } = useContext(EditorContext);
  const displayTypeObject = getDisplayTypeObject(currentDisplay);
  const fieldGroups = getDisplayTypeSettings(currentDisplay);
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);
  const [status, setStatus] = useState(!currentDisplay.id && "editDisplay");
  const [popupStatus, setPopupStatus] = useState(false);

  /**
   * Update the Display on field update
   * @param {object} field
   * @param {string} value
   */
  const handleOnUpdate = (id, value) => {
    console.log("id", id);
    console.log("value", value);

    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, id, value);

    // Update the editor display
    const updatedDisplay = { ...currentDisplay };
    updatedDisplay.settings[id] = value;
    updatedDisplay.updated = Date.now();
    actions.setCurrentDisplay(updatedDisplay);
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    switch (popupStatus) {
      case "displayTemplateSave":
        return (
          <DisplayTemplateSavePopup
            activeTemplate={currentDisplay}
            templates={displays}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedTemplate) => {
              setStatus(false);
              setPopupStatus(false);
              actions.updateDisplay(updatedTemplate);
              actions.setCurrentDisplay(updatedTemplate);
            }}
          />
        );
      case "displayTemplateSelect":
        return (
          <DisplayTemplateSelectorPopup
            activeTemplate={currentDisplay}
            templates={displays}
            dittyEl={dittyEl}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedTemplate) => {
              setStatus(false);
              setPopupStatus(false);
              if (currentDisplay.id === updatedTemplate.id) {
                return false;
              }
              actions.setCurrentDisplay(updatedTemplate);
            }}
          />
        );
      case "displayTypeSelect":
        return (
          <DisplayTypeSelectorPopup
            activeType={currentDisplay.type}
            dittyEl={dittyEl}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedType) => {
              setPopupStatus(false);
              if (currentDisplay.type === updatedType) {
                return false;
              }
              const updatedDisplay = { ...currentDisplay };
              updatedDisplay.type = updatedType;
              actions.setCurrentDisplay(updatedDisplay);
            }}
          />
        );
      default:
        return;
    }
  };

  const templateButtons = () => {
    return (
      <>
        <Button onClick={() => setPopupStatus("displayTemplateSelect")}>
          {__("Change Template", "ditty-news-ticker")}
        </Button>
        <Button
          onClick={() => {
            const customDisplay = {
              type: currentDisplay.type,
              settings: currentDisplay.settings,
            };
            actions.setCurrentDisplay(customDisplay);
            setStatus("editDisplay");
          }}
        >
          {__("Customize", "ditty-news-ticker")}
        </Button>
      </>
    );
  };

  const customButtons = () => {
    return (
      <>
        <Button onClick={() => setPopupStatus("displayTemplateSelect")}>
          {__("Use Template", "ditty-news-ticker")}
        </Button>
        <Button
          onClick={() => {
            setPopupStatus("displayTemplateSave");
          }}
        >
          {__("Save as Template", "ditty-news-ticker")}
        </Button>
      </>
    );
  };

  const panelHeader = () => {
    return (
      <>
        <IconBlock icon={displayTypeObject.icon} className="displayEditType">
          <h3>{displayTypeObject.label}</h3>
          {"editDisplay" === status && (
            <Link onClick={() => setPopupStatus("displayTypeSelect")}>
              {__("Change Type", "ditty-news-ticker")}
            </Link>
          )}
        </IconBlock>
        <IconBlock style={{ marginBottom: "10px" }}>
          {currentDisplay.id ? (
            <>
              <h2>{currentDisplay.title} </h2>
              <p>
                {__("Post ID", "ditty-news-ticker")} :{" "}
                <a href={currentDisplay.edit_url}>{currentDisplay.id}</a>
              </p>
              <p>{currentDisplay.description}</p>
            </>
          ) : (
            <>
              <p>{displayTypeObject.description}</p>
            </>
          )}
        </IconBlock>
        <ButtonGroup className="ditty-displayEdit__links">
          {currentDisplay.id ? templateButtons() : customButtons()}
        </ButtonGroup>
      </>
    );
  };

  const panelContent = () => {
    if ("editDisplay" === status) {
      const index = fieldGroups.findIndex((fieldGroup) => {
        return fieldGroup.id === currentTabId;
      });
      if (-1 === index) {
        return false;
      }

      const fieldGroup = fieldGroups[index];
      return (
        <FieldList
          fields={fieldGroup.fields}
          values={currentDisplay.settings}
          onUpdate={handleOnUpdate}
        />
      );
    }
  };

  return (
    <>
      <Panel
        id="displays"
        header={panelHeader()}
        tabs={"editDisplay" == status && fieldGroups}
        tabClick={(tab) => setCurrentTabId(tab.id)}
        currentTabId={currentTabId}
        tabsType="cloud"
      >
        {panelContent()}
      </Panel>
      {renderPopup()}
    </>
  );
};
export default PanelDisplays;
