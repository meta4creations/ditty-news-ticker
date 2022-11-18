import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faList,
  faEllipsis,
  faTabletScreen,
  faSliders,
  faBrush,
  faHeading,
} from "@fortawesome/pro-regular-svg-icons";
import { migrateDisplayTypes } from "./migrate";

/**
 * Return all display types
 * @returns array
 */
export const getDisplayTypes = () => {
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
        title: true,
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
    displayType[0].icon
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

const displaySettingsGeneral = (displayType) => {
  return {
    id: "general",
    label: __("General", "ditty-news-ticker"),
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
          id: "titleFontSize",
          type: "text",
          name: __("Font Size", "ditty-news-ticker"),
          help: __("Set a custom font size.", "ditty-news-ticker"),
        },
        {
          id: "titleLineHeight",
          type: "text",
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
      ],
      displayType
    ),
  };
};

const displaySettingsStyle = (
  displayType,
  groups = ["container", "content", "page", "item"]
) => {
  return {
    id: "styles",
    label: __("Styles", "ditty-news-ticker"),
    icon: <FontAwesomeIcon icon={faBrush} />,
    fields: groups.reduce((currentFields, group) => {
      switch (group) {
        case "container":
          return currentFields.concat(
            window.dittyHooks.applyFilters(
              "dittyDisplaySettingsStylesContainerFields",
              [
                {
                  type: "group",
                  name: __("Container", "ditty-news-ticker"),
                  desc: __("Add custom container styles.", "ditty-news-ticker"),
                  fields: [
                    {
                      type: "text",
                      id: "maxWidth",
                      name: __("Max. Width", "ditty-news-ticker"),
                      help: __(
                        "Set a maximum width for the container",
                        "ditty-news-ticker"
                      ),
                    },
                    {
                      type: "color",
                      id: "bgColor",
                      name: __("Background Color", "ditty-news-ticker"),
                    },
                    {
                      type: "spacing",
                      id: "padding",
                      name: __("Padding", "ditty-news-ticker"),
                    },
                    {
                      type: "spacing",
                      id: "margin",
                      name: __("Margin", "ditty-news-ticker"),
                      options: {
                        marginTop: __("Top", "ditty-news-ticker"),
                        marginBottom: __("Bottom", "ditty-news-ticker"),
                        marginLeft: __("Left", "ditty-news-ticker"),
                        marginRight: __("Right", "ditty-news-ticker"),
                      },
                    },
                    {
                      type: "border",
                      id: "border",
                      name: __("Border", "ditty-news-ticker"),
                    },
                  ],
                },
              ],
              displayType
            )
          );
        case "content":
          return currentFields.concat(
            window.dittyHooks.applyFilters(
              "dittyDisplaySettingsStylesContentFields",
              [
                {
                  type: "heading",
                  std: __("Content", "ditty-news-ticker"),
                  desc: __("Add custom content styles.", "ditty-news-ticker"),
                },
                {
                  type: "color",
                  id: "contentsBgColor",
                  name: __("Background Color", "ditty-news-ticker"),
                },
                {
                  type: "spacing",
                  id: "contentsPadding",
                  name: __("Padding", "ditty-news-ticker"),
                },
              ],
              displayType
            )
          );
        case "page":
          return currentFields.concat(
            window.dittyHooks.applyFilters(
              "dittyDisplaySettingsStylesPageFields",
              [
                {
                  type: "heading",
                  std: __("Page", "ditty-news-ticker"),
                  desc: __("Add custom page styles.", "ditty-news-ticker"),
                },
                {
                  type: "color",
                  id: "pageBgColor",
                  name: __("Background Color", "ditty-news-ticker"),
                },
                {
                  type: "spacing",
                  id: "pagePadding",
                  name: __("Padding", "ditty-news-ticker"),
                },
              ],
              displayType
            )
          );
        case "item":
          return currentFields.concat(
            window.dittyHooks.applyFilters(
              "dittyDisplaySettingsStylesItemFields",
              [
                {
                  type: "heading",
                  std: __("Item", "ditty-news-ticker"),
                  desc: __("Add custom item styles.", "ditty-news-ticker"),
                },
                {
                  type: "color",
                  id: "itemTextColor",
                  name: __("Text Color", "ditty-news-ticker"),
                },
                {
                  type: "color",
                  id: "itemBgColor",
                  name: __("Background Color", "ditty-news-ticker"),
                },
                {
                  type: "spacing",
                  id: "itemPadding",
                  name: __("Padding", "ditty-news-ticker"),
                },
              ],
              displayType
            )
          );
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
