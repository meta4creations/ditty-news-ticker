import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { AdminBar, FooterBar } from "../components";
import { EditorContext } from "./context";
import Preview from "./Preview";
import Editor from "./Editor";

import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  const { id, title, helpers, actions } = useContext(EditorContext);
  const [showSpinner, setShowSpinner] = useState(false);
  const updates = helpers.dittyUpdates();
  const hasUpdates = Object.keys(updates).length !== 0;

  const handleUpdateTitle = (updatedTitle) => {
    if ("" === updatedTitle) {
      actions.updateTitle(__(`Ditty ${id}`, "ditty-news-ticker"));
    } else {
      actions.updateTitle(updatedTitle);
    }
  };

  const onDittySaveComplete = () => {
    setShowSpinner(false);
  };

  const handleSaveDitty = () => {
    setShowSpinner(true);
    actions.saveDitty(onDittySaveComplete);
  };

  return (
    <>
      <AdminBar
        title={title}
        hasUpdates={hasUpdates}
        showSpinner={showSpinner}
        onUpdateTitle={handleUpdateTitle}
        onSubmit={handleSaveDitty}
      />
      <div id="ditty-editor" className="ditty-adminPage__app">
        <Preview className="ditty-adminPage__app__content" />
        <Editor className="ditty-adminPage__app__sidebar" />
      </div>
      <FooterBar />
      <ToastContainer />
    </>
  );
};
