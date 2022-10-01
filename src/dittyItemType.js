import { __ } from "@wordpress/i18n";

/**
 * Modify the item icon
 */
window.dittyHooks.addFilter(
  "dittyEditorItemIcon",
  "dittyEditor",
  (icon, data) => {
    switch (data.item_type) {
      case "posts_feed":
        return <i className="fab fa-wordpress"></i>;
      default:
        return <i className="fas fa-pencil-alt"></i>;
    }
  }
);

/**
 * Modify the item label
 */
window.dittyHooks.addFilter(
  "dittyEditorItemLabel",
  "dittyEditor",
  (icon, data) => {
    switch (data.item_type) {
      case "posts_feed":
        return __("Posts Feed", "ditty-news-ticker");
      default:
        return "Add something here";
    }
  }
);

window.dittyHooks.addFilter(
  "dittyEditorItemElements",
  "dittyEditor",
  (elements) => {
    elements.push({
      id: "clone",
      content: <i className="fas fa-clone"></i>,
    });
    return elements;
  }
);
