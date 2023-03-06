import classnames from "classnames";
import { __ } from "@wordpress/i18n";
import Ditty from "./Ditty";

const Preview = ({ id, title, display, displayItems, className, styles }) => {
  const classes = classnames(className);

  return (
    <div id="ditty-editor__preview" className={classes} style={styles}>
      {id && (
        <Ditty
          id={id}
          title={title}
          display={display}
          displayItems={displayItems}
        />
      )}
    </div>
  );
};
export default Preview;
