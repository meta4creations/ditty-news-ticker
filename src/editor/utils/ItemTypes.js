import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPenToSquare, faPencil } from "@fortawesome/pro-light-svg-icons";
import { faWordpress } from "@fortawesome/free-brands-svg-icons";
import _ from "lodash";
import { migrateItemTypes } from "./migrate";

/**
 * Return all itemm types
 * @returns array
 */
export const getItemTypes = () => {
  const itemTypes = window.dittyHooks.applyFilters("dittyItemTypes", [
    {
      id: "default",
      icon: <FontAwesomeIcon icon={faPencil} />,
      label: __("Default", "ditty-news-ticker"),
      description: __("Manually add HTML to the item.", "ditty-news-ticker"),
    },
    {
      id: "wp_editor",
      icon: <FontAwesomeIcon icon={faPenToSquare} />,
      label: __("WP Editor", "ditty-news-ticker"),
      description: __(
        "Manually add wp editor content to the item.",
        "ditty-news-ticker"
      ),
    },
    {
      id: "posts_feed",
      icon: <FontAwesomeIcon icon={faWordpress} />,
      label: __("WP Posts Feed (Lite)", "ditty-news-ticker"),
      description: __("Add a WP Posts feed.", "ditty-news-ticker"),
    },
  ]);

  const migratedItemTypes = migrateItemTypes(itemTypes);
  const sortedItemTypes = _.orderBy(migratedItemTypes, ["label"], ["asc"]);
  return sortedItemTypes;
};

/**
 * Return an item types icon from item
 * @param {object} item
 * @returns element
 */
export const getItemTypeIcon = (item) => {
  const itemTypes = getItemTypes();
  const itemType = itemTypes.filter(
    (itemType) => itemType.id === item.item_type
  );
  return itemType.length ? (
    itemType[0].icon
  ) : (
    <FontAwesomeIcon icon={faPencil} />
  );
};

/**
 * Return the fields for an item type
 * @param {string} itemType
 * @returns object
 */
export const getItemTypeFields = (itemType) => {
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
