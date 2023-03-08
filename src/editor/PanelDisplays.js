import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTabletScreen } from "@fortawesome/pro-light-svg-icons";
import {
  updateDisplayOptions,
  updateDittyDisplayTemplate,
  updateDittyDisplayType,
} from "../services/dittyService";
import { PopupTypeSelector } from "../common";
import { Button, ButtonGroup, IconBlock, Link, Panel } from "../components";
import { FieldList } from "../fields";
import {
  getDisplayTypes,
  getDisplayTypeIcon,
  getDisplayTypeObject,
  getDisplayTypeSettings,
} from "../utils/displayTypes";
import { EditorContext } from "./context";
import PopupTemplateSave from "./PopupTemplateSave";
import PopupTemplateSelector from "./PopupTemplateSelector";

const PanelDisplays = () => {
  const { actions, currentDisplay, displays } = useContext(EditorContext);
  const displayTypeObject = getDisplayTypeObject(currentDisplay);
  const fieldGroups = getDisplayTypeSettings(currentDisplay);
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";

  const [currentTabId, setCurrentTabId] = useState(initialTab);
  const [status, setStatus] = useState(!currentDisplay.id && "editDisplay");
  const [popupStatus, setPopupStatus] = useState(false);
  const [currentTemplate, setCurrentTemplate] = useState(
    currentDisplay.id ? currentDisplay : false
  );

  const displayTypes = getDisplayTypes();

  /**
   * Update the Display on field update
   * @param {object} field
   * @param {string} value
   */
  const handleOnUpdate = (id, value) => {
    // Update the editor display
    console.log(id, value);

    const updatedDisplay = { ...currentDisplay };
    updatedDisplay.settings[id] = value;
    updatedDisplay.updated = Date.now();
    actions.setCurrentDisplay(updatedDisplay);

    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, id, value);
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    switch (popupStatus) {
      case "displayTemplateSave":
        const templateToSave = currentTemplate
          ? { ...currentTemplate, ...currentDisplay }
          : { ...currentDisplay };
        delete templateToSave.version;
        return (
          <PopupTemplateSave
            templateType="display"
            currentTemplate={templateToSave}
            templates={displays}
            filterKey="type"
            filters={getDisplayTypes()}
            headerIcon={<FontAwesomeIcon icon={faTabletScreen} />}
            templateIcon={(template) => {
              return getDisplayTypeIcon(template);
            }}
            saveData={(type, selectedTemplate, name, description) => {
              return "existing" === type
                ? {
                    display: {
                      ...selectedTemplate,
                      type: currentDisplay.type,
                      settings: currentDisplay.settings,
                      updated: Date.now(),
                    },
                  }
                : {
                    title: name,
                    description: description,
                    display: selectedTemplate,
                  };
            }}
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
          <PopupTemplateSelector
            currentTemplate={currentDisplay}
            templates={displays}
            filterKey="type"
            filters={getDisplayTypes()}
            headerIcon={<FontAwesomeIcon icon={faTabletScreen} />}
            templateIcon={(template) => {
              return getDisplayTypeIcon(template);
            }}
            onChange={(selectedTemplate) => {
              updateDittyDisplayTemplate(dittyEl, selectedTemplate);
            }}
            onClose={(selectedTemplate) => {
              setPopupStatus(false);
              if (currentDisplay.id !== selectedTemplate.id) {
                updateDittyDisplayTemplate(dittyEl, currentDisplay);
              }
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
          <PopupTypeSelector
            currentType={currentDisplay.type}
            types={displayTypes}
            getTypeObject={getDisplayTypeObject}
            onChange={(selectedType) => {
              updateDittyDisplayType(dittyEl, selectedType);
            }}
            onClose={(selectedType) => {
              setPopupStatus(false);
              if (currentDisplay.type !== selectedType) {
                updateDittyDisplayType(dittyEl, currentDisplay.type);
              }
            }}
            onUpdate={(updatedType) => {
              setPopupStatus(false);
              if (currentDisplay.type === updatedType) {
                return false;
              }
              const updatedDisplay = _.cloneDeep(currentDisplay);
              const updatedDisplayTypeObject =
                getDisplayTypeObject(updatedType);

              updatedDisplay.type = updatedType;
              updatedDisplay.settings = {
                ...updatedDisplayTypeObject.defaultValues,
                ...updatedDisplay.settings,
              };
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
              settings: _.cloneDeep(currentDisplay.settings),
            };
            actions.setCurrentDisplay(customDisplay);
            setCurrentTemplate(currentDisplay);
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
    const title = currentDisplay.id
      ? `${currentDisplay.title} (${displayTypeObject.label})`
      : displayTypeObject.label;
    const description = currentDisplay.id
      ? currentDisplay.description
      : displayTypeObject.description;

    return (
      <>
        <IconBlock
          icon={displayTypeObject.icon}
          className="ditty-icon-block--heading"
          style={{ marginBottom: "10px" }}
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{title}</h2>
            {"editDisplay" === status && (
              <Link onClick={() => setPopupStatus("displayTypeSelect")}>
                {__("Change Type", "ditty-news-ticker")}
              </Link>
            )}
            {currentDisplay.id && (
              <Link onClick={() => setPopupStatus("displayTemplateSelect")}>
                {__("Change Template", "ditty-news-ticker")}
              </Link>
            )}
          </div>
          <p>{description}</p>
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
          name={fieldGroup.name}
          description={fieldGroup.desc}
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
