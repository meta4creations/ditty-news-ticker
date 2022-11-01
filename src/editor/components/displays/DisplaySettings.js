import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import Field from "../../common/Field";

const DisplaySettings = ({ display, editor }) => {
  const { helpers, actions } = useContext(editor);

  const handleFieldUpdate = (field, value) => {
    actions.updateDisplay(display, field, value);
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
