import _ from "lodash";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { AdminBar, FooterBar } from "../common";
import { Tabs } from "../components";
import { FieldList } from "../fields";
import { saveSettings } from "../services/httpService";
import { ReactComponent as Logo } from "../assets/img/d.svg";

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
        icon: <Logo style={{ height: "30px", fill: "#19bf7c" }} />,
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
