import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLoader } from "@fortawesome/pro-light-svg-icons";
import { Button } from "../components";
import { TextField } from "../fields";

const AdminBar = ({
  title,
  buttonLabel = __("Save", "ditty-news-ticker"),
  hasUpdates,
  showSpinner,
  onUpdateTitle,
  onSubmit,
}) => {
  const [editTitle, setEditTitle] = useState(false);

  return (
    <div id="ditty-editor__adminbar">
      <div id="ditty-editor__adminbar__title">
        {editTitle ? (
          <TextField
            value={title}
            onChange={(updatedValue) =>
              onUpdateTitle && onUpdateTitle(updatedValue)
            }
            onBlur={() => {
              setEditTitle(false);
              if ("" === title && onUpdateTitle) {
                onUpdateTitle(title);
              }
            }}
            setFocus={true}
          />
        ) : (
          <h2 onClick={() => onUpdateTitle && setEditTitle(true)}>{title}</h2>
        )}
      </div>

      <Button
        className={hasUpdates ? "ditty-has-updates" : null}
        onClick={() => {
          onSubmit && onSubmit();
        }}
      >
        {showSpinner && <FontAwesomeIcon icon={faLoader} className="fa-spin" />}
        <span>{buttonLabel}</span>
      </Button>
    </div>
  );
};
export default AdminBar;
