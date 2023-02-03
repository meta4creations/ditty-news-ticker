import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "./context";
import Ditty from "./Ditty";

const Preview = () => {
  const { id, settings } = useContext(EditorContext);
  const styles = { ...settings.previewPadding };
  styles.backgroundColor = settings.previewBg;

  return (
    <div id="ditty-editor__preview" style={styles}>
      <Ditty id={id} />
    </div>
  );
};
export default Preview;
