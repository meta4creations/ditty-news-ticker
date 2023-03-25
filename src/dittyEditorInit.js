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

/**
 * Store registered item types
 */
dittyEditor.itemTypes = [];
dittyEditor.registerItemType = (itemType) => {
  const index = dittyEditor.itemTypes.findIndex(
    (type) => type.id === itemType.id
  );
  if (index < 0) {
    dittyEditor.itemTypes.push(itemType);
  } else {
    dittyEditor.itemTypes[index] = itemType;
  }
  console.log("itemTypes", dittyEditor.itemTypes);
};

/**
 * Store registered display types
 */
dittyEditor.displayTypes = [];
dittyEditor.registerDisplayType = (displayType) => {
  dittyEditor.displayTypes.push(displayType);
};
