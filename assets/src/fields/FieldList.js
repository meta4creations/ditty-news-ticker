const { __ } = wp.i18n;
import classnames from "classnames";
import Field from "./Field";
import { showField } from "./fieldHelpers";

const FieldList = ({
  name,
  description,
  fields,
  children,
  values,
  className,
  onUpdate,
  delayChange = false,
}) => {
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

  return (
    <div className={classes}>
      {(name || description) && (
        <div className="ditty-field-list__heading">
          {name && <h3 className="ditty-field-list__heading__title">{name}</h3>}{" "}
          {description && (
            <p className="ditty-field-list__heading__description">
              {description}
            </p>
          )}
        </div>
      )}
      {children && children}
      {fields &&
        fields.map((field, index) => {
          return showField(field, values) ? (
            <Field
              key={field.id ? field.id : index}
              field={field}
              fieldValue={fieldValue(field)}
              updateValue={onUpdate}
              delayChange={delayChange}
            />
          ) : null;
        })}
    </div>
  );
};

export default FieldList;
