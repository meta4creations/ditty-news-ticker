import _ from "lodash";
import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import { AdminBar, FooterBar } from "../common";
import { Tabs } from "../components";
import { FieldList } from "../fields";
import { saveSettings } from "../services/httpService";
import { ReactComponent as Logo } from "../assets/img/d.svg";

import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  const params = new URLSearchParams(location.search);

  const fieldGroups =
    dittySettingsVars && dittySettingsVars.fields
      ? dittySettingsVars.fields
      : {};

  const tabParam =
    params.get("tab") &&
    fieldGroups.reduce((id, group) => {
      return String(group.id) === params.get("tab") ? group.id : id;
    }, null);

  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(
    tabParam ? tabParam : initialTab
  );

  const [initSettings, setInitSettings] = useState(
    dittySettingsVars && dittySettingsVars.settings
      ? _.cloneDeep(dittySettingsVars.settings)
      : {}
  );
  const [settings, setSettings] = useState(_.cloneDeep(initSettings));
  const [showSpinner, setShowSpinner] = useState(false);

  console.log("settings", settings);

  const hasUpdates = !_.isEqual(settings, initSettings);
  const wrapper = document.getElementById("ditty-settings__wrapper");

  useEffect(() => {
    const resizeHandler = () => {
      const windowH = window.innerHeight;
      const top = wrapper.getBoundingClientRect().top;
      const h = windowH - top;
      wrapper.style.height = `${h}px`;
    };
    resizeHandler();
    window.addEventListener("resize", resizeHandler);
    return () => window.removeEventListener("resize", resizeHandler);
  }, []);

  const setParams = (name, val) => {
    if (val) {
      params.set(name, val);
    } else {
      params.delete(name);
    }
    if ("" === params.toString()) {
      history.pushState(null, null, location.pathname);
    } else {
      history.pushState(
        null,
        null,
        location.pathname + "?" + params.toString()
      );
    }
  };

  const onSettingsSaveComplete = (data) => {
    setShowSpinner(false);

    if (data.updates.settings) {
      toast(__("Settings have been updated!", "ditty-news-ticker"), {
        autoClose: 2000,
        icon: <Logo style={{ height: "30px" }} />,
      });
      setInitSettings(_.cloneDeep(data.updates.settings));
      setSettings(_.cloneDeep(data.updates.settings));
    } else if (data.updates.errors) {
      console.log("errors", data.updates.errors);
    }
  };

  const handleSaveSettings = async () => {
    setShowSpinner(true);
    const updatedSettings = _.cloneDeep(settings);
    // if (settings.variation_defaults) {
    //   updatedSettings.variation_defaults = JSON.stringify(
    //     settings.variation_defaults
    //   );
    // }
    console.log("updatedSettings", updatedSettings);

    try {
      await saveSettings(updatedSettings, onSettingsSaveComplete);
    } catch (ex) {
      let update = __("Whoops! Something went wrong...", "ditty-news-ticker");
      if (
        (ex.response && ex.response.status === 403) ||
        (ex.response && ex.response.status === 404)
      ) {
        update = ex.response.data.message;
      }

      setShowSpinner(false);
      toast(update, {
        autoClose: 2000,
        icon: <Logo style={{ height: "30px" }} />,
        className: "ditty-error",
      });
    }
  };

  const getCurrentFieldGroup = () => {
    const index = fieldGroups.findIndex((fieldGroup) => {
      return fieldGroup.id === currentTabId;
    });
    if (-1 === index) {
      return false;
    }
    return fieldGroups[index];
  };

  const renderContent = () => {
    const currentFieldGroup = getCurrentFieldGroup();
    return (
      <FieldList
        name={currentFieldGroup.name}
        description={currentFieldGroup.description}
        fields={currentFieldGroup.fields}
        values={settings}
        onUpdate={(id, value) => {
          const updatedSettings = _.cloneDeep(settings);
          console.log("updatedSettings", updatedSettings);
          updatedSettings[id] = value;
          setSettings(updatedSettings);
        }}
      />
    );

    // switch (currentFieldGroup.id) {
    //   case "layoutDefaults":
    //     return (
    //       <FieldList
    //         name={currentFieldGroup.name}
    //         description={currentFieldGroup.description}
    //       />
    //     );
    //   case "layoutTemplates":
    //     return (
    //       <FieldList
    //         name={currentFieldGroup.name}
    //         description={currentFieldGroup.description}
    //       />
    //     );
    //   case "displayTemplates":
    //     return (
    //       <FieldList
    //         name={currentFieldGroup.name}
    //         description={currentFieldGroup.description}
    //       />
    //     );
    //   case "extensions":
    //     return (
    //       <FieldList
    //         name={currentFieldGroup.name}
    //         description={currentFieldGroup.description}
    //       />
    //     );
    //   default:
    //     return (
    //       <FieldList
    //         name={currentFieldGroup.name}
    //         description={currentFieldGroup.description}
    //         fields={currentFieldGroup.fields}
    //         values={settings}
    //         onUpdate={(id, value) => {
    //           const updatedSettings = { ...settings };
    //           updatedSettings[id] = value;
    //           setSettings(updatedSettings);
    //         }}
    //       />
    //     );
    // }
  };

  return (
    <>
      <AdminBar
        title={__("Settings", "ditty-news-ticker")}
        buttonLabel={__("Save Settings", "ditty-news-ticker")}
        hasUpdates={hasUpdates}
        showSpinner={showSpinner}
        onSubmit={handleSaveSettings}
      />
      <div id="ditty-settings" className="ditty-adminPage__app">
        <div
          id="ditty-settings__navigation"
          className="ditty-adminPage__app__sidebar"
        >
          <Tabs
            type="list"
            tabs={fieldGroups}
            currentTabId={currentTabId}
            tabClick={(tab) => {
              setParams("tab", tab.id);
              setCurrentTabId(tab.id);
            }}
            className="itemEdit__header__tabs"
          />
        </div>
        <div
          id="ditty-settings__content"
          className="ditty-adminPage__app__content"
        >
          {renderContent()}
        </div>
      </div>
      <FooterBar />
      <ToastContainer />
    </>
  );
};
