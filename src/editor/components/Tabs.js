import { __ } from "@wordpress/i18n";

const Tabs = ({ tabs, type, currentTabId, tabClick }) => {
  /**
   * Render the tabs container class name
   * @returns className
   */
  const renderTabsClass = () => {
    let className = "ditty-editor__tabs";
    if (type && "" !== type) {
      className += ` ditty-editor__tabs--${type}`;
    }
    return className;
  };

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
    if (tab.icon) {
      return tab.id === currentTabId ? (
        <span className="ditty-editor__tab__label">{tab.label}</span>
      ) : (
        <span className="ditty-editor__tab__icon">{tab.icon}</span>
      );
    } else {
      return <span className="ditty-editor__tab__label">{tab.label}</span>;
    }
  };

  /**
   * Return the tabs
   */
  return (
    <div className={renderTabsClass()}>
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
