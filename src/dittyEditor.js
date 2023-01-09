const { render } = wp.element; //we are using wp.element here!
import { createHooks } from "@wordpress/hooks";
import { EditorProvider } from "./editor/context";
import App from "./editor/app";
import { easeOptions, sliderTransitions } from "./editor/utils/helpers";
import "./editor/css/editor.scss";

// window.dittyEditor = {
//   helpers: {
//     easeOptions,
//     sliderTransitions,
//   },
// };
const editorHooks = createHooks();
dittyEditor.addFilter = (action, callable, priority) => {
  editorHooks.addFilter(action, "dittyEditor", callable, priority);
};
dittyEditor.applyFilters = (action, args) => {
  console.log("args", args);
  return editorHooks.applyFilters(action, args);
};
dittyEditor.helpers = {
  easeOptions,
  sliderTransitions,
};
dittyEditor.registerDisplayType = (displayType) => {
  dittyEditor.addFilter("dittyDisplayTypes", (displayTypes) => {
    displayTypes.push(displayType);
    return displayTypes;
  });
};

if (document.getElementById("ditty-editor__wrapper")) {
  const $dittyEditorWrapper = document.getElementById("ditty-editor__wrapper");
  render(
    <EditorProvider data={$dittyEditorWrapper.dataset}>
      <App />
    </EditorProvider>,
    document.getElementById("ditty-editor__wrapper")
  );
}
