import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import FieldHeader from "./FieldHeader";

const BaseField = (props) => {
  const {
    type,
    id,
    inline,
    fieldBefore,
    fieldAfter,
    prefix,
    suffix,
    className,
    style,
    children,
    hideHeader,
    raw,
  } = props;
  const fieldClasses = classnames(
    "ditty-field",
    `ditty-field--${type}`,
    `ditty-field-id--${id}`,
    className
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
      {raw ? (
        children
      ) : (
        <>
          {fieldBefore && (
            <div className="ditty-field__before">{fieldBefore}</div>
          )}
          <div className="ditty-field__contents">
            {!hideHeader && <FieldHeader {...props} />}
            {children && (
              <div className="ditty-field__input__container">
                {prefix && (
                  <div className="ditty-field__input__prefix">{prefix}</div>
                )}
                <div className={inputClasses}>{children}</div>
                {suffix && (
                  <div className="ditty-field__input__suffix">{suffix}</div>
                )}
              </div>
            )}
          </div>
          {fieldAfter && <div className="ditty-field__after">{fieldAfter}</div>}
        </>
      )}
    </div>
  );
};

export default BaseField;
