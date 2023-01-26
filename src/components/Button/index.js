import classnames from "classnames";

const Button = ({
  children,
  className,
  disabled,
  icon,
  type,
  isPressed,
  isBusy,
  onClick,
}) => {
  const classes = classnames("ditty-button", className, {
    "is-secondary": type === "secondary",
    "is-primary": type === "primary",
    "is-tertiary": type === "tertiary",
    "is-pressed": isPressed,
    "is-busy": isBusy,
    "has-icon": !!icon,
  });

  return (
    <button
      className={classes}
      onClick={onClick}
      disabled={disabled ? "disabled" : false}
    >
      {children}
    </button>
  );
};
export default Button;
