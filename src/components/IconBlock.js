import classnames from "classnames";
import Icon from "./Icon";

const IconBlock = ({
  align,
  justify,
  icon,
  iconSize,
  className,
  style,
  iconStyle,
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
    ...iconStyle,
  };

  const classes = classnames("ditty-icon-block", className);

  return (
    <div className={classes} style={styles}>
      {icon && (
        <div className="ditty-icon-block__icon" style={iconStyles}>
          {"string" === typeof icon ? <Icon id={icon} /> : icon}
        </div>
      )}
      <div className="ditty-icon-block__contents">{children}</div>
    </div>
  );
};
export default IconBlock;
