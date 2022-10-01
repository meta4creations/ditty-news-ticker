import { __ } from "@wordpress/i18n";

const Tabs = ({ currentTab, onTabClick }) => {
  const tabs = [
    {
      id: "items",
      label: __("Items", "ditty-news-ticker"),
      icon: "fas fa-stream",
    },
    {
      id: "display",
      label: __("Display", "ditty-news-ticker"),
      icon: "fas fa-tablet-alt",
    },
    {
      id: "settings",
      label: __("Settings", "ditty-news-ticker"),
      icon: "fas fa-cog",
    },
  ];

  const selectedTabs = tabs.filter((tab) => tab.id === currentTab);
  const selectedTab = selectedTabs.length ? selectedTabs[0] : tabs[0];

  function renderButtonClass(tab) {
    let className = "ditty-editor__tab";
    if (tab === selectedTab) {
      className += " ditty-editor__tab--active";
    }
    return className;
  }

  return (
    <div className="ditty-editor__tabs">
      {tabs.map((tab) => {
        return (
          <button
            className={renderButtonClass(tab)}
            key={tab.id}
            onClick={() => onTabClick(tab.id)}
          >
            <i className={tab.icon}></i>
            <span>{tab.label}</span>
          </button>
        );
      })}
    </div>
  );
};
export default Tabs;
