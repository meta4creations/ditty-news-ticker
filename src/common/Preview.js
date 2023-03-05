import classnames from "classnames";
import { __ } from "@wordpress/i18n";
import Ditty from "./Ditty";

const Preview = ({ dittyId, className, styles }) => {
  const classes = classnames(className);

  return (
    <div id="ditty-editor__preview" className={classes} style={styles}>
      {dittyId && <Ditty id={dittyId} />}
    </div>
  );
};
export default Preview;
