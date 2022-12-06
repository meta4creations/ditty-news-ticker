import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";

const BaseField = ({
  type,
  id,
  name,
  desc,
  help,
  icon,
  inline,
  prefix,
  suffix,
  className,
  style,
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

  const fieldClasses = classnames(
    "ditty-field",
    `ditty-field--${type}`,
    className,
    {
      "ditty-field--help": displayHelp,
    }
  );

  const inputClasses = classnames(
    "ditty-field__input",
    `ditty-field__input--${type}`,
    {
      "ditty-field__input--inline": inline,
    }
  );

  return (
    <div className={fieldClasses} key={id} style={style}>
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
          <div className={inputClasses}>{children}</div>
          {suffix && <div className="ditty-field__input__suffix">{suffix}</div>}
        </div>
      )}
    </div>
  );
};

export default BaseField;
