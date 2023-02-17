import { __ } from "@wordpress/i18n";
import { Fragment, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheck, faGear, faXmark } from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";
import { Button, ButtonGroup } from "../components";
import FieldHeader from "./FieldHeader";

const LayoutAttributeField = (props) => {
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
    "ditty-field--layoutAttributeField",
    `ditty-field-id--${id}`,
    className
  );

  const styles = {
    cursor: collapsible ? "pointer" : "default",
  };

  const handleUpdateValue = (inputField, updatedValue) => {
    const groupValue = typeof value === "object" ? value : {};
    groupValue[inputField.id] = updatedValue;
    onChange(groupValue);
  };

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

  return (
    <div className={fieldClasses} key={id}>
      <FieldHeader
        {...props}
        afterContents={
          <ButtonGroup className="layoutAttributeActions">
            <span
              className="layoutAttributeAction layoutAttributeAction__enable"
              onClick={() => console.log("enable")}
            >
              <FontAwesomeIcon icon={faCheck} />
            </span>
            <span
              className="layoutAttributeAction layoutAttributeAction__disable"
              onClick={() => console.log("disable")}
            >
              <FontAwesomeIcon icon={faXmark} />
            </span>
            <span
              className="layoutAttributeAction layoutAttributeAction__customize"
              onClick={toggleContent}
            >
              <FontAwesomeIcon icon={faGear} />
            </span>
          </ButtonGroup>
        }
        style={styles}
      />
      {displayContent && fields && (
        <div className="ditty-field__input__container">
          <div className="ditty-field__input ditty-field__input--group">
            {groupFields().map((groupField, index) => {
              const groupFieldValue = value[groupField.id]
                ? value[groupField.id]
                : groupField.std
                ? groupField.std
                : "";

              return (
                <Fragment
                  key={
                    groupField.id ? `${id}${groupField.id}` : `${id}${index}`
                  }
                >
                  {renderInput(groupField, groupFieldValue, handleUpdateValue)}
                </Fragment>
              );
            })}
          </div>
        </div>
      )}
    </div>
  );
};

export default LayoutAttributeField;
