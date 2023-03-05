import _ from "lodash";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { AdminBar, FooterBar } from "../common";
import { Tabs } from "../components";
import { FieldList } from "../fields";
import { saveSettings } from "../services/httpService";
import { ReactComponent as Logo } from "./ditty.svg";

import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  const fieldGroups =
    dittySettingsVars && dittySettingsVars.fields
      ? dittySettingsVars.fields
      : {};
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);

  const [initSettings, setInitSettings] = useState(
    dittySettingsVars && dittySettingsVars.settings
      ? dittySettingsVars.settings
      : {}
  );
  const [settings, setSettings] = useState(_.cloneDeep(initSettings));
  const [showSpinner, setShowSpinner] = useState(false);

  const hasUpdates = !_.isEqual(settings, initSettings);

  const onSaveComplete = (data) => {
    setShowSpinner(false);

    if (data.updates.settings) {
      toast(__("Settings have been updated!", "ditty-news-ticker"), {
        autoClose: 3000,
        icon: (
          <svg
            className="ditty-logo"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 69.8 71.1"
          >
            <path d="M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM54.7 63.7a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z" />
          </svg>
        ),
      });
      setInitSettings(_.cloneDeep(data.updates.settings));
      setSettings(_.cloneDeep(data.updates.settings));
    } else if (data.updates.errors) {
      console.log("errors", data.updates.errors);
    }
  };

  const handleSaveSettings = () => {
    setShowSpinner(true);
    saveSettings(settings, onSaveComplete);
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

    switch (currentFieldGroup.id) {
      case "layoutDefaults":
        return (
          <FieldList
            name={currentFieldGroup.name}
            description={currentFieldGroup.description}
          />
        );
      case "layoutTemplates":
        return (
          <FieldList
            name={currentFieldGroup.name}
            description={currentFieldGroup.description}
          />
        );
      case "displayTemplates":
        return (
          <FieldList
            name={currentFieldGroup.name}
            description={currentFieldGroup.description}
          />
        );
      case "extensions":
        console.log("currentFieldGroup", currentFieldGroup);
        return (
          <FieldList
            name={currentFieldGroup.name}
            description={currentFieldGroup.description}
          />
        );
      default:
        return (
          <FieldList
            name={currentFieldGroup.name}
            description={currentFieldGroup.description}
            fields={currentFieldGroup.fields}
            values={settings}
            onUpdate={(id, value) => {
              const updatedSettings = { ...settings };
              updatedSettings[id] = value;
              setSettings(updatedSettings);
            }}
          />
        );
    }
  };

  return (
    <>
      <AdminBar
        title={
          <>
            <Logo
              style={{ height: "30px", fill: "#19bf7c", marginRight: "5px" }}
            />
            <span>{__("settings", "ditty-news-ticker")}</span>
          </>
        }
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
            tabClick={(tab) => setCurrentTabId(tab.id)}
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
