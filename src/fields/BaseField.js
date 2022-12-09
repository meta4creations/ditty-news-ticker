import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";
import FieldHeader from "./FieldHeader";

const BaseField = (props) => {
  const {
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
  } = props;
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
    `ditty-field-id--${id}`,
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
      <FieldHeader {...props} />
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
