import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import Field from "./Field";

const FieldList = ({ fields, children, values, className, onUpdate }) => {
  const classes = classnames("ditty-field-list", className);

  const groupFields = (gFields) => {
    if (Array.isArray(gFields)) {
      return gFields;
    }
    if ("object" === typeof gFields) {
      const fieldsArray = [];
      for (const key in gFields) {
        fieldsArray.push(gFields[key]);
      }
      return fieldsArray;
    }
  };

  const fieldValue = (field) => {
    let value = values[field.id]
      ? values[field.id]
      : field.std
      ? field.std
      : "";
    if ("group" === field.type && field.multipleFields) {
      value = groupFields(field.fields).map((f) => {
        return {
          id: f.id,
          value: values[f.id] ? values[f.id] : f.std ? f.std : "",
        };
      });
    }
    return value;
  };

  /**
   * Check field visibility based on other field values
   * @param {object} field
   * @returns
   */
  const showField = (field) => {
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
        if (operators[f.compare](values[f.key], f.value)) {
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

  return (
    <div className={classes}>
      {children && children}
      {fields &&
        fields.map((field, index) => {
          return showField(field) ? (
            <Field
              key={field.id ? field.id : index}
              field={field}
              fieldValue={fieldValue(field)}
              updateValue={onUpdate}
            />
          ) : null;
        })}
    </div>
  );
};

export default FieldList;
