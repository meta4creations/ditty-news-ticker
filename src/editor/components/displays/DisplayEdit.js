import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { Animate } from "@wordpress/components";
import Panel from "../Panel";
import Field from "../../common/Field";
import Modal from "../Modal";
import { updateDisplayOptions } from "../../../services/dittyService";
import { getDisplayTypeSettings } from "../../utils/displayTypes";

const DisplayEdit = ({ displayObject, goBack, editor }) => {
  const { actions } = useContext(editor);

  /**
   * Set the initial fields
   */
  const fieldGroups = getDisplayTypeSettings(displayObject);

  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);
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
  const handleFieldUpdate = (field, value) => {
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

    const fields = fieldGroups[index].fields;
    return (
      fields &&
      fields.map((field, index) => {
        const value = displayObject.settings[field.id]
          ? displayObject.settings[field.id]
          : field.std;
        return (
          <Field
            key={field.id ? field.id : `${field.id}${index}`}
            field={field}
            value={value}
            allValues={displayObject.settings}
            onFieldUpdate={handleFieldUpdate}
          />
        );
      })
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
