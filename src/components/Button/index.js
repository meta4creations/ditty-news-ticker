import classnames from "classnames";

const Button = ({
  children,
  className,
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
    <button className={classes} onClick={onClick}>
      {children}
    </button>
  );
};
export default Button;
