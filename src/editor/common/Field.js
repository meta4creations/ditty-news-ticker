import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import {
  CheckboxControl,
  ColorPicker,
  RangeControl,
  SelectControl,
  TextControl,
  TextareaControl,
  __experimentalBorderControl as BorderControl,
  __experimentalBoxControl as BoxControl,
} from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";
import TextField from "./fields/TextField";
import RadioField from "./fields/RadioField";

const Field = ({ field, value, onFieldUpdate }) => {
  const [displayHelp, setDisplayHelp] = useState(false);

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

  const toggleHelp = () => {
    if (displayHelp) {
      setDisplayHelp(false);
    } else {
      setDisplayHelp(true);
    }
  };

  const renderField = () => {
    switch (field.type) {
      case "border":
        return (
          <BorderControl
            label={field.name}
            hideLabelFromVision="true"
            values={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "checkbox":
        return (
          <CheckboxControl
            label={field.label}
            hideLabelFromVision="true"
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "color":
        return (
          <ColorPicker
            label={field.label}
            hideLabelFromVision="true"
            color={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            enableAlpha
          />
        );
      case "number":
        return (
          <TextField
            value={Number(value)}
            type="number"
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "slider":
        return (
          <RangeControl
            label={field.label}
            hideLabelFromVision="true"
            value={Number(value)}
            step={1}
            widthInputField="true"
            min={field.min}
            max={field.max}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "radio":
        return (
          <RadioField
            selected={value}
            options={convertFieldOptions(field.options)}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "select":
        return (
          <SelectControl
            label={field.label}
            hideLabelFromVision="true"
            value={value}
            options={convertFieldOptions(field.options)}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "spacing":
        return (
          <BoxControl
            label={field.label}
            hideLabelFromVision="true"
            values={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "textarea":
        return (
          <TextareaControl
            label={field.label}
            hideLabelFromVision="true"
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "wysiwyg":
        return (
          <TextareaControl
            label={field.label}
            hideLabelFromVision="true"
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      default:
        return (
          <TextField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
    }
  };

  return (
    <div
      className={`ditty-field ditty-field--${field.type} ${
        field.help && displayHelp ? " ditty-field--help" : ""
      }`}
      key={field.id}
    >
      <div className="ditty-field__heading">
        <label className="ditty-field__label">
          {field.name}{" "}
          {field.help && (
            <FontAwesomeIcon icon={faCircleQuestion} onClick={toggleHelp} />
          )}
        </label>
        {field.help && displayHelp && (
          <div className="ditty-field__help">{field.help}</div>
        )}
      </div>
      <div className="ditty-field__input">{renderField()}</div>
    </div>
  );
};

export default Field;
