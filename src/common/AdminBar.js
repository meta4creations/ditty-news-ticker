import _ from "lodash";
const { __ } = wp.i18n;
const { useState } = wp.element;
import { Button, Icon } from "../components";
import { TextField, TextareaField } from "../fields";
import { ReactComponent as Logo } from "../assets/img/d.svg";

const AdminBar = ({
  logo = <Logo style={{ height: "30px" }} />,
  title,
  description,
  status,
  buttonLabel = __("Save", "ditty-news-ticker"),
  buttonDisabled,
  hasUpdates,
  showSpinner,
  onUpdateTitle,
  onUpdateDescription,
  onUpdateStatus,
  onSubmit,
}) => {
  const [editTitle, setEditTitle] = useState(false);
  const [editDescription, setEditDescription] = useState(false);

  const renderButtons = () => {
    return "buttons";
  };

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
              <>
                <h2
                  id="ditty-adminbar__title__element"
                  onClick={() => onUpdateTitle && setEditTitle(true)}
                >
                  {title}
                </h2>
                {status && (
                  <span
                    className={`ditty-adminbar__status ditty-adminbar__status--${status}`}
                    onClick={() => {
                      const updatedStatus =
                        status == "publish" ? "draft" : "publish";
                      onUpdateStatus(updatedStatus);
                    }}
                  >
                    {status == "publish"
                      ? __("Active", "ditty-news-ticker")
                      : __("Disabled", "ditty-news-ticker")}
                  </span>
                )}
              </>
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
        className={!buttonDisabled && hasUpdates ? "ditty-has-updates" : null}
        disabled={buttonDisabled ? "disabled" : false}
        onClick={() => {
          onSubmit && onSubmit();
        }}
      >
        {showSpinner && <Icon id="loader" spin />}
        <span>{buttonLabel}</span>
      </Button>
    </div>
  );
};
export default AdminBar;
