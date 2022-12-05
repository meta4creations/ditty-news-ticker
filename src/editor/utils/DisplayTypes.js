import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faArrowsLeftRight,
  faList,
  faEllipsis,
  faTabletScreen,
  faSliders,
  faBrush,
  faHeading,
} from "@fortawesome/pro-light-svg-icons";
import { migrateDisplayTypes } from "./migrate";

/**
 * Return all display types
 * @returns array
 */
const getDisplayTypes = () => {
  const displayTypes = window.dittyHooks.applyFilters("dittyDisplayTypes", [
    {
      id: "ticker",
      icon: <FontAwesomeIcon icon={faEllipsis} />,
      label: __("Ticker", "ditty-news-ticker"),
      description: __(
        "Display items in a basic news ticker.",
        "ditty-news-ticker"
      ),
      settings: {
        general: true,
        title: true,
        styles: ["container", "content", "item"],
      },
    },
    {
      id: "list",
      icon: <FontAwesomeIcon icon={faList} />,
      label: __("List", "ditty-news-ticker"),
      description: __("Display items in a static list.", "ditty-news-ticker"),
      settings: {
        general: true,
        //title: true,
        navigation: ["arrows", "bullets"],
        styles: ["container", "content", "page", "item"],
      },
    },
  ]);

  const migratedDisplayTypes = migrateDisplayTypes(displayTypes);
  const sortedDisplayTypes = _.orderBy(
    migratedDisplayTypes,
    ["label"],
    ["asc"]
  );
  return sortedDisplayTypes;
};
export const displayTypes = getDisplayTypes();

/**
 * Get the current display object
 * @returns object
 */
export const getDisplayObject = (display, displays) => {
  if (typeof display === "object") {
    return display;
  } else {
    const filteredDisplays = displays.filter((d) => {
      return Number(d.id) === Number(display);
    });
    return filteredDisplays.length ? filteredDisplays[0] : {};
  }
};

/**
 * Return a display type icon from the display
 * @param {object} item
 * @returns element
 */
export const getDisplayTypeObject = (display) => {
  const displayTypes = getDisplayTypes();
  const displayType = displayTypes.filter((displayType) => {
    if (typeof display === "object") {
      return displayType.id === display.type;
    } else {
      return displayType.id === display;
    }
  });
  return displayType.length ? displayType[0] : false;
};

/**
 * Return a display type icon from the display
 * @param {object} item
 * @returns element
 */
export const getDisplayTypeIcon = (display) => {
  const displayType = getDisplayTypeObject(display);
  return displayType ? (
    displayType.icon
  ) : (
    <FontAwesomeIcon icon={faTabletScreen} />
  );
};

/**
 * Return a display type label from the display
 * @param {object} item
 * @returns element
 */
export const getDisplayTypeLabel = (display) => {
  const displayType = getDisplayTypeObject(display);
  return displayType && displayType.label;
};

/**
 * Return a display type label from the display
 * @param {object} item
 * @returns element
 */
export const getDisplayTypeDescription = (display) => {
  const displayType = getDisplayTypeObject(display);
  return displayType && displayType.description;
};

/**
 * Return the fields for an item type
 * @param {string} itemType
 * @returns object
 */
export const getDisplayTypeSettings = (display) => {
  const displayTypeObject = getDisplayTypeObject(display);
  const fieldGroups = [];
  for (const key in displayTypeObject.settings) {
    switch (key) {
      case "general":
        fieldGroups.push(displaySettingsGeneral(displayTypeObject.id));
        break;
      case "title":
        fieldGroups.push(displaySettingsTitle(displayTypeObject.id));
        break;
      case "navigation":
        fieldGroups.push(displaySettingsNavigation(displayTypeObject.id));
        break;
      case "styles":
        fieldGroups.push(
          displaySettingsStyle(
            displayTypeObject.id,
            displayTypeObject.settings[key]
          )
        );
        break;
      case "css":
        //fieldGroups.push(displaySettingsGeneral(displayTypeObject.id));
        break;
      default:
        fieldGroups.push(
          window.dittyHooks.applyFilters(
            "getDisplayTypeSettingsCustom",
            [],
            displayType
          )
        );
        break;
    }
  }
  return fieldGroups;
};

const borderSettings = (prefix, namePrefix) => {
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
    },
  ];
};

const displaySettingsGeneral = (displayType) => {
  return {
    id: "general",
    label: __("General", "ditty-news-ticker"),
    name: __("General Settings", "ditty-news-ticker"),
    desc: __(
      `Set the general settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faSliders} />,
    fields: window.dittyHooks.applyFilters(
      "dittyDisplaySettingsGeneralFields",
      [],
      displayType
    ),
  };
};

const displaySettingsTitle = (displayType) => {
  return {
    id: "title",
    label: __("Title", "ditty-news-ticker"),
    name: __("Title Settings", "ditty-news-ticker"),
    desc: __(
      `Set the title settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faHeading} />,
    fields: window.dittyHooks.applyFilters(
      "dittyDisplaySettingsTitleFields",
      [
        {
          id: "titleDisplay",
          type: "select",
          name: __("Display", "ditty-news-ticker"),
          help: __(
            "Show the Ditty title with your ticker.",
            "ditty-news-ticker"
          ),
          options: {
            none: __("None", "ditty-news-ticker"),
            top: __("Top", "ditty-news-ticker"),
            bottom: __("Bottom", "ditty-news-ticker"),
            left: __("Left", "ditty-news-ticker"),
            right: __("Right", "ditty-news-ticker"),
          },
        },
        {
          id: "titleElementPosition",
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
          id: "titleElement",
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
          id: "titleFontSize",
          type: "unit",
          name: __("Font Size", "ditty-news-ticker"),
          help: __("Set a custom font size.", "ditty-news-ticker"),
        },
        {
          id: "titleLineHeight",
          type: "unit",
          name: __("Line Height", "ditty-news-ticker"),
          help: __("Set a custom line height.", "ditty-news-ticker"),
        },
        {
          id: "titleColor",
          type: "color",
          name: __("Text Color", "ditty-news-ticker"),
          help: __("Set a custom font color.", "ditty-news-ticker"),
        },
        {
          id: "titleBgColor",
          type: "color",
          name: __("Background Color", "ditty-news-ticker"),
          help: __(
            "Add a background title to the title area.",
            "ditty-news-ticker"
          ),
        },
        {
          id: "titleMargin",
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
          id: "titlePadding",
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
        },
        ...borderSettings("title"),
      ],
      displayType
    ),
  };
};

const displaySettingsNavigation = (
  displayType,
  groups = ["arrows", "bullets"]
) => {
  return {
    id: "navigation",
    label: __("Navigation", "ditty-news-ticker"),
    name: __("Navigation Settings", "ditty-news-ticker"),
    desc: __(
      `Set the navigation settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faArrowsLeftRight} />,
    fields: groups.reduce((currentFields, group) => {
      switch (group) {
        case "arrows":
          return currentFields.concat([
            {
              type: "group",
              name: __("Arrow Settings", "ditty-news-ticker"),
              desc: __(
                "Configure the arrow navigation settings.",
                "ditty-news-ticker"
              ),
              fields: window.dittyHooks.applyFilters(
                "dittyDisplaySettingsArrowFields",
                [
                  {
                    type: "select",
                    id: "arrows",
                    name: __("Arrows", "ditty-news-ticker"),
                    help: __(
                      "Set the arrow navigation style",
                      "ditty-news-ticker"
                    ),
                    options: {
                      none: __("Hide", "ditty-news-ticker"),
                      style1: __("Show", "ditty-news-ticker"),
                    },
                    std: "style1",
                  },
                  {
                    type: "color",
                    id: "arrowsIconColor",
                    name: __("Arrows Icon Color", "ditty-news-ticker"),
                    help: __(
                      "Add a custom icon color to the arrows",
                      "ditty-news-ticker"
                    ),
                    std: "#777",
                  },
                  {
                    type: "color",
                    id: "arrowsBgColor",
                    name: __("Arrows Background Color", "ditty-news-ticker"),
                    help: __(
                      "Add a custom background color to the arrows",
                      "ditty-news-ticker"
                    ),
                  },
                  {
                    type: "select",
                    id: "arrowsPosition",
                    name: __("Arrows Position", "ditty-news-ticker"),
                    help: __(
                      "Set the position of the arrows",
                      "ditty-news-ticker"
                    ),
                    options: {
                      flexStart: __("Top", "ditty-news-ticker"),
                      center: __("Center", "ditty-news-ticker"),
                      flexEnd: __("Bottom", "ditty-news-ticker"),
                    },
                    std: "center",
                  },
                  {
                    type: "spacing",
                    id: "arrowsPadding",
                    name: __("Arrows Padding", "ditty-news-ticker"),
                    help: __(
                      "Add padding to the arrows container",
                      "ditty-news-ticker"
                    ),
                  },
                  {
                    type: "checkbox",
                    id: "arrowsStatic",
                    name: __("Arrows Visibility", "ditty-news-ticker"),
                    label: __(
                      "Keep arrows visible at all times",
                      "ditty-news-ticker"
                    ),
                    help: __(
                      "Keep arrows visible at all times",
                      "ditty-news-ticker"
                    ),
                    std: 1,
                  },
                ],
                displayType
              ),
            },
          ]);
        case "bullets":
          return currentFields.concat([
            {
              type: "group",
              name: __("Bullet Settings", "ditty-news-ticker"),
              desc: __(
                "Configure the bullet navigation settings.",
                "ditty-news-ticker"
              ),
              fields: window.dittyHooks.applyFilters(
                "dittyDisplaySettingsBulletFields",
                [
                  {
                    type: "select",
                    id: "bullets",
                    name: __("Bullets", "ditty-news-ticker"),
                    help: __(
                      "Set the bullet navigation style",
                      "ditty-news-ticker"
                    ),
                    options: {
                      none: __("Hide", "ditty-news-ticker"),
                      style1: __("Show", "ditty-news-ticker"),
                    },
                    std: "style1",
                  },
                  {
                    type: "color",
                    id: "bulletsColor",
                    name: __("Bullets Color", "ditty-news-ticker"),
                    help: __(
                      "Add a custom color to the bullets",
                      "ditty-news-ticker"
                    ),
                    std: "#777",
                  },
                  {
                    type: "color",
                    id: "bulletsColorActive",
                    name: __("Bullets Active Color", "ditty-news-ticker"),
                    help: __(
                      "Add a custom color to the active bullet",
                      "ditty-news-ticker"
                    ),
                    std: "#000",
                  },
                  {
                    type: "select",
                    id: "bulletsPosition",
                    name: __("Bullets Position", "ditty-news-ticker"),
                    help: __(
                      "Set the position of the bullets",
                      "ditty-news-ticker"
                    ),
                    options: {
                      topLeft: __("Top Left", "ditty-news-ticker"),
                      topCenter: __("Top Center", "ditty-news-ticker"),
                      topRight: __("Top Right", "ditty-news-ticker"),
                      bottomLeft: __("Bottom Left", "ditty-news-ticker"),
                      bottomCenter: __("Bottom Center", "ditty-news-ticker"),
                      bottomRight: __("Bottom Right", "ditty-news-ticker"),
                    },
                    std: "bottomCenter",
                  },
                  {
                    type: "slider",
                    id: "bulletsSpacing",
                    name: __("Bullets Spacing", "ditty-news-ticker"),
                    help: __(
                      "Set the amount of space between bullets (in pixels).",
                      "ditty-news-ticker"
                    ),
                    suffix: "px",
                    min: 0,
                    max: 50,
                    step: 1,
                    std: "5",
                  },
                  {
                    type: "spacing",
                    id: "bulletsPadding",
                    name: __("Bullets Padding", "ditty-news-ticker"),
                    help: __(
                      "Add padding to the bullets container",
                      "ditty-news-ticker"
                    ),
                  },
                ],
                displayType
              ),
            },
          ]);
        default:
          return currentFields.concat(
            window.dittyHooks.applyFilters(
              "dittyDisplaySettingsNavigationCustomFields",
              [],
              group,
              displayType
            )
          );
      }
    }, []),
  };
};

const displaySettingsStyle = (
  displayType,
  groups = ["container", "content", "page", "item"]
) => {
  return {
    id: "styles",
    label: __("Styles", "ditty-news-ticker"),
    name: __("Styles Settings", "ditty-news-ticker"),
    desc: __(
      `Set various element styles of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faBrush} />,
    fields: groups.reduce((currentFields, group) => {
      switch (group) {
        case "container":
          return currentFields.concat([
            {
              type: "group",
              name: __("Container Styles", "ditty-news-ticker"),
              desc: __("Add custom container styles.", "ditty-news-ticker"),
              fields: window.dittyHooks.applyFilters(
                "dittyDisplaySettingsStylesContainerFields",
                [
                  {
                    type: "unit",
                    id: "maxWidth",
                    name: __("Container Max. Width", "ditty-news-ticker"),
                    help: __(
                      "Set a maximum width for the container",
                      "ditty-news-ticker"
                    ),
                  },
                  {
                    type: "color",
                    id: "bgColor",
                    name: __("Container Background Color", "ditty-news-ticker"),
                  },
                  {
                    type: "spacing",
                    id: "padding",
                    name: __("Container Padding", "ditty-news-ticker"),
                  },
                  {
                    type: "spacing",
                    id: "margin",
                    name: __("Container Margin", "ditty-news-ticker"),
                    options: {
                      marginTop: __("Top", "ditty-news-ticker"),
                      marginBottom: __("Bottom", "ditty-news-ticker"),
                      marginLeft: __("Left", "ditty-news-ticker"),
                      marginRight: __("Right", "ditty-news-ticker"),
                    },
                  },
                  ...borderSettings("", __("Container", "ditty-news-ticker")),
                ],
                displayType
              ),
            },
          ]);
        case "content":
          return currentFields.concat([
            {
              type: "group",
              name: __("Content Styles", "ditty-news-ticker"),
              desc: __("Add custom content styles.", "ditty-news-ticker"),
              fields: window.dittyHooks.applyFilters(
                "dittyDisplaySettingsStylesContentFields",
                [
                  {
                    type: "color",
                    id: "contentsBgColor",
                    name: __("Content Background Color", "ditty-news-ticker"),
                  },
                  {
                    type: "spacing",
                    id: "contentsPadding",
                    name: __("Content Padding", "ditty-news-ticker"),
                  },
                  ...borderSettings(
                    "contents",
                    __("Content", "ditty-news-ticker")
                  ),
                ],
                displayType
              ),
            },
          ]);
        case "page":
          return currentFields.concat([
            {
              type: "group",
              name: __("Page Styles", "ditty-news-ticker"),
              desc: __("Add custom page styles.", "ditty-news-ticker"),
              fields: window.dittyHooks.applyFilters(
                "dittyDisplaySettingsStylesPageFields",
                [
                  {
                    type: "color",
                    id: "pageBgColor",
                    name: __("Page Background Color", "ditty-news-ticker"),
                  },
                  {
                    type: "spacing",
                    id: "pagePadding",
                    name: __("Page Padding", "ditty-news-ticker"),
                  },
                  ...borderSettings("page", __("Page", "ditty-news-ticker")),
                ],
                displayType
              ),
            },
          ]);
        case "item":
          return currentFields.concat([
            {
              type: "group",
              name: __("Item Styles", "ditty-news-ticker"),
              desc: __("Add custom item styles.", "ditty-news-ticker"),
              fields: window.dittyHooks.applyFilters(
                "dittyDisplaySettingsStylesItemFields",
                [
                  {
                    type: "color",
                    id: "itemTextColor",
                    name: __("Item Text Color", "ditty-news-ticker"),
                  },
                  {
                    type: "color",
                    id: "itemBgColor",
                    name: __("Item Background Color", "ditty-news-ticker"),
                  },
                  {
                    type: "spacing",
                    id: "itemPadding",
                    name: __("Item Padding", "ditty-news-ticker"),
                  },
                  ...borderSettings("item", __("Item", "ditty-news-ticker")),
                ],
                displayType
              ),
            },
          ]);
        default:
          return currentFields.concat(
            window.dittyHooks.applyFilters(
              "dittyDisplaySettingsStylesCustomFields",
              [],
              group,
              displayType
            )
          );
      }
    }, []),
  };
};
