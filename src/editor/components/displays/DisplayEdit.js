import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { Button } from "@wordpress/components";
import Panel from "../Panel";
import Field from "../../common/Field";
import { updateDisplayOptions } from "../../../services/dittyService";
import {
  getDisplayTypeLabel,
  getDisplayTypeSettings,
} from "../../utils/displayTypes";

const DisplayEdit = ({ displayObject, goBack, editor }) => {
  const { actions } = useContext(editor);

  /**
   * Set the initial fields
   */
  const fieldGroups = getDisplayTypeSettings(displayObject);

  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);

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
   * Render the panel header
   * @returns components
   */
  const panelHeader = () => {
    return (
      <>
        <h3>
          {__(
            `Custom ${getDisplayTypeLabel(displayObject)} display`,
            "ditty-news-ticker"
          )}
        </h3>
        <div className="ditty-editor__panel__header__buttons">
          <Button variant="secondary">
            {__("Save as Template", "ditty-news-ticker")}
          </Button>
          <Button onClick={goBack} variant="link">
            {__("Cancel", "ditty-news-ticker")}
          </Button>
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

  return (
    <Panel
      id="displayEdit"
      header={panelHeader()}
      tabs={fieldGroups}
      tabClick={handleTabClick}
      currentTabId={currentTabId}
      tabsType="cloud"
      content={panelContent()}
    />
  );
};
export default DisplayEdit;
