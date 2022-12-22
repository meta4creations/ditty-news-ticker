import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLoader } from "@fortawesome/pro-light-svg-icons";
import { EditorContext } from "./context";
import { Button } from "../components";

const AdminBar = () => {
  const { title, helpers, actions } = useContext(EditorContext);
  const [showSpinner, setShowSpinner] = useState(false);
  const updates = helpers.dittyUpdates();

  const onComplete = (data) => {
    setShowSpinner(false);
  };

  return (
    <div id="ditty-editor__adminbar">
      <h2>{title}</h2>
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
