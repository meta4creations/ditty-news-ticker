import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faCircleQuestion,
  faChevronDown,
  faChevronUp,
} from "@fortawesome/pro-solid-svg-icons";
import classnames from "classnames";

const CloneField = (props) => {
  const { name, desc, help, className, children } = props;

  const fieldClasses = classnames(
    "ditty-field",
    "ditty-field--clone",
    className
  );

  return <div className={fieldClasses}>{children}</div>;
};

export default CloneField;
