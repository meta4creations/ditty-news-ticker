import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
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

const PanelDisplays = ({
  display,
  title,
  description,
  onUpdateDisplaySettings,
  onUpdateDisplayType,
}) => {
  const displayTypeObject = getDisplayTypeObject(display);
  const fieldGroups = getDisplayTypeSettings(display);
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);
  const [popupStatus, setPopupStatus] = useState(false);

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
    const dittyEl = document.getElementById("ditty-editor__ditty");
    switch (popupStatus) {
      case "displayTypeSelect":
        return (
          <PopupTypeSelector
            currentType={display.type}
            types={displayTypes}
            getTypeObject={getDisplayTypeObject}
            onChange={(selectedType) => {
              updateDittyDisplayType(dittyEl, selectedType);
            }}
            onClose={(selectedType) => {
              setPopupStatus(false);
              if (display.type !== selectedType) {
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
          style={{ marginBottom: "10px" }}
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{title}</h2>
            <Link onClick={() => setPopupStatus("displayTypeSelect")}>
              {__("Change Type", "ditty-news-ticker")}
            </Link>
          </div>
          <p>{description}</p>
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
        id="displays"
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
export default PanelDisplays;
