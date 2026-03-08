import classnames from "classnames";
const { __ } = wp.i18n;
const { useContext } = wp.element;
import { getDisplayObject } from "../utils/displayTypes";
import Ditty from "./Ditty";
import PreviewIframe from "./PreviewIframe";
import { EditorContext } from "../editor/context";

const Preview = ({
  id,
  title,
  display,
  displays,
  displayItems,
  className,
  styles,
}) => {
  const editor = useContext(EditorContext);
  const { previewVersion, settings } = editor || {};
  
  const classes = classnames(className);
  const defaultDisplayType = dittyEditorVars.defaultDisplayType
    ? dittyEditorVars.defaultDisplayType
    : "list";
  const currentDisplay = display ? display : defaultDisplayType;
  const displayObject = getDisplayObject(currentDisplay, displays);

  // Get Ditty version from settings (controls both editor preview and frontend)
  const dittyVersion = settings?.dittyVersion || settings?.previewMode || "v4";
  const previewRenderMode = settings?.previewRenderMode || "endpoint";

  const getPreviewStyles = () => {
    const previewStyles = styles || {};
    return previewStyles;
  };

  // Render v4 preview (iframe-based)
  if (dittyVersion === "v4") {
    return (
      <div id="ditty-editor__preview" className={classes}>
        <div id="ditty-editor__preview__contents" style={getPreviewStyles()}>
          <PreviewIframe
            mode={previewRenderMode}
            dittyId={id}
            title={title}
            display={displayObject}
            displayItems={displayItems}
            styles={getPreviewStyles()}
            previewVersion={previewVersion}
            debounceDelay={300}
          />
        </div>
      </div>
    );
  }

  // Render v3 preview (legacy direct DOM)
  return (
    <div id="ditty-editor__preview" className={classes}>
      <div id="ditty-editor__preview__contents" style={getPreviewStyles()}>
        {id && (
          <Ditty
            id={id}
            title={title}
            display={displayObject}
            displayItems={displayItems}
          />
        )}
      </div>
    </div>
  );
};

export default Preview;
