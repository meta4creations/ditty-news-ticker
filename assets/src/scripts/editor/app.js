const { __ } = wp.i18n;
const { useState, useContext, useEffect } = wp.element;
import { AdminBar, FooterBar, Preview } from "../common";
import { EditorContext } from "./context";
import Editor from "./Editor";

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
  const { DittyNotificationContainer } = dittyEditor.notifications;
  const [showSpinner, setShowSpinner] = useState(false);
  const updates = helpers.dittyUpdates();
  const hasUpdates = Object.keys(updates).length !== 0;
  const wrapper = document.getElementById("ditty-editor__wrapper");
  const adminMenu = document.getElementById("adminmenuwrap");

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
      adminMenu.style.height = `${h}px`;
    };
    resizeHandler();
    window.addEventListener("resize", resizeHandler);

    // Clean up the event listener when the component is unmounted
    return () => {
      window.removeEventListener("resize", resizeHandler);
      window.removeEventListener("beforeunload", handleBeforeUnload);
    };
  }, [hasUpdates]);

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
    styles.background = settings.previewBg ? settings.previewBg : false;
    return styles;
  };

  const previewSettings = currentDisplay.settings
    ? currentDisplay.settings
    : {};
  previewSettings.orderby = settings.orderby ? settings.orderby : "list";
  previewSettings.order = settings.order ? settings.order : "desc";

  const previewDisplay = currentDisplay
    ? {
        type: currentDisplay.type,
        settings: previewSettings,
      }
    : false;

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
          display={previewDisplay}
          displays={displays}
          displayItems={displayItems}
          styles={getPreviewStyles()}
        />
        <Editor className="ditty-adminPage__app__sidebar" />
      </div>
      <FooterBar />
      <DittyNotificationContainer />
    </>
  );
};
