import classnames from "classnames";
import _ from "lodash";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { fal } from "@fortawesome/pro-light-svg-icons";
import { fas } from "@fortawesome/pro-solid-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";
library.add(fal, fas, fab);

const Icon = ({ id, type = "fal", spin, className, style, onClick }) => {
  const classes = classnames("ditty-icon", className);
  const atts = {
    className: classes,
    style,
    spin,
    onClick,
  };

  return id && <FontAwesomeIcon {...atts} icon={[type, _.kebabCase(id)]} />;
};
export default Icon;
