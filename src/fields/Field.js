import { __ } from "@wordpress/i18n";
import { Fragment } from "@wordpress/element";

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
  let confirmedValue = fieldValue;
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

  const getCloneValues = (field, value = confirmedValue) => {
    let cloneValues = Array.isArray(value) ? value : [value];
    if (cloneValues.length < 1) {
      cloneValues.push("");
    }
    return cloneValues;
  };

  const addCloneValue = (field, cloneValues, value, index) => {
    if (index && index <= cloneValues.length) {
      cloneValues.splice(index, 0, value);
    } else {
      cloneValues.push(value);
    }

    updateValue(field, cloneValues);
    return cloneValues;
  };

  const handleUpdateValue = (field, value) => {
    if (field.cloneIndex) {
      const cloneValues = getCloneValues(field);
      cloneValues[Number(field.cloneIndex)] = value;
      updateValue(field, cloneValues);
    } else {
      updateValue(field, value);
    }
  };

  const renderClones = (inputField, inputValue) => {
    const cloneValues = getCloneValues(inputField, inputValue);
    const cloneFields = cloneValues.map((cloneValue, cloneIndex) => {
      const cloneField = { ...inputField };
      delete cloneField.clone;
      delete cloneField.clone_button;
      cloneField.hideHeader = true;
      cloneField.cloneIndex = `${cloneIndex}`;

      return {
        id: `${inputField.id}${cloneIndex}`,
        data: cloneValue,
        content: (
          <CloneField
            key={`${inputField.id}${cloneIndex}`}
            value={cloneValue}
            onDelete={() => {
              cloneValues.splice(cloneIndex, 1);
              updateValue(inputField, cloneValues);
            }}
            onClone={(value = "") => {
              addCloneValue(inputField, cloneValues, value, cloneIndex + 1);
            }}
          >
            {renderInput(cloneField, cloneValue)}
          </CloneField>
        ),
      };
    });

    return (
      <Clone
        {...inputField}
        fields={cloneFields}
        onSort={(sortedValues) => {
          updateValue(inputField, sortedValues);
        }}
        onClone={() => {
          addCloneValue(inputField, cloneValues, "");
        }}
      />
    );
  };

  const renderInput = (inputField, inputValue) => {
    if (inputField.clone) {
      return renderClones(inputField, inputValue);
    } else {
      switch (inputField.type) {
        case "checkbox":
          return (
            <CheckboxField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "color":
          return (
            <ColorField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "group":
          const fields = arrayValues(inputField.fields);
          return (
            <GroupField {...inputField}>
              {fields.map((groupField, index) => {
                if (showField(groupField)) {
                  const groupFieldValue = inputValue[groupField.id]
                    ? inputValue[groupField.id]
                    : groupField.std
                    ? groupField.std
                    : "";

                  return (
                    <Fragment
                      key={
                        groupField.id
                          ? `${inputField.id}${groupField.id}`
                          : `${inputField.id}${index}`
                      }
                    >
                      <div
                        className={`GROUPFIELD type-${groupField.type} id-${groupField.id}`}
                      >
                        {renderInput(groupField, groupFieldValue)}
                      </div>
                    </Fragment>
                  );
                } else {
                  console.log("no show");
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
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "radio":
          return (
            <RadioField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "radius":
          return (
            <SpacingField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "select":
          return (
            <SelectField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "slider":
          return (
            <SliderField
              value={String(inputValue)}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );

        case "spacing":
          return (
            <SpacingField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "textarea":
          return (
            <TextareaField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...field}
            />
          );
        case "unit":
          return (
            <UnitField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        case "wysiwyg":
          return (
            <TextareaField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
        default:
          return (
            <TextField
              value={inputValue}
              onChange={(updatedValue) =>
                handleUpdateValue(inputField, updatedValue)
              }
              {...inputField}
            />
          );
      }
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
