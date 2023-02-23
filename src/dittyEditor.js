const { render } = wp.element; //we are using wp.element here!
import { createHooks } from "@wordpress/hooks";
import { EditorProvider } from "./editor/context";
import App from "./editor/app";
import "./assets/css/editor.scss";
import { easeOptions, sliderTransitions } from "./utils/helpers";
import { layoutTags } from "./utils/layoutTags";

const editorHooks = createHooks();
dittyEditor.addFilter = (action, callable, priority) => {
  editorHooks.addFilter(action, "dittyEditor", callable, priority);
};
dittyEditor.applyFilters = editorHooks.applyFilters;
dittyEditor.helpers = {
  easeOptions,
  sliderTransitions,
};
dittyEditor.layoutTags = layoutTags;
dittyEditor.registerItemType = (itemType) => {
  dittyEditor.addFilter("dittyItemTypes", (itemTypes) => {
    itemTypes.push(itemType);
    return itemTypes;
  });
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
