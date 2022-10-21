import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faList,
  faEllipsis,
  faTabletScreen,
} from "@fortawesome/pro-light-svg-icons";
import { faWordpress } from "@fortawesome/free-brands-svg-icons";
import _ from "lodash";
import { migrateDisplayTypes } from "../utils/Migrate";

/**
 * Return all displya types
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
 * Return the fields for an item type
 * @param {string} itemType
 * @returns object
 */
export const getDisplayTypeFields = (itemType) => {
  const itemTypeFields = window.dittyHooks.applyFilters("dittyItemTypeFields", [
    {
      id: "default",
      fields: [
        {
          type: "textarea",
          id: "content",
          name: __("Content", "ditty-news-ticker"),
          help: __(
            "Add the content of your item. HTML and inline styles are supported.",
            "ditty-news-ticker"
          ),
          std: __(
            "This is a sample item. Please edit me!",
            "ditty-news-ticker"
          ),
        },
        {
          type: "text",
          id: "link_url",
          name: __("Link", "ditty-news-ticker"),
          help: __(
            "Add a custom link to your content. You can also add a link directly into your content.",
            "ditty-news-ticker"
          ),
          atts: {
            type: "url",
          },
        },
        {
          type: "text",
          id: "link_title",
          name: __("Title", "ditty-news-ticker"),
          help: __("Add a title to the custom lnk.", "ditty-news-ticker"),
        },
        {
          type: "select",
          id: "link_target",
          name: __("Target", "ditty-news-ticker"),
          help: __("Set a target for your link.", "ditty-news-ticker"),
          options: {
            _self: "_self",
            _blank: "_blank",
          },
          std: "_self",
        },
        {
          type: "checkbox",
          id: "link_nofollow",
          name: __("No Follow", "ditty-news-ticker"),
          label: __('Add "nofollow" to link', "ditty-news-ticker"),
          help: __(
            "Enabling this setting will add an attribute called 'nofollow' to your link. This tells search engines to not follow this link.",
            "ditty-news-ticker"
          ),
        },
      ],
    },
    {
      id: "wp_editor",
      fields: [
        {
          type: "wysiwyg",
          id: "content",
          name: __("Content", "ditty-news-ticker"),
          help: __(
            "Add the content of your item. HTML and inline styles are supported.",
            "ditty-news-ticker"
          ),
        },
      ],
    },
    {
      id: "posts_feed",
      fields: [
        {
          type: "number",
          id: "limit",
          name: __("Limit", "ditty-news-ticker"),
          help: __("Set the number of Posts to display.", "ditty-news-ticker"),
        },
      ],
    },
  ]);
  const fields = itemTypeFields.filter((f) => f.id === itemType);
  return fields.length ? fields[0].fields : null;
};
