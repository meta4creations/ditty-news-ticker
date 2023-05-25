import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import { AdminBar, FooterBar } from "../common";
import LayoutEditor from "./LayoutEditor";
import { saveLayout } from "../services/httpService";
import { getItemTypeObject } from "../utils/itemTypes";
import { compileLayoutStyle } from "../utils/layouts";
import {
  getDisplayItems,
  updateDisplayOptions,
} from "../services/dittyService";
import { updateLayoutCss } from "../utils/helpers";

export default () => {
  const { DittyNotificationContainer, dittyNotification } =
    dittyEditor.notifications;
  const defaultItemTypeObject = getItemTypeObject("default");
  const [id, setId] = useState(dittyEditorVars.id);
  const [title, setTitle] = useState(dittyEditorVars.title);
  const [description, setDescription] = useState(dittyEditorVars.description);
  const [status, setStatus] = useState(dittyEditorVars.status);

  const [layout, setLayout] = useState(
    "ditty_layout-new" === dittyEditorVars.id
      ? {
          html: defaultItemTypeObject.defaultLayout.html,
          css: defaultItemTypeObject.defaultLayout.css,
        }
      : {
          html: dittyEditorVars.html,
          css: dittyEditorVars.css,
        }
  );

  const [editorItem, setEditorItem] = useState(
    dittyEditorVars.editorItem
      ? dittyEditorVars.editorItem
      : {
          item_id: id,
          item_type: "default",
          item_value: defaultItemTypeObject.defaultValues,
        }
  );
  const [editorSettings, setEditorSettings] = useState(
    dittyEditorVars.editorSettings ? dittyEditorVars.editorSettings : {}
  );
  const [showSpinner, setShowSpinner] = useState(false);
  const [updates, setUpdates] = useState(
    "ditty_layout-new" == id
      ? {
          title: title,
          id: dittyEditorVars.id,
          layout: layout,
          editorItem: editorItem,
        }
      : {}
  );
  const hasUpdates = Object.keys(updates).length !== 0;
  const [displayItem, setDisplayItem] = useState({
    html: "",
    css: "",
  });
  const wrapper = document.getElementById("ditty-layout-editor__wrapper");

  useEffect(() => {
    updatePreview();

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

  const updatePreview = (args = {}) => {
    const updatedEditorItem = args.updatedEditorItem
      ? args.updatedEditorItem
      : editorItem;
    const updatedLayout = args.updatedLayout ? args.updatedLayout : layout;

    // Get new display items
    const editorDisplayItem = {
      ...updatedEditorItem,
      item_id: id,
      layout_value: {
        default: { id: id, html: updatedLayout.html, css: updatedLayout.css },
      },
    };
    getDisplayItems(editorDisplayItem, false, (data) => {
      if (data.display_items && data.display_items.length) {
        updateLayoutCss(data.display_items[0].css, id);
        setDisplayItem(data.display_items[0]);
      }
    });
  };

  const onLayoutSaveComplete = (data) => {
    setShowSpinner(false);
    // If saving a new Layout
    if (data.updates && data.updates.new) {
      // Get the current URL
      const url = new URL(window.location.href);
      // Update the query parameters
      url.searchParams.set("page", "ditty_layout");
      url.searchParams.set("id", data.updates.new);
      // Replace the current state with the updated URL
      history.replaceState(null, "", url);
    }
    // Creat notifications
    if (data.updates) {
      const notifications = [];
      if (data.updates.new) {
        notifications.push(
          __(`Layout has been published!`, "ditty-news-ticker")
        );
      } else {
        notifications.push(__(`Layout has been updated!`, "ditty-news-ticker"));
      }
      // Show notifications
      notifications.map((update, index) => {
        dittyNotification(update, "success", {
          delay: index * 100,
        });
      });
      // Update state with sanitized data
      const updatedLayout = { ...layout };
      for (const property in data.updates) {
        switch (property) {
          case "css":
            updatedLayout.css = data.updates.css;
            break;
          case "description":
            setDescription(data.updates.description);
            break;
          case "editorItem":
            setEditorItem(data.updates.editorItem);
            break;
          case "editorSettings":
            setEditorSettings(data.updates.editorSettings);
            break;
          case "html":
            updatedLayout.html = data.updates.html;
            break;
          case "new":
            setId(data.updates.new);
            break;
          case "title":
            setTitle(data.updates.title);
            break;
          default:
            break;
        }
      }
      setLayout(updatedLayout);
    }
    setUpdates({});
  };

  const handleSaveLayout = async () => {
    if (!hasUpdates) {
      return false;
    }
    setShowSpinner(true);

    const compiledLayout = {
      id: id,
      html: updates.layout && updates.layout.html ? updates.layout.html : false,
      css: updates.layout && updates.layout.css ? updates.layout.css : false,
    };

    const data = {
      title: updates.title,
      description: updates.description ? updates.description : false,
      status: updates.status ? updates.status : false,
      editorItem: updates.editorItem ? updates.editorItem : false,
      editorSettings: updates.editorSettings ? updates.editorSettings : false,
      layout: compiledLayout,
    };

    try {
      await saveLayout(data, onLayoutSaveComplete);
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

  const handleUpdateLayout = (updatedLayout, type) => {
    const newUpdates = { ...updates };
    newUpdates.layout = updatedLayout;
    setLayout(updatedLayout);
    setUpdates(newUpdates);
    if ("css" !== type) {
      updatePreview({ updatedLayout: updatedLayout });
    }
    compileLayoutStyle(updatedLayout.css, `${id}_default`, (css) => {
      updateLayoutCss(css, id);
    });
  };

  const handleUpdateEditorItem = (updatedEditorItem) => {
    const newUpdates = { ...updates };
    updatedEditorItem.item_id = id;
    newUpdates.editorItem = updatedEditorItem;

    setEditorItem(updatedEditorItem);
    setUpdates(newUpdates);
    updatePreview({ updatedEditorItem: updatedEditorItem });
  };

  const handleUpdateEditorSettings = (updatedEditorSettings) => {
    const newUpdates = { ...updates };
    newUpdates.editorSettings = updatedEditorSettings;

    setEditorSettings(updatedEditorSettings);
    setUpdates(newUpdates);
  };

  const getPreviewStyles = () => {
    const styles = editorSettings.previewPadding
      ? { ...editorSettings.previewPadding }
      : {};
    styles.background = editorSettings.previewBg
      ? editorSettings.previewBg
      : false;
    return styles;
  };

  return (
    <>
      <AdminBar
        title={title}
        description={description}
        //status={status}
        buttonLabel={__("Save Layout", "ditty-news-ticker")}
        hasUpdates={hasUpdates}
        showSpinner={showSpinner}
        onUpdateTitle={handleUpdateTitle}
        onUpdateDescription={handleUpdateDescription}
        onUpdateStatus={handleUpdateStatus}
        onSubmit={handleSaveLayout}
      />
      <div id="ditty-layout-editor" className="ditty-adminPage__app">
        <div
          className="ditty-adminPage__app__content"
          dangerouslySetInnerHTML={{ __html: displayItem.html }}
          style={getPreviewStyles()}
        />
        <LayoutEditor
          className="ditty-adminPage__app__sidebar"
          title={title}
          description={description}
          status={status}
          layout={layout}
          editorItem={editorItem}
          editorSettings={editorSettings}
          onUpdateLayout={handleUpdateLayout}
          onUpdateTitle={handleUpdateTitle}
          onUpdateDescription={handleUpdateDescription}
          onUpdateStatus={handleUpdateStatus}
          onUpdateEditorItem={handleUpdateEditorItem}
          onUpdateEditorSettings={handleUpdateEditorSettings}
        />
      </div>
      <FooterBar />
      <DittyNotificationContainer />
    </>
  );
};
