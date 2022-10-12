import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import SortableList from "./common/SortableList";
import Item from "./Item";
import { EditorContext } from "./context";

const PanelItems = () => {
  const { items, actions } = useContext(EditorContext);

  /**
   * Render the editorItem icon
   */
  const handleRenderIcon = (item) => {
    return window.dittyHooks.applyFilters(
      "dittyEditorItemIcon",
      <i className="fas fa-pencil-alt"></i>,
      item
    );
  };

  /**
   * Render the editorItem label
   */
  const handleRenderLabel = (item) => {
    return window.dittyHooks.applyFilters(
      "dittyEditorItemLabel",
      item.item_type,
      item
    );
  };

  const handleItemClick = (e, item) => {
    console.log("target", e.target);
  };

  const handleElementClick = (e, elementId, item) => {
    if ("settings" == elementId) {
      console.log("item", item);
    }
  };

  /**
   * Pull data from sorted list items to update items
   * @param {array} sortedListItems
   */
  const handleSortEnd = (sortedListItems) => {
    const updatedItems = sortedListItems.map((item) => {
      return item.data;
    });
    actions.updateItems(updatedItems);
  };

  /**
   * Prepare the items for the sortable list
   * @returns {array}
   */
  const prepareItems = () => {
    return items.map((item, index) => {
      return {
        id: item.item_id,
        data: item,
        content: (
          <Item
            data={item}
            renderIcon={handleRenderIcon}
            renderLabel={handleRenderLabel}
            editable={true}
            onClick={handleItemClick}
            onElementClick={handleElementClick}
          />
        ),
      };
    });
  };

  return <SortableList items={prepareItems()} onSortEnd={handleSortEnd} />;
};
export default PanelItems;
