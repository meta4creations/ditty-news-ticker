import classnames from "classnames";

const ButtonGroup = ({
  direction,
  align,
  justify,
  gap,
  children,
  className,
  type,
}) => {
  const classes = classnames("ditty-button-group", className, {
    "is-tabbed": type === "tabbed",
  });

  const styles = {
    flexDirection: direction ? direction : false,
    alignItems: align ? align : false,
    justifyContent: justify ? justify : false,
    gap: gap ? gap : false,
  };

  return (
    <div className={classes} style={styles}>
      {children}
    </div>
  );
};
export default ButtonGroup;
