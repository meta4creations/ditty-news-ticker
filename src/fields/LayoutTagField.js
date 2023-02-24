import { __ } from "@wordpress/i18n";
import { Fragment, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faCheck,
  faCircleCheck,
  faGear,
  faPenCircle,
  faXmark,
} from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";
import { Button, ButtonGroup } from "../components";
import FieldHeader from "./FieldHeader";

const LayoutTagField = (props) => {
  const {
    id,
    fields,
    value,
    className,
    collapsible,
    defaultState = "expanded",
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
    "ditty-field--layoutTagField",
    `ditty-field-id--${id}`,
    className,
    {
      "is-disabled": value.disabled,
      "is-customized":
        (!value.disabled && Object.keys(value).length) ||
        (value.disabled && Object.keys(value).length > 1),
    }
  );

  const styles = {
    cursor: collapsible ? "pointer" : "default",
  };

  const handleUpdateValue = (inputField, updatedValue) => {
    const groupValue = typeof value === "object" ? value : {};
    groupValue[inputField.id].value = {
      customValue: true,
      value: updatedValue,
    };
    onChange(groupValue);
  };

  const toggleStatus = () => {
    const groupValue = typeof value === "object" ? value : {};
    if (groupValue.disabled) {
      delete groupValue.disabled;
    } else {
      groupValue.disabled = true;
    }
    onChange(groupValue);
  };

  const toggleAttribute = (attribute) => {
    const groupValue = typeof value === "object" ? value : {};
    const attributeValue = groupValue[attribute]
      ? { ...groupValue[attribute] }
      : {};
    if (attributeValue.customValue) {
      delete attributeValue.customValue;
    } else {
      attributeValue.customValue = true;
    }
    console.log("groupValue", groupValue);

    onChange(groupValue);
  };

  const getAttributeFields = () => {
    if ("object" === typeof fields) {
      const fieldsArray = [];
      for (const key in fields) {
        fieldsArray.push(fields[key]);
      }
      return fieldsArray.length ? fieldsArray : false;
    }
  };

  const attributeFields = getAttributeFields();

  return (
    <div className={fieldClasses} key={id}>
      <FieldHeader
        {...props}
        beforeContents={
          <FontAwesomeIcon
            className="layoutTagEnabled"
            icon={faCircleCheck}
            onClick={() => toggleStatus()}
          />
        }
        afterContents={
          attributeFields &&
          !value.disabled && (
            <ButtonGroup className="layoutTagActions" gap="3px">
              <span
                className="layoutTagAction layoutTagAction__customize"
                onClick={toggleContent}
              >
                <FontAwesomeIcon icon={faGear} />
              </span>
            </ButtonGroup>
          )
        }
        style={styles}
      />
      {displayContent && attributeFields && (
        <div className="ditty-field__input__container">
          <div className="ditty-field__input ditty-field__input--group">
            {attributeFields.map((attributeField, index) => {
              const attributeFieldValue = value[attributeField.id]
                ? value[attributeField.id].value
                  ? value[attributeField.id].value
                  : attributeField.std
                : attributeField.std
                ? attributeField.std
                : "";

              console.log("attributeFieldValue", attributeFieldValue);

              attributeField.prefix = (
                <FontAwesomeIcon
                  className="layoutAttributeCustomized"
                  icon={faPenCircle}
                  onClick={() => toggleAttribute(attributeField.id)}
                />
              );

              return (
                <Fragment
                  key={
                    attributeField.id
                      ? `${id}${attributeField.id}`
                      : `${id}${index}`
                  }
                >
                  {renderInput(
                    attributeField,
                    attributeFieldValue,
                    handleUpdateValue
                  )}
                </Fragment>
              );
            })}
          </div>
        </div>
      )}
    </div>
  );
};

export default LayoutTagField;
