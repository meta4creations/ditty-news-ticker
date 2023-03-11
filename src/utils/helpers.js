import { __ } from "@wordpress/i18n";

/**
 * Return easing options
 * @returns object
 */
const getEaseOptions = () => {
  const eases = [
    "linear",
    "swing",
    "jswing",
    "easeInQuad",
    "easeInCubic",
    "easeInQuart",
    "easeInQuint",
    "easeInSine",
    "easeInExpo",
    "easeInCirc",
    "easeInElastic",
    "easeInBack",
    "easeInBounce",
    "easeOutQuad",
    "easeOutCubic",
    "easeOutQuart",
    "easeOutQuint",
    "easeOutSine",
    "easeOutExpo",
    "easeOutCirc",
    "easeOutElastic",
    "easeOutBack",
    "easeOutBounce",
    "easeInOutQuad",
    "easeInOutCubic",
    "easeInOutQuart",
    "easeInOutQuint",
    "easeInOutSine",
    "easeInOutExpo",
    "easeInOutCirc",
    "easeInOutElastic",
    "easeInOutBack",
    "easeInOutBounce",
  ];
  const easeObject = {};
  for (let i = 0; i < eases.length; i++) {
    easeObject[eases[i]] = eases[i];
  }
  return easeObject;
};
export const easeOptions = getEaseOptions();

/**
 * Return the slider transition options
 * @returns object
 */
function getSliderTransitions() {
  return {
    fade: __("Fade", "ditty-news-ticker"),
    slideLeft: __("Slide Left", "ditty-news-ticker"),
    slideRight: __("Slide Right", "ditty-news-ticker"),
    slideDown: __("Slide Down", "ditty-news-ticker"),
    slideUp: __("Slide Up", "ditty-news-ticker"),
  };
}
export const sliderTransitions = getSliderTransitions();

/**
 * Convert default box controls to custom control keys
 * @returns object
 */
export const convertBoxControlValues = (values, args) => {
  const updatedValues = {};
  for (const [objKey, objValue] of Object.entries(args)) {
    updatedValues[objValue] = values[objKey];
  }
  return updatedValues;
};

/**
 * Convert default box controls to custom control keys
 * @returns object
 */
export const updatedDisplayItems = (prevItems, newItems, type = "replace") => {
  const prevGroupedItems = prevItems.reduce((items, item) => {
    const index = items.findIndex((i) => {
      return i.id === item.id;
    });
    if (index < 0) {
      items.push({
        id: item.id,
        items: [item],
      });
    } else {
      items[index].items.push(item);
    }
    return items;
  }, []);

  const newGroupedItems = newItems.reduce((items, item) => {
    const index = items.findIndex((i) => {
      return i.id === item.id;
    });
    item.updated = "updated";
    if (index < 0) {
      items.push({
        id: item.id,
        items: [item],
      });
    } else {
      items[index].items.push(item);
    }
    return items;
  }, []);

  let flattenedItems;
  if ("update" === type) {
    const updatedGroupedItems = newGroupedItems.reduce((groups, newItems) => {
      const index = groups.findIndex((group) => {
        return group.id === newItems.id;
      });
      if (index < 0) {
        groups.push(newItems);
      } else {
        groups[index] = newItems;
      }
      return groups;
    }, prevGroupedItems);
    flattenedItems = updatedGroupedItems.reduce((items, group) => {
      return [...items, ...group.items];
    }, []);
  } else {
    flattenedItems = newGroupedItems.reduce((items, group) => {
      return [...items, ...group.items];
    }, []);
  }
  const updatedIndexes = [];
  const updatedItems = flattenedItems.map((item, index) => {
    if (item.updated) {
      updatedIndexes.push(index);
      delete item.updated;
    } else if (typeof prevItems[index] === "undefined") {
      updatedIndexes.push(index);
    } else if (String(prevItems[index].uniq_id) !== String(item.uniq_id)) {
      updatedIndexes.push(index);
    }
    return item;
  });

  return {
    updatedItems: updatedItems,
    updatedIndexes: updatedIndexes,
  };
};

/**
 * Update item layout css
 *
 * @since    3.1
 * @return   null
 */
export const updateLayoutCss = (layoutCss, layoutId) => {
  let styles = document.getElementById(`style#ditty-layout--${layoutId}`);
  if (!styles) {
    styles = document.createElement("style");
    styles.setAttribute("id", `ditty-layout--${layoutId}`);
    document.getElementsByTagName("head")[0].appendChild(styles);
  }
  layoutCss = layoutCss.replace("&gt;", ">");
  console.log("layoutCss", layoutCss);
  styles.innerHTML = layoutCss;
};
