import { __ } from "@wordpress/i18n";
import { Fragment, useState } from "@wordpress/element";
import classnames from "classnames";
import { ButtonGroup, Icon } from "../components";
import FieldHeader from "./FieldHeader";

const LayoutTagField = (props) => {
  const { id, fields, value, className, onChange, renderInput } = props;
  const [displayContent, setDisplayContent] = useState(false);

  const toggleContent = () => {
    setDisplayContent(!displayContent);
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

  const handleUpdateValue = (attribute, updatedValue) => {
    const groupValue = typeof value === "object" ? { ...value } : {};
    const attributeValue = {
      customValue: true,
      value: updatedValue,
    };
    groupValue[attribute.id] = attributeValue;
    onChange(groupValue);
  };

  const attributeHasCustomValue = (attribute) => {
    const groupValue = typeof value === "object" ? { ...value } : {};
    const attributeValue = groupValue[attribute.id]
      ? { ...groupValue[attribute.id] }
      : {};
    return "undefined" === attributeValue.customValue
      ? false
      : attributeValue.customValue;
  };

  const getAttributeValue = (attribute) => {
    return value[attribute.id]
      ? value[attribute.id].value
        ? value[attribute.id].value
        : attribute.std
      : attribute.std
      ? attribute.std
      : "";
  };

  const toggleAttribute = (attribute) => {
    const groupValue = typeof value === "object" ? { ...value } : {};
    const attributeValue = groupValue[attribute.id]
      ? { ...groupValue[attribute.id] }
      : {};
    const attributeDefault = attribute.std ? attribute.std : false;
    if (attributeValue.customValue) {
      delete attributeValue.customValue;
    } else {
      attributeValue.customValue = true;
      attributeValue.value = attributeValue.value
        ? attributeValue.value
        : attributeDefault;
    }
    groupValue[attribute.id] = attributeValue;
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

  const tagHasCustomValues = () => {
    let hasCustomization = false;
    for (const attribute in value) {
      if (
        typeof value[attribute] === "object" &&
        value[attribute].customValue
      ) {
        hasCustomization = true;
        break;
      }
    }
    if (value.disabled) {
      hasCustomization = false;
    }
    return hasCustomization;
  };

  const fieldClasses = classnames(
    "ditty-field",
    "ditty-field--layoutTagField",
    `ditty-field-id--${id}`,
    className,
    {
      "is-disabled": value.disabled,
      "is-customized": tagHasCustomValues(),
      "is-open": displayContent,
    }
  );

  return (
    <div className={fieldClasses} key={id}>
      <FieldHeader
        {...props}
        headerStart={
          <Icon
            id="faCircleCheck"
            className="layoutTagEnabled"
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
                <Icon id="faGear" />
              </span>
            </ButtonGroup>
          )
        }
      />
      {displayContent && attributeFields && !value.disabled && (
        <div className="ditty-field__input__container">
          <div className="ditty-field__input ditty-field__input--group">
            {attributeFields.map((attributeField, index) => {
              const attributeFieldValue = getAttributeValue(attributeField);

              attributeField.className = attributeField.className
                ? `${attributeField.className} ditty-layout-attribute-field`
                : "ditty-layout-attribute-field";
              attributeField.fieldBefore = (
                <Icon
                  id="faPenCircle"
                  className="layoutAttributeCustomized"
                  onClick={() => toggleAttribute(attributeField)}
                />
              );

              if (attributeHasCustomValue(attributeField)) {
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
