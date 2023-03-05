import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { AdminBar, FooterBar, Preview } from "../common";
import DisplayEditor from "./DisplayEditor";
import { ReactComponent as Logo } from "../assets/img/d.svg";

import { saveDisplay } from "../services/httpService";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  const [id, setId] = useState(dittyEditorVars.id);
  const [title, setTitle] = useState(dittyEditorVars.title);
  const [description, setDescription] = useState(dittyEditorVars.description);
  const [type, setType] = useState(dittyEditorVars.type);
  const [settings, setSettings] = useState(dittyEditorVars.settings);
  const [showSpinner, setShowSpinner] = useState(false);
  const [updates, setUpdates] = useState(
    "ditty_display-new" == id ? { title: title, display: display } : {}
  );
  const hasUpdates = Object.keys(updates).length !== 0;

  const onDisplaySaveComplete = (data) => {
    setShowSpinner(false);

    // If saving a new Display
    if (data.updates && data.updates.new) {
      // Get the current URL
      const url = new URL(window.location.href);

      // Update the query parameters
      url.searchParams.set("page", "ditty-display");
      url.searchParams.set("id", data.updates.new);

      // Replace the current state with the updated URL
      history.replaceState(null, "", url);
    }

    // Creat toast notifications
    if (data.updates) {
      const toastUpdates = [];
      if (data.updates.new) {
        toastUpdates.push(
          __(`Display has been published!`, "ditty-news-ticker")
        );
      } else {
        for (const property in data.updates) {
          switch (property) {
            case "description":
              toastUpdates.push(
                __(`Display description has been updated!`, "ditty-news-ticker")
              );
              break;
            case "settings":
              toastUpdates.push(
                __(`Display settings have been updated!`, "ditty-news-ticker")
              );
              break;
            case "title":
              toastUpdates.push(
                __(`Display title has been updated!`, "ditty-news-ticker")
              );
              break;
            case "type":
              toastUpdates.push(
                __(`Display type has been updated!`, "ditty-news-ticker")
              );
              break;
            default:
              break;
          }
        }
      }

      // Show Toast updates
      toastUpdates.map((update, index) => {
        toast(update, {
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
          delay: index * 100,
        });
      });

      // Update state with sanitized data
      for (const property in data.updates) {
        switch (property) {
          case "description":
            setDescription(data.updates.description);
            break;
          case "new":
            setId(data.updates.new);
            break;
          case "settings":
            setSettings(data.updates.settings);
            break;
          case "title":
            setTitle(data.updates.title);
            break;
          case "type":
            setType(data.updates.type);
            break;
          default:
            break;
        }
      }
    }
  };

  const handleSaveDisplay = () => {
    if (!hasUpdates) {
      return false;
    }
    setShowSpinner(true);

    const display = {
      id: id,
      type: updates.type ? updates.type : false,
      settings: updates.settings ? updates.settings : false,
    };
    const data = {
      title: updates.title ? updates.title : false,
      description: updates.description ? updates.description : false,
      display: display,
    };
    saveDisplay(data, onDisplaySaveComplete);
  };

  const handleUpdateTitle = (updatedTitle) => {
    const newTitle =
      "" === updatedTitle
        ? __(`Display ${id}`, "ditty-news-ticker")
        : updatedTitle;
    const newUpdates = { ...updates };
    newUpdates.title = newTitle;

    setTitle(newTitle);
    setUpdates(newUpdates);
  };

  const handleUpdateDisplaySettings = (id, value) => {
    const updatedSettings = { ...settings };
    updatedSettings[id] = value;
    const newUpdates = { ...updates };
    newUpdates.settings = updatedSettings;

    setSettings(updatedSettings);
    setUpdates(newUpdates);
  };

  const handleUpdateDisplayType = (type) => {
    const newUpdates = { ...updates };
    newUpdates.type = type;

    setType(type);
    setUpdates(newUpdates);
  };

  return (
    <>
      <AdminBar
        logo={
          <Logo
            style={{ height: "30px", fill: "#19bf7c", marginRight: "5px" }}
          />
        }
        title={title}
        buttonLabel={__("Save Display", "ditty-news-ticker")}
        hasUpdates={hasUpdates}
        showSpinner={showSpinner}
        onUpdateTitle={handleUpdateTitle}
        onSubmit={handleSaveDisplay}
      />
      <div id="ditty-display-editor" className="ditty-adminPage__app">
        <Preview className="ditty-adminPage__app__content" />
        <DisplayEditor
          className="ditty-adminPage__app__sidebar"
          display={{ id: id, type: type, settings: settings }}
          title={title}
          description={description}
          updateDisplaySettings={handleUpdateDisplaySettings}
          updateDisplayType={handleUpdateDisplayType}
        />
      </div>
      <FooterBar />
      <ToastContainer />
    </>
  );
};
