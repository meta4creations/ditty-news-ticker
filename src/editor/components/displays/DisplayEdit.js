import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { Animate } from "@wordpress/components";
import Panel from "../Panel";
import Field from "../../../fields/Field";
import Modal from "../Modal";
import { updateDisplayOptions } from "../../../services/dittyService";
import {
  getDisplayTypes,
  getDisplayTypeSettings,
  getDisplayTypeObject,
} from "../../utils/displayTypes";
import Tabs from "../../common/Tabs";
import IconBlock from "../../common/IconBlock";
import IconButton from "../../common/IconButton";

const DisplayEdit = ({ displayObject, goBack, editor }) => {
  const { actions } = useContext(editor);
  const displayTypeObject = getDisplayTypeObject(displayObject);
  const displayTypes = getDisplayTypes();

  /**
   * Set the initial fields
   */
  const fieldGroups = getDisplayTypeSettings(displayObject);

  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);
  const [showDisplayTypes, setShowDisplayTypes] = useState(false);
  const [currentDisplayType, setCurrentDisplayType] = useState(
    displayObject.type
  );
  const [modalStatus, setModalStatus] = useState(false);
  const toggleModalStatus = (status) => {
    if (status === modalStatus) {
      setModalStatus(false);
    } else {
      setModalStatus(status);
    }
  };

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

  const handleTypeClick = (displayType) => {
    setCurrentDisplayType(displayType.id);
  };

  const renderDisplayTypes = () => {
    return (
      <Tabs
        tabs={displayTypes}
        currentTabId={currentDisplayType}
        tabClick={handleTypeClick}
        type="cloud"
      />
    );
  };

  /**
   * Render the panel Header
   * @returns components
   */
  const panelHeader = () => {
    return (
      <>
        <IconBlock icon={displayTypeObject.icon}>
          <h2>{displayTypeObject.label}</h2>
          <p>{displayTypeObject.description}</p>
          <div className="ditty-displayEdit__links">
            <button
              className="ditty-link"
              onClick={() => setShowDisplayTypes(true)}
            >
              {__("Change Type", "ditty-news-ticker")}
            </button>
            <button className="ditty-link">
              {__("Use Template", "ditty-news-ticker")}
            </button>
          </div>
        </IconBlock>

        <div className="ditty-displayEdit__types">{renderDisplayTypes()}</div>
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
          <button
            className="ditty-button"
            onClick={() => toggleModalStatus("save")}
          >
            {__("Save as Template", "ditty-news-ticker")}
          </button>
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

  const renderSaveModal = () => {
    return (
      <Modal
        closeModal={toggleModalStatus}
        label="Save Display Settings as a Template"
      >
        Popover is toggled!
      </Modal>
    );
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
      {"save" === modalStatus && renderSaveModal()}
    </>
  );
};
export default DisplayEdit;
