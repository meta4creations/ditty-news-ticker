import { getRenderedItems } from "./httpService";
import { displayTypeExists } from "../utils/helpers";

export const initializeDitty = (dittyEl, displayType, args) => {
  if (!displayTypeExists(dittyEl, displayType)) return false;
  jQuery(dittyEl)[`ditty_${displayType}`](args);
};

export const updateDittyDisplayTemplate = (dittyEl, display) => {
  if (!displayTypeExists(dittyEl, display.type)) return false;
  const prevType = dittyEl.dataset.type;
  const oldDitty = dittyEl[`_ditty_${prevType}`];
  const args = display.settings;
  args["id"] = display.id;
  args["display"] = display.type;
  args["title"] = display.title;
  if (oldDitty) {
    args["status"] = oldDitty.options("status");
    args["items"] = oldDitty.options("items");
    oldDitty.destroy();
  }
  jQuery(dittyEl)[`ditty_${display.type}`](args);
  dittyEl.dataset.type = display.type;
};

export const updateDittyDisplayType = (dittyEl, displayType) => {
  if (!displayTypeExists(dittyEl, displayType)) return false;
  const prevType = dittyEl.dataset.type;
  if (prevType !== displayType) {
    const oldDitty = dittyEl[`_ditty_${prevType}`];
    const args = oldDitty ? oldDitty.options() : {};
    if (oldDitty) {
      oldDitty.destroy();
    }

    jQuery(dittyEl)[`ditty_${displayType}`](args);
    dittyEl.dataset.type = displayType;
  }
};

export const updateDisplayOptions = (dittyEl, option, value) => {
  const displayType = dittyEl.dataset.type;
  if (!displayTypeExists(dittyEl, displayType)) return false;
  dittyEl[`_ditty_${displayType}`].options(option, value);
};

/**
 * Get display items based on items and stored layouts
 * @param {array} items
 * @param {array} layouts
 * @param {function} returnDisplayItems
 */
export const getDisplayItems = async (items, layouts, returnData) => {
  const itemsArray = Array.isArray(items) ? items : [items];
  try {
    await getRenderedItems(itemsArray, layouts, (data) => {
      //returnDisplayItems && returnDisplayItems(data.display_items, "update");
      returnData && returnData(data);
    });
  } catch (ex) {
    const { dittyNotification } = dittyEditor.notifications;
    dittyNotification(ex, "error");
  }
};

export const addDisplayItems = (dittyEl, displayItems) => {
  const displayType = dittyEl.dataset.type;
  if (!displayTypeExists(dittyEl, displayType)) return false;
  displayItems.map((displayItem) =>
    dittyEl[`_ditty_${displayType}`].addItem(displayItem)
  );
};

/**
 * Delete an item from the Ditty display
 * @param {element} dittyEl
 * @param {object} item
 */
export const deleteDisplayItems = (dittyEl, item) => {
  const displayType = dittyEl.dataset.type;
  if (!displayTypeExists(dittyEl, displayType)) return false;
  dittyEl[`_ditty_${displayType}`].deleteItem(item.item_id);
};

/**
 * Update the Ditty display items
 * @param {element} dittyEl
 * @param {object} items
 */
// export const updateDisplayItems = (dittyEl, displayItems) => {
//   const displayType = dittyEl.dataset.type;
//   if (!displayTypeExists(dittyEl, displayType)) return false;
//   dittyEl["_ditty_" + displayType].loadItems(displayItems, "update");
// };

/**
 * Update the Ditty display items
 * @param {element} dittyEl
 * @param {object} items
 */
export const replaceDisplayItems = (dittyEl, displayItems) => {
  const displayType = dittyEl.dataset.type;
  if (!displayTypeExists(dittyEl, displayType)) return false;
  dittyEl[`_ditty_${displayType}`].loadItems(displayItems, "static");
};
