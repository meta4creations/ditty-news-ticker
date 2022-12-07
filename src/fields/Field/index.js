import { __ } from "@wordpress/i18n";
import { Fragment } from "@wordpress/element";

import BaseField from "../BaseField";
import ColorField from "../ColorField";
import CheckboxField from "../CheckboxField";
import GroupField from "../GroupField";
import NumberField from "../NumberField";
import RadioField from "../RadioField";
import SelectField from "../SelectField";
import SliderField from "../SliderField";
import SpacingField from "../SpacingField";
import TextField from "../TextField";
import TextareaField from "../TextareaField";
import UnitField from "../UnitField";

const Field = ({ field, value, allValues, updateValue }) => {
  let confirmedValue = value;
  if (!confirmedValue) {
    if (allValues) {
      confirmedValue = allValues[field.id]
        ? allValues[field.id]
        : field.std
        ? field.std
        : "";
    } else {
      confirmedValue = "";
    }
  }

  /**
   * Convert an objec to an array
   * @param {mixed} data
   * @returns array
   */
  const arrayValues = (data) => {
    if (typeof data === "object") {
      const modifiedArray = [];
      for (const key in data) {
        modifiedArray.push(data[key]);
      }
      return modifiedArray;
    }
    return data;
  };

  const renderInput = (inputField, inputValue) => {
    switch (inputField.type) {
      case "checkbox":
        return (
          <CheckboxField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "color":
        return (
          <ColorField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "group":
        const fields = arrayValues(inputField.fields);
        return (
          <GroupField value={inputValue} {...inputField}>
            {fields.map((groupField, index) => {
              if (showField(groupField)) {
                const groupFieldValue = allValues[groupField.id]
                  ? allValues[groupField.id]
                  : groupField.std
                  ? groupField.std
                  : "";

                return (
                  <Fragment key={groupField.id ? groupField.id : index}>
                    {renderInput(groupField, groupFieldValue)}
                  </Fragment>
                );
              } else {
                return false;
              }
            })}
          </GroupField>
        );
      case "heading":
        return <BaseField {...inputField} />;
      case "number":
        return (
          <NumberField
            value={String(inputValue)}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "radio":
        return (
          <RadioField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "radius":
        return (
          <SpacingField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "select":
        return (
          <SelectField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "slider":
        return (
          <SliderField
            value={String(inputValue)}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );

      case "spacing":
        return (
          <SpacingField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "textarea":
        return (
          <TextareaField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...field}
          />
        );
      case "unit":
        return (
          <UnitField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      case "wysiwyg":
        return (
          <TextareaField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
      default:
        return (
          <TextField
            value={inputValue}
            onChange={(updatedValue) => updateValue(inputField, updatedValue)}
            {...inputField}
          />
        );
    }
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
    return renderInput(field, confirmedValue);
  } else {
    return false;
  }
};

export default Field;
