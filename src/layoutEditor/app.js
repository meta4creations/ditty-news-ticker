import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import { AdminBar, FooterBar } from "../common";
import LayoutEditor from "./LayoutEditor";
import { ReactComponent as Logo } from "../assets/img/d.svg";

import { saveLayout } from "../services/httpService";
import { getItemTypeObject } from "../utils/itemTypes";
import { compileLayoutStyle } from "../utils/layouts";
import { getDisplayItems } from "../services/dittyService";
import { updateLayoutCss } from "../utils/helpers";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  const defaultItemTypeObject = getItemTypeObject("default");

  const [id, setId] = useState(dittyEditorVars.id);
  const [title, setTitle] = useState(dittyEditorVars.title);
  const [description, setDescription] = useState(dittyEditorVars.description);
  const [status, setStatus] = useState(dittyEditorVars.status);
  // const [html, setHtml] = useState(
  //   "ditty_layout-new" === dittyEditorVars.id
  //     ? defaultItemTypeObject.defaultLayout.html
  //     : dittyEditorVars.html
  // );
  // const [css, setCss] = useState(
  //   "ditty_layout-new" === dittyEditorVars.id
  //     ? defaultItemTypeObject.defaultLayout.css
  //     : dittyEditorVars.css
  // );

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
          autoClose: 2000,
          icon: <Logo style={{ height: "30px" }} />,
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
      let update = __("Whoops! Something went wrong...", "ditty-news-ticker");
      if (ex.response && ex.response.status === 403) {
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

  const handleUpdateLayout = (updatedLayout) => {
    const newUpdates = { ...updates };
    newUpdates.layout = updatedLayout;
    setLayout(updatedLayout);
    setUpdates(newUpdates);
    updatePreview({ updatedLayout: updatedLayout });
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
      <ToastContainer />
    </>
  );
};
