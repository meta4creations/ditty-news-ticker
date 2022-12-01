import { __ } from "@wordpress/i18n";
import { useContext, useEffect } from "@wordpress/element";
import { convertBoxControlValues } from "../utils/helpers";
import { EditorContext } from "../context";
import Ditty from "./Ditty";

const Preview = () => {
  const { id, settings } = useContext(EditorContext);

  const padding = convertBoxControlValues(settings.previewPadding, {
    top: "paddingTop",
    left: "paddingLeft",
    right: "paddingRight",
    bottom: "paddingBottom",
  });

  const styles = { ...padding };
  styles.backgroundColor = settings.previewBg;

  return (
    <div id="ditty-editor__preview" style={styles}>
      <Ditty id={id} />
    </div>
  );
};
export default Preview;
