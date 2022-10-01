import { __ } from "@wordpress/i18n";
import { useEffect, useState } from "@wordpress/element";
import Tabs from "./tabs";
import Panels from "./panels";

export default ({ id, title, items }) => {
  const [currentTab, setCurrentTab] = useState("");

  function handleTabClick(tab) {
    setCurrentTab(tab);
  }

  return (
    <div className="ditty-editor__contents">
      <Tabs currentTab={currentTab} onTabClick={handleTabClick} />
      <Panels currentPanel={currentTab} items={items} />
    </div>
  );
};
