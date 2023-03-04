const { render } = wp.element; //we are using wp.element here!
import { createHooks } from "@wordpress/hooks";
import App from "./displayEditor/app";
import "./assets/css/editor.scss";
import { easeOptions, sliderTransitions } from "./utils/helpers";

const editorHooks = createHooks();
dittyEditor.addFilter = (action, callable, priority) => {
  editorHooks.addFilter(action, "dittyEditor", callable, priority);
};
dittyEditor.applyFilters = editorHooks.applyFilters;
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

if (document.getElementById("ditty-display-editor__wrapper")) {
  render(<App />, document.getElementById("ditty-display-editor__wrapper"));
}
