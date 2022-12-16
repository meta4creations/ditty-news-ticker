import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";

import BaseField from "./BaseField";
import ColorField from "./ColorField";
import Clone from "./Clone";
import CloneField from "./CloneField";
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

const Field = ({ field, fieldValue, allValues, updateValue }) => {
  const [fieldVal, setFieldVal] = useState(fieldValue);

  const getCloneValues = (field, value = fieldVal) => {
    let cloneValues = Array.isArray(value) ? value : [value];
    if (cloneValues.length < 1) {
      cloneValues.push("");
    }

    const cloneValueObjects = cloneValues.map((cloneValue, cloneIndex) => {
      const cloneValueObject =
        typeof cloneValue === "object" && cloneValue._id
          ? cloneValue
          : { _id: Date.now() + cloneIndex, _value: cloneValue };
      return cloneValueObject;
    });
    return cloneValueObjects;
  };

  const addCloneValue = (field, cloneValues, value, index) => {
    if (index && index <= cloneValues.length) {
      cloneValues.splice(index, 0, { _id: Date.now() + index, _value: value });
    } else {
      cloneValues.push({ _id: Date.now() + cloneValues.length, _value: value });
    }

    handleUpdateCloneValue(field, cloneValues);
  };

  const handleUpdateCloneValue = (field, cloneValues) => {
    const cleanedValues = cloneValues.map((cloneValue) => {
      return cloneValue._id ? cloneValue._value : cloneValue;
    });
    updateValue(field.id, cleanedValues);
    setFieldVal(cloneValues);
  };

  const handleUpdateValue = (field, value) => {
    if (field.cloneIndex) {
      const cloneValues = getCloneValues(field);
      cloneValues[Number(field.cloneIndex)]._value = value;
      handleUpdateCloneValue(field, cloneValues);
    } else {
      if (Array.isArray(value)) {
        value.map((v) => updateValue(v.id, v.value));
      } else {
        updateValue(field.id, value);
      }
      setFieldVal(value);
    }
  };

  const renderClone = (inputField, inputValue) => {
    const cloneValues = getCloneValues(inputField, inputValue);
    const cloneFields = cloneValues.map((cloneValue, cloneIndex) => {
      const cloneField = { ...inputField };
      delete cloneField.clone;
      delete cloneField.clone_button;
      cloneField.hideHeader = true;
      cloneField.cloneIndex = `${cloneIndex}`;
      cloneField.cloneId = cloneValue._id;

      return {
        id: cloneValue._id,
        data: cloneValue,
        content: (
          <CloneField
            key={`${inputField.id}${cloneIndex}`}
            value={cloneValue._value}
            onDelete={() => {
              cloneValues.splice(cloneIndex, 1);
              handleUpdateCloneValue(inputField, cloneValues);
            }}
            onClone={(value = "") => {
              addCloneValue(inputField, cloneValues, value, cloneIndex + 1);
            }}
          >
            {renderInput(cloneField, cloneValue._value)}
          </CloneField>
        ),
      };
    });

    return (
      <Clone
        {...inputField}
        fields={cloneFields}
        onSort={(sortedValues) => {
          handleUpdateCloneValue(inputField, sortedValues);
        }}
        onClone={() => {
          addCloneValue(inputField, cloneValues, "");
        }}
      />
    );
  };

  const renderInput = (
    inputField,
    inputValue,
    onUpdate = handleUpdateValue
  ) => {
    if (inputField.clone) {
      return renderClone(inputField, inputValue);
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
          return (
            <GroupField
              value={inputValue}
              allValues={allValues}
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

  return renderInput(field, fieldVal, handleUpdateValue);
};

export default Field;
