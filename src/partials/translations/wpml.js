import { addFilter } from "@wordpress/hooks";

const dittyWPML = (function () {
  const editorTab = (tabs) => {
    if ("wpml" !== dittyEditorVars.translationPlugin) {
      return tabs;
    }
    console.log("yessir!");
    return tabs;
  };

  return {
    EditorTab: editorTab,
  };
})();

addFilter(
  "dittyEditor.tabs",
  "ditty-news-ticker/dittyEditorWPMLTab",
  dittyWPML.EditorTab
);
