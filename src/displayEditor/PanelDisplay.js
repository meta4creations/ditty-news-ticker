import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTabletScreen } from "@fortawesome/pro-regular-svg-icons";
import {
  updateDisplayOptions,
  updateDittyDisplayType,
} from "../services/dittyService";
import { IconBlock, Link, Panel } from "../components";
import { FieldList } from "../fields";
import {
  getDisplayTypes,
  getDisplayTypeObject,
  getDisplayTypeSettings,
} from "../utils/displayTypes";
import { PopupTypeSelector } from "../common";

const PanelDisplay = ({
  display,
  onUpdateDisplaySettings,
  onUpdateDisplayType,
}) => {
  const defaultDisplayType = dittyEditorVars.defaultDisplayType
    ? dittyEditorVars.defaultDisplayType
    : "list";
  const displayTypeObject = getDisplayTypeObject(
    display.type ? display : defaultDisplayType
  );
  const fieldGroups = getDisplayTypeSettings(
    display.type ? display : defaultDisplayType
  );
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);
  const [popupStatus, setPopupStatus] = useState(
    display.type ? false : "displayTypeSelect"
  );

  const displayTypes = getDisplayTypes();

  /**
   * Update the Display on field update
   * @param {object} field
   * @param {string} value
   */
  const handleOnUpdate = (id, value) => {
    onUpdateDisplaySettings(id, value);

    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, id, value);
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    switch (popupStatus) {
      case "displayTypeSelect":
        return (
          <PopupTypeSelector
            forceUpdate={display.type ? false : true}
            defaultTitle={__("Display Type", "ditty-news-ticker")}
            defaultDescription={__(
              "Choose the display type you want to use.",
              "ditty-news-ticker"
            )}
            defaultIcon={<FontAwesomeIcon icon={faTabletScreen} />}
            currentType={display.type ? display.type : false}
            submitLabel={
              display.type
                ? __("Update Type", "ditty-news-ticker")
                : __("Use Type", "ditty-news-ticker")
            }
            types={displayTypes}
            getTypeObject={getDisplayTypeObject}
            onChange={(selectedType) => {
              const dittyEl = document.getElementById("ditty-editor__ditty");
              updateDittyDisplayType(dittyEl, selectedType);
            }}
            onClose={(selectedType) => {
              const dittyEl = document.getElementById("ditty-editor__ditty");
              setPopupStatus(false);
              if (display.type && display.type !== selectedType) {
                updateDittyDisplayType(dittyEl, display.type);
              }
            }}
            onUpdate={(updatedType) => {
              setPopupStatus(false);
              if (display.type === updatedType) {
                return false;
              }
              const updatedDisplay = _.cloneDeep(display);
              const updatedDisplayTypeObject =
                getDisplayTypeObject(updatedType);
              const updatedSettings = {
                ...updatedDisplayTypeObject.defaultValues,
                ...updatedDisplay.settings,
              };
              onUpdateDisplayType(updatedType, updatedSettings);
            }}
          />
        );
      default:
        return;
    }
  };

  const panelHeader = () => {
    return (
      <>
        <IconBlock
          icon={displayTypeObject.icon}
          className="ditty-icon-block--heading"
          style={{ padding: "0" }}
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{displayTypeObject.label}</h2>
            <Link onClick={() => setPopupStatus("displayTypeSelect")}>
              {__("Change Type", "ditty-news-ticker")}
            </Link>
          </div>
          <p>{displayTypeObject.description}</p>
        </IconBlock>
      </>
    );
  };

  const panelContent = () => {
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
        values={display.settings}
        onUpdate={handleOnUpdate}
      />
    );
  };

  return (
    <>
      <Panel
        id="editDisplay"
        header={panelHeader()}
        tabs={fieldGroups}
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
export default PanelDisplay;
