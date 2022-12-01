import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCubes } from "@fortawesome/pro-light-svg-icons";

import {
  updateDisplayOptions,
  updateDittyDisplayTemplate,
} from "../../services/dittyService";
import { Button, ButtonGroup, IconBlock, Link, Popup } from "../../components";
import {
  getDisplayTypeSettings,
  getDisplayTypeObject,
} from "../../utils/displayTypes";
import Field from "../../fields/Field";

import Panel from "../components/Panel";
import { DisplayTypeSelector, DisplayTemplateSelector } from "../displays";

const DisplayEdit = ({ displayObject, goBack, editor }) => {
  const { displays, actions } = useContext(editor);
  const displayTypeObject = getDisplayTypeObject(displayObject);

  /**
   * Set the initial fields
   */
  const fieldGroups = getDisplayTypeSettings(displayObject);

  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);

  const [popupStatus, setPopupStatus] = useState(false);
  const [displayTypeSelection, setDisplayTypeSelection] =
    useState(displayTypeObject);
  const [displayTemplateSelection, setDisplayTemplateSelection] =
    useState(false);

  /**
   * Set the current tab
   * @param {string} tab
   */
  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  /**
   * Update the Display on field update
   * @param {object} field
   * @param {string} value
   */
  const handleUpdateValue = (field, value) => {
    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, displayObject.type, field.id, value);

    // Update the editor display
    const updatedDisplay = { ...displayObject };
    updatedDisplay.settings[field.id] = value;
    updatedDisplay.updated = Date.now();
    actions.setCurrentDisplay(updatedDisplay);
  };

  /**
   * Render the panel Header
   * @returns components
   */
  const panelHeader = () => {
    return (
      <>
        <IconBlock icon={displayTypeObject.icon} iconSize="60px">
          <h2>{displayTypeObject.label}</h2>
          <p>{displayTypeObject.description}</p>
          <ButtonGroup className="ditty-displayEdit__links">
            <Link onClick={() => setPopupStatus("displayTypeSelect")}>
              {__("Change Type", "ditty-news-ticker")}
            </Link>
            <Link onClick={() => setPopupStatus("displayTemplateSelect")}>
              {__("Use Template", "ditty-news-ticker")}
            </Link>
          </ButtonGroup>
        </IconBlock>
      </>
    );
  };

  /**
   * Render the panel Footer
   * @returns components
   */
  const panelFooter = () => {
    return (
      <>
        <div className="ditty-editor__panel__header__buttons">
          <Button>{__("Save as Template", "ditty-news-ticker")}</Button>
        </div>
      </>
    );
  };

  /**
   * Render the panel content
   * @returns components
   */
  const panelContent = () => {
    const index = fieldGroups.findIndex((fieldGroup) => {
      return fieldGroup.id === currentTabId;
    });
    if (-1 === index) {
      return false;
    }

    const fieldGroup = fieldGroups[index];

    const fields =
      fieldGroup.fields &&
      fieldGroup.fields.map((field, index) => {
        const value = displayObject.settings[field.id]
          ? displayObject.settings[field.id]
          : field.std
          ? field.std
          : "";

        return (
          <Field
            key={field.id ? field.id : index}
            field={field}
            value={value}
            allValues={displayObject.settings}
            updateValue={handleUpdateValue}
          />
        );
      });

    return (
      <>
        <Field
          key={`${fieldGroup.id}Panel`}
          field={{
            id: `${fieldGroup.id}Panel`,
            type: "heading",
            name: fieldGroup.name,
            desc: fieldGroup.desc,
            icon: fieldGroup.icon,
            class: "ditty-field--panel-heading",
          }}
        />
        {fields}
      </>
    );
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    switch (popupStatus) {
      case "displayTypeSelect":
        return (
          <Popup
            id={popupStatus}
            header={
              <IconBlock icon={displayTypeSelection.icon}>
                <h2>{displayTypeSelection.label}</h2>
                <p>{displayTypeSelection.description}</p>
              </IconBlock>
            }
            onClose={() => {
              setPopupStatus(false);
              setDisplayTypeSelection(displayTypeObject);
            }}
            onSubmit={() => {
              setPopupStatus(false);
              if (displayTypeObject.id === displayTypeSelection.id) {
                return false;
              }

              // Update the editor display
              const updatedDisplay = { ...displayObject };
              updatedDisplay.type = displayTypeSelection.id;
              updatedDisplay.updated = Date.now();

              // Update the Ditty options
              const dittyEl = document.getElementById("ditty-editor__ditty");
              updateDittyDisplayTemplate(
                dittyEl,
                updatedDisplay,
                displayObject
              );

              actions.setCurrentDisplay(updatedDisplay);
            }}
          >
            <DisplayTypeSelector
              selected={displayTypeSelection.id}
              onSelected={setDisplayTypeSelection}
            />
          </Popup>
        );
      case "displayTemplateSelect":
        return (
          <Popup
            id={popupStatus}
            header={
              <IconBlock icon={<FontAwesomeIcon icon={faCubes} />}>
                <h2>
                  {__("Choose a saved Display template", "ditty-news-ticker")}
                </h2>
                <p>
                  {__(
                    "Select one of your previously saved Display templates.",
                    "ditty-news-ticker"
                  )}
                </p>
              </IconBlock>
            }
            onClose={() => {
              setPopupStatus(false);
            }}
            onSubmit={() => {
              setPopupStatus(false);
              // if (displayTypeObject.id === displayTypeSelection.id) {
              //   return false;
              // }

              // // Update the editor display
              // const updatedDisplay = { ...displayObject };
              // updatedDisplay.type = displayTypeSelection.id;
              // updatedDisplay.updated = Date.now();

              // // Update the Ditty options
              // const dittyEl = document.getElementById("ditty-editor__ditty");
              // updateDittyDisplayTemplate(dittyEl, updatedDisplay, displayObject);

              // actions.setCurrentDisplay(updatedDisplay);
            }}
          >
            <DisplayTemplateSelector
              selected={displayTemplateSelection}
              onSelected={setDisplayTemplateSelection}
              editor={editor}
            />
          </Popup>
        );
      default:
        return;
    }
  };

  return (
    <>
      <Panel
        id="displayEdit"
        header={panelHeader()}
        footer={panelFooter()}
        tabs={fieldGroups}
        tabClick={handleTabClick}
        currentTabId={currentTabId}
        tabsType="cloud"
        content={panelContent()}
      />
      {renderPopup()}
    </>
  );
};
export default DisplayEdit;
