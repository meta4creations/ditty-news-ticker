import { __ } from "@wordpress/i18n";
import classnames from "classnames";

const Tabs = ({ className, tabs, type, currentTabId, tabClick }) => {
  /**
   * Render a tabs class name
   * @param {object} tab
   * @returns buttonClasses
   */
  const renderButtonClass = (tab) => {
    const buttonClasses = classnames("ditty-editor__tab", {
      "ditty-editor__tab--active": tab.id === currentTabId,
    });
    return buttonClasses;
  };

  /**
   * Render a tabs content
   * @param {object} tab
   * @returns className
   */
  const renderButtonContent = (tab) => {
    return (
      <>
        {tab.icon && (
          <span className="ditty-editor__tab__icon">{tab.icon}</span>
        )}
        {tab.label && (
          <span className="ditty-editor__tab__label">{tab.label}</span>
        )}
      </>
    );
  };

  const classes = classnames("ditty-editor__tabs", className, {
    "ditty-editor__tabs--primary": type === "primary",
    "ditty-editor__tabs--secondary": type === "secondary",
    "ditty-editor__tabs--cloud": type === "cloud",
  });

  /**
   * Return the tabs
   */
  return (
    <div className={classes}>
      {tabs.map((tab) => {
        return (
          <button
            className={renderButtonClass(tab)}
            key={tab.id}
            onClick={() => tabClick(tab)}
          >
            {renderButtonContent(tab)}
          </button>
        );
      })}
    </div>
  );
};
export default Tabs;
