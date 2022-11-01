import { __ } from "@wordpress/i18n";
import {
  CheckboxControl,
  ColorPicker,
  DimensionControl,
  RadioControl,
  RangeControl,
  SelectControl,
  TextControl,
  TextareaControl,
  __experimentalBoxControl as BoxControl,
} from "@wordpress/components";

const Field = ({ field, value, onFieldUpdate }) => {
  const convertFieldOptions = (options) => {
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
      case "checkbox":
        return (
          <CheckboxControl
            label={field.label}
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "color":
        return (
          <ColorPicker
            color={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            enableAlpha
          />
        );
      case "number":
        return (
          <TextControl
            label={field.name}
            value={Number(value)}
            type="number"
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "slider":
        return (
          <RangeControl
            label={field.name}
            value={Number(value)}
            min={field.min}
            max={field.max}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "radio":
        return (
          <RadioControl
            label={field.name}
            help={field.help}
            selected={value}
            options={convertFieldOptions(field.options)}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "select":
        return (
          <SelectControl
            label={field.name}
            value={value}
            options={convertFieldOptions(field.options)}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "spacing":
        return (
          <BoxControl
            label={field.name}
            values={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "textarea":
        return (
          <TextareaControl
            label={field.name}
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "wysiwyg":
        return (
          <TextareaControl
            label={field.name}
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      default:
        return (
          <TextControl
            label={field.name}
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
    }
  };

  return (
    <div
      className={`dittyEditorField dittyEditorField--${field.type}`}
      key={field.id}
    >
      {renderField()}
    </div>
  );
};

export default Field;
