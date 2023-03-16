import classnames from "classnames";
import { __ } from "@wordpress/i18n";
import Ditty from "./Ditty";

const Preview = ({ id, title, display, displayItems, className, styles }) => {
  const classes = classnames(className);

  return (
    <div id="ditty-editor__preview" className={classes}>
      <div id="ditty-editor__preview__contents" style={styles}>
        {id && (
          <Ditty
            id={id}
            title={title}
            display={display}
            displayItems={displayItems}
          />
        )}
      </div>
    </div>
  );
};
export default Preview;
