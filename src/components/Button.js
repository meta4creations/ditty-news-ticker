import classnames from "classnames";

const Button = ({
  children,
  className,
  disabled,
  icon,
  type,
  size,
  style,
  isPressed,
  isBusy,
  onClick,
}) => {
  const classes = classnames("ditty-button", className, {
    "is-secondary": type === "secondary",
    "is-primary": type === "primary",
    "is-tertiary": type === "tertiary",
    "is-small": size === "small",
    "is-pressed": isPressed,
    "is-busy": isBusy,
    "has-icon": !!icon,
  });

  return (
    <button
      className={classes}
      style={style && style}
      onClick={onClick}
      disabled={disabled ? "disabled" : false}
    >
      {children}
    </button>
  );
};
export default Button;
