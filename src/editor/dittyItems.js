import { __ } from "@wordpress/i18n";
import PanelItems from "./components/PanelItems";

/**
 * Modify the display icon
 */
window.dittyHooks.addFilter(
  "dittyEditorPanel",
  "dittyEditor",
  (panel, panelId, context) => {
    if ("items" === panelId) {
      return <PanelItems editor={context} />;
    }
    return panel;
  }
);

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
      case "default":
        return data.item_value.content;
      case "posts_feed":
        return __("Posts Feed", "ditty-news-ticker");
      default:
        return "Add something here";
    }
  }
);

/**
 * Modify the display icon
 */
window.dittyHooks.addFilter(
  "dittyEditorDisplayIcon",
  "dittyEditor",
  (icon, data) => {
    switch (data.type) {
      case "list":
        return <i className="fas fa-list"></i>;
      case "ticker":
        return <i className="fas fa-ellipsis-h"></i>;
      default:
        return <i className="fas fa-tablet-alt"></i>;
    }
  }
);
