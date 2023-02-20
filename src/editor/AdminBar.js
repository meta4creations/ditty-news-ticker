import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLoader } from "@fortawesome/pro-light-svg-icons";
import { EditorContext } from "./context";
import { Button } from "../components";
import { TextField } from "../fields";

const AdminBar = () => {
  const { id, title, helpers, actions } = useContext(EditorContext);
  const [editTitle, setEditTitle] = useState(false);
  const [showSpinner, setShowSpinner] = useState(false);
  const updates = helpers.dittyUpdates();

  const onComplete = (data) => {
    setShowSpinner(false);
  };

  return (
    <div id="ditty-editor__adminbar">
      <div id="ditty-editor__adminbar__title">
        {editTitle ? (
          <TextField
            value={title}
            onChange={(updatedValue) => actions.updateTitle(updatedValue)}
            onBlur={() => {
              setEditTitle(false);
              if ("" === title) {
                actions.updateTitle(__(`Ditty ${id}`, "ditty-news-ticker"));
              }
            }}
            setFocus={true}
          />
        ) : (
          <h2 onClick={() => setEditTitle(true)}>{title}</h2>
        )}
      </div>

      <Button
        className={
          Object.keys(updates).length !== 0 ? "ditty-has-updates" : null
        }
        onClick={() => {
          setShowSpinner(true);
          actions.saveDitty(onComplete);
        }}
      >
        {showSpinner && <FontAwesomeIcon icon={faLoader} className="fa-spin" />}
        <span>{__("Save", "ditty-news-ticker")}</span>
      </Button>
    </div>
  );
};
export default AdminBar;
