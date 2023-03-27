import { createHooks } from "@wordpress/hooks";
import * as common from "./common";
import * as components from "./components";
import * as fields from "./fields";
import * as helpers from "./utils/helpers";

dittyEditor.common = common;
dittyEditor.components = components;
dittyEditor.fields = fields;
dittyEditor.helpers = helpers;

const editorHooks = createHooks();
dittyEditor.addFilter = (action, callable, priority) => {
  editorHooks.addFilter(action, "dittyEditor", callable, priority);
};
dittyEditor.applyFilters = editorHooks.applyFilters;

//dittyEditor.DittyItemType = DittyItemType;
//dittyEditor.layoutTags = layoutTags;

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
};

/**
 * Store registered display types
 */
dittyEditor.displayTypes = [];
dittyEditor.registerDisplayType = (displayType) => {
  dittyEditor.displayTypes.push(displayType);
};
