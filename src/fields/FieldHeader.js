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
  beforeContents,
  afterContents,
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
      {icon && <div className="ditty-field__icon">{icon}</div>}
      {beforeContents}
      <div className="ditty-field__heading__contents">
        <label className="ditty-field__label">
          {name ? name : id}{" "}
          {help && (
            <FontAwesomeIcon
              className="ditty-field__help-icon"
              icon={faCircleQuestion}
              onClick={toggleHelp}
            />
          )}
        </label>
        {help && displayHelp && <div className="ditty-field__help">{help}</div>}
        {description && (
          <div className="ditty-field__description">{description}</div>
        )}
      </div>
      {afterContents}
    </div>
  ) : (
    ""
  );
};

export default FieldHeader;
