import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import { loremIpsum } from "lorem-ipsum";
import { AdminBar, FooterBar, Preview } from "../common";
import DisplayEditor from "./DisplayEditor";
import { saveDisplay } from "../services/httpService";
import {
  replaceDisplayItems,
  updateDisplayOptions,
} from "../services/dittyService";
import { getDisplayTypeObject } from "../utils/displayTypes";

export default () => {
  const { DittyNotificationContainer, dittyNotification } =
    dittyEditor.notifications;
  const defaultDisplayType = dittyEditorVars.defaultDisplayType
    ? dittyEditorVars.defaultDisplayType
    : "list";

  const [id, setId] = useState(dittyEditorVars.id);
  const [title, setTitle] = useState(dittyEditorVars.title);
  const [description, setDescription] = useState(dittyEditorVars.description);
  const [status, setStatus] = useState(dittyEditorVars.status);
  const [type, setType] = useState(
    dittyEditorVars.type ? dittyEditorVars.type : false
  );
  const displayTypeObject = getDisplayTypeObject(
    dittyEditorVars.type ? dittyEditorVars.type : defaultDisplayType
  );

  const [settings, setSettings] = useState(
    "ditty_display-new" == id
      ? displayTypeObject.defaultValues
      : dittyEditorVars.settings
  );
  const [editorSettings, setEditorSettings] = useState(
    dittyEditorVars.editorSettings ? dittyEditorVars.editorSettings : {}
  );
  const [showSpinner, setShowSpinner] = useState(false);
  const [updates, setUpdates] = useState(
    "ditty_display-new" == id
      ? {
          title: title,
          id: dittyEditorVars.id,
          type: dittyEditorVars.type,
          settings: settings,
        }
      : {}
  );

  const hasUpdates = Object.keys(updates).length !== 0;
  const wrapper = document.getElementById("ditty-display-editor__wrapper");

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

    const resizeHandler = () => {
      const windowH = window.innerHeight;
      const top = wrapper.getBoundingClientRect().top;
      const h = windowH - top;
      wrapper.style.height = `${h}px`;
    };
    resizeHandler();
    window.addEventListener("resize", resizeHandler);

    // Clean up the event listener when the component is unmounted
    return () => {
      window.removeEventListener("resize", resizeHandler);
      window.removeEventListener("beforeunload", handleBeforeUnload);
    };
  }, [hasUpdates]);

  const onDisplaySaveComplete = (data) => {
    setShowSpinner(false);

    // If saving a new Display
    if (data.updates && data.updates.new) {
      // Get the current URL
      const url = new URL(window.location.href);

      // Update the query parameters
      url.searchParams.set("page", "ditty_display");
      url.searchParams.set("id", data.updates.new);

      // Replace the current state with the updated URL
      history.replaceState(null, "", url);
    }

    // Creat notifications
    if (data.updates) {
      const notifications = [];
      if (data.updates.new) {
        notifications.push(
          __(`Display has been published!`, "ditty-news-ticker")
        );
      } else {
        notifications.push(
          __(`Display has been updated!`, "ditty-news-ticker")
        );
      }

      // Show updates
      notifications.map((update, index) => {
        dittyNotification(update, "success", {
          delay: index * 100,
        });
      });

      // Update state with sanitized data
      for (const property in data.updates) {
        switch (property) {
          case "description":
            setDescription(data.updates.description);
            break;
          case "editorSettings":
            setEditorSettings(data.updates.editorSettings);
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
    setUpdates({});
  };

  const handleSaveDisplay = async () => {
    if (!hasUpdates) {
      return false;
    }
    setShowSpinner(true);

    const display = {
      id: id,
      type: updates.type ? updates.type : type,
      settings: updates.settings ? updates.settings : false,
    };

    let updatedTitle = false;
    if (updates.title) {
      updatedTitle =
        "" === updates.title && "ditty_display-new" == id
          ? __(`Display ${id}`, "ditty-news-ticker")
          : updates.title;
    }
    const data = {
      title: updatedTitle,
      description: updates.description ? updates.description : false,
      status: updates.status ? updates.status : false,
      editorSettings: updates.editorSettings ? updates.editorSettings : false,
      display: display,
    };

    try {
      await saveDisplay(data, onDisplaySaveComplete);
    } catch (ex) {
      dittyNotification(ex, "error");
      setShowSpinner(false);
    }
  };

  const handleUpdateTitle = (updatedTitle) => {
    const newUpdates = { ...updates };
    newUpdates.title = updatedTitle;

    setTitle(updatedTitle);
    setUpdates(newUpdates);

    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, "title", updatedTitle);
  };

  const handleUpdateDescription = (updatedDescription) => {
    const newUpdates = { ...updates };
    newUpdates.description = updatedDescription;

    setDescription(updatedDescription);
    setUpdates(newUpdates);
  };

  const handleUpdateStatus = (updatedStatus) => {
    const newUpdates = { ...updates };
    newUpdates.status = updatedStatus;
    setStatus(updatedStatus);
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

  const handleUpdateDisplayType = (updatedType, updatedSettings) => {
    const newUpdates = { ...updates };
    newUpdates.type = updatedType;
    newUpdates.settings = updatedSettings;

    setType(updatedType);
    setSettings(updatedSettings);
    setUpdates(newUpdates);
  };

  const handleUpdateEditorSettings = (updatedEditorSettings) => {
    const newUpdates = { ...updates };
    newUpdates.editorSettings = updatedEditorSettings;

    setEditorSettings(updatedEditorSettings);
    setUpdates(newUpdates);

    // Update the preview items
    if (
      updatedEditorSettings.previewItems !== editorSettings.previewItems ||
      updatedEditorSettings.previewChildItems !==
        editorSettings.previewChildItems
    ) {
      const dittyEl = document.getElementById("ditty-editor__ditty");
      const displayItems = getDisplayItems(
        updatedEditorSettings.previewItems,
        updatedEditorSettings.previewChildItems
      );
      replaceDisplayItems(dittyEl, displayItems);
    }
  };

  const getPreviewStyles = () => {
    const styles = editorSettings.previewPadding
      ? { ...editorSettings.previewPadding }
      : {};
    styles.backgroundColor = editorSettings.previewBg
      ? editorSettings.previewBg
      : false;
    return styles;
  };

  const getDisplayItems = (numberOfItems = 20, numberOfChildItems = 0) => {
    let counter = 1;
    const updatedPreviewItems = [];
    for (let i = 0; i < numberOfItems; i++) {
      const parentId = counter;
      updatedPreviewItems.push({
        css: "",
        html: `<div class="ditty-item ditty-item--${counter} ditty-item-type--default ditty-layout--${id}" data-item_id="${counter}" data-item_uniq_id="${counter}" data-parent_id="0" data-item_type="default" data-layout_id="${id}"><div class="ditty-item__elements"><div class="ditty-item__content" style="font-size:14px;line-height:1.5em;">${loremIpsum()}</div></div></div>`,
        id: counter,
        is_disabled: [],
        layout_id: id,
        parent_id: "0",
        uniq_id: counter,
      });
      counter++;
      for (let a = 0; a < numberOfChildItems; a++) {
        updatedPreviewItems.push({
          css: "",
          html: `<div class="ditty-item ditty-item--${counter} ditty-item-type--default ditty-layout--${id}" data-item_id="${counter}" data-item_uniq_id="${counter}" data-parent_id="${parentId}" data-item_type="default" data-layout_id="${id}"><div class="ditty-item__elements"><div class="ditty-item__content" style="font-size:14px;line-height:1.5em;">${loremIpsum()}</div></div></div>`,
          id: counter,
          is_disabled: [],
          layout_id: id,
          parent_id: parentId,
          uniq_id: counter,
        });
        counter++;
      }
    }
    return updatedPreviewItems;
  };

  return (
    <>
      <AdminBar
        title={title}
        description={description}
        //status={status}
        buttonLabel={__("Save Display", "ditty-news-ticker")}
        buttonDisabled={type ? false : true}
        hasUpdates={hasUpdates}
        showSpinner={showSpinner}
        onUpdateTitle={handleUpdateTitle}
        onUpdateDescription={handleUpdateDescription}
        onUpdateStatus={handleUpdateStatus}
        onSubmit={handleSaveDisplay}
      />
      <div id="ditty-display-editor" className="ditty-adminPage__app">
        <Preview
          className="ditty-adminPage__app__content"
          id={id}
          title={title}
          display={
            type
              ? { id: id, type: type, settings: settings }
              : { id: id, type: defaultDisplayType, settings: settings }
          }
          displayItems={getDisplayItems(
            editorSettings.previewItems,
            editorSettings.previewChildItems
          )}
          styles={getPreviewStyles()}
        />
        <DisplayEditor
          className="ditty-adminPage__app__sidebar"
          display={{ id: id, type: type, settings: settings }}
          title={title}
          description={description}
          status={status}
          editorSettings={editorSettings}
          onUpdateDisplaySettings={handleUpdateDisplaySettings}
          onUpdateDisplayType={handleUpdateDisplayType}
          onUpdateTitle={handleUpdateTitle}
          onUpdateDescription={handleUpdateDescription}
          onUpdateStatus={handleUpdateStatus}
          onUpdateEditorSettings={handleUpdateEditorSettings}
        />
      </div>
      <FooterBar />
      <DittyNotificationContainer />
    </>
  );
};
