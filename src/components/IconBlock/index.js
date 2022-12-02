import classnames from "classnames";

const IconBlock = ({
  align,
  justify,
  icon,
  iconSize,
  className,
  style,
  children,
}) => {
  const styles = {
    alignItems: align ? align : false,
    justifyContent: justify ? justify : false,
    ...style,
  };
  const iconStyles = {
    width: iconSize ? iconSize : false,
    height: iconSize ? iconSize : false,
  };

  const classes = classnames("ditty-icon-block", className);

  return (
    <div className={classes} style={styles}>
      {icon && (
        <div className="ditty-icon-block__icon" style={iconStyles}>
          {icon}
        </div>
      )}
      <div className="ditty-icon-block__contents">{children}</div>
    </div>
  );
};
export default IconBlock;
