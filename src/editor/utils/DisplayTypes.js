import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faList,
  faEllipsis,
  faTabletScreen,
  faArrowsLeftRight,
  faPenToSquare,
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
    },
    {
      id: "list",
      icon: <FontAwesomeIcon icon={faList} />,
      label: __("List", "ditty-news-ticker"),
      description: __("Display items in a static list.", "ditty-news-ticker"),
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
export const getDisplayTypeIcon = (display) => {
  const displayTypes = getDisplayTypes();
  const displayType = displayTypes.filter(
    (displayType) => displayType.id === display.type
  );
  return displayType.length ? (
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
  const displayTypes = getDisplayTypes();
  const displayType = displayTypes.filter(
    (displayType) => displayType.id === display.type
  );
  return displayType.length && displayType[0].label;
};

/**
 * Return the fields for an item type
 * @param {string} itemType
 * @returns object
 */
export const getDisplayTypeFields = (displayType) => {
  const fields = window.dittyHooks.applyFilters(
    "dittyDisplayTypeFields",
    [],
    displayType
  );

  const modifiedFields = fields.map((field) => {
    let modifiedField = field;
    if (!field.fields) {
      switch (field.id) {
        case "arrows":
          modifiedField = arrowNavigationFields();
          break;
        case "bullets":
          modifiedField = bulletNavigationFields();
          break;
        default:
          break;
      }
    }
    if (!modifiedField.icon) {
      modifiedField.icon = <FontAwesomeIcon icon={faPenToSquare} />;
    }
    if (!modifiedField.label) {
      modifiedField.label = _.capitalize(field.id);
    }
    return modifiedField;
  });

  return modifiedFields;
};

/**
 * Return the arrow navigation fields
 * @returns object
 */
export const arrowNavigationFields = () => {
  const fields = {
    id: "arrows",
    icon: <FontAwesomeIcon icon={faArrowsLeftRight} />,
    label: __("Arrow Navigation", "ditty-news-ticker"),
    fields: [
      {
        type: "radio",
        id: "direction",
        name: __("Direction", "ditty-news-ticker"),
        help: __("Set the direction of the ticker.", "ditty-news-ticker"),
        options: {
          left: __("Left", "ditty-news-ticker"),
          right: __("Right", "ditty-news-ticker"),
          down: __("Down", "ditty-news-ticker"),
          up: __("Up", "ditty-news-ticker"),
        },
        inline: true,
      },
    ],
  };
  return fields;
};

/**
 * Return the arrow navigation fields
 * @returns object
 */
export const bulletNavigationFields = () => {
  const fields = {
    id: "bullets",
    icon: <FontAwesomeIcon icon={faEllipsis} />,
    label: __("Bullets Navigation", "ditty-news-ticker"),
    fields: [
      {
        type: "radio",
        id: "direction",
        name: __("Direction", "ditty-news-ticker"),
        help: __("Set the direction of the ticker.", "ditty-news-ticker"),
        options: {
          left: __("Left", "ditty-news-ticker"),
          right: __("Right", "ditty-news-ticker"),
          down: __("Down", "ditty-news-ticker"),
          up: __("Up", "ditty-news-ticker"),
        },
        inline: true,
      },
    ],
  };
  return fields;
};
