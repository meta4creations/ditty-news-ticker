import { createHooks } from "@wordpress/hooks";
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
