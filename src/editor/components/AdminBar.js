import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "../context";

const AdminBar = () => {
  const { title, actions } = useContext(EditorContext);
  return (
    <div id="ditty-editor__adminbar">
      <h2>{title}</h2>
      <button className="ditty-button" onClick={actions.saveDitty}>
        {__("Save", "ditty-news-ticker")}
      </button>
    </div>
  );
};
export default AdminBar;
