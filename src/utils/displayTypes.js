const { applyFilters } = wp.hooks;
const { __ } = wp.i18n;
import _ from "lodash";
import { Icon } from "../components";
import { borderSettings, titleSettings } from "./helpers";

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
        const icon = phpType.icon;
        const iconType = phpType.iconType ? phpType.iconType : "fal";
        const iconObj = <Icon id={icon} type={iconType} />;
        filtered.push({
          id: phpType.type,
          icon: iconObj,
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
 * Return api display types
 * @returns array
 */
export const getAPIDisplayTypes = () => {
  const apiDisplayTypes = dittyEditorVars
    ? dittyEditorVars.apiDisplayTypes
    : [];
  if (!apiDisplayTypes) {
    return [];
  }
  const displayTypes = getDisplayTypes();

  let filteredTypes = apiDisplayTypes.filter(
    (apiDisplay) =>
      !displayTypes.some((display) => display.id === apiDisplay.id) ||
      displayTypes.some(
        (display) => display.id === apiDisplay.id && display.isLite
      )
  );

  // Append "_preview" to the id of each object
  filteredTypes = filteredTypes.map((obj) => {
    const icon = obj.icon;
    const iconType = obj.iconType ? obj.iconType : "fal";
    let iconObj = <Icon id={icon} type={iconType} />;
    return {
      ...obj,
      id: obj.id + "_preview",
      icon: iconObj,
      isPreview: true,
      className: "ditty-preview",
    };
  });

  return filteredTypes;
};

/**
 * Get the current display object
 * @returns object
 */
export const getDisplayObject = (display, displays) => {
  if (typeof display === "object") {
    if (!display.type) {
      display.type = "list";
    }
    if (!display.settings) {
      const displayTypeObject = getDisplayTypeObject(display.type);
      display.settings = displayTypeObject.defaultValues
        ? displayTypeObject.defaultValues
        : {};
    }
    return display;
  } else {
    const index = displays.findIndex((object) => {
      return Number(object.id) === Number(display);
    });
    if (index >= 0) {
      const displayObject = _.cloneDeep(displays[index]);
      return displayObject;
    }
    const displayTypeObject = getDisplayTypeObject("list");
    return {
      type: "list",
      settings: displayTypeObject.defaultValues
        ? displayTypeObject.defaultValues
        : {},
    };
  }
};

/**
 * Return a display type icon from the display
 * @param {object} display
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
 * @param {object} display
 * @returns element
 */
export const getDisplayTypeIcon = (display) => {
  const displayType = getDisplayTypeObject(display);
  return displayType ? displayType.icon : <Icon id="faTabletScreen" />;
};

/**
 * Return a display type label from the display
 * @param {object} display
 * @returns element
 */
export const getDisplayTypeLabel = (display) => {
  const displayType = getDisplayTypeObject(display);
  return displayType && displayType.label;
};

/**
 * Return a display type label from the display
 * @param {object} display
 * @returns element
 */
export const getDisplayTypeDescription = (display) => {
  const displayType = getDisplayTypeObject(display);
  return displayType && displayType.description;
};

/**
 * Return the fields for an display type
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
              applyFilters(
                "dittyEditor.displayTypeCustomSettings",
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
    description: __(
      `Configure the settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <Icon id="faSliders" />,
    fields: settings,
  };
};

const displaySettingsGeneral = (displayType) => {
  return {
    id: "general",
    label: __("General", "ditty-news-ticker"),
    name: __("General Settings", "ditty-news-ticker"),
    description: __(
      `Set the general settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <Icon id="faSliders" />,
    fields: applyFilters(
      "dittyEditor.displaySettingsGeneralFields",
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
    description: __(
      `Set the title settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <Icon id="faHeading" />,
    fields: applyFilters(
      "dittyEditor.displaySettingsTitleFields",
      [...titleSettings()],
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
    description: __(
      `Set the navigation settings of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <Icon id="faArrowsLeftRight" />,
    fields: groups.reduce((currentFields, group) => {
      switch (group) {
        case "arrows":
          return currentFields.concat([
            {
              id: "arrowSettings",
              type: "group",
              name: __("Arrow Settings", "ditty-news-ticker"),
              description: __(
                "Configure the arrow navigation settings.",
                "ditty-news-ticker"
              ),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: applyFilters(
                "dittyEditor.displaySettingsArrowFields",
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
                    gradient: true,
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
              description: __(
                "Configure the bullet navigation settings.",
                "ditty-news-ticker"
              ),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: applyFilters(
                "dittyEditor.displaySettingsBulletFields",
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
            applyFilters(
              "dittyEditor.displaySettingsNavigationCustomFields",
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
    description: __(
      `Set various element styles of the ${displayType}.`,
      "ditty-news-ticker"
    ),
    icon: <Icon id="faBrush" />,
    fields: groups.reduce((currentFields, group) => {
      switch (group) {
        case "container":
          return currentFields.concat([
            {
              id: "containerStyles",
              type: "group",
              name: __("Container Styles", "ditty-news-ticker"),
              description: __(
                "Add custom container styles.",
                "ditty-news-ticker"
              ),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: applyFilters(
                "dittyEditor.displaySettingsStylesContainerFields",
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
                    gradient: true,
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
              description: __(
                "Add custom content styles.",
                "ditty-news-ticker"
              ),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: applyFilters(
                "dittyEditor.displaySettingsStylesContentFields",
                [
                  {
                    type: "color",
                    id: "contentsBgColor",
                    gradient: true,
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
              description: __("Add custom page styles.", "ditty-news-ticker"),
              multipleFields: true,
              defaultState: "collapsed",
              collapsible: true,
              fields: applyFilters(
                "dittyEditor.displaySettingsStylesPageFields",
                [
                  {
                    type: "color",
                    id: "pageBgColor",
                    gradient: true,
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
              description: __("Add custom item styles.", "ditty-news-ticker"),
              multipleFields: true,
              collapsible: true,
              fields: applyFilters(
                "dittyEditor.displaySettingsStylesItemFields",
                [
                  {
                    type: "typography",
                    id: "itemTypography",
                    name: __("Item Typography", "ditty-news-ticker"),
                    help: __(
                      "Add base typography settings for the display items. Use layout customization to target specific elements or override these settings for individual items.",
                      "ditty-news-ticker"
                    ),
                  },
                  {
                    type: "color",
                    id: "itemTextColor",
                    name: __("Item Text Color", "ditty-news-ticker"),
                  },
                  {
                    type: "color",
                    id: "itemLinkColor",
                    name: __("Item Link Color", "ditty-news-ticker"),
                  },
                  {
                    type: "color",
                    id: "itemBgColor",
                    gradient: true,
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
            applyFilters(
              "dittyEditor.displaySettingsStylesCustomFields",
              [],
              group,
              displayType
            )
          );
      }
    }, []),
  };
};
