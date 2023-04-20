import { __ } from "@wordpress/i18n";
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
