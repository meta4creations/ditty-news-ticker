import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "../context";

const AdminBar = () => {
  const { title } = useContext(EditorContext);
  return (
    <div id="ditty-editor__adminbar">
      <h2>{title}</h2>
    </div>
  );
};
export default AdminBar;
