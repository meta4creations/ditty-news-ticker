import _ from "lodash";
const { __ } = wp.i18n;
const { useState, useEffect } = wp.element;
import { AdminBar, FooterBar } from "../common";
import { Tabs } from "../components";
import { FieldList } from "../fields";
import { saveSettings } from "../services/httpService";

export default () => {
  const { DittyNotificationContainer, dittyNotification } =
    dittyEditor.notifications;
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

  const hasUpdates = !_.isEqual(settings, initSettings);

  useEffect(() => {
    const handleBeforeUnload = (event) => {
      if (hasUpdates) {
        event.preventDefault();
        event.returnValue = __(
          "You have unsaved changes. Are you sure you want to leave this page?",
          "ditty-news-ticker"
        );
      }
    };
    window.addEventListener("beforeunload", handleBeforeUnload);

    // Clean up the event listener when the component is unmounted
    return () => {
      window.removeEventListener("beforeunload", handleBeforeUnload);
    };
  }, [hasUpdates]);

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
      dittyNotification(__("Settings have been updated!", "ditty-news-ticker"));
      setInitSettings(_.cloneDeep(data.updates.settings));
      setSettings(_.cloneDeep(data.updates.settings));
    } else if (data.updates.errors) {
      if (window.console) {
        console.log("errors", data.updates.errors);
      }
    }
  };

  const handleSaveSettings = async () => {
    setShowSpinner(true);
    const updatedSettings = _.cloneDeep(settings);
    try {
      await saveSettings(updatedSettings, onSettingsSaveComplete);
    } catch (ex) {
      setShowSpinner(false);
      dittyNotification(ex, "error");
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
          updatedSettings[id] = value;
          setSettings(updatedSettings);
        }}
      />
    );
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
      <DittyNotificationContainer />
    </>
  );
};
