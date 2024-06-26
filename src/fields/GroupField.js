const { __ } = wp.i18n;
const { Fragment, useState } = wp.element;
import classnames from "classnames";
import { Icon } from "../components";
import FieldHeader from "./FieldHeader";
import { showField } from "./fieldHelpers";

const GroupField = (props) => {
  const {
    id,
    fields,
    value,
    className,
    collapsible,
    defaultState = "expanded",
    multipleFields = false,
    onChange,
    renderInput,
  } = props;
  const [displayContent, setDisplayContent] = useState(
    collapsible && "collapsed" === defaultState ? false : true
  );

  const toggleContent = (e) => {
    if (
      collapsible &&
      !e.target.classList.contains("ditty-field__help-icon") &&
      !e.target.parentElement.classList.contains("ditty-field__help-icon")
    ) {
      setDisplayContent(!displayContent);
    }
  };

  const fieldClasses = classnames(
    "ditty-field",
    "ditty-field--group",
    `ditty-field-id--${id}`,
    className
  );

  const styles = {
    cursor: collapsible ? "pointer" : "default",
  };

  const handleUpdateValue = (inputField, updatedValue) => {
    let groupValue;
    if (multipleFields) {
      onChange(updatedValue, inputField);
    } else {
      groupValue =
        typeof value === "object" && !Array.isArray(value) ? value : {};
      groupValue[inputField.id] = updatedValue;
      onChange(groupValue);
    }
  };

  const groupValues = multipleFields ? {} : value;
  if (multipleFields && Array.isArray(value)) {
    value.map((v) => (groupValues[v.id] = v.value));
  }

  const groupFields = () => {
    if (Array.isArray(fields)) {
      return fields;
    }
    if ("object" === typeof fields) {
      const fieldsArray = [];
      for (const key in fields) {
        fieldsArray.push(fields[key]);
      }
      return fieldsArray;
    }
  };

  const headerProps = { ...props };
  if (props.cloneId) {
    headerProps.name = props.cloneLabel ? props.cloneLabel(groupValues) : false;
  }

  return (
    <div className={fieldClasses} key={id}>
      {(headerProps.name || headerProps.collapsible) && (
        <FieldHeader
          {...headerProps}
          headerEnd={
            collapsible ? (
              <Icon
                id={displayContent ? "faChevronUp" : "faChevronDown"}
                className="ditty-field__toggle"
              />
            ) : null
          }
          onClick={toggleContent}
          style={styles}
        />
      )}
      {displayContent && fields && (
        <div className="ditty-field__input__container">
          <div className="ditty-field__input ditty-field__input--group">
            {groupFields().map((groupField, index) => {
              const groupFieldValue = groupValues[groupField.id]
                ? groupValues[groupField.id]
                : groupField.std
                ? groupField.std
                : "";

              return showField(groupField, value) ? (
                <Fragment
                  key={
                    groupField.id ? `${id}${groupField.id}` : `${id}${index}`
                  }
                >
                  {renderInput(groupField, groupFieldValue, handleUpdateValue)}
                </Fragment>
              ) : null;
            })}
          </div>
        </div>
      )}
    </div>
  );
};

export default GroupField;
