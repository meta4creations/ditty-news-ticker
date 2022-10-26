import { __ } from "@wordpress/i18n";
import PanelItems from "../editor/components/PanelItems";
import ItemSettings from "../editor/components/items/ItemSettings";
import LayoutList from "../editor/components/layouts/LayoutList";

/**
 * Render the Items panel
 */
window.dittyHooks.addFilter(
  "dittyEditorPanel",
  "dittyEditor",
  (panel, tabId, context) => {
    if ("items" === tabId) {
      return <PanelItems editor={context} />;
    }
    return panel;
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
 * Render the Items Edit panel
 */
window.dittyHooks.addFilter(
  "dittyItemEditPanel",
  "dittyEditor",
  (panel, tabId, item, editor) => {
    switch (tabId) {
      case "settings":
        return <ItemSettings item={item} editor={editor} />;
      case "layout":
        return <LayoutList item={item} editor={editor} />;
      default:
        return panel;
    }
  }
);
