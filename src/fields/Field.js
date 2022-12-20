import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";
import ColorField from "./ColorField";
import Clone from "./Clone";
import CheckboxField from "./CheckboxField";
import GroupField from "./GroupField";
import NumberField from "./NumberField";
import RadioField from "./RadioField";
import SelectField from "./SelectField";
import SliderField from "./SliderField";
import SpacingField from "./SpacingField";
import TextField from "./TextField";
import TextareaField from "./TextareaField";
import UnitField from "./UnitField";

const Field = ({ field, fieldValue, updateValue }) => {
  const handleUpdateValue = (field, value) => {
    if (Array.isArray(value) && !field.clone) {
      value.map((v) => updateValue(v.id, v.value));
    } else {
      updateValue(field.id, value);
    }
  };

  const renderInput = (
    inputField,
    inputValue,
    onUpdate = handleUpdateValue
  ) => {
    if (inputField.clone) {
      return (
        <Clone
          field={inputField}
          value={inputValue}
          renderInput={renderInput}
          onChange={(updatedValue) => {
            onUpdate(inputField, updatedValue);
          }}
        />
      );
    } else {
      switch (inputField.type) {
        case "checkbox":
          return (
            <CheckboxField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "color":
          return (
            <ColorField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "group":
          if (inputField.multiple_fields) {
            inputField.multipleFields = inputField.multiple_fields;
          }
          if (inputField.default_state) {
            inputField.defaultState = inputField.default_state;
          }
          return (
            <GroupField
              value={inputValue}
              renderInput={renderInput}
              onChange={(updatedValue) => {
                onUpdate(inputField, updatedValue);
              }}
              {...inputField}
            />
          );
        case "heading":
          return <BaseField {...inputField} />;
        case "number":
          return (
            <NumberField
              value={String(inputValue)}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "radio":
          return (
            <RadioField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "radius":
          return (
            <SpacingField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "select":
          return (
            <SelectField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "slider":
          return (
            <SliderField
              value={String(inputValue)}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );

        case "spacing":
          return (
            <SpacingField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "textarea":
          return (
            <TextareaField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "unit":
          return (
            <UnitField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        case "wysiwyg":
          return (
            <TextareaField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
        default:
          return (
            <TextField
              value={inputValue}
              onChange={(updatedValue) => onUpdate(inputField, updatedValue)}
              {...inputField}
            />
          );
      }
    }
  };

  return renderInput(field, fieldValue, handleUpdateValue);
};

export default Field;
