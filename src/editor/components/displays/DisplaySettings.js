import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import Field from "../../common/Field";
import { updateDisplayOptions } from "../../../services/dittyService";

const DisplaySettings = ({ display, editor }) => {
  const { helpers, actions } = useContext(editor);

  const handleFieldUpdate = (field, value) => {
    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, display.type, field.id, value);

    // Update the editor display
    const updatedDisplay = { ...display };
    updatedDisplay.settings[field.id] = value;
    actions.setCurrentDisplay(updatedDisplay);
  };

  const renderFields = () => {
    const fields = helpers.displayTypeFields(display.type);
    return (
      fields &&
      fields.map((field) => {
        const value = display.settings[field.id]
          ? display.settings[field.id]
          : field.std;

        return (
          <Field
            key={field.id}
            field={field}
            value={value}
            onFieldUpdate={handleFieldUpdate}
          />
        );
      })
    );
  };

  return <div className="dittyEditorFields">{renderFields()}</div>;
};
export default DisplaySettings;
