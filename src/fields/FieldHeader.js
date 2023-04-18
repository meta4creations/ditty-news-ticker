import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";

const FieldHeader = ({
  id,
  name,
  description,
  help,
  icon,
  headerStart,
  headerEnd,
  style,
  onClick,
}) => {
  const [displayHelp, setDisplayHelp] = useState(false);

  const toggleHelp = () => {
    if (displayHelp) {
      setDisplayHelp(false);
    } else {
      setDisplayHelp(true);
    }
  };

  return name || help || icon ? (
    <div className="ditty-field__heading" onClick={onClick} style={style}>
      {headerStart}
      {icon && (
        <div className="ditty-field__icon">
          {"string" === typeof icon ? <i className={icon}></i> : icon}
        </div>
      )}
      <div className="ditty-field__heading__contents">
        <label className="ditty-field__label">
          {name ? name : id}{" "}
          {help && (
            <FontAwesomeIcon
              icon={faCircleQuestion}
              className={`ditty-field__help-icon ${displayHelp && `active`}`}
              onClick={toggleHelp}
            />
          )}
        </label>
        {help && displayHelp && <div className="ditty-field__help">{help}</div>}
        {description && (
          <div className="ditty-field__description">{description}</div>
        )}
      </div>
      {headerEnd}
    </div>
  ) : (
    ""
  );
};

export default FieldHeader;
