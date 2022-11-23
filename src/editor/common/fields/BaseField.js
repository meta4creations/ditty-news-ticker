import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";

const BaseField = ({
  type,
  id,
  name,
  help,
  inline,
  prefix,
  suffix,
  children,
}) => {
  const [displayHelp, setDisplayHelp] = useState(false);

  const toggleHelp = () => {
    if (displayHelp) {
      setDisplayHelp(false);
    } else {
      setDisplayHelp(true);
    }
  };

  const getInputClass = (inputField) => {
    let className = `ditty-field__input ditty-field__input--${type}`;
    if (inline) {
      className += " ditty-field__input--inline";
    }
    return className;
  };

  return (
    <div
      className={`ditty-field ditty-field--${type} ${
        help && displayHelp ? "ditty-field--help" : ""
      }`}
      key={id}
    >
      <div className="ditty-field__heading">
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
        {help && displayHelp && <div className="ditty-field__help">{help}</div>}
      </div>
      <div className="ditty-field__input__container">
        {prefix && <div className="ditty-field__input__prefix">{prefix}</div>}
        <div className={getInputClass()}>{children}</div>
        {suffix && <div className="ditty-field__input__suffix">{suffix}</div>}
      </div>
    </div>
  );
};

export default BaseField;
