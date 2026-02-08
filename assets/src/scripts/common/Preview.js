import classnames from "classnames";
const { __ } = wp.i18n;
import { getDisplayObject } from "../utils/displayTypes";
import Ditty from "./Ditty";

const Preview = ({
  id,
  title,
  display,
  displays,
  displayItems,
  className,
  styles,
}) => {
  const classes = classnames(className);
  const defaultDisplayType = dittyEditorVars.defaultDisplayType
    ? dittyEditorVars.defaultDisplayType
    : "list";
  const currentDisplay = display ? display : defaultDisplayType;
  const displayObject = getDisplayObject(currentDisplay, displays);

  return (
    <div id="ditty-editor__preview" className={classes}>
      <div id="ditty-editor__preview__contents" style={styles}>
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
