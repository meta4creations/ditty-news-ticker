import { getRenderedItems } from "./httpService";

export const initializeDitty = (dittyEl, displayType, args) => {
  jQuery(dittyEl)["ditty_" + displayType](args);
};

export const updateDittyDisplayTemplate = (dittyEl, display) => {
  const prevType = dittyEl.dataset.type;
  if (prevType === display.type) {
    dittyEl["_ditty_" + display.type].options(display.settings);
  } else {
    const oldDitty = dittyEl["_ditty_" + prevType];
    const args = display.settings;
    args["id"] = display.id;
    args["display"] = display.type;
    args["title"] = display.title;
    args["status"] = oldDitty.options("status");
    args["items"] = oldDitty.options("items");

    oldDitty.destroy();

    jQuery(dittyEl)["ditty_" + display.type](args);
    dittyEl.dataset.type = display.type;
  }
};

export const updateDittyDisplayType = (dittyEl, type) => {
  const prevType = dittyEl.dataset.type;
  if (prevType !== type) {
    const oldDitty = dittyEl["_ditty_" + prevType];
    const args = oldDitty.options();
    oldDitty.destroy();

    jQuery(dittyEl)["ditty_" + type](args);
    dittyEl.dataset.type = type;
  }
};

export const updateDisplayOptions = (dittyEl, option, value) => {
  const type = dittyEl.dataset.type;
  dittyEl["_ditty_" + type].options(option, value);
};

/**
 * Get display items based on items and stored layouts
 * @param {array} items
 * @param {array} layouts
 * @param {function} returnDisplayItems
 */
export const getDisplayItems = async (items, layouts, returnDisplayItems) => {
  const itemsArray = Array.isArray(items) ? items : [items];
  try {
    await getRenderedItems(itemsArray, layouts, (data) => {
      //returnDisplayItems && returnDisplayItems(data.display_items, "update");
      returnDisplayItems && returnDisplayItems(data.display_items);
    });
  } catch (ex) {
    console.log("catch", ex);
    if (ex.response && ex.response.status === 404) {
    }
  }
};

export const addDisplayItems = (dittyEl, displayItems) => {
  const type = dittyEl.dataset.type;
  displayItems.map((displayItem) =>
    dittyEl["_ditty_" + type].addItem(displayItem)
  );
};

/**
 * Delete an item from the Ditty display
 * @param {element} dittyEl
 * @param {object} item
 */
export const deleteDisplayItems = (dittyEl, item) => {
  const type = dittyEl.dataset.type;
  dittyEl["_ditty_" + type].deleteItem(item.item_id);
};

/**
 * Update the Ditty display items
 * @param {element} dittyEl
 * @param {object} items
 */
export const updateDisplayItems = (dittyEl, displayItems) => {
  const type = dittyEl.dataset.type;
  dittyEl["_ditty_" + type].loadItems(displayItems, "update");
};

/**
 * Update the Ditty display items
 * @param {element} dittyEl
 * @param {object} items
 */
export const replaceDisplayItems = (dittyEl, displayItems) => {
  const type = dittyEl.dataset.type;
  dittyEl["_ditty_" + type].loadItems(displayItems);
};
