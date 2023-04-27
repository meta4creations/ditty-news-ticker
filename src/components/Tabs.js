import { __ } from "@wordpress/i18n";
import classnames from "classnames";

const Tabs = ({ className, tabs, type, currentTabId, tabClick, style }) => {
  /**
   * Render a tabs class name
   * @param {object} tab
   * @returns buttonClasses
   */
  const renderButtonClass = (tab) => {
    const buttonClasses = classnames("ditty-tab", `ditty-tab--${tab.id}`, {
      "ditty-tab--active": tab.id === currentTabId,
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
          <span className="ditty-tab__icon">
            {"string" === typeof tab.icon ? (
              <i className={tab.icon}></i>
            ) : (
              tab.icon
            )}
          </span>
        )}
        {tab.label && <span className="ditty-tab__label">{tab.label}</span>}
      </>
    );
  };

  const classes = classnames("ditty-tabs", className, {
    "ditty-tabs--primary": type === "primary",
    "ditty-tabs--secondary": type === "secondary",
    "ditty-tabs--cloud": type === "cloud",
    "ditty-tabs--list": type === "list",
  });

  /**
   * Return the tabs
   */
  return (
    <div className={classes} style={style}>
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
