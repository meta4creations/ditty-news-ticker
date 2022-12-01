import { __ } from "@wordpress/i18n";

const IconBlock = ({ icon, iconSize, children }) => {
  const iconStyles = {
    width: iconSize ? iconSize : false,
    height: iconSize ? iconSize : false,
  };

  return (
    <div className="ditty-icon-block">
      <div className="ditty-icon-block__icon" style={iconStyles}>
        {icon}
      </div>
      <div className="ditty-icon-block__contents">{children}</div>
    </div>
  );
};
export default IconBlock;
