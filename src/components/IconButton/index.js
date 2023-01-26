import { __ } from "@wordpress/i18n";

const IconButton = ({ icon, label, active, onButtonClick }) => {
  const renderButtonClass = () => {
    const className = active
      ? "ditty-icon-button ditty-icon-button--active"
      : "ditty-icon-button";
    return className;
  };

  return (
    <button className={renderButtonClass()} onClick={onButtonClick}>
      <span className="ditty-icon-button__icon">{icon}</span>
      <span className="ditty-icon-button__label">{label}</span>
    </button>
  );
};
export default IconButton;
