import { __ } from "@wordpress/i18n";

const IconBlock = ({ icon, children }) => {
  return (
    <div className="ditty-icon-block">
      <div className="ditty-icon-block__icon">{icon}</div>
      <div className="ditty-icon-block__contents">{children}</div>
    </div>
  );
};
export default IconBlock;
