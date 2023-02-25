import { __ } from "@wordpress/i18n";
import { Fragment, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faCircleCheck,
  faGear,
  faPenCircle,
} from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";
import { ButtonGroup } from "../components";
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

  const toggleStatus = () => {
    const groupValue = typeof value === "object" ? { ...value } : {};
    if (groupValue.disabled) {
      delete groupValue.disabled;
    } else {
      groupValue.disabled = true;
    }
    onChange(groupValue);
  };

  const handleUpdateValue = (inputField, updatedValue) => {
    const groupValue = typeof value === "object" ? { ...value } : {};
    const attributeValue = {
      customValue: true,
      value: updatedValue,
    };
    groupValue[inputField.id] = attributeValue;
    onChange(groupValue);
  };

  const attributeHasCustomValue = (attribute) => {
    const groupValue = typeof value === "object" ? { ...value } : {};
    const attributeValue = groupValue[attribute]
      ? { ...groupValue[attribute] }
      : {};
    return "undefined" === attributeValue.customValue
      ? false
      : attributeValue.customValue;
  };

  const toggleAttribute = (attribute) => {
    const groupValue = typeof value === "object" ? { ...value } : {};
    const attributeValue = groupValue[attribute]
      ? { ...groupValue[attribute] }
      : {};
    if (attributeValue.customValue) {
      delete attributeValue.customValue;
    } else {
      attributeValue.customValue = true;
    }
    groupValue[attribute] = attributeValue;
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
        headerStart={
          <FontAwesomeIcon
            className="layoutTagEnabled"
            icon={faCircleCheck}
            onClick={() => toggleStatus()}
          />
        }
        headerEnd={
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

              attributeField.className = attributeField.className
                ? `${attributeField.className} ditty-layout-attribute-field`
                : "ditty-layout-attribute-field";
              attributeField.fieldBefore = (
                <FontAwesomeIcon
                  className="layoutAttributeCustomized"
                  icon={faPenCircle}
                  onClick={() => toggleAttribute(attributeField.id)}
                />
              );

              if (attributeHasCustomValue(attributeField.id)) {
                attributeField.className +=
                  " ditty-layout-attribute-field--custom";
              } else {
                attributeField.type = "heading";
              }

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
