import { getItemTypeObject } from "../utils/itemTypes";
import {
  renderLayout,
  getLayoutObject,
  getDefaultLayout,
} from "../utils/layouts";

import axios from "axios";
const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

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

export const updateDittyItems = (dittyEl, items, layouts) => {
  console.log("items", items);
  const renderedItems = getRenderedItems(items, layouts);
  const type = dittyEl.dataset.type;
  console.log("renderedItems", renderedItems);
  dittyEl["_ditty_" + type].options("items", renderedItems);
};

const getVariationLayouts = (variations, layouts) => {
  const variationLayouts = [];
  for (const key in variations) {
    variationLayouts.push({
      variation: key,
      value: getLayoutObject(variations[key], layouts),
    });
  }
  return variationLayouts;
};

const getDisplayItems = (item, layouts) => {
  const itemTypeObject = getItemTypeObject(item.item_type);
  if (itemTypeObject.getDisplayItems) {
    const displayItems = itemTypeObject.getDisplayItems(item);
  }
  //console.log("itemTypeObject", itemTypeObject);
  // const variationLayouts = getVariationLayouts(item.layout_value, layouts);
  // const layoutData = variationLayouts.length
  //   ? variationLayouts[0].value
  //   : getDefaultLayout();
  // const dItems = item.display_items.map((dItem) => {
  //   const html = renderLayout(dItem, layoutData, itemTypeObject);
  //   return {
  //     id: dItem.item_id,
  //     uniq_id: dItem.item_uniq_id ? dItem.item_uniq_id : dItem.item_id,
  //     parent_id: 0,
  //     layout_id: layoutData.id ? layoutData.id : false,
  //     css: layoutData.css,
  //     html: html,
  //   };
  // });
  // return dItems;
};

export const getRenderedItems = (items, layouts) => {
  return items.reduce((items, item) => {
    const dItems = getDisplayItems(item, layouts);
    return items.concat(dItems);
  }, []);
};

export function getRenderedItemsAlt(items, layouts, onComplete) {
  const apiURL = `${apiEndpoint}/displayItems`;
  console.log("apiURL", apiURL);
  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    items: items,
    layouts: layouts,
  };
  console.log("apiData", apiData);
  return axios.post(apiURL, { apiData }).then((res) => {
    console.log("res.data", res.data);
    onComplete && onComplete(res.data);
  });
}
