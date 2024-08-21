const { __ } = wp.i18n;
import * as common from "./common";
import * as components from "./components";
import * as fields from "./fields";
import * as helpers from "./utils/helpers";
import { phpItemMods } from "./services/httpService";
import * as dittyService from "./services/dittyService";
import * as notifications from "./utils/DittyNotification";

dittyEditor.common = common;
dittyEditor.components = components;
dittyEditor.fields = fields;
dittyEditor.helpers = helpers;
dittyEditor.notifications = notifications;
dittyEditor.dittyService = dittyService;
dittyEditor.httpService = {
  phpItemMods,
};

/**
 * Store registered item types
 */
dittyEditor.itemTypes = [];
dittyEditor.registerItemType = (itemType) => {
  console.log("itemType", itemType);
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

/**
 * Add icons
 */
dittyEditor.icons = {
  slider: (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
      <path d="M0 416c0 8.8 7.2 16 16 16l65.6 0c7.4 36.5 39.7 64 78.4 64s71-27.5 78.4-64L496 432c8.8 0 16-7.2 16-16s-7.2-16-16-16l-257.6 0c-7.4-36.5-39.7-64-78.4-64s-71 27.5-78.4 64L16 400c-8.8 0-16 7.2-16 16zm112 0a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM304 256a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm48-80c-38.7 0-71 27.5-78.4 64L16 240c-8.8 0-16 7.2-16 16s7.2 16 16 16l257.6 0c7.4 36.5 39.7 64 78.4 64s71-27.5 78.4-64l65.6 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-65.6 0c-7.4-36.5-39.7-64-78.4-64zM192 144a48 48 0 1 1 0-96 48 48 0 1 1 0 96zm78.4-64C263 43.5 230.7 16 192 16s-71 27.5-78.4 64L16 80C7.2 80 0 87.2 0 96s7.2 16 16 16l97.6 0c7.4 36.5 39.7 64 78.4 64s71-27.5 78.4-64L496 112c8.8 0 16-7.2 16-16s-7.2-16-16-16L270.4 80z" />
    </svg>
  ),
};
