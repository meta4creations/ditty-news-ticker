import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "../context";

const AdminBar = () => {
  const { title, helpers, actions } = useContext(EditorContext);

  const getButtonClass = () => {
    let className = "ditty-button";
    const updates = helpers.dittyUpdates();
    if (Object.keys(updates).length !== 0) {
      className += " ditty-has-updates";
    }
    return className;
  };

  return (
    <div id="ditty-editor__adminbar">
      <h2>{title}</h2>
      <button className={getButtonClass()} onClick={actions.saveDitty}>
        {__("Save", "ditty-news-ticker")}
      </button>
    </div>
  );
};
export default AdminBar;
