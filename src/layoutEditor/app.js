import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import { AdminBar, FooterBar } from "../common";
import LayoutEditor from "./LayoutEditor";
import { ReactComponent as Logo } from "../assets/img/d.svg";

import { saveLayout } from "../services/httpService";
import { compileLayoutStyle } from "../utils/layouts";
import { getDisplayItems } from "../services/dittyService";
import { updateLayoutCss } from "../utils/helpers";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  const [id, setId] = useState(dittyEditorVars.id);
  const [title, setTitle] = useState(dittyEditorVars.title);
  const [description, setDescription] = useState(dittyEditorVars.description);
  const [status, setStatus] = useState(dittyEditorVars.status);
  const [html, setHtml] = useState(dittyEditorVars.html);
  const [css, setCss] = useState(dittyEditorVars.css);
  const [editorItem, setEditorItem] = useState(
    dittyEditorVars.editorItem
      ? dittyEditorVars.editorItem
      : { item_id: id, item_type: "default", item_value: {} }
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
          html: "",
          css: "",
        }
      : {}
  );
  const hasUpdates = Object.keys(updates).length !== 0;
  const [displayItem, setDisplayItem] = useState({
    html: "",
    css: "",
  });

  useEffect(() => {
    updatePreview();
  }, []);

  const updateCss = (css) => {
    updateLayoutCss(css, id);
  };

  const updatePreview = (args = {}) => {
    const updatedEditorItem = args.updatedEditorItem
      ? args.updatedEditorItem
      : editorItem;
    const updatedHtml = args.updatedHtml ? args.updatedHtml : html;
    const updatedCss = args.updatedCss ? args.updatedCss : css;

    // Get new display items
    const editorDisplayItem = {
      ...updatedEditorItem,
      item_id: id,
      layout_value: { default: { id: id, html: updatedHtml, css: updatedCss } },
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
    // Creat toast notifications
    if (data.updates) {
      const toastUpdates = [];
      if (data.updates.new) {
        toastUpdates.push(
          __(`Layout has been published!`, "ditty-news-ticker")
        );
      } else {
        toastUpdates.push(__(`Layout has been updated!`, "ditty-news-ticker"));
      }
      // Show Toast updates
      toastUpdates.map((update, index) => {
        toast(update, {
          autoClose: 3000,
          icon: <Logo style={{ height: "30px", fill: "#19bf7c" }} />,
          delay: index * 100,
        });
      });
      // Update state with sanitized data
      for (const property in data.updates) {
        switch (property) {
          case "css":
            setCss(data.updates.css);
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
            setHtml(data.updates.html);
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
    }
    setUpdates({});
  };

  const handleSaveLayout = () => {
    if (!hasUpdates) {
      return false;
    }
    setShowSpinner(true);

    const layout = {
      id: id,
      html: updates.html ? updates.html : false,
      css: updates.css ? updates.css : false,
    };

    const data = {
      title: updates.title,
      description: updates.description ? updates.description : false,
      status: updates.status ? updates.status : false,
      editorItem: updates.editorItem ? updates.editorItem : false,
      editorSettings: updates.editorSettings ? updates.editorSettings : false,
      layout: layout,
    };
    saveLayout(data, onLayoutSaveComplete);
  };

  const handleUpdateTitle = (updatedTitle) => {
    const newUpdates = { ...updates };
    newUpdates.title = updatedTitle;

    setTitle(updatedTitle);
    setUpdates(newUpdates);

    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, "title", newTitle);
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

  const handleUpdateLayoutHtml = (updatedHtml) => {
    const newUpdates = { ...updates };
    newUpdates.html = updatedHtml;

    setHtml(updatedHtml);
    setUpdates(newUpdates);
    updatePreview({ updatedHtml: updatedHtml });
  };

  const handleUpdateLayoutCss = (updatedCss) => {
    const newUpdates = { ...updates };
    newUpdates.css = updatedCss;

    setCss(updatedCss);
    setUpdates(newUpdates);
    compileLayoutStyle(updatedCss, `${id}_default`, updateCss);
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
    styles.backgroundColor = editorSettings.previewBg
      ? editorSettings.previewBg
      : false;
    return styles;
  };

  return (
    <>
      <AdminBar
        title={title}
        description={description}
        status={status}
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
          layoutHtml={html}
          layoutCss={css}
          editorItem={editorItem}
          editorSettings={editorSettings}
          onUpdateLayoutHtml={handleUpdateLayoutHtml}
          onUpdateLayoutCss={handleUpdateLayoutCss}
          onUpdateTitle={handleUpdateTitle}
          onUpdateDescription={handleUpdateDescription}
          onUpdateStatus={handleUpdateStatus}
          onUpdateEditorItem={handleUpdateEditorItem}
          onUpdateEditorSettings={handleUpdateEditorSettings}
        />
      </div>
      <FooterBar />
      <ToastContainer />
    </>
  );
};
