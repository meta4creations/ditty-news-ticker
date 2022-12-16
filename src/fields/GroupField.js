import { __ } from "@wordpress/i18n";
import { Fragment, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faChevronUp } from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";
import FieldHeader from "./FieldHeader";

const GroupField = (props) => {
  const {
    id,
    fields,
    value,
    className,
    collapsible,
    defaultState = "expanded",
    multipleFields,
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
      groupValue = value.map((v) => {
        return {
          id: v.id,
          value: v.id === inputField.id ? updatedValue : v.value,
        };
      });
    } else {
      groupValue = typeof value === "object" ? value : {};
      groupValue[inputField.id] = updatedValue;
    }
    onChange(groupValue);
  };

  const groupValues = multipleFields ? {} : value;
  if (multipleFields && Array.isArray(value)) {
    value.map((v) => (groupValues[v.id] = v.value));
  }

  return (
    <div className={fieldClasses} key={id}>
      <FieldHeader
        {...props}
        afterContents={
          collapsible ? (
            <FontAwesomeIcon
              className="ditty-field__toggle"
              icon={displayContent ? faChevronUp : faChevronDown}
            />
          ) : null
        }
        onClick={toggleContent}
        style={styles}
      />
      {displayContent && fields && (
        <div className="ditty-field__input__container">
          <div className="ditty-field__input ditty-field__input--group">
            {fields.map((groupField, index) => {
              const groupFieldValue = groupValues[groupField.id]
                ? groupValues[groupField.id]
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

export default GroupField;
