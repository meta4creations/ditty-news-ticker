import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleQuestion } from "@fortawesome/pro-solid-svg-icons";

const FieldHeader = ({
  name,
  desc,
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
        {desc && <div className="ditty-field__description">{desc}</div>}
      </div>
      {afterContents}
    </div>
  ) : (
    ""
  );
};

export default FieldHeader;
