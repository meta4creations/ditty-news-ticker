import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faCircleQuestion,
  faChevronDown,
  faChevronUp,
} from "@fortawesome/pro-solid-svg-icons";

const GroupField = ({ id, name, desc, help, children }) => {
  const [displayHelp, setDisplayHelp] = useState(false);
  const [displayContent, setDisplayContent] = useState(false);

  const toggleHelp = () => {
    setDisplayHelp(!displayHelp);
  };

  const toggleContent = (e) => {
    if (
      !e.target.classList.contains("ditty-field__help-icon") &&
      !e.target.parentElement.classList.contains("ditty-field__help-icon")
    ) {
      setDisplayContent(!displayContent);
    }
  };

  return (
    <div
      className={`ditty-field ditty-field--group ${
        help && displayHelp ? "ditty-field--help" : ""
      }`}
      key={id}
    >
      {(name || help) && (
        <div className="ditty-field__heading" onClick={toggleContent}>
          <div className="ditty-field__heading__contents">
            <label className="ditty-field__label">
              {name}{" "}
              {help && (
                <FontAwesomeIcon
                  className="ditty-field__help-icon"
                  icon={faCircleQuestion}
                  onClick={toggleHelp}
                />
              )}
            </label>
            {help && displayHelp && (
              <div className="ditty-field__help">{help}</div>
            )}
            {desc && <div className="ditty-field__description">{desc}</div>}
          </div>
          <FontAwesomeIcon
            className="ditty-field__toggle"
            icon={displayContent ? faChevronUp : faChevronDown}
          />
        </div>
      )}
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
