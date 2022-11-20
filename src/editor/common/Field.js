import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import {
  CheckboxControl,
  ColorPicker,
  DimensionControl,
  RangeControl,
  RadioControl,
  SelectControl,
  TextControl,
  TextareaControl,
  __experimentalBorderControl as BorderControl,
  __experimentalBoxControl as BoxControl,
  __experimentalHeading as Heading,
  __experimentalUnitControl as UnitControl,
} from "@wordpress/components";
import SliderField from "./fields/SliderField";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";

const Field = ({ field, value, allValues, onFieldUpdate }) => {
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

  const renderInput = () => {
    switch (field.type) {
      case "border":
        return (
          <BorderControl
            label={field.name}
            //hideLabelFromVision="true"
            values={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "checkbox":
        return (
          <CheckboxControl
            label={field.label}
            checked={value ? value : false}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "color":
        return (
          <ColorPicker
            label={field.name}
            //hideLabelFromVision="true"
            color={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            enableAlpha
          />
        );
      case "dimension":
        return (
          <DimensionControl
            label={field.name}
            //hideLabelFromVision="true"
            color={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            enableAlpha
          />
        );
      case "heading":
        const level = field.level ? field.level : 3;
        return <Heading level={level}>{value}</Heading>;
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
        return <SliderField {...field} />;
      case "radio":
        return (
          <RadioControl
            label={field.name}
            hideLabelFromVision="true"
            selected={value}
            options={convertFieldOptions(field.options)}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "select":
        return (
          <SelectControl
            label={field.name}
            hideLabelFromVision="true"
            value={value}
            options={convertFieldOptions(field.options)}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "spacing":
        return (
          <BoxControl
            label={field.name}
            //hideLabelFromVision="true"
            values={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "textarea":
        return (
          <TextareaControl
            label={field.name}
            //hideLabelFromVision="true"
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "unit":
        return (
          <UnitControl
            label={field.name}
            hideLabelFromVision="true"
            value={value}
            size="default"
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "wysiwyg":
        return (
          <TextareaControl
            label={field.name}
            //hideLabelFromVision="true"
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      default:
        return (
          <TextControl
            label={field.name}
            hideLabelFromVision="true"
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
    }
  };

  const getInputClass = () => {
    let className = `ditty-field__input ditty-field__input--${field.type}`;
    if (field.inline) {
      className += " ditty-field__input--inline";
    }
    return className;
  };

  const showField = () => {
    if (!field.show) {
      return true;
    }

    const operators = {
      "=": (a, b) => {
        return a === b;
      },
      "!=": (a, b) => {
        return a !== b;
      },
    };

    if (field.show) {
      const relation = field.show.relation ? field.show.relation : "AND";
      const checks = field.show.fields.map((f) => {
        if (operators[f.compare](allValues[f.key], f.value)) {
          return "pass";
        } else {
          return "fail";
        }
      });
      if ("OR" === relation) {
        return checks.includes("pass");
      } else {
        return checks.every((v) => v === "pass");
      }
    }
  };

  if (showField()) {
    return (
      <div
        className={`ditty-field ditty-field--${field.type} ${
          field.help && displayHelp ? "ditty-field--help" : ""
        }`}
        key={field.id}
      >
        <div className="ditty-field__heading">
          <label className="ditty-field__label">
            {field.name}{" "}
            {field.help && (
              <FontAwesomeIcon
                className="ditty-field__help-icon"
                icon={faCircleQuestion}
                onClick={toggleHelp}
              />
            )}
          </label>
          {field.help && displayHelp && (
            <div className="ditty-field__help">{field.help}</div>
          )}
        </div>
        <div className={getInputClass()}>{renderInput()}</div>
      </div>
    );
  } else {
    return false;
  }
};

export default Field;
