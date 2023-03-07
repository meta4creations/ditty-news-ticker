import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";
import Clone from "./Clone";
import CheckboxField from "./CheckboxField";
import CheckboxesField from "./CheckboxesField";
import ColorField from "./ColorField";
import ComponentField from "./ComponentField";
import GroupField from "./GroupField";
import HtmlField from "./HtmlField";
import LayoutTagField from "./LayoutTagField";
import NumberField from "./NumberField";
import RadioField from "./RadioField";
import SelectField from "./SelectField";
import SliderField from "./SliderField";
import SpacingField from "./SpacingField";
import TextField from "./TextField";
import TextareaField from "./TextareaField";
import UnitField from "./UnitField";

const Field = ({ field, fieldValue, updateValue, delayChange = false }) => {
  const handleUpdateValue = (field, value) => {
    if ("group" === field.type && Array.isArray(value) && !field.clone) {
      updateValue(field.id, value);
      //value.map((v) => updateValue(v.id, v.value));
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
        case "checkboxes":
          return (
            <CheckboxesField
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
        case "component":
          return <ComponentField value={inputValue} {...inputField} />;
        case "group":
          return (
            <GroupField
              value={inputValue}
              renderInput={renderInput}
              onChange={(updatedValue, maybeField = false) => {
                const updatedField = maybeField ? maybeField : inputField;
                onUpdate(updatedField, updatedValue);
              }}
              {...inputField}
            />
          );
        case "heading":
          return <BaseField {...inputField} />;
        case "html":
          return <HtmlField value={inputValue} {...inputField} />;
        case "layout_attribute":
          return (
            <LayoutTagField
              value={inputValue}
              renderInput={renderInput}
              onChange={(updatedValue) => {
                onUpdate(inputField, updatedValue);
              }}
              {...inputField}
            />
          );
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
              delayChange={delayChange}
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
              delayChange={delayChange}
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
