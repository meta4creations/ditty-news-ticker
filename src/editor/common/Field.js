import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import {
  TextControl,
  TextareaControl,
  __experimentalBorderControl as BorderControl,
  __experimentalHeading as Heading,
} from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";
import ColorField from "./fields/ColorField";
import CheckboxField from "./fields/CheckboxField";
import RadioField from "./fields/RadioField";
import SelectField from "./fields/SelectField";
import SliderField from "./fields/SliderField";
import SpacingField from "./fields/SpacingField";
import UnitField from "./fields/UnitField";

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

  const renderInput = (inputField) => {
    switch (inputField.type) {
      case "border":
        return (
          <BorderControl
            label={inputField.name}
            help={inputField.help}
            values={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "checkbox":
        return (
          <CheckboxField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            {...field}
          />
        );
      case "color":
        return (
          <ColorField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            {...field}
          />
        );
      case "heading":
        const level = inputField.level ? inputField.level : 3;
        return <Heading level={level}>{value}</Heading>;
      case "number":
        return (
          <TextControl
            label={inputField.name}
            help={inputField.help}
            value={Number(value)}
            type="number"
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "slider":
        return (
          <SliderField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            {...field}
          />
        );
      case "radio":
        return (
          <RadioField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            {...field}
          />
        );
      case "select":
        return (
          <SelectField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            {...field}
          />
        );
      case "spacing":
        return (
          <SpacingField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            {...field}
          />
        );
      case "textarea":
        return (
          <TextareaControl
            label={inputField.name}
            help={inputField.help}
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      case "unit":
        return (
          <UnitField
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
            {...field}
          />
        );
      case "wysiwyg":
        return (
          <TextareaControl
            label={inputField.name}
            help={inputField.help}
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
      default:
        return (
          <TextControl
            label={inputField.name}
            help={inputField.help}
            value={value}
            onChange={(updatedValue) => updateValue(updatedValue)}
          />
        );
    }
  };

  const getInputClass = (inputField) => {
    let className = `ditty-field__input ditty-field__input--${inputField.type}`;
    if (inputField.inline) {
      className += " ditty-field__input--inline";
    }
    return className;
  };

  const showField = (inputField) => {
    if (!inputField.show) {
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

    if (inputField.show) {
      const relation = inputField.show.relation
        ? inputField.show.relation
        : "AND";
      const checks = inputField.show.fields.map((f) => {
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

  if (showField(field)) {
    return renderInput(field);
  } else {
    return false;
  }
};

export default Field;
