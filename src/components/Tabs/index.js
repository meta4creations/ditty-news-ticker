import { __ } from "@wordpress/i18n";
import classnames from "classnames";

const Tabs = ({ className, tabs, type, currentTabId, tabClick }) => {
  /**
   * Render a tabs class name
   * @param {object} tab
   * @returns className
   */
  const renderButtonClass = (tab) => {
    let className = "ditty-editor__tab";
    if (tab.id === currentTabId) {
      className += " ditty-editor__tab--active";
    }
    return className;
  };

  /**
   * Render a tabs content
   * @param {object} tab
   * @returns className
   */
  const renderButtonContent = (tab) => {
    return (
      <>
        <span className="ditty-editor__tab__icon">{tab.icon}</span>
        <span className="ditty-editor__tab__label">{tab.label}</span>
      </>
    );
  };

  const classes = classnames("ditty-editor__tabs", className, {
    "ditty-editor__tabs--primary": type === "primary",
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
