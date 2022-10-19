import { __ } from "@wordpress/i18n";
import PanelItems from "./components/PanelItems";
import ItemSettings from "./components/items/ItemSettings";
import LayoutList from "./components/layouts/LayoutList";

/**
 * Merge in php item types
 */
window.dittyHooks.addFilter("dittyItemTypes", "dittyEditor", (itemTypes) => {
  const phpItemTypes = dittyEditorVars.itemTypes.reduce((filtered, phpType) => {
    const existingType = itemTypes.filter((type) => type.id === phpType.type);
    if (!existingType.length) {
      filtered.push({
        id: phpType.type,
        icon: <i className={phpType.icon}></i>,
        label: phpType.label,
        description: phpType.description,
      });
    }
    return filtered;
  }, []);
  if (phpItemTypes.length) {
    const updatedItemTypes = itemTypes.concat(phpItemTypes);
    console.log(updatedItemTypes);
    return updatedItemTypes;
  } else {
    return itemTypes;
  }
});

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
