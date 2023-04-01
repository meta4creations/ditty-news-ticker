import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faArrowsLeftRight,
  faTabletScreen,
  faSliders,
  faBrush,
  faHeading,
} from "@fortawesome/pro-light-svg-icons";

/**
 * Return all display types
 * @returns array
 */
export const getDisplayTypes = () => {
  const displayTypes = dittyEditor ? dittyEditor.displayTypes : [];
  const migratedDisplayTypes = migrateDisplayTypes(displayTypes);
  const sortedDisplayTypes = _.orderBy(
    migratedDisplayTypes,
    ["label"],
    ["asc"]
  );
  return sortedDisplayTypes;
};

/**
 * Migrate php display types
 * @param {array} displayTypes
 * @returns array
 */
const migrateDisplayTypes = (displayTypes) => {
  const phpDisplayTypes =
    dittyEditorVars.displayTypes &&
    dittyEditorVars.displayTypes.reduce((filtered, phpType) => {
      const existingType = displayTypes.filter(
        (type) => type.id === phpType.type
      );
      if (!existingType.length) {
        filtered.push({
          id: phpType.type,
          icon: <i className={phpType.icon}></i>,
          label: phpType.label,
          description: phpType.description,
          phpSettings: phpType.settings,
        });
      }
      return filtered;
    }, []);
  if (phpDisplayTypes && phpDisplayTypes.length) {
    const updatedDisplayTypes = displayTypes.concat(phpDisplayTypes);
    return updatedDisplayTypes;
  } else {
    return displayTypes;
  }
};

/**
 * Get the current display object
 * @returns object
 */
export const getDisplayObject = (display, displays) => {
  if (typeof display === "object") {
    return display;
  } else {
    const index = displays.findIndex((object) => {
      return Number(object.id) === Number(display);
    });
    if (index >= 0) {
      const displayObject = _.cloneDeep(displays[index]);
      return displayObject;
    }
    const displayTypeObject = getDisplayTypeObject("ticker");
    return {
      type: "ticker",
      settings: displayTypeObject.defaultValues
        ? displayTypeObject.defaultValues
        : {},
    };
  }
};

/**
 * Return a display type icon from the display
 * @param {object} item
 * @returns element
 */
export const getDisplayTypeObject = (display) => {
  const displayTypes = getDisplayTypes();
  const displayTypeObject = displayTypes.filter((displayType) => {
    if (typeof display === "object") {
      return displayType.id === display.type;
    } else {
      return displayType.id === display;
    }
  });
  return displayTypeObject.length ? displayTypeObject[0] : false;
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
 * @param {string} display
 * @returns object
 */
export const getDisplayTypeSettings = (display) => {
  const displayTypeObject = getDisplayTypeObject(display);
  const fieldGroups = [];
  if (displayTypeObject.phpSettings) {
    fieldGroups.push(
      phpDisplayTypeSettings(
        displayTypeObject.type,
        displayTypeObject.phpSettings
      )
    );
  } else {
    for (const key in displayTypeObject.settings) {
      if (
        typeof displayTypeObject.settings[key] === "object" &&
        !Array.isArray(displayTypeObject.settings[key])
      ) {
        fieldGroups.push(displayTypeObject.settings[key]);
      } else {
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
              dittyEditor.applyFilters(
                "getDisplayTypeSettingsCustom",
                [],
                displayTypeObject.id,
                key
              )
            );
            break;
        }
      }
    }
  }
  return fieldGroups;
};

const phpDisplayTypeSettings = (displayType, settings) => {
  return {
    id: "settings",
    label: __("Settings", "ditty-news-ticker"),
    name: __("Settings", "ditty-news-ticker"),
    desc: __(
      `Configure the settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faSliders} />,
    fields: settings,
  };
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
    fields: dittyEditor.applyFilters(
      "displaySettingsGeneralFields",
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
    fields: dittyEditor.applyFilters(
      "displaySettingsTitleFields",
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
          min: 0,
        },
        {
          id: "titleLineHeight",
          type: "unit",
          name: __("Line Height", "ditty-news-ticker"),
          help: __("Set a custom line height.", "ditty-news-ticker"),
          min: 0,
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
          min: 0,
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
              id: "arrowSettings",
              type: "group",
              name: __("Arrow Settings", "ditty-news-ticker"),
              desc: __(
                "Configure the arrow navigation settings.",
                "ditty-news-ticker"
              ),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: dittyEditor.applyFilters(
                "displaySettingsArrowFields",
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
                  },
                  {
                    type: "color",
                    id: "arrowsIconColor",
                    name: __("Arrows Icon Color", "ditty-news-ticker"),
                    help: __(
                      "Add a custom icon color to the arrows",
                      "ditty-news-ticker"
                    ),
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
                  },
                  {
                    type: "spacing",
                    id: "arrowsPadding",
                    name: __("Arrows Padding", "ditty-news-ticker"),
                    help: __(
                      "Add padding to the arrows container",
                      "ditty-news-ticker"
                    ),
                    min: 0,
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
                  },
                ],
                displayType
              ),
            },
          ]);
        case "bullets":
          return currentFields.concat([
            {
              id: "bulletSettings",
              type: "group",
              name: __("Bullet Settings", "ditty-news-ticker"),
              desc: __(
                "Configure the bullet navigation settings.",
                "ditty-news-ticker"
              ),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: dittyEditor.applyFilters(
                "displaySettingsBulletFields",
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
                  },
                  {
                    type: "color",
                    id: "bulletsColor",
                    name: __("Bullets Color", "ditty-news-ticker"),
                    help: __(
                      "Add a custom color to the bullets",
                      "ditty-news-ticker"
                    ),
                  },
                  {
                    type: "color",
                    id: "bulletsColorActive",
                    name: __("Bullets Active Color", "ditty-news-ticker"),
                    help: __(
                      "Add a custom color to the active bullet",
                      "ditty-news-ticker"
                    ),
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
                    type: "unit",
                    id: "bulletsSpacing",
                    name: __("Bullets Spacing", "ditty-news-ticker"),
                    help: __(
                      "Set the amount of space between bullets (in pixels).",
                      "ditty-news-ticker"
                    ),
                    min: 0,
                  },
                  {
                    type: "spacing",
                    id: "bulletsPadding",
                    name: __("Bullets Padding", "ditty-news-ticker"),
                    help: __(
                      "Add padding to the bullets container",
                      "ditty-news-ticker"
                    ),
                    min: 0,
                  },
                ],
                displayType
              ),
            },
          ]);
        default:
          return currentFields.concat(
            dittyEditor.applyFilters(
              "displaySettingsNavigationCustomFields",
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
    name: __("Style Settings", "ditty-news-ticker"),
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
              id: "containerStyles",
              type: "group",
              name: __("Container Styles", "ditty-news-ticker"),
              desc: __("Add custom container styles.", "ditty-news-ticker"),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: dittyEditor.applyFilters(
                "displaySettingsStylesContainerFields",
                [
                  {
                    type: "unit",
                    id: "maxWidth",
                    name: __("Container Max. Width", "ditty-news-ticker"),
                    help: __(
                      "Set a maximum width for the container",
                      "ditty-news-ticker"
                    ),
                    min: 0,
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
                    min: 0,
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
              id: "contentStyles",
              type: "group",
              name: __("Content Styles", "ditty-news-ticker"),
              desc: __("Add custom content styles.", "ditty-news-ticker"),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: dittyEditor.applyFilters(
                "displaySettingsStylesContentFields",
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
                    min: 0,
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
              id: "pageStyles",
              type: "group",
              name: __("Page Styles", "ditty-news-ticker"),
              desc: __("Add custom page styles.", "ditty-news-ticker"),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: dittyEditor.applyFilters(
                "displaySettingsStylesPageFields",
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
                    min: 0,
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
              id: "itemStyles",
              type: "group",
              name: __("Item Styles", "ditty-news-ticker"),
              desc: __("Add custom item styles.", "ditty-news-ticker"),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: dittyEditor.applyFilters(
                "displaySettingsStylesItemFields",
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
                    min: 0,
                  },
                  ...borderSettings("item", __("Item", "ditty-news-ticker")),
                ],
                displayType
              ),
            },
          ]);
        default:
          return currentFields.concat(
            dittyEditor.applyFilters(
              "displaySettingsStylesCustomFields",
              [],
              group,
              displayType
            )
          );
      }
    }, []),
  };
};
