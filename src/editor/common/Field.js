import { __ } from "@wordpress/i18n";
import {
  TextControl,
  TextareaControl,
  SelectControl,
  CheckboxControl,
} from "@wordpress/components";

const Field = ({ field, value, onFieldUpdate }) => {
  const convertSelectOptions = (options) => {
    const optionsArray = [];
    for (const key in options) {
      optionsArray.push({
        label: options[key],
        value: key,
      });
    }
    return optionsArray;
  };

  const updateValue = (value) => {
    onFieldUpdate(field, value);
  };

  const renderField = () => {
    switch (field.type) {
      case "textarea":
        return (
          <TextareaControl
            label={field.name}
            value={value}
            onChange={(value) => updateValue(value)}
          />
        );
      case "select":
        return (
          <SelectControl
            label={field.name}
            value={value}
            options={convertSelectOptions(field.options)}
            onChange={(value) => updateValue(value)}
          />
        );
      case "checkbox":
        return (
          <CheckboxControl
            label={field.label}
            value={value}
            onChange={(value) => updateValue(value)}
          />
        );
      default:
        return (
          <TextControl
            label={field.name}
            value={value}
            onChange={(value) => updateValue(value)}
          />
        );
    }
  };

  return (
    <div className="dittyEditorField" key={field.id}>
      {renderField()}
    </div>
  );
};

export default Field;
