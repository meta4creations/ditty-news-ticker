import { __ } from "@wordpress/i18n";
import classnames from "classnames";

const FieldList = ({ className, children }) => {
  const classes = classnames("ditty-field-list", className);
  return <div className={classes}>{children}</div>;
};

export default FieldList;
