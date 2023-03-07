import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLoader } from "@fortawesome/pro-light-svg-icons";
import { Button } from "../components";
import { TextField, TextareaField } from "../fields";

const AdminBar = ({
  logo,
  title,
  description,
  buttonLabel = __("Save", "ditty-news-ticker"),
  hasUpdates,
  showSpinner,
  onUpdateTitle,
  onUpdateDescription,
  onSubmit,
}) => {
  const [editTitle, setEditTitle] = useState(false);
  const [editDescription, setEditDescription] = useState(false);

  return (
    <div id="ditty-adminbar">
      {logo && <div id="ditty-adminbar__logo">{logo}</div>}
      <div id="ditty-adminbar__contents">
        {!editDescription && (
          <div id="ditty-adminbar__title">
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
              <h2 onClick={() => onUpdateTitle && setEditTitle(true)}>
                {title}
              </h2>
            )}
          </div>
        )}
        {editDescription && (
          <TextareaField
            value={description}
            onChange={(updatedValue) =>
              onUpdateDescription && onUpdateDescription(updatedValue)
            }
            onBlur={() => {
              console.log("on blur");
              setEditDescription(false);
            }}
            setFocus={true}
          />
        )}
        {description && !editDescription && !editTitle && (
          <div
            id="ditty-adminbar__description"
            onClick={() => onUpdateDescription && setEditDescription(true)}
          >
            {description}
          </div>
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
