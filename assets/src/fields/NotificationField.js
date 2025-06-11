const { __ } = wp.i18n;
import classnames from "classnames";
import BaseField from "./BaseField";

const NotificationField = (props) => {
  const { value, kind, className } = props;

  const classes = classnames(className, {
    "is-primary": kind === "primary",
    "is-secondary": kind === "secondary",
    "is-success": kind === "success",
    "is-danger": kind === "danger",
    "is-warning": kind === "warning",
    "is-info": kind === "info",
    "is-light": kind === "light",
    "is-dark": kind === "dark",
  });

  return (
    <BaseField {...props} inputClassName={classes} type="notification">
      {value}
    </BaseField>
  );
};

export default NotificationField;
