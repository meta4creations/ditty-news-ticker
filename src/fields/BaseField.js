import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";

const BaseField = (props) => {
  const { type, id, name, desc, help, icon, inline, prefix, suffix, children } =
    props;
  const [displayHelp, setDisplayHelp] = useState(false);

  const toggleHelp = () => {
    if (displayHelp) {
      setDisplayHelp(false);
    } else {
      setDisplayHelp(true);
    }
  };

  const getFieldClass = (inputField) => {
    let className = `ditty-field ditty-field--${type} ${
      help && displayHelp ? "ditty-field--help" : ""
    }`;
    if (props.class) {
      className += ` ${props.class}`;
    }
    return className;
  };

  const getInputClass = (inputField) => {
    let className = `ditty-field__input ditty-field__input--${type}`;
    if (inline) {
      className += " ditty-field__input--inline";
    }
    return className;
  };

  return (
    <div className={getFieldClass()} key={id}>
      {(name || help || icon) && (
        <div className="ditty-field__heading">
          {icon && <div className="ditty-field__icon">{icon}</div>}
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
        </div>
      )}
      {children && (
        <div className="ditty-field__input__container">
          {prefix && <div className="ditty-field__input__prefix">{prefix}</div>}
          <div className={getInputClass()}>{children}</div>
          {suffix && <div className="ditty-field__input__suffix">{suffix}</div>}
        </div>
      )}
    </div>
  );
};

export default BaseField;
