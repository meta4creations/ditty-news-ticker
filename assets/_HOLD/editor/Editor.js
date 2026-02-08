import classnames from "classnames";
const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;
const { useState, useContext } = wp.element;
import { Icon, Tabs } from "../components";
import PanelItems from "./PanelItems";
import PanelDisplays from "./PanelDisplays";
import PanelSettings from "./PanelSettings";
import PanelTranslation from "./PanelTranslation";
import { EditorContext } from "./context";

const Editor = ({ className }) => {
  const editor = useContext(EditorContext);
  const { settings, actions } = editor;
  const [currentTabId, setCurrentTabId] = useState("items");
  let editorWidth = settings.editorWidth ? Number(settings.editorWidth) : 350;
  let editorHeight = settings.editorHeight
    ? Number(settings.editorHeight)
    : 350;
  if (editorWidth < 300) {
    editorWidth = 300;
  }

  const handler = (mouseDownEvent) => {
    const isVertical = window.innerWidth < 782;

    const startSize = isVertical ? editorHeight : editorWidth;
    const startPosition = isVertical
      ? mouseDownEvent.pageY
      : mouseDownEvent.pageX;

    function onMouseMove(mouseMoveEvent) {
      let newSize = isVertical
        ? startSize + startPosition - mouseMoveEvent.pageY
        : startSize + startPosition - mouseMoveEvent.pageX;
      if (newSize < 300) {
        newSize = 300;
      }

      if (isVertical) {
        settings.editorHeight = newSize;
      } else {
        settings.editorWidth = newSize;
      }
      actions.updateSettings(settings);
    }
    function onMouseUp() {
      document.body.removeEventListener("mousemove", onMouseMove);
      // uncomment the following line if not using `{ once: true }`
      // document.body.removeEventListener("mouseup", onMouseUp);
    }

    document.body.addEventListener("mousemove", onMouseMove);
    document.body.addEventListener("mouseup", onMouseUp, { once: true });
  };

  const tabsArray = [
    {
      id: "items",
      label: __("Items", "ditty-news-ticker"),
      icon: <Icon id="faBarsStaggered" />,
      content: <PanelItems />,
    },
    {
      id: "display",
      label: __("Display", "ditty-news-ticker"),
      icon: <Icon id="faTabletScreen" />,
      content: <PanelDisplays />,
    },
    {
      id: "settings",
      label: __("Settings", "ditty-news-ticker"),
      icon: <Icon id="faGear" />,
      content: <PanelSettings />,
    },
  ];
  if (dittyEditorVars.translationPlugin) {
    tabsArray.push({
      id: "translation",
      label: __("Translation", "ditty-news-ticker"),
      icon: <Icon id="faLanguage" />,
      content: <PanelTranslation />,
    });
  }
  const tabs = applyFilters("dittyEditor.tabs", tabsArray, EditorContext);

  const renderAfterEditor = () => {
    const afterEditor = applyFilters("dittyEditor.afterEditor", [], editor);
    const sortedAfterEditor = afterEditor
      .sort((a, b) => (a.order || 10) - (b.order || 10))
      .map((item) => item.content);
    return sortedAfterEditor.map((after, index) => (
      <React.Fragment key={index}>{after}</React.Fragment>
    ));
  };

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  const renderCurrentPanel = () => {
    const index = tabs.findIndex((object) => {
      return object.id === currentTabId;
    });
    const content =
      -1 === index ? "" : tabs[index].content ? tabs[index].content : "";

    return applyFilters(
      "dittyEditor.panel",
      content,
      currentTabId,
      EditorContext
    );
  };

  const classes = classnames(className);

  return (
    <div
      id="ditty-editor__editor"
      className={classes}
      style={{ width: `${editorWidth}px`, height: `${editorHeight}px` }}
    >
      <div
        id="ditty-editor__sizer"
        className="ditty-adminPage__app__sizer"
        onMouseDown={handler}
      ></div>
      <Tabs
        tabs={tabs}
        currentTabId={currentTabId}
        tabClick={handleTabClick}
        type="primary"
      />
      <div className="ditty-editor__panels">{renderCurrentPanel()}</div>
      {renderAfterEditor()}
    </div>
  );
};
export default Editor;
