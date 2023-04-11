import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { AdminBar, FooterBar, Preview } from "../common";
import { getDisplayObject } from "../utils/displayTypes";
import { EditorContext } from "./context";
import Editor from "./Editor";

import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  const {
    id,
    title,
    status,
    displayItems,
    currentDisplay,
    displays,
    helpers,
    settings,
    actions,
  } = useContext(EditorContext);
  const [showSpinner, setShowSpinner] = useState(false);
  const updates = helpers.dittyUpdates();
  const hasUpdates = Object.keys(updates).length !== 0;

  const onDittySaveComplete = () => {
    setShowSpinner(false);
  };

  const handleSaveDitty = () => {
    setShowSpinner(true);
    actions.saveDitty(onDittySaveComplete);
  };

  const getPreviewStyles = () => {
    const styles = settings.previewPadding
      ? { ...settings.previewPadding }
      : {};
    styles.backgroundColor = settings.previewBg ? settings.previewBg : false;
    return styles;
  };

  return (
    <>
      <AdminBar
        title={title}
        status={status}
        buttonLabel={__("Save Ditty", "ditty-news-ticker")}
        hasUpdates={hasUpdates}
        showSpinner={showSpinner}
        onUpdateTitle={actions.updateTitle}
        onUpdateStatus={actions.updateStatus}
        onSubmit={handleSaveDitty}
      />
      <div id="ditty-editor" className="ditty-adminPage__app">
        <Preview
          className="ditty-adminPage__app__content"
          id={id}
          title={title}
          display={getDisplayObject(currentDisplay, displays)}
          displayItems={displayItems}
          styles={getPreviewStyles()}
        />
        <Editor className="ditty-adminPage__app__sidebar" />
      </div>
      <FooterBar />
      <ToastContainer />
    </>
  );
};
