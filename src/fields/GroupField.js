import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faChevronUp } from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";
import FieldHeader from "./FieldHeader";

const GroupField = (props) => {
  const { id, className, children } = props;
  const [displayContent, setDisplayContent] = useState(false);

  const toggleContent = (e) => {
    if (
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
    className,
    {
      "ditty-field--help": displayHelp,
    }
  );

  return (
    <div className={fieldClasses} key={id}>
      <FieldHeader
        {...props}
        afterContents={
          <FontAwesomeIcon
            className="ditty-field__toggle"
            icon={displayContent ? faChevronUp : faChevronDown}
          />
        }
        onClick={toggleContent}
      />
      {displayContent && children && (
        <div className="ditty-field__input__container">
          <div className="ditty-field__input ditty-field__input--group">
            {children}
          </div>
        </div>
      )}
    </div>
  );
};

export default GroupField;
