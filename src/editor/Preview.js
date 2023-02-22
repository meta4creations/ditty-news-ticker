import classnames from "classnames";
import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "./context";
import Ditty from "./Ditty";

const Preview = ({ className }) => {
  const { id, settings } = useContext(EditorContext);
  const styles = { ...settings.previewPadding };
  styles.backgroundColor = settings.previewBg;

  const classes = classnames(className);

  return (
    <div id="ditty-editor__preview" className={classes} style={styles}>
      <Ditty id={id} />
    </div>
  );
};
export default Preview;
