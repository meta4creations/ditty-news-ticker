import { getRenderedItems } from "./httpService";
import { displayTypeExists } from "../utils/helpers";

/**
 * @deprecated since 4.0 - Use v4 preview system with actions.refreshPreview()
 * Initialize Ditty display (v3 legacy)
 */
export const initializeDitty = (dittyEl, displayType, args) => {
  if (!displayTypeExists(dittyEl, displayType)) return false;
  jQuery(dittyEl)[`ditty_${displayType}`](args);
};

/**
 * @deprecated since 4.0 - Use v4 preview system with actions.refreshPreview()
 * Update Ditty display template (v3 legacy)
 */
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

/**
 * @deprecated since 4.0 - Use v4 preview system with actions.refreshPreview()
 * Update Ditty display type (v3 legacy)
 */
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

/**
 * @deprecated since 4.0 - Use v4 preview system with actions.refreshPreview()
 * Update display options (v3 legacy)
 */
export const updateDisplayOptions = (dittyEl, option, value) => {
  if (!dittyEl) return false;
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

/**
 * @deprecated since 4.0 - Use v4 preview system with actions.refreshPreview()
 * Add display items to Ditty (v3 legacy)
 */
export const addDisplayItems = (dittyEl, displayItems) => {
  if (!dittyEl) return false;
  const displayType = dittyEl.dataset.type;
  if (!displayTypeExists(dittyEl, displayType)) return false;
  displayItems.map((displayItem) =>
    dittyEl[`_ditty_${displayType}`].addItem(displayItem)
  );
};

/**
 * @deprecated since 4.0 - Use v4 preview system with actions.refreshPreview()
 * Delete an item from the Ditty display (v3 legacy)
 * @param {element} dittyEl
 * @param {object} item
 */
export const deleteDisplayItems = (dittyEl, item) => {
  if (!dittyEl) return false;
  const displayType = dittyEl.dataset.type;
  if (!displayTypeExists(dittyEl, displayType)) return false;
  dittyEl[`_ditty_${displayType}`].deleteItem(item.item_id);
};

/**
 * @deprecated since 4.0 - Use v4 preview system with actions.refreshPreview()
 * Replace the Ditty display items (v3 legacy)
 * @param {element} dittyEl
 * @param {object} items
 */
export const replaceDisplayItems = (dittyEl, displayItems) => {
  if (!dittyEl) return false;
  const displayType = dittyEl.dataset.type;
  if (!displayTypeExists(dittyEl, displayType)) return false;
  dittyEl[`_ditty_${displayType}`].loadItems(displayItems, "static");
};

/**
 * @deprecated since 4.0 - Use v4 preview system
 * Show a specific item (v3 legacy)
 */
export const showItem = (item) => {
  const dittyEl = document.getElementById("ditty-editor__ditty");
  if (!dittyEl) return false;
  const displayType = dittyEl.dataset.type;
  dittyEl[`_ditty_${displayType}`].showItem(item.item_id);
};
