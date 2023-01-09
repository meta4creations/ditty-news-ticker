import { __ } from "@wordpress/i18n";

/**
 * Modify the item label
 */
dittyEditor.addFilter("itemLabel", (itemType, item) => {
  switch (itemType) {
    case "default":
      return item.item_value.content;
    case "posts_feed":
      return __("Posts Feed", "ditty-news-ticker");
    default:
      return "Add something here";
  }
});
