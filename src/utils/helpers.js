import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLink } from "@fortawesome/pro-light-svg-icons";

export const displayTypeExists = (dittyEl, displayType) => {
  if ("function" === typeof jQuery(dittyEl)["ditty_" + displayType]) {
    return true;
  } else {
    if (window.console) {
      console.log(
        __("Ditty Display type not loaded:", "ditty-news-ticker"),
        displayType
      );
    }
  }
};

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
  let styles = document.getElementById(`ditty-layout--${layoutId}`);
  if (!styles) {
    styles = document.createElement("style");
    styles.setAttribute("id", `ditty-layout--${layoutId}`);
    document.getElementsByTagName("head")[0].appendChild(styles);
  }
  layoutCss = layoutCss.replace("&gt;", ">");
  styles.innerHTML = layoutCss;
};

export const borderSettings = (prefix, namePrefix) => {
  const prefixed = prefix ? `${prefix}Border` : "border";
  const namePrefixed = namePrefix ? `${namePrefix} Border` : "Border";
  return [
    {
      id: `${prefixed}Color`,
      type: "color",
      name: __(`${namePrefixed} Color`, "ditty-news-ticker"),
      help: __("Choose a color for the border.", "ditty-news-ticker"),
    },
    {
      id: `${prefixed}Style`,
      type: "select",
      name: __(`${namePrefixed} Style`, "ditty-news-ticker"),
      help: __(
        "A border style must be set for a border to render.",
        "ditty-news-ticker"
      ),
      options: {
        none: "none",
        dotted: "dotted",
        dashed: "dashed",
        solid: "solid",
        double: "double",
        groove: "groove",
        ridge: "ridge",
        inset: "inset",
        outset: "outset",
        hidden: "hidden",
      },
    },
    {
      id: `${prefixed}Width`,
      type: "spacing",
      name: __(`${namePrefixed} Width`, "ditty-news-ticker"),
      help: __("Set custom border widths.", "ditty-news-ticker"),
      options: {
        borderTopWidth: __("Top", "ditty-news-ticker"),
        borderBottomWidth: __("Bottom", "ditty-news-ticker"),
        borderLeftWidth: __("Left", "ditty-news-ticker"),
        borderRightWidth: __("Right", "ditty-news-ticker"),
      },
      min: 0,
    },
    {
      id: `${prefixed}Radius`,
      type: "radius",
      name: __(`${namePrefixed} Radius`, "ditty-news-ticker"),
      help: __("Choose a custom border radius.", "ditty-news-ticker"),
      options: {
        borderTopLeftRadius: __("Top Left", "ditty-news-ticker"),
        borderTopRightRadius: __("Top Right", "ditty-news-ticker"),
        borderBottomLeftRadius: __("Bottom Left", "ditty-news-ticker"),
        borderBottomRightRadius: __("Bottom Right", "ditty-news-ticker"),
      },
      min: 0,
    },
  ];
};

export const linkFieldGroup = () => {
  return {
    id: "linkSettings",
    label: __("Links", "ditty-news-ticker"),
    name: __("Link Settings", "ditty-news-ticker"),
    description: __(
      "Configure global link settings for this items elements.",
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faLink} />,
    fields: linkSettings(),
  };
};

export const linkSettings = () => {
  return [
    {
      type: "select",
      id: "link_target",
      name: __("Link Target", "ditty-news-ticker"),
      help: __("Set a target for your links.", "ditty-news-ticker"),
      placeholder: __("Use layout settings", "ditty-news-ticker"),
      options: {
        _self: "_self",
        _blank: "_blank",
        _parent: "_parent",
        _top: "_top",
      },
    },
    {
      type: "checkbox",
      id: "link_nofollow",
      name: __("Link No Follow", "ditty-news-ticker"),
      label: __('Add "nofollow" to link', "ditty-news-ticker"),
      help: __(
        "Enabling this setting will add an attribute called 'nofollow' to your links. This tells search engines to not follow this link.",
        "ditty-news-ticker"
      ),
    },
  ];
};

export const titleSettings = (prefix) => {
  const prefixed = prefix ? `${prefix}Title` : "title";
  return [
    {
      id: `${prefixed}Display`,
      type: "select",
      name: __("Display", "ditty-news-ticker"),
      help: __("Select how to display the title", "ditty-news-ticker"),
      options: {
        none: __("None", "ditty-news-ticker"),
        top: __("Top", "ditty-news-ticker"),
        bottom: __("Bottom", "ditty-news-ticker"),
        left: __("Left", "ditty-news-ticker"),
        right: __("Right", "ditty-news-ticker"),
      },
    },
    {
      id: `${prefixed}ElementPosition`,
      type: "radio",
      name: __("Element Position", "ditty-news-ticker"),
      help: __(
        "Set the position of the element within the title area.",
        "ditty-news-ticker"
      ),
      options: {
        start: __("Start", "ditty-news-ticker"),
        center: __("Center", "ditty-news-ticker"),
        end: __("End", "ditty-news-ticker"),
      },
      inline: true,
    },
    {
      id: `${prefixed}Element`,
      type: "select",
      name: __("Element", "ditty-news-ticker"),
      help: __(
        "Select the HTML element to use for the title.",
        "ditty-news-ticker"
      ),
      options: {
        h1: "h1",
        h2: "h2",
        h3: "h3",
        h4: "h4",
        h5: "h5",
        h6: "h6",
        p: "p",
      },
    },
    {
      id: `${prefixed}FontSize`,
      type: "unit",
      name: __("Font Size", "ditty-news-ticker"),
      help: __("Set a custom font size.", "ditty-news-ticker"),
      min: 0,
    },
    {
      id: `${prefixed}LineHeight`,
      type: "unit",
      name: __("Line Height", "ditty-news-ticker"),
      help: __("Set a custom line height.", "ditty-news-ticker"),
      min: 0,
    },
    {
      id: `${prefixed}MaxWidth`,
      type: "unit",
      name: __("Max Width", "ditty-news-ticker"),
      help: __("Set a max width for the title area.", "ditty-news-ticker"),
      min: 0,
    },
    {
      id: `${prefixed}Color`,
      type: "color",
      name: __("Text Color", "ditty-news-ticker"),
      help: __("Set a custom font color.", "ditty-news-ticker"),
    },
    {
      id: `${prefixed}LinkColor`,
      type: "color",
      name: __("Link Color", "ditty-news-ticker"),
      help: __("Set a custom link color.", "ditty-news-ticker"),
    },
    {
      id: `${prefixed}BgColor`,
      type: "color",
      name: __("Background Color", "ditty-news-ticker"),
      help: __(
        "Add a background title to the title area.",
        "ditty-news-ticker"
      ),
    },
    {
      id: `${prefixed}Margin`,
      type: "spacing",
      name: __("Margin", "ditty-news-ticker"),
      help: __(
        "Add custom margins around the title area.",
        "ditty-news-ticker"
      ),
      options: {
        marginTop: __("Top", "ditty-news-ticker"),
        marginBottom: __("Bottom", "ditty-news-ticker"),
        marginLeft: __("Left", "ditty-news-ticker"),
        marginRight: __("Right", "ditty-news-ticker"),
      },
    },
    {
      id: `${prefixed}Padding`,
      type: "spacing",
      name: __("Padding", "ditty-news-ticker"),
      help: __(
        "Add custom padding around the title area.",
        "ditty-news-ticker"
      ),
      options: {
        paddingTop: __("Top", "ditty-news-ticker"),
        paddingBottom: __("Bottom", "ditty-news-ticker"),
        paddingLeft: __("Left", "ditty-news-ticker"),
        paddingRight: __("Right", "ditty-news-ticker"),
      },
      min: 0,
    },
    ...borderSettings(prefixed),
  ];
};
