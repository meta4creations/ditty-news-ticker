import { __ } from "@wordpress/i18n";
import { AdminBar, Tabs } from "../components";
//import FooterBar from "./FooterBar";

import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  console.log("dittySettingsVars", dittySettingsVars);
  const fieldGroups =
    dittySettingsVars && dittySettingsVars.fields
      ? dittySettingsVars.fields
      : {};
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);

  return (
    <>
      <AdminBar title={__("Ditty Settings", "ditty-news-ticker")} />
      <div id="ditty-settings">
        <Tabs
          type="cloud"
          tabs={fieldGroups}
          currentTabId={currentTabId}
          tabClick={(tab) => setCurrentTabId(tab.id)}
          className="itemEdit__header__tabs"
        />
      </div>
      <ToastContainer />
    </>
  );
};
