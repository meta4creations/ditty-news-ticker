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

export const updateDittyItem = async (dittyEl, item, layouts) => {
  try {
    await getRenderedItems([item], layouts, (data) => {
      if (data.display_items) {
        const type = dittyEl.dataset.type;
        dittyEl["_ditty_" + type].updateItems(data.display_items, item.item_id);
      }
      //this.handleAfterSaveDitty(data, onComplete)
    });
  } catch (ex) {
    console.log("catch", ex);
    if (ex.response && ex.response.status === 404) {
    }
  }
};

export const addDittyItem = async (
  dittyEl,
  item,
  layouts,
  index = 0,
  onComplete
) => {
  try {
    await getRenderedItems([item], layouts, (data) => {
      if (data.display_items) {
        const type = dittyEl.dataset.type;
        console.log("data.display_items", data.display_items);
        data.display_items.map((displayItem) =>
          dittyEl["_ditty_" + type].addItem(displayItem)
        );
        onComplete && onComplete(data.display_items);
      }
    });
  } catch (ex) {
    console.log("catch", ex);
    if (ex.response && ex.response.status === 404) {
    }
  }
};
